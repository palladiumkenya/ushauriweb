


<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Audit Trail</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline-primary">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Search Facility</h4>
                    </div>
                    <div class="card-body">
                        <div class="facility_search_div" id="facility_search_div">
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <!--   --> <h3 class="box-title">Facility Search</h3>
                                    </div><!-- /.box-header -->
                                    <!-- Donation search Form  -->

                                    <div class="box-body">

                                        <div class="checkbox">
                                            <label>Search By: Phone No
                                                <input type="radio" class="flat-red search_option" value="Phone No " name="audit_trail_search_option" checked="checked">
                                            </label>


                                        </div>

                                        <div class="search_input_div" id="search_input_div" style="display: inline;">
                                            <div class="form-group">
                                                <label for="GG"></label>
                                                <input type="text" class="form-control audit_trail_search_value" id="audit_trail_search_value" name="audit_trail_search_value"  placeholder="Enter Phone Number ...">
                                            </div>
                                            <button class="btn btn-small audit_trail_serach_btn "><i class="icon-search"></i>Search</button>
                                        </div>


                                        <div class="audit_trail_search_results_div" id="audit_trail_search_results_div" style="display: inline;">

                                        </div>


                                    </div><!-- /.box-body -->


                                </div><!-- /.box -->



                                <!-- Form Element sizes -->


                            </div><!--/.col (left) -->
                        </div>





                    </div>

                </div>


            </div>


        </div>
        <!-- Row -->



        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->


















<!--END MAIN WRAPPER -->