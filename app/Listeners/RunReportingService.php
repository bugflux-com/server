<?php

namespace App\Listeners;

use App\Events\ReportWasReceived;
use App\Services\ReportingService;

class RunReportingService
{
    private $service;

    /**
     * Create the event listener.
     *
     * @param ReportingService $service
     */
    public function __construct(ReportingService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param ReportWasReceived $event
     */
    public function handle(ReportWasReceived $event)
    {
        $this->service->processOrFail($event->flatReport);
    }
}
