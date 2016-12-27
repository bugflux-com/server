<?php

namespace App\Http\Controllers\Project;

use App\Events\NewPrivilegeGiven;
use App\Events\ChangedPrivilege;
use App\Models\Group;
use App\Models\NotificationType;
use App\Models\Permission;
use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;
use App\Services\Models\PermissionService;
use App\Services\UserService;
use Illuminate\Http\Request;
use DB;


use App\Http\Requests;
use Illuminate\Mail\Message;
use Mail;
use Password;
use Storage;
use URL;

class PermissionController extends Controller
{
    private $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function index($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('readAny', [Permission::class, $project]);

        $permissions = $project->permissions()
            ->with('group', 'user')->paginate();

        return view('projects.permissions.index', compact('project', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [Permission::class, $project]);
        $groups = Group::all()->pluck('id', 'name');

        return view('projects.permissions.create', compact('project', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserService $service
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [Permission::class, $project]);
        $this->validate($request, [
            'email' => 'required|email',
            'group_id' => 'required|exists:groups,id'
        ]);

        $this->service->create($request, $project_id);

        return redirect()->route('projects.permissions.index', [$project_id])
            ->with('success', 'User permission has been added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $project_id, $id)
    {
        $project = Project::findOrFail($project_id);
        $permission = $project->permissions()->findOrFail($id);
        $this->authorize('update', $permission);

        $groups = Group::all()->pluck('id', 'name');
        $previous_url = $request->input('previous_url', route('projects.permissions.index', $project_id));

        return view('projects.permissions.edit', compact('project', 'permission', 'groups', 'previous_url'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $id)
    {
        $permission = Permission::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('update', $permission);
        $this->validate($request, [
            'group_id' => 'required|exists:groups,id',
            'previous_url' => 'required|url'
        ]);

        $permission->fill($request->only('group_id'))->saveOrFail();

        event(new ChangedPrivilege($permission));

        return redirect()->to($request->previous_url)
            ->with('success', 'User permission has been changed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        $permission = Permission::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('delete', $permission);

        // TODO: Add password confirmation?
        DB::transaction(function () use ($permission) {
            $permission->delete();

            $notificationType = NotificationType::where('code', 'new_error')->firstOrFail();
            $permission->user->notificationTypes()
                ->newPivotStatementForId($notificationType->id)
                ->where('wantable_id', $permission->project_id)
                ->delete();
        });

        return redirect()->back()
            ->with('success', 'User permission has been removed.');
    }
}
