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

                    <button class="add_btn btn btn-primary btn-small" id="add_btn">Add Target County</button>
                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Target County Name</th>
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
                                foreach ($target_county as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>
                                        <td><?php echo $value->status; ?></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                            <button class="btn btn-primary edit_btn" id="edit_btn">Edit </button></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
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
                            <h2 id="actionLabel">Add Target County</h2>






                            <form class="form add_form" id="add_form">
                                
                                 <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <div class="form-group">
                                    <label>Target County Name : </label> 
                                    <!--<input type="text"  required="" name="name" class="form-control name"/>-->
                                    <select class="form-control county_id" name="county_id" required="" >
                                        <option value="">Please select : </option>
                                        <?php
                                        foreach ($counties as $value) {
                                            ?>
                                            <option value="<?php echo $value->id; ?>" > <?php echo $value->name; ?> </option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                </div> 


                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select class="form-control " id="" name="status">
                                        <option value="">Please select : </option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>

                                </div> 






                                <button class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add Target County</button>
                                <button class="close_add_div btn btn-danger btn-small" id="close_add_div">Cancel</button>
                            </form>





                        </div>




                    </div>






                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit Target County</h2>






                            <form class="form edit_form" id="edit_form">

 <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                <div class="form-group">
                                    <label>Target County Name : </label> 
                                    <!--<input type="text"  required="" name="name" id="edit_name" class="form-control name"/>-->
                                    
                                    
                                     <select class="form-control edit_county_id" name="county_id" required="" >
                                        <option value="">Please select : </option>
                                        <?php
                                        foreach ($counties as $value) {
                                            ?>
                                            <option value="<?php echo $value->id; ?>" > <?php echo $value->name; ?> </option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                    
                                    
                                </div> 

                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control " id="edit_status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>



                                <input type="hidden" name="target_county_id" class="form-control target_county_id" id="edit_target_county_id" />




                                <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update Target County</button>
                                <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>
                            </form>





                        </div>




                    </div>





                    <div class="delete_div" style="display: none;">











                        <div class="panel-body  formData" id="deleteForm">
                            <h2 id="actionLabel">Delete Target County</h2>






                            <form class="form delete_form" id="delete_form">
 <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <p><span class="delete_description"></span></p>


                                <input type="hidden" name="target_county_id" class="form-control target_county_id" id="delete_target_county_id" />




                                <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete Target County</button>
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
            var get_function = "get_target_county_data";
            var success_alert = "Target County added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_target_county_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {

                        $("#edit_county_id").val(value.county_id);
                        $('#edit_target_county_id').val(value.id);
                        $('#edit_created_at').val(value.created_at);
                        $('#edit_target_countystamp').val(value.target_countystamp);
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
                url: "<?php echo base_url(); ?>admin/get_target_county_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {


                        $('#delete_target_county_id').val(value.id);

                        var delete_descripton = "Do you want to delete Target County :  " + value.target_county_name + "";
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
            var submit_function = "add_target_county";
            var form_class = "add_form";
            var success_alert = "Target County added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".add_div").hide();
            $(".table_div").show();
            $(".name").empty();

            $(".status").empty();
        });



        $(".submit_edit_div").click(function () {
            var controller = "admin";
            var submit_function = "edit_target_county";
            var form_class = "edit_form";
            var success_alert = "Target County data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".edit_div").hide();
            $(".table_div").show();
            $(".name").empty();

            $(".status").empty();
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_target_county";
            var form_class = "delete_form";
            var success_alert = "Target County data delete successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".delete_div").hide();
            $(".table_div").show();
            $(".name").empty();

            $(".status").empty();
        });






    });
</script>




<!--END MAIN WRAPPER -->