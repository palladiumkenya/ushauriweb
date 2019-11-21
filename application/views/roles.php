<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Roles Management
            </div>   
            <div >


                <div class="panel-body"> 







                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">User Permissions Module</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">









                            <form id="assign_access_form" class="assign_access_form form-horizontal">

                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <div class="div_user_name" id="div_user_name">
                                    <select name="user_name" id="user_name" class="user_name form-control">
                                        <option>Please select User Name : </option>
                                        <?php foreach ($users as $value) {
                                            ?>
                                            <option value="<?php echo $value->id; ?>"><?php echo $value->f_name . ' ' . $value->m_name . ' ' . $value->l_name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div id="rights_div" class="rights_div" style="display: none;">

                                    <div class="form-group">


                                        <hr>


                                        <table class="table table-advance table-bordered table-condensed table-hover table-responsive table-striped dataTable dataTables_filter">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Check
                                                    </th>
                                                    <th>
                                                        Function Name
                                                    </th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> <input type="checkbox" name="select_all_fctns" id="select_all_fctns" class="select_all_fctns  minimal"/>
                                                    </td>   
                                                    <td>Select All</td> </tr>
                                                <?php foreach ($modules as $value) {
                                                    ?>
                                                    <tr>


                                                        <td> 
                                                            <input type="checkbox" name="functions[]" id="functions" value="<?php echo $value->id; ?>"   class=" minimal functions administration_functions" />

                                                        </td><td> <?php echo $value->module; ?></td>
                                                        <td><?php echo $value->description; ?></td>
                                                    </tr>


                                                    <?php
                                                }
                                                ?>


                                            </tbody>
                                        </table>








                                    </div>











                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success assign_access_btn" id="assign_access_btn">Assign Roles </button>
                                            <button type="reset" class="btn btn-primary cancel_btn" id="cancel_btn">Cancel</button>

                                        </div>
                                    </div>



                                </div>
                            </form>




















                        </div><!-- /.box-body -->
                    </div><!-- /.box -->






























                </div>
            </div>                <div class="panel-footer">
                Get   in touch: support.tech@mhealthkenya.org                             </div>

        </div>        












    </div>



</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>






<script>
    $(document).ready(function () {


        $("#user_name").change(function () {
            $(".rights_div").hide();
            $(".loader").show();
            var user_id = this.value;
            $.ajax({
                type: "GET",
                url: "<?php echo base_url(); ?>admin/get_user_permissions/" + user_id,
                dataType: "JSON",
                success: function (response) {
                    $(".rights_div").show();
                    $("input:checkbox").prop("checked", false);
                    $(".loader").hide();
                    for (i = 0; i < response.length; i++) {
                        var value = response[i].module_id;
                        if (value == "No Rights Assigned") {
                            $(".rights_div").show();

                        } else {

                            $("input:checkbox[value=" + value + "]").prop("checked", true);

                        }

                    }

                }, error: function (data) {

                    $(".loader").hide();
                    sweetAlert("Oops...", "Something went wrong!", "error");

                }
            });

        });

        $('#assign_access_form').submit(function (event) {

            dataString = $("#assign_access_form").serialize();
            $(".btn").prop('disabled', true);
            $(".loader").show();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>admin/assign_roles",
                data: dataString,
                success: function (data) {
                    //sweetAlert("Success", "success");
                    $(".loader").hide();
                    swal({
                        title: "Success!",
                        text: "Rights Assigned Successfullly!",
                        type: "success",
                        confirmButtonText: "Okay!",
                        closeOnConfirm: true
                    }, function () {
                        window.location.reload();

                    });
                    $(".rights_div").hide();


                }, error: function (data) {
                    $(".btn").prop('disabled', false);
                    $(".loader").hide();
                    sweetAlert("Oops...", "Something went wrong!", "error");

                }

            });
            event.preventDefault();
            return false;
        });




    });
</script>



<script type="text/javascript">
    $(document).ready(function () {
        $('.addministration_select_all').click(function (event) {  //on click 

            if (this.checked) { // check select status
                $('.administration_functions').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"               
                });
            } else {
                $('.administration_functions').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });
            }
        });

        $('.daily_ops_select_all').click(function (event) {  //on click 

            if (this.checked) { // check select status
                $('.daily_ops_functions').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"               
                });
            } else {
                $('.daily_ops_functions').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });
            }
        });

        $('.reports_select_all').click(function (event) {  //on click 

            if (this.checked) { // check select status
                $('.reports_functions').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"               
                });
            } else {
                $('.reports_functions').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });
            }
        });

        $('.select_all_fctns').click(function (event) {  //on click 

            if (this.checked) { // check select status
                $('.functions').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"               
                });
            } else {
                $('.functions').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });
            }
        });

    });
</script>




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
            $(".loader").show();

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
                    $(".loader").hide();
                    $.each(response, function (i, value) {

                        $("#edit_name").empty();
                        $("#edit_module_id").empty();
                        $("#edit_controller").empty();
                        $("#edit_function").empty();





                        $("#edit_name").val(value.module);
                        $('#edit_module_id').val(value.id);
                        $('#edit_controller').val(value.controller);
                        $('#edit_location').val(value.function);

                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");
                        $('#edit_level option[value=' + value.level + ']').attr("selected", "selected");

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
                url: "<?php echo base_url(); ?>admin/get_module_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {


                        $('#delete_module_id').val(value.id);

                        var delete_descripton = "Do you want to delete Module :  " + value.module_name + "";
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




<div class="modal_loading" id="modal_loading"><!-- Place at bottom of page --></div>
<style type="text/css">
    /* Start by setting display:none to make this hidden.
Then we position it in relation to the viewport window
with position:fixed. Width, height, top and left speak
for themselves. Background we set to 80% white with
our animation centered, and no-repeating */
    .modal_loading {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 ) 
            50% 50% 
            no-repeat;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        var opts = {
            lines: 12             // The number of lines to draw
            , length: 7             // The length of each line
            , width: 5              // The line thickness
            , radius: 10            // The radius of the inner circle
            , scale: 1.0            // Scales overall size of the spinner
            , corners: 1            // Roundness (0..1)
            , color: '#000'         // #rgb or #rrggbb
            , opacity: 1 / 4          // Opacity of the lines
            , rotate: 0             // Rotation offset
            , direction: 1          // 1: clockwise, -1: counterclockwise
            , speed: 1              // Rounds per second
            , trail: 100            // Afterglow percentage
            , fps: 20               // Frames per second when using setTimeout()
            , zIndex: 2e9           // Use a high z-index by default
            , className: 'spinner'  // CSS class to assign to the element
            , top: '50%'            // center vertically
            , left: '50%'           // center horizontally
            , shadow: false         // Whether to render a shadow
            , hwaccel: false        // Whether to use hardware acceleration (might be buggy)
            , position: 'absolute'  // Element positioning
        }
        var target = document.getElementById('modal_loading');
        var spinner = new Spinner(opts).spin(target);
    });
</script>




<!--END MAIN WRAPPER -->