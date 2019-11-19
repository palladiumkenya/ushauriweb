<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                All Client report in the  system
            </div>   
            <div >


                <div class="panel-body"> 


                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UPN</th>
                                    <th>Client Name</th>
                                    <th>Phone No</th>
                                    <th>DoB</th>
                                    <th>Status</th>
                                    <th>Enrollment Date</th>
                                    <th>ART Date</th>
                                    <th>Group Name</th>
                                    <th>Preferred Lang</th>
                                    <th>Gender</th>
                                    <th>Marital Status</th>
                                    <th>SMS Enable</th>
                                    <th>Wellness Check</th>
                                    <th>Motivational Messages</th>
                                    <th>MFL No </th>
                                    <th>Facility Name</th>
                                    <th>County </th>
                                    <th>Sub County </th>
                                    <th>Date</th>
                                    <th>Age Group</th>
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
                                        <?php
                                        $view_client = $this->session->userdata('view_client');

                                        if ($view_client == "Yes") {
                                            ?>

                                            <td><?php
                                                $client_name = ucwords(strtolower($value->f_name)) . ' ' . ucwords(strtolower($value->m_name)) . ' ' . ucwords(strtolower($value->l_name));

                                                echo $client_name;
                                                ?></td>
                                            <td><?php echo $value->phone_no; ?></td>
                                            <?php
                                        } else {
                                            ?>

                                            <td>XXXXXX XXXXXXX</td>
                                            <td>XXXXXX XXXXXXX</td>
                                            <?php
                                        }
                                        ?>
                                        <td><?php echo $value->dob; ?></td>
                                        <td><?php echo $value->client_status; ?></td>
                                        <td><?php echo $value->enrollment_date; ?></td>
                                        <td><?php echo $value->art_date; ?></td>
                                        <td><?php echo $value->group_name; ?></td>
                                        <td><?php echo $value->language_name; ?></td>
                                        <td><?php echo $value->gender_name; ?></td>
                                        <td><?php echo $value->marital; ?></td>
                                        <td><?php echo $value->smsenable; ?></td>
                                        <td><?php echo $value->wellness_enable; ?></td>
                                        <td><?php echo $value->motivational_enable; ?></td>
                                        <td><?php echo $value->mfl_code; ?></td>
                                        <td><?php echo $value->facility_name; ?></td>
                                        <td><?php echo $value->county_name; ?></td>
                                        <td><?php echo $value->sub_county_name; ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->age_group; ?></td>

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
                                                        <label class='control-label'>Clinic Number* (Unique value : )</label>
                                                        <input class='form-control' type='text' required='' name='clinic_number'  id='edit_clinic_number' >
                                                        <label class='control-label'>First Name </label> 
                                                        <input class='form-control fname' type='text' name='fname'  id='edit_fname' >

                                                        <label class='control-label'>Middle Name </label>  
                                                        <input type='text' class='form-control mname' name='mname'  id='edit_mname'>

                                                        <label class='control-label'>Last Name</label>  
                                                        <input class='form-control lame' type='text' name='lname'  id='edit_lname' >

                                                        <label class='control-label'> Year of Birth: </label>
                                                        <input class='form-control dob' type='text' name='p_year'  id='edit_dob' >

                                                        <label class='control-label'> Status</label>

                                                        <select class='form-control condition' name='condition' id='edit_condition'>
                                                            <option value=''> </option>

                                                            <option value='ART'>ART </option>
                                                            <option value='Pre ART'>Pre ART </option>
                                                        </select>


                                                        <label class='control-label'>  Grouping :</label>
                                                        <select class='form-control group' name='group' id='edit_group' >
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($groupings as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'> Select Facility Attached : </label>  
                                                        <select class='form-control facilities' name='facilities' id='edit_facilities' >
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
                                                        <input class='form-control mobile'  type='text' name='mobile' id='edit_mobile'   >
                                                        <label class='control-label'> Alternative Phone Number </label>
                                                        <input class='form-control altmobile'  type='text' name='altmobile' id='edit_altmobile'>
                                                        <label class='control-label'>If shared, name</label> 
                                                        <input class='form-control sharename'  type='text' name='sharename' id='edit_sharename'>
                                                        <label class='control-label'>  Preferred Lang :  </label> 
                                                        <select class='form-control lang' name='lang' id='lang'>
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($langauges as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'>  Enable Message Alerts? </label> 
                                                        <select class='form-control smsenable' name='smsenable' id='edit_smsenable'>
                                                            <option value=''> </option>
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

                                                                <label class='control-label'> Explain other appointment: </label> 
                                                                <textarea class='form-control p_apptype3'  name='p_apptype3'  id='edit_p_apptype3'></textarea>

                                                                <label class='control-label'> Custom Appointment Reminder </label> 
                                                                <textarea class='form-control p_custommsg'  name='custom_appointsms' id='edit_p_custommsg' onkeyup="countChar(this)">	 </textarea>
                                                                <br/>
                                                                <div id="charNum"></div>

                                                                </br> 

                                                                <button type="submit" class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Add Client</button>
                                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>

                                                        </div>  </div></section></div> </div> 






                                    </div>
                                </div>   






                            </form>





                        </div>

                        <div class="appointment_logs_div" id="appointment_logs_div">

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
                    get_appointment_logs(data_id);
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
                        $('#edit_mname').val(value.clinic_number);
                        $('#edit_fname').val(value.f_name);
                        $('#edit_mname').val(value.m_name);
                        $('#edit_lname').val(value.l_name);
                        $('#edit_dob').val(value.dob);

                        $('#edit_condition option[value=' + value.client_status + ']').attr("selected", "selected");
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
                        $('#edit_p_apptype1 option[value=' + value.app_type_1 + ']').attr("selected", "selected");
                     
                        $('#edit_p_apptype3').val(value.expln_app);
                        $('#edit_p_custommsg').val(value.custom_txt);




                        $(".edit_div").show();
                        $(".table_div").hide();





                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                    });





                }, error: function (data) {
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });









        });



        function get_appointment_logs(client_id) {
            $('.loader').show();
            var controller = "home";
            var get_function = "get_appointment_logs";
            var error_alert = "Ooops , Something really bad has happened (:";
            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + client_id,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    var table = "<table class='table table-bordered table-hover table-condensed table-stripped '><thead><th>No.</th><th>Clinic No</th><th>Client Name.</th><th>Phone No</th><th>Appointment Date.</th><th>Status </th><th>No.</th><th>Enrollmet Date</th><th>App Message .</th></thead>\n\
                <tbody id='app_results_tbody' class='app_results_tbody'></tbody></table>";
                    $(".appointment_logs_div").append(table);
                    $.each(response, function (i, value) {

                        var clinic_no = value.clinic_number;
                        var client_name = value.f_name + " " + value.m_name + " " + value.l_name;
                        var phone_no = value.phone_no;
                        var appointment_dte = value.appntmnt_date;
                        var status = value.status;
                        var enrollment = value.enrollment_date;
                        var updated_at = value.updated_at;
                        var app_msg = value.app_msg;



                        var tbody = "<tr><td>1</td><td>" + clinic_no + "</td><td>" + client_name + "</td><td>" + phone_no + "</td><td>" + appointment_dte + "</td><td>" + status + "</td><td>" + enrollment + "</td><td>" + app_msg + "</td></tr>";
                        $(".app_results_tbody").append(tbody);
                    });





                }, error: function (data) {
                    sweetAlert("Oops...", "" + error_alert + "", "error");

                }

            });



        }


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