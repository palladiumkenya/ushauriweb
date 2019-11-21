<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->


<div class="row state-overview" >

    <div class="col-lg-12">

        <div class="state col-lg-12">
            <div class="row">
                <div class="state col-lg-2 col-sm-2">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>
                        <div class="value">
                            <a href="javascript:void()" class="all_appointments_btn" id="all_appointments_btn">
                                <h4 class="client_tile">
                                    <?php
                                    echo number_format($all_appointments);
                                    ?>
                                </h4>
                                <h6>No of Appointments</h6>   
                            </a>

                        </div>
                    </section>
                </div>   <div class="state col-lg-2 col-sm-2">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>
                        <div class="value">
                            <a href="javascript:void()" class="current_appointments_btn" id="current_appointments_btn">
                                <h4 class="consent_tile">   <?php
                                    echo number_format($current_appointments);
                                    ?></h4>

                                <h6>No of Current Appointments </h6>
                            </a>

                        </div>

                    </section>
                </div>







                <div class="state col-lg-2 col-sm-2">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>

                        <a href="javascript:void()" class="missed_appointments_btn" id="missed_appointments_btn">
                            <div class="value">
                                <h4 class="appointments_tile">
                                    <?php echo number_format($missed_appointments); ?>

                                </h4>
                                <h6>Total No of  Missed Appointments</h6>
                            </div> 
                        </a>


                    </section>
                </div>



                <div class="state col-lg-2 col-sm-2">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-building">

                            </i>
                        </div>
                        <a href="javascript:void()" class="defaulted_appointments_btn" id="defaulted_appointments_btn">

                        </a>
                        <div class="value">
                            <h4 class="active_appointments_tile">
                                <?php echo number_format($defaulted_appointments); ?>
                            </h4>
                            <h6>No of  Defaulted Appointments</h6>
                        </div>


                    </section>
                </div>
                <div class="state col-lg-2 col-sm-2">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>
                        <a href="javascript:void()" class="ltfu_appointments_btn" id="ltfu_appointments_btn">
                            <div class="value">
                                <h4 class="old_appointments_tile">
                                    <?php echo number_format($LTFU_appointments); ?>
                                </h4>
                                <h6>No of LTFU  Appointments</h6>
                            </div>
                        </a>

                    </section>
                </div>

                <div class="state col-lg-2 col-sm-2">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>

                        <a href="<?php echo base_url(); ?>home/today_appointments" class="today_appointments_btn" id="today_appointments_btn">
                            <div class="value">
                                <h4 class="today_appointments_tile">
                                    <?php echo number_format($Today_appointments); ?>

                                </h4>
                                <h6>No of Appointments Today</h6>
                            </div> 
                        </a>

                    </section>
                </div>





            </div>



        </div>






    </div>


    <div class="panel panel-primary">




        <div class="panel-body"> 

            <!--DYNAMIC CHART STARTS HERE-->


            <div class="all_appointment_explained_div col-md-12" id="all_appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-4">
                        <div id="appointment_month_chart" class="appointment_month_chart" ></div>
                        <div class="appointment_month_report_div" id="appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="appointment_gender_chart" class="appointment_gender_chart"></div>
                        <div class="appointment_gender_report_div" id="appointment_gender_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="appointment_marital_chart" class="appointment_marital_chart" ></div>
                        <div class="appointment_marital_report_div" id="appointment_marital_report_div"></div>

                    </div>

                </div>






            </div>

            <div class="current_appointment_explained_div col-md-12" id="current_appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-4">
                        <div id="current_appointment_month_chart" class="current_appointment_month_chart" ></div>
                        <div class="current_appointment_month_report_div" id="current_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="current_appointment_gender_chart" class="current_appointment_gender_chart"></div>
                        <div class="current_appointment_gender_report_div" id="current_appointment_gender_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="current_appointment_marital_chart" class="appointment_marital_chart" ></div>
                        <div class="appointment_marital_report_div" id="appointment_marital_report_div"></div>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div id="current_appointment_grouping_chart" class="current_appointment_grouping_chart" ></div>
                        <div class="current_appointment_month_report_div" id="current_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="current_appointment_condition_chart" class="current_appointment_condition_chart"></div>
                        <div class="current_appointment_gender_report_div" id="current_appointment_gender_report_div"></div>

                    </div>


                </div>






            </div>

            <div class="missed_appointment_explained_div col-md-12" id="missed_appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-4">
                        <div id="missed_appointment_month_chart" class="missed_appointment_month_chart" ></div>
                        <div class="missed_appointment_month_report_div" id="missed_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="missed_appointment_gender_chart" class="missed_appointment_gender_chart"></div>
                        <div class="missed_appointment_gender_report_div" id="missed_appointment_gender_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="missed_appointment_marital_chart" class="missed_appointment_marital_chart" ></div>
                        <div class="missed_appointment_marital_report_div" id="missed_appointment_marital_report_div"></div>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div id="missed_appointment_grouping_chart" class="missed_appointment_grouping_chart" ></div>
                        <div class="missed_appointment_month_report_div" id="missed_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="missed_appointment_condition_chart" class="missed_appointment_condition_chart"></div>
                        <div class="missed_appointment_gender_report_div" id="missed_appointment_gender_report_div"></div>

                    </div>


                </div>





            </div>

            <div class="defaulted_appointment_explained_div col-md-12" id="defaulted_appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-4">
                        <div id="defaulted_appointment_month_chart" class="defaulted_appointment_month_chart" ></div>
                        <div class="defaulted_appointment_month_report_div" id="defaulted_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="defaulted_appointment_gender_chart" class="defaulted_appointment_gender_chart"></div>
                        <div class="defaulted_appointment_gender_report_div" id="defaulted_appointment_gender_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="defaulted_appointment_marital_chart" class="defaulted_appointment_marital_chart" ></div>
                        <div class="defaulted_appointment_marital_report_div" id="defaulted_appointment_marital_report_div"></div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div id="defaulted_appointment_grouping_chart" class="defaulted_appointment_grouping_chart" ></div>
                        <div class="defaulted_appointment_month_report_div" id="defaulted_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="defaulted_appointment_condition_chart" class="defaulted_appointment_condition_chart"></div>
                        <div class="defaulted_appointment_gender_report_div" id="defaulted_appointment_gender_report_div"></div>

                    </div>


                </div>







            </div>

            <div class="ltfu_appointment_explained_div col-md-12" id="ltfu_appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-4">
                        <div id="ltfu_appointment_month_chart" class="ltfu_appointment_month_chart" ></div>
                        <div class="ltfu_appointment_month_report_div" id="ltfu_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="ltfu_appointment_gender_chart" class="ltfu_appointment_gender_chart"></div>
                        <div class="ltfu_appointment_gender_report_div" id="ltfu_appointment_gender_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="ltfu_appointment_marital_chart" class="ltfu_appointment_marital_chart" ></div>
                        <div class="ltfu_appointment_marital_report_div" id="ltfu_appointment_marital_report_div"></div>

                    </div>

                </div>


                <div class="row">
                    <div class="col-md-4">
                        <div id="ltfu_appointment_grouping_chart" class="ltfu_appointment_grouping_chart" ></div>
                        <div class="ltfu_appointment_month_report_div" id="ltfu_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="ltfu_appointment_condition_chart" class="ltfu_appointment_condition_chart"></div>
                        <div class="ltfu_appointment_gender_report_div" id="ltfu_appointment_gender_report_div"></div>

                    </div>


                </div>








            </div>

            <div class="today_appointment_explained_div col-md-12" id="today_appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-4">
                        <div id="today_appointment_month_chart" class="today_appointment_month_chart" ></div>
                        <div class="today_appointment_month_report_div" id="today_appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="today_appointment_gender_chart" class="today_appointment_gender_chart"></div>
                        <div class="today_appointment_gender_report_div" id="today_appointment_gender_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="today_appointment_marital_chart" class="today_appointment_marital_chart" ></div>
                        <div class="today_appointment_marital_report_div" id="today_appointment_marital_report_div"></div>

                    </div>

                </div>








            </div>

            <div class="all_appointment_explained_div col-md-12" id="all_appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-4">
                        <div id="appointment_month_chart" class="appointment_month_chart" ></div>
                        <div class="appointment_month_report_div" id="appointment_month_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="appointment_gender_chart" class="appointment_gender_chart"></div>
                        <div class="appointment_gender_report_div" id="appointment_gender_report_div"></div>

                    </div>
                    <div class="col-md-4">
                        <div id="appointment_marital_chart" class="appointment_marital_chart" ></div>
                        <div class="appointment_marital_report_div" id="appointment_marital_report_div"></div>

                    </div>

                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div id="appointment_grouping_chart" class="appointment_grouping_chart"></div>
                        <div class="appointment_grouping_report_div" id="appointment_grouping_report_div"></div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="appointment_condition_chart" class="appointment_condition_chart" ></div>
                        <div class="appointment_condition_report_div" id="appointment_condition_report_div"></div>

                    </div>
                    <div class="col-md-6">

                        <p id="appointment_entry_chart" class="appointment_entry_chart" ></p>
                        <div class="appointment_entry_report_div" id="appointment_entry_report_div"></div>
                    </div>
                </div>







            </div>






        </div>




    </div>





    <?php
    $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    );
    ?>

    <input type="hidden" class="tokenizer" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />



    <!--DYNAMIC CHART ENDS HERE-->
