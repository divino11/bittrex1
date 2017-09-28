<?php
require_once 'db/db.php';
mysqli_set_charset($link, 'utf8');
$sql = "SELECT * FROM `bit`";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
    echo "<br>" . "{$row['result1']}";
    echo "<br>" . "{$row['result2']}";
    echo "<br>" . "{$row['result3']}";

?>
<html>
<head>
 <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>
<div id="container1"></div>
<div id="container2"></div>
<div id="container3"></div>
</body>
</html>


<script>

        var A2 = <?php echo $row['result2'] ?>;
        var A3 = <?php echo $row['result3'] ?>;
</script>
<script type="text/javascript">
    var A1 = <?php echo $row['result1'] ?>;
    Highcharts.chart('container1', {
        chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10,
            events: {
                load: function () {
                    // set up the updating of the chart each second
                    var seriesA1 = this.series[0];

                    setInterval(function () {
                        A1 = <?php echo $row['result1']; ?>;
                        var x = (new Date()).getTime(); // current time
                        if (A1) {
                            y1 = A1;
                            seriesA1.addPoint([x, y1], false, true);
                        }
                    }, 1000);
                }
            }
        },
        title: {
            text: 'Live random data'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150
        },
        yAxis: {
            title: {
                text: 'Value'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueDecimals: 9
            /* formatter: function () {
                 return '<b>' + this.series.name + '</b><br/>' +
                     Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                     Highcharts.numberFormat(this.y, 2);
             }*/
        },
        legend: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        series: [{
            name: 'Sell orders',
            data: (function () {
                // generate an array of random data
                var data = [],
                    time = (new Date()).getTime(),
                    i;

                for (i = -100; i <= 0; i += 1) {
                    data.push({
                        x: time + i * 1000,
                        y: A1
                    });
                }
                return data;
            }())
        }
        ]
    });
</script>
<script type="text/javascript">
    Highcharts.chart('container2', {
        chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10,
            events: {
                load: function () {
                    // set up the updating of the chart each second
                    var seriesA2 = this.series[0];

                    setInterval(function () {
                        A2 = <?php echo $row['result2']; ?>;
                        var x = (new Date()).getTime(); // current time
                        if (A) {
                            y1 = A2;
                            seriesA2.addPoint([x, y1], false, true);
                        }
                    }, 1000);
                }
            }
        },
        title: {
            text: 'Live random data'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150
        },
        yAxis: {
            title: {
                text: 'Value'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueDecimals: 9
            /* formatter: function () {
                 return '<b>' + this.series.name + '</b><br/>' +
                     Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                     Highcharts.numberFormat(this.y, 2);
             }*/
        },
        legend: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        series: [{
            name: 'Sell orders',
            data: (function () {
                // generate an array of random data
                var data = [],
                    time = (new Date()).getTime(),
                    i;

                for (i = -100; i <= 0; i += 1) {
                    data.push({
                        x: time + i * 1000,
                        y: A2
                    });
                }
                return data;
            }())
        }
        ]
    });
</script>
<script type="text/javascript">
    Highcharts.chart('container3', {
        chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10,
            events: {
                load: function () {
                    // set up the updating of the chart each second
                    var seriesA3 = this.series[0];

                    setInterval(function () {
                        A3 = <?php echo $row['result3']; ?>;
                        var x = (new Date()).getTime(); // current time
                        if (A3) {
                            y1 = A3;
                            seriesA3.addPoint([x, y1], false, true);
                        }
                    }, 1000);
                }
            }
        },
        title: {
            text: 'Live random data'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150
        },
        yAxis: {
            title: {
                text: 'Value'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueDecimals: 9
            /* formatter: function () {
                 return '<b>' + this.series.name + '</b><br/>' +
                     Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                     Highcharts.numberFormat(this.y, 2);
             }*/
        },
        legend: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        series: [{
            name: 'Sell orders',
            data: (function () {
                // generate an array of random data
                var data = [],
                    time = (new Date()).getTime(),
                    i;

                for (i = -100; i <= 0; i += 1) {
                    data.push({
                        x: time + i * 1000,
                        y: A3
                    });
                }
                return data;
            }())
        }
        ]
    });
</script>
