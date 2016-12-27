<?php

namespace App\Services;

use App\Events\NewErrorReported;
use App\Events\NewReportReported;
use App\Models\Environment;
use App\Models\Error;
use App\Models\FlatReport;
use App\Models\Language;
use App\Models\Mapping;
use App\Models\Project;
use App\Models\Report;
use App\Models\System;
use App\Models\Version;
use DB;

class ReportingService
{

    private $is_success;
    private $message;

    /**
     * Returns if the last run of the service ended up with a success
     *
     * @return mixed
     */
    public function isSuccess()
    {
        return $this->is_success;
    }

    /**
     * Returns the message based on the last run of the service
     *
     * @return mixed
     */
    public function getMessage()
    {
        if($this->is_success==false)
        {
            if(!empty($this->message))
            { // ReportingService gave an error message
                return $this->message;
            }
            else
            { // Other error
                return 'An error occured during processing the report';
            }
        }
        elseif($this->is_success==true)
        { // Default response - everything ok
            return 'Report processed successfully';
        }
    }

    /**
     * Convert report from flat to structured.
     *
     * @param FlatReport $flatReport
     * @return bool
     */
    public function process(FlatReport $flatReport)
    {
        try {
            $this->processOrFail($flatReport);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * Convert report from flat to structured.
     * Throw exception if operation cannot be done.
     *
     * @param FlatReport $flatReport
     * @return bool
     */
    public function processOrFail(FlatReport $flatReport)
    {
        DB::transaction(function () use ($flatReport) {
            $this->is_success = false;
            $project = Project::where('code', $flatReport->project)->firstOrFail();
            $version = Version::where('project_id', $project->id)->where('name', $flatReport->version)->first();
            try
            {
                $model = 'system';
                $system = Mapping::resolve(System::class, $flatReport->system, 'name');

                $model = 'language';
                $language = Mapping::resolve(Language::class, $flatReport->language, 'code');
            }
            catch (\Exception $exception)
            {
                // There is no fail when we cannot find mappings for system or language, but we do not continue to create report without this data
                $this->message = 'Could not find mapping for given '.$model;
                return false;
            }

            $environment = Environment::where('name', $flatReport->environment)->firstOrFail();

            // If new version of app has still not been attached to project
            // (manually or via pull-request webhook) then attach it.
            $version = $version ?: $project->versions()->firstOrCreate(['name' => $flatReport->version]);

            // Get existing error or create new if error occurs the first time.
            $error = Error::where('project_id', $project->id)
                ->where('hash', $flatReport->hash)
                ->where('environment_id', $environment->id)->first();

            if (!$error) {
                $error = Error::create([
                    'hash' => $flatReport->hash,
                    'name' => $flatReport->name,
                    'project_id' => $project->id,
                    'environment_id' => $environment->id,
                ]);
                event(new NewErrorReported($error));
            }

            $report = new Report([
                'name' => $flatReport->name,
                'stack_trace' => $flatReport->stack_trace,
                'message' => $flatReport->message,
                'client_id' => $flatReport->client_id,
                'client_ip' => $flatReport->client_ip,
                'error_id' => $error->id,
                'language_id' => $language->id,
                'system_id' => $system->id,
                'version_id' => $version->id,
                'flat_report_id' => $flatReport->id,
                'reported_at_date' => $flatReport->created_at->toDateString(),
                'reported_at' => $flatReport->created_at,
            ]);

            $flatReport->touchImported();
            $report->saveOrFail();
            event(new NewReportReported($report));
            $this->is_success = true;
        });
    }
}