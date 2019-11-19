<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                System Module management
            </div>   
            <div >


                <div class="panel-body"> 

                    <button class="add_btn btn btn-primary btn-small" id="add_btn">Add Module</button>
                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Module Name</th>
                                    <th>Controller</th>
                                    <th>Function</th>
                                    <th>Level</th>
                                    <th>Span Class</th>
                                    <th>Icon Class</th>
                                    <th>Status</th>
                                    <th>Date Added</th>
                                    <th>Time Stamp</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($modules as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->module; ?></td>
                                        <td><?php echo $value->function; ?></td>
                                        <td><?php echo $value->controller; ?></td>
                                        <td><?php echo $value->level; ?></td>
                                        <td><?php echo $value->span_class; ?></td>
                                        <td><?php echo $value->icon_class; ?></td>
                                        <td><?php
                                            $status = $value->status;
                                            if ($status == "Active") {
                                                ?>
                                                <label style="color: green"><?php echo $status; ?></label>
                                                <?php
                                            } else if ($status == "Disabled") {
                                                ?>
                                                <label style="color: red"><?php echo $status; ?></label>

                                                <?php
                                            }
                                            ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>
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
                            <h2 id="actionLabel">Add Module</h2>






                            <form class="form add_form" id="add_form">


   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                <div class="form-group">
                                    <label>Module Name : </label> 
                                    <input type="text"  required="" name="name" id="name" placeholder="Module Name " class="form-control name"/>

                                </div> 
                                <div class="form-group">
                                    <label>Controller : </label> 
                                    <input type="text"  required="" name="controller" placeholder="Controller Name" id="controller" class="form-control controller"/>

                                </div> 

                                <div class="form-group">
                                    <label>Function : </label> 
                                    <input type="text" name="function" class="form-control function" id="function" placeholder="Function Name"/>
                                </div> 


                                <div class="form-group">
                                    <label>Description : </label> 

                                    <textarea class="form-control description" id="description" name="description" placeholder="Enter your Description here..."></textarea>
                                </div> 


                                <div class="form-group">
                                    <label>Span Class value : </label> 

                                    <textarea class="form-control span" id="span" name="span" placeholder="Enter your Span here..."></textarea>
                                </div> 


                                <div class="form-group">
                                    <label>Icon Class Value : </label> 

                                    <textarea class="form-control icon" id="icon" name="icon" placeholder="Enter your Icon here..."></textarea>
                                </div> 



                                <div class="form-group">
                                    <label>Level : </label> <span class="info">Level 1 : This module link will appear at the  top bar of the  system. Level 2 : This module link will appear at the left bar of the system </span>
                                    <select name="level" class="form-control level" id="level" >
                                        <option value="">Please select</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                    </select>
                                </div>




                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control status" id="status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>






                                <button class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add Module</button>
                                <button class="close_add_div btn btn-danger btn-small" id="close_add_div">Cancel</button>
                            </form>





                        </div>




                    </div>






                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit Module</h2>






                            <form class="form edit_form" id="edit_form">


   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <div class="form-group">
                                    <label>Module Name : </label> 
                                    <input type="text"  required="" name="name" id="edit_name" class="form-control name"/>

                                </div> 
                                <div class="form-group">
                                    <label>Controller : </label> 
                                    <input type="text"  required="" name="controller" id="edit_controller" class="form-control controller"/>

                                </div> 

                                <div class="form-group">
                                    <label>Function : </label> 
                                    <input type="text" name="function" class="form-control function" id="edit_function" placeholder="Function Name"/>
                                </div> 


                                <div class="form-group">
                                    <label>Description : </label> 

                                    <textarea class="form-control description" id="edit_description" name="description" placeholder="Enter your Description here..."></textarea>
                                </div> 


                                <div class="form-group">
                                    <label>Span Class value : </label> 

                                    <textarea class="form-control span" id="edit_span" name="span" placeholder="Enter your Span here..."></textarea>
                                </div> 


                                <div class="form-group">
                                    <label>Icon Class Value : </label> 
                                    <span>Kindly reference the  following link to get the  right type of coding you would like : <a href="http://getbootstrap.com/components/"></a></span>
                                    <textarea class="form-control icon" id="edit_icon" name="icon" placeholder="Enter your Icon here..."></textarea>
                                </div> 


                                <div class="form-group">
                                    <label>Level : </label> <span class="info">Level 1 : This module link will appear at the  top bar of the  system. Level 2 : This module link will appear at the left bar of the system </span>

                                    <select name="level" class="form-control level" id="edit_level" >
                                        <option value="">Please select</option>
                                           <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                    </select>
                                </div>




                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control status" id="edit_status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>



                                <input type="hidden" name="module_id" class="form-control module_id" id="edit_module_id" />




                                <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update Module</button>
                                <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>
                            </form>





                        </div>




                    </div>





                    <div class="delete_div" style="display: none;">











                        <div class="panel-body  formData" id="deleteForm">
                            <h2 id="actionLabel">Delete Module</h2>






                            <form class="form delete_form" id="delete_form">

   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                
                                
                                <p><span class="delete_description"></span></p>


                                <input type="hidden" name="module_id" class="form-control module_id" id="delete_module_id" />




                                <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete Module</button>
                                <button class="close_delete_div btn btn-danger btn-small" id="close_delete_div">Cancel</button>
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
        $(document).on('click', ".add_btn", function () {
            $(".name").empty();

            $(".name").empty();
            $(".controller").empty();
            $(".function").empty();
            $(".e_mail").empty();




            $(".add_div").show();
            $(".table_div").hide();


        });


        $(document).on('click', ".edit_btn", function () {
            $('.loader').show();

            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var controller = "admin";
            var get_function = "get_module_data";
            var success_alert = "Module added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_module_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {

                        $("#edit_name").empty();
                        $("#edit_module_id").empty();
                        $("#edit_controller").empty();
                        $("#edit_function").empty();
                        $("#edit_description").empty();
                        $("#edit_span").empty();
                        $("#edit_icon").empty();



                        $("#edit_name").val(value.module);
                        $('#edit_module_id').val(value.id);
                        $('#edit_controller').val(value.controller);
                        $('#edit_function').val(value.function);
                        $('#edit_description').val(value.description);
                        $("#edit_icon").val(value.icon_class);
                        $("#edit_span").val(value.span_class);
                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");
                        $('#edit_level option[value=' + value.level + ']').attr("selected", "selected");

                    });


                    $(".edit_div").show();
                    $(".table_div").hide();


                }, error: function (data) {
                    $('.loader').hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });



        $(document).on('click', ".delete_btn", function () {
            $('.loader').show();

            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var error_alert = "Ooops , Something really bad has happened (:";


            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_module_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {


                        $('#delete_module_id').val(value.id);

                        var delete_descripton = "Do you want to delete Module :  " + value.module + "";
                        $(".delete_description").append(delete_descripton);
                    });


                    $(".delete_div").show();
                    $(".table_div").hide();


                }, error: function (data) {
                    $('.loader').hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });



        $(".close_add_div").click(function () {
            $(".add_div").hide();
            $(".table_div").show();
            $(".f_name").empty();

            $(".l_name").empty();

            $(".phone_no").empty();
            $(".e_mail").empty();

            $(".m_name").empty();
            $(".dob").empty();

        });





        $(".submit_add_div").click(function () {

            var controller = "admin";
            var submit_function = "add_module";
            var form_class = "add_form";
            var success_alert = "Module added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);


            $(".add_div").show();
            $(".table_div").hide();
        });



        $(".submit_edit_div").click(function () {
            var controller = "admin";
            var submit_function = "edit_module";
            var form_class = "edit_form";
            var success_alert = "Module data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".edit_div").show();
            $(".table_div").hide();
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_module";
            var form_class = "delete_form";
            var success_alert = "Module data delete successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".delete_div").show();
            $(".table_div").hide();
        });






    });
</script>




<!--END MAIN WRAPPER -->