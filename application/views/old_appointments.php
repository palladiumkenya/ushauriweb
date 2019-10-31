<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Old Appointment reports in the  system
            </div>   
            <div >


                <div class="panel-body"> 

                    


                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UPN</th>
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
                                        <td><?php echo $value->app_type_1; ?></td>
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







                    <div class="edit_div" style="display: none;">

                        <button class="back_button btn btn-primary btn-small"><span class="icon-backward"></span>Back</button>



                        <div class="appointment_logs_div" id="appointment_logs_div">

                        </div>


                    </div>










                </div>
            </div>                <div class="panel-footer">
                Get   in touch: support.tech@mhealthkenya.org                             </div>

        </div>        












    </div>



</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>








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
            var error_alert = "Ooops , Something really bad has happened (:";



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
            var error_alert = "Ooops , Something really bad has happened (:";
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




<!--END MAIN WRAPPER -->