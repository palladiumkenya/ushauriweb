<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
            </div>   
            <div >


                <div class="panel-body"> 

                    <button class="add_btn btn btn-primary btn-small" id="add_btn">Add Target Facilities</button>
                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Target Facilities Name</th>
                                    <th>Date Added</th>
                                    <th>Time Stamp</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($target_facility as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>
                                        <td><?php echo $value->status; ?></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->mfl_code; ?>" class="id"/>
                                            <button class="btn btn-primary edit_btn" id="edit_btn">Edit </button></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->mfl_code; ?>" class="id"/>
                                            <button class="btn btn-primary delete_btn" id="delete_btn">Delete </button></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>









                    <div class="add_div" style="display: none;">



                        <div class="panel-body  formData" id="addForm">
                            <h2 id="actionLabel">Add Target Facilities</h2>






                            <form class="form add_form" id="add_form">
 <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                <div class="form-group">
                                    <label>Target Facilities Name : </label> 
                                    <input type="text"  required="" name="mfl_code" class="form-control mfl_code" id="mfl_code"/>
                                    <span class="facility_name" id="facility_name"></span>
                                </div> 


                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select class="form-control " id="" name="status">
                                        <option value="">Please select : </option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>

                                </div> 






                                <button class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add Target Facilities</button>
                                <button class="close_add_div btn btn-danger btn-small" id="close_add_div">Cancel</button>
                            </form>





                        </div>




                    </div>






                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit Target Facilities</h2>






                            <form class="form edit_form" id="edit_form">

 <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                <div class="form-group">
                                    <label>Target Facilities Name : </label> 
                                    <input type="text"  required="" name="mfl_code" class="form-control edit_mfl_code" id="edit_mfl_code"/>
                                    <span class="edit_facility_name" id="edit_facility_name"></span>
                                </div>  

                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control " id="edit_status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>



                                <input type="hidden" name="target_facility_id" class="form-control target_facility_id" id="edit_facility_id" />




                                <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update Target Facilities</button>
                                <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>
                            </form>





                        </div>




                    </div>





                    <div class="delete_div" style="display: none;">











                        <div class="panel-body  formData" id="deleteForm">
                            <h2 id="actionLabel">Delete Target Facilities</h2>






                            <form class="form delete_form" id="delete_form">
 <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <p><span class="delete_description"></span></p>


                                <input type="hidden" name="target_facility_id" class="form-control target_facility_id" id="delete_target_facility_id" />




                                <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete Target Facilities</button>
                                <button class="close_delete_div btn btn-danger btn-small" id="close_delete_div">Cancel</button>
                            </form>





                        </div>




                    </div>









                </div>
            </div>                

        </div>        












    </div>



</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>








<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', ".add_btn", function () {

            $(".add_div").show();
            $(".table_div").hide();


        });


        $(document).on('click', ".edit_btn", function () {

            $(".loader").show();
            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var controller = "admin";
            var get_function = "get_target_facility_data";
            var success_alert = "Target Facilities added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_target_facility_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {

                        $("#edit_facility_id").val(value.id);
                        $('#edit_mfl_code').val(value.mfl_code);
                        $('#edit_created_at').val(value.created_at);
                        $('#edit_target_facilitystamp').val(value.target_facilitystamp);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");

                    });


                    $(".edit_div").show();
                    $(".table_div").hide();


                }, error: function (data) {
                    $(".loader").hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });



        $(document).on('click', ".delete_btn", function () {

            $(".loader").show();
            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var error_alert = "Ooops , Something really bad has happened (:";


            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_target_facility_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {


                        $('#delete_target_facility_id').val(value.id);

                        var delete_descripton = "Do you want to delete Target Facilities :  " + value.target_facility_name + "";
                        $(".delete_description").append(delete_descripton);
                    });


                    $(".delete_div").show();
                    $(".table_div").hide();


                }, error: function (data) {
                    $(".loader").hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });



        $(".close_add_div").click(function () {
            $(".add_div").hide();
            $(".table_div").show();
            $(".name").empty();

            $(".status").empty();



        });





        $(".submit_add_div").click(function () {
            var controller = "admin";
            var submit_function = "add_target_facilities";
            var form_class = "add_form";
            var success_alert = "Target Facilities added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".add_div").hide();
            $(".table_div").show();
            $(".name").empty();

            $(".status").empty();
        });



        $(".submit_edit_div").click(function () {
            var controller = "admin";
            var submit_function = "edit_target_facility";
            var form_class = "edit_form";
            var success_alert = "Target Facilities data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".edit_div").hide();
            $(".table_div").show();
            $(".name").empty();

            $(".status").empty();
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_target_facility";
            var form_class = "delete_form";
            var success_alert = "Target Facilities data delete successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".delete_div").hide();
            $(".table_div").show();
            $(".name").empty();

            $(".status").empty();
        });




        $(".mfl_code").keyup(function () {
            var mfl_code = $(".mfl_code").val();
            if (mfl_code.length !== 5) {

            } else {

                var controller = "home";
                var get_function = "get_transfer_mfl_no";
                $(".facility_loading").show();
                $(".facility_name").empty();
                $.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + mfl_code,
                    dataType: "JSON",
                    success: function (response) {
                        $(".facility_loading").hide();
                        $('.loader').hide();
                        var isempty = jQuery.isEmptyObject(response);
                        if (isempty) {
                            $(".facility_name").empty();
                        } else {

                            $.each(response, function (i, value) {


                                var facility_name = "You selected : " + value.name;
                                $(".facility_name").append(facility_name);
                                $(".load_client_details").show();
                            });
                        }





                    }, error: function (data) {
                        $('.loader').hide();
                        sweetAlert("Oops...", "" + error_alert + "", "error");
                    }

                });
            }





        });

        $(".edit_mfl_code").keyup(function () {
            var edit_mfl_code = $(".edit_mfl_code").val();
            if (edit_mfl_code.length !== 5) {

            } else {

                var controller = "home";
                var get_function = "get_transfer_mfl_no";
                $(".facility_loading").show();
                $(".edit_facility_name").empty();
                $.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + edit_mfl_code,
                    dataType: "JSON",
                    success: function (response) {
                        $(".facility_loading").hide();
                        $('.loader').hide();
                        var isempty = jQuery.isEmptyObject(response);
                        if (isempty) {
                            $(".edit_facility_name").empty();
                        } else {

                            $.each(response, function (i, value) {


                                var facility_name = "You selected : " + value.name;
                                $(".edit_facility_name").append(facility_name);
                                $(".edit_load_client_details").show();
                            });
                        }





                    }, error: function (data) {
                        $('.loader').hide();
                        sweetAlert("Oops...", "" + error_alert + "", "error");
                    }

                });
            }





        });




    });
</script>




<!--END MAIN WRAPPER -->