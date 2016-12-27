<?php


namespace App\Listeners;


use App\Events\NewErrorReported;
use App\Models\NotificationGroup;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Project;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Mail;

class NewErrorNotification
{
    /**
     * Handle the event.
     *
     * @param NewErrorReported $event
     */
    public function handle(NewErrorReported $event)
    {
        $error = $event->error;
        $notificationType = NotificationType::where('code', 'new_error')->firstOrFail();
        $users = $notificationType
            ->users()
            ->wherePivot('wantable_id', $error->project_id)
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
                Mail::send("profile.notifications.emails.{$notificationType->code}", compact('user', 'error'), function ($m) use ($user, $error) {
                    $m->from(config('mail.from.address'), config('mail.from.name'));

                    $m->to($user->email, $user->name)
                        ->subject('New error in project '.$error->project->name);
                });
            }
        }

        if(!empty($groupings_ids)) {
            $associations = [
                'json' => [
                    'project_id' => $error->project->id,
                    'project' => $error->project->name,
                    'error_id' => $error->id,
                    'error' => $error->name
                ],
                'notificable_id' => $error->project->id,
                'notificable_type' => array_search(Project::class, AppServiceProvider::$morphMap),
            ];
            $notification = Notification::create($associations);

            $notification->groups()->attach($groupings_ids);
        }


    }
}