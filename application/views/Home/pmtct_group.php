<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">PMTCT Groups</a></li>
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
                        <h4 class="card-title">PMTCT Grouping List</h4>
                        <!-- <h6 class="card-subtitle"> List of Client Groupings </h6> -->
                        <div class="table-responsive m-t-40">



                            <div class="panel-body"> 


                                <div class="table_div">



                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UPN</th>
                                    <th>Client Name</th>
                                    <th>Phone No</th>
                                    <th>Grouping</th>
                                    <th>Treatment</th>
                                    <th>Date Enrolled</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($pmtct_group as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->clinic_number; ?></td>

                                        <td>
                                            <?php echo $value->f_name . " " . $value->m_name . " " . $value->l_name; ?>


                                        </td>
                                        <td><?php echo $value->phone_no; ?></td>
                                        <td><?php echo $value->group_name; ?></td>
                                        <td><?php echo $value->client_status; ?></td>
                                        <td><?php echo $value->enrollment_date; ?></td>


                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                                    </table>


                                </div>




                            </div>





                            <div class="confirm_div" style="display: none;">


                                <div class="panel-body  formData" id="editForm">
                                    <h2 id="actionLabel"> Client Appointment</h2>


                                    <form class="confirm_form" id="confirm_form">

                                        <?php
                                        $csrf = array(
                                            'name' => $this->security->get_csrf_token_name(),
                                            'hash' => $this->security->get_csrf_hash()
                                        );
                                        ?>

                                        <input type="hidden" class="tokenizer" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                        <div class='row'>
                                            <div class='col-xs-10 form-group'>
                                                <div class='col-xs-5'>
                                                    <section class='panel'>
                                                        <header class='panel-heading'>   
                                                        </header>
                                                        <div class='panel-body'>  <div class='form'>


                                                                <input type="hidden" id="confirm_client_id" class="client_id" name="client_id"/>

                                                                <h2> Client Personal Information </h2> 
                                                                <label class='control-label'>Clinic Number* (Unique value : )</label>
                                                                <input class='form-control' type='text' readonly="" required='' name='clinic_number'  id='confirm_clinic_number' >
                                                                <label class='control-label'>First Name </label> 
                                                                <input class='form-control fname' readonly="" type='text' name='fname'  id='confirm_fname' >

                                                                <label class='control-label'>Middle Name </label>  
                                                                <input type='text' class='form-control mname' readonly="" name='mname'  id='confirm_mname'>

                                                                <label class='control-label'>Last Name</label>  
                                                                <input class='form-control lame' type='text' readonly="" name='lname'  id='confirm_lname' >

                                                                <label class='control-label'>Cell Phone Number (Own) </label> 
                                                                <input class='form-control mobile' readonly=""  type='text' name='mobile' id='confirm_mobile'   >


                                                                <label class='control-label'>Appointment Type </label> 
                                                                <input class='form-control app_type_1' placeholder="Appointment Type " readonly=""  type='text' name='app_type_1' id='app_type_1'   >


                                                                <div class="appointment_kept_div" id="appointment_kept_div" style="display: inline;">

                                                                </div>


                                                                <label class='control-label'> Next Appointment Date </lable> 
                                                                    <input type='text' readonly="" placeholder="DD/MM/YYYY" class='form-control appointment_date' name='apptdate' id='appointment_date'/> 
                                                                    <input type='hidden'  name='appointment_type' id='edit_appointment_type' value='Appointment' class='form-control' readonly/>


                                                            </div>
                                                            <button type="submit" class="submit_confirm_div btn btn-success btn-small" id="submit_confirm_div">Submit</button>
                                                            <button type="button" class="cancel_confirm_client btn btn-danger btn-small" id="cancel_confirm_client">Cancel</button>


                                                        </div> </section></div>










                                            </div>
                                        </div>   






                                    </form>





                                </div>  




                            </div>








                            <div class="edit_div" style="display: none;">

                                <div class="panel-body  formData" id="editForm">
                                    <h2 id="actionLabel">Message Logs </h2>


                                    <button class="back_button btn btn-primary btn-small"><span class="icon-backward"></span>Back</button>


                                </div>

                                <div class="appointment_logs_div" id="appointment_logs_div">

                                </div>


                            </div>





                            <?php
                            $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                            );
                            ?>




                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PAge Content -->



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







        $(document).on('click', ".confirm_btn", function () {
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

                    $.each(response, function (i, value) {
                        $("#confirm_client_id").empty();

                        $("#confirm_status").empty();

                        $("#confirm_f_name").empty();

                        $("#confirm_m_name").empty();

                        $("#confirm_l_name").empty();


                        $("#confirm_mobile").empty();


                        $("#confirm_client_id").val(value.client_id);
                        $('#confirm_clinic_number').val(value.clinic_number);
                        $('#confirm_mobile').val(value.phone_no);
                        $('#confirm_fname').val(value.f_name);
                        $('#confirm_mname').val(value.m_name);
                        $('#confirm_lname').val(value.l_name);






                        $(".confirm_div").show();
                        $(".table_div").hide();
                        $(".edit_div").hide();




                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                    });





                }, error: function (data) {
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });














        $(document).on('click', ".edit_btn", function () {


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
                        $('#edit_mname').val(value.clinic_number);
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
            var controller = "home";
            var get_function = "get_appointment_logs";
            var error_alert = "An Error Ocurred";
            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + client_id,
                dataType: "JSON",
                success: function (response) {
                    var table = "<table class='table table-bordered table-hover table-condensed table-stripped '><thead><th>No.</th><th>Clinic No</th><th>Client Name.</th><th>Phone No</th><th>Appointment Date.</th><th>Status </th><th>No.</th><th>Enrollmet Date</th><th>App Message .</th></thead>\n\
                <tbody id='app_results_tbody' class='app_results_tbody'></tbody></table>";
                    $(".appointment_logs_div").append(table);
                    $.each(response, function (i, value) {

                        var clinic_no = value.clinic_number;
                        var client_name = value.f_name + " " + value.m_name + " " + value.l_name;
                        var phone_no = value.phone_no;
                        var appointment_dte = value.appntmnt_date;
                        var status = value.status;
                        var enrollment = value.enrollment_date;
                        var updated_at = value.updated_at;
                        var app_msg = value.app_msg;



                        var tbody = "<tr><td>1</td><td>" + clinic_no + "</td><td>" + client_name + "</td><td>" + phone_no + "</td><td>" + appointment_dte + "</td><td>" + status + "</td><td>" + enrollment + "</td><td>" + app_msg + "</td></tr>";
                        $(".app_results_tbody").append(tbody);
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
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });





        $(".submit_confirm_div").click(function () {
            var controller = "home";
            var submit_function = "update_appointment";
            var form_class = "confirm_form";
            var success_alert = "Client Appointment updated successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });












    });
</script>




<!--END MAIN WRAPPER -->


















