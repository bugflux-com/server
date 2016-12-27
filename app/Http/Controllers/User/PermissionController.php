<?php

namespace App\Http\Controllers\User;

use App\Events\NewPrivilegeGiven;
use App\Models\Group;
use App\Models\NotificationType;
use App\Models\Permission;
use App\Models\Project;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $user = User::findOrFail($user_id);
        $this->authorize('read', $user);

        $permissions = $user->permissions()
            ->with('group', 'project')->paginate();

        return view('users.permissions.index', compact('user', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id)
    {
        $user = User::findOrFail($user_id);
        $this->authorize('createAnyPermission', Permission::class);

        $groups = Group::all()->pluck('id', 'name');
        $projects = Project::all()->pluck('id', 'name');

        return view('users.permissions.create', compact('user', 'groups', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $this->authorize('createAnyPermission', Permission::class);
        $this->validate($request, [
            'group_id' => 'required|exists:groups,id',
            'project_id' => 'required|exists:projects,id'
        ]);

        // Allow only one permission per project.
        $permission = Permission::where('project_id', $request->project_id)
            ->where('user_id', $user->id)->first();

        if (!empty($permission)) {
            return redirect()->route('users.permissions.index', $user->id)
                ->with('failure', 'This user has got the permission for given project');
        }

        // Assign permission.
        $request->merge(['user_id' => $user->id]);

        \DB::transaction(function () use ($request, $user) {
            $permission = Permission::create($request->only('user_id', 'group_id', 'project_id'));

            // at default user after being added to project wants new errors
            $notificationType = NotificationType::where('code', 'new_error')->firstOrFail();
            $user->notificationTypes()->attach($notificationType->id, [
                'wantable_id' => $permission->project_id,
                'wantable_type' => array_search(Project::class, AppServiceProvider::$morphMap),
                'internal' => true,
                'email' => true,
            ]);

            event(new NewPrivilegeGiven($permission));
        });

        return redirect()->route('users.permissions.index', $user->id)
            ->with('success', 'User permission has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $user_id, $id)
    {
        $user = User::findOrFail($user_id);
        $permission = Permission::where('user_id', $user_id)->findOrFail($id);
        $this->authorize('update', $permission);

        $groups = Group::all()->pluck('id', 'name');
        $previous_url = $request->input('previous_url', route('users.permissions.index', $user_id));

        return view('users.permissions.edit', compact('user', 'permission', 'groups', 'previous_url'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $id)
    {
        //
    }
}
