


<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>/admin/facilities">Facilities</a></li>
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
                                    </div><!-- /.box-header -->
                                    <!-- Donation search Form  -->



                                    <div class="row">
                                        <div class="col-sm-4 ">

                                            <div class="checkbox">
                                                <label>Search By: MFL Code / Facility Name 
                                                    </label>
                                               

                                            </div>

                                            <div class="search_input_div col-md-7" id="search_input_div" >
                                                <div class="form-group">
                                                    <label for="GG"></label>
                                                    <input type="text" class="input input-rounded input-sm  form-control search_value" id="search_value" name="search_value"  placeholder="Enter MFL Code or Facility Name ...">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-8 ">
                                            <div class="search_results_div" id="search_results_div" style="display: none;">

                                            </div>

                                        </div>

                                    </div>











                                    <!-- The Modal -->
                                    <div class="modal" id="AddFacilityModal">






                                        <div class="modal-dialog modal-lg ">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add Facility</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <form class="form add_facility_form" id="add_facility_form">



                                                        <div class="row">
                                                            <div class="col-sm-6">

                                                                <?php
                                                                $csrf = array(
                                                                    'name' => $this->security->get_csrf_token_name(),
                                                                    'hash' => $this->security->get_csrf_hash()
                                                                );
                                                                ?>

                                                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                                                <div class="form-group">
                                                                    <label>Facility Name : <span class="text-danger">*</span> </label>
                                                                    <input type="text" readonly="" required="" name="f_name" class=" input input-rounded input-sm form-control facility_name"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>M.F.L Code : <span class="text-danger">*</span> </label> 
                                                                    <input type="text" readonly=""  required=""name="mfl_code" class=" input input-rounded input-sm form-control mfl_code"/>

                                                                </div>                
                                                                <div class="form-group">
                                                                    <label>Facility Type : <span class="text-danger">*</span> </label>
                                                                    <input type="text" readonly="" required=""  name="facility_type" class=" input input-rounded input-sm form-control facility_type"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Owner : <span class="text-danger">*</span> </label>

                                                                    <input type="text" readonly="" required="" name="f_county" class=" input input-rounded input-sm form-control facility_county"/>


                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Level : <span class="text-danger">*</span> </label>
                                                                    <input type="text" readonly="" required="" name="f_location" class=" input input-rounded input-sm form-control facility_location "/>
                                                                </div> 
                                                            </div>
                                                            <div class="col-sm-6">

                                                                <div class="form-group">
                                                                    <label>Partner Name : <span class="text-danger">*</span> </label>
                                                                    <select class=" input input-rounded input-sm form-control partner_name" required="" id="partner_name" name="partner_name">
                                                                        <option value="">Please select :</option>                                       
                                                                        <?php foreach ($partners as $value) {
                                                                            ?>
                                                                            <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                                                        <?php }
                                                                        ?>
                                                                    </select>                
                                                                </div>  
                                                                <div class="form-group">
                                                                    <label>Contact name : <span class="text-danger">*</span> </label>
                                                                    <input type="text" name="f_contact" class="input input-rounded input-sm form-control" required="" />

                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Phone number: <span class="text-danger">*</span> </label>
                                                                    <input type="text" name="f_mobile" class=" input input-rounded input-sm form-control " required="" />

                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Average No of  Active Clients: <span class="text-danger">*</span> </label>
                                                                    <input type="text" name="avg_active_clients"  placeholder="Average No of Clients" required="" class=" input input-rounded input-sm form-control "/>

                                                                </div>


                                                                <button type="submit" class="submit_facility btn btn-success btn-small" id="submit_facility"><i class="fa fa-plus-circle"></i>Add Facility</button>

                                                            </div>
                                                        </div>




                                                    </form>

                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>










































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