@props(['series'])

@section('styles')
    <!-- tui charts Css -->
    <link href="{{asset('assets/libs/tui-chart/tui-chart.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <!-- tui charts plugins -->
    <script src="{{asset('assets/libs/tui-chart/tui-chart-all.min.js')}}"></script>

    <!-- tui charts map -->
    <script src="{{asset('assets/libs/tui-chart/maps/usa.js')}}"></script>

    <!-- tui charts plugins -->
    <script src="{{asset('assets/js/pages/tui-charts.init.js')}}"></script>

    <script>

        var barchartColors = getChartColorsArray("donut-charts"),
            donutpieChartWidth = document.getElementById("donut-charts").offsetWidth,
            container = document.getElementById("donut-charts"),
            data = {
                categories: ["Summary"],
                series: <?php echo $series; ?>,
            },
            options = {
                chart: {
                    width: donutpieChartWidth,
                    height: 380,
                    title: "History",
                    format: function (e, a, t, r, o) {
                        return "makingSeriesLabel" === t && (e += " ৳"), e;
                    },
                },
                series: { radiusRange: ["40%", "100%"], showLabel: !0 },
                tooltip: { suffix: " ৳" },
                legend: { align: "bottom" },
            },
            theme = {
                chart: { background: { color: "#fff", opacity: 0 } },
                title: { color: "#8791af" },
                plot: { lineColor: "rgba(166, 176, 207, 0.1)" },
                legend: { label: { color: "#8791af" } },
                series: { series: { colors: barchartColors }, label: { color: "#fff", fontFamily: "sans-serif" } },
            };

        tui.chart.registerTheme("myTheme", theme), (options.theme = "myTheme");
        var donutChart = tui.chart.pieChart(container, data, options);
        window.onresize = function () {
            donutChart.resize({ width: donutpieChartWidth, height: 350 });
        };
    </script>

@endsection


