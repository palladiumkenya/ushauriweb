
<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                All the 47 Republic of Kenya Counties
            </div>   
            <div >


                <div class="panel-body"> 

                    <button class="add_btn btn btn-primary btn-small" id="add_btn">Add County</button>
                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>County Name</th>
                                    <th>Code</th>
                                    <th>Tier</th>
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
                                foreach ($counties as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->code; ?></td>
                                        <td><?php echo $value->tier; ?></td>
                                        <td><?php echo $value->status; ?></td>
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
                            <h2 id="actionLabel">Add County</h2>






                            <form class="form add_form" id="add_form">


                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />




                                <div class="form-county">
                                    <label>County Name : </label> 
                                    <input type="text"  required="" name="name" class="form-control name"/>

                                </div> 


                                <div class="form-county">
                                    <label>Code : </label> 
                                    <input name="code" required="" class="form-control code-text code" id="code"/>

                                </div> 
                                <div class="form-county">
                                    <label class="control-label">Tier : </label> 

                                    <select name="tier" required="" class="form-control code-text tier" id="tier">
                                        <option value="">Please Select : </option>
                                        <?php
                                        foreach ($county_tier as $value) {
                                            ?>
                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div> 







                                <div class="form-county">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control" id="status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>


                                <button class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add County</button>
                                <button class="close_add_div btn btn-danger btn-small" id="close_add_div">Cancel</button>
                            </form>





                        </div>




                    </div>






                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit County</h2>






                            <form class="form edit_form" id="edit_form">

                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                <div class="form-county">
                                    <label>County Name : </label> 
                                    <input type="text"  required="" name="name" id="edit_name" class="form-control name"/>

                                </div> 


                                <div class="form-county">
                                    <label class="control-label">Code : </label> 
                                    <input name="code"  class="form-control code-text code" id="edit_code">

                                </div> 

                                <div class="form-county">
                                    <label class="control-label">Tier : </label> 

                                    <select name="tier" required="" class="form-control code-text tier" id="edit_tier">
                                        <option value="">Please Select : </option>
                                        <?php
                                        foreach ($county_tier as $value) {
                                            ?>
                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div> 





                                <div class="form-county">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control " id="edit_status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>


                                <input type="hidden" name="county_id" class="form-control county_id" id="edit_county_id" />

                                <br/>


                                <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update County</button>
                                <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>
                            </form>





                        </div>




                    </div>





                    <div class="delete_div" style="display: none;">











                        <div class="panel-body  formData" id="deleteForm">
                            <h2 id="actionLabel">Delete County</h2>






                            <form class="form delete_form" id="delete_form">


                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <p><span class="delete_code"></span></p>


                                <input type="hidden" name="county_id" class="form-control county_id" id="delete_county_id" />


                                <br/>

                                <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete County</button>
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

            $(".code").empty();
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
            var get_function = "get_county_data";
            var success_alert = "County added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_county_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {
                        $("#edit_name").empty();
                        $("#edit_county_id").empty();
                        $("#edit_code").empty();
                        $("#edit_phone_no").empty();
                        $("#edit_e_mail").empty();
                        $("#edit_created_at").empty();
                        $("#edit_timestamp").empty();





                        $("#edit_name").val(value.name);
                        $('#edit_county_id').val(value.id);
                        $('#edit_code').val(value.code);
                        $('#edit_phone_no').val(value.phone_no);
                        $('#edit_e_mail').val(value.e_mail);
                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");
                        $('#edit_tier option[value=' + value.tier + ']').attr("selected", "selected");

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
                url: "<?php echo base_url(); ?>admin/get_county_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {


                        $('#delete_county_id').val(value.id);

                        var delete_descripton = "Do you want to delete County :  " + value.name + "";
                        $(".delete_code").append(delete_descripton);
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
            var submit_function = "add_county";
            var form_class = "add_form";
            var success_alert = "County added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });



        $(".submit_edit_div").click(function () {
            var controller = "admin";
            var submit_function = "edit_county";
            var form_class = "edit_form";
            var success_alert = "County data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_county";
            var form_class = "delete_form";
            var success_alert = "County data delete successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });






    });
</script>




<!--END MAIN WRAPPER -->