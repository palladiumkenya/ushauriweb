<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">Today's Appointments</a></li>
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
                        <h4 class="card-title">Today's Appointments List</h4>
                        <h6 class="card-subtitle"> List of Appointments due today  </h6>
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

                                                <?php
                                                $access_level = $this->session->userdata('access_level');
                                                if ($access_level == "Donor") {
                                                    ?>

                                                    <?php
                                                } else {
                                                    ?>
                                                    <th>Action</th>

                                                    <?php
                                                }
                                                ?>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($today_appointments as $value) {
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
                                                        <td><?php echo $value->file_no; ?></td>
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

                                                    <?php
                                                    $access_level = $this->session->userdata('access_level');
                                                    if ($access_level == "Donor") {
                                                        ?>

                                                        <?php
                                                    } else {
                                                        ?>


                                                        <td>  
                                                            <input type="hidden" id="client_id" name="hidden_appointment_id" class="hidden_appointment_id form-control" value="<?php echo $value->appointment_id; ?>"/>
                                                            <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                            <input type="hidden" id="hidden_clinic_number" name="hidden_clinic_number" class="hidden_clinic_number form-control" value="<?php echo $value->clinic_number; ?>"/>
                                                            <input type="hidden" id="app_type_1" name="app_type_1" class="app_type_1 form-control" value="<?php echo $value->appointment_type; ?>"/>
                                                            <!--<button class="btn btn-primary btn-small confirm_btn" id="confirm_btn">Confirm</button>-->

                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary dropdown-toggle fa-arrow-down" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Action<span class="icon-arrow-down"></span>
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                    <button class="btn btn-default  confirm_btn" id="confirm_btn"><span class="icon-save"></span>Confirm</button> <br>
                                                                    <button class="btn btn-default  call_btn" id="call_btn"> <span class="icon-phone"></span>Call</button> <br>
                                                                    <button class="btn btn-default  msg_btn" id="msg_btn"><span class="icon-envelope"></span>Message</button> <br>
                                                                    <button class="btn btn-default  home_visit_btn" id="home_visit_btn"><span class="icon-home"></span> Home Visit</button> <br>

                                                                </div>
                                                            </div>

                                                        </td>


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




                            </div>


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




<script type="text/javascript">
    $(document).ready(function () {

        pre_art_summary();
        function pre_art_summary() {
            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>reports/art_attended_summary",
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                    var male_attended = 0;
                    var female_attended = 0;
                    $.each(response, function (i, value) {
                        var app_status = value.app_status;

                        var male = value.Male1;
                        var female = value.Female1;


                        if (app_status == 'Booked' || app_status == 'Notified') {
                            male_attended += parseInt(male);
                            female_attended += parseInt(female);
                        }

                        console.log(",ale => " + male_attended);
                        console.log("Female  => " + female_attended);

                        $("#confirm_client_id").empty();

                        $("#confirm_status").empty();

                        $("#confirm_f_name").empty();

                        $("#confirm_m_name").empty();

                        $("#confirm_l_name").empty();


                        $("#confirm_mobile").empty();


                        $("#confirm_client_id").val(value.app_status);
                        $('#confirm_clinic_number').val(value.male1);
                        $('#confirm_mobile').val(value.Female1);


                    });





                }, error: function (data) {
                    sweetAlert("", " An error occured ...", "error");

                }

            });


        }




















    });
</script>




