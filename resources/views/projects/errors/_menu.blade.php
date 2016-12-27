<div class="container fluid">
    <div class="mts">
        <h1 class="text-overflow">{{ $error->name }}</h1>
        <div class="font-neutral">
            @if($error->tags->isEmpty())
                <span class="text-middle"> No tags assigned </span>
            @endif

            @foreach($error->tags as $tag)
                <a href="{{ route('projects.errors.index', [$error->project_id, 'tag' => $tag->id]) }}">
                    <span class="label text-middle" style="background: {{ $tag->hex }}">
                        {{ $tag->name }}
                    </span>
                </a>
            @endforeach

            @can('connectWithTag', $error)
                <a href="{{ route('projects.errors.show', [$error->project_id, $error->id]) }}" class="text-middle">
                    <span class="font-large icon-add_box"></span>
                </a>
            @endcan
        </div>
    </div>

    <div class="mts">
        <a href="{{ route('projects.errors.index', $project->id) }}" class="button -small">
            <span class="icon-arrow_back"></span> Project
        </a>
    </div>
</div>

<div class="links mts">
    <a href="{{ route('projects.errors.show', [$project->id, $error->id]) }}" @if(in_array($master_view, ['projects.errors.show', 'projects.errors.edit'])) class="current" @endif>
        <span class="icon-info"></span>
        <span class="plx"> Overview </span>
    </a>

    @can('readAny', [\App\Models\Report::class, $error])
        <a href="{{ route('projects.errors.reports.index', [$error->project_id, $error->id]) }}"
           @if(starts_with($master_view, 'projects.errors.reports.')) class="current" @endif>
            <span class="icon-description"></span>
            <span class="plx"> Reports </span>
        </a>
    @endcan

    <a href="{{ route('projects.errors.comments.index', [$project->id, $error->id]) }}" @if(starts_with($master_view, 'projects.errors.comments.')) class="current" @endif>
        <span class="icon-question_answer"></span>
        <span class="plx"> Comments </span>
        @if($comments_count > 0)
            <span class="badge -info">{{ $comments_count }}</span>
        @endif
    </a>

    <a href="{{ route('projects.errors.duplicates.index', [$project->id, $error->id]) }}" @if(starts_with($master_view, 'projects.errors.duplicates.')) class="current" @endif>
        <span class="icon-content_copy"></span>
        <span class="plx"> Duplicates </span>
        @if($duplicates_count > 0)
            <span class="badge -warning">{{ $duplicates_count }}</span>
        @endif
    </a>
</div>