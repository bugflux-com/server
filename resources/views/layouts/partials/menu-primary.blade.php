<div class="links">
    <a href="{{ route('projects.index') }}"
       @if(starts_with($master_view, 'projects.')) class="current" @endif>
        <span class="icon-layers"></span>
        <span class="mlx">@lang('links.projects')</span>
    </a>
    <a href="{{ route('users.index') }}"
       @if(starts_with($master_view, 'users.')) class="current" @endif>
        <span class="icon-supervisor_account"></span>
        <span class="mlx">@lang('links.users')</span>
    </a>
    <a href="{{ route('systems.index') }}"
       @if(starts_with($master_view, 'systems.')) class="current" @endif>
        <span class="icon-phonelink"></span>
        <span class="mlx">@lang('links.systems')</span>
    </a>
    <a href="{{ route('languages.index') }}"
       @if(starts_with($master_view, 'languages.')) class="current" @endif>
        <span class="icon-language"></span>
        <span class="mlx">@lang('links.languages')</span>
    </a>
</div>