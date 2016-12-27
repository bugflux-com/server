<?php

namespace App\Http\Controllers;

use App\Events\NewPrivilegeGiven;
use App\Models\Group;
use App\Models\NotificationType;
use App\Models\Permission;
use App\Models\Project;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Services\UserService;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Mail\Message;
use Mail;
use Password;
use Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get user's photo file.
     *
     * @return \Illuminate\Http\Response
     */
    public function photo($id, $size)
    {
        $user = User::findOrFail($id);
        $this->authorize('read', $user);

        $path = "user_photos/$id-$size.jpg";
        $default = "user_photos/default-$size.jpg";

        $filename = Storage::exists($path) ? $path : $default;
        $storage = storage_path('app');

        return response()->file("$storage/$filename");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('readAny', User::class);
        $users = \Auth::user()->collaborators()->paginate();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('createAny', User::class);

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserService $service
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserService $service)
    {
        $this->authorize('createAny', User::class);
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'is_blocked' => 'required|boolean'
        ]);

        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->only(['name', 'email', 'password', 'is_blocked']));

        $service->sendWelcomeMail($user->email, $user->name);

        return redirect()->route('users.show', $user->id)
            ->with('success', 'New account has been created and the email with password change link has been sent.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('read', $user);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $this->validate($request, [
            //'password_current' => 'current_password', // TODO: Uncomment if deleting password will also require confirmation
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->email . ',email',
            'password' => 'confirmed',
            'is_blocked' => 'required|boolean'
        ]);

        $data = $request->only(['name', 'email', 'is_blocked']);
        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        $user->fill($data)->saveOrFail();

        return redirect()->route('users.show', $user->id)
            ->with('success', 'User data has been changed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        // TODO: Add password confirmation?
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User has been deleted.');
    }
}
