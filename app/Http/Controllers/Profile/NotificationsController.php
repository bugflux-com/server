<?php

namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Models\Error;
use App\Models\NotificationType;
use App\Models\NotificationTypeUser;
use App\Models\Project;
use App\Models\Report;
use App\Providers\AppServiceProvider;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of groupings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Auth::user()->notificationGroups()
            ->with('type')->latest()->paginate();

        return view('profile.notifications.index',
            compact('notifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $notifications = $user->notificationTypes()->whereIn('code', [
            'invalid_login_attempt', 'new_privilege', 'changed_privilege'
        ])->get()->keyBy('code')->transform(function($item, $key) {
            return [
                'internal' => $item->pivot->internal,
                'email' => $item->pivot->email,
            ];
        });

        return view('profile.notifications.edit', compact('notifications'));
    }

    /**
     * Display a listing of notifications for given group.
     *
     * @return \Illuminate\Http\Response
     */
    public function showGroup($id)
    {
        $grouping = Auth::user()->notificationGroups()->findOrFail($id);
        $notifications = $grouping->notifications()->latest()->paginate();

        $grouping->viewed_at = Carbon::now();
        $grouping->save();

        return view('profile.notifications.show',
            compact('grouping','notifications'));
    }

    /**
     * Update the notifications settings.
     *
     * @param  Request $request
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function updateErrorsNotifications(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->validate($request, [
            'new_error.internal' => 'required|boolean',
            'new_error.email' => 'required|boolean',
        ]);

        $this->authorize('read', $project);

        $notification_type = NotificationType::where('code', 'new_error')->firstOrFail();
        NotificationTypeUser::updateOrCreate([
            'notification_type_id' => $notification_type->id,
            'user_id' => Auth::user()->id,
            'wantable_id' => $project_id,
            'wantable_type' => array_search(Project::class, AppServiceProvider::$morphMap),
        ], [
            'internal' => $request->input('new_error.internal'),
            'email' => $request->input('new_error.email')
        ]);

        return redirect()->route('projects.errors.index', $project_id)
            ->with('success', 'Notification settings has been updated.');
    }

    /**
     * Update the notifications settings.
     *
     * @param  Request $request
     * @param $error_id
     * @return \Illuminate\Http\Response
     */
    public function updateReportsNotifications(Request $request, $error_id)
    {
        $error = Error::findOrFail($error_id);
        $this->validate($request, [
            'new_report.internal' => 'required|boolean',
            'new_report.email' => 'required|boolean',
        ]);

        $this->authorize('read', $error);

        $notification_type = NotificationType::where('code', 'new_report')->firstOrFail();
        NotificationTypeUser::updateOrCreate([
            'notification_type_id' => $notification_type->id,
            'user_id' => Auth::user()->id,
            'wantable_id' => $error_id,
            'wantable_type' => array_search(Error::class, AppServiceProvider::$morphMap),
        ], [
            'internal' => $request->input('new_report.internal'),
            'email' => $request->input('new_report.email')
        ]);

        return redirect()->route('projects.errors.reports.index', [$error->project_id, $error_id])
            ->with('success', 'Notification settings has been updated.');
    }


    /**
     * Update the notifications settings.
     *
     * @param  Request $request
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function updateErrorsCommentsNotifications(Request $request, $error_id)
    {
        $error = Error::findOrFail($error_id);
        $this->validate($request, [
            'new_error_comment.internal' => 'required|boolean',
            'new_error_comment.email' => 'required|boolean',
        ]);

        $this->authorize('read', $error);

        $notification_type = NotificationType::where('code', 'new_error_comment')->firstOrFail();
        NotificationTypeUser::updateOrCreate([
            'notification_type_id' => $notification_type->id,
            'user_id' => Auth::user()->id,
            'wantable_id' => $error_id,
            'wantable_type' => array_search(Error::class, AppServiceProvider::$morphMap),

        ], [
            'internal' => $request->input('new_error_comment.internal'),
            'email' => $request->input('new_error_comment.email')
        ]);

        return redirect()->route('projects.errors.comments.index', [$error->project_id, $error_id])
            ->with('success', 'Notification settings has been updated.');
    }

    /**
     * Update the notifications settings.
     *
     * @param  Request $request
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function updateReportsCommentsNotifications(Request $request, $report_id)
    {
        $report = Report::findOrFail($report_id);
        $this->validate($request, [
            'new_report_comment.internal' => 'required|boolean',
            'new_report_comment.email' => 'required|boolean',
        ]);

        $this->authorize('read', $report);

        $notification_type = NotificationType::where('code', 'new_report_comment')->firstOrFail();
        NotificationTypeUser::updateOrCreate([
            'notification_type_id' => $notification_type->id,
            'user_id' => Auth::user()->id,
            'wantable_id' => $report_id,
            'wantable_type' => array_search(Report::class, AppServiceProvider::$morphMap),
        ], [
            'internal' => $request->input('new_report_comment.internal'),
            'email' => $request->input('new_report_comment.email')
        ]);

        return redirect()->route('projects.errors.reports.comments.index', [$report->error->project_id, $report->error_id, $report_id])
            ->with('success', 'Notification settings has been updated.');
    }
}