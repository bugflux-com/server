<?php


namespace App\Listeners;


use App\Events\NewReportReported;
use App\Models\Error;
use App\Models\NotificationGroup;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NewReportNotification
{
    /**
     * Handle the event.
     *
     * @param NewReportReported $event
     */
    public function handle(NewReportReported $event)
    {
        $report = $event->report;
        $notificationType = NotificationType::where('code', 'new_report')->first();
        $users = $notificationType->users()
            ->wherePivot('wantable_id', $report->error_id)
            ->get();

        $groupings_ids = [];
        foreach ($users as $user) {
            if ($user->pivot->internal) {
                $grouping = $user->notificationGroups()
                    ->where('notification_type_id', $notificationType->id)
                    ->where('viewed_at', '=', null)->first();

                if (empty($grouping)) {
                    $grouping = NotificationGroup::create([
                        'user_id' => $user->id,
                        'notification_type_id' => $notificationType->id,
                    ]);
                }

                array_push($groupings_ids, $grouping->id);
            }

            if ($user->pivot->email) {
                Mail::send("profile.notifications.emails.{$notificationType->code}", compact('user', 'report'), function ($m) use ($user, $report) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));

                    $m->to($user->email, $user->name)
                        ->subject('New report for error ' . $report->error->name);
                });
            }
        }

        if (!empty($groupings_ids)) {
            $associations = [
                'json' => [
                    'error' => $report->error->name,
                    'error_id' => $report->error_id,
                    'project' => $report->error->project->name,
                    'project_id' => $report->error->project_id,
                    'report' => $report->name,
                    'report_id' => $report->id,
                ],
                'notificable_id' => $report->error_id,
                'notificable_type' => array_search(Error::class, AppServiceProvider::$morphMap),
            ];

            $notification = Notification::create($associations);
            $notification->groups()->attach($groupings_ids);
        }
    }
}