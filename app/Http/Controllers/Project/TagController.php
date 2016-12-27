<?php

namespace App\Http\Controllers\Project;

use App\Models\Error;
use App\Models\Language;
use App\Models\Mapping;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\System;
use App\Models\Tag;
use App\Services\UserService;
use Illuminate\Http\Request;
use DB;


use App\Http\Requests;
use Illuminate\Mail\Message;
use Mail;
use Password;
use Storage;
use URL;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function index($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('readAny', [Tag::class, $project]);

        $tags = $project->tags()->with('project')
            ->withCount('errors')
            ->paginate();

        return view('projects.tags.index', compact('project', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [Tag::class, $project]);

        return view('projects.tags.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserService $service
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [Tag::class, $project]);

        $this->validate($request, [
            'name' => "required|string|unique:tags,name,NULL,id,project_id,$project_id",
            'color' => 'required_without:custom_rgb|regex:/^[a-fA-F0-9]{6}$/',
            'red' => 'required_with:custom_rgb|numeric|between:0,255',
            'green' => 'required_with:custom_rgb|numeric|between:0,255',
            'blue' => 'required_with:custom_rgb|numeric|between:0,255',
        ]);

        if($request->has('color')) {
            $request->merge([
                'red' => hexdec(substr($request->color, 0, 2)),
                'green' => hexdec(substr($request->color, 2, 2)),
                'blue' => hexdec(substr($request->color, 4, 2)),
            ]);
        }

        $request->merge(['project_id' => $project_id]);
        Tag::create($request->only('name','red','green','blue','project_id'));

        return redirect()->route('projects.tags.index', [$project_id])
            ->with('success', 'Tag has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $id)
    {
        $project = Project::findOrFail($project_id);
        $tag = $project->tags()->findOrFail($id);
        $this->authorize('update', $tag);

        return view('projects.tags.edit', compact('project', 'tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $id)
    {
        $tag = Tag::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('update', $tag);

        $this->validate($request, [
            'name' => "required|string|unique:tags,name,$id,id,project_id,$project_id",
            'red' => 'required|numeric|between:0,255',
            'green' => 'required|numeric|between:0,255',
            'blue' => 'required|numeric|between:0,255',
        ]);

        $tag->fill($request->only('name','red','green','blue'))->saveOrFail();

        return redirect()->route('projects.tags.index', [$project_id])
            ->with('success', 'Tag has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $project_id
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        $tag = Tag::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('delete', $tag);

        $tag->delete();

        return redirect()->back()
            ->with('success', 'Tag has been deleted.');
    }

    /**
     * Show the form for creating a new resource.
     * @param $project_id
     * @param $error_id
     * @return \Illuminate\Http\Response
     */
    public function createTag($project_id, $error_id)
    {
        $project = Project::findOrFail($project_id);
        $error = Error::findOrFail($error_id);
        $this->authorize('connectWithTag',  $error);

        $tags =  Tag::doesntHave('errors', 'and', function($q) use ($error_id, $project_id) {
            $q->where('error_id' , $error_id);
            })->where('project_id', $project_id)
            ->pluck('id', 'name');

        return view('projects.errors.tag', compact('project', 'error', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $project_id
     * @param $error_id
     * @return \Illuminate\Http\Response
     */
    public function storeTag(Request $request, $project_id, $error_id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        Tag::where('project_id', $project_id)
            ->findOrFail($request->tag_id);

        $this->authorize('connectWithTag',  $error);

        $this->validate($request, [
            'tag_id' => 'required|exists:tags,id|unique:error_tag,tag_id,NULL,id,error_id,'.$error_id
        ]);

        $error->tags()->attach($request->tag_id);

        return redirect()->route('projects.errors.show', [$error->project_id, $error->id])
            ->with('success', 'Tag has been added to the error.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyTag($project_id, $error_id, $tag_id)
    {
        $error = Error::where('project_id', $project_id)
            ->findOrFail($error_id);

        Tag::where('project_id', $project_id)
            ->findOrFail($tag_id);

        $this->authorize('disconnectWithTag', $error);
        $error->tags()->detach($tag_id);

        return redirect()->route('projects.errors.show', [$project_id, $error_id])
            ->with('success', 'Tag has been removed from the error.');
    }

}
