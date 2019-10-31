<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Appointments</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->




        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Appointments List</h4>
                        <h6 class="card-subtitle">A list of Appointments in the  system </h6>
                        <div class="table-responsive m-t-40">



                            <div class="panel-body"> 




                                <div class="table_div">



                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>UPN</th>
                                                <th>Serial no </th>
                                                <th>Client Name</th>
                                                <th>Phone No</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Type</th>
                                                <th>Status</th>
                                                <th>Enrollment Date</th>
                                                <th>ART Date</th>
                                                <th>Date Added</th>
                                                <th>Time Stamp</th>



                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($appointments as $value) {
                                                ?>
                                                <tr>
                                                    <td class="a-center"><?php echo $i; ?></td>
                                                    <td>
                                                        <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                        <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                            <?php echo $value->clinic_number; ?>
                                                        </button>

                                                    </td>
                                                    <td><?php echo $value->file_no; ?></td>
                                                    <?php
                                                    $view_client = $this->session->userdata('view_client');

                                                    if ($view_client == "Yes") {
                                                        ?>

                                                        <td><?php
                                                            $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                            echo $client_name;
                                                            ?></td>
                                                        <td><?php echo $value->phone_no; ?></td>
                                                        <?php
                                                    } else {
                                                        ?>

                                                        <td>XXXXXX XXXXXXX</td>
                                                        <td>XXXXXX XXXXXXX</td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td><?php echo $value->appntmnt_date; ?></td>
                                                    <td><?php echo $value->appointment_types; ?></td>
                                                    <td><?php echo $value->status; ?></td>
                                                    <td><?php echo $value->enrollment_date; ?></td>
                                                    <td><?php echo $value->art_date; ?></td>
                                                    <td><?php echo $value->created_at; ?></td>
                                                    <td><?php echo $value->updated_at; ?></td>



                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>


                                </div>










                            </div>








                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PAge Content -->










        <div class="add_div" style="display: none;">


            <div class="panel-body  formData" id="editForm">
                <h2 id="actionLabel"> Client Appointment</h2>


                <form class="add_form" id="add_form">



                    <?php
                    $csrf = array(
                        'name' => $this->security->get_csrf_token_name(),
                        'hash' => $this->security->get_csrf_hash()
                    );
                    ?>

                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />




                    <div class='row'>
                        <div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form-group'>
                            <div class='col-xs-5 col-md-5 col-sm-5 col-lg-5 col-lg-5'>
                                <section class='panel'>
                                    <header class='panel-heading'>   
                                    </header>
                                    <div class='panel-body'>  <div class='form'>

                                            <button type="button" class="go_back_view btn btn-default btn-small" id="go_back_view"><i class="icon-backward  "></i>Go Back</button>

                                            <h2> Client Personal Information </h2> 
                                            <label class='control-label'>Unique Patient Number* (Unique value : )</label><span style="color: red">*</span>
                                            <input type="text" readonly="" value="<?php echo $this->session->userdata('facility_id'); ?>" class="form-control mfl_code" id="mfl_code" name="mfl_code" placeholder="MFL Code"/>
                                            <input class='form-control new_clinic_number' placeholder="Enter Unique Patient Number"  type='text' required='' name='clinic_number' id='clinic_number' >
                                            <br>
                                            <button type="button" class="new_check_clinic_number btn btn-default" id="new_check_clinic_number">Check Clinic No : </button>


                                            <div class="hide_other_info" id="hide_other_info" style="display: none;">

                                                <input type="hidden" name="client_id" id="add_client_id" class="add_client_id form-control"/>
                                                <label class='control-label'>First Name </label> 
                                                <input class='form-control add_fname' readonly="" type='text' name='fname'  id='add_fname' >

                                                <label class='control-label'>Middle Name </label>  
                                                <input type='text' class='form-control add_mname' readonly="" name='mname'  id='add_mname'>






                                            </div>       </div>


                                    </div> </section>
                            </div>
                            <div class='col-xs-5 col-md-5 col-sm-5 col-lg-5 col-lg-5'>


                                <section class='panel'>
                                    <header class='panel-heading'>   
                                    </header>
                                    <div class='panel-body'>  <div class='form'>


                                            <div class="hide_other_info" id="hide_other_info" style="display: none;">

                                                <label class='control-label'>Last Name</label>  
                                                <input class='form-control add_lname' type='text' readonly="" name='lname'  id='add_lname' >

                                                <label class='control-label'>Cell Phone Number (Own) </label> 
                                                <input class='form-control add_mobile' readonly=""  type='text' name='mobile' id='add_mobile'   >


                                                <label class='control-label'>Appointment Type </label> 


                                                <label class='control-label'> Appointment Type 1: </label>
                                                <select class='form-control' name='appointment_type' id='p_apptype1' >
                                                    <option value=''>Please select : </option>
                                                    <?php
                                                    foreach ($app_types as $value) {
                                                        ?>
                                                        <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>




                                                <label class='control-label'> Next Appointment Date </lable> 
                                                    <input type='text' readonly="" placeholder="DD/MM/YYYY" class='form-control appointment_date' name='appointment_date' id='appointment_date'/> 


                                            </div>       </div>
                                        <div class="hide_other_info" style="display: none;">


                                            <button type="submit" class="submit_add_appointment btn btn-success btn-small" id="submit_add_appointment"><i class="icon-save"></i>Submit</button>
                                            <button type="button" class="cancel_add_appointment_form btn btn-danger btn-small" id="cancel_add_appointment_form"><i class="icon-stop"></i>Cancel</button>
                                        </div>

                                    </div> </section>


                            </div>










                        </div>
                    </div>   






                </form>





            </div>  


        </div>












        <div class="edit_app_div" style="display: none;">




            <div class="panel-body  formData" id="editForm">
                <h2 id="actionLabel"> Client Appointment</h2>


                <form class="edit_appointment_form" id="edit_appointment_form">



                    <?php
                    $csrf = array(
                        'name' => $this->security->get_csrf_token_name(),
                        'hash' => $this->security->get_csrf_hash()
                    );
                    ?>

                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />





                    <div class='row'>
                        <div class='col-xs-10 form-group'>
                            <div class='col-xs-5'>
                                <section class='panel'>
                                    <header class='panel-heading'>   
                                    </header>
                                    <div class='panel-body'>  <div class='form'>


                                            <input type="hidden" name="client_id" id="edit_client_id" class="edit_client_id form-control"/>
                                            <input type="hidden" name="appointment_id" id="edit_appointment_id" class="edit_appointment_id form-control"/>


                                            <h4 class="client_name" id="client_name"></h4>
                                            <h4 class="client_phone" id="client_phone"></h4>



                                            <label class='control-label'> Appointment Type : </label>

                                            <select class='form-control client_app_type' required="" name='p_apptype1' id='client_app_type' >
                                                <option value=''>Please Select : </option>
                                                <?php
                                                foreach ($appointment_types as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                                    <?php
                                                }
                                                ?>


                                            </select>
                                            <label class="control-label">Was previous appointment kept? </label>
                                            <select class='form-control app_kept' required="" name='app_kept' id='app_kept' >
                                                <option value=''>Please Select : </option>
                                                <option value='Yes'>Yes </option>
                                                <option value='No'>No </option>

                                            </select>




                                            <label class='control-label'> Next Appointment Date </lable> 
                                                <input type='text' readonly="" placeholder="DD/MM/YYYY" class='form-control client_appointment_date appointment_date' name='appointment_date' id='client_appointment_date'/> 

                                        </div>

                                        <button type="submit" class="submit_edit_appointment btn btn-success btn-small" id="submit_edit_appointment">Submit</button>
                                        <button type="button" class="cancel_edit_appointment_form btn btn-danger btn-small" id="cancel_edit_appointment_form">Cancel</button>


                                    </div> </section></div>










                        </div>
                    </div>   






                </form>





            </div>  



        </div>









        <div class="edit_div" style="display: none;">

            <button class="back_button btn btn-primary btn-small"><span class="icon-backward"></span>Back</button>



            <div class="appointment_logs_div" id="appointment_logs_div">

            </div>


        </div>




























        <!-- END COMMENT AND NOTIFICATION  SECTION -->

    </div>






    <!--END MAIN WRAPPER -->


</div>





<!-- End PAge Content -->
</div>
<!-- End Container fluid  -->
<!-- footer -->
<footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
<!-- End footer -->
</div>
<!-- End Page wrapper  -->


<!--END BLOCK SECTION -->












<script type="text/javascript">
    $(document).ready(function () {
        $(".back_button").click(function () {
            $(".table_div").show();
            $(".edit_div").hide();
        });




        $(".cancel_add_appointment_form").click(function () {
            $(".table_div").show();
            $(".add_div").hide();
            $(".edit_app_div").hide();
            $(".back_button").hide();
            $(".edit_div").hide();
            $(".add_btn").show();
        });
        $(".cancel_edit_appointment_form").click(function () {
            $(".table_div").show();
            $(".add_div").hide();
            $(".edit_app_div").hide();
            $(".back_button").hide();
            $(".edit_div").hide();
            $(".add_btn").show();
        });
        $(".back_button").click(function () {
            $(".table_div").show();
            $(".edit_div").hide();
            $(".add_div").hide();
            $(".add_btn").show();
        });


        $(document).on('click', ".add_btn", function () {
            $(".add_div").show();
            $(".table_div").hide();
            $(".edit_div").hide();
            $(".add_btn").hide();
        });
        $(document).on('click', ".go_back_view", function () {
            $(".add_div").hide();
            $(".table_div").show();
            $(".edit_div").hide();
            $(".add_btn").show();
        });








        $(document).on('click', ".edit_app_btn", function () {
            $('.loader').show();

            //get data
            var data_id = $(this).closest('tr').find('input[name="appointment_id"]').val();
            var controller = "home";
            var get_function = "get_appointment_data";
            var error_alert = "Ooops , An Error Occured!";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();

                    $.each(response, function (i, value) {
                        $("#appointment_id").empty();

                        $("#clinic_number").empty();
                        $("#client_name").empty();

                        $("#client_phone").empty();



                        $("#client_appointment_date").empty();



                        $('#edit_appointment_id').val(value.appointment_id);
                        $('#edit_client_id').val(value.client_id);
                        $('#clinic_number').val(value.clinic_number);
                        $('#client_name').append("Client Name : " + value.f_name + " " + value.m_name + " " + value.l_name);
                        $('#client_phone').append("Client Phone No : " + value.phone_no);
                        $('#client_appointment_date').val(value.appntmnt_date);
                        $('#client_app_type').val(value.app_type_1);


                        // $('#client_app_type option[value=' + value.app_type_1 + ']').attr("selected", "selected");



                        $(".edit_app_div").show();
                        $(".table_div").hide();





                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                    });





                }, error: function (data) {
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });


        $(document).on('click', ".edit_btn", function () {
            $('.loader').show();

            //get data
            var data_id = $(this).closest('tr').find('input[name="client_id"]').val();
            var controller = "home";
            var get_function = "get_client_data";
            var error_alert = "An Error Ocurred";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    get_appointment_logs(data_id);
                    $.each(response, function (i, value) {
                        $("#edit_user_id").empty();

                        $("#edit_status").empty();

                        $("#edit_f_name").empty();

                        $("#edit_m_name").empty();

                        $("#edit_l_name").empty();

                        $("#edit_dob").empty();

                        $("#edit_phone_no").empty();

                        $("#edit_e_mail").empty();
                        $("#edit_status").empty();

                        $('#edit_client_id').val(value.client_id);
                        $('#edit_clinic_number').val(value.clinic_number);
                        $('#edit_fname').val(value.f_name);
                        $('#edit_mname').val(value.m_name);
                        $('#edit_lname').val(value.l_name);
                        $('#edit_dob').val(value.dob);

                        $('#edit_condition option[value=' + value.client_status + ']').attr("selected", "selected");
                        $('#edit_group option[value=' + value.group_id + ']').attr("selected", "selected");
                        $('#edit_facilities option[value=' + value.facility_id + ']').attr("selected", "selected");
                        $('#edit_frequency').val(value.txt_frequency);
                        $('#edit_time option[value=' + value.txt_time + ']').attr("selected", "selected");


                        $('#edit_mobile').val(value.phone_no);
                        $('#edit_altmobile').val(value.alt_phone_no);
                        $('#edit_sharename').val(value.shared_no_name);
                        $('#edit_lang option[value=' + value.language_id + ']').attr("selected", "selected");
                        $('#edit_smsenable option[value=' + value.smsenable + ']').attr("selected", "selected");


                        $('#appointment_date').val(value.appntmnt_date);
                        $('#edit_p_apptype1 option[value=' + value.app_type_1 + ']').attr("selected", "selected");
                        //$('#edit_p_apptype2 option[value=' + value.app_type_2 + ']').attr("selected", "selected");
                        $('#edit_p_apptype2').val(value.app_type_2);
                        $('#edit_p_apptype3').val(value.expln_app);
                        $('#edit_p_custommsg').val(value.custom_txt);




                        $(".edit_div").show();
                        $(".table_div").hide();





                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                    });





                }, error: function (data) {
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });



        function get_appointment_logs(client_id) {
            $(".appointment_logs_div").empty();
            $('.loader').show();
            var controller = "home";
            var get_function = "get_appointment_logs";
            var error_alert = "An Error Ocurred";
            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + client_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    var table = "<table class='table table-bordered table-hover table-condensed table-stripped '><thead><th>No . </th><th>Clinic No</th><th>Client Name.</th><th>Phone No</th><th>Message Type.</th><th>Status </th><th>Sent On </th><th>App Message .</th></thead>\n\
                <tbody id='app_results_tbody' class='app_results_tbody'></tbody></table>";
                    $(".appointment_logs_div").append(table);
                    var no = 1;
                    $.each(response, function (i, value) {

                        var clinic_no = value.clinic_number;
                        var client_name = value.f_name + " " + value.m_name + " " + value.l_name;
                        var phone_no = value.phone_no;
                        var sent_on = value.sent_on;
                        var status = value.status;
                        var enrollment = value.enrollment_date;
                        var updated_at = value.updated_at;
                        var msg = value.msg;
                        var msg_type = value.msg_type;



                        var tbody = "<tr><td>" + no + "</td><td>" + clinic_no + "</td><td>" + client_name + "</td><td>" + phone_no + "</td><td>" + msg_type + "</td><td>" + status + "</td><td>" + sent_on + "</td><td>" + msg + "</td></tr>";
                        $(".app_results_tbody").append(tbody);
                        no++;
                    });





                }, error: function (data) {
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });



        }


        $(".close_add_div").click(function () {
            $(".add_div").hide();
            $(".table_div").show();
            $(".f_name").empty();

            $(".l_name").empty();

            $(".phone_no").empty();
            $(".e_mail").empty();

            $(".m_name").empty();
            $(".dob").empty();

        });








        $(".submit_edit_div").click(function () {
            var controller = "home";
            var submit_function = "update_client";
            var form_class = "edit_form";
            var success_alert = "Client data updated successfully ... :) ";
            var error_alert = "Ooops , Something happened, we are working on it (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });

        $(".submit_edit_appointment").click(function () {
            var controller = "home";
            var submit_function = "edit_appointment";
            var form_class = "edit_appointment_form";
            var success_alert = "Client Appointment was updated successfully ... :) ";
            var error_alert = "Ooops , Something  happened, we are working on it (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });











        $(".close_add_div").click(function () {
            $(".add_div").hide();
            $(".table_div").show();
            $(".f_name").empty();

            $(".l_name").empty();

            $(".phone_no").empty();
            $(".e_mail").empty();

            $(".m_name").empty();
            $(".dob").empty();

        });








        $(".submit_add_appointment").click(function () {
            var controller = "home";
            var submit_function = "add_appointment";
            var form_class = "add_form";
            var success_alert = "Client appointment was added successfully ... :) ";
            var error_alert = "Ooops , An Error Occured (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });











        $(".new_check_clinic_number").click(function () {

            $('.loader').show();
            var clinic_number = $(".new_clinic_number").val();
            var mfl_code = $(".mfl_code").val();
            var upn = mfl_code + clinic_number;
            var controller = "home";
            var get_function = "check_clinic_no";
            var error_alert = "Sorry  , Error occured (:";
            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + upn,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    var isempty = jQuery.isEmptyObject(response);
                    if (isempty) {
                        swal("Error", "Clinic number  : " + upn + " was not found in the  system ", "warning");
                        $(".hide_other_info").hide();
                    } else {
                        $(".hide_other_info").show();


                        $('.add_fname').val(response[0].f_name);
                        $('.add_mname').val(response[0].m_name);
                        $(".add_lname").val(response[0].l_name);
                        $(".add_mobile").val(response[0].phone_no);
                        $(".add_client_id").val(response[0].id);

                        $.each(response, function (i, value) {




                        });
                    }





                }, error: function (data) {
                    $('.loader').hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");
                }

            });
        });









    });
</script>


































