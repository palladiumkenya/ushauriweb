<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">Clients with no Appointments</a></li>
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
                        <h4 class="card-title">Client Grouping List</h4>
                        <h6 class="card-subtitle"> List of Client Groupings </h6>
                        <div class="table-responsive m-t-40">




                            <div class="table_div">

  <input type="hidden" name="report_name" class="report_name input-rounded input-sm form-control " id="report_name" value="Clients with no Appointments Export Report"/>


                                <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>UPN</th>
                                            <th>Serial no </th>
                                            <th>Client Name</th>
                                            <th>Phone No</th>
                                            <th>Status</th>
                                            <th>Enrollment Date</th>
                                            <th>ART Date</th>

                                            <?php
                                            if ($access_level === 'Facilty') {
                                                ?>
                                                <th>Date Added</th>
                                                <th>Time Stamp</th>
                                                <?php
                                            } else {
                                                ?>
                                                <th>County</th>
                                                <th>Sub County</th>
                                                <th>Facility </th>
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
                                                <td>
                                                    <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                        <?php echo $value->clinic_number; ?>
                                                    </button>

                                                </td>
                                                <td><?php echo $value->file_no; ?></td>
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
                                                <td><?php echo $value->status; ?></td>
                                                <td><?php echo $value->enrollment_date; ?></td>
                                                <td><?php echo $value->art_date; ?></td>


                                                <?php
                                                if ($access_level === 'Facilty') {
                                                    ?>
                                                    <td><?php echo $value->created_at; ?></td>
                                                    <td><?php echo $value->updated_at; ?></td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td><?php echo $value->county_name; ?></td>
                                                    <td><?php echo $value->sub_county_name; ?></td>
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


                            <div class="edit_div" style="display: none;">

                                <button class="back_button btn btn-primary btn-small"><span class="icon-backward"></span>Back</button>



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

