</div>





</div>        

</div>

</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/b-print-1.2.4/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.2.0/r-2.1.1/rg-1.0.0/rr-1.2.0/sc-1.4.2/se-1.2.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/b-print-1.2.4/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.2.0/r-2.1.1/rg-1.0.0/rr-1.2.0/sc-1.4.2/se-1.2.0/datatables.min.js"></script>




<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/alasql/0.4.0/alasql.min.js"></script>
<script type="text/javascript">




    $(document).ready(function () {



        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
            }
            return val;
        }



        $(".current_appointments_btn").click(function () {
            $(".all_appointment_explained_div").hide();
            $(".current_appointment_explained_div").show();
            $(".missed_appointment_explained_div").hide();
            $(".defaulted_appointment_explained_div").hide();
            $(".ltfu_appointment_explained_div").hide();
            $(".today_appointment_explained_div").hide();
        });
        $(".all_appointments_btn").click(function () {
            $(".all_appointment_explained_div").show();
            $(".current_appointment_explained_div").hide();
            $(".missed_appointment_explained_div").hide();
            $(".defaulted_appointment_explained_div").hide();
            $(".ltfu_appointment_explained_div").hide();
            $(".today_appointment_explained_div").hide();
        });
        $(".missed_appointments_btn").click(function () {
            $(".all_appointment_explained_div").hide();
            $(".current_appointment_explained_div").hide();
            $(".missed_appointment_explained_div").show();
            $(".defaulted_appointment_explained_div").hide();
            $(".ltfu_appointment_explained_div").hide();
            $(".today_appointment_explained_div").hide();
        });
        $(".defaulted_appointments_btn").click(function () {
            $(".all_appointment_explained_div").hide();
            $(".current_appointment_explained_div").hide();
            $(".missed_appointment_explained_div").hide();
            $(".defaulted_appointment_explained_div").show();
            $(".ltfu_appointment_explained_div").hide();
            $(".today_appointment_explained_div").hide();
        });
        $(".ltfu_appointments_btn").click(function () {

            $(".all_appointment_explained_div").hide();
            $(".current_appointment_explained_div").hide();
            $(".missed_appointment_explained_div").hide();
            $(".defaulted_appointment_explained_div").hide();
            $(".ltfu_appointment_explained_div").show();
            $(".today_appointment_explained_div").hide();
        });
        $(".today_appointments_btn").click(function () {

            $(".all_appointment_explained_div").hide();
            $(".current_appointment_explained_div").hide();
            $(".missed_appointment_explained_div").hide();
            $(".defaulted_appointment_explained_div").hide();
            $(".ltfu_appointment_explained_div").hide();
            $(".today_appointment_explained_div").show();
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
                selected_date_to = "To : " + $(".date_to").val();
            }


            if (county != "") {
                selected_county = "For " + $(".filter_county option:selected").text() + " County ";

            }
            if (sub_county != "") {
                selected_sub_county = ", " + $(".filter_sub_county option:selected").text() + "Sub County ";

            }
            if (facility != "") {
                selected_facility = "," + $(".filter_facility option:selected").text() + " ";

            }

            var description_one = "" + selected_county + " " + selected_sub_county + "  " + selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            var tokenizer = $(".tokenizer").val();
            draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two, tokenizer);


            county_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            sub_county_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            client_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            consent_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            partners_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            facilities_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            active_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            old_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            today_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            current_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            missed_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            messages_tile(county, sub_county, facility, date_from, date_to, tokenizer);

        });
        var county = $(".filter_county").val();
        var sub_county = $(".filter_sub_county").val();
        var facility = $(".filter_facility").val();
        var date_from = $(".date_from").val();
        var date_to = $(".date_to").val();
        var tokenizer = $(".tokenizer").val();
        var description_one = "";
        var description_two = "";

        draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two, tokenizer);
        function draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two, tokenizer) {

            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }


            //ALL APPOINTMENTS REPORTS STARTS HERE ..

            var processed_json = new Array();


            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>home/get_appointment_month/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    for (i = 0; i < data.length; i++) {
                        processed_json.push([data[i].name, parseInt(data[i].value)]);
                    }


                    // draw chart
                    Highcharts.chart('appointment_month_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Appointment Distribution by Month</br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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



                    create_gender_report(data);



                }, error: function (errorThrown) {

                }
            });



            var gender_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_appointment_gender/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        gender_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('appointment_gender_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Appointment Distribution by Gender </br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: gender_json
                            }]
                    });



                    //create_client_language_report(data);

                }, error: function (errorThrown) {

                }
            });






            var marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_appointment_marital/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        marital_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('appointment_marital_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Appointment Distribution by Marital Status ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: marital_json
                            }]
                    });


                    //create_client_marital_report(data);
                }, error: function (errorThrown) {

                }
            });






            var grouping_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_appointment_grouping/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        grouping_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('appointment_grouping_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Appointment Distribution by Grouping ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: grouping_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });



            var condition_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_appointment_condition/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        condition_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('appointment_condition_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Appointment Distribution by Condition ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: condition_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });




            //ALL APPONTMENTS REPORTS ENDS HERE

            //CURRENT APPOINTMENTS REPORTS STARTS HERE ..

            var processed_json = new Array();


            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>home/get_current_appointment_month/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    for (i = 0; i < data.length; i++) {
                        processed_json.push([data[i].name, parseInt(data[i].value)]);
                    }


                    // draw chart
                    Highcharts.chart('current_appointment_month_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Current Appointment Distribution by Month</br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: processed_json
                            }]
                    });



                    create_gender_report(data);



                }, error: function (errorThrown) {

                }
            });



            var gender_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_current_appointment_gender/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        gender_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('current_appointment_gender_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Current Appointment Distribution by Gender </br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: gender_json
                            }]
                    });



                    //create_client_language_report(data);

                }, error: function (errorThrown) {

                }
            });






            var marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_current_appointment_marital/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        marital_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('current_appointment_marital_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Current Appointment Distribution by Marital Status ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: marital_json
                            }]
                    });


                    //create_client_marital_report(data);
                }, error: function (errorThrown) {

                }
            });






            var grouping_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_current_appointment_grouping/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        grouping_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('current_appointment_grouping_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Current Appointment Distribution by Grouping ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: grouping_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });



            var condition_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_current_appointment_condition/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        condition_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('current_appointment_condition_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Current Appointment Distribution by Condition ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: condition_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });




            //CURRENT APPONTMENTS REPORTS ENDS HERE

            //MISSED APPOINTMENTS REPORTS STARTS HERE ..

            var processed_json = new Array();


            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>home/get_missed_appointment_month/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    for (i = 0; i < data.length; i++) {
                        processed_json.push([data[i].name, parseInt(data[i].value)]);
                    }


                    // draw chart
                    Highcharts.chart('missed_appointment_month_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Missed Appointment Distribution by Month</br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: processed_json
                            }]
                    });



                    create_gender_report(data);



                }, error: function (errorThrown) {

                }
            });



            var gender_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_missed_appointment_gender/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        gender_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('missed_appointment_gender_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Missed Appointment Distribution by Gender </br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: gender_json
                            }]
                    });



                    //create_client_language_report(data);

                }, error: function (errorThrown) {

                }
            });






            var marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_missed_appointment_marital/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        marital_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('missed_appointment_marital_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Missed Appointment Distribution by Marital Status ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: marital_json
                            }]
                    });


                    //create_client_marital_report(data);
                }, error: function (errorThrown) {

                }
            });






            var grouping_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_missed_appointment_grouping/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        grouping_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('missed_appointment_grouping_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Missed Appointment Distribution by Grouping ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: grouping_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });



            var condition_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Home/get_missed_appointment_condition/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        condition_json.push([data[i].name, parseInt(data[i].value)]);
                    }

                    // draw chart
                    Highcharts.chart('missed_appointment_condition_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Missed Appointment Distribution by Condition ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                name: 'Appointments',
                                colorByPoint: true,
                                data: condition_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });




            //MISSED APPONTMENTS REPORTS ENDS HERE




































































        }




        function appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".appointments_tile").empty();
            $(".appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/appointment_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".appointments_tile").empty();
                    $(".appointments_tile").append(response);
                }, error: function (data) {

                }
            })
        }
        function active_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".active_appointments_tile").empty();
            $(".active_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/active_appointment_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".active_appointments_tile").empty();
                    $(".active_appointments_tile").append(response);
                }, error: function (data) {

                }
            })
        }
        function old_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".old_appointments_tile").empty();
            $(".old_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/old_appointment_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".old_appointments_tile").empty();
                    $(".old_appointments_tile").append(response);
                }, error: function (data) {

                }
            })
        }

        function today_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".today_appointments_tile").empty();
            $(".today_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_today_appointments_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".today_appointments_tile").empty();
                    $(".today_appointments_tile").append(response);
                }, error: function (data) {

                }
            })
        }
        function current_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".current_appointments_tile").empty();
            $(".current_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_current_appointments_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".current_appointments_tile").empty();
                    $(".current_appointments_tile").append(response);
                }, error: function (data) {

                }
            })
        }
        function missed_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".missed_appointments_tile").empty();
            $(".missed_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_missed_appointments_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".missed_appointments_tile").empty();
                    $(".missed_appointments_tile").append(response);
                }, error: function (data) {

                }
            })
        }



        function messages_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".messages_tile").empty();
            $(".messages_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_messages_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".messages_tile").empty();
                    $(".messages_tile").append(response);
                }, error: function (data) {

                }
            })
        }



        function county_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".county_tile").empty();
            $(".county_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/county_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".county_tile").empty();
                    $(".county_tile").append(response);
                }, error: function (data) {

                }
            })
        }



        function sub_county_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".sub_county_tile").empty();
            $(".sub_county_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/sub_county_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".sub_county_tile").empty();
                    $(".sub_county_tile").append(response);
                }, error: function (data) {

                }
            })
        }



        function client_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".client_tile").empty();
            $(".client_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/client_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {

                    $(".client_tile").empty();
                    var response = commaSeparateNumber(response);
                    $(".client_tile").append(response);
                }, error: function (data) {

                }
            })
        }






        function consent_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".consent_tile").empty();
            $(".consent_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/consented_clients_json/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var consented_clients = response[0].consented_clients;
                    var response = commaSeparateNumber(consented_clients);
                    //var all_clients = response[0].all_clients;

                    // var response = ((consented_clients / all_clients) * 100).toFixed(1);
                    $(".consent_tile").empty();
                    $(".consent_tile").append(response);
                }, error: function (data) {

                }
            });
        }


        function  partners_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".partner_tile").empty();
            $(".partner_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/partner_info/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    $(".partner_tile").empty();
                    $(".partner_tile").append(response);
                }, error: function (data) {

                }
            })
        }

        function facilities_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".facilities_tile").empty();
            $(".facilities_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/facility_info/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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