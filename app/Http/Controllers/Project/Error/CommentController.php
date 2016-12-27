<?php

namespace App\Http\Controllers\Project\Error;

use App\Events\CommentAdded;
use App\Models\Comment;
use App\Models\Error;
use App\Models\Project;
use App\Providers\AppServiceProvider;
use Auth;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * CommentController constructor.
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
    public function index(Request $request, $project_id, $error_id)
    {
        $error = Error::with('project')
            ->where('project_id', $project_id)
            ->findOrFail($error_id);
        $project = $error->project;
        $this->authorize('readAny', [Comment::class, $project_id]);

        $comments = $error->comments()
            ->with('user')->paginate();

        // Redirect to the last page if page is not specified
        if($request->get('page', null) == null) {
            session()->reflash();
            return redirect()->route('projects.errors.comments.index', [
                $project_id, $error_id,
                'page' => $comments->lastPage()
            ]);
        }

        // Notifications settings
        $notifications = Auth::user()->notificationTypes()->wherePivot('wantable_id', $error->id)
            ->where('code', 'new_error_comment')->first();

        if(empty($notifications)) {
            $notifications = (object)[ 'pivot' => false];
        }

        return view('projects.errors.comments.index',
            compact('project', 'error', 'comments', 'notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id, $error_id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $this->authorize('createAny', [Comment::class, $project_id]);

        return view('projects.errors.comments.create', compact('error'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id, $error_id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $this->authorize('createAny', [Comment::class, $project_id]);

        $this->validate($request, [
            'message' => 'required',
        ]);

        $associations = [
            'commentable_id' => $error_id,
            'commentable_type' => array_search(Error::class, AppServiceProvider::$morphMap),
            'user_id' => Auth::user()->id,
        ];

        $comment = Comment::create($request->only('message') + $associations);

        event(new CommentAdded($comment));

        return redirect()->route('projects.errors.comments.index', [$project_id, $error_id])
            ->with('success', 'Comment has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $error_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $comment = $error->comments()
            ->with('user')
            ->findOrFail($id);
        $this->authorize('read', [$comment, $project_id]);

        return view('projects.errors.comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $error_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);
        $project = $error->project;

        $comment = $error->comments()
            ->findOrFail($id);

        $this->authorize('update', [$comment, $project_id]);

        return view('projects.errors.comments.edit', compact('project', 'error', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $error_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $comment = $error->comments()
            ->findOrFail($id);

        $this->authorize('update', [$comment, $project_id]);
        $this->validate($request, [
            'message' => 'required',
        ]);

        $comment->fill($request->only('message'))->saveOrFail();

        return redirect()->route('projects.errors.comments.index',  [$error->project_id, $error->id])
            ->with('success', 'Comment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $error_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $comment = $error->comments()
            ->findOrFail($id);

        $this->authorize('delete', [$comment, $project_id]);
        $comment->delete();

        return redirect()->route('projects.errors.comments.index',  [$error->project_id, $error->id])
            ->with('success', 'Comment has been deleted.');
    }
}
