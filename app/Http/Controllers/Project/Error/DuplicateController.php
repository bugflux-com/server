<?php

namespace App\Http\Controllers\Project\Error;

use App\Models\Error;
use App\Models\ErrorDuplicate;
use App\Models\Project;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DuplicateController extends Controller
{
    /**
     * DuplicateController constructor.
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
    public function index($project_id, $error_id)
    {
        $project = Project::findOrFail($project_id);
        $error = $project->errors()->findOrFail($error_id);
        $this->authorize('readAny', [\App\Models\Error::class, $project]);

        $duplicates = ErrorDuplicate::with('error')
            ->duplicatesOf($error_id)
            ->latest()->paginate();

        return view('projects.errors.duplicates.index',
            compact('project', 'error', 'duplicates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id, $error_id)
    {
        $project = Project::findOrFail($project_id);
        $error = Error::findOrFail($error_id);
        $this->authorize('update',  $error);

        $duplicate_code = ErrorDuplicate::where('error_id' , $error_id)->first();
        $duplicate_ids = [];

        if(!empty($duplicate_code))
        {
            $duplicate_code = $duplicate_code->code;
            $duplicate_ids = ErrorDuplicate::where('code', $duplicate_code)
                ->where('error_id', '!=' , $error_id)
                ->get(['error_id']);
        }

        $errors = Error::where('project_id', $project_id)
            ->where('id', '!=' , $error_id)
            ->whereNotIn('id', $duplicate_ids)
            ->pluck('id', 'name');

        return view('projects.errors.duplicates.create', compact('project', 'error', 'errors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id, $error_id)
    {
        $currentError = Error::where('project_id', $project_id)
            ->findOrFail($error_id);
        $this->authorize('update',  $currentError);

        $this->validate($request, [
            'error_id' => 'required|exists:errors,id'
        ]);

        $duplicatedErrorCode = null;
        $currentErrorDuplicate = ErrorDuplicate::where('error_id' , $currentError->id)->first();
        $targetErrorDuplicate = ErrorDuplicate::where('error_id' , $request->error_id)->first();

        $currentErrorDuplicateCount = count($currentErrorDuplicate);
        $targetErrorDuplicateCount = count($targetErrorDuplicate);

        if( $currentErrorDuplicateCount == 0 && $targetErrorDuplicateCount == 0)
        { // none of the both errors were marked as duplicated
            while (!empty(ErrorDuplicate::where('code', $duplicatedErrorCode = ErrorDuplicate::random_code())->first()));

            $request->merge(['code' => $duplicatedErrorCode]);

            \DB::transaction(function () use ($request, $error_id) {
                //creating duplication for the target error
                ErrorDuplicate::create($request->only('code', 'error_id'));

                //creating duplication for the current error
                $request->merge(['error_id' => $error_id]);
                ErrorDuplicate::create($request->only('code', 'error_id'));
            });
        }
        elseif ($currentErrorDuplicateCount == 0 && $targetErrorDuplicateCount != 0)
        { // targeted error was marked as a duplicate before
            $duplicatedErrorCode = $targetErrorDuplicate->code;

            //creating duplication for the current error
            $request->merge(['code' => $duplicatedErrorCode]);
            $request->merge(['error_id' => $error_id]);
            ErrorDuplicate::create($request->only('code', 'error_id'));
        }
        elseif ($currentErrorDuplicateCount != 0 && $targetErrorDuplicateCount  == 0)
        { // current error was marked as a duplicate before
            $duplicatedErrorCode = $currentErrorDuplicate->code;

            //creating duplication for the target error
            $request->merge(['code' => $duplicatedErrorCode]);
            ErrorDuplicate::create($request->only('code', 'error_id'));
        }
        elseif($currentErrorDuplicate->code != $targetErrorDuplicate->code)
        { // both errors were marked as duplicates before and their codes aren't the same

            //update current errors group to create one group with the target ones
            ErrorDuplicate::where('code', $currentErrorDuplicate->code)
                ->update(['code' => $targetErrorDuplicate->code]);
        }

        return redirect()->route('projects.errors.duplicates.index', [$currentError->project_id, $currentError->id])
            ->with('success', 'Error has been marked as a duplicate.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $error_id, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $error_id, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $error_id, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $error_id, $id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        $duplicate = ErrorDuplicate::findOrFail($id);

        $this->authorize('update', $error);
        $duplicate->delete();

        return redirect()->route('projects.errors.duplicates.index', [$project_id, $error_id])
            ->with('success', 'Error has been removed from duplicate list.');
    }
}
