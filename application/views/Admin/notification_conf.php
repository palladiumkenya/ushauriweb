<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>admin/notification_conf">Notification Conf</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->




        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Notification Types</h4>
                        <div class="table-responsive m-t-40">








                            <div class="panel-body"> 


                                <!-- <button class="add_btn btn btn-primary btn-small" id="add_btn">Add Time</button> -->





                                <div class="table_div">
  <input type="hidden" name="report_name" class="report_name input-rounded input-sm form-control " id="report_name" value="Notification Configuration Export Report"/>

                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
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










                                <div class="row edit_div" id="edit_div" style="display: none">



                                    <div class="col-lg-12">
                                        <div class="card card-outline-primary">
                                            <div class="card-header">
                                                <h4 class="m-b-0 text-white">Edit Time</h4>
                                            </div>
                                            <div class="card-body">






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
                                        </div></div></div>



                                <div class="row delete_div" id="delete_div" style="display: none">
                                    <div class="col-lg-6">
                                        <div class="card card-outline-primary">
                                            <div class="card-header">
                                                <h4 class="m-b-0 text-white">Delete Time</h4>
                                            </div>
                                            <div class="card-body">




                                                <form class="form delete_form" id="delete_form">


                                                    <?php
                                                    $csrf = array(
                                                        'name' => $this->security->get_csrf_token_name(),
                                                        'hash' => $this->security->get_csrf_hash()
                                                    );
                                                    ?>

                                                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />




                                                    <p><span class="delete_message"></span></p>


                                                    <input type="hidden" name="message_id" class="form-control message_id" id="delete_message_id" />




                                                    <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete Message</button>
                                                    <button class="close_delete_div btn btn-danger btn-small" id="close_delete_div">Cancel</button>
                                                </form>




                                            </div>
                                        </div></div></div>





                            </div>








                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PAge Content -->





        <!-- END COMMENT AND NOTIFICATION  SECTION -->

    </div>






    <!--END MAIN WRAPPER -->


</div>





<!-- End PAge Content -->
</div>
<!-- End Container fluid  -->
<!-- footer -->
<footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
<!-- End footer -->
</div>
<!-- End Page wrapper  -->





<!--END BLOCK SECTION -->













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
            var error_alert = "An Error Ocurred";



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
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".edit_div").show();
            $(".table_div").hide();
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_content";
            var form_class = "delete_form";
            var success_alert = "Content data delete successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);

            $(".delete_div").show();
            $(".table_div").hide();
        });






    });
</script>






<!--END MAIN WRAPPER -->

















