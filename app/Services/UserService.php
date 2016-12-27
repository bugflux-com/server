<?php


namespace App\Services;


use Illuminate\Mail\Message;
use Mail;
use Password;

class UserService
{
    /**
     * Send welcome email and start
     * resetting password procedure.
     *
     * @param string $email
     * @param string|null $name
     */
    public function sendWelcomeMail($email, $name = null)
    {
        Mail::send('users.emails.new_user', compact('name'), function ($m) use ($email, $name) {
            $m->from(config('mail.from.address'), config('mail.from.name'));

            $m->to($email, $name ?: $email)
                ->subject('Welcome to bugflux!');
        });

        // TODO: Other message for failure (sendResetLink return status)
        Password::broker()->sendResetLink(
            ['email' => $email],
            function (Message $message) {
                $message->subject('Reset password');
            }
        );
    }
}