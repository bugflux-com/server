<?php


namespace App\Listeners;


use App\Events\CommentAdded;
use App\Models\Error;
use App\Models\NotificationGroup;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\NotificationTypeUser;
use App\Models\Report;
use App\Providers\AppServiceProvider;
use Auth;
use Mail;

class NewCommentNotification
{
    /**
     * Handle the event.
     *
     * @param CommentAdded $event
     */
    public function handle(CommentAdded $event)
    {
        $comment = $event->comment;
        $type_code = null;
        $report = null;
        $error = null;

        $commentable_type_error = array_search(Error::class, AppServiceProvider::$morphMap);
        $commentable_type_report = array_search(Report::class, AppServiceProvider::$morphMap);

        if($comment->commentable_type == $commentable_type_report) {
            $type_code = 'new_report_comment';
            $report = Report::findOrFail($comment->commentable_id);
            $error = $report->error;
        }
        else if($comment->commentable_type == $commentable_type_error) {
            $type_code = 'new_error_comment';
            $error = Error::findOrFail($comment->commentable_id);
        }
        else {
            // TODO: Throw exception or just skip?
            return;
        }

        $notificationType = NotificationType::where('code', $type_code)->firstOrFail();
        $user = Auth::user();

        // check if person who added the comment wants comment notifications about this report

        $notification_type_user = NotificationTypeUser::where('user_id', $user->id)
            ->where('wantable_id', $comment->commentable_id)
            ->where('notification_type_id', $notificationType->id)
            ->first();

        if(empty($notification_type_user)) {

            // user didn't set whether to get notifications
            // lets create info that he/she wants

            NotificationTypeUser::create([
                'notification_type_id' => $notificationType->id,
                'user_id' => $user->id,
                'wantable_id' => $comment->commentable_id,
                'wantable_type' => $comment->commentable_type,
                'internal' => true,
                'email' => true,
            ]);

        }
        
        // create notification for people who want them

        $users = $notificationType
            ->users()
            ->where('user_id', '!=', $user->id)
            ->wherePivot('wantable_id', $comment->commentable_id)
            ->get();

        $groupings_ids = [];

        foreach ($users as $user)
        {
            if ($user->pivot->internal) {
                $grouping = $user->notificationGroups()
                    ->where('notification_type_id', $notificationType->id)
                    ->where('viewed_at', '=', null)->first();

                if(empty($grouping)) {
                    $grouping = NotificationGroup::create([
                        'user_id' => $user->id,
                        'notification_type_id' => $notificationType->id,
                    ]);
                }

                array_push($groupings_ids, $grouping->id);
            }

            if ($user->pivot->email) {
                Mail::send("profile.notifications.emails.{$notificationType->code}", compact('user', 'comment', 'error', 'report'), function ($m) use ($user) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));

                    $m->to($user->email, $user->name)
                        ->subject('New comment');
                });
            }
        }

        if(!empty($groupings_ids)) {

            $json = [
                'project_id' => $error->project->id,
                'project' => $error->project->name,
                'error_id' => $error->id,
                'error' => $error->name,
                'user_id' => $comment->user_id,
                'user' => $comment->user->name ?: $comment->user->email,
                'message' => str_limit($comment->message, 40),
            ];

            $commentable_id = $error->id;
            $commentable_type = $commentable_type_error;

            if($type_code == 'new_report_comment') {

                $commentable_id = $report->id;
                $commentable_type = $commentable_type_report;

                $json = array_merge($json, [
                    'report_id' => $report->id,
                    'report' => $report->name,
                ]);
            }

            $associations = [
                'json' => $json,
                'notificable_id' => $commentable_id,
                'notificable_type' => $commentable_type,
            ];

            $notification = Notification::create($associations);

            $notification->groups()->attach($groupings_ids);
        }


    }
}