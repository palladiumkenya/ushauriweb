<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">






    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                ADHERENCE SUMMARY FOR 
            </div>
            <div class="panel-body">

                <div class="col-lg-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="icon-bell"></i> Attendance Summary (Pre-ART)
                        </div>

                        <div class="panel-body">

                            <table class="table table-bordered table-condensed table-hover table-responsive table-striped">
                                <thead>
                                    <tr>
                                        <th align="center" style="width: 100%" rowspan="2">Attendance (Pre-ART)</th>
                                        <th colspan="2" style="width: 100%"> < 15 yrs</th>
                                        <th colspan="2" style="width: 100%" > > 15 yrs</th>
                                        <th style="width: 100%" rowspan="2">Total</th>  
                                    </tr>
                                    <tr>
                                       
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>

                                    </tr>  </thead>
                                <tbody>
                                    <?php
                                    $args = array_merge($att_pre_art1, $att_pre_art2);
                                   // print_r($args);
                                    $tmp = array();

                                    foreach ($args as $arg) {
                                        $tmp[$arg['app_status']][] = @$arg['Male1'];
                                        $tmp[$arg['app_status']][] = @$arg['Male2'];
                                        $tmp[$arg['app_status']][] = @$arg['Female1'];
                                        $tmp[$arg['app_status']][] = @$arg['Female2'];
                                    }

                                    $output = array();

                                    foreach ($tmp as $type => $labels) {
                                        $output[] = array(
                                            'app_status' => $type,
                                            'values' => $labels
                                        );
                                    }

                                    foreach ($output as $value) {
                                        ?>
                                        <tr>
                                            <td style="width: 100%"><?php echo $value['app_status']; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][0]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][2]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][5]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][7]; ?></td>
                                            <td style="width: 100%"><?php echo array_sum($value['values']); ?></td>
                                        </tr>
                                    <?php }
                                    ?>







                                </tbody>
                            </table>

                        </div>

                    </div>



                </div>


                <div class="col-lg-6">

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="icon-bell"></i> Scheduled Summary (Pre-ART)
                        </div>

                        <div class="panel-body">

                            <table class="table table-bordered table-condensed table-hover table-responsive table-striped">
                                <thead>
                                    <tr>
                                        <th align="center" style="width: 100%" rowspan="2">Scheduled (Pre-ART)</th>
                                        <th colspan="2" style="width: 100%"> < 15 yrs</th>
                                        <th colspan="2" style="width: 100%" > > 15 yrs</th>
                                        <th rowspan="2" style="width: 100%">Total</th>  
                                    </tr>
                                    <tr>
                                       
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>

                                    </tr>  </thead>
                                <tbody>
                                    <?php
                                    $args = array_merge($schld_pre_art1, $schld_pre_art2);

                                    $tmp = array();

                                    foreach ($args as $arg) {
                                        $tmp[$arg['app_status']][] = @$arg['Male1'];
                                        $tmp[$arg['app_status']][] = @$arg['Male2'];
                                        $tmp[$arg['app_status']][] = @$arg['Female1'];
                                        $tmp[$arg['app_status']][] = @$arg['Female2'];
                                    }

                                    $output = array();

                                    foreach ($tmp as $type => $labels) {
                                        $output[] = array(
                                            'app_status' => $type,
                                            'values' => $labels
                                        );
                                    }

                                    foreach ($output as $value) {
                                        ?>
                                        <tr>
                                            <td style="width: 100%"><?php echo $value['app_status']; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][0]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][2]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][5]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][7]; ?></td>
                                            <td style="width: 100%"><?php echo array_sum($value['values']); ?></td>
                                        </tr>
                                    <?php }
                                    ?>







                                </tbody>
                            </table>

                        </div>

                    </div>



                </div>
                <div class="col-lg-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="icon-bell"></i> Attendance Summary (ART)
                        </div>

                        <div class="panel-body">

                            <table class="table table-bordered table-condensed table-hover table-responsive table-striped">
                                <thead>
                                    <tr>
                                        <th align="center" style="width: 100%" rowspan="2">Attendance (ART)</th>
                                        <th colspan="2" style="width: 100%"> < 15 yrs</th>
                                        <th colspan="2" style="width: 100%" > > 15 yrs</th>
                                        <th rowspan="2" style="width: 100%">Total</th>  
                                    </tr>
                                    <tr>
                                        
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>

                                    </tr>  </thead>
                                <tbody>
                                    <?php
                                    $args = array_merge($att_art1, $att_art2);

                                    $tmp = array();

                                    foreach ($args as $arg) {
                                        $tmp[$arg['app_status']][] = @$arg['Male1'];
                                        $tmp[$arg['app_status']][] = @$arg['Male2'];
                                        $tmp[$arg['app_status']][] = @$arg['Female1'];
                                        $tmp[$arg['app_status']][] = @$arg['Female2'];
                                    }

                                    $output = array();

                                    foreach ($tmp as $type => $labels) {
                                        $output[] = array(
                                            'app_status' => $type,
                                            'values' => $labels
                                        );
                                    }

                                    foreach ($output as $value) {
                                        ?>
                                        <tr>
                                            <td style="width: 100%"><?php echo $value['app_status']; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][0]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][2]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][5]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][7]; ?></td>
                                            <td style="width: 100%"><?php echo array_sum($value['values']); ?></td>
                                        </tr>
                                    <?php }
                                    ?>







                                </tbody>
                            </table>

                        </div>

                    </div>



                </div>






                <div class="col-lg-6">

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <i class="icon-bell"></i> Scheduled Summary (ART)
                        </div>

                        <div class="panel-body">

                            <table class="table table-bordered table-condensed table-hover table-responsive table-striped">
                                <thead>
                                    <tr>
                                        <th align="center" style="width: 100%" rowspan="2">Scheduled Summary</th>
                                        <th colspan="2" style="width: 100%"> < 15 yrs</th>
                                        <th colspan="2" style="width: 100%" > > 15 yrs</th>
                                        <th rowspan="2" style="width: 100%">Total</th>  
                                    </tr>
                                    <tr>
                                        
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>
                                        <th style="width: 50%">Male</th>
                                        <th style="width: 50%">Female</th>

                                    </tr>  </thead>
                                <tbody>
                                    <?php
                                    $args = array_merge($schld_art1, $schld_art2);

                                    $tmp = array();

                                    foreach ($args as $arg) {
                                        $tmp[$arg['app_status']][] = @$arg['Male1'];
                                        $tmp[$arg['app_status']][] = @$arg['Male2'];
                                        $tmp[$arg['app_status']][] = @$arg['Female1'];
                                        $tmp[$arg['app_status']][] = @$arg['Female2'];
                                    }

                                    $output = array();

                                    foreach ($tmp as $type => $labels) {
                                        $output[] = array(
                                            'app_status' => $type,
                                            'values' => $labels
                                        );
                                    }

                                    foreach ($output as $value) {
                                        ?>
                                        <tr>
                                            <td style="width: 100%"><?php echo $value['app_status']; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][0]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][2]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][5]; ?></td>
                                            <td style="width: 50%" ><?php echo $value['values'][7]; ?></td>
                                            <td style="width: 100%"><?php echo array_sum($value['values']); ?></td>
                                        </tr>
                                    <?php }
                                    ?>







                                </tbody>
                            </table>

                        </div>

                    </div>



                </div>


            </div>
        </div>
    </div>


    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Scheduled Appointments in the  System
            </div>   
            <div >

                <div class="panel-body">

                    <table id="table" class="table table-bordered table-condensed table-hover table-responsive table-stripped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>UPN</th>
                                <th>Client Name</th>
                                <th>Phone No</th>
                                <th>Appointment Date</th>
                                <th>Appointment Type</th>
                                <?php
                                $access_level = $this->session->userdata('access_level');
                                if ($access_level == "Facility") {
                                    ?>

                                    <th>Outgoing Msg</th>
                                    <th>Action</th>
                                    <?php
                                } else {
                                    ?>


                                    <?php
                                }
                                ?>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($appointments as $value) {
                                ?>
                                <tr>
                                    <td class="a-center"><?php echo $i; ?></td>



                                    <?php
                                    $view_client = $this->session->userdata('view_client');

                                    if ($view_client == "Yes") {
                                        ?>
                                        <td>
                                            <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                            <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                <?php echo $value->clinic_number; ?>
                                            </button>

                                        </td>
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
                                        <td>XXXXXX XXXXXXX</td>
                                        <?php
                                    }
                                    ?>
                                    <td><?php echo $value->appntmnt_date; ?></td>
                                    <td><?php echo $value->app_type_1; ?></td>
                                    <?php
                                    $access_level = $this->session->userdata('access_level');
                                    if ($access_level == "Facility") {
                                        ?>

                                        <td><?php echo $value->app_msg; ?></td>
                                        <td>  
                                            <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                            <input type="hidden" id="app_type_1" name="app_type_1" class="app_type_1 form-control" value="<?php echo $value->app_type_1; ?>"/>
                                            <button class="btn btn-primary btn-small confirm_btn" id="confirm_btn">Confirm</button></td>
                                        <?php
                                    } else {
                                        ?>



                                        <?php
                                    }
                                    ?> </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>

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



        $(document).on('click', ".confirm_btn", function () {
            $('.loader').show();
            //get data
            var data_id = $(this).closest('tr').find('input[name="client_id"]').val();
            var app_type_1 = $(this).closest('tr').find('input[name="app_type_1"]').val();

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
                        $('.app_type_1').val(app_type_1);

                        $('.appointment_kept_div').empty();
                        var appointment_kept = "<label class='control-label'> Was the  previous appointment Kept ? :</label>\n\
                <select class='form-control' name='app_kept' id='app_kept' required='' > \n\
                 <option value=''>Please select :</option> <option value='Yes'>Yes</option>\n\
                 <option value='No'>No</option> \n\
                </select>";
                        $('.appointment_kept_div').append(appointment_kept);







                        $(".confirm_div").show();
                        $(".table_div").hide();
                        $(".edit_div").hide();
                        $(".appointment_logs_div").hide();




                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                    });





                }, error: function (data) {
                    $('.loader').hide();
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
                        $('#edit_mname').val(value.clinic_number);
                        $('#edit_fname').val(value.f_name);
                        $('#edit_mname').val(value.m_name);
                        $('#edit_lname').val(value.l_name);
                        $('#edit_dob').val(value.dob);

                        $('#edit_condition option[value="' + value.client_status + '"]').attr("selected", "selected");
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


        $(".cancel_add_client").click(function () {
            $(".edit_form").hide();
            $(".table_div").show();
            $(".edit_div").hide();
        });

        $(".cancel_confirm_client").click(function () {
            $(".confirm_div").hide();
            $(".table_div").show();
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








        $(".submit_edit_div").click(function () {
            var controller = "home";
            var submit_function = "update_client";
            var form_class = "edit_form";
            var success_alert = "Client data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });





        $(".submit_confirm_div").click(function () {
            var controller = "home";
            var submit_function = "update_appointment";
            var form_class = "confirm_form";
            var success_alert = "Client Appointment updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });












    });
</script>




<!--END MAIN WRAPPER -->