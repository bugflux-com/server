<?php


namespace App\Services\Models;


use App\Events\NewPrivilegeGiven;
use App\Models\NotificationType;
use App\Models\Permission;
use App\Models\Project;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Services\UserService;
use Illuminate\Http\Request;

class PermissionService
{
    private $userService;

    /**
     * PermissionService constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create(Request $request, $project_id) {
        $user = User::where('email', $request->only('email'))->first();

        // Create user if not exists
        // (common case when we're adding client to the project).
        if (empty($user)) {
            // Random string that user will never guess
            // (nullable password column can not be used).
            $request->merge(['password' => str_random(64)]);

            $user = User::create($request->only(['email', 'password']));
            $this->userService->sendWelcomeMail($request->email);
        }

        // Allow only one permission per project.
        $permission = Permission::where('project_id', $project_id)
            ->where('user_id', $user->id)->first();

        if (!empty($permission)) {
            return redirect()->route('projects.permissions.index', [$project_id])
                ->with('failure', 'This user has got the permission for given project');
        }

        // Assign permission.
        $request->merge(['user_id' => $user->id]);
        $request->merge(['project_id' => $project_id]);

        return \DB::transaction(function () use ($request, $user) {
            $permission = Permission::create($request->only('user_id', 'group_id', 'project_id'));

            // At default user after being added to project wants new errors
            $notificationType = NotificationType::where('code', 'new_error')->firstOrFail();
            $user->notificationTypes()->syncWithoutDetaching([$notificationType->id], [
                'wantable_id' => $request->project_id,
                'wantable_type' => array_search(Project::class, AppServiceProvider::$morphMap),
                'internal' => true,
                'email' => true,
            ]);

            event(new NewPrivilegeGiven($permission));

            return $permission;
        });
    }
}