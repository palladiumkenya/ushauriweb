<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
           <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Activated Facilities</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->


        <div class="table_div" id="table_div">


            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Active Facilities</h4>
                            <h6 class="card-subtitle">A list of all activated facilities in the  system </h6>
                            <div class="table-responsive m-t-40">
                                <input type="hidden" name="report_name" class="report_name input-rounded input-sm form-control " id="report_name" value="Active Facilities Export Report"/>

                                <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Facility Name</th>
                                            <th>Level</th>
                                            <th>Code</th>
                                            <th>Owner</th>
                                            <th>Facility Type</th>
                                            <th>County </th>
                                            <th>Sub County </th>
                                            <th>Date Added </th>
                                            <th>Approve</th>
                                            <th>Edit</th>
                                            <th>Delete</th>

                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No.</th>
                                            <th>Facility Name</th>
                                            <th>Level</th>
                                            <th>Code</th>
                                            <th>Owner</th>
                                            <th>Facility Type</th>
                                            <th>County </th>
                                            <th>Sub County </th>
                                            <th>Date Added </th>
                                            <th>Approve</th>
                                            <?php
                                            $access_level = $this->session->userdata('access_level');
                                            if ($access_level == "Facility") {
                                                ?>
                                                <?php
                                            } else {
                                                ?>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                                <?php
                                            }
                                            ?>

                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $i = 1;

                                        foreach ($facilities as $value) {
                                            ?>
                                            <tr>
                                                <td class="a-center"><?php echo $i; ?></td>
                                                <td><?php echo $value->name; ?></td>
                                                <td><?php echo $value->keph_level; ?></td>
                                                <td><?php echo $value->code; ?></td>
                                                <td><?php echo $value->owner; ?></td>
                                                <td><?php echo $value->facility_type; ?></td>   
                                                <td><?php echo $value->county; ?></td>
                                                <td><?php echo $value->sub_county; ?></td>
                                                <td><?php echo $value->created_at; ?></td>
                                                <?php
                                                $is_approved = $value->is_approved;
                                                if ($is_approved == "Yes") {

                                                    $access_level = $this->session->userdata('access_level');
                                                    if ($access_level == "Facility") {
                                                        ?>
                                                        <td><?php echo $value->is_approved; ?></td>

                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td><?php echo $value->is_approved; ?></td>
                                                        <td>
                                                            <input type="hidden" name="id" value="<?php echo $value->partner_facility_id; ?>" class="id"/>
                                                            <button class="btn btn-sm btn-primary edit_btn" id="edit_btn"><i class="fa fa-edit"></i> </button></td>
                                                        <td>
                                                            <input type="hidden" name="id" value="<?php echo $value->partner_facility_id; ?>" class="id"/>
                                                            <button class="btn btn-sm btn-primary delete_btn" id="delete_btn"><i class="fa fa-trash"></i> </button></td>

                                                        <?php
                                                    }
                                                } else {


                                                    $access_level = $this->session->userdata('access_level');
                                                    if ($access_level == "Facility" or $access_level == "Donor") {
                                                        ?>
                                                        <td><?php echo $value->is_approved; ?></td>

                                                        <?php
                                                    } elseif ($access_level == "Admin" or $access_level == "Partner" or $access_level == "County" or $access_level == "Sub County") {
                                                        ?>
                                                        <td>
                                                            <input type="hidden" name="facility_name" value="<?php echo $value->name; ?>" class="form-control facility_name " id="broadcast_name"/>
                                                            <input type="hidden" name="mfl_code" value="<?php echo $value->code; ?>" class="mfl_code"/>
                                                            <button class="btn btn-primary approve_btn" id="approve_btn">Approve </button>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="id" value="<?php echo $value->partner_facility_id; ?>" class="id"/>
                                                            <button class="btn btn-sm btn-primary edit_btn" id="edit_btn"><i class="fa fa-edit"></i> </button></td>
                                                        <td>
                                                            <input type="hidden" name="id" value="<?php echo $value->partner_facility_id; ?>" class="id"/>
                                                            <button class="btn btn-sm btn-primary delete_btn" id="delete_btn"><i class="fa fa-trash"></i> </button></td>

                                                        <?php
                                                    }
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
                        </div>
                    </div>
                </div>
            </div>
            <!-- End PAge Content -->



        </div>







        <div class=" edit_div" id="edit_div" style="display: none">


            <div class="row">

                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Edit Partner Facility</h4>
                        </div>
                        <div class="card-body">



                            <form class="form edit_form" id="edit_form">


                                <div class="row">



                                    <?php
                                    $csrf = array(
                                        'name' => $this->security->get_csrf_token_name(),
                                        'hash' => $this->security->get_csrf_hash()
                                    );
                                    ?>

                                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                    <div class="col-sm-6 ">





                                        <div class="form-group">
                                            <label>Facility Name : </label> 
                                            <input type="text"  required="" name="facility_name" class="input-rounded input-sm form-control facility_name" id="edit_facility_name"/>

                                        </div> 


                                        <div class="form-group">
                                            <label>MFL Code : </label> 
                                            <input type="text"   name="mfl_code" class="input-rounded input-sm form-control mfl_code" id="edit_mfl_code"/>

                                        </div> 




                                        <div class="form-group">
                                            <label> No of Clients on ART : </label> 
                                            <input type="number"  required="" name="avg_clients" id="edit_avg_clients" class="input-rounded input-sm form-control edit_avg_clients"  placeholder="No of Clients "  id="edit_avg_clients"/>

                                        </div>



                                        <div class="form-group">
                                            <label>Partner Name : </label> 
                                            <select name="partner_name" class="input-rounded input-sm form-control edit_partner_name" id="edit_partner_name" >
                                                <option value="">Please select</option>
                                                <?php foreach ($partners as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>



                                        <input type="hidden" name="partner_facility_id" class="form-control partner_facility_id" id="edit_partner_facility_id" />




                                        <button type="submit" class="submit_edit_div btn-sm btn btn-success btn-small" id="submit_edit_div">Update Facility</button>
                                        <button type="button" class="close_edit_div btn-sm btn btn-danger btn-small" id="close_edit_div">Cancel</button>




                                    </div>




                                </div>




                            </form>






                        </div>
                    </div></div>

            </div>


        </div>



        <div class="row delete_div" id="delete_div" style="display: none">
            <div class="col-lg-6">
                <div class="card card-outline-primary">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Delete Partner</h4>
                    </div>
                    <div class="card-body">



                        <form class="form delete_form" id="delete_form">
                            <?php
                            $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                            );
                            ?>

                            <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                            <p><span class="delete_description"></span></p>


                            <input type="hidden" name="partner_facility_id" class="form-control partner_facility_id" id="delete_partner_facility_id" />




                            <button type="submit" class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete Facility</button>
                            <button type="button" class="close_delete_div btn btn-danger btn-small" id="close_delete_div">Cancel</button>
                        </form>




                    </div>
                </div></div></div>














        <!-- END COMMENT AND NOTIFICATION  SECTION -->

    </div>



    <script src="<?php echo base_url(); ?>/assets/js/lib/jquery/jquery.min.js"></script>



    <script type="text/javascript">
        $(document).ready(function () {





            $(document).on('click', ".edit_btn", function () {
                $(".loader").show();

                //get data
                var data_id = $(this).closest('tr').find('input[name="id"]').val();
                var error_alert = "An Error Ocurred";



                $.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>admin/get_facility_data/" + data_id,
                    dataType: "JSON",
                    success: function (response) {
                        $(".loader").hide();
                        $.each(response, function (i, value) {
                            $("#edit_facility_name").empty();



                            $("#edit_mfl_code").empty();

                            $("#edit_avg_clients").empty();


                            $("#edit_partner_facility_id").empty();

                            $("#edit_phone_no").empty();

                            $("#edit_e_mail").empty();


                            $('#edit_partner_facility_id').val(value.partner_facility_id);
                            $('#edit_partner_name option[value=' + value.status + ']').attr("selected", "selected");

                            $('#edit_avg_clients').val(value.avg_clients);
                            $('#edit_mfl_code').val(value.mfl_code);
                            $('#edit_facility_name').val(value.facility_name);
                            $('#edit_partner_name').val(value.partner_id);







                        });


                        $(".edit_div").show();
                        $(".table_div").hide();














                    }, error: function (data) {
                        $(".loader").hide();
                        sweetAlert("Oops...", "" + error_alert + "", "error");

                    }

                });



            });



            $(document).on('click', ".delete_btn", function () {

                $(".loader").show();
                //get data
                var data_id = $(this).closest('tr').find('input[name="id"]').val();
                var error_alert = "An Error Ocurred";


                $.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>admin/get_facility_data/" + data_id,
                    dataType: "JSON",
                    success: function (response) {
                        $(".loader").hide();
                        $.each(response, function (i, value) {
                            $("#delete_user_id").empty();

                            $('#delete_partner_facility_id').val(value.partner_facility_id);
                            $('#delete_f_name').val(value.facility_name);
                            $('#delete_m_name').val(value.mfl_code);
                            $('#delete_l_name').val(value.l_name);

                            var delete_descripton = "Do you want to delete Facility  : " + value.facility_name + " :  MFL Code " + value.mfl_code + " ";
                            $(".delete_description").append(delete_descripton);
                        });


                        $(".delete_div").show();
                        $(".table_div").hide();


                    }, error: function (data) {
                        $(".loader").hide();
                        sweetAlert("Oops...", "" + error_alert + "", "error");

                    }

                });




            })


            $(".close_delete_div").click(function () {
                $(".delete_div").hide();
                $(".table_div").show();
            });


            $(".close_edit_div").click(function () {
                $(".edit_div").hide();
                $(".table_div").show();
            });




            $(document).on('click', ".approve_btn", function () {
                $('.loader').show();
                //get data
                var facility_id = $(this).closest('tr').find('input[name="mfl_code"]').val();
                var facility_name = $(this).closest('tr').find('input[name="facility_name"]').val();
                swal({
                    title: "Aprove Facility ?",
                    text: "Facility Details : " + facility_name + "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, Approve it!",
                    cancelButtonText: "No!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                        function (isconfirm) {
                            var tokenizer = $(".tokenizer").val();



                            if (isconfirm) {
                                $.ajax({
                                    url: "<?php echo base_url(); ?>admin/approve_facility/" + facility_id + "",
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {tokenizer: tokenizer},
                                    success: function (data) {
                                        console.log(data[0].response);
                                        if (data[0].response === true) {

                                            swal({
                                                title: "Approved!",
                                                text: "Facility Addition Approved succesfully ",
                                                type: "success",
                                                confirmButtonText: "Okay!",
                                                closeOnConfirm: true
                                            }, function () {
                                                window.location.reload(1);
                                            });
                                        }

                                    }, error: function (error) {
                                        if (error === false) {
                                            swal("Failed!", "Facility Addition was not approved ", "error");
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
                                                url: "<?php echo base_url(); ?>admin/disapprove_facility/" + facility_id + "",
                                                type: 'POST',
                                                dataType: 'JSON',
                                                data: {reason: inputValue, tokenizer: tokenizer},
                                                success: function (data) {
                                                    console.log(data[0].response);
                                                    if (data[0].response === true) {

                                                        swal({
                                                            title: "Dis Approved!",
                                                            text: "Facilty Addition Dis-Approved succesfully ",
                                                            type: "success",
                                                            confirmButtonText: "Okay!",
                                                            closeOnConfirm: true
                                                        }, function () {
                                                            window.location.reload(1);
                                                        });
                                                    }

                                                }, error: function (error) {
                                                    if (error === false) {
                                                        swal("Failed!", "Facility addition was not approved succesfully", "error");
                                                    }
                                                }
                                            });
                                            //swal("Nice!", "You wrote: " + inputValue, "success");
                                        });
                            }



                        });
            });






            $(".submit_edit_div").click(function () {
                var controller = "admin";
                var submit_function = "edit_partner_facility";
                var form_class = "edit_form";
                var success_alert = "Facility  updated successfully ... :) ";
                var error_alert = "An Error Ocurred";
                submit_data(controller, submit_function, form_class, success_alert, error_alert);
            });




            $(".submit_delete_div").click(function () {
                var controller = "admin";
                var submit_function = "delete_partner_facility";
                var form_class = "delete_form";
                var success_alert = "Facility deleted successfully ... :) ";
                var error_alert = "An Error Ocurred";
                submit_data(controller, submit_function, form_class, success_alert, error_alert);
            });








        });
    </script>


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
