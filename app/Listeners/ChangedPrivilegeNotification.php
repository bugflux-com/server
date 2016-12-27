<?php


namespace App\Listeners;


use App\Events\ChangedPrivilege;
use App\Models\NotificationGroup;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ChangedPrivilegeNotification
{
    /**
     * Handle the event.
     *
     * @param ChangedPrivilege $event
     */
    public function handle(ChangedPrivilege $event)
    {
        $permission = $event->permission;
        $notificationType = $permission->user->notificationTypes()
            ->where('code', 'changed_privilege')->first();

        if(empty($notificationType)) {
            // No entity exists - assume that user
            // doesn't want to receive notification.
            return;
        }

        if ($notificationType->pivot->internal) {
            $grouping = $permission->user->notificationGroups()
                ->where('notification_type_id', $notificationType->id)
                ->where('viewed_at', '=', null)->first();

            $associations = [
                'json' => [
                    'project_id' => $permission->project->id,
                    'project' => $permission->project->name,
                    'group' => $permission->group->name
                ],
                'notificable_id' => null,
                'notificable_type' => null,
            ];

            DB::transaction(function () use ($associations, $permission, $notificationType, $grouping) {

                if(empty($grouping)) {
                    $grouping = NotificationGroup::create([
                        'user_id' => $permission->user->id,
                        'notification_type_id' => $notificationType->id,
                    ]);
                }

                $notification = Notification::create($associations);
                $notification->groups()->attach($grouping->id);
            });
        }

        if ($notificationType->pivot->email) {
            Mail::send("profile.notifications.emails.{$notificationType->code}", compact('permission'), function ($m) use ($permission) {
                $m->from(config('mail.from.address'), config('mail.from.name'));

                $m->to($permission->user->email, $permission->user->name)
                    ->subject('Changed role in project');
            });
        }
    }
}