<div class="container fluid">
    <div class="mts">
        <h1 class="text-overflow">{{ $project->name }}</h1>
        <span class="font-neutral text-overflow">#{{ $project->code }}</span>
    </div>
</div>

<div class="links mts">
    <a href="{{ route('projects.show', $project->id) }}" @if($master_view == 'projects.show') class="current" @endif>
        <span class="icon-info"></span>
        <span class="plx"> Overview </span>
    </a>

    <a href="{{ route('projects.errors.index', $project->id) }}" @if(starts_with($master_view, 'projects.errors.')) class="current" @endif>
        <span class="icon-new_releases"></span>
        <span class="plx"> Errors </span>
    </a>

    <a href="{{ route('projects.versions.index', $project->id) }}" @if(starts_with($master_view, 'projects.versions.')) class="current" @endif>
        <span class="icon-turned_in"></span>
        <span class="plx"> Versions </span>
    </a>

    <a href="{{ route('projects.flat-reports.index', $project->id) }}" @if(starts_with($master_view, 'projects.flat_reports.')) class="current" @endif>
        <span class="icon-extension"></span>
        <span class="plx">
            Unclassified reports
            @if($unclassified_reports_count > 0)
                <span class="badge -warning">{{ $unclassified_reports_count }}</span>
            @endif
        </span>
    </a>

    <a href="{{ route('projects.rejection-rules.index', $project->id) }}" @if(starts_with($master_view, 'projects.rejection_rules.')) class="current" @endif>
        <span class="icon-remove_circle"></span>
        <span class="plx"> Rejection rules </span>
    </a>

    <a href="{{ route('projects.mappings.index', $project->id) }}" @if(starts_with($master_view, 'projects.mappings.')) class="current" @endif>
        <span class="icon-school"></span>
        <span class="plx"> Mappings </span>
    </a>

    @can('readAny', [\App\Models\Permission::class, $project])
        <a href="{{ route('projects.permissions.index', $project->id) }}" @if(starts_with($master_view, 'projects.permissions.')) class="current" @endif>
            <span class="icon-lock"></span>
            <span class="plx"> Permissions </span>
        </a>
    @endcan

    @can('readAny', [\App\Models\Tag::class, $project])
        <a href="{{ route('projects.tags.index', $project->id) }}" @if(starts_with($master_view, 'projects.tags.')) class="current" @endif>
            <span class="icon-label"></span>
            <span class="plx"> Tags </span>
        </a>
    @endcan
</div>