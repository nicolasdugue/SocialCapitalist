<script type="text/javascript">
            var chartLine;
            var chartLineData = [];
            var chartLineCursor;



            AmCharts.ready(function () {
                // generate some data first
                generateChartData();

                // SERIAL CHART
                chartLine = new AmCharts.AmSerialChart();
                chartLine.pathToImages = "images/";
                chartLine.dataProvider = chartLineData;
                chartLine.categoryField = "date";
                chartLine.balloon.bulletSize = 5;

                // listen for "dataUpdated" event (fired when chartLine is rendered) and call zoomChart method when it happens
                chartLine.addListener("dataUpdated", zoomChart);

                // AXES
                // category
                var categoryAxis = chartLine.categoryAxis;
                categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
                categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
                categoryAxis.dashLength = 1;
                categoryAxis.minorGridEnabled = true;
                categoryAxis.twoLineMode = true;
                categoryAxis.dateFormats = [{
                    period: 'fff',
                    format: 'JJ:NN:SS'
                }, {
                    period: 'ss',
                    format: 'JJ:NN:SS'
                }, {
                    period: 'mm',
                    format: 'JJ:NN'
                }, {
                    period: 'hh',
                    format: 'JJ:NN'
                }, {
                    period: 'DD',
                    format: 'DD'
                }, {
                    period: 'WW',
                    format: 'DD'
                }, {
                    period: 'MM',
                    format: 'MMM'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }];

                categoryAxis.axisColor = "#DADADA";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.axisAlpha = 0;
                valueAxis.dashLength = 1;
                chartLine.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.title = "red line";
                graph.valueField = "visits";
                graph.bullet = "round";
                graph.bulletBorderColor = "#FFFFFF";
                graph.bulletBorderThickness = 2;
                graph.bulletBorderAlpha = 1;
                graph.lineThickness = 2;
                graph.lineColor = "#5fb503";
                graph.negativeLineColor = "#efcc26";
                graph.hideBulletsCount = 50; // this makes the chartLine to hide bullets when there are more than 50 series in selection
                chartLine.addGraph(graph);

                // CURSOR
                chartLineCursor = new AmCharts.ChartCursor();
                chartLineCursor.cursorPosition = "mouse";
                chartLineCursor.pan = true; // set it to fals if you want the cursor to work in "select" mode
                chartLine.addChartCursor(chartLineCursor);

                // SCROLLBAR
                var chartLineScrollbar = new AmCharts.ChartScrollbar();
                chartLine.addChartScrollbar(chartLineScrollbar);

                chartLine.creditsPosition = "bottom-right";

                // WRITE
                chartLine.write("content");
            });

            // generate some random data, quite different range
            function generateChartData() {
                var firstDate = new Date();
                firstDate.setDate(firstDate.getDate() - 500);

                for (var i = 0; i < 500; i++) {
                    // we create date objects here. In your data, you can have date strings
                    // and then set format of your dates using chartLine.dataDateFormat property,
                    // however when possible, use date objects, as this will speed up chartLine rendering.
                    var newDate = new Date(firstDate);
                    newDate.setDate(newDate.getDate() + i);

                    var visits = Math.round(Math.random() * 40) - 20;

                    chartLineData.push({
                        date: newDate,
                        visits: visits
                    });
                }
            }

            // this method is called when chartLine is first inited as we listen for "dataUpdated" event
            function zoomChart() {
                // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                chartLine.zoomToIndexes(chartLineData.length - 40, chartLineData.length - 1);
            }

            // changes cursor mode from pan to select
            function setPanSelect() {
                if (document.getElementById("rb1").checked) {
                    chartLineCursor.pan = false;
                    chartLineCursor.zoomable = true;
                } else {
                    chartLineCursor.pan = true;
                }
                chartLine.validateNow();
            }

        </script>
