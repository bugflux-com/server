<?php

namespace App\Http\Controllers\Project;

use App\Events\NewPrivilegeGiven;
use App\Events\ChangedPrivilege;
use App\Events\ReportWasReceived;
use App\Models\FlatReport;
use App\Models\Group;
use App\Models\NotificationType;
use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\Version;
use App\Services\ReportingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use DB;


use App\Http\Requests;
use Illuminate\Mail\Message;
use Mail;
use Password;
use Storage;
use URL;

class FlatReportController extends Controller
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
        $this->authorize('readAny', [FlatReport::class, $project]);

        $flat_reports = FlatReport::where('project', $project->code)
            ->where('imported_at', null)
            ->latest()
            ->paginate();

        return view('projects.flat_reports.index', compact('project', 'flat_reports'));
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
        $project = Project::findOrFail($project_id);
        $flat_report = FlatReport::where('project', $project->code)
            ->where('imported_at', null)
            ->findOrFail($id);
        $this->authorize('read', [$flat_report, $project]);

        return view('projects.flat_reports.show', compact('project','flat_report'));
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
        $project = Project::findOrFail($project_id);
        $flat_report = FlatReport::where('project', $project->code)->findOrFail($id);
        $this->authorize('delete', [$flat_report, $project]);

        // TODO: Add password confirmation?
        $flat_report->delete();

        return redirect()->route('projects.flat-reports.index', [$project_id])
            ->with('success', 'Report has been deleted.');
    }

    /**
     * Display the specified resource.
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function redoReport($project_id, $id, ReportingService $service)
    {
        $project = Project::findOrFail($project_id);
        $flat_report = FlatReport::where('project', $project->code)
            ->where('imported_at', null)
            ->findOrFail($id);
        $this->authorize('redo', [$flat_report, $project]);

        $service->process($flat_report);

        $type = $service->isSuccess() == true? 'success' : 'failure';

        $message = $service->getMessage();

        return redirect()
            ->route('projects.flat-reports.index', [$project_id])
            ->with($type, $message);
    }
}
