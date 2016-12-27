<?php

namespace App\Http\Controllers\Project\Error\Report;

use App\Events\CommentAdded;
use App\Models\Comment;
use App\Models\Error;
use App\Models\Report;
use App\Providers\AppServiceProvider;
use Auth;
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
    public function index(Request $request, $project_id, $error_id, $report_id)
    {
        $error = Error::with('project')
            ->where('project_id', $project_id)
            ->findOrFail($error_id);
        $project = $error->project;

        $report = Report::where('error_id', $error_id)
            ->findOrFail($report_id);
        $this->authorize('readAny', [Comment::class, $project_id]);

        $comments = $report->comments()
            ->with('user')
            ->paginate();

        // Redirect to the last page if page is not specified
        if($request->get('page', null) == null) {
            session()->reflash();
            return redirect()->route('projects.errors.reports.comments.index', [
                $project_id, $error_id, $report_id,
                'page' => $comments->lastPage()
            ]);
        }

        // Notification settings
        $notifications = Auth::user()->notificationTypes()->wherePivot('wantable_id', $report->id)
            ->where('code', 'new_report_comment')->first();

        if(empty($notifications)) {
            $notifications = (object)[ 'pivot' => false];
        }

        return view('projects.errors.reports.comments.index',
            compact('project', 'notifications', 'error', 'comments', 'report'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id, $error_id, $report_id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $report = $error->reports()->findOrFail($report_id);
        $this->authorize('createAny', [Comment::class, $project_id]);

        return view('projects.errors.reports.comments.create', compact('error', 'report'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id, $error_id, $report_id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $report = $error->reports()->findOrFail($report_id);
        $this->authorize('createAny', [Comment::class, $project_id]);

        $this->validate($request, [
            'message' => 'required',
        ]);

        $associations = [
            'commentable_id' => $report_id,
            'commentable_type' => array_search(Report::class, AppServiceProvider::$morphMap),
            'user_id' => Auth::user()->id,
        ];

        $comment = Comment::create($request->only('message') + $associations);

        event(new CommentAdded($comment));

        return redirect()->route('projects.errors.reports.comments.index', [$project_id, $error_id, $report_id])
            ->with('success', 'Comment has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $error_id, $report_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $report = $error->reports()->findOrFail($report_id);


        $comment = $report->comments()
            ->with('user')
            ->findOrFail($id);

        $this->authorize('read', [$comment, $project_id]);

        return view('projects.errors.comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $error_id, $report_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);
        $project = $error->project;

        $report = $error->reports()->findOrFail($report_id);

        $comment = $report->comments()
            ->findOrFail($id);

        $this->authorize('update', [$comment, $project_id]);

        return view('projects.errors.reports.comments.edit', compact('project', 'error', 'report', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $error_id, $report_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $report = $error->reports()->findOrFail($report_id);

        $comment = $report->comments()
            ->findOrFail($id);

        $this->authorize('update', [$comment, $project_id]);
        $this->validate($request, [
            'message' => 'required',
        ]);

        $comment->fill($request->only('message'))->saveOrFail();

        return redirect()->route('projects.errors.reports.comments.index', [$project_id, $error_id, $report_id])
            ->with('success', 'Comment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $error_id, $report_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $report = $error->reports()->findOrFail($report_id);

        $comment = $report->comments()
            ->findOrFail($id);

        $this->authorize('delete', [$comment, $project_id]);
        $comment->delete();

        return redirect()->route('projects.errors.reports.comments.index', [$project_id, $error_id, $report_id])
            ->with('success', 'Comment has been deleted.');
    }
}
