<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Deactivated</a></li>
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
                        <h4 class="card-title">De activated clients</h4>
                        <h6 class="card-subtitle">All Clients who have been deactivated/disabled in the system </h6>
                        <div class="table-responsive m-t-40">



                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Serial no </th>
                                        <th>Client Name</th>
                                        <th>Phone No</th>
                                        <th>DoB</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Date Added</th>
                                        <?php
                                        $access_level = $this->session->userdata('access_level');
                                        if ($access_level === 'Facility') {
                                            ?>

                                            <?php
                                        } else {
                                            ?>
                                            <th>County</th>
                                            <th>Sub County</th>
                                            <th>Facility</th>

                                            <?php
                                        }
                                        ?>

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
                                                    <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                    <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                        <?php echo $value->clinic_number; ?>
                                                    </button>

                                                </td>
                                                <td><?php echo $value->file_no; ?></td>
                                                <td><?php
                                                    $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                    echo $client_name;
                                                    ?></td>
                                                <td><?php echo $value->phone_no; ?></td>
                                              
                                            
                                            <td><?php echo $value->dob; ?></td>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->status; ?></td>

                                            <td><?php echo $value->created_at; ?></td>
                                            <?php
                                            if ($access_level === 'Facility') {
                                                ?>

                                                <?php
                                            } else {
                                                ?>

                                                <td><?php echo $value->county_name; ?></td>
                                                <td><?php echo $value->sub_county; ?></td>
                                                <td><?php echo $value->facility_name; ?></td>

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
