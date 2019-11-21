<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Broadcast Report showing the  target group in the  system and the date the  message is supposed to go out. 
            </div>   
            <div >


                <div class="panel-body"> 


                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Broadcast Name </th>
                                    <th>No clients</th>
                                    <th>Message </th>
                                    <th>Date Created</th>
                                    <th>Broadcast Date</th>
                                    <th>Target Users</th>
                                    <?php
                                    $access_level = $this->session->userdata('access_level');
                                    if ($access_level == "Facility") {
                                        ?>
                                        <th>Approved</th>
                                        <?php
                                    } elseif ($access_level == "Admin" or $access_level == 'Partner') {
                                        ?>
                                        <th>Approved</th>
                                        <?php
                                    }
                                    ?>


                                    <?php
                                    if ($access_level == 'Facility') {
                                        ?>
                                        <th>Edit</th>
                                        <th>Delete</th>
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
                                foreach ($broadcasts as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->no_clients; ?></td>
                                        <td><?php echo $value->msg; ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->broadcast_date; ?></td>
                                        <td><?php echo $value->recepient_type; ?></td>
                                        <?php
                                        $access_level = $this->session->userdata('access_level');
                                        if ($access_level == "Facility") {
                                            $is_approved = $value->is_approved;
                                            if ($is_approved == "Yes") {
                                                ?>
                                                <td>
                                                    <?php
                                                    echo $is_approved;
                                                    ?>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td>
                                                    <?php
                                                    echo $is_approved;
                                                    ?>
                                                </td>
                                                <?php
                                            }
                                        } elseif ($access_level == "Admin" or $access_level == 'Partner') {
                                            $is_approved = $value->is_approved;
                                            if ($is_approved == "Yes") {
                                                ?>
                                                <td>
                                                    <?php
                                                    echo $is_approved;
                                                    ?>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td>
                                                    <input type="hidden" name="broadcast_name" value="<?php echo $value->name; ?>" class="form-control broadcsst_name " id="broadcast_name"/>
                                                    <input type="hidden" name="broadcast_id" value="<?php echo $value->id; ?>" class="id"/>
                                                    <button class="btn btn-primary approve_btn" id="approve_btn">Approve </button>
                                                </td>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($access_level == 'Facility') {
                                            ?>
                                            <td>
                                                <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                                <button class="btn btn-primary edit_btn" id="edit_btn">Edit </button></td>
                                            <td>
                                                <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                                <button class="btn btn-primary delete_btn" id="delete_btn">Delete </button></td>
                                            <?php
                                        } else {
                                            ?>

                                            <?php
                                        }
                                        ?>


                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>




                    <div class="edit_broadcast" id="edit_broadcast" style="display: none;">





                        <form class="update_form" id="update_form">

                            <?php
                            $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                            );
                            ?>

                            <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                            <span colspan='2' align='left'> <h2 style='color:Black;'>Clients Broadcast Service </h2> </span> 
                            <div class='row'>
                                <div class='col-xs-10 form-goup'>
                                    <section class='panel'>
                                        <header class='panel-heading'>Clients Broadcast Service</header>
                                        <div class='panel-body'>
                                            <div class='form'>
                                                <label class='control-label'>Broad cast Name </label>
                                                <input type="hidden" name="broadcast_id" class="broadcast_id form-control" id="broadcast_id"  />
                                                <input type="text" name="broadcast_name" required="" class="form-control broadcst_name" id="broadcast_name" placeholder="Broad cast Name "/>


                                                <br>

                                                <?php
                                                $access_level = $this->session->userdata('access_level');
                                                if ($access_level == "Facility") {
                                                    ?>

                                                    <?php
                                                } else {
                                                    ?>
                                                    <label class='control-label'>County</label>

                                                    <select name='county' class='form-control county filter_county' id='county'>
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
                                                    <label class='control-label'>Sub County</label>


                                                    <span class="filter_sub_county_wait" style="display: none;">Loading Sub Counties, Please Wait ...</span>

                                                    <select class="form-control filter_sub_county" name="sub_county" id="filter_sub_county">
                                                        <option value="">Please Select : </option>
                                                    </select>
                                                    <br>
                                                    <label class="control-label">Facilities</label>
                                                    <span class="filter_facility_wait" style="display: none;">Loading Facilities , Please Wait ...</span>
                                                    <select class="form-control filter_facility" name="facility" id="filter_facility">
                                                        <option value="">Please select : </option>
                                                    </select>

                                                    <?php
                                                }
                                                ?>
                                                <br/>

                                                <label class='control-label'>Search Group</label>



                                                <select name='group' class='form-control group' required="" id='group'>
                                                    <option value="">Please select : </option>
                                                    <option value="0">All</option>
                                                    <?php
                                                    foreach ($groupings as $value) {
                                                        ?>
                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php $today = date("Y-m-d H:i:s"); ?>
                                                <label class='control-label'>Current Date and Time</label>
                                                <input type="hidden" name="date_time" class="date_time form-control" id="date_time" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly=""/>
                                                <input type='text' name='datime' readonly='' required="" id='datime' value='<?php echo date("d-m-Y H:i:s"); ?>' class='form-control'>
                                                <label class='control-label'>Broadcast Date : </label>
                                                <input type='text' name='broadcast_date' required="" id='broadcast_date'  class='broadcast_date form-control' />
                                                <label class='control-label'>Select target group : </label>
                                                <select name='target_group' required="" class='form-control target_group' id='target_group'>
                                                    .<option value=''>Please select: </option>
                                                    <option value='1'>All Clients</option>
                                                    <option value='2'>All active Appointments</option>"
                                                </select>
                                                <label class='control-label'>Broadcast Time</label>
                                                <select name='default_time' required=""class='form-control default_time' id='default_time'>
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
                                                <div>
                                                    <label class='control-label'>Broadcast SMS :</label>
                                                    <textarea required="" name='defaultsms' id='defaultsms' onkeyup="countChar(this)" class='form-control broadcast_message' > </textarea> 		   <br/>

                                                </div>

                                                <div>
                                                    <label class="control-label">
                                                        Status
                                                    </label>
                                                    <select name="status" class="form-control broadcast_status" id="broadcast_status">
                                                        <option value=""> Please Select : </option>
                                                        <option value="Active">Active</option>
                                                        <option value="Disabled">Disabled</option>
                                                    </select>
                                                </div>

                                                <br>
                                                <button type="submit" class="submit_add_div btn btn-success btn-small" id="submit_add_div">Set SMS</button>
                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>


                                            </div></div></section></div></div>






                        </form>





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

    function countChar(val) {
        var len = val.value.length;
        if (len >= 160) {
            val.value = val.value.substring(0, 160);
            sweetAlert("Oops...", "You've exceeded the  number of characters you are required to type...", "warning");
        } else {
            $('#charNum').text(160 - len);
        }
    }
    ;

    $(document).ready(function () {

        count_clients();


        $('.filter_county').on('change', function () {
            // Does some stuff and logs the event to the console
            $(".filter_sub_county").hide();
            $(".filter_sub_county_wait").show();
            var county_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_sub_county/" + county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    count_clients();
                    $(".filter_sub_county").empty();
                    var select = "<option value=''> Please Select : </option>";
                    $(".filter_sub_county").append(select);
                    $.each(data, function (i, value) {
                        $(".filter_sub_county_wait").hide();
                        $(".filter_sub_county").show();
                        var sub_county_options = "<option value=" + value.sub_county_id + ">" + value.sub_county_name + "</option>";
                        $(".filter_sub_county").append(sub_county_options);


                    });

                }, error: function (jqXHR) {

                }
            })
        });


        $('.filter_sub_county').on('change', function () {
            // Does some stuff and logs the event to the console
            $(".filter_facility").hide();
            $(".filter_facility_wait").show();
            var sub_county_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_facilities/" + sub_county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    count_clients();
                    $(".filter_facility").empty();
                    var select = "<option value=''>Please Select : </option>";
                    $(".filter_facility").append(select);
                    $.each(data, function (i, value) {
                        $(".filter_facility_wait").hide();
                        $(".filter_facility").show();
                        var facility_options = "<option value=" + value.mfl_code + ">" + value.facility_name + "</option>";
                        $(".filter_facility").append(facility_options);


                    });

                }, error: function (jqXHR) {

                }
            })
        });


        $('.filter_facility').on('change', function () {
            count_clients();
        });
        $('#target_group').on('change', function () {
            count_clients();
        });
        $('.group').on('change', function () {
            count_clients();
        });





        $(".cancel_add_client").click(function () {

            $('.edit_broadcast').hide();
            $('.table_div').show();
        });


        $(".submit_add_div").click(function () {

            var controller = "home";
            var submit_function = "update_broadcast_sms";
            var form_class = "update_form";
            var success_alert = "Broadcast set successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
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

        $('input').on('keyup', function () {

            count_clients();
        });


        function count_clients() {
            var county = $(".filter_county").val();
            var sub_county = $(".filter_sub_county").val();
            var facility = $(".filter_facility").val();
            var group = $("#group").val();
            var target_group = $("#target_group").val();
            $(".span_no_clients").empty();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>home/count_target_clients/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, group: group, target_group: target_group},
                success: function (data) {
                    $(".span_no_clients").empty();
                    console.log(data[0].no_clients);
                    $(".span_no_clients").append(data[0].no_clients);
                    //$( ".span_no_clients" ).html(data[0].no_clients);
                }, error: function (errorThrown) {

                }
            });
        }




        $(document).on('click', ".edit_btn", function () {
            $('.loader').show();
            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var controller = "home";
            var get_function = "get_broadcast_data";
            var error_alert = "Ooops ,An error ocurred (:";

            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    count_clients();
                    $('.loader').hide();
                    $.each(response, function (i, value) {
                        $("#broadcast_id").empty();

                        $("#broadcast_name").empty();

                        $(".broadcast_message").empty();

                        $("#broadcast_id").val(value.id);
                        $('#broadcast_name').val(value.name);
                        $('.broadcast_message').val(value.msg);
                        $('.broadcast_status').val(value.status);
                        $('.broadcast_date').val(value.broadcast_date);
                        $(".filter_county").val(value.county_id);
                        $(".filter_sub_county").val(value.sub_county_id);
                        $(".filter_facility").val(value.mfl_code);
                        $(".default_time").val(value.time_id);
                        $(".group").val(value.group_id);
                        $(".target_group").val(value.target_group);

                        $(".edit_broadcast").show();
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




    });
</script>





<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', ".approve_btn", function () {
            $('.loader').show();
            //get data
            var broadcast_id = $(this).closest('tr').find('input[name="broadcast_id"]').val();
            var broadcast_name = $(this).closest('tr').find('input[name="broadcast_name"]').val();
            swal({
                title: "Aprove Broadcast ?",
                text: "Broadcast Details : " + broadcast_name + "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Approve it!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
                    function (isconfirm) {

                        if (isconfirm) {
                            $.ajax({
                                url: "<?php echo base_url(); ?>home/approve_broadcast/" + broadcast_id + "",
                                type: 'POST',
                                dataType: 'JSON',
                                success: function (data) {
                                    console.log(data[0].response);
                                    if (data[0].response === true) {

                                        swal({
                                            title: "Approved!",
                                            text: "Broadcast Approved succesfully ",
                                            type: "success",
                                            confirmButtonText: "Okay!",
                                            closeOnConfirm: true
                                        }, function () {
                                            window.location.reload(1);
                                        });
                                    }

                                }, error: function (error) {
                                    if (error === false) {
                                        swal("Failed!", "Broadcast was not approved succesfully", "error");
                                    }
                                }
                            });
                        } else {
                            swal({
                                title: "Reason!",
                                text: "Please provide reason for cancelling :",
                                type: "input",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                animation: "slide-from-top",
                                inputPlaceholder: "Cancelation Reason"
                            },
                                    function (inputValue) {
                                        if (inputValue === false)
                                            return false;
                                        if (inputValue === "") {
                                            swal.showInputError("You need to write something!");
                                            return false
                                        }


                                        $.ajax({
                                            url: "<?php echo base_url(); ?>home/disapprove_broadcast/" + broadcast_id + "",
                                            type: 'POST',
                                            dataType: 'JSON',
                                            data: {reason: inputValue},
                                            success: function (data) {
                                                console.log(data[0].response);
                                                if (data[0].response === true) {

                                                    swal({
                                                        title: "Dis Approved!",
                                                        text: "Broadcast Dis-Approved succesfully ",
                                                        type: "success",
                                                        confirmButtonText: "Okay!",
                                                        closeOnConfirm: true
                                                    }, function () {
                                                        window.location.reload(1);
                                                    });
                                                }

                                            }, error: function (error) {
                                                if (error === false) {
                                                    swal("Failed!", "Broadcast was not approved succesfully", "error");
                                                }
                                            }
                                        });
                                        //swal("Nice!", "You wrote: " + inputValue, "success");
                                    });
                        }



                    });
        });

    });
</script>



<!--END MAIN WRAPPER -->