<?php

namespace App\Http\Controllers\API\v1;

use App\Events\ReportWasReceived;
use App\Http\Controllers\Controller;
use App\Models\FlatReport;
use App\Models\RejectionRule;
use App\Models\Project;
use Illuminate\Http\Request;

use App\Http\Requests;

class ErrorController extends Controller
{

    protected $rejection_fields = [
        'version', 'system', 'language', 'hash', 'name', 'environment', 'stack_trace', 'message', 'client_id',  'client_ip',
    ];


    private function do_reject_report(Request $request, $rejection_rules, $fields)
    {
        $do_reject = false;

        foreach ($rejection_rules as $rejection_rule) {
            $does_report_fulfill_rule = true;
            foreach ($fields as $rejection_field) {
                $field_value_regexp = $rejection_rule->$rejection_field;
                $request_field = $request->$rejection_field;
                if(!empty($field_value_regexp)) {
                    $pattern = "#".$field_value_regexp."#u";
                    $result = preg_match($pattern, $request_field);
                    if($result == 0) {
                        $does_report_fulfill_rule = false;
                        break;
                    }
                }
            }
            if($does_report_fulfill_rule) {
                $do_reject = true;
                break;
            }
        }

        return $do_reject;
    }

    /**
     * Receive error report from application.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->merge([
            'client_ip' => $request->getClientIp(),
            'message' => strlen(trim($request->message)) ? $request->message : null, // empty string/whitespaces -> null
        ]);

        $this->validate($request, [
            'project' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'system' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'hash' => 'required|alpha_num|max:64',
            'name' => 'required|string|max:255',
            'environment' => 'required|string|max:255',
            'stack_trace' => 'required|string',
            'client_id' => 'required|alpha_num|max:64',
        ]);

        // rejection
        $project = Project::where('code', $request->project)->firstOrFail();

        if($this->do_reject_report($request, $project->rejectionRules, $this->rejection_fields)) {
            return response()->json(['status' => 'rejected']);
        }

        $flatReport = new FlatReport($request->only([
            'project', 'version', 'system', 'language', 'hash', 'name',
            'environment', 'stack_trace', 'message', 'client_id', 'client_ip'
        ]));

        // TODO: Return "202 Accepted" for partial processing, otherwise 200 (with created model?)
        $flatReport->saveOrFail();
        event(new ReportWasReceived($flatReport));
    }
}
