<?php

namespace App\Providers;

use App\Events\CommentAdded;
use App\Events\NewErrorReported;
use App\Events\NewPrivilegeGiven;
use App\Events\ChangedPrivilege;
use App\Events\NewReportReported;
use App\Events\ReportWasReceived;
use App\Listeners\InvalidLoginAttemptNotification;
use App\Listeners\NewCommentNotification;
use App\Listeners\NewErrorNotification;
use App\Listeners\NewPrivilegeNotification;
use App\Listeners\ChangedPrivilegeNotification;
use App\Listeners\NewReportNotification;
use App\Listeners\RunReportingService;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Failed as LoginAttemptFailed;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ReportWasReceived::class => [
            RunReportingService::class,
        ],
        LoginAttemptFailed::class => [
            InvalidLoginAttemptNotification::class,
        ],
        NewPrivilegeGiven::class => [
            NewPrivilegeNotification::class,
        ],
        ChangedPrivilege::class => [
            ChangedPrivilegeNotification::class,
        ],
        NewErrorReported::class => [
            NewErrorNotification::class,
        ],
        NewReportReported::class => [
            NewReportNotification::class,
        ],
        CommentAdded::class => [
            NewCommentNotification::class,
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
