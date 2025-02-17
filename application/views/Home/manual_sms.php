                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">Manual SMS</a></li>
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
                        <h4 class="card-title">Send Manual SMS</h4>
                        <!--<h6 class="card-subtitle"> List of Client Groupings </h6>-->
                        <div class="table-responsive m-t-40">



                            <div class="panel-body"> 


                                <div class="table_div">



                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                         <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Clinic No</th>
                                    <th>Client Name</th>
                                    <th>Phone No</th>
                                    <th>Appointment Date</th>
                                    <th>Status</th>
                                    <th>Enrollment Date</th>
                                    <th>Time Stamp</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($clients as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->clinic_number; ?></td>

                                        <td>
                                            <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                            <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                <?php echo $value->f_name . " " . $value->m_name . " " . $value->l_name; ?>
                                            </button>

                                        </td>
                                        <td><?php echo $value->phone_no; ?></td>
                                        <td><?php echo $value->appntmnt_date; ?></td>
                                        <td><?php echo $value->status; ?></td>
                                        <td><?php echo $value->enrollment_date; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>

                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                                    </table>


                                </div>




                            </div>


                            

                    <div class="edit_div" style="display: none;">



                        <div class="panel-body  formData" id="editForm">
                            <form class="edit_form" id="edit_form">

                                
                                   <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                
                                
                                
                                <div class='row'>
                                    <div class='col-xs-10 form-group'>
                                        <div class="col-12">
                                            <section class='panel'>
                                                <header class='panel-heading'>   
                                                </header>
                                                <div class='panel-body'>  <div class='form'>


                                                        <input type="hidden" id="edit_client_id" class="client_id" name="client_id"/>

                                                        <h2> Send Client Manual SMS </h2> 
                                                        <label class='control-label'>Clinic Number* (Unique value : )</label>
                                                        <input class='form-control' readonly="" type='text' required='' name='clinic_number'  id='edit_clinic_number' >
                                                        <label class='control-label'>First Name </label> 
                                                        <input class='form-control fname' readonly="" type='text' name='fname'  id='edit_fname' >

                                                        <label class='control-label'>Middle Name </label>  
                                                        <input type='text' class='form-control mname' readonly="" name='mname'  id='edit_mname'>

                                                        <label class='control-label'>Last Name</label>  
                                                        <input class='form-control lame' type='text' readonly="" name='lname'  id='edit_lname' >


                                                        <label class='control-label'>Cell Phone Number (Own) </label> 
                                                        <input class='form-control mobile' readonly=""  type='text' name='mobile' id='edit_mobile'   >

                                                        <label class='control-label'> Message </label> 
                                                        <textarea class='form-control message'  name='message' id='message' onkeyup="countChar(this)">	 </textarea>
                                                        <br/>
                                                        <div id="charNum"></div>

                                                        </br> 

                                                        <button type="submit" class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Send SMS</button>
                                                        <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>




                                                    </div>

                                                </div> </section></div>




                                    </div>
                                </div>   






                            </form>





                        </div>




                    </div>








                         


                            <?php
                            $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                            );
                            ?>




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





<script type="text/javascript">

    function countChar(val) {
        var len = val.value.length;
        if (len >= 160) {
            val.value = val.value.substring(0, 160);
            sweetAlert("Oops...", "You've exceeded the  number of characters you are required to type...", "warning");
        } else {
            $('#charNum').text(160 - len);
        }
    }
    ;


    $(document).ready(function () {


        $(document).on('click', ".edit_btn", function () {
            $('.loader').show();

            //get data
            var data_id = $(this).closest('tr').find('input[name="client_id"]').val();
            var controller = "home";
            var get_function = "get_client_data";
            var error_alert = "An Error Ocurred";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {
                        $("#edit_user_id").empty();

                        $("#edit_status").empty();

                        $("#edit_f_name").empty();

                        $("#edit_m_name").empty();

                        $("#edit_l_name").empty();

                        $("#edit_dob").empty();

                        $("#edit_phone_no").empty();

                        $("#edit_e_mail").empty();
                        $("#edit_status").empty();

                        $('#edit_client_id').val(value.client_id);
                        $('#edit_clinic_number').val(value.clinic_number);
                        $('#edit_fname').val(value.f_name);
                        $('#edit_mname').val(value.m_name);
                        $('#edit_lname').val(value.l_name);
                        $('#edit_dob').val(value.dob);

                        $(".edit_div").show();
                        $(".table_div").hide();



                        $('#edit_mobile').val(value.phone_no);









                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                    });





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








        $(".submit_edit_div").click(function () {
            var controller = "home";
            var submit_function = "send_manual_sms";
            var form_class = "edit_form";
            var success_alert = "SMS set for delivery successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });









    });
</script>







<!--END MAIN WRAPPER -->