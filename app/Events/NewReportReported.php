<?php


namespace App\Events;


use App\Models\Report;

class NewReportReported
{
    /**
     * New report.
     *
     * @var Report
     */
    public $report;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }
}