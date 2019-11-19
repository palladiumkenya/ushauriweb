<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->


<div class="row state-overview" >

    <div class="col-lg-12">








    </div>


    <div class="panel panel-primary">




        <div class="panel-body"> 

            <!--DYNAMIC CHART STARTS HERE-->
            <div class="col-xs-5">

                <input type="text" class="upn_search form-control" id="upn_search" name="upn_search" placeholder="Please Enter UPN No : "/>
                <button type="button" class="search_upn_btn btn btn-default" ><i class=" icon_search"></i>Search</button>

            </div>




            <div class="generated_profile_div" id="generated_profile_div" style="display: none;">

                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="well well-sm">
                                <div class="row">

                                    <div class="col-sm-12 col-md-6">
                                        <h4 class="clinic_number"></h4>

                                        <p>
                                        <h5 class="dob"></h5>

                                        <h5 class="marital"></h5>
                                        <h5 class="gender"></h5>

                                        <h5 class="group"></h5></p>
                                        <!-- Split button -->

                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <h4 class="client_name"> </h4>
                                        <p>
                                        <h5 class="language"></h5>

                                        <h5 class="phone_no"></h5>
                                        <h5 class="enrollment_date"></h5>
                                        <h5 class="art_date"></h5></p>
                                        <!-- Split button -->

                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-sm-12 col-md-6">

                                        <p>
                                        <h5 class="all_appointments"></h5>
                                        <h5 class="today_appointments"></h5>

                                        <h5 class="current_appointments"></h5>
                                        <h5 class="missed_appointments"></h5>

                                        <h5 class="defaulted_appointments"></h5></p>
                                        <h5 class="ltfu_appointments"></h5></p>
                                        <!-- Split button -->

                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <h4>Appointment Types : </h4>
                                        <p class="client_appointment_types">
                                        </p>
                                        <!-- Split button -->

                                    </div>
                                </div>
                            </div>
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



        $(".search_upn_btn").click(function () {

            $(".generated_profile_div").show();



            var upn = $(".upn_search").val();

            // Does some stuff and logs the event to the console


            $.ajax({
                url: "<?php echo base_url(); ?>home/get_client_profile/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".client_name").empty();
                    $(".client_status").empty();
                    $(".dob").empty();
                    $(".enrollment_date").empty();
                    $(".art_date").empty();
                    $(".group").empty();
                    $(".gender").empty();
                    $(".language").empty();
                    $(".marital").empty();
                    $(".phone_no").empty();
                    $(".status").empty();
                    $(".clinic_number").empty();
                    var isempty = jQuery.isEmptyObject(data);
                    if (isempty) {
                        swal("Sorry", "Clininc number : " + upn + " was not found in the  system  ", "info");
                    } else {


                        $.each(data, function (i, value) {
                            $(".client_name").append("Client Name : " + value.f_name + " " + value.m_name + " " + value.l_name);
                            $(".client_status").append("Client Status" + value.client_status);
                            $(".dob").append("Date of Birth " + value.dob);
                            $(".enrollment_date").append("Enrollment Date : " + value.enrollment_date);
                            $(".art_date").append("ART Start Date : " + value.art_date);
                            $(".group").append("Groupi : " + value.group_name);
                            $(".gender").append("Language  : " + value.gender_name);
                            $(".language").append("Language  : " + value.language_name);
                            $(".marital").append("Marital Status : " + value.marital);
                            $(".phone_no").append("Phone No : " + value.phone_no);
                            $(".status").append("Status : " + value.status);
                            $(".clinic_number").append("Clinic Number : " + value.clinic_number);
                        });


                    }



                }, error: function (jqXHR) {
                    swal("Error", "Clininc number already exists and is registered under : " + upn + " ", "warning");
                }
            });


            $.ajax({
                url: "<?php echo base_url(); ?>home/count_client_all_appointments/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".all_appointments").empty();
                    $.each(data, function (i, value) {
                        $(".all_appointments").append("No of  Appointments : " + value.num);
                    });

                }, error: function (error) {

                }
            });
            $.ajax({
                url: "<?php echo base_url(); ?>home/count_client_current_appointments/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".current_appointments").empty();
                    $.each(data, function (i, value) {
                        $(".current_appointments").append("No of Current Appointments : " + value.num);
                    });

                }, error: function (error) {

                }
            });
            $.ajax({
                url: "<?php echo base_url(); ?>home/count_client_missed_appointments/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".missed_appointments").empty();
                    $.each(data, function (i, value) {
                        $(".missed_appointments").append("No of Missed Appointments : " + value.num);
                    });

                }, error: function (error) {

                }
            });
            $.ajax({
                url: "<?php echo base_url(); ?>home/count_client_defaulted_appointments/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".defaulted_appointments").empty();
                    $.each(data, function (i, value) {
                        $(".defaulted_appointments").append("No of Defaulted Appointments : " + value.num);
                    });

                }, error: function (error) {

                }
            });
            $.ajax({
                url: "<?php echo base_url(); ?>home/count_client_LTFU_appointments/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".LTFU_appointments").empty();
                    $.each(data, function (i, value) {
                        $(".LTFU_appointments").append("No of LTFU Appointments : " + value.num);
                    });

                }, error: function (error) {

                }
            });
            $.ajax({
                url: "<?php echo base_url(); ?>home/count_client_Today_appointments/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".today_appointments").empty();
                    $.each(data, function (i, value) {
                        $(".today_appointments").append(value.num);
                    });

                }, error: function (error) {

                }
            });
            $.ajax({
                url: "<?php echo base_url(); ?>home/count_client_appointments_type/" + upn + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".client_appointment_types").empty();
                    $.each(data, function (i, value) {
                        $(".client_appointment_types").append("<li>Appointment Type : " + value.app_type + " => " + value.num + "</li>");
                    });

                }, error: function (error) {

                }
            });























        });






    });






</script>


<!--END MAIN WRAPPER -->