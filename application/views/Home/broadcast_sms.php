<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">Not Okay Check-Ins</a></li>
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
                        <h4 class="card-title">Broadcast Service Module</h4>
                        <h6 class="card-subtitle"> User Broadcast service module </h6>
                        <div class="table-responsive m-t-40">







                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UsersModal" id="for_users">
                                <i class="fa fa-mail-forward"></i> Broadcast to Users
                            </button>
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ClientsModal">
                                <i class="fa fa-mail-forward"></i> Broadcast to Clients
                            </button>

                            <!-- Users Modal -->
                            <div class="modal" id="UsersModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Users Broadcast Service</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">

                                            <form class="broadcast_user_form" id="broadcast_user_form">
                                                <div class="col-lg-12">
                                                    <div class="row">




                                                        <?php
                                                        $csrf = array(
                                                            'name' => $this->security->get_csrf_token_name(),
                                                            'hash' => $this->security->get_csrf_hash()
                                                        );
                                                        ?>

                                                        <input class="tokenizer" id="tokenizer" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />




                                                        <div class="col-sm-6">




                                                            <label class='control-label'>Broad cast Name </label>

                                                            <input type="text" name="broadcast_name" required="" class=" input input-rounded input-sm form-control broadcast_name " id="broadcast_name" placeholder="Broad cast Name " />


                                                            <label class='control-label'>Select Access Level : </label>
                                                            <select name='access_level' required="" class=' input input-rounded input-sm form-control filter_user_access_level' id='filter_user_access_level'>
                                                                <option value=''>Please select: </option>
                                                                <option value='0'>All</option>
                                                                <?php
                                                                foreach ($access_levels as $value) {
                                                                ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                <?php
                                                                }
                                                                ?>

                                                            </select>

                                                            <?php
                                                            $access_level = $this->session->userdata('access_level');
                                                            if ($access_level == "Facility") {
                                                            ?>

                                                            <?php
                                                            } else {
                                                            ?>


                                                                <div class="filter_county_div">
                                                                    <label class='control-label'>County</label>

                                                                    <select name='county' class='input input-rounded input-sm form-control  filter_user_county' id='filter_user_county'>
                                                                        <option value="">Please select : </option>
                                                                        <option value="0">All</option>
                                                                        <?php
                                                                        foreach ($counties as $value) {
                                                                        ?>
                                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>

                                                                    </select>

                                                                    <br>
                                                                </div>

                                                                <div class="filter_sub_county_div">
                                                                    <label class='control-label'>Sub County</label>


                                                                    <span class="filter_user_sub_county_wait" style="display: none;">Loading Sub Counties, Please Wait ...</span>

                                                                    <select class=" input input-rounded input-sm form-control filter_user_sub_county" name="sub_county" id="filter_user_sub_county">
                                                                        <option value="">Please Select : </option>
                                                                    </select>

                                                                    <br>
                                                                </div>
                                                        </div>
                                                        <div class="col-sm-6">



                                                            <div class="filter_facility_div">
                                                                <label class="control-label">Facilities</label>
                                                                <span class="filter_user_facility_wait" style="display: none;">Loading Facilities , Please Wait ...</span>
                                                                <select class="form-control filter_user_facility" name="facility" id="filter_user_facility">
                                                                    <option value="">Please select : </option>
                                                                </select>

                                                                <br>
                                                            </div>



                                                        <?php
                                                            }
                                                        ?>

                                                        <?php $today = date("Y-m-d H:i:s"); ?>
                                                        <label class='control-label'>Current Date and Time</label>
                                                        <li class="current_time"></li>
                                                        <label class='control-label'>Broadcast Date : </label>
                                                        <input type='text' name='broadcast_date' required="" id='user_broadcast_date' class=' input input-rounded input-sm user_broadcast_date form-control appointment_date' />

                                                        <label class='control-label'>Broadcast Time</label>
                                                        <select name='broadcast_time' required="" class=' input input-rounded input-sm form-control user_broadcast_time' id='user_broadcast_time'>
                                                            <option value="">Please select : </option>
                                                            <?php foreach ($times as $value) {
                                                            ?>
                                                                <option value='<?php echo $value->id; ?>'> <?php echo $value->name; ?> </option>
                                                            <?php }
                                                            ?>


                                                        </select>



                                                        <div>
                                                            <label class="control-label">Target Users</label>
                                                            <span class="span_no_users" id="span_no_users"></span></div>
                                                        <label class='control-label'>Broadcast SMS :</label>
                                                        <textarea required="" name='broadcast_message' id='user_broadcast_sms' onkeyup="countChar(this)" class=' input input-rounded input-sm form-control user_broadcast_sms'> </textarea> <br />


                                                        <br>
                                                        <button type="submit" class="submit_user_broadcast btn btn-success btn-small" id="submit_user_broadcast">Set SMS</button>

                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                        <!--<button type="button" class="cancel_user_broadcast btn btn-danger btn-small" id="cancel_user_broadcast">Cancel</button>-->

                                                        </div>










                                                    </div>
                                                </div>


                                            </form>


                                        </div>

                                        <!-- Modal footer -->
                                        <!-- <div class="modal-footer">
                                            <button type="submit" class="submit_user_broadcast btn btn-success btn-small" id="submit_user_broadcast">Set SMS</button>

                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div> -->

                                    </div>
                                </div>
                            </div>
                            <!-- Clients Modal -->
                            <div class="modal" id="ClientsModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Clients Broadcast Service</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">


                                            <div class="col-lg-12">
                                                <form class="add_form" id="add_form">


                                                    <div>
                                                        <?php
                                                        $csrf = array(
                                                            'name' => $this->security->get_csrf_token_name(),
                                                            'hash' => $this->security->get_csrf_hash()
                                                        );
                                                        ?>

                                                        <input class="tokenizer" id="tokenizer" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                                        <div class='row'>



                                                            <div class="col-sm-6">
                                                                <label class='control-label'>Broad cast Name </label>

                                                                <input type="text" name="broadcast_name" required="" class=" input input-rounded input-sm form-control broadcast_name " id="broadcast_name" placeholder="Broad cast Name " />

                                                                <?php
                                                                $access_level = $this->session->userdata('access_level');
                                                                if ($access_level == "Facility") {
                                                                ?>

                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <label class='control-label'>County</label>

                                                                    <select name='county' class=' input input-rounded input-sm form-control county filter_county' id='county'>
                                                                        <option value="">Please select : </option>
                                                                        <option value="All">All</option>
                                                                        <?php
                                                                        foreach ($counties as $value) {
                                                                        ?>
                                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>

                                                                    </select>
                                                                    <br>
                                                                    <label class='control-label'>Sub County</label>


                                                                    <span class="filter_sub_county_wait" style="display: none;">Loading Sub Counties, Please Wait ...</span>

                                                                    <select class=" input input-rounded input-sm form-control filter_sub_county" name="sub_county" id="filter_sub_county">
                                                                        <option value="">Please Select : </option>
                                                                    </select>
                                                                    <br>
                                                                    <label class="control-label">Facilities</label>
                                                                    <span class="filter_facility_wait" style="display: none;">Loading Facilities , Please Wait ...</span>
                                                                    <select class=" input input-rounded input-sm form-control filter_facility" name="facility" id="filter_facility">
                                                                        <option value="">Please select : </option>
                                                                    </select>

                                                                <?php
                                                                }
                                                                ?>
                                                                <label class='control-label'>Client Category </label>



                                                                <select name='group' class=' input input-rounded input-sm form-control group' required="" id='group'>
                                                                    <option value="">Please select : </option>
                                                                    <option value="All">All</option>
                                                                    <?php
                                                                    foreach ($groupings as $value) {
                                                                    ?>
                                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                </select>

                                                            </div>
                                                            <div class="col-sm-6">


                                                                <?php $today = date("Y-m-d H:i:s"); ?>
                                                                <label class='control-label'>Current Date and Time</label>
                                                                <input type="hidden" name="date_time" class=" input input-rounded input-sm date_time form-control" id="date_time" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly="" />
                                                                <input type='text' name='datime' readonly='' required="" id='datime' value='<?php echo date("d-m-Y H:i:s"); ?>' class=' input input-rounded input-sm  form-control'>
                                                                <label class='control-label'>Broadcast Date : </label>
                                                                <input type='text' name='broadcast_date' required="" id='appointment_date' class=' input input-rounded input-sm appointment_date form-control' />
                                                                <label class='control-label'>Select target group : </label>
                                                                <select name='target_group' required="" class=' input input-rounded input-sm form-control target_group' id='target_group'>
                                                                    .<option value=''>Please select: </option>
                                                                    <option value='1'>All Clients</option>
                                                                    <option value='2'>All Future Appointments</option>
                                                                    <option value='3'>All Missed Appointments</option>
                                                                    <option value='4'>All Defaulted Appointments</option>
                                                                    <option value='5'>All LTFU Appointments</option>
                                                                </select>
                                                                <hr>
                                                                <div class="form-inline future_appointment_date_div" style="display: none;">


                                                                    <label class="control-label"> Please select Appointment Date Range : </label>
                                                                    <input type="text" class=" input input-rounded input-sm form-control appointment_date appointment_from" id="appointment_from" name="appointment_from" placeholder="Appointment From" />
                                                                    -
                                                                    <input type="text" class=" input input-rounded input-sm form-control appointment_date appointment_to" id="appointment_to" name="appointment_to" placeholder="Appointment To " />
                                                                </div>

                                                                <label class='control-label'>Broadcast Time</label>
                                                                <select name='default_time' required="" class=' input input-rounded input-sm form-control' id='default_time'>
                                                                    <option value="">Please select : </option>
                                                                    <?php foreach ($times as $value) {
                                                                    ?>
                                                                        <option value='<?php echo $value->id; ?>'> <?php echo $value->name; ?> </option>
                                                                    <?php }
                                                                    ?>


                                                                </select>
                                                                <div>
                                                                    <label class="control-label">Target Clients</label>
                                                                    <span class="span_no_clients" id="span_no_clients"></span></div>
                                                                <label class='control-label'>Broadcast SMS :</label>
                                                                <textarea required="" name='defaultsms' id='defaultsms' onkeyup="countChar(this)" class=' input input-rounded input-sm form-control'> </textarea> <br />


                                                                <br>
                                                                <button type="submit" class="submit_add_div btn btn-success btn-small" id="submit_add_div">Set SMS</button>
                                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>


                                                            </div>




                                                        </div>








                                                    </div>




                                                </form>
                                            </div>




                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
<footer class="footer"> © 2018 Ushauri - All rights reserved. Powered by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
<!-- End footer -->
</div>
<!-- End Page wrapper  -->




<!--END MAIN WRAPPER -->




<script type="text/javascript">
    function countChar(val) {
        var len = val.value.length;
        //        if (len >= 160) {
        //            val.value = val.value.substring(0, 160);
        //            //sweetAlert("Oops...", "You've exceeded the  number of characters you are required to type...", "warning");
        //        } else {
        //            $('#charNum').text(160 - len);
        //        }
    };

    $(document).ready(function() {

        count_clients();
        count_users();



        $('.filter_user_access_level').on('change', function() {
            // Does some stuff and logs the event to the console
            //            $(".filter_user_county").hide();
            //            $(".filter_sub_county").hide();
            //            $(".filter_user_county_wait").show();
            var access_level = this.value;

            //if access level = admin , ignore the county , sub county and facility level
            if (access_level === '0') {
                $(".filter_county_div").hide();
                $(".filter_sub_county_div").hide();
                $(".filter_facility_div").hide();
            } else if (access_level === '1') {
                $(".filter_county_div").hide();
                $(".filter_sub_county_div").hide();
                $(".filter_facility_div").hide();

            } else if (access_level === '2') {
                $(".filter_county_div").hide();
                $(".filter_sub_county_div").hide();
                $(".filter_facility_div").hide();

            } else if (access_level === '3') {
                $(".filter_county_div").hide();
                $(".filter_sub_county_div").hide();
                $(".filter_facility_div").hide();

            } else if (access_level === '4') {
                $(".filter_county_div").hide();
                $(".filter_sub_county_div").hide();
                $(".filter_facility_div").hide();

            } else if (access_level === '5') {
                $(".filter_county_div").show();
                $(".filter_sub_county_div").show();

            } else if (access_level === '6') {
                $(".filter_county_div").show();
                $(".filter_sub_county_div").show();
            }
        });
        $('.filter_user_county').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_user_sub_county").hide();
            $(".filter_user_sub_county_wait").show();
            var county_id = this.value;

            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_sub_county/" + county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    count_clients();
                    count_users();
                    $(".filter_user_sub_county").empty();
                    var select = "<option value=''> Please Select : </option>";
                    $(".filter_user_sub_county").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_user_sub_county_wait").hide();
                        $(".filter_user_sub_county").show();
                        var sub_county_options = "<option value=" + value.sub_county_id + ">" + value.sub_county_name + "</option>";
                        $(".filter_user_sub_county").append(sub_county_options);


                    });

                },
                error: function(jqXHR) {

                }
            })
        });


        $('.filter_user_sub_county').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_user_facility").hide();
            $(".filter_user_facility_wait").show();
            var sub_county_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_facilities/" + sub_county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    count_clients();
                    count_users();
                    $(".filter_user_facility").empty();
                    var select = "<option value=''>Please Select : </option>";
                    $(".filter_user_facility").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_user_facility_wait").hide();
                        $(".filter_user_facility").show();
                        var facility_options = "<option value=" + value.mfl_code + ">" + value.facility_name + "</option>";
                        $(".filter_user_facility").append(facility_options);


                    });

                },
                error: function(jqXHR) {

                }
            })
        });


        $('.filter_user_access_level').on('change', function() {

            count_users();
        });
        $('.filter_user_county').on('change', function() {

            count_users();
        });
        $('.filter_user_sub_county').on('change', function() {

            count_users();
        });
        $('.filter_user_facility').on('change', function() {

            count_users();
        });






        $('.filter_facility').on('change', function() {
            count_clients();
            count_users();
        });
        $('.appointment_from').on('click', function() {
            count_clients();

        });
        $('.appointment_to').on('click', function() {
            count_clients();

        });
        $('#target_group').on('change', function() {
            count_clients();
            count_users();
            var selected = this.value;
            if (selected == '2') {
                $(".future_appointment_date_div").show();
            }

        });
        $('.group').on('change', function() {
            count_clients();
            count_users();
        });







        $('.filter_county').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_sub_county").hide();
            $(".filter_sub_county_wait").show();
            var county_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_sub_county/" + county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    count_clients();
                    count_users();
                    $(".filter_sub_county").empty();
                    var select = "<option value=''> Please Select : </option>";
                    $(".filter_sub_county").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_sub_county_wait").hide();
                        $(".filter_sub_county").show();
                        var sub_county_options = "<option value=" + value.sub_county_id + ">" + value.sub_county_name + "</option>";
                        $(".filter_sub_county").append(sub_county_options);


                    });

                },
                error: function(jqXHR) {

                }
            })
        });


        $('.filter_sub_county').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_facility").hide();
            $(".filter_facility_wait").show();
            var sub_county_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_facilities/" + sub_county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    count_clients();
                    count_users();
                    $(".filter_facility").empty();
                    var select = "<option value=''>Please Select : </option>";
                    $(".filter_facility").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_facility_wait").hide();
                        $(".filter_facility").show();
                        var facility_options = "<option value=" + value.mfl_code + ">" + value.facility_name + "</option>";
                        $(".filter_facility").append(facility_options);


                    });

                },
                error: function(jqXHR) {

                }
            })
        });


        $('.filter_facility').on('change', function() {
            count_clients();
            count_users();
        });
        $('#target_group').on('change', function() {
            count_clients();
            count_users();
        });
        $('.group').on('change', function() {
            count_clients();
            count_users();
        });





        $(".cancel_add_client").click(function() {

            $(".clinic_number").val('');
            $(".fname").val('');
            $(".lname").val('');
            $(".mname").val('');
            $(".phone_no").val('');
            $(".dob").val('');
            $(".frequency").val('');
            $(".mobile").val('');
            $(".altmobile").val('');
            $(".sharename").val('');
            $(".frequency").val('');

            $(".appointment_date").val('');
            $(".p_apptype3").val('');
            $(".p_custommsg").val('');
        });


        $(".submit_add_div").click(function() {

            var controller = "home";
            var submit_function = "set_broadcast_sms";
            var form_class = "add_form";
            var success_alert = "Broadcast set successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
            $(".clinic_number").empty();
            $(".fname").empty();
            $(".lname").empty();
            $(".mname").empty();
            $(".phone_no").empty();
            $(".dob").empty();
            $(".frequency").empty();
            $(".mobile").empty();
            $(".altmobile").empty();
            $(".sharename").empty();
            $(".frequency").empty();

            $(".appointment_date").empty();
            $(".p_apptype3").empty();
            $(".p_custommsg").empty();

        });
        $(".submit_user_broadcast").click(function() {


            var controller = "home";
            var submit_function = "set_user_broadcast_sms";
            var form_class = "broadcast_user_form";
            var success_alert = "Broadcast set successfully ... :) ";
            var error_alert = "  An error occured:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);


        });
        // var btn = document.getElementById('for_users')
        // btn.addEventListener('click', nextItem)

        // function nextItem() {
        //     console.log('clicked')
        //     let new_name = $('#broadcast_name').val();
        //     let new_level = $('#filter_user_access_level').val();
        //     let new_county = $('#filter_user_county').val();
        //     let new_sub = $('#filter_user_sub_county').val();
        //     let new_facility = $('#filter_user_facility').val();
        //     let new_date = $('#user_broadcast_date').val();
        //     let new_time = $('#user_broadcast_time').val();
        //     let new_sms = $('#user_broadcast_sms').val();

        // }

        $('input').on('keyup', function() {

            count_clients();
            count_users();

        });


        function count_users() {
            var county = $(".user_county").val();
            var sub_county = $(".user_sub_county").val();
            var facility = $(".user_facility").val();
            var access_level = $(".filter_user_access_level").val();
            var tokenizer = $(".tokenizer").val();
            $(".span_no_users").empty();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>home/count_target_users/",
                dataType: 'JSON',
                data: {
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    access_level: access_level,
                    tokenizer: tokenizer
                },
                success: function(data) {
                    $(".span_no_users").empty();
                    console.log("Total User : " + data[0].no_users);
                    $(".span_no_users").append(data[0].no_users);
                    //$( ".span_no_users" ).html(data[0].no_clients);
                },
                error: function(errorThrown) {

                }
            });
        }

        function count_clients() {
            var county = $(".filter_county").val();
            var sub_county = $(".filter_sub_county").val();
            var facility = $(".filter_facility").val();
            var group = $("#group").val();
            var target_group = $("#target_group").val();
            var tokenizer = $(".tokenizer").val();
            var appointment_from = $(".appointment_from").val();
            var appointment_to = $(".appointment_to").val();
            $(".span_no_clients").empty();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>home/count_target_clients/",
                dataType: 'JSON',
                data: {
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    group: group,
                    target_group: target_group,
                    tokenizer: tokenizer,
                    appointment_to: appointment_to,
                    appointment_from: appointment_from
                },
                success: function(data) {
                    $(".span_no_clients").empty();
                    console.log("No of Client => " + data[0].no_clients);
                    $(".span_no_clients").append(data[0].no_clients);
                    //$( ".span_no_clients" ).html(data[0].no_clients);
                },
                error: function(errorThrown) {

                }
            });
        }

    });
</script>

























<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->









<!--END MAIN WRAPPER -->