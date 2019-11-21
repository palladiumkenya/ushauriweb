
        <!--END BLOCK SECTION -->
        <hr />
        <!-- COMMENT AND NOTIFICATION  SECTION -->
        <div class="row" id="data">

            <div class="col-lg-12">


                <div class="panel panel-primary" id="main_clinician">

                    <div class="panel-heading"> 
                        Edit Client Details
                    </div>   
                    <div >


                        <div class="panel-body"> 



                            <form class="add_form" id="add_form">

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




                                                        <h2> Client Personal Information </h2> 
                                                        <label class='control-label'>Clinic Number* (Unique value : )</label>
                                                        <input class='form-control' type='text' required='' name='clinic_number'  id='clinic_number' >
                                                        <label class='control-label'>First Name </label> 
                                                        <input class='form-control fname' type='text' name='fname'  id='fname' >

                                                        <label class='control-label'>Middle Name </label>  
                                                        <input type='text' class='form-control mname' name='mname'  id='mname'>

                                                        <label class='control-label'>Last Name</label>  
                                                        <input class='form-control lame' type='text' name='lname'  id='lname' >

                                                        <label class='control-label'> Year of Birth: </label>
                                                        <input class='form-control dob' type='text' name='p_year'  id='dob' >

                                                        <label class='control-label'> Status</label>

                                                        <select class='form-control condition' name='condition' id='condition'>
                                                            <option value=''> </option>

                                                            <option value='ART'>ART </option>
                                                            <option value='Pre ART'>Pre ART </option>
                                                        </select>


                                                        <label class='control-label'>  Grouping :</label>
                                                        <select class='form-control group' name='group' id='group' >
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($groupings as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'> Select Facility Attached : </label>  
                                                        <select class='form-control facilities' name='facilities' id='facilities' >
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($facilities as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>


                                                        </select>


                                                        <label class='control-label'>How often would you like to receive Adherence messages?(Hrs)</label> 
                                                        <input class="form-control" type="text"   name="frequency" id="frequency">   


                                                        <label class='control-label'>What time of day do you want to receive Alerts? </label> 
                                                        <select name='time' class='form-control time' id='time'>"
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($times as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'>Cell Phone Number (Own) </label> 
                                                        <input class='form-control mobile'  type='text' name='mobile' id='mobile'   >
                                                        <label class='control-label'> Alternative Phone Number </label>
                                                        <input class='form-control altmobile'  type='text' name='altmobile' id='altmobile'>
                                                        <label class='control-label'>If shared, name</label> 
                                                        <input class='form-control sharename'  type='text' name='sharename' id='sharename'>
                                                        <label class='control-label'>  Preferred language :  </label> 
                                                        <select class='form-control lang' name='lang' id='lang'>
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($langauges as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>

                                                        <label class='control-label'>  Enable Message Alerts? </label> 
                                                        <select class='form-control smsenable' name='smsenable' id='smsenable'>
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
                                                                <input type='hidden'  name='appointment_type' id='appointment_type' value='Appointment' class='form-control' readonly/>
                                                                <label class='control-label'> Appointment Status </label>
                                                                <input type='hidden' class='form-control' name='appstatus' id='appstatus' value='Booked'/>

                                                                <label class='control-label'> Appointment Type 1: </label>
                                                                <select class='form-control' name='p_apptype1' id='p_apptype1' >
                                                                    <option value=''></option>
                                                                    <option value="Re-fill">Re-fill </option>
                                                                    <option value="Clinical-Review">Clinical-Review </option>
                                                                    <option value="Enhanced Adherence">Enhanced Adherence </option>
                                                                </select>
                                                               
                                                                
                                                                </br> 

                                                                <button type="submit" class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add Client</button>
                                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>

                                                        </div>  </div></section></div> </div> 






                                    </div>
                                </div>   






                            </form>








                            <div class="panel-footer">
                                Get   in touch: support.tech@mhealthkenya.org                             </div>

                        </div>        












                    </div>



                </div>
            </div>
            <!-- END COMMENT AND NOTIFICATION  SECTION -->

        </div>








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

                $(".cancel_add_client").click(function () {
                    $(".f_name").empty();
                    $(".m_name").empty();
                    $(".l_name").empty();
                    $(".appointment_date").empty();
                    $(".p_custommsg").empty();
                    $(".p_apptype3").empty();
                    $(".dob").empty();
                    $(".phone_no").empty();
                    $(".e_mail").empty();
                    $(".m_name").empty();
                    $(".dob").empty();
                });


                $(".submit_add_div").click(function () {
                    var controller = "home";
                    var submit_function = "add_client";
                    var form_class = "add_form";
                    var success_alert = "Client added successfully ... :) ";
                    var error_alert = "Ooops , Something really bad has happened (:";
                    submit_data(controller, submit_function, form_class, success_alert, error_alert);

                    $(".f_name").empty();
                    $(".l_name").empty();
                    $(".phone_no").empty();
                    $(".e_mail").empty();
                    $(".m_name").empty();
                    $(".dob").empty();
                });
            });
        </script>




        <!--END MAIN WRAPPER -->