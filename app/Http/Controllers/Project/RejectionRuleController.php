<?php

namespace App\Http\Controllers\Project;

use App\Models\RejectionRule;
use App\Models\Project;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RejectionRuleController extends Controller
{
    /**
     * The fields basing on which reports can be rejected
     *
     * @var array
     */
    protected $rejection_fields = [
        'Version' => 'version',
        'System' => 'system',
        'Language' => 'language',
        'Hash' => 'hash',
        'Name' => 'name',
        'Environment' => 'environment',
        'Stack trace' => 'stack_trace',
        'Message' => 'message',
        'Client id' => 'client_id',
        'Client ip' => 'client_ip',
    ];


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
        $this->authorize('readAny', [RejectionRule::class, $project]);

        $rejection_rules = $project->rejectionRules()->paginate();

        // TODO: Move below logic to the model
        $fields = array_values($this->rejection_fields);
        foreach ($rejection_rules as $rejection_rule) {
            $rejection_rule->filled = [];
            foreach ($fields as $field) {
                if(!empty($rejection_rule->$field)) {
                    $rejection_rule->filled = array_merge($rejection_rule->filled, [$field => $rejection_rule->$field]);
                }
            }
        }

        return view('projects.rejection_rules.index', compact('project', 'rejection_rules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [RejectionRule::class, $project]);

        $rejection_fields = $this->rejection_fields;

        return view('projects.rejection_rules.create', compact('project', 'rejection_fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('createAny', [RejectionRule::class, $project]);

        $fields = array_values($this->rejection_fields);
        $validation_table = array_fill_keys($fields, 'string');

        $validation_table = array_merge($validation_table,[
            'description' => 'required|string'
        ]);

        $this->validate($request, $validation_table);

        $is_any_filled = false;
        foreach ($fields as $field) {
            if(!empty($request->$field)) {
                $is_any_filled = true;
            }
        }

        if(!$is_any_filled) {
            return redirect()->back()
                ->withInput($request->all())
                ->with(['failure' => 'At least one field is required']);
        }

        $request->merge([
            'project_id' => $project_id
        ]);

        $only = array_merge($fields, ['description', 'project_id']);
        RejectionRule::create($request->only($only));

        return redirect()->route('projects.rejection-rules.index', [$project_id])
            ->with('success', 'Rejection rule has been added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $id)
    {
        $project = Project::findOrFail($project_id);
        $rejection_rule = $project->rejectionRules()->findOrFail($id);
        $this->authorize('update', $rejection_rule);

        $rejection_fields = $this->rejection_fields;

        return view('projects.rejection_rules.edit', compact('project', 'rejection_rule', 'rejection_fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $id)
    {
        $rejection_rule = RejectionRule::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('update', $rejection_rule);

        $fields = array_values($this->rejection_fields);
        $validation_table = array_fill_keys($fields, 'string');

        $validation_table = array_merge($validation_table,[
            'description' => 'required|string'
        ]);

        $this->validate($request, $validation_table);

        $is_any_filled = false;
        foreach ($fields as $field) {
            if(!empty($request->$field)) {
                $is_any_filled = true;
            }
        }

        if(!$is_any_filled) {
            return redirect()->back()->withErrors(['error' => 'At least one field is required']);
        }

        $request->merge([
            'project_id' => $project_id
        ]);

        $only = array_merge($fields, ['description', 'project_id']);
        $rejection_rule->fill($request->only($only))->saveOrFail();

        return redirect()->route('projects.rejection-rules.index', [$project_id])
            ->with('success', 'Rejection rule has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        $rejection_rule = RejectionRule::where('project_id', $project_id)->findOrFail($id);
        $this->authorize('delete', $rejection_rule);

        // TODO: Add password confirmation?
        $rejection_rule->delete();

        return redirect()->back()
            ->with('success', 'Rejection rule has been deleted.');
    }
}
