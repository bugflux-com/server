<?php

namespace App\Http\Controllers;

use App\Models\NewEmailToken;
use App\Models\NotificationType;
use App\Models\NotificationTypeUser;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Image;
use Mail;
use Storage;

class ProfileController extends Controller
{
    /**
     * ProfileController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->action('ProfileController@edit');
    }

    /**
     * Show the form for editing the user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'password_current' => 'current_password',
        ]);

        Auth::user()->fill([
            'password' => bcrypt($request->password)
        ])->saveOrFail();

        return redirect()->route('profile.edit')
            ->with('success', 'Your password has been changed.');
    }

    /**
     * Update the user name.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateName(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        Auth::user()->fill([
            'name' => $request->name
        ])->saveOrFail();

        return redirect()->route('profile.edit')
            ->with('success', 'Your name has been changed.');
    }

    /**
     * Handle new email request.
     *
     * Generate token and send mail with
     * confirmation link to the new email.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendEmailConfirmation(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email'
        ]);

        $token = str_random(100);
        $email = $request->email;

        DB::transaction(function() use ($email, $token) {
            if(config('profile.new_email_token.invalidate_previous', false)) {
                // Invalidate all previous user's tokens.
                // a) This is common scenario when someone enters wrong e-mail
                //    and would like to invalidate it immediately.
                // b) Avoid situations when someone set new email "A" and then "B".
                //    If you first confirm "B" and then "A" then the result is the
                //    "A" email address set to the user account.
                //
                // TODO: Allow multiple emails address per account.
                //       When user confirm new email then he could remove the previous one
                //       (there must be at least one active email).
                NewEmailToken::where('user_id', Auth::user()->id)
                    ->delete();
            }

            // Create new (the only valid) token.
            NewEmailToken::create([
                'user_id' => Auth::user()->id,
                'new_email' => $email,
                'token' => $token,
            ]);

            // Send token to the newly entered email address.
            Mail::send('profile.emails.new_email', compact('token'), function ($m) use ($email) {
                $m->from(config('mail.from.address'), config('mail.from.name'));

                $m->to($email, Auth::user()->name)
                    ->subject('Confirm new email');
            });
        });

        return redirect()->route('profile.edit')
            ->with('success', 'Check your email - we have sent you email with confirmation link.');
    }

    /**
     * Show form (with password field) to confirm identity.
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmNewEmail(Request $request, $token)
    {
        NewEmailToken::where('token', $token)
            ->where('user_id', Auth::user()->id)
            ->active()->firstOrFail();

        return view('profile.new_email', compact('token'));
    }

    /**
     * Validate user identity and change user email to the new one.
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmNewEmailUpdate(Request $request, $token)
    {
        $new_mail_token = NewEmailToken::where('token', $token)
            ->where('user_id', Auth::user()->id)
            ->active()->firstOrFail();

        $this->validate($request, [
            'password_current' => 'current_password',
        ]);

        DB::transaction(function() use ($new_mail_token) {
            Auth::user()->fill(['email' => $new_mail_token->new_email])
                ->saveOrFail();

            $new_mail_token->delete();
        });

        return redirect()->route('profile.edit')
            ->with('success', 'Your email has been changed.');
    }

    /**
     * Update the user photo.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {
        $sizes = collect(config('profile.photo_sizes'));

        $min_size = $sizes['large'];
        $this->validate($request, [
            'photo' => "required|image|dimensions:min_width=$min_size,min_height=$min_size"
        ]);

        $user_id = Auth::user()->id;
        $image = Image::make($request->file('photo')->getRealPath());

        $reversed = $sizes->sort()->reverse(); // From largest to smallest
        foreach($reversed as $name => $size) {
            $file = $image->fit($size)->stream();
            Storage::put("user_photos/$user_id-$name.jpg", $file);
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Your photo has been changed. Due to the browser\'s cache photo will be changed after some time.');
    }

    /**
     * Remove user photo (back to the default one).
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroyPhoto(Request $request)
    {
        $user_id = Auth::user()->id;
        $sizes = config('profile.photo_sizes');
        foreach($sizes as $name => $size) {
            Storage::delete("user_photos/$user_id-$name.jpg");
        }

        return redirect()->route('profile.edit')
            ->with('success','Your profile photo has been successfully removed.');
    }

    /**
     * Update the notifications settings.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateNotifications(Request $request)
    {
        $this->validate($request, [
            'invalid_login_attempt.internal' => 'required|boolean',
            'invalid_login_attempt.email' => 'required|boolean',
            'new_privilege.internal' => 'required|boolean',
            'new_privilege.email' => 'required|boolean',
            'changed_privilege.internal' => 'required|boolean',
            'changed_privilege.email' => 'required|boolean',
        ]);

        DB::transaction(function() use ($request) {
            $entries = NotificationType::whereIn('code', [
                'invalid_login_attempt', 'new_privilege', 'changed_privilege'
            ])->get();

            foreach ($entries as $entry) {
                NotificationTypeUser::updateOrCreate([
                    // Only works for user notifications without related object
                    // (without specifying wantable_* columns)
                    'notification_type_id' => $entry->id,
                    'user_id' => Auth::user()->id,
                ], [
                    'user_id' => Auth::user()->id,
                    'internal' => $request->input("{$entry->code}.internal"),
                    'email' => $request->input("{$entry->code}.email"),
                ]);
            }
        });

        return redirect()->route('profile.notifications.edit')
            ->with('success', 'Notifications settings saved.');
    }
}
