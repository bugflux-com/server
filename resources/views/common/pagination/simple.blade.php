@if($paginator->hasPages())
    <span class="input-group {{ $class }}">
        @if($paginator->previousPageUrl() != null)
            <a class="button -small" href="{{ $paginator->previousPageUrl() }}" title="Previous page"><span class="icon-keyboard_arrow_left"></span></a>
        @else
            <a class="button -small -disabled" title="Previous page doesn't exists"><span class="icon-keyboard_arrow_left"></span></a>
        @endif

        @if($paginator->nextPageUrl() != null)
            <a class="button -small" href="{{ $paginator->nextPageUrl() }}" title="Next page"><span class="icon-keyboard_arrow_right"></span></a>
        @else
            <a class="button -small -disabled" title="Next page doesn't exists"><span class="icon-keyboard_arrow_right"></span></a>
        @endif
    </span>
@endif