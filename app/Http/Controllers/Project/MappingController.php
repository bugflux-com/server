<?php

namespace App\Http\Controllers\Project;

use App\Models\Language;
use App\Models\Mapping;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\System;
use App\Services\UserService;
use Illuminate\Http\Request;
use DB;


use App\Http\Requests;
use Illuminate\Mail\Message;
use Mail;
use Password;
use Storage;
use URL;

class MappingController extends Controller
{
    /**
     * The types to be mapped
     *
     * @var array
     */
    protected $mapping_types = [
        'System' => 'system',
        'Language' => 'language'
    ];

    private function implodeArrayValues($array) {
        return implode(",", array_values($array));
    }

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
        $this->authorize('readAny', [Mapping::class, $project]);

        $display_default_mappings = false;

        $mappings = $project->mappings()->with('mappable')->where('is_default',$display_default_mappings)->paginate();

        return view('projects.mappings.index', compact('project', 'mappings', 'display_default_mappings'));
    }

    /**
     * Show the form for creating a new resource.
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [Mapping::class, $project]);

        $mapping_types = $this->mapping_types;
        $systems = System::all()->pluck('id', 'name');
        $languages = Language::all()->pluck('id', 'name');

        return view('projects.mappings.create', compact('project', 'mapping_types', 'systems', 'languages'));
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
        $this->authorize('createAny', [Mapping::class, $project]);

        $field_name = $request->type;
        $table_name = str_plural($field_name);
        $mapping_types = $this->implodeArrayValues($this->mapping_types);

        $this->validate($request, [
            'value' => 'required|string',
            'type' => 'required|string|in:'.$mapping_types.'|unique:mappings,mappable_type,NULL,id,value,'.$request->value.',project_id,'.$project_id,
            $field_name => "required|exists:$table_name,id",
        ]);

        $request->merge([
            'mappable_id' => $request->$field_name,
            'mappable_type' => $request->type,
            'project_id' => $project_id
        ]);

        Mapping::create($request->only('value','mappable_type','mappable_id','project_id'));

        return redirect()->route('projects.mappings.index', [$project_id])
            ->with('success', 'Mapping has been created.');
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
        $mapping = $project->mappings()->findOrFail($id);
        $this->authorize('update', $mapping);

        $mapping_types = $this->mapping_types;
        $systems = System::all()->pluck('id', 'name');;
        $languages = Language::all()->pluck('id', 'name');;

        return view('projects.mappings.edit', compact('project', 'mapping', 'mapping_types','systems','languages'));
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
        $mapping = Mapping::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('update', $mapping);

        $field_name = $request->type;
        $table_name = str_plural($field_name);
        $mapping_types = $this->implodeArrayValues($this->mapping_types);

        $this->validate($request, [
            'value' => 'required|string',
            'type' => "required|string|in:$mapping_types|unique:mappings,mappable_type,$id,id,value,{$request->value},project_id,'.$project_id",
            $field_name => "required|exists:$table_name,id",
        ]);

        $request->merge([
            'mappable_id' => $request->$field_name,
            'mappable_type' => $request->type,
            'is_default' => false,
        ]);
        $mapping->fill($request->only('value','mappable_type','mappable_id','is_default'))->saveOrFail();

        return redirect()->route('projects.mappings.index', [$project_id])
            ->with('success', 'Mapping has been updated.');
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
        $mapping = Mapping::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('delete', $mapping);

        // TODO: Add password confirmation?
        $mapping->delete();

        return redirect()->back()
            ->with('success', 'Mapping has been deleted.');
    }

    /**
     * Display a listing of the resource.
     *
     * @param $project_id
     * @return \Illuminate\Http\Response
     */
    public function defaultMappingsIndex($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('readAny', [Mapping::class, $project]);

        $display_default_mappings = true;

        $mappings = $project->mappings()->with('mappable')->where('is_default',$display_default_mappings)->paginate();

        return view('projects.mappings.index', compact('project', 'mappings', 'display_default_mappings'));
    }

}
