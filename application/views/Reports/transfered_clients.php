<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
             <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Trnasfers</a></li>
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
<!--                        <h4 class="card-title">My Facilities List</h4>-->
                        <h6 class="card-subtitle">A list of all  transferrd in clients </h6>
                        <!--<div class="table-responsive m-t-40">-->
                            
                            
                              <input type="hidden" name="report_name" class="report_name input-rounded input-sm form-control " id="report_name" value="Transfered Clients Export Report"/>

                            
                            
                                <?php
                        $access_level = $this->session->userdata('access_level');
                        if ($access_level == "Partner") {
                            ?>
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Serial no </th>
                                        <th>Client Name</th>
                                        <th>DoB</th>
                                        <th>Phone No</th>
                                        <th>Type</th>
                                        <th>Condition</th>
                                        <th>Previous Clinic</th>
                                        <th>New Clinic</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($clients as $value) {
                                        ?>
                                        <tr>
                                            <td class="a-center"><?php echo $i; ?></td>
                                                <td>
                                                    <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                        <?php echo $value->clinic_number; ?>
                                                    </button>

                                                </td>
                                                <td><?php echo $value->file_no; ?></td>
                                                <td><?php
                                                    $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                    echo $client_name;
                                                    ?></td>
                                                <td><?php echo $value->dob; ?></td>
                                                <td><?php echo $value->phone_no; ?></td>
                                            
                                            
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->old_mfl_code . " " . $value->prev_clinic; ?></td>
                                            <td><?php echo $value->code . " " . $value->new_clinic; ?></td>


                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        } elseif ($access_level == "Facility") {
                            ?>
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Serial no</th>
                                        <th>Client Name</th>
                                        <th>DoB</th>
                                        <th>Phone No</th>
                                        <th>Type</th>
                                        <th>Condition</th>
                                        <th>Previous Clinic</th>
                                        <th>New Clinic</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($clients as $value) {
                                        ?>
                                        <tr>
                                            <td class="a-center"><?php echo $i; ?></td>

                                            <?php
                                            $view_client = $this->session->userdata('view_client');

                                            if ($view_client == "Yes") {
                                                ?>
                                                <td>
                                                    <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                        <?php echo $value->clinic_number; ?>
                                                    </button>

                                                </td>
                                                <td><?php echo $value->file_no; ?></td>
                                                <td><?php
                                                    $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                    echo $client_name;
                                                    ?></td>
                                                <td><?php echo $value->dob; ?></td>
                                                <td><?php echo $value->phone_no; ?></td>
                                                <?php
                                            } else {
                                                ?>

                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <?php
                                            }
                                            ?>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->old_mfl_code . " " . $value->prev_clinic; ?></td>
                                            <td><?php echo $value->code . " " . $value->new_clinic; ?></td>


                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        } else {
                            ?>

                             <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                               <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Serial no </th>
                                        <th>Client Name</th>
                                        <th>DoB</th>
                                        <th>Phone No</th>
                                        <th>Type</th>
                                        <th>Condition</th>
                                        <th>Previous Clinic</th>
                                        <th>New Clinic</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($clients as $value) {
                                        ?>
                                        <tr>
                                            <td class="a-center"><?php echo $i; ?></td>

                                            <?php
                                            $view_client = $this->session->userdata('view_client');

                                            if ($view_client == "Yes") {
                                                ?>
                                                <td>
                                                    <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                        <?php echo $value->clinic_number; ?>
                                                    </button>

                                                </td>
                                                <td><?php echo $value->file_no; ?></td>
                                                <td><?php
                                                    $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                    echo $client_name;
                                                    ?></td>
                                                <td><?php echo $value->dob; ?></td>
                                                <td><?php echo $value->phone_no; ?></td>
                                                <?php
                                            } else {
                                                ?>

                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <?php
                                            }
                                            ?>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->old_mfl_code . " " . $value->prev_clinic; ?></td>
                                            <td><?php echo $value->code . " " . $value->new_clinic; ?></td>


                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>


                            <?php
                        }
                        ?>

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PAge Content -->





















        <!-- END COMMENT AND NOTIFICATION  SECTION -->

    </div>



    <script src="<?php echo base_url(); ?>/assets/js/lib/jquery/jquery.min.js"></script>



    <script type="text/javascript">
        $(document).ready(function () {

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




















