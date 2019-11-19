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
                            <p>No of COunties</p>
                        </div>
                    </section>
                </div>   <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>
                        <div class="value">
                            <h1 class="consent_tile">  </h1>

                            <p>No of Sub Counties </p>
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
                            <p>No of Facilities</p>
                        </div>
                    </section>
                </div>
               

            </div>
        </div>




        <div class="panel panel-primary">




            <div class="panel-body"> 

                <!--DYNAMIC CHART STARTS HERE-->

                <div class="row">

                    <div class="col-md-6">

                        <div id="gender_chart" class="gender_chart" ></div> 

                    </div>
                    <div class="col-md-6">

                        <div id="language_chart" class="language_chart" ></div>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">


                        <p id="marital_piechart" class="marital_piechart" ></p>

                    </div>
                    <div class="col-md-6">

                        <p id="client_type_chart" class="client_type_chart" ></p>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">


                        <p id="client_type_barchart" class="client_type_barchart" ></p>

                    </div>
                    <div class="col-md-6">
                        <p id="client_age_group_barchart" class="client_age_group_barchart" ></p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">


                        <p id="consented_client_chart" class="consented_client_chart" ></p>

                    </div>
                    <div class="col-md-6">
                        <p id="consented_client_json" class="consented_client_json"></p>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <p id="client_condition_chart" class="client_condition_chart" ></p>

                    </div>
                    <div class="col-md-6">
                        <p id="client_status_chart" class="client_status_chart" ></p>
                    </div>
                </div>






                <!--DYNAMIC CHART ENDS HERE-->
            </div>

           



        </div>        

    </div>

</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>



<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">




    $(document).ready(function () {

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
        draw_chart(county, sub_county, facility, date_from, date_to);
        function draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two) {
            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + '</br>' + description_two;
            }
            var processed_json = new Array();


            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_gender_reports/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    for (i = 0; i < data.length; i++) {
                        processed_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('gender_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Gender</br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: processed_json
                            }]
                    });

                }, error: function (errorThrown) {

                }
            });


//            $.getJSON('<?php echo base_url(); ?>Reports/get_gender_reports/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                // Populate series
//                for (i = 0; i < data.length; i++) {
//                    processed_json.push([data[i].name, parseInt(data[i].value)]);
//                }
//
//               
//            });
            var language_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_language/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        language_json.push([data[i].NAME, parseInt(data[i].VALUE)]);
                    }

                    // draw chart
                    Highcharts.chart('language_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Language </br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: language_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });





//
//            $.getJSON('<?php echo base_url(); ?>Reports/get_client_language/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//
//            });
            var marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_marital_reports/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        marital_json.push([data[i].NAME, parseInt(data[i].VALUE)]);
                    }

                    // draw chart
                    Highcharts.chart('marital_piechart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Marital Status'
                        }, subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: marital_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });




//            $.getJSON('<?php echo base_url(); ?>Reports/get_marital_reports/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//                
//            });




            var client_type_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_type/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_type_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_type_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Type'
                        }, subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: client_type_json
                            }]
                    });
                }, error: function (error) {

                }

            });

//            $.getJSON('<?php echo base_url(); ?>Reports/get_client_type/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '', function (data) {
//
//            });

            var client_type_json2 = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_category/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_type_json2.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart

                    Highcharts.chart('client_type_barchart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: "Client Distribution by Type"
                        },
                        subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: client_type_json2
                            }]
                    });
                }, error: function (errorThrown) {

                }});


            var client_age_group_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_age_group/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_age_group_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart

                    Highcharts.chart('client_age_group_barchart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: "Client Distribution by Age Group"
                        },
                        subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: client_age_group_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });





            var client_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_status/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_status_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_status_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Status'
                        }, subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: client_status_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });


            var consented_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_consented_clients/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('consented_client_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Consented for SMS Alerts'
                        }, subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: consented_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });









            var consented_column_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_consented_clients/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_column_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart

                    Highcharts.chart('consented_client_json', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: "Client Consented for SMS Alerts"
                        },
                        subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: consented_column_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });







            var condition_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_condition/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        condition_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_condition_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Condition'
                        }, subtitle: {
                            text: 'Source: <a href="https://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: condition_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });



        }




        function client_tile(county, sub_county, facility, date_from, date_to) {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/client_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (response) {

                    $(".client_tile").empty();
                    $(".client_tile").append(response);
                }, error: function (data) {

                }
            })
        }






        function consent_tile(county, sub_county, facility, date_from, date_to) {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/consented_clients_json/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
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
                type: "POST",
                url: '<?php echo base_url(); ?>reports/partner_info/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
                success: function (response) {
                    $(".partner_tile").empty();
                    $(".partner_tile").append(response);
                }, error: function (data) {

                }
            })
        }

        function facilities_tile(county, sub_county, facility, date_from, date_to) {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/facility_info/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to},
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