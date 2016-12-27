<div class="tabs">
    <a class="tabs-item {{ !$display_default_mappings ? '-active' : '' }}"
       href="{{ route('projects.mappings.index', $project->id) }}">
        <span> Customized </span>
    </a>
    <a class="tabs-item {{ $display_default_mappings ? '-active' : '' }}"
       href="{{ route('projects.default-mappings', $project->id) }}">
        <span> Default </span>
    </a>
</div>