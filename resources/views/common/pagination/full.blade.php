@php($range = !empty($range) ? $range : 3)
@if($paginator->hasPages())
    <div class="pagination {{ $class }}">
        {{-- Prev/Next buttons --}}
        @include('common.pagination.simple', ['paginator' => $paginator, 'class' => 'mtx mrs'])

        {{-- Stats --}}
        <span class="font-small iblock text-middle mtx sm-hide md-hide lg-hide">
            <span> Page <b>{{ $paginator->currentPage() }}</b> of {{ $paginator->lastPage() }}</span>
        </span>

        <span class="input-group mtx xs-hide">
            {{-- First item --}}
            @if($paginator->currentPage() > 1)
                <a href="{{ $paginator->url(1) }}" class="button -small">1</a>
            @endif

            {{-- Divider --}}
            @if($paginator->currentPage() - $range > 2)
                <span class="font-light mlx mrx"></span>
            @endif

            {{-- Previous pages --}}
            @for($i = max(2, $paginator->currentPage() - $range); $i < $paginator->currentPage(); ++$i)
                <a href="{{ $paginator->url($i) }}" class="button -small">{{ $i }}</a>
            @endfor

            {{-- Current page --}}
            <a class="button -small"><b>{{ $paginator->currentPage() }}</b></a>

            {{-- Next pages --}}
            @for($i = $paginator->currentPage() + 1; $i <= min($paginator->currentPage() + $range, $paginator->lastPage() - 1); ++$i)
                <a href="{{ $paginator->url($i) }}" class="button -small">{{ $i }}</a>
            @endfor

            {{-- Divider --}}
            @if($paginator->currentPage() + $range < $paginator->lastPage() - 1)
                <span class="font-light mlx mrx"></span>
            @endif

            {{-- Last item --}}
            @if($paginator->currentPage() < $paginator->lastPage())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="button -small">{{ $paginator->lastPage() }}</a>
            @endif
        </span>
    </div>
@endif