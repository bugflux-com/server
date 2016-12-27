<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Error;
use App\Models\FlatReport;
use App\Models\RejectionRule;
use App\Models\Language;
use App\Models\Mapping;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Report;
use App\Models\System;
use App\Models\Tag;
use App\Models\User;
use App\Models\Version;
use App\Policies\CommentPolicy;
use App\Policies\ErrorPolicy;
use App\Policies\FlatReportPolicy;
use App\Policies\RejectionRulePolicy;
use App\Policies\LanguagePolicy;
use App\Policies\MappingPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ReportPolicy;
use App\Policies\SystemPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Policies\VersionPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        Comment::class => CommentPolicy::class,
        Permission::class => PermissionPolicy::class,
        Error::class => ErrorPolicy::class,
        Report::class => ReportPolicy::class,
        Version::class => VersionPolicy::class,
        Mapping::class => MappingPolicy::class,
        System::class => SystemPolicy::class,
        FlatReport::class => FlatReportPolicy::class,
        Language::class => LanguagePolicy::class,
        RejectionRule::class => RejectionRulePolicy::class,
        Tag::class => TagPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
