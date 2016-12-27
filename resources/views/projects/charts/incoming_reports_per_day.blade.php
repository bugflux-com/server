<script>
    @php($_reports = $reports->pluck('aggregate', 'reported_at_date_string'))

    var myChart = new Chart('{{ $canvas_id }}', {
            type: 'bar',
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Incoming reports per day'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        type: 'category',
                        gridLines: {
                            display: false
                        }
                    }]
                }
            },
            data: {
                labels: [
                    @foreach(new \DatePeriod(\Carbon\Carbon::now()->subDays($period), new \DateInterval('P1D'), \Carbon\Carbon::now()) as $date)
                        '{{ $date->format('j M') }}',
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'New reports',
                        data: [
                            @foreach(new \DatePeriod(\Carbon\Carbon::now()->subDays($period), new \DateInterval('P1D'), \Carbon\Carbon::now()) as $date)
                            {{ $_reports->get($date->toDateString(), 0) }},
                            @endforeach
                        ],
                        backgroundColor: "rgba(0, 150, 136, 0.4)",
                        borderColor: "rgba(0, 150, 136, 0.8)",
                        borderWidth: 1
                    }
                ]
            }
        });
</script>