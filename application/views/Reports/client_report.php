<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">Client Report</a></li>
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
                        <h4 class="card-title">Summary Report</h4>
                        <!-- <h6 class="card-subtitle"> List of Client Groupings </h6> -->
                        <div class="table-responsive m-t-40">




                                <div class="table_div">

  <input type="hidden" name="report_name" class="report_name input-rounded input-sm form-control " id="report_name" value="Clients Export Report"/>


                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                  <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>County</th>
                                    <th>Sub County</th>
                                    <th>MFL No</th>
                                    <th>Facility</th>
                                    <th>No of Clients</th>
                                    <th>No of Appointments</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($appointments as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>


                                        <td><?php echo $value->county; ?></td>
                                        <td><?php echo $value->sub_county; ?></td>
                                        <td><?php echo $value->mfl_code; ?></td>
                                        <td><?php echo $value->facility_name; ?></td>
                                        <td><?php echo $value->no_clients; ?></td>
                                        <td><?php echo $value->past_appointments+$value->today_appointments; ?></td>


                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                      
                                    </table>


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



