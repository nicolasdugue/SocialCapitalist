<script type="text/javascript">
    var chart;
    var legend;

    var chartData = [{
        country: "Czech Republic",
        litres: 301.90
    }, {
        country: "Ireland",
        litres: 201.10
    }, {
        country: "Germany",
        litres: 165.80
    }, {
        country: "Australia",
        litres: 139.90
    }, {
        country: "Austria",
        litres: 128.30
    }, {
        country: "UK",
        litres: 99.00
    }, {
        country: "Belgium",
        litres: 60.00
    }];

    AmCharts.ready(function () {
        // PIE CHART
        chart = new AmCharts.AmPieChart();
        chart.dataProvider = chartData;
        chart.titleField = "country";
        chart.valueField = "litres";
        chart.outlineColor = "#FFFFFF";
        chart.outlineAlpha = 0.8;
        chart.outlineThickness = 2;

        // WRITE
        chart.write("activity");
    });
</script>

<script type="text/javascript">
    var chart;
    var legend;

    var chartData = [{
        country: "Czech Republic",
        litres: 301.90
    }, {
        country: "Ireland",
        litres: 201.10
    }, {
        country: "Germany",
        litres: 165.80
    }, {
        country: "Australia",
        litres: 139.90
    }, {
        country: "Austria",
        litres: 128.30
    }, {
        country: "UK",
        litres: 99.00
    }, {
        country: "Belgium",
        litres: 60.00
    }];

    AmCharts.ready(function () {
        // PIE CHART
        chart = new AmCharts.AmPieChart();
        chart.dataProvider = chartData;
        chart.titleField = "country";
        chart.valueField = "litres";
        chart.outlineColor = "#FFFFFF";
        chart.outlineAlpha = 0.8;
        chart.outlineThickness = 2;

        // WRITE
        chart.write("activity1");
    });
</script>

<script type="text/javascript">
    var chart;
    var legend;

    var chartData = [{
        country: "Czech Republic",
        litres: 301.90
    }, {
        country: "Ireland",
        litres: 201.10
    }, {
        country: "Germany",
        litres: 165.80
    }, {
        country: "Australia",
        litres: 139.90
    }, {
        country: "Austria",
        litres: 128.30
    }, {
        country: "UK",
        litres: 99.00
    }, {
        country: "Belgium",
        litres: 60.00
    }];

    AmCharts.ready(function () {
        // PIE CHART
        chart = new AmCharts.AmPieChart();
        chart.dataProvider = chartData;
        chart.titleField = "country";
        chart.valueField = "litres";
        chart.outlineColor = "#FFFFFF";
        chart.outlineAlpha = 0.8;
        chart.outlineThickness = 2;

        // WRITE
        chart.write("activity2");
    });
</script>
