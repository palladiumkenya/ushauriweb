<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Notification COnfiguration Report MOdule
            </div>   
            <div >


                <div class="panel-body"> 

                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Notification Type</th>
                                    <th>Days</th>
                                    <th>Based on </th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Date Added</th>
                                    <th>Time Stamp</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($notifications as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->notification_type; ?></td>
                                        <td><?php echo $value->days; ?></td>
                                        <td><?php echo $value->based_on; ?></td>
                                        <td><?php echo $value->value; ?></td>
                                        <td><?php echo $value->status; ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                            <button class="btn btn-primary edit_btn" id="edit_btn">Edit </button></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>












                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit Content</h2>






                            <form class="form edit_form" id="edit_form">

   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                <div>
                                    <label>Notification Type </label>
                                    <input type="text" readonly="" class="form-control edit_notification_type " id="edit_notification_type" name="edit_notification_type"/>
                                </div>




                                <div class="form-group">
                                    <label>Days : </label> 
                                    <input type="text" name="days" class="form-control days" id="edit_days"/>

                                </div> 







                                <div class="form-group">
                                    <label>Status : </label> 
                                    <select name="status" class="form-control status" id="edit_status" >
                                        <option value="">Please select</option>
                                        <option value="Active">Active</option>
                                        <option value="Disabled">Disabled</option>
                                    </select>
                                </div>



                                <input type="hidden" name="notificaiton_id" class="form-control notification_id" id="edit_notification_id" />




                                <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update </button>
                                <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>
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

            $(".content").empty();
            $(".response").empty();




            $(".add_div").show();
            $(".table_div").hide();


        });


        $(document).on('click', ".edit_btn", function () {

  $(".loader").show();
            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var controller = "admin";
            var get_function = "get_notificationtype_data";
            var success_alert = "Content added successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_notificationtype_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
  $(".loader").hide();
                    $.each(response, function (i, value) {


                        $("#edit_notification_id").empty();
                        $("#edit_days").empty();


                        $("#edit_notification_type").val(value.notification_type);


                        $("#edit_days").val(value.days);
                        $('#edit_notification_id').val(value.id);
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





     


        $(".submit_edit_div").click(function () {
            var controller = "admin";
            var submit_function = "edit_notification_flow";
            var form_class = "edit_form";
            var success_alert = "Notification data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".edit_div").show();
            $(".table_div").hide();
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_content";
            var form_class = "delete_form";
            var success_alert = "Content data delete successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".delete_div").show();
            $(".table_div").hide();
        });






    });
</script>




<!--END MAIN WRAPPER -->