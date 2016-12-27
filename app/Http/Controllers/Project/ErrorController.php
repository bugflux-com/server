<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Error;
use App\Models\ErrorDuplicate;
use App\Models\NotificationType;
use App\Models\NotificationTypeUser;
use App\Models\Project;
use App\Models\Tag;
use App\Providers\AppServiceProvider;
use App\Services\Models\ErrorService;
use App\Services\SortService;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ErrorController extends Controller
{
    private $sort_default = 'last_seen';
    private $sort_columns = [
        'id' => 'id',
        'name' => 'name',
        //'environment' => 'environments.id',
        'total' => 'reports_count',
        'first_seen' => 'created_at',
        'last_seen' => 'updated_at',
        //'users' => 'clients_count',
    ];

    /**
     * Possible sort columns labels
     *
     * @var array
     */
    protected $sort_column_names = [
        'Id' => 'id',
        'Name' => 'name',
        'Total' => 'total',
        //'First seen' => 'first_seen',
        'Last seen' => 'last_seen',
    ];

    /**
     * Possible sort direction labels
     *
     * @var array
     */
    protected $sort_direction_names = [
        'A - Z' => 'asc',
        'Z - A' => 'desc',
    ];


    private $filter_env_default = 'all';
    private $filter_env_names = [
        'dev' => 'Development',
        'prod' => 'Production',
        'test' => 'Testing',
        'all' => null,
    ];

    private $filter_env_view_names = [
        'Development' => 'dev',
        'Production' => 'prod',
        'Testing' => 'test',
        'All environments' => 'all',
    ];

    private $errorService;

    /**
     * ErrorController constructor.
     * @param ErrorService $errorService
     */
    public function __construct(ErrorService $errorService)
    {
        $this->middleware('auth');
        $this->errorService = $errorService;
    }

    private function get_tags_names_ids($project)
    {
        $tags = $project->tags()->get()->all();
        $names = array_merge(['With or without tags', 'Only with tags', 'Only without tags'], array_map(function($o) { return $o->name; }, $tags));
        $ids = array_merge([-1, -2, -3], array_map(function($o) { return $o->id; }, $tags));
        return array_combine($names, $ids);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $project_id)
    {
        $trend_for_days = 7;

        $project = Project::findOrFail($project_id);
        $this->authorize('readAny', [Error::class, $project]);

        $tags_names_ids = $this->get_tags_names_ids($project);

        $this->validate($request, [
            'sort_column' => 'string|in:'.implode(',', array_keys($this->sort_columns)),
            'sort_direction' => 'string|in:asc,desc',
            'filter_env' => 'string|in:'.implode(',', array_keys($this->filter_env_names)),
            'tag' => 'string|in:'.implode(',', array_values($tags_names_ids)),
        ]);

        $sort_col_request = $request->query('sort_column', $this->sort_default);
        $sort_col = $this->sort_columns[$sort_col_request];
        $sort_dir = $request->query('sort_direction', 'desc');
        $tag = $request->query('tag', -1);

        $env_filter_request = $request->query('filter_env', $this->filter_env_default);
        $env_filter = $this->filter_env_names[$env_filter_request];

        $errors = $project->errors()
            ->withCount('reports', 'comments')
            ->with(['project', 'clientsCount','environment','tags'])
            ->withReportsTrend($trend_for_days)
            ->filteredEnvironment($env_filter)
            ->filteredTag($tag)
            ->orderBy($sort_col, $sort_dir)
            ->paginate();

        $notifications = Auth::user()->notificationTypes()->wherePivot('wantable_id', $project_id)
            ->firstOrNew(['code' => 'new_error']);

        $sort_column_names = $this->sort_column_names;
        $sort_direction_names = $this->sort_direction_names;
        $filter_env_view_names = $this->filter_env_view_names;

        $errors->appends([
            'sort_column' => $sort_col_request,
            'sort_derection' => $sort_dir,
            'filter_env' => $env_filter_request
        ]);

        return view('projects.errors.index', compact('project', 'errors', 'trend_for_days', 'notifications',
            'sort_column_names', 'sort_direction_names', 'sort_col_request', 'sort_dir',
            'filter_env_view_names', 'env_filter_request', 'tags_names_ids', 'tag'));
    }

    /**
     * Display the specified resource.
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $id)
    {
        $error = Error::with(['project','environment','tags'])
            ->where('project_id', $project_id)
            ->findOrFail($id);
        $project = $error->project;
        $this->authorize('read', $error);

        $tags =  Tag::doesntHave('errors', 'and', function($q) use ($id, $project_id) {
            $q->where('error_id' , $id);
        })->where('project_id', $project_id)
        ->pluck('id', 'name');

        return view('projects.errors.show', compact('tags', 'project', 'error'));
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
        $error = Error::with('project')
            ->where('project_id', $project_id)
            ->findOrFail($id);
        $project = $error->project;
        $this->authorize('update', $error);

        return view('projects.errors.edit', compact('project', 'error'));
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
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        $error = Error::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('update', $error);
        $error->fill($request->only('name'))->saveOrFail();

        return redirect()->route('projects.errors.show', [$error->project_id, $error->id])
            ->with('success', 'Error has been updated.');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * If all reports that belongs to the error are deleted
     * then remove also the parent resource (error).
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        DB::transaction(function() use($project_id, $id) {
            $error = Error::where('project_id', $project_id)
                ->findOrFail($id);
            $this->authorize('delete', $error);

            $this->errorService->deleteDependencies([$error]);
            $error->delete();
        });

        return redirect()->route('projects.errors.index',  [$project_id, $id])
            ->with('success', 'Error has been deleted.');
    }
}
