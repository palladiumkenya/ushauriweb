
<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Client Profile</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->

    <div class="Search_Modal" style="display: inline;">
        <!-- Button to Open the Modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> <i class="fa fa-search"></i>
            Search Client
        </button>
    </div>

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Search Client</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="search_field">
                        <input type="text" class="upn_search form-control" id="upn_search" name="upn_search" placeholder="Please Enter UPN No : "/>
                    </div>

                    <div class="loading_div" style="display: none;">
                        <span>Loading ....Please wait .....</span>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="search_upn_btn btn btn-default pull-left" ><i class=" fa fa-search"></i>Search</button>
                    <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-stop-circle-o"></i>Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- The Modal -->
    
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">

           
            <!-- Column -->
            <div class="col-lg-12">
                <div class="card">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs profile-tab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Profile</a> </li>
                        <li class="nav-item"> <a class="nav-link " data-toggle="tab" href="#appointments" role="tab">Appointments</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#outgoing" role="tab">Outgoing Messages</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#incoming" role="tab">Incoming Messages</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#wellness" role="tab">Wellness</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#outcomes" role="tab">Appointment Outcomes</a> </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active " id="profile" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                                        <br>
                                        <p class="text-muted client_name"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r "> <strong>Mobile</strong>
                                        <br>
                                        <p class="text-muted phone_no"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r"> <strong>Marital Status</strong>
                                        <br>
                                        <p class="text-muted marital"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6"> <strong>Language </strong>
                                        <br>
                                        <p class="text-muted language "></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r"> <strong>Client Condition</strong>
                                        <br>
                                        <p class="text-muted client_status"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r "> <strong>Date of Birth </strong>
                                        <br>
                                        <p class="text-muted dob"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r"> <strong>Enrollment Date </strong>
                                        <br>
                                        <p class="text-muted enrollment_date"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6"> <strong>ART Date  </strong>
                                        <br>
                                        <p class="text-muted art_date"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r "> <strong>Group  </strong>
                                        <br>
                                        <p class="text-muted group"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r"> <strong>Status </strong>
                                        <br>
                                        <p class="text-muted status"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6"> <strong>CCC  Number    </strong>
                                        <br>
                                        <p class="text-muted clinic_number"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6"> <strong>File  Number    </strong>
                                        <br>
                                        <p class="text-muted file_no"></p>
                                    </div>
                                      <div class="col-md-3 col-xs-6"> <strong>Gender      </strong>
                                        <br>
                                        <p class="text-muted gender_name"></p>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="tab-pane " id="appointments" role="tabpanel">
                            <div class="card-body" >

                                <div class="row">
                                    <div class="col-md-3 col-xs-6 b-r"> <strong>Total Appointments : </strong>
                                        <br>
                                        <p class="text-muted all_appointments"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r "> <strong>Future Appointments : </strong>
                                        <br>
                                        <p class="text-muted current_appointments"></p>
                                    </div>

                                    <div class="col-md-3 col-xs-6"> <strong>Kept Appointments :  </strong>
                                        <br>
                                        <p class="text-muted kept_appointments "></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6"> <strong>Missed Appointments :  </strong>
                                        <br>
                                        <p class="text-muted missed_appointments "></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r"> <strong>Defaulted Appointments : </strong>
                                        <br>
                                        <p class="text-muted defaulted_appointments"></p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r "> <strong>LTFU Appointments :  </strong>
                                        <br>
                                        <p class="text-muted LTFU_appointments"></p>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-6 b-r "> <strong>No of Appointments  by Type:  </strong>
                                        <ul class=" text-muted client_appointment_types"></ul>
                                    </div>
                                </div>




                            </div>
                        </div>
                        <!--second tab-->

                        <div class="tab-pane" id="outgoing" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="outgoing_messages_logs_div col-xs-6 col-md-6" id="outgoing_messages_logs_div" ></div>

                                </div>

                            </div>
                        </div>

                        <div class="tab-pane" id="incoming" role="tabpanel">
                            <div class="card-body">

                                <div class="row">
                                    <div class="incoming_messages_logs_div" id="incoming_messages_logs_div" ></div>

                                     </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="wellness" role="tabpanel">
                            <div class="card-body">

                                <div class="row">
                                    <div class="wellness_messages_logs_div" id="wellness_messages_logs_div" ></div>

                                </div>

                            </div>
                        </div>



                        <div class="tab-pane" id="outcomes" role="tabpanel">
                            <div class="card-body">

                                <div class="row">
                                    <div class="appointment_outcomes_log_div" id="appointment_outcomes_log_div" ></div>

                                </div>

                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>

        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 All rights reserved. Template designed by <a href="https://colorlib.com">Colorlib</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->



