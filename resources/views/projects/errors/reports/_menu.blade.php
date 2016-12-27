<div class="container fluid">
    <div class="mts">
        <h1 class="text-overflow">{{ $report->name }}</h1>
        <div class="font-neutral" title="{{ $report->reported_at }}">{{ $report->reported_at->diffForHumans() }}</div>
    </div>

    <div class="mts">
        <a href="{{ route('projects.errors.index', $project->id) }}" class="button -small">
            <span class="icon-arrow_back"></span> Project
        </a>

        <a href="{{ route('projects.errors.reports.index', [$project->id, $error->id]) }}" class="button -small">
            <span class="icon-arrow_back"></span> Error
        </a>
    </div>
</div>

<div class="links mts">
    <a href="{{ route('projects.errors.reports.show', [$project->id, $error->id, $report->id]) }}" @if($master_view == 'projects.errors.reports.show') class="current" @endif>
        <span class="icon-info"></span>
        <span class="plx"> Overview </span>
    </a>

    {{-- TODO: Dodać widok raportu nieptrzetworzonego --}}

    <a href="{{ route('projects.errors.reports.comments.index', [$project->id, $error->id, $report->id]) }}" @if(starts_with($master_view, 'projects.errors.reports.comments.')) class="current" @endif>
        <span class="icon-question_answer"></span>
        <span class="plx"> Comments </span>
        @if($comments_count > 0)
            <span class="badge -info">{{ $comments_count }}</span>
        @endif
    </a>
</div>