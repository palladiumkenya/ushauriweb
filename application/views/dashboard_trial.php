<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->


<div class="row state-overview" >

    <div class="col-lg-12">

        <div class="state col-lg-12">
            <div class="row">
                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>
                        <div class="value">
                            <h1 class="client_tile">
                                <?php
                                echo $client_info;
                                ?>
                            </h1>
                            <p>No of Clients</p>
                        </div>
                    </section>
                </div>   <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>
                        <div class="value">
                            <h1 class="consent_tile">  <?php
                                foreach ($consented_clients as $value) {
                                    $clients = $value->all_clients;
                                    $consented_clients = $value->consented_clients;
                                    if ($clients == 0) {
                                        echo "0 %";
                                    } else {
                                        $percentage = ($consented_clients / $clients) * 100;
                                        $percentage = round($percentage, 2);
                                        echo $percentage . '%';
                                    }
                                }
                                ?></h1>

                            <p>% of Consented Clients </p>
                        </div>
                    </section>
                </div>

                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-building">

                            </i>
                        </div>
                        <div class="value">
                            <h1 class="partner_tile"></h1>
                            <p>Partners</p>
                        </div>
                    </section>
                </div><div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>
                        <div class="value">
                            <h1 class="facilities_tile"></h1>
                            <p>Facilities</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>




        <div class="panel panel-primary">
            <div class="cont" id="cont">

                <?php 
                $chart  = & get_instance();
                $chart->render_data();
                ?>
                
                
            </div>

       

            



        </div>        

    </div>

</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>


<!--
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>-->

