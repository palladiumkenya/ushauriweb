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
                            <a href="javascript:void()" class="no_clients_btn" id="no_clients_btn">
                                <h1 class="client_tile">
                                    <?php
                                    echo number_format($client_info);
                                    ?>
                                </h1>
                                <p>No of Clients</p>   
                            </a>

                        </div>
                    </section>
                </div>   <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>
                        <div class="value">
                            <a href="javascript:void()" class="consented_client_btn" id="consented_client_btn">
                                <h1 class="consent_tile">  <?php
                                    foreach ($consented_clients as $value) {
                                        $clients = $value->all_clients;
                                        $consented_clients = $value->consented_clients;
                                        echo number_format($consented_clients);
                                    }
                                    ?></h1>

                                <p>No of Consented Clients </p>
                            </a>

                        </div>

                    </section>
                </div>







                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>

                        <a href="javascript:void()" class="appointments_btn" id="appointments_btn">
                            <div class="value">
                                <h1 class="appointments_tile">
                                    <?php echo number_format($appointments); ?>

                                </h1>
                                <p>Total No of  Appointments</p>
                            </div> 
                        </a>


                    </section>
                </div>



                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-building">

                            </i>
                        </div>

                        <div class="value">
                            <h1 class="active_appointments_tile">
                                <?php echo number_format($active_appointments); ?>
                            </h1>
                            <p>No of  Active Appointments</p>
                        </div>


                    </section>
                </div>
                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>
                        <div class="value">
                            <h1 class="old_appointments_tile">
                                <?php echo number_format($old_appointments); ?>
                            </h1>
                            <p>No of Old Appointments</p>
                        </div>
                    </section>
                </div>

                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>
                        
                        <a href="<?php echo base_url();?>home/today_appointments" class="" id="">
                            <div class="value">
                                <h1 class="today_appointments_tile">
                                    <?php echo number_format($today_appointments); ?>

                                </h1>
                                   <p>No of Appointments Today</p>
                            </div> 
                        </a>
                       
                    </section>
                </div>



                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-mail-forward">

                            </i>
                        </div>
                        <a href="javascript:void()" class="messages_btn" id="messages_btn">
                            <div class="value messages_tile_div">
                                <h1 class="messages_tile">
                                    <?php echo number_format($messages_sent); ?>

                                </h1>
                                <p>Messages Sent</p>
                            </div> 
                        </a>

                    </section>
                </div>





                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>
                        <div class="value">
                            <h1 class="appointments_kept"></h1>
                            <p></p>
                        </div>
                    </section>
                </div>



            </div>



        </div>



        <?php
        $access_level = $this->session->userdata('access_level');

        if ($access_level == "Facility") {
            ?>
            <?php
        } else {
            ?>


            <div class="row">
                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>

                        <a href="javascript:void()" class="county_btn" id="county_btn">
                            <div class="value">
                                <h1 class="county_tile">
                                    <?php echo ($counties); ?>

                                </h1>
                                <p>No of County/ies</p>
                            </div> 
                        </a>

                    </section>
                </div>   <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-user">

                            </i>
                        </div>  
                        <div class="value">
                            <h1 class="sub_county_tile">  <?php
                                echo $sub_counties;
                                ?> </h1>

                            <p>No of Sub County/ies </p>
                        </div>



                    </section>
                </div>



                <?php
                $access_level = $this->session->userdata('access_level');

                if ($access_level == "Partner") {
                    ?>
                    <?php
                } else {
                    ?>

                    <div class="state col-lg-3 col-sm-3">
                        <section class="panel">
                            <div class="symbol">
                                <i class="icon-building">

                                </i>
                            </div>
                            <div class="value">
                                <h1 class="partner_tile">
                                    <?php
                                    echo $partners;
                                    ?>
                                </h1>
                                <p>Partners</p>
                            </div>
                        </section>
                    </div>

                    <?php
                }
                ?>


                <div class="state col-lg-3 col-sm-3">
                    <section class="panel">
                        <div class="symbol">
                            <i class="icon-hospital">

                            </i>
                        </div>


                        <a href="javascript:void()" class="facility_btn" id="facility_btn">
                            <div class="value">
                                <h1 class="facilities_tile">
                                    <?php echo ($facilities); ?>

                                </h1>
                                <p>No of Facility/ies</p>
                            </div> 
                        </a>

                    </section>
                </div>



            </div>

            <?php
        }
        ?>


    </div>


    <div class="panel panel-primary">




        <div class="panel-body"> 

            <!--DYNAMIC CHART STARTS HERE-->


            <div class="client_explained_div col-md-12" id="client_explained_div" style="display: none;">
                <div class="row">

                    <div class="col-md-6">

                        <div id="gender_chart" class="gender_chart" ></div> 
                        <br>
                        <div class="gender_report_div" id="gender_report_div">

                        </div>

                    </div>
                    <div class="col-md-6">

                        <div id="language_chart" class="language_chart" ></div>
                        <br>
                        <div class="language_report_div" id="language_report_div">                 
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">


                        <p id="marital_piechart" class="marital_piechart" ></p>
                        <div class="marital_report_div" id="marital_report_div"></div>
                    </div>
                    <div class="col-md-6">

                        <p id="client_type_chart" class="client_type_chart" ></p>
                        <div class="type_report_div" id="type_report_div"></div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">


                        <p id="client_type_barchart" class="client_type_barchart" ></p>
                        <div class="client_category_report_div" id="client_category_report_div"></div>

                    </div>
                    <div class="col-md-6">
                        <p id="client_age_group_barchart" class="client_age_group_barchart" ></p>
                        <div class="client_age_group_report_div" id="client_age_group_report_div"></div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <p id="client_condition_chart" class="client_condition_chart" ></p>
                        <div class="client_condition_report_div" id="client_condition_report_div"></div>

                    </div>
                    <div class="col-md-6">
                        <p id="client_status_chart" class="client_status_chart" ></p>
                        <div class="client_status_report_div" id="client_status_report_div"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p id="client_reg_chart" class="client_reg_chart" ></p>
                        <div class="client_reg_report_div" id="client_reg_report_div"></div>
                    </div>

                    <div class="col-md-6">
                        <p id="cummulative_client_grouping" class="cummulative_client_grouping" ></p>

                    </div>

                </div>
            </div>

            <div class="consented_explained_div col-md-12" id="consented_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-6">


                        <div id="consented_client_chart" class="consented_client_chart" ></div>
                        <div class="client_consented_report_div" id="client_consented_report_div"></div>

                    </div>
                    <div class="col-md-6">
                        <div id="consented_client_json" class="consented_client_json"></div>
                        <div class="client_consent_report_div"></div>


                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p id="consent_gauge" class="consent_gauge"></p>
                    </div>
                    <div class="col-md-6">
                        <div id="consent_client_gender_chart" class="consent_client_gender_chart"></div>
                        <div class="client_consented_gender_report_div" id="client_consented_gender_report_div"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div id="consent_client_marital_chart" class="consent_client_marital_chart"></div>
                        <div class="client_consented_marital_report_div" id="client_consented_marital_report_div"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="consent_client_group_chart" class="consent_client_group_chart"></div>
                        <div class="client_consented_category_report_div" id="client_consented_category_report_div"></div>
                    </div>
                </div>



            </div>

            <div class="appointment_explained_div col-md-12" id="appointment_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-6">
                        <div id="appointment_status_chart" class="appointment_status_chart" ></div>
                        <div class="client_appointment_status_report_div" id="client_appointment_status_report_div"></div>

                    </div>
                    <div class="col-md-6">
                        <div id="appointment_distribution_by_booked_chart" class="appointment_distribution_by_booked_chart"></div>
                        <div class="client_booked_appointment_status_report_div" id="client_booked_appointment_status_report_div"></div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="appointment_distribution_by_notified_chart" class="appointment_distribution_by_notified_chart" ></div>
                        <div class="client_notified_appointment_status_report_div" id="client_notified_appointment_status_report_div"></div>

                    </div>
                    <div class="col-md-6">
                        <div id="appointment_distribution_by_missed_chart" class="appointment_distribution_by_missed_chart"></div>
                        <div class="client_missed_appointment_status_report_div" id="client_missed_appointment_status_report_div"></div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="appointment_distribution_by_defaulted_chart" class="appointment_distribution_by_defaulted_chart" ></div>
                        <div class="client_defaulted_appointment_status_report_div" id="client_defaulted_appointment_status_report_div"></div>

                    </div>
                    <div class="col-md-6">

                        <p id="client_appointment_chart" class="client_appointment_chart" ></p>
                        <div class="client_appointment_report_div" id="client_appointment_report_div"></div>
                    </div>
                </div>







            </div>

            <div class="messages_explained_div" id="messages_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-6">


                        <div id="message_distribution_pie_chart" class="message_distribution_pie_chart" ></div>
                        <br>
                        <div class="message_distribution_report_div" id="message_distribution_report_div">

                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="message_distribution_bar_chart" id="message_distribution_bar_chart">

                        </div>
                        <br>



                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">


                        <p id="message_distribution_status" class="message_distribution_status" ></p>

                    </div>

                </div>


            </div>


            <div class="percentage_counties_explained_div col-md-12" id="percentage_counties_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-6">


                        <p id="percentage_counties_gauge" class="percentage_counties_gauge" ></p>

                    </div>
                    <div class="col-md-6">
                        <div class="percentate_counties_table" id="percentage_counties_table">

                        </div>

                    </div>
                </div>







            </div>
            <div class="percentage_facilities_explained_div col-md-12" id="percentage_facilities_explained_div" style="display: none;" >


                <div class="row">
                    <div class="col-md-6">


                        <p id="percentage_facilities_gauge" class="percentage_facilities_gauge" ></p>

                    </div>
                    <div class="col-md-6">
                        <div class="percentate_facilities_table" id="percentate_facilities_table">

                        </div>

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


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/b-print-1.2.4/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.2.0/r-2.1.1/rg-1.0.0/rr-1.2.0/sc-1.4.2/se-1.2.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/b-print-1.2.4/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.2.0/r-2.1.1/rg-1.0.0/rr-1.2.0/sc-1.4.2/se-1.2.0/datatables.min.js"></script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.1.1/highcharts.js"></script>
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



        $(".county_btn").click(function () {
            $(".consented_explained_div").hide();
            $(".appointments_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").show();
            $(".messages_explained_div").hide();
        });
        $(".facility_btn").click(function () {
            $(".consented_explained_div").hide();
            $(".appointments_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").show();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
        });
        $(".consented_client_btn").click(function () {
            $(".consented_explained_div").show();
            $(".appointments_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
        });
        $(".no_clients_btn").click(function () {
            $(".consented_explained_div").hide();
            $(".appointments_explained_div").hide();
            $(".client_explained_div").show();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
        });
        $(".appointments_btn").click(function () {

            $(".consented_explained_div").hide();
            $(".appointment_explained_div").show();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
        });
        $(".messages_btn").click(function () {

            $(".consented_explained_div").hide();
            $(".appointment_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").show();
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
            var processed_json = new Array();

            $(".gender_report_div").empty();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_gender_reports/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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



            var language_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_language/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                                data: language_json
                            }]
                    });



                    create_client_language_report(data);

                }, error: function (errorThrown) {

                }
            });






            var marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_marital_reports/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Distribution by Marital Status ' + final_description + ' '
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
                                data: marital_json
                            }]
                    });


                    create_client_marital_report(data);
                }, error: function (errorThrown) {

                }
            });






            var client_type_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_type/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Distribution by Type ' + final_description + ' '
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
                                data: client_type_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });



            var client_type_json2 = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_category/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Distribution by Category ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                    create_client_category_report(data);
                }, error: function (errorThrown) {

                }});


            var client_age_group_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_age_group/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Distribution by Age Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                    create_client_age_group_report(data);
                }, error: function (errorThrown) {

                }
            });





            var client_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_status/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Distribution by Status ' + final_description + ' '
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
                                data: client_status_json
                            }]
                    });
                    create_client_status_report(data);

                }, error: function (errorThrown) {

                }
            });






            var client_reg_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_registration/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_reg_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_reg_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Registration Point ' + final_description + ' '
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
                                data: client_reg_json
                            }]
                    });
                    create_client_reg_report(data);

                }, error: function (errorThrown) {

                }
            });

            var client_appointment_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_appointment/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_appointment_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_appointment_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Appointment Distribution by Entry Point ' + final_description + ' '
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
                                data: client_appointment_json
                            }]
                    });
                    create_client_appointment_report(data);

                }, error: function (errorThrown) {

                }
            });


            var consented_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_consented_clients/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Consented for SMS Alerts ' + final_description + ' '
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
                                data: consented_json
                            }]
                    });
                    create_client_consented_report(data);
                }, error: function (errorThrown) {

                }
            });









            var consented_column_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_consented_clients/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Consented for SMS Alerts ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
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
                            text: 'Client Distribution by Condition ' + final_description + ' '
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
                                data: condition_json
                            }]
                    });

                    create_client_condition_report(data);

                }, error: function (errorThrown) {

                }
            });














            var appointment_status_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_status_distribution/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_status_json.push([data[i].app_status, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_status_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Appointment Status ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                data: appointment_status_json
                            }]
                    });
                    create_client_appointment_status_report(data);
                }, error: function (errorThrown) {

                }});








            var appointment_booked_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_booked/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_booked_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_booked_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Booked Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                data: appointment_booked_status_json
                            }]
                    });

                    create_client_booked_appointment_status_report(data);

                }, error: function (errorThrown) {

                }});

            var appointment_notified_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_notified/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_notified_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_notified_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Notified  Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                data: appointment_notified_status_json
                            }]
                    });

                    create_client_notified_appointment_status_report(data);

                }, error: function (errorThrown) {

                }});



            var appointment_missed_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_missed/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_missed_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_missed_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Missed Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                data: appointment_missed_status_json
                            }]
                    });

                    create_client_missed_appointment_status_report(data);

                }, error: function (errorThrown) {

                }});


            var appointment_defaulted_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_defaulted/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_defaulted_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_defaulted_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Defaulted Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                data: appointment_defaulted_status_json
                            }]
                    });
                    create_client_defaulted_appointment_status_report(data);


                }, error: function (errorThrown) {

                }});



