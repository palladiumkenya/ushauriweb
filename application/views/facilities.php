<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Assign Facility to a partner 
            </div>   
            <div >


                <div class="panel-body"> 










                    <div class="table_div" id="table_div">

                        <!-- Content Wrapper. Contains page content -->
                        <div class="content-wrapper">
                            <!-- Content Header (Page header) -->
                            <section class="content-header">
                                <h1>

                                    <small></small>
                                </h1>
                                <ol class="breadcrumb">
                                    <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/facilities">Add Facilities</a></li>
                                </ol>
                            </section>

                            <!-- Main content -->
                            <section class="content">
                                <div class="row">
                                    <div class="donation_search_div" id="donation_search_div">
                                        <div class="col-md-12">
                                            <!-- general form elements -->
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <!--   --> <h3 class="box-title">Facility Search</h3>
                                                </div><!-- /.box-header -->
                                                <!-- Donation search Form  -->
                                                <form role="form" id="facility_search_form" class="facility_search_form"> </form>
                                                <div class="box-body">

                                                    <div class="checkbox">
                                                        <label>Search By: MFL Code
                                                            <input type="radio" class="flat-red search_option" value="MFL Code" name="search_option" checked="checked">
                                                        </label>
                                                        <label>Facility Name
                                                            <input type="radio" class="flat-red search_option" value="Facility Name" name="search_option">
                                                        </label>

                                                    </div>

                                                    <div class="search_input_div" id="search_input_div" style="display: inline;">
                                                        <div class="form-group">
                                                            <label for="GG"></label>
                                                            <input type="text" class="form-control search_value" id="search_value" name="search_value"  placeholder="Enter Value ...">
                                                        </div>
                                                    </div>


                                                    <div class="search_results_div" id="search_results_div" style="display: none;">

                                                    </div>


                                                </div><!-- /.box-body -->

                                                <div class="box-footer">
                                                </div>




                                            </div><!-- /.box -->



                                            <!-- Form Element sizes -->


                                        </div><!--/.col (left) -->
                                    </div>





                                </div><!--/.col (right) -->       

                            </section>
                        </div><!-- /.box -->
                        <!-- general form elements disabled -->

                    </div>














































































                    <div class="add_facilty_div" style="display: none;">











                        <div class="panel-body  formData" id="addForm">
                            <h2 id="actionLabel">Add Facility</h2>






                            <form class="form add_facility_form" id="add_facility_form">
                                
                                   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                
                                
                                <div class="form-group">
                                    <label>Facility Name : </label>
                                    <input type="text" readonly="" required="" name="f_name" class="form-control facility_name"/>
                                </div>
                                <div class="form-group">
                                    <label>M.F.L Code : </label> 
                                    <input type="text" readonly=""  required=""name="mfl_code" class="form-control mfl_code"/>

                                </div>                
                                <div class="form-group">
                                    <label>Facility Type : </label>
                                    <input type="text" readonly="" required=""  name="facility_type" class="form-control facility_type"/>
                                </div>
                                <div class="form-group">
                                    <label>Owner : </label>

                                    <input type="text" readonly="" required="" name="f_county" class="form-control facility_county"/>


                                </div>

                                <div class="form-group">
                                    <label>Level : </label>
                                    <input type="text" readonly="" required="" name="f_location" class="form-control facility_location "/>
                                </div>              





                                <div class="form-group">
                                    <label>Partner Name : </label>
                                    <select class="form-control partner_name" required="" id="partner_name" name="partner_name">
                                        <option value="">Please select :</option>                                       
                                        <?php foreach ($partners as $value) {
                                            ?>
                                            <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                        <?php }
                                        ?>
                                    </select>                
                                </div>  
                                <div class="form-group">
                                    <label>Contact name : </label>
                                    <input type="text" name="f_contact" class="form-control"/>

                                </div>
                                <div class="form-group">
                                    <label>Phone number: </label>
                                    <input type="text" name="f_mobile" class="form-control "/>

                                </div>


                                <button class="submit_facility btn btn-success btn-small" id="submit_facility">Add Facility</button>
                                <button class="close_add_facility_btn btn btn-danger btn-small" id="close_add_facility_btn">Cancel</button>
                            </form>





                        </div>




                    </div>










                </div>
            </div>                <div class="panel-footer">
                Get   in touch: support.tech@mhealthkenya.org                             </div>

        </div>        












    </div>



</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>








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
            $(".table_div").hide();

            //dTbles('#myModal2').modal('show');
            //jQuery("#addFacilityModal").modal("show");





        });





        $(".close_add_facility_btn").click(function () {
            $(".add_facilty_div").hide();
            $(".table_div").show();
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
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });






    });
</script>















<!--END MAIN WRAPPER -->