<?php


namespace App\Listeners;

use App\Models\NotificationGroup;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Failed as LoginAttemptFailed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class InvalidLoginAttemptNotification
{

    /**
     * Handle the event.
     *
     * @param LoginAttemptFailed $event
     */
    public function handle(LoginAttemptFailed $event)
    {
        $user = $event->user;
        if (empty($user)) {
            // Invalid login with the e-mail
            // which doesn't belong to any user.
            return;
        }

        $notificationType = $user->notificationTypes()
            ->where('code', 'invalid_login_attempt')->first();

        if(empty($notificationType)) {
            // No entity exists - assume that user
            // doesn't want to receive notification.
            return;
        }

        if ($notificationType->pivot->internal) {
            $grouping = $user->notificationGroups()
                ->where('notification_type_id', $notificationType->id)
                ->where('viewed_at', '=', null)->first();

            $now = Carbon::now();
            $ip = app('request')->ip();

            $associations = [
                'json' => [
                    'ip' => $ip,
                    'attempted_at' => $now,
                ],
                'notificable_id' => null,
                'notificable_type' => null,
            ];

            DB::transaction(function () use ($associations, $user, $notificationType, $grouping) {

                if(empty($grouping)) {
                    $grouping = NotificationGroup::create([
                        'user_id' => $user->id,
                        'notification_type_id' => $notificationType->id,
                    ]);
                }

                $notification = Notification::create($associations);
                $notification->groups()->attach($grouping->id);
            });

        }

        if ($notificationType->pivot->email) {
            Mail::send("profile.notifications.emails.{$notificationType->code}", compact('user', 'ip'), function ($m) use ($user) {
                $m->from(config('mail.from.address'), config('mail.from.name'));

                $m->to($user->email, $user->name)
                    ->subject('Invalid login attempt!');
            });
        }
    }
}