<script type="text/javascript">




    $(document).ready(function () {



        $.getJSON('http://localhost/t4a/reports/render_data', function (data) {
            $('#container').Highcharts(data);
        });


        $(".filter_dashboard").click(function () {
            var county = $(".filter_county").val();
            var sub_county = $(".filter_sub_county").val();
            var facility = $(".filter_facility").val();
            var date_from = $(".date_from").val();
            var date_to = $(".date_to").val();




            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_date_from = "";
            var selected_date_to = "";

            if (date_from.length > 0) {
                selected_date_from = "From : " + $(".date_from").val() + " - ";
            }

            if (date_to.length > 0) {
                selected_date_to = "To : " + $(".date_from").val();
            }


            if (county != "") {
                selected_county = "For " + $(".filter_county option:selected").text() + " County ";

            }
            if (sub_county != "") {
                selected_sub_county = ", " + $(".filter_sub_county option:selected").text() + "Sub County ";

            }
            if (facility != "") {
                selected_facility = "," + $(".filter_facility option:selected").text() + "Facility";

            }

            var description_one = "" + selected_county + " " + selected_sub_county + "  " + selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two);


            client_tile(county, sub_county, facility, date_from, date_to);
            consent_tile(county, sub_county, facility, date_from, date_to);
            partners_tile(county, sub_county, facility, date_from, date_to);
            facilities_tile(county, sub_county, facility, date_from, date_to);
        });
        var county = $(".filter_county").val();
        var sub_county = $(".filter_sub_county").val();
        var facility = $(".filter_facility").val();
        var date_from = $(".date_from").val();
        var date_to = $(".date_to").val();
//        draw_chart(county, sub_county, facility, date_from, date_to);
//        function draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two) {
//            var final_description;
//            if (description_one == undefined && description_two != undefined) {
//                final_description = " " + description_two;
//            } else if (description_one != undefined && description_two == undefined) {
//                final_description = " " + description_one;
//            } else if (description_one == undefined && description_two == undefined) {
//                final_description = " ";
//            } else {
//                final_description = description_one + '</br>' + description_two;
//            }
//            var processed_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>Reports/get_gender_reports/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    processed_json.push([data[i].name, parseInt(data[i].value)]);
//                }
//
//                // draw chart
//                Highcharts.chart('gender_chart', {
//                    chart: {
//                        plotBackgroundColor: null,
//                        plotBorderWidth: null,
//                        plotShadow: false,
//                        type: 'pie'
//                    },
//                    title: {
//                        text: 'Client Distribution by Gender</br> ' + final_description + ' '
//                    }, subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    tooltip: {
//                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//                    }, credits: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        pie: {
//                            allowPointSelect: true,
//                            cursor: 'pointer',
//                            dataLabels: {
//                                enabled: true,
//                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                                style: {
//                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                                }
//                            }
//                        }
//                    },
//                    series: [{
//                            name: 'Clients',
//                            colorByPoint: true,
//                            data: processed_json
//                        }]
//                });
//            });
//            var language_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>Reports/get_client_language/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    language_json.push([data[i].NAME, parseInt(data[i].VALUE)]);
//                }
//
//                // draw chart
//                Highcharts.chart('language_chart', {
//                    chart: {
//                        plotBackgroundColor: null,
//                        plotBorderWidth: null,
//                        plotShadow: false,
//                        type: 'pie'
//                    },
//                    title: {
//                        text: 'Client Distribution by Language </br> ' + final_description + ''
//                    }, subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    tooltip: {
//                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//                    }, credits: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        pie: {
//                            allowPointSelect: true,
//                            cursor: 'pointer',
//                            dataLabels: {
//                                enabled: true,
//                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                                style: {
//                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                                }
//                            }
//                        }
//                    },
//                    series: [{
//                            name: 'Clients',
//                            colorByPoint: true,
//                            data: language_json
//                        }]
//                });
//            });
//            var marital_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>Reports/get_marital_reports/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    marital_json.push([data[i].NAME, parseInt(data[i].VALUE)]);
//                }
//
//                // draw chart
//                Highcharts.chart('marital_piechart', {
//                    chart: {
//                        plotBackgroundColor: null,
//                        plotBorderWidth: null,
//                        plotShadow: false,
//                        type: 'pie'
//                    },
//                    title: {
//                        text: 'Client Distribution by Marital Status </br> ' + final_description + ''
//                    }, subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    tooltip: {
//                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//                    }, credits: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        pie: {
//                            allowPointSelect: true,
//                            cursor: 'pointer',
//                            dataLabels: {
//                                enabled: true,
//                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                                style: {
//                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                                }
//                            }
//                        }
//                    },
//                    series: [{
//                            name: 'Clients',
//                            colorByPoint: true,
//                            data: marital_json
//                        }]
//                });
//            });
//            var client_type_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>Reports/get_client_type/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    client_type_json.push([data[i].k, parseInt(data[i].v)]);
//                }
//
//                // draw chart
//                Highcharts.chart('client_type_chart', {
//                    chart: {
//                        plotBackgroundColor: null,
//                        plotBorderWidth: null,
//                        plotShadow: false,
//                        type: 'pie'
//                    },
//                    title: {
//                        text: 'Client Distribution by Type </br> ' + final_description + ''
//                    }, subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    tooltip: {
//                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//                    }, credits: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        pie: {
//                            allowPointSelect: true,
//                            cursor: 'pointer',
//                            dataLabels: {
//                                enabled: true,
//                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                                style: {
//                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                                }
//                            }
//                        }
//                    },
//                    series: [{
//                            name: 'Clients',
//                            colorByPoint: true,
//                            data: client_type_json
//                        }]
//                });
//            });
//            var client_type_json2 = new Array();
//            $.getJSON('<?php echo base_url(); ?>reports/get_client_category/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    client_type_json2.push([data[i].k, parseInt(data[i].v)]);
//                }
//
//                // draw chart
//
//                Highcharts.chart('client_type_barchart', {
//                    chart: {
//                        type: "column"
//                    },
//                    title: {
//                        text: 'Client Distribution by Type </br> ' + final_description + ' '
//                    },
//                    subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    xAxis: {
//                        type: 'category',
//                        allowDecimals: false,
//                        title: {
//                            text: ""
//                        }
//                    },
//                    yAxis: {
//                        min: 0,
//                        title: {
//                            text: "No of Clients",
//                            align: 'high'
//                        },
//                        labels: {
//                            overflow: 'justify'
//                        }
//                    }, plotOptions: {
//                        bar: {
//                            dataLabels: {
//                                enabled: true
//                            }
//                        }
//                    }, legend: {
//                        layout: 'vertical',
//                        align: 'right',
//                        verticalAlign: 'top',
//                        x: -40,
//                        y: 80,
//                        floating: true,
//                        borderWidth: 1,
//                        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
//                        shadow: true
//                    },
//                    credits: {
//                        enabled: false
//                    },
//                    series: [{
//                            name: 'Clients',
//                            data: client_type_json2
//                        }]
//                });
//            });
//            var client_age_group_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>reports/get_client_age_group/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    client_age_group_json.push([data[i].k, parseInt(data[i].v)]);
//                }
//
//                // draw chart
//
//                Highcharts.chart('client_age_group_barchart', {
//                    chart: {
//                        type: "column"
//                    },
//                    title: {
//                        text: 'Client Distribution by Age Group </br> ' + final_description + ''
//                    },
//                    subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    xAxis: {
//                        type: 'category',
//                        allowDecimals: false,
//                        title: {
//                            text: ""
//                        }
//                    },
//                    yAxis: {
//                        min: 0,
//                        title: {
//                            text: "No of Clients",
//                            align: 'high'
//                        },
//                        labels: {
//                            overflow: 'justify'
//                        }
//                    }, plotOptions: {
//                        bar: {
//                            dataLabels: {
//                                enabled: true
//                            }
//                        }
//                    }, legend: {
//                        layout: 'vertical',
//                        align: 'right',
//                        verticalAlign: 'top',
//                        x: -40,
//                        y: 80,
//                        floating: true,
//                        borderWidth: 1,
//                        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
//                        shadow: true
//                    },
//                    credits: {
//                        enabled: false
//                    },
//                    series: [{
//                            name: 'Clients',
//                            data: client_age_group_json
//                        }]
//                });
//            });
//            var client_status_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>Reports/get_client_status/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    client_status_json.push([data[i].k, parseInt(data[i].v)]);
//                }
//
//                // draw chart
//                Highcharts.chart('client_status_chart', {
//                    chart: {
//                        plotBackgroundColor: null,
//                        plotBorderWidth: null,
//                        plotShadow: false,
//                        type: 'pie'
//                    },
//                    title: {
//                        text: 'Client Consented for SMS Alerts </br> ' + final_description + ''
//                    }, subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    tooltip: {
//                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//                    }, credits: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        pie: {
//                            allowPointSelect: true,
//                            cursor: 'pointer',
//                            dataLabels: {
//                                enabled: true,
//                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                                style: {
//                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                                }
//                            }
//                        }
//                    },
//                    series: [{
//                            name: 'Clients',
//                            colorByPoint: true,
//                            data: client_status_json
//                        }]
//                });
//            });
//            var consented_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>Reports/get_consented_clients/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    consented_json.push([data[i].k, parseInt(data[i].v)]);
//                }
//
//                // draw chart
//                Highcharts.chart('consented_client_chart', {
//                    chart: {
//                        plotBackgroundColor: null,
//                        plotBorderWidth: null,
//                        plotShadow: false,
//                        type: 'pie'
//                    },
//                    title: {
//                        text: 'Client Consented for SMS Alerts </br> ' + final_description + ''
//                    }, subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    tooltip: {
//                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//                    }, credits: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        pie: {
//                            allowPointSelect: true,
//                            cursor: 'pointer',
//                            dataLabels: {
//                                enabled: true,
//                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                                style: {
//                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                                }
//                            }
//                        }
//                    },
//                    series: [{
//                            name: 'Clients',
//                            colorByPoint: true,
//                            data: consented_json
//                        }]
//                });
//            });
//            var condition_json = new Array();
//            $.getJSON('<?php echo base_url(); ?>Reports/get_client_condition/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    condition_json.push([data[i].k, parseInt(data[i].v)]);
//                }
//
//                // draw chart
//                Highcharts.chart('client_condition_chart', {
//                    chart: {
//                        plotBackgroundColor: null,
//                        plotBorderWidth: null,
//                        plotShadow: false,
//                        type: 'pie'
//                    },
//                    title: {
//                        text: 'Client Distribution by Condition </br> ' + final_description + ''
//                    }, subtitle: {
//                        text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
//                    },
//                    tooltip: {
//                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//                    }, credits: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        pie: {
//                            allowPointSelect: true,
//                            cursor: 'pointer',
//                            dataLabels: {
//                                enabled: true,
//                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
//                                style: {
//                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
//                                }
//                            }
//                        }
//                    },
//                    series: [{
//                            name: 'Clients',
//                            colorByPoint: true,
//                            data: condition_json
//                        }]
//                });
//            });
//        }




        function client_tile(county, sub_county, facility, date_from, date_to) {
            $.ajax({
                type: "GET",
                url: '<?php echo base_url(); ?>reports/client_info_json/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                success: function (response) {

                    $(".client_tile").empty();
                    $(".client_tile").append(response);
                }, error: function (data) {

                }
            })
        }






        function consent_tile(county, sub_county, facility, date_from, date_to) {
            $.ajax({
                type: "GET",
                url: '<?php echo base_url(); ?>reports/consented_clients_json/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                success: function (response) {
                    var consented_clients = response[0].consented_clients;
                    var all_clients = response[0].all_clients;
                    var response = ((consented_clients / all_clients) * 100).toFixed(2);
                    $(".consent_tile").empty();
                    $(".consent_tile").append(response + "% ");
                }, error: function (data) {

                }
            })
        }


        function  partners_tile(county, sub_county, facility, date_from, date_to) {
            $.ajax({
                type: "GET",
                url: '<?php echo base_url(); ?>reports/partner_info/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/json/',
                dataType: "json",
                success: function (response) {
                    $(".partner_tile").empty();
                    $(".partner_tile").append(response);
                }, error: function (data) {

                }
            })
        }

        function facilities_tile(county, sub_county, facility, date_from, date_to) {
            $.ajax({
                type: "GET",
                url: '<?php echo base_url(); ?>reports/facility_info/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/json/',
                dataType: "json",
                success: function (response) {
                    $(".facilities_tile").empty();
                    $(".facilities_tile").append(response);
                }, error: function (data) {

                }
            })
        }



    });






</script>


<!--END MAIN WRAPPER -->