
<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Messages Extract</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->

        <?php echo $access_level ?>
        <div class="row">
            <form class="form-inline" >

                <input type='hidden' id="access_level" value="<?php echo $access_level; ?>" />
                <input type='hidden' id="partner_id" value="<?php echo $partner_id; ?>" />
                <input type='hidden' id="facility_id" value="<?php echo $facility_id; ?>" />

                <?php if($access_level == 'Admin' || $access_level == 'Donor') {?>

                <select class = "form-control filter_partner  input-rounded input-sm select2" name = "filter_partner" id = "filter_partner">
                    <option value = "">Please select Partner</option>
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


                <?php if($access_level == 'Admin' || $access_level == 'Donor' || $access_level == 'Partner') {?>
                    
                <select class = "form-control filter_county  input-rounded input-sm select2" name = "filter_county" id = "filter_county">
                    <option value = "">Please select County</option>
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
                </select>


                <span class="filter_facility_wait" style="display: none;">Loading , Please Wait ...</span>

                <select class="form-control filter_facility input-rounded input-sm select2" name="filter_facility" id="filter_facility">
                    <option value="">Please select Facility : </option>
                </select>

                <?php }?>


                <input type="text" name="date_from" id="date_from" class="form-controL date_from input-rounded input-sm " placeholder="Date From : "/>

                <input type="text" name="date_to" id="date_to" class="form-control date_to input-rounded input-sm " placeholder="Date To : "/>


                <button class="btn btn-default filter_message_extract btn-round  btn-small btn-primary  " type="button" name="filter_message_extract" id="filter_message_extract"> <i class="fa fa-filter"></i>  Filter</button>


            </form>



        </div>






        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Messages Extract  List</h4>
                        <h6 class="card-subtitle">Raw Data Extract of all Client Messages in the  system </h6>
                        <div class="table-responsive m-t-40">


                            <div class="panel-body"> 
                                <div class="table_div">
                                    <div class="message_reports_div"></div>
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
    <footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->

