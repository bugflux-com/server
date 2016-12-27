<script>
    @php($_reports = $reports->pluck('aggregate', 'version.name'))

    var myChart = new Chart('{{ $canvas_id }}', {
            type: 'horizontalBar',
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Top 3: Total reports per version'
                },
                scales: {
                    yAxes: [{
                        type: 'category',
                        gridLines: {
                            display: false
                        }
                    }],
                    xAxes: [{
                        type: 'linear',
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            },
            data: {
                labels: [
                    @foreach($_reports->keys() as $label)
                        '{{ preg_replace('/\&[^\;]+\;/', '', e($label)) }}',
                    @endforeach
                ],
                datasets: [
                    {
                        label: ['Total reports'],
                        data: [
                            @foreach($_reports->values() as $count)
                                {{ $count }},
                            @endforeach
                        ],
                        backgroundColor: "rgba(0, 150, 136, 0.4)",
                        borderColor: "rgba(0, 150, 136, 0.8)",
                        borderWidth: 1
                    },
                ]
            }
        });
</script>