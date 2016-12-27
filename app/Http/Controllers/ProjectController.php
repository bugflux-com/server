<?php

namespace App\Http\Controllers;

use App\Models\Mapping;
use App\Models\Project;
use App\Services\Models\ErrorService;
use App\Services\Models\ProjectService;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProjectController extends Controller
{
    private $service;
    private $errorService;

    /**
     * ProjectController constructor.
     * @param ProjectService $service
     * @param ErrorService $errorService
     */
    public function __construct(ProjectService $service, ErrorService $errorService)
    {
        $this->service = $service;
        $this->errorService = $errorService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('readAny', Project::class);
        $projects = Project::withCount('errors', 'reports')
            ->availableForCurrentUser()
            ->orderBy('name')
            ->paginate();
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('createAny', Project::class);

        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('createAny', Project::class);
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        $project = $this->service->create($request);

        return redirect()->route('projects.permissions.index', $project->id)
            ->with('success', 'Project has been created. Assign a project manager who will manage this project.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trend_for_days = 30;
        $project = Project::withReportsTrend($trend_for_days)
            ->WithReportsPerVersion(3)
            ->WithReportsPerSystem(3)
            ->WithReportsPerLanguage(3)
            ->findOrFail($id);
        $this->authorize('read', $project);

        return view('projects.show', compact('project', 'trend_for_days'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        $project->fill($request->only('name'))->saveOrFail();

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Project name has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('delete', $project);

        DB::transaction(function() use($project) {
            $this->errorService->deleteDependencies($project->errors()->get()->all());
            $project->delete();
        });

        return redirect()->route('projects.index')
            ->with('success', 'Project has been deleted.');
    }
}
