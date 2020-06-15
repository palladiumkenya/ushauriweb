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
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Clients Extract</a></li>
            </ol>
        </div>
    </div>
    <style>
        #loading {
            display: none;
        }
    </style>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->

        <?php echo $access_level ?>
        <div class="row">
            <form class="form-inline">
                <input type='hidden' id="access_level" value="<?php echo $access_level; ?>" />
                <input type='hidden' id="partner_id" value="<?php echo $partner_id; ?>" />
                <input type='hidden' id="facility_id" value="<?php echo $facility_id; ?>" />
                <input type='hidden' id="sub_county_id" value="<?php echo $sub_county_id; ?>" />


                <?php if ($access_level == 'Admin' || $access_level == 'Donor') { ?>

                    <select class="form-control filter_partner  input-rounded input-sm select2" name="filter_partner" id="filter_partner">
                        <option value="">Please select Partner</option>
                        <?php
                        foreach ($filtered_partner as $value) {
                        ?>
                            <option value="<?php echo $value->partner_id; ?>"><?php echo $value->partner_name; ?></option>
                        <?php
                        }
                        ?>
                        <option></option>
                    </select>

                <?php } ?>

                <?php if ($access_level == 'Admin' || $access_level == 'Donor' || $access_level == 'Partner') { ?>


                    <select class="form-control filter_county  input-rounded input-sm select2" name="filter_county" id="filter_county">
                        <option value="">Please select County</option>
                        <?php
                        foreach ($filtered_county as $value) {
                        ?>
                            <option value="<?php echo $value->county_id; ?>"><?php echo $value->county_name; ?></option>
                        <?php
                        }
                        ?>
                        <option></option>
                    </select>


                    <span class="filter_sub_county_wait" style="display: none;">Loading , Please Wait ...</span>
                    <select class="form-control filter_sub_county input-rounded input-sm select2" name="filter_sub_county" id="filter_sub_county">
                        <option value="">Please Select Sub County : </option>
                        <?php
                        foreach ($filtered_sub_county as $value) {
                        ?>
                            <option value="<?php echo $value->sub_county_id; ?>"><?php echo $value->sub_county; ?></option>
                        <?php
                        }
                        ?>
                        <option></option>
                    </select>


                    <span class="filter_facility_wait" style="display: none;">Loading , Please Wait ...</span>

                    <select class="form-control filter_facility input-rounded input-sm select2" name="filter_facility" id="filter_facility">
                        <option value="">Please select Facility : </option>
                    </select>

                    <span class="filter_time_wait" style="display: none;">Loading , Please Wait ...</span>

                <?php } ?>

                <select class="form-control filter_time  input-rounded input-sm select2" name="filter_time" id="filter_time">
                    <option value="">Please select Month</option>
                    <?php
                    foreach ($filtered_time as $value) {
                    ?>
                        <option value="<?php echo $value->time; ?>"><?php echo $value->time; ?></option>
                    <?php
                    }
                    ?>
                </select>

                <button class="btn btn-default filter_monthly_appointment_extract btn-round  btn-small btn-primary  " type="button" name="filter_monthly_appointment_extract" id="filter_monthly_appointment_extract"> <i class="fa fa-filter"></i> Filter</button>


            </form>






        </div>
        <!-- <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <b><img src="<?php echo base_url(); ?>/assets/images/loading.gif" alt="" /></b>
                </div>

            </div>
        </div> -->
        <div class="row">
            <div id="loading">
                <b><img src="<?php echo base_url(); ?>/assets/images/loading.gif" alt="" /></b>
            </div>
        </div>






        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Monthly Appointment Report</h4>
                        <h6 class="card-subtitle">Monthly Appointment Summary Report</h6>
                        <div class="table-responsive m-t-40">


                            <div class="panel-body">
                                <div class="table_div">
                                    <div class="appointment_summary_reports_div"></div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PAge Content -->


        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 Ushauri - All rights reserved. Powered by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->