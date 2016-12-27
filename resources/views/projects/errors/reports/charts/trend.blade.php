@php($_reports = $reports->pluck('aggregate', 'reported_at_date_string'))
@php($_max_value = $_reports->max())

<div style="height: 20px">
    @foreach(new \DatePeriod(\Carbon\Carbon::now()->subDays($period), new \DateInterval('P1D'), \Carbon\Carbon::now()) as $date)
        @php($_value = $_reports->get($date->toDateString(), 0))
        @if($_value > 0)
            <div title="{{ $_value }}" style="background: crimson; width: 5px; display: inline-block; height: {{ $_value / $_max_value * 100 }}%"></div>
        @else
            <div style="background: gray; width: 5px; display: inline-block; height: 1px"></div>
        @endif
    @endforeach
</div>