//        








            var messages_queue_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_messages_queued_dist/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        messages_queue_json.push([data[i].message_type, parseInt(data[i].no_messages)]);
                    }

                    // draw chart
                    Highcharts.chart('message_distribution_pie_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Messages queued distribution </br> ' + final_description + ' (Pie Chart) '
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
                                name: 'Messages',
                                colorByPoint: true,
                                data: messages_queue_json
                            }]
                    });



                    create_message_distribution_report(data);

                }, error: function (errorThrown) {

                }
            });

            var messages_queue_clmn_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_messages_queued_dist/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        messages_queue_clmn_json.push([data[i].message_type, parseInt(data[i].no_messages)]);
                    }

                    // draw chart

                    Highcharts.chart('message_distribution_bar_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Messages queued distribution ' + final_description + ' (Bar Chart) '
                        },
                        subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
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
                                text: "No of Messages",
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
                                name: 'Messages',
                                data: messages_queue_clmn_json
                            }]
                    });

                }, error: function (errorThrown) {

                }
            });



























            var consent_json = new Array();

            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/consented_clients_json/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var consented_clients = response[0].consented_clients;
                    var all_clients = response[0].all_clients;

                    var consent_val = ((consented_clients / all_clients) * 100).toFixed(1);
                    // Populate series
                    for (i = 0; i < response.length; i++) {
                        consent_json.push([parseInt(consent_val)]);
                    }






                    Highcharts.chart('consent_gauge', {
                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },
                        title: {
                            text: 'Percentage of Clients consented ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
                        },
                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: [{
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                        stops: [
                                            [0, '#FFF'],
                                            [1, '#333']
                                        ]
                                    },
                                    borderWidth: 0,
                                    outerRadius: '109%'
                                }, {
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                        stops: [
                                            [0, '#333'],
                                            [1, '#FFF']
                                        ]
                                    },
                                    borderWidth: 1,
                                    outerRadius: '107%'
                                }, {
                                    // default background
                                }, {
                                    backgroundColor: '#DDD',
                                    borderWidth: 0,
                                    outerRadius: '105%',
                                    innerRadius: '103%'
                                }]
                        },
                        // the value axis
                        yAxis: {
                            min: 0,
                            max: 100,
                            minorTickInterval: 'auto',
                            minorTickWidth: 1,
                            minorTickLength: 10,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',
                            tickPixelInterval: 30,
                            tickWidth: 2,
                            tickPosition: 'inside',
                            tickLength: 10,
                            tickColor: '#666',
                            labels: {
                                step: 2,
                                rotation: 'auto'
                            },
                            title: {
                                text: 'Consented'
                            },
                            plotBands: [{
                                    from: 80,
                                    to: 100,
                                    color: '#55BF3B' // green
                                }, {
                                    from: 40,
                                    to: 80,
                                    color: '#DDDF0D' // yellow
                                }, {
                                    from: 0,
                                    to: 40,
                                    color: '#DF5353' // red
                                }]
                        },
                        series: [{
                                name: 'Consented ',
                                data: consent_json,
                                tooltip: {
                                    valueSuffix: '%'
                                }, credits: {
                                    enabled: false
                                }
                            }]

                    })






                }
            });

            var target_counties_json = new Array();

            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/percentage_counties/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var actual_counties = response[0].actual_counties;
                    var target_counties = response[0].target_counties;

                    var percentage_counties = ((actual_counties / target_counties) * 100).toFixed(1);
                    // Populate series
                    for (i = 0; i < response.length; i++) {
                        target_counties_json.push([parseInt(percentage_counties)]);
                    }






                    Highcharts.chart('percentage_counties_gauge', {
                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },
                        title: {
                            text: 'Percentage of Counties covered ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
                        },
                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: [{
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                        stops: [
                                            [0, '#FFF'],
                                            [1, '#333']
                                        ]
                                    },
                                    borderWidth: 0,
                                    outerRadius: '109%'
                                }, {
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                        stops: [
                                            [0, '#333'],
                                            [1, '#FFF']
                                        ]
                                    },
                                    borderWidth: 1,
                                    outerRadius: '107%'
                                }, {
                                    // default background
                                }, {
                                    backgroundColor: '#DDD',
                                    borderWidth: 0,
                                    outerRadius: '105%',
                                    innerRadius: '103%'
                                }]
                        },
                        // the value axis
                        yAxis: {
                            min: 0,
                            max: 100,
                            minorTickInterval: 'auto',
                            minorTickWidth: 1,
                            minorTickLength: 10,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',
                            tickPixelInterval: 30,
                            tickWidth: 2,
                            tickPosition: 'inside',
                            tickLength: 10,
                            tickColor: '#666',
                            labels: {
                                step: 2,
                                rotation: 'auto'
                            },
                            title: {
                                text: 'Consented'
                            },
                            plotBands: [{
                                    from: 80,
                                    to: 100,
                                    color: '#55BF3B' // green
                                }, {
                                    from: 40,
                                    to: 80,
                                    color: '#DDDF0D' // yellow
                                }, {
                                    from: 0,
                                    to: 40,
                                    color: '#DF5353' // red
                                }]
                        },
                        series: [{
                                name: 'Target counties ',
                                data: target_counties_json,
                                tooltip: {
                                    valueSuffix: ' %'
                                }, credits: {
                                    enabled: false
                                }
                            }]

                    })






                }
            });

            var target_facilities_json = new Array();

            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/percentage_facilities/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var actual_facilities = response[0].actual_facilities;
                    var target_facilities = response[0].target_facilities;

                    var percentage_facilities = ((actual_facilities / target_facilities) * 100).toFixed(1);
                    // Populate series
                    for (i = 0; i < response.length; i++) {
                        target_facilities_json.push([parseInt(percentage_facilities)]);
                    }






                    Highcharts.chart('percentage_facilities_gauge', {
                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },
                        title: {
                            text: 'Percentage of Facilities covered ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="https://hivsms.nascop.org">Ushauri</a>'
                        },
                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: [{
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                        stops: [
                                            [0, '#FFF'],
                                            [1, '#333']
                                        ]
                                    },
                                    borderWidth: 0,
                                    outerRadius: '109%'
                                }, {
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                        stops: [
                                            [0, '#333'],
                                            [1, '#FFF']
                                        ]
                                    },
                                    borderWidth: 1,
                                    outerRadius: '107%'
                                }, {
                                    // default background
                                }, {
                                    backgroundColor: '#DDD',
                                    borderWidth: 0,
                                    outerRadius: '105%',
                                    innerRadius: '103%'
                                }]
                        },
                        // the value axis
                        yAxis: {
                            min: 0,
                            max: 100,
                            minorTickInterval: 'auto',
                            minorTickWidth: 1,
                            minorTickLength: 10,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',
                            tickPixelInterval: 30,
                            tickWidth: 2,
                            tickPosition: 'inside',
                            tickLength: 10,
                            tickColor: '#666',
                            labels: {
                                step: 2,
                                rotation: 'auto'
                            },
                            title: {
                                text: 'Consented'
                            },
                            plotBands: [{
                                    from: 80,
                                    to: 100,
                                    color: '#55BF3B' // green
                                }, {
                                    from: 40,
                                    to: 80,
                                    color: '#DDDF0D' // yellow
                                }, {
                                    from: 0,
                                    to: 40,
                                    color: '#DF5353' // red
                                }]
                        },
                        series: [{
                                name: 'Target counties ',
                                data: target_facilities_json,
                                tooltip: {
                                    valueSuffix: '%'
                                }, credits: {
                                    enabled: false
                                }
                            }]

                    })






                }
            });












            var consented_gender_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/consented_clients_gender/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_gender_json.push([data[i].gender, parseInt(data[i].total_client)]);
                    }

                    // draw chart
                    Highcharts.chart('consent_client_gender_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Consented Client Distribution by Gender ' + final_description + ' '
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
                                data: consented_gender_json
                            }]
                    });
                    create_client_consented_gender_report(data);
                }, error: function (errorThrown) {

                }
            });

            var consented_marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/consented_clients_marital/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_marital_json.push([data[i].marital, parseInt(data[i].total_client)]);
                    }

                    // draw chart
                    Highcharts.chart('consent_client_marital_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Consented Client Distribution by Marital Status ' + final_description + ' '
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
                                data: consented_marital_json
                            }]
                    });
                    create_client_consented_marital_report(data);
                }, error: function (errorThrown) {

                }
            });
            var consented_group_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/consented_clients_groups/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_group_json.push([data[i].group_name, parseInt(data[i].total_client)]);
                    }

                    // draw chart
                    Highcharts.chart('consent_client_group_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Consented Client Distribution by Group ' + final_description + ' '
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
                                data: consented_group_json
                            }]
                    });
                    create_client_consented_category_report(data);

                }, error: function (errorThrown) {

                }
            });



































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