
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


                    <div class="table_div">

                        <?php
                        $access_level = $this->session->userdata('access_level');
                        if ($access_level == "Partner") {
                            ?>
                            <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Client Name</th>
                                        <th>DoB</th>
                                        <th>Phone No</th>
                                        <th>Type</th>
                                        <th>Treatment</th>
                                        <th>Status</th>
                                        <th>Enrollment Date</th>
                                        <th>ART Date</th>
                                        <th>Date Added</th>
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
                                            <td>
                                                <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                    <?php echo $value->clinic_number; ?>
                                                </button>

                                            </td>
                                            <td><?php
                                                $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                echo $client_name;
                                                ?></td>
                                            <td><?php echo $value->dob; ?></td>
                                            <td><?php echo $value->phone_no; ?></td>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->status; ?></td>
                                            <td><?php echo $value->enrollment_date; ?></td>

                                            <td><?php echo $value->art_date; ?></td>

                                            <td><?php echo $value->created_at; ?></td>
                                            <td><?php echo $value->updated_at; ?></td>

                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        } elseif ($access_level == "Facility") {
                            ?>
                            <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Client Name</th>
                                        <th>DoB</th>
                                        <th>Phone No</th>
                                        <th>Type</th>
                                        <th>Treatment</th>
                                        <th>Status</th>
                                        <th>Enrollment Date</th>
                                        <th>ART Date</th>
                                        <th>Date Added</th>
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
                                            <td>
                                                <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                    <?php echo $value->clinic_number; ?>
                                                </button>

                                            </td>
                                            <td><?php
                                                $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                echo $client_name;
                                                ?></td>
                                            <td><?php echo $value->dob; ?></td>
                                            <td><?php echo $value->phone_no; ?></td>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->status; ?></td>
                                            <td><?php echo $value->enrollment_date; ?></td>

                                            <td><?php echo $value->art_date; ?></td>

                                            <td><?php echo $value->created_at; ?></td>
                                            <td><?php echo $value->updated_at; ?></td>

                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        } else {
                            ?>

                            <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Client Name</th>
                                        <th>DoB</th>
                                        <th>Phone No</th>
                                        <th>Type</th>
                                        <th>Treatment</th>
                                        <th>Status</th>
                                        <th>Enrollment Date</th>
                                        <th>ART Date</th>
                                        <th>Date Added</th>
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
                                            <td>
                                                <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                    <?php echo $value->clinic_number; ?>
                                                </button>

                                            </td>
                                            <td><?php
                                                $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                echo $client_name;
                                                ?></td>
                                            <td><?php echo $value->dob; ?></td>
                                            <td><?php echo $value->phone_no; ?></td>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->status; ?></td>
                                            <td><?php echo $value->enrollment_date; ?></td>

                                            <td><?php echo $value->art_date; ?></td>

                                            <td><?php echo $value->created_at; ?></td>
                                            <td><?php echo $value->updated_at; ?></td>

                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>


                            <?php
                        }
                        ?>




                    </div>













                    <div class="edit_div" style="display: none;">











                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit Client</h2>


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
                                        <div class='col-xs-5'>
                                            <section class='panel'>
                                                <header class='panel-heading'>   
                                                </header>
                                                <div class='panel-body'>  <div class='form'>


                                                        <input type="hidden" id="edit_client_id" class="client_id" name="client_id"/>

                                                        <h2> Client Personal Information </h2> 
                                                        <label class='control-label'>Unique Patient Number* (Unique value : ) Once entered , cannot be changed </label>
                                                        <input class='form-control' type='text' required=''  name='clinic_number'  id='edit_clinic_number' >
                                                        <label class='control-label'>First Name </label> 
                                                        <input class='form-control fname' required="" type='text' name='fname'  id='edit_fname' >

                                                        <label class='control-label'>Middle Name </label>  
                                                        <input type='text' required="" class='form-control mname' name='mname'  id='edit_mname'>

                                                        <label class='control-label'>Last Name</label>  
                                                        <input class='form-control lame' type='text' name='lname'  id='edit_lname' >

                                                        <label class='control-label'> Date of Birth: </label>
                                                        <input class='form-control dob' required="" type='text' name='p_year'  id='edit_dob' >





                                                        <label class='control-label'>  Gender : <span style="color: red">*</span> </label> 
                                                        <select class='form-control gender' required="" name='gender' id='edit_gender'>
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($genders as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>


                                                        <label class='control-label'>  Marital Status : <span style="color: red">*</span> </label> 
                                                        <select class='form-control marital' required="" name='marital' id='edit_marital'>
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($maritals as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->marital; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>




                                                        <label class='control-label'> Treatment</label>

                                                        <select class='form-control condition' required="" name='condition' id='edit_condition'>
                                                            <option value=''> </option>
                                                            <option value='ART'>ART </option>
                                                            <option value='Pre ART'>Pre ART </option>
                                                        </select>







                                                        <label class='control-label'> HIV Enrollment Date: </label> <span style="color: red">*</span>
                                                        <input class='form-control enrollment_date date' required="" placeholder="Date enrolled to HIV Care" type='text' name='enrollment_date' id='edit_enrollment_date' >




                                                        <label class='control-label'> ART Start Date: </label> 
                                                        <input class='form-control  date'  placeholder="ART Start Date" type='text' name='art_date' id='edit_art_date' >






                                                        <label class='control-label'>  Grouping :</label>
                                                        <select class='form-control group' required="" name='group' id='edit_group' >
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($groupings as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'> Select Facility Attached : </label>  
                                                        <select class='form-control facilities' required="" name='facilities' id='edit_facilities' >
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($facilities as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>


                                                        </select>


                                                        <label class='control-label'>How often would you like to receive Adherence messages?(Hrs)</label> 
                                                        <input class="form-control" type="text"   name="frequency" id="edit_frequency">   


                                                        <label class='control-label'>What time of day do you want to receive Alerts? </label> 
                                                        <select name='time' class='form-control time' id='edit_time'>"
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($times as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'>Cell Phone Number (Own) </label> 
                                                        <input class='form-control mobile' required=""  type='text' name='mobile' id='edit_mobile'   >
                                                        <label class='control-label'> Alternative Phone Number </label>
                                                        <input class='form-control altmobile'  type='text' name='altmobile' id='edit_altmobile'>
                                                        <label class='control-label'>If shared, name</label> 
                                                        <input class='form-control sharename'  type='text' name='sharename' id='edit_sharename'>
                                                        <label class='control-label'>  Communication language :  </label> 
                                                        <select class='form-control lang' name='lang' id='edit_lang'>
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($langauges as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'>  Enable Message Alerts? </label> 
                                                        <select class='form-control smsenable' required="" name='smsenable' id='edit_smsenable'>
                                                            <option value=''> </option>
                                                            <option value='Yes'> Yes </option>
                                                            <option value='No'> No </option>
                                                        </select>


                                                        <label class='control-label'>  Client Status :  </label> 
                                                        <select class='form-control edit_status' required="" name='status' id='edit_status'>
                                                            <option value=''> Please select : </option>
                                                            <option value='Active'> Active </option>
                                                            <option value='Disabled'> Disabled </option>
                                                            <option value="Deceased">Deceased</option>
                                                        </select>



                                                        <label class='control-label'>  Enable Wellness Check-ins? <span style="color: red">*</span> </label> 
                                                        <select class='form-control edit_wellnessenable' required="" name='wellnessenable' id='edit_wellnessenable'>
                                                            <option value=''>Please select : </option>
                                                            <option value='Yes'> Yes </option>
                                                            <option value='No'> No </option>
                                                        </select>


                                                        <label class='control-label'>  Enable Motivational Messages? <span style="color: red">*</span> </label> 
                                                        <select class='form-control edit_motivational_enable' required="" name='motivational_enable' id='edit_motivational_enable'>
                                                            <option value=''>Please select : </option>
                                                            <option value='Yes'> Yes </option>
                                                            <option value='No'> No </option>
                                                        </select>

                                                    </div>

                                                </div> </section></div>
                                        <!--    /*     * ********************End of personal enrollment info section ******************************************** */
                                        
                                            /*     * ********************Start  enrollment appointment info section ******************************************** */-->
                                        <div class='col-xs-5 form-group'>
                                            <div class=''>
                                                <section class='panel'>
                                                    <header class='panel-heading'>  </header>
                                                    <div class='panel-body'>  <div class='form'>
                                                            <h2> Client Appointment Information </h2> 
                                                            <label class='control-label'> Next Appointment Date </lable> 
                                                                <input type='text'  class='form-control appointment_date' name='apptdate' id='appointment_date'/> 
                                                                <input type='hidden'  name='appointment_type' id='edit_appointment_type' value='Appointment' class='form-control' readonly/>
                                                                <label class='control-label'> Appointment Status </label>
                                                                <input type='hidden' class='form-control' name='appstatus' id='edit_appstatus' value='Booked'/>

                                                                <label class='control-label'> Appointment Type 1: </label>
                                                                <select class='form-control' name='p_apptype1' id='edit_p_apptype1' >
                                                                    <option value=''></option>
                                                                    <option value="Re-fill">Re-fill </option>
                                                                    <option value="Clinical-Review">Clinical-Review </option>
                                                                    <option value="Enhanced Adherence">Enhanced Adherence </option>
                                                               </select>
                                                                



                                                                <button type="submit" class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update Client</button>
                                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>

                                                        </div>  </div></section></div> </div> 






                                    </div>
                                </div>   






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



        $(".cancel_add_client").click(function () {

            $(".edit_div").hide();
            $(".table_div").show();
        });

        $(document).on('click', ".edit_btn", function () {
            $('.loader').show();

            //get data
            var data_id = $(this).closest('tr').find('input[name="client_id"]').val();
            var controller = "home";
            var get_function = "get_client_data";
            var error_alert = "Ooops , Something really bad has happened (:";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {
                        $("#edit_user_id").empty();



                        $("#edit_f_name").empty();

                        $("#edit_m_name").empty();

                        $("#edit_l_name").empty();

                        $("#edit_dob").empty();

                        $("#edit_phone_no").empty();

                        $("#edit_e_mail").empty();


                        $('#edit_client_id').val(value.client_id);
                        $('#edit_clinic_number').val(value.clinic_number);
                        $('#edit_fname').val(value.f_name);
                        $('#edit_mname').val(value.m_name);
                        $('#edit_lname').val(value.l_name);
                        $('#edit_dob').val(value.dob);


                        //$('#edit_condition').val(value.client_status);


                        $('#edit_condition option[value="' + value.client_status + '"]').attr("selected", "selected");
                        $('#edit_group option[value=' + value.group_id + ']').attr("selected", "selected");
                        $('#edit_facilities option[value=' + value.facility_id + ']').attr("selected", "selected");
                        $('#edit_frequency').val(value.txt_frequency);
                        $('#edit_time option[value=' + value.txt_time + ']').attr("selected", "selected");


                        $('#edit_mobile').val(value.phone_no);
                        $('#edit_altmobile').val(value.alt_phone_no);
                        $('#edit_sharename').val(value.shared_no_name);
                        $('#edit_lang option[value=' + value.language_id + ']').attr("selected", "selected");
                        $('#edit_smsenable option[value=' + value.smsenable + ']').attr("selected", "selected");


                        $('#appointment_date').val(value.appntmnt_date);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");
                        $('#edit_gender option[value=' + value.gender + ']').attr("selected", "selected");
                        $('#edit_marital option[value=' + value.marital + ']').attr("selected", "selected");


                        $('#edit_motivational_enable option[value=' + value.motivational_enable + ']').attr("selected", "selected");
                        $('#edit_wellnessenable option[value=' + value.wellness_enable + ']').attr("selected", "selected");





                        var enrollment_date = value.enrollment_date;
                        var res = enrollment_date.substring(0, 10);
                        var new_enrollment_date = res.split("-").reverse().join("/");

                        $('#edit_enrollment_date').val(new_enrollment_date);

                        var art_date = value.art_date;
                        var art_res = art_date.substring(0, 10);
                        var new_art_date = art_res.split("-").reverse().join("/");

                        $('#edit_art_date').val(new_art_date);






                        $(".edit_div").show();
                        $(".table_div").hide();





                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                    });





                }, error: function (data) {
                    $('.loader').hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });





            //get data
            var data_id = $(this).closest('tr').find('input[name="client_id"]').val();
            var controller = "home";
            var get_function2 = "get_appointment_client_data";
            var error_alert = "Ooops , Something really bad has happened (:";

            $('.loader').show();

            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function2 + "/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    $.each(response, function (i, value) {

                        $("#edit_p_custommsg").empty();

                        $("#edit_p_apptype3").empty();

                        $("#edit_p_apptype2").empty();

                        $("#appointment_date").empty();





                        $('#appointment_date').val(value.appntmnt_date);
                        $('#edit_p_apptype2').val(value.app_type_2);
                        $('#edit_p_apptype3').val(value.expln_app);
                        $('#edit_p_custommsg').val(value.custom_txt);







                        $(".edit_div").show();
                        $(".table_div").hide();





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
            var submit_function = "update_client";
            var form_class = "edit_form";
            var success_alert = "Client data updated successfully ... :) ";
            var error_alert = "Ooops , Something really bad has happened (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });









    });
</script>




<!--END MAIN WRAPPER -->