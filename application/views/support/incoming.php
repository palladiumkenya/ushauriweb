


<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
             <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Incoming </a></li>
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
                        <h4 class="m-b-0 text-white">Search Incoming</h4>
                    </div>
                    <div class="card-body">
                        <div class="facility_search_div" id="facility_search_div">
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <!--   --> <h3 class="box-title">Incoming Messages Search</h3>
                                    </div><!-- /.box-header -->
                                    <!-- Donation search Form  -->

                                    <div class="box-body">

                                        <div class="checkbox">
                                            <label>Search By: Phone No
                                                <input type="radio" class="flat-red search_option" value="Phone No " name="incoming_search_option" checked="checked">
                                            </label>


                                        </div>

                                        <div class="search_input_div" id="search_input_div" style="display: inline;">
                                            <div class="form-group">
                                                <label for="GG"></label>
                                                <input type="text" class="form-control incoming_search_value" id="incoming_search_value" name="incoming_search_value"  placeholder="Enter Phone Number ...">
                                            </div>
                                            <button class="btn btn-small incoming_serach_btn "><i class="icon-search"></i>Search</button>
                                        </div>


                                        <div class="incoming_search_results_div" id="incoming_search_results_div" style="display: inline;">

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














<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', ".add_facility", function () {

            $(".facility_name").empty();

            $(".code").empty();

            $(".facility_type").empty();

            $(".location").empty();

            //get data
            var facility_name = $(this).closest('tr').find('input[name="facility_name"]').val();

            var mfl_code = $(this).closest('tr').find('input[name="code"]').val();

            var facility_type = $(this).closest('tr').find('input[name="facility_type"]').val();

            var location = $(this).closest('tr').find('input[name="sub_county_name"]').val();

            var county_name = $(this).closest('tr').find('input[name="county_name"]').val();





            $(".facility_name").val(facility_name);

            $(".mfl_code").val(mfl_code);

            $(".facility_type").val(facility_type);

            $(".facility_location").val(location);
            $(".facility_county").val(county_name);


            $(".add_facilty_div").show();
            $(".search_results_div").hide();





        });





        $(".close_add_facility_btn").click(function () {



            $(".search_results_div").show();
            $(".facility_search_div").show();
            $(".add_facilty_div").hide();
            $(".facility_name").empty();

            $(".mfl_code").empty();

            $(".facility_type").empty();
            $(".facility_county").empty();

        });



        $(".submit_facility").click(function () {
            var controller = "admin";
            var submit_function = "assign_partner_facility";
            var form_class = "add_facility_form";
            var success_alert = "Facility added successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });






    });
</script>















<!--END MAIN WRAPPER -->