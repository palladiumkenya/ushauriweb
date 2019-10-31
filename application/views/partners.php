<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Partners Report
            </div>   
            <div >


                <div class="panel-body"> 

                    <?php
                    $access_level = $this->session->userdata('access_level');

                    if ($access_level == "Donor") {
                        ?>

                        <?php
                    } else {
                        ?>
                        <button class="add_btn btn btn-primary btn-small" id="add_btn">Add Partner</button>
                        <?php
                    }
                    ?>


                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Partner Name</th>
                                    <th>Partner Type</th>
                                    <th>Phone No</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Date Added</th>
                                    <th>Time Stamp</th>
                                    <?php
                                    $access_level = $this->session->userdata('access_level');
                                    if ($access_level == "Donor") {
                                        ?>

                                        <?php
                                    } else {
                                        ?>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <?php
                                    }
                                    ?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($partners as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->partner_name; ?></td>
                                        <td><?php echo $value->partner_type_name; ?></td>
                                        <td><?php echo $value->phone_no; ?></td>
                                        <td><?php echo $value->e_mail; ?></td>
                                        <td><?php echo $value->location; ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>


                                        <?php
                                        $access_level = $this->session->userdata('access_level');
                                        if ($access_level == "Donor") {
                                            ?>

                                            <?php
                                        } else {
                                            ?>
                                            <td>
                                                <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                                <button class="btn btn-primary edit_btn" id="edit_btn">Edit </button></td>
                                            <td>
                                                <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                                <button class="btn btn-primary delete_btn" id="delete_btn">Delete </button></td>

                                            <?php
                                        }
                                        ?>


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
                            <h2 id="actionLabel">Add Partner</h2>






                            <form class="form add_form" id="add_form">
                                
                                
                                   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <div class="form-group">
                                    <label>Partner Name : </label> 
                                    <input type="text"  required="" name="name" class="form-control name"/>

                                </div> 


                                <div class="form-group">
                                    <label>Description : </label> 
                                    <textarea name="description" class="form-control description-text description" id="description"></textarea>

                                </div> 

                                <div class="form-group">
                                    <label>Location : </label> 
                                    <input type="text"  required="" name="location" class="form-control location"/>

                                </div> 




                                <div class="form-group">
                                    <label>E mail : </label> 
                                    <input type="text"  required="" name="e_mail" class="form-control e_mail"/>

                                </div> 

                                <div class="form-group">
                                    <label>Phone No : </label> 
                                    <input type="text"  required="" name="phone_no" class="form-control phone_no"/>

                                </div> 

                                <div class="form-group">
                                    <label>Partner Type : </label>

                                    <select class="form-control partner_type_id"  id="partner_type_id" name="partner_type_id">
                                        <option value="">Please select : </option>                                               
                                        <?php foreach ($partner_type as $value) {
                                            ?>
                                            <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                        <?php }
                                        ?>
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



                                <div class="form-group">
                                    <label>Partner Logo</label> <br>
                                    <span class="label label-info">Please do not add special characters or space when naming your image Allowed format : JPG,JPEG,PNG</span>
                                    <br>
                                    <input type="file" required="" name="partner_logo" id="partner_logo" class="form-control partner_logo" size="20" />
                                </div>






                                <button class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add Partner</button>
                                <button class="close_add_div btn btn-danger btn-small" id="close_add_div">Cancel</button>
                            </form>





                        </div>




                    </div>






                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit Partner</h2>






                            <form class="form edit_form" id="edit_form">

   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                <div class="form-group">
                                    <label>Partner Name : </label> 
                                    <input type="text"  required="" name="name" id="edit_name" class="form-control name"/>

                                </div> 


                                <div class="form-group">
                                    <label>Description : </label> 
                                    <textarea name="description"  class="form-control description-text description" id="edit_description"></textarea>

                                </div> 

                                <div class="form-group">
                                    <label>Location : </label> 
                                    <input type="text"  required="" name="location" id="edit_location" class="form-control location"/>

                                </div> 




                                <div class="form-group">
                                    <label>E mail : </label> 
                                    <input type="text"  required="" name="e_mail" id="edit_e_mail" class="form-control e_mail"/>

                                </div> 

                                <div class="form-group">
                                    <label>Phone No : </label> 
                                    <input type="text"  required="" name="phone_no" id="edit_phone_no" class="form-control phone_no"/>

                                </div> 

                                <div class="form-group">
                                    <label>Partner Type : </label>

                                    <select class="form-control partner_type_id"  id="edit_partner_type_id" name="partner_type_id">
                                        <option value="">Please select : </option>
                                        <?php foreach ($partner_type as $value) {
                                            ?>
                                            <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                        <?php }
                                        ?>
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



                                <input type="hidden" name="partner_id" class="form-control partner_id" id="edit_partner_id" />




                                <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update Partner</button>
                                <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>
                            </form>





                        </div>




                    </div>





                    <div class="delete_div" style="display: none;">











                        <div class="panel-body  formData" id="deleteForm">
                            <h2 id="actionLabel">Delete Partner</h2>






                            <form class="form delete_form" id="delete_form">

   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                
                                
                                <p><span class="delete_description"></span></p>


                                <input type="hidden" name="partner_id" class="form-control partner_id" id="delete_partner_id" />




                                <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete Partner</button>
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

            $(".description").empty();
            $(".location").empty();
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
            var get_function = "get_partner_data";
            var success_alert = "Partner added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_partner_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {

                        $("#edit_name").empty();
                        $("#edit_partner_id").empty();

                        $("#edit_description").empty();
                        $("#edit_location").empty();
                        $("#edit_phone_no").empty();
                        $("#edit_e_mail").empty();
                        $("#edit_created_at").empty();
                        $("#edit_timestamp").empty();
                        $("#edit_partner_id").empty();







                        $("#edit_name").val(value.partner_name);
                        $('#edit_partner_id').val(value.id);
                        $('#edit_partner_type_id option[value=' + value.partner_type_id + ']').attr("selected", "selected");
                        $('#edit_description').val(value.description);
                        $('#edit_location').val(value.location);
                        $('#edit_phone_no').val(value.phone_no);
                        $('#edit_e_mail').val(value.e_mail);
                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");

                    });


                    $(".edit_div").show();
                    $(".table_div").hide();


                }, error: function (data) {
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
                url: "<?php echo base_url(); ?>admin/get_partner_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {


                        $('#delete_partner_id').val(value.id);

                        var delete_descripton = "Do you want to delete Partner :  " + value.partner_name + "";
                        $(".delete_description").append(delete_descripton);
                    });


                    $(".delete_div").show();
                    $(".table_div").hide();


                }, error: function (data) {
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
            $('.loader').show();
            var controller = "admin";
            var submit_function = "add_partner";
            var form_class = "add_form";
            var success_alert = "Partner added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            //submit_data(controller, submit_function, form_class, success_alert, error_alert);










            $("#" + form_class + "").submit(function (event) {

                $('.modal_loading').show();
                $(".btn").prop('disabled', true);
                dataString = $("#" + form_class + "").serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>" + controller + "/" + submit_function + "",
                    data: dataString,
                    success: function (data) {
                        $('.modal_loading').hide();
                        data = JSON.parse(data);
                        var response = data[0].response;
                        if (response === true) {


                            $('.loader').hide();




                            var file_data = $('#partner_logo').prop('files')[0];
                            var form_data = new FormData();
                            form_data.append('file', file_data);
                            $.ajax({
                                url: "<?php echo base_url(); ?>admin/add_logo/Partner", // point to server-side controller method
                                dataType: 'text', // what to expect back from the server
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                type: 'post',
                                success: function (response) {
                                    $('.loader').hide();
                                    console.log(response);
                                },
                                error: function (response) {
                                    $(".btn").prop('disabled', false);
                                    $('.loader').hide();
                                    console.log(response);
                                }
                            });









                            swal({
                                title: "Success!",
                                text: "" + success_alert + "",
                                type: "success",
                                confirmButtonText: "Okay!",
                                closeOnConfirm: true
                            }, function () {
                                //window.location.reload(1);
                            });




                        } else if (response === 'Taken') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info", "Clinic No already taken ", 'info');
                        } else {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Oops...", "" + error_alert + "", "error");
                        }


                    }, error: function (data) {
                        $(".btn").prop('disabled', false);
                        $('.modal_loading').hide();
                        sweetAlert("Oops...", "" + error_alert + "", "error");
                    }

                });
                event.preventDefault();
                return false;
            });

















            $(".add_div").show();
            $(".table_div").hide();





















        });



        $(".submit_edit_div").click(function () {
            $('.loader').show();
            var controller = "admin";
            var submit_function = "edit_partner";
            var form_class = "edit_form";
            var success_alert = "Partner data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".edit_div").show();
            $(".table_div").hide();
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_partner";
            var form_class = "delete_form";
            var success_alert = "Partner data delete successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".delete_div").show();
            $(".table_div").hide();
        });






    });
</script>




<!--END MAIN WRAPPER -->