<?php

namespace App\Events;

use App\Events\Event;
use App\Models\FlatReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReportWasReceived extends Event
{
    use SerializesModels;

    /**
     * Newly added report.
     *
     * @var FlatReport
     */
    public $flatReport;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FlatReport $flatReport)
    {
        $this->flatReport = $flatReport;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
