
<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Message type customization 
            </div>   
            <div >


                <div class="panel-body"> 

                    <button class="add_btn btn btn-primary btn-small" id="add_btn">Add Message Type</button>
                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Message Type Name</th>
                                    <th>Description</th>
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
                                foreach ($message_types as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->description; ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>
                                        <td><?php
                                            $status = $value->status;
                                            if ($status === "Active") {
                                                ?>
                                                <span style="color: green;"><?php echo $status; ?></span>
                                                <?php
                                            } else if ($status === "Disabled") {
                                                ?>
                                                <span style="color: red;"><?php echo $status; ?></span>
                                                <?php
                                            }
                                            ?></td>
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
                            <h2 id="actionLabel">Add Message Type</h2>






                            <form class="form add_form" id="add_form">

                                
                                   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                
                                <div class="form-group">
                                    <label>Message Type Name : </label> 
                                    <input type="text"  required="" name="name" class="form-control name"/>

                                </div> 


                                <div class="form-group">
                                    <label>Description : </label> 
                                    <textarea name="description" class="form-control description-text description" id="description"></textarea>

                                </div> 



                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control status" id="status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>


                                <button class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add Message Type</button>
                                <button class="close_add_div btn btn-danger btn-small" id="close_add_div">Cancel</button>
                            </form>





                        </div>




                    </div>






                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit Message Type</h2>






                            <form class="form edit_form" id="edit_form">


   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <div class="form-group">
                                    <label>Message Type Name : </label> 
                                    <input type="text"  required="" name="name" id="edit_name" class="form-control name"/>

                                </div> 


                                <div class="form-group">
                                    <label>Description : </label> 
                                    <textarea name="description"  class="form-control description-text description" id="edit_description"></textarea>

                                </div> 




                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control status" id="edit_status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>


                                <input type="hidden" name="message_type_id" class="form-control message_type_id" id="edit_message_type_id" />




                                <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update Message Type</button>
                                <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>
                            </form>





                        </div>




                    </div>





                    <div class="delete_div" style="display: none;">











                        <div class="panel-body  formData" id="deleteForm">
                            <h2 id="actionLabel">Delete Message Type</h2>






                            <form class="form delete_form" id="delete_form">


                                   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                
                                
                                <p><span class="delete_description"></span></p>


                                <input type="hidden" name="message_type_id" class="form-control message_type_id" id="delete_message_type_id" />




                                <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete Message Type</button>
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
            $(".name").empty();

            $(".description").empty();
            $(".phone_no").empty();
            $(".e_mail").empty();


            $(".add_div").show();
            $(".table_div").hide();


        });


        $(document).on('click', ".edit_btn", function () {

 $('.loader').show();
            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var controller = "admin";
            var get_function = "get_message_type_data";
            var success_alert = "Message Type added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_message_type_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
 $('.loader').hide();
                    $.each(response, function (i, value) {
                        $("#edit_name").empty();
                        $("#edit_message_type_id").empty();
                        $("#edit_description").empty();
                        $("#edit_phone_no").empty();
                        $("#edit_e_mail").empty();
                        $("#edit_created_at").empty();
                        $("#edit_timestamp").empty();
                       




                        $("#edit_name").val(value.name);
                        $('#edit_message_type_id').val(value.id);
                        $('#edit_description').val(value.description);
                        $('#edit_phone_no').val(value.phone_no);
                        $('#edit_e_mail').val(value.e_mail);
                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");

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
                url: "<?php echo base_url(); ?>admin/get_message_type_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
 $('.loader').hide();
                    $.each(response, function (i, value) {


                        $('#delete_message_type_id').val(value.id);

                        var delete_descripton = "Do you want to delete Message Type :  " + value.name + "";
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
            var submit_function = "add_message_type";
            var form_class = "add_form";
            var success_alert = "Message Type added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });



        $(".submit_edit_div").click(function () {
            var controller = "admin";
            var submit_function = "edit_message_type";
            var form_class = "edit_form";
            var success_alert = "Message Type data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_message_type";
            var form_class = "delete_form";
            var success_alert = "Message Type data delete successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });






    });
</script>




<!--END MAIN WRAPPER -->