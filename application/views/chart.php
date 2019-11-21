<!DOCTYPE html>
<html lang="en">
    <head>
        <title>dc.js - Bar Chart Example</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/dcjs/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/dcjs/css/dc.css"/>
    </head>
    <body>

        <div class="container">
            <script type="text/javascript" src="<?php echo base_url()?>assets/dcjs/header.js"></script>
            <div id="test"></div>

            <script type="text/javascript" src="<?php echo base_url()?>assets/js/d3.js"></script>
            <script type="text/javascript" src="<?php echo base_url()?>assets/js/crossfilter.js"></script>
            <script type="text/javascript" src="<?php echo base_url()?>assets/js/dc.js"></script>
            <script type="text/javascript">

                var chart = dc.barChart("#test");
                d3.json("<?php echo base_url();?>home/sum_appointment_status", function (error, experiments) {
                    console.log(experiments);
                    experiments.forEach(function (x) {
                        x.Speed = +x.Speed;
                    });

                    var ndx = crossfilter(experiments),
                            runDimension = ndx.dimension(function (d) {
                                return +d.Run;
                            }),
                            speedSumGroup = runDimension.group().reduceSum(function (d) {
                        return d.Speed * d.Run / 1000;
                    });

                    chart
                            .width(768)
                            .height(480)
                            .x(d3.scale.linear().domain([6, 20]))
                            .brushOn(false)
                            .yAxisLabel("This is the Y Axis!")
                            .dimension(runDimension)
                            .group(speedSumGroup)
                            .on('renderlet', function (chart) {
                                chart.selectAll('rect').on("click", function (d) {
                                    console.log("click!", d);
                                });
                            });
                    chart.render();
                });

            </script>

        </div>
    </body>
</html>