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
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Calendar</a></li>
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
                        <h6 class="card-subtitle">A list of Appointments in the system </h6>
                        <div class="table-responsive m-t-40">


                            <div class="panel-body">


                                <div class="table_div">



                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>UPN</th>
                                                <th>File No </th>
                                                <th>Client Name</th>
                                                <th>Phone No</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Type</th>
                                                <th>Clinic</th>
                                                <th>Action</th>
                                                <?php
                                                $access_level = $this->session->userdata('access_level');
                                                if ($access_level == "Facility") {
                                                ?>
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
                                                            <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>" />
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
                                                        <td>XXXXXX XXXXXX</td>
                                                        <td>XXXXXX XXXXXXX</td>
                                                        <td>XXXXXX XXXXXXX</td>
                                                    <?php
                                                    }
                                                    ?>
                                                    <td><?php echo $value->appntmnt_date; ?></td>
                                                    <td><?php echo $value->appointment_types; ?></td>
                                                    <td><?php echo $value->clinic; ?></td>
                                                    <?php
                                                    $access_level = $this->session->userdata('access_level');
                                                    if ($access_level == "Facility") {
                                                    ?>

                                                        <td>
                                                            <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>" />
                                                            <input type="hidden" id="app_type_1" name="app_type_1" class="app_type_1 form-control" value="<?php echo $value->app_type_1; ?>" />
                                                            <!-- <button class="btn btn-primary btn-small confirm_btn" id="confirm_btn">Confirm</button></td> -->
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
<footer class="footer"> © 2018 Ushauri - All rights reserved. Powered by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
<!-- End footer -->
</div>
<!-- End Page wrapper  -->





<!--END BLOCK SECTION -->




<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->

</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>







<!--END MAIN WRAPPER -->