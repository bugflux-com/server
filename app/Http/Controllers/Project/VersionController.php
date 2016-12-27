<?php

namespace App\Http\Controllers\Project;

use App\Events\NewPrivilegeGiven;
use App\Events\ChangedPrivilege;
use App\Models\Group;
use App\Models\NotificationType;
use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\Version;
use App\Services\UserService;
use Illuminate\Http\Request;
use DB;


use App\Http\Requests;
use Illuminate\Mail\Message;
use Mail;
use Password;
use Storage;
use URL;

class VersionController extends Controller
{

    public function __construct()
    {
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
        $this->authorize('readAny', [Version::class, $project]);

        $versions = $project->versions()->latest()->paginate();

        return view('projects.versions.index', compact('project', 'versions'));
    }

    /**
     * Show the form for creating a new resource.
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [Version::class, $project]);

        return view('projects.versions.create', compact('project'));
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
        $this->authorize('createAny', [Version::class, $project]);
        $this->validate($request, [
            'name' => 'required|string|unique:versions,name,NULL,id,project_id,'.$project_id //TODO Regex??
        ]);

        $request->merge(['project_id' => $project_id]);
        Version::create($request->only('name', 'project_id'));

        return redirect()->route('projects.versions.index', [$project_id])
            ->with('success', 'Version has been added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $id)
    {
        $version = Version::with('project')
            ->where('project_id', $project_id)
            ->findOrFail($id);
        $this->authorize('update', $version);

        $project = $version->project;
        return view('projects.versions.edit', compact('project', 'version'));
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
        $version = Version::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('update', $version);
        $this->validate($request, [
            'name' => "required|string|unique:versions,name,$id,id,project_id,$project_id" //TODO Regex??
        ]);

        $version->fill($request->only('name'))->saveOrFail();

        return redirect()->route('projects.versions.index', [$project_id])
            ->with('success', 'Vesion has been updated.');
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
        $version = Version::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('delete', $version);

        // TODO: Add password confirmation?
        $version->delete();

        return redirect()->back()
            ->with('success', 'Version has been deleted.');
    }
}