<?php
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
?>

<input type="hidden" class="tokenizer" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />








<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.2.1/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.css"/>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.2.1/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.js"></script>



<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script type="text/javascript">

var tb = jQuery.noConflict();
    $(document).ready(function(){
          $('#myModal').modal('show');
    });

    tb(document).ready(function () {


        

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, 'tb1' + ',' + 'tb2');
            }
            return val;
        }



        tb(".search_upn_btn").click(function () {
            tb(".loading_div").show();
            tb(".search_field").hide();
            tb(".generated_profile_div").show();
            var tokenizer = tb(".tokenizer").val();
            var upn = tb(".upn_search").val();
            // Does some stuff and logs the event to the console
            tb(".loader").show();

            tb.ajax({
                url: "<?php echo base_url(); ?>home/get_client_profile/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    console.log(data);
                    tb(".loading_div").hide();
                    tb(".search_field").show();
                    $('#myModal').modal('hide');
                    tb(".modal-backdrop").remove();
                    tb(".loader").hide();
                    tb(".client_name").empty();
                    tb(".client_status").empty();
                    tb(".dob").empty();
                    tb(".enrollment_date").empty();
                    tb(".art_date").empty();
                    tb(".group").empty();
                    tb(".gender").empty();
                    tb(".language").empty();
                    tb(".marital").empty();
                    tb(".phone_no").empty();
                    tb(".status").empty();
                    tb(".clinic_number").empty();
                    tb(".serial_number").empty();
                    tb(".gender_name").empty();
                    var isempty = jQuery.isEmptyObject(data);
                    if (isempty) {
                        swal("Sorry", "Clininc number : " + upn + " was not found in the  system  ", "info");
                    } else {


                        tb.each(data, function (i, value) {
                            tb(".client_name").append(" " + value.f_name + " " + value.m_name + " " + value.l_name);
                            tb(".client_status").append(" " + value.client_status);
                            tb(".dob").append(" " + value.dob);
                            tb(".enrollment_date").append("  " + value.enrollment_date);
                            tb(".art_date").append(" " + value.art_date);
                            tb(".group").append(" " + value.group_name);
                            tb(".gender_name").append(" " + value.gender_name);
                            tb(".language").append(" " + value.language_name);
                            tb(".marital").append(" " + value.marital);
                            tb(".phone_no").append(" " + value.phone_no);
                            tb(".status").append(" " + value.status);
                            tb(".clinic_number").append(" " + value.clinic_number);
                            tb(".file_no").append(" " + value.file_no);
                            var description = "  CCC No : " + value.clinic_number + "  Serial  No : " + value.file_no + "   Gender Name : " + value.gender_name + " Phone Number : " + value.phone_no;
                            tb(".client_description").append(description);


                            if (value.gender_name == "Male") {
                                tb(".male_profile").show();
                                tb(".female_profile").hide();
                            } else {
                                tb(".male_profile").hide();
                                tb(".female_profile").show();
                            }

                            var client_id = value.client_id;
                            tb(".outgoing_messages_logs_div").empty();
                            tb(".wellness_messages_logs_div").empty();
                            tb(".incoming_messages_logs_div").empty();
                            get_outgoing_msgs_logs(client_id);
                            get_incoming_msgs_logs(client_id);
                            get_wellness_logs(client_id);
                            get_client_outcomes(client_id);


                        });
                    }






                }, error: function (jqXHR) {
                    tb(".loading_div").hide();
                    tb(".search_field").show();
                    swal("Error", "Clininc number " + upn + " does not exist in the system :  ", "warning");
                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_all_appointments/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".all_appointments").empty();
                    tb.each(data, function (i, value) {
                        tb(".all_appointments").append(" " + value.num);
                    });
                }, error: function (error) {

                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_current_appointments/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".current_appointments").empty();
                    tb.each(data, function (i, value) {
                        tb(".current_appointments").append(" " + value.num);
                    });
                }, error: function (error) {

                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_kept_appointments/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".kept_appointments").empty();
                    tb.each(data, function (i, value) {
                        tb(".kept_appointments").append(" " + value.num);
                    });
                }, error: function (error) {

                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_missed_appointments/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".missed_appointments").empty();
                    tb.each(data, function (i, value) {
                        tb(".missed_appointments").append(" " + value.num);
                    });
                }, error: function (error) {

                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_defaulted_appointments/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".defaulted_appointments").empty();
                    tb.each(data, function (i, value) {
                        tb(".defaulted_appointments").append(" " + value.num);
                    });
                }, error: function (error) {

                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_LTFU_appointments/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".LTFU_appointments").empty();
                    tb.each(data, function (i, value) {
                        tb(".LTFU_appointments").append(" " + value.num);
                    });
                }, error: function (error) {

                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_Today_appointments/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".today_appointments").empty();
                    tb.each(data, function (i, value) {
                        tb(".today_appointments").append(value.num);
                    });
                }, error: function (error) {

                }
            });
            tb.ajax({
                url: "<?php echo base_url(); ?>home/count_client_appointments_type/",
                type: 'POST',
                dataType: 'JSON',
                data: {upn: upn, tokenizer: tokenizer},
                success: function (data) {
                    tb(".client_appointment_types").empty();
                    tb.each(data, function (i, value) {
                        tb(".client_appointment_types").append("<li>   " + value.app_type + "  :  " + value.num + "</li>");
                    });
                }, error: function (error) {

                }
            });


            function get_client_outcomes(client_id) {
                tb(".appointment_outcomes_log_div").empty();
                tb('.loader').show();
                var controller = "home";
                var get_function = "get_client_outcomes";
                var error_alert = "An Error Ocurred";
                tb.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + client_id,
                    dataType: "JSON",
                    success: function (response) {
                        tb('.loader').hide();
                        tb(".appointment_outcomes_log_div").empty();
                        var check_data = jQuery.isEmptyObject(response);
                        if (check_data === true) {
                            tb(".appointment_outcomes_log_div").hide();
                        } else {


                            var table = "<table class='display nowrap table table-hover table-striped table-bordered dataTable outcomes_msg_table  '>\n\
                <thead><tr><th>No . </th><th>Clinic No</th><th>File No</th><th>Appointment Date.</th><th>Appointment type </th><th> Tracer name </th><th> Outcome </th><th> Final outcome </th>\n\
                <th> Tracing Date </th> <th>Other outcome</th> <th>Status</th> <th>Days</th> <th>Created at</th> </tr></thead>\n\
                <tbody id='outcomes_results_tbody' class='outcomes_results_tbody'></tbody></table>";
                            tb(".appointment_outcomes_log_div").append(table);
                            var no = 1;
                            tb.each(response, function (i, outcome_values) {

                                var clinic_no = outcome_values.clinic_number;
                                var client_name = outcome_values.client_name;
                                var file_no = outcome_values.file_no;
                                var appntmnt_date = outcome_values.appntmnt_date;
                                var appointment_type = outcome_values.appointment_type;
                                var tracer_name = outcome_values.tracer_name;
                                var outcome = outcome_values.outcome;
                                var final_outcome = outcome_values.final_outcome;
                                var tracing_date = outcome_values.tracing_date;
                                var other_final_outcome = outcome_values.other_outcome;
                                var created_at = outcome_values.created_at;
                                var days_defaulted = outcome_values.days_defaulted;
                                var app_status = outcome_values.app_status;

                                console.log("Our response " + clinic_no);


                                var tbody = "<tr>\n\
                                <td>" + no + "</td>\n\
                                <td>" + clinic_no + "</td>\n\
                                <td>" + file_no + "</td>\n\
                                <td>" + appntmnt_date + "</td>\n\
                                <td>" + appointment_type + "</td>\n\
                                <td>" + tracer_name + "</td>\n\
                                <td>" + outcome + "</td>\n\
                                <td>" + final_outcome + "</td>\n\
                                <td>" + tracing_date + "</td>\n\
                                <td>" + other_final_outcome + "</td>\n\
                                <td>" + app_status + "</td>\n\
                                <td>" + days_defaulted + "</td>\n\
                                <td>" + created_at + "</td>\n\
                                </tr>";
                                tb(".outcomes_results_tbody").append(tbody);
                                no++;
                            });







                            var report_name = "Appointment outcomes for Client ";

                            tb('.outcomes_msg_table').DataTable({
                                dom: 'Bfrtip',
                                responsive: true,
                                "aLengthMenu": [[5, 10, 25, 50, -1], [10, 25, 50, "All"]],
                                buttons: [
                                    tb.extend(true, {}, {
                                        extend: 'copyHtml5',
                                        title: report_name
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'excelHtml5',
                                        title: report_name
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'pdfHtml5',
                                        title: report_name
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'csvHtml5',
                                        title: report_name
                                    })
                                ]
                            });


                            tb(".appointment_outcomes_log_div").show();






                        }


                    }, error: function (data) {
                        sweetAlert("Oops...", "" + error_alert + "", "error");

                    }

                });



            }



            function get_outgoing_msgs_logs(client_id) {
                tb(".outgoing_messages_logs_div").empty();
                tb('.loader').show();
                var controller = "home";
                var get_function = "get_message_logs";
                var error_alert = "An Error Ocurred";
                tb.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + client_id,
                    dataType: "JSON",
                    success: function (response) {
                        tb('.loader').hide();
                        tb(".outgoing_messages_logs_div").empty();
                        var check_data = jQuery.isEmptyObject(response);
                        if (check_data === true) {
                            tb(".outgoing_messages_logs_div").hide();
                        } else {


                            var table = "<table class='display nowrap table table-hover table-striped table-bordered dataTable outgoing_msg_table  '><thead><tr><th>No . </th><th>Clinic No</th><th>Phone No</th><th>Message Type.</th><th>Sent On </th><th> Message </th></tr></thead>\n\
                <tbody id='msg_results_tbody' class='msg_results_tbody'></tbody></table>";
                            tb(".outgoing_messages_logs_div").append(table);
                            var no = 1;
                            tb.each(response, function (i, value) {

                                var clinic_no = value.clinic_number;
                                var client_name = value.f_name + " " + value.m_name + " " + value.l_name;
                                var phone_no = value.phone_no;
                                var sent_on = value.sent_on;
                                var status = value.status;
                                var enrollment = value.enrollment_date;
                                var time_stamp = value.time_stamp;
                                var msg = value.msg;
                                var msg_type = value.msg_type;




                                var tbody = "<tr><td>" + no + "</td><td>" + clinic_no + "</td><td>" + phone_no + "</td><td>" + msg_type + "</td><td>" + sent_on + "</td><td>" + msg + "</td></tr>";
                                tb(".msg_results_tbody").append(tbody);
                                no++;
                            });







                            var report_name = "Outgoing msgs for Client ";

                            tb('.outgoing_msg_table').DataTable({
                                dom: 'Bfrtip',
                                responsive: true,
                                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                                buttons: [
                                    tb.extend(true, {}, {
                                        extend: 'copyHtml5',
                                        title: report_name
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'excelHtml5',
                                        title: report_name
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'pdfHtml5',
                                        title: report_name
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'csvHtml5',
                                        title: report_name
                                    })
                                ]
                            });


                            tb(".outgoing_messages_logs_div").show();






                        }


                    }, error: function (data) {
                        sweetAlert("Oops...", "" + error_alert + "", "error");

                    }

                });



            }
            function get_wellness_logs(client_id) {
                tb(".wellness_messages_logs_div").empty();
                tb('.loader').show();
                var controller = "home";
                var get_function = "get_wellness_logs";
                var error_alert = "An Error Ocurred";
                tb.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + client_id,
                    dataType: "JSON",
                    success: function (response) {
                        tb('.loader').hide();
                        tb(".wellness_messages_logs_div").empty();


                        var check_data = jQuery.isEmptyObject(response);
                        if (check_data === true) {
                            tb(".wellness_messages_logs_div").hide();
                        } else {
                            var table = "<table class='table table-bordered table-hover table-condensed table-stripped wellness_msg_table '><thead><tr><th>No . </th><th>Clinic No</th><th>Client Name.</th><th>Phone No</th><th>Message Type.</th><th>Status </th><th>Sent On </th><th>App Message .</th></tr></thead>\n\
                <tbody id='wellness_results_tbody' class='wellness_results_tbody'></tbody></table>";
                            tb(".wellness_messages_logs_div").append(table);
                            var no = 1;
                            tb.each(response, function (i, value) {

                                var clinic_no = value.clinic_number;
                                var client_name = value.client_name;
                                var phone_no = value.source;
                                var sent_on = value.sent_on;
                                var status = value.status;
                                var destination = value.destination;
                                var response_type = value.response_type;
                                var msg = value.msg;
                                var date_sent = value.date_sent;


                                var tbody = "<tr><td>" + no + "</td><td>" + clinic_no + "</td><td>" + client_name + "</td><td>" + phone_no + "</td><td>" + destination + "</td><td>" + response_type + "</td><td>" + date_sent + "</td><td>" + msg + "</td></tr>";
                                tb(".wellness_results_tbody").append(tbody);
                                no++;
                            });

                            tb('.wellness_msg_table').DataTable({
                                dom: 'Bfrtip',
                                responsive: true,
                                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                                buttons: [
                                    tb.extend(true, {}, {
                                        extend: 'copyHtml5'
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'excelHtml5'
                                    }),
                                    tb.extend(true, {}, {
                                        extend: 'pdfHtml5'
                                    })
                                ]
                            });
                        }





                    }, error: function (data) {
                        sweetAlert("Oops...", "" + error_alert + "", "error");

                    }

                });



            }
            function get_incoming_msgs_logs(client_id) {
                tb(".incoming_messages_logs_div").empty();
                tb('.loader').show();
                var controller = "home";
                var get_function = "get_incoming_messages";
                var error_alert = "An Error Ocurred";
                tb.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + client_id,
                    dataType: "JSON",
                    success: function (response) {
                        tb('.loader').hide();
                        tb(".incoming_messages_logs_div").empty();
                        var table = "<table class='table table-bordered table-hover table-condensed table-stripped incoming_msg_table '><thead><tr><th>No . </th><th>Clinic No</th><th>Received On </th><th>Message .</th></tr></thead>\n\
                <tbody id='incoming_results_tbody' class='incoming_results_tbody'></tbody></table>";
                        tb(".incoming_messages_logs_div").append(table);
                        var no = 1;
                        tb.each(response, function (i, value) {

                            var phone_no = value.source;
                            var sent_on = value.created_at;
                            var msg = value.msg;



                            var tbody = "<tr><td>" + no + "</td><td>" + phone_no + "</td><td>" + sent_on + "</td><td>" + msg + "</td></tr>";
                            tb(".incoming_results_tbody").append(tbody);
                            no++;
                        });



                        tb('.incoming_msg_table').DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                            buttons: [
                                tb.extend(true, {}, {
                                    extend: 'copyHtml5'
                                }),
                                tb.extend(true, {}, {
                                    extend: 'excelHtml5'
                                }),
                                tb.extend(true, {}, {
                                    extend: 'pdfHtml5'
                                })
                            ]
                        });



                    }, error: function (data) {
                        sweetAlert("Oops...", "" + error_alert + "", "error");

                    }

                });



            }

        });
    });






</script>


<!--END MAIN WRAPPER -->


