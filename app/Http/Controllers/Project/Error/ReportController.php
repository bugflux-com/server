<?php

namespace App\Http\Controllers\Project\Error;

use App\Models\Error;
use App\Models\NotificationType;
use App\Models\Report;
use App\Services\Models\ErrorService;
use App\Services\Models\ReportService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private $reportService;
    private $errorService;

    /**
     * ReportController constructor.
     * @param ErrorService $errorService
     * @param ReportService $reportService
     */
    public function __construct(ErrorService $errorService, ReportService $reportService)
    {
        $this->middleware('auth');
        $this->reportService = $reportService;
        $this->errorService = $errorService;
    }

    private function get_names_ids($error, $model_name)
    {
        $names_reports = $error->reports()->groupBy($model_name.'_id')->distinct($model_name.'_id')->get()->all();
        $names = array_merge(['All '.$model_name.'s'], array_map(function($o) use ($model_name) { return $o->{$model_name}->name; }, $names_reports));
        $model_key_name = $model_name . '_id';
        $ids = array_merge([-1], array_map(function($o) use ($model_key_name) {
            return  $o->{$model_key_name}; }, $names_reports));
        return array_combine($names, $ids);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $project_id
     * @param $error_id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $project_id, $error_id)
    {
        $error = Error::with('project')
            ->where('project_id', $project_id)
            ->findOrFail($error_id);
        $project = $error->project;

        $this->authorize('readAny', [Report::class, $error]);

        // filter
        $system_names_ids  = $this->get_names_ids($error, 'system');
        $language_names_ids = $this->get_names_ids($error, 'language');
        $version_names_ids = $this->get_names_ids($error, 'version');

        $this->validate($request, [
            'system' => 'string|in:'.implode(',', array_values($system_names_ids)),
            'language' => 'string|in:'.implode(',', array_values($language_names_ids)),
            'version' => 'string|in:'.implode(',', array_values($version_names_ids)),
        ]);

        $system = $request->query('system', -1);
        $language = $request->query('language', -1);
        $version = $request->query('version', -1);

        $reports = $error->reports()->withCount('comments')
            ->with('system', 'language', 'version')
            ->filteredSystem($system)
            ->filteredLanguage($language)
            ->filteredVersion($version)
            ->orderBy('reported_at', 'desc')->paginate();

        $notifications = Auth::user()->notificationTypes()->wherePivot('wantable_id', $error_id)
            ->firstOrNew(['code' => 'new_report']);

        return view('projects.errors.reports.index',
            compact('project', 'error', 'reports', 'notifications', 'system_names_ids', 'system', 'language_names_ids', 'language', 'version_names_ids', 'version'));
    }

    /**
     * Display the specified resource.
     *
     * @param $project_id
     * @param $error_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $error_id, $id)
    {
        $error = Error::with('project')
            ->where('project_id', $project_id)
            ->findOrFail($error_id);

        $report = $error->reports()->findOrFail($id);
        $this->authorize('read', $report);
        $project = $error->project;

        return view('projects.errors.reports.show', compact('error', 'report', 'project', 'notifications'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * If all reports that belongs to the error are deleted
     * then remove also the parent resource (error).
     *
     * @param $project_id
     * @param $error_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $error_id, $id)
    {
        $reports_left = DB::transaction(function() use($project_id, $error_id, $id) {
            $error = Error::where('project_id', $project_id)
                ->findOrFail($error_id);

            $report = $error->reports()->findOrFail($id);
            $this->authorize('delete', $report);
            $reports_count = $error->reports()->count();

            $this->reportService->deleteDependencies([$report]);
            $report->delete();
            if($reports_count == 1) {
                $this->errorService->deleteDependencies([$error]);
                $error->delete();
            }

            return $reports_count - 1;
        });

        if($reports_left == 0) {
            return redirect()->route('projects.errors.index',  [$project_id])
                ->with('success', 'Report and error have been deleted.');
        }

        return redirect()->route('projects.errors.reports.index',  [$project_id, $error_id])
            ->with('success', 'Report has been deleted.');
    }
}
