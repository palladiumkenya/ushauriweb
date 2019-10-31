<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
             <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Clients</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->


        <ul class="nav">
            <li class="nav-item">

                <a class="nav-link"  href="#new_client_tab" data-toggle="tab">New Client</a>
            </li>
            <li class="nav-item">

                <a class="nav-link"  href="#transfer_client_tab" data-toggle="tab">Transfer Client</a>
            </li>

        </ul>








        <div class="tab-content clearfix">



            <div class="  tab-pane  new_client_tab" id="new_client_tab" >
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">New Client Form</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-body">

                                    <!-- New Client Form starts here -->



                                    <form class="add_new_form" id="add_new_form">




                                        <?php
                                        $csrf = array(
                                            'name' => $this->security->get_csrf_token_name(),
                                            'hash' => $this->security->get_csrf_hash()
                                        );
                                        ?>

                                        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                        <input type="hidden" name="registration_type" value="New" class="form-control registration_type" id="registration_type " readonly=""/>
                                        <h2> Client Personal Information </h2> 
                                        <label class='control-label'>Unique Patient Number* (Unique value : )</label><span style="color: red">*</span>
                                        <input type="hidden" readonly="" value="<?php echo $this->session->userdata('facility_id'); ?>" class="form-control mfl_code" id="mfl_code" name="mfl_code" placeholder="MFL Code"/>
                                        <input class='form-control new_clinic_number input-rounded input-sm' placeholder="Enter Unique Patient Number"  type='text' required='' name='clinic_number' id='clinic_number' >
                                        <br>
                                        <button type="button" class="new_check_clinic_number btn btn-default" id="new_check_clinic_number">Check Clinic No : </button>

                                        <div class="new_continue_div" style="display: none;">
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <label class='control-label'>First Name : <span style="color: red">*</span> </label> 
                                                    <input class='form-control fname  input-rounded input-sm' placeholder="First Name" required="" type='text' name='fname' id='fname' >

                                                </div>


                                                <div class="col-md-6">
                                                    <label class='control-label'>Middle Name : <span style="color: red">*</span> </label>  
                                                    <input type='text' class='form-control mname input-rounded input-sm' placeholder="Middle Name"  name='mname' id='mname'>

                                                </div>

                                            </div>

                                            <div class="row p-t-20">  <div class="col-md-6">
                                                    <label class='control-label'>Last Name : <span style="color: red">*</span> </label>  
                                                    <input class='form-control lname input-rounded input-sm'  placeholder="Last Name" type='text' name='lname' id='lname' >

                                                </div>
                                                <div class="col-md-6">
                                                    <label class='control-label'> Date of Birth: <span style="color: red">*</span> </label> 
                                                    <input class='form-control dob input-rounded input-sm' readonly="" required="" placeholder="Date of Birth" type='text' name='p_year' id='dob' >


                                                </div>   </div>
                                            <div class="row p-t-20">     <div class="col-md-6">
                                                    <label class='control-label'>  Gender : <span style="color: red">*</span> </label> 
                                                    <select class='form-control gender input-rounded input-sm' required="" name='gender' id='gender'>
                                                        <option value=''>Please select </option>
                                                        <?php foreach ($genders as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class='control-label'>  Marital Status : <span style="color: red">*</span> </label> 
                                                    <select class='form-control marital input-rounded input-sm ' required="" name='marital' id='marital'>
                                                        <option value=''>Please select </option>
                                                        <?php foreach ($maritals as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->marital; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>

                                                </div>     </div>
                                            <div class="row p-t-20">  


                                                <div class="col-md-6">

                                                    <label class='control-label'> Treatment: <span style="color: red">*</span> </label> 

                                                    <select class='form-control condition input-rounded input-sm ' required="" name='condition' id='condition'>
                                                        <option value=''> </option>

                                                        <option value='ART'>ART </option>
                                                        <option value='Pre ART'>Pre ART </option>
                                                    </select>

                                                </div>



                                                <div class="col-md-6">
                                                    <label class='control-label'> HIV Enrollment Date: <span style="color: red">*</span> </label> 
                                                    <input class='form-control enrollment_date  dob input-rounded input-sm' readonly="" required="" placeholder="Date enrolled to HIV Care" type='text' name='enrollment_date' id='enrollment_date' >

                                                </div>

                                            </div>
                                            <div class="row p-t-20"> 


                                                <div class="col-md-6">


                                                    <label class='control-label'> ART Start Date: <span style="color: red">*</span> </label> 
                                                    <input class='form-control ART_Start_date  dob input-rounded input-sm' readonly=""  placeholder="ART Start Date" type='text' name='art_date' id='art_date' >

                                                </div>


                                                <div class="col-md-6">

                                                    <?php
                                                    $facility_id = $this->session->userdata('facility_id');
                                                    if (empty($facility_id)) {
                                                        ?>
                                                        <label class='control-label'>  Facility Attached : <span style="color: red">*</span> </label>  
                                                        <select class='form-control facilities input-rounded input-sm ' required="" name='facilities' id='facilities' >
                                                            <option value=''>Please select </option>
                                                            <?php foreach ($facilities as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->facility_id; ?>"><?php echo " " . $value->facility_name . " => " . $value->mfl_code . "   " . $value->county_name . "  " . $value->sub_county_name; ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <input readonly="" type="text" name="facilities" class="form-control facilities" id="facilities" value="<?php echo $facility_id; ?>" />
                                                        <?php
                                                    }
                                                    ?>

                                                </div>

                                            </div>



                                            <div class="row p-t-20"> 


                                                <div class="col-md-6">

                                                    <label class='control-label'>Cell Phone Number (Own) <span style="color: red">*</span> </label> 
                                                    <input class='form-control mobile input-rounded input-sm' value="07" placeholder="Client Phone No e.g 0712345678 "   type='text' name='mobile' id='mobile'   >

                                                </div>



                                                <div class="col-md-6">
                                                    <label class='control-label'>  Communication language  : <span style="color: red">*</span> </label> 
                                                    <select class='form-control lang input-rounded input-sm' required="" name='lang' id='lang'>
                                                        <option value=''>Please select </option>
                                                        <?php foreach ($langauges as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>

                                                </div></div>

                                            <div class="row p-t-20"> 
                                                <div class="col-md-6">
                                                    <label class='control-label'>  Enable Message Alerts? <span style="color: red">*</span> </label> 
                                                    <select class='form-control smsenable input-rounded input-sm' required="" name='smsenable' id='smsenable'>
                                                        <option value=''>Please select : </option>
                                                        <option value='Yes'> Yes </option>
                                                        <option value='No'> No </option>
                                                    </select>

                                                </div>

                                                <div class="col-md-6">

                                                    <div class="consent_date_div" id="consent_date_div" style="display: none;">

                                                        <label class='control-label'> Consent Date: <span style="color: red">*</span>  </label> 
                                                        <input class='form-control consent_date dob input-rounded input-sm' readonly=""  type='text' name='consent_date'  placeholder='Please enter Consent Date' id='consent_date'>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row p-t-20"> 
                                                <div class="col-md-6">

                                                    <label class='control-label'>  Receive Weekly Motivational/Informative Message Alerts? <span style="color: red">*</span> </label> 
                                                    <select class='form-control motivational_enable input-rounded input-sm' required="" name='motivational_enable' id='motivational_enable'>
                                                        <option value=''>Please select : </option>
                                                        <option value='Yes'> Yes </option>
                                                        <option value='No'> No </option>
                                                    </select>

                                                </div>


                                                <div class="col-md-6">

                                                    <label class='control-label'>  Preferred Messaging Time : <span style="color: red">*</span> </label> 
                                                    <select class='form-control time input-rounded input-sm' required="" name='time' id='time'>
                                                        <option value=''>Please select </option>
                                                        <?php foreach ($times as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="row p-t-20"> 
                                                <div class="col-md-6">
                                                    <label class='control-label'>  Client Status <span style="color: red">*</span> </label> 
                                                    <select class='form-control client_status input-rounded input-sm' required="" name='status' id='status'>
                                                        <option value=''>Please Select : </option>
                                                        <option value='Active'> Active </option>
                                                        <option value="Disabled">Disabled</option>
                                                        <option value="Transfered Out">Transfered Out</option>
                                                        <option value='Deceased'> Deceased </option>
                                                    </select>

                                                </div>
                                                <div class="col-md-6">

                                                    <div id="transfer_date_div" class="transfer_date_div" style="display: none;">
                                                        <label class='control-label'>Transfer Date</label> 
                                                        <input class='form-control transfer_date date input-rounded input-sm'  readonly="" type='text' name='transfer_date' placeholder="Please enter Trnasfer Date " id='edit_transfer_date'>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>



                                        <!--    /*     * ********************End of personal enrollment info section ******************************************** */
                                        
                                            /*     * ********************Start  enrollment appointment info section ******************************************** */-->

                                        <div class="new_continue_div" style="display: none;">

                                            <div class=''>
                                                <section class='panel'>
                                                    <header class='panel-heading'>  </header>
                                                    <div class='panel-body'>  <div class='form'>
                                                            <h2> Client Appointment Information </h2> 
                                                            <label class='control-label'> Next Appointment Date </lable> 
                                                                <input type='text' class='form-control appointment_date input-rounded input-sm ' readonly="" placeholder="Next Appointment Date " name='apptdate' id='appointment_date'/> 
                                                                <input type='hidden' name='appointment_type' id='appointment_type' value='Appointment' class='form-control input-rounded input-sm' readonly/>
                                                                <!--<label class='control-label'> Appointment Status </label>-->
                                                                <input type='hidden' class='form-control input-rounded input-sm' placeholder="Booked" name='appstatus' id='appstatus' value='Booked'/>

                                                                <label class='control-label'> Appointment Type : </label>

                                                                <select class='form-control input-rounded input-sm' name='p_apptype1' id='p_apptype1' >
                                                                    <option value=''>Please Select : </option>
                                                                    <?php
                                                                    foreach ($app_types as $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>


                                                                </select>




                                                                </br> 

                                                                <button type="submit" class="submit_add_new_div btn btn-success btn-small" id="submit_add_new_div">Add Client</button>
                                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>

                                                        </div>  </div></section></div>

                                        </div> 



                                    </form>





                                    <!-- New Client Form ends here  -->



                                </div>





                            </div>
                        </div>
                    </div>


                </div>
            </div>


            <div class=" tab-pane transfer_client_tab" id="transfer_client_tab" >
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Transfer Client form</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-body">
                                    <!-- Transfer Client Form starts here -->

                                    <form class="add_transfer_form" id="add_transfer_form">


                                        <hr>

                                        <div class='form'>







                                            <?php
                                            $csrf = array(
                                                'name' => $this->security->get_csrf_token_name(),
                                                'hash' => $this->security->get_csrf_hash()
                                            );
                                            ?>

                                            <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                            <input type="hidden" name="registration_type" value="Transfer" class="form-control registration_type input-rounded input-sm" id="registration_type " readonly=""/>
                                            <input type="text" name="facility_id" class="transfer_mfl_no form-control input-rounded input-sm" placeholder="Please enter MFL No : " id="transfer_mfl_no"/>
                                            <span class="facility_loading" id="facility_loading" style="display: none;">Please wait , loading Facility Name : </span>
                                            <span class="facility_name" id="facility_name"></span>
                                            <br>


                                            <div class="load_client_details" id="load_client_details" style="display: none;">








                                                <h2> Client Personal Information </h2> 
                                                <label class='control-label'>Unique Patient Number* (Unique value : )</label><span style="color: red">*</span>
                                                <input class='form-control transfer_clinic_number input-rounded input-sm' placeholder="Enter Unique Patient Number"  type='text' required='' name='clinic_number' id='clinic_number' >
                                                <br>
                                                <button type="button" class="transfer_check_clinic_number btn btn-default" id="transfer_check_clinic_number">Check Clinic No : </button>

                                                <div class="transfer_continue_div" style="display: none;">

                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'>First Name </label> <span style="color: red">*</span>
                                                            <input class='form-control transfer_fname input-rounded input-sm' placeholder="First Name" required="" type='text' name='fname' id='fname' >

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class='control-label'>Middle Name </label> <span style="color: red">*</span> 
                                                            <input type='text' class='form-control transfer_mname input-rounded input-sm' placeholder="Middle Name" required="" name='mname' id='mname'>

                                                        </div>

                                                    </div>


                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'>Last Name</label>  <span style="color: red">*</span>
                                                            <input class='form-control transfer_lname input-rounded input-sm'  placeholder="Last Name" type='text' name='lname' id='lname' >

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class='control-label'> Date of Birth: </label> <span style="color: red">*</span>
                                                            <input class='form-control transfer_dob input-rounded input-sm' required="" readonly="" placeholder="Date of Birth" type='text' name='p_year' id='dob' >

                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'>  Gender : <span style="color: red">*</span> </label> 
                                                            <select class='form-control transfer_gender input-rounded input-sm' required="" name='gender' id='gender'>
                                                                <option value=''>Please select </option>
                                                                <?php foreach ($genders as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                <?php }
                                                                ?>
                                                            </select>    
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class='control-label'>  Marital Status : <span style="color: red">*</span> </label> 
                                                            <select class='form-control transfer_marital input-rounded input-sm' required="" name='marital' id='marital'>
                                                                <option value=''>Please select </option>
                                                                <?php foreach ($maritals as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->marital; ?></option>
                                                                <?php }
                                                                ?>
                                                            </select>

                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'> Treatment: </label> <span style="color: red">*</span>

                                                            <select class='form-control transfer_condition input-rounded input-sm' required="" name='condition' id='condition'>
                                                                <option value=''> </option>

                                                                <option value='ART'>ART </option>
                                                                <option value='Pre ART'>Pre ART </option>
                                                            </select> 
                                                        </div>

                                                        <div class="col-md-6">

                                                            <label class='control-label'> HIV Enrollment Date: </label> <span style="color: red">*</span>
                                                            <input class='form-control transfer_enrollment_date  dob input-rounded input-sm' readonly="" required="" placeholder="Date enrolled to HIV Care" type='text' name='enrollment_date' id='enrollment_date' >


                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">

                                                            <label class='control-label'> ART Start Date: </label> 
                                                            <input class='form-control transfer_ART_Start_date  dob input-rounded input-sm' readonly=""  placeholder="ART Start Date" type='text' name='art_date' id='art_date' >



                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class='control-label'>  Facility Attached : </label>  <span style="color: red">*</span> 
                                                            <?php
                                                            $facility_id = $this->session->userdata('facility_id');
                                                            if (empty($facility_id)) {
                                                                ?>
                                                                <select class='form-control transfer_facilities input-rounded input-sm ' required="" name='facilities' id='facilities' >
                                                                    <option value=''>Please select </option>
                                                                    <?php foreach ($facilities as $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value->facility_id; ?>"><?php echo " " . $value->facility_name . " => " . $value->mfl_code . "   " . $value->county_name . "  " . $value->sub_county_name; ?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <input readonly="" type="text" name="facilities" class="form-control facilities input-rounded input-sm" id="facilities" value="<?php echo $facility_id; ?>" />
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'>Cell Phone Number (Own) <span style="color: red">*</span> </label> 
                                                            <input class='form-control transfer_mobile input-rounded input-sm' required="" placeholder="Client Phone No e.g 0712345678" pattern="^(([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+$" type='text' name='mobile' id='mobile'   >

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class='control-label'> Alternative Phone Number </label>
                                                            <input class='form-control transfer_altmobile input-rounded input-sm' type='text' placeholder="Alternative Phone No e.g 0712345678" pattern="^(([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+$" name='altmobile' id='altmobile'>

                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'>If shared, name</label> 
                                                            <input class='form-control transfer_sharename input-rounded input-sm' type='text' placeholder="Shared Name " name='sharename' id='sharename'>

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class='control-label'>  Communication lang : <span style="color: red">*</span> </label> 
                                                            <select class='form-control transfer_lang input-rounded input-sm' required="" name='lang' id='lang'>
                                                                <option value=''>Please select </option>
                                                                <?php foreach ($langauges as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                <?php }
                                                                ?>
                                                            </select>   
                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'>  Enable Message Alerts? <span style="color: red">*</span> </label> 
                                                            <select class='form-control transfer_smsenable input-rounded input-sm' required="" name='smsenable' id='smsenable'>
                                                                <option value=''>Please select : </option>
                                                                <option value='Yes'> Yes </option>
                                                                <option value='No'> No </option>
                                                            </select>   
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="transfer_consent_date_div" id="transfer_consent_date_div" style="display: none;">

                                                                <label class='control-label'> Consent Date: </label> 
                                                                <input class='form-control transfer_consent_date dob input-rounded input-sm' readonly=""  type='text' name='consent_date'  placeholder='Please enter Consent Date' id='transfer_consent_date'>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'>  Client Status <span style="color: red">*</span> </label> 
                                                            <select class='form-control transfer_client_status input-rounded input-sm' required="" name='status' id='status'>
                                                                <option value=''>Please Select : </option>
                                                                <option value='Active'> Active </option>
                                                                <option value="Disabled">Disabled</option>
                                                                <option value='Deceased'> Deceased </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">

                                                        </div>

                                                    </div>
                                                    <div class="row p-t-20"> 


                                                        <div class="col-md-6">
                                                            <label class='control-label'> Next Appointment Date </label> 
                                                            <input type='text' class='form-control transfer_appointment_date input-rounded input-sm' placeholder="Next Appointment Date " name='apptdate' id='appointment_date'/> 
                                                            <input type='hidden' name='appointment_type' id='appointment_type' value='Appointment' class=' input-rounded input-sm transfer_appointment_type form-control' readonly/>

                                                        </div>

                                                        <div class="col-md-6">
                                                            <input type='hidden' class='form-control transfer_appstatus' placeholder="Booked" name='appstatus' id='appstatus' value='Booked'/>

                                                            <label class='control-label'> Appointment Type : </label>
                                                            <select class='form-control transfer_p_apptype1 input-rounded input-sm '  name='p_apptype1' id='p_apptype1' >
                                                                <option value=''>Please Select : </option>
                                                                <?php
                                                                foreach ($app_types as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <option value="Re-fill">Re-fill </option>
                                                                <option value="Clinical-Review">Clinical-Review </option>
                                                                <option value="Enhanced Adherence">Enhanced Adherence </option>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <br>
                                                    <button type="submit" class="submit_add_transfer_div btn btn-success btn-small" id="submit_add_transfer_div">Add Client</button>
                                                    <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>




                                                </div>









                                            </div>




                                        </div> 
                                        <!--    /*     * ********************End of personal enrollment info section ******************************************** */
                                        
                                          


                                    </form>




                                        <!-- Transfer Client Form ends here  --> 
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>










        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 All rights reserved. Template designed by <a href="https://colorlib.com">Colorlib</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->






<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>



<script>
    var val = jQuery.noConflict();
    val("#commentForm").validate();</script>




<script type="text/javascript">

    function countChar(val) {
        var len = val.value.length;
        if (len >= 160) {
            val.value = val.value.substring(0, 160);
            sweetAlert("You've exceeded the  number of characters you are required to type...", "", "warning");
        } else {
            $('#charNum').text(160 - len);
        }
    }
    ;
    $(document).ready(function () {

        $(".edit_status").change(function () {
            var status_value = this.value;

            if (status_value = "Transfer Out") {
                $(".transfer_date_div").show();
            } else {
                $(".transfer_date").empty();
                $(".transfer_date_div").hide();
            }

        });





        $(".smsenable").change(function () {
            var status_value = this.value;

            if (status_value === "Yes") {
                $(".consent_date_div").show();
                $(".consent_date").empty();
                var consent_date = "";
                $(".consent_date_div").append(consent_date);
            } else {
                $(".consent_date").empty();
                $(".consent_date_div").hide();
            }

        });




        $(".transfer_smsenable").change(function () {
            var status_value = this.value;

            if (status_value === "Yes") {
                $(".transfer_consent_date_div").show();
                $(".transfer_consent_date").empty();
                var consent_date = "";
                $(".transfer_consent_date_div").append(consent_date);
            } else {
                $(".consent_date").empty();
                $(".consent_date_div").hide();
            }

        });



        $('.dob').on('changeDate', function (event) {

        });

        $('select').on('change', function () {

            var input_age = $("#dob").val();
            var age = getAge(input_age);

            if ((age >= 0) && (age <= 9)) {

                $('#group option[value=9]').attr('selected', 'selected');

            } else if ((age >= 10) && (age <= 19)) {

                $('#group option[value=3]').attr('selected', 'selected');

            } else if (age >= 20) {

                $('#group option[value=7]').attr('selected', 'selected');

            }
        });

        $('input').on('keyup', function () {

            var input_age = $("#dob").val();
            var age = getAge(input_age);

            if ((age >= 0) && (age <= 9)) {

                $('#group option[value=9]').attr('selected', 'selected');

            } else if ((age >= 10) && (age <= 19)) {

                $('#group option[value=3]').attr('selected', 'selected');

            } else if (age >= 20) {

                $('#group option[value=7]').attr('selected', 'selected');

            }
        });
        function getAge(dateString) {
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }


        $(".gender").change(function () {
            var gender = this.value;
            if (gender == "1") {
                $(".group").append("<option value='1'>PMTCT</option>");
                $(".group").append("<option value='8'>Lactating Woman</option>");
            } else if (gender == "2") {
                $(".group option[value='1']").remove();
                $(".group option[value='8']").remove();
            }
        });
        $(".transfer_mfl_no").keyup(function () {
            var transfer_mfl_no = $(".transfer_mfl_no").val();
            if (transfer_mfl_no.length != 5) {

            } else {

                var controller = "home";
                var get_function = "get_transfer_mfl_no";
                $(".facility_loading").show();
                $(".facility_name").empty();
                $.ajax({
                    type: "GET",
                    async: true,
                    url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + transfer_mfl_no,
                    dataType: "JSON",
                    success: function (response) {
                        $(".facility_loading").hide();
                        $('.loader').hide();
                        var isempty = jQuery.isEmptyObject(response);
                        if (isempty) {
                            $(".facility_name").empty();
                        } else {

                            $.each(response, function (i, value) {


                                var facility_name = "You selected : " + value.name;
                                $(".facility_name").append(facility_name);
                                $(".load_client_details").show();
                            });
                        }





                    }, error: function (data) {
                        $('.loader').hide();
                        sweetAlert("Oops...", "" + error_alert + "", "error");
                    }

                });
            }





        });
        $(".clinic_number").keyup(function () {
            $(".continue_div").hide();
        });
        $(".new_check_clinic_number").click(function () {
            $('.loader').show();
            var clinic_number = $(".new_clinic_number").val();
            var mfl_code = $(".mfl_code").val();
            var upn = mfl_code + clinic_number;
            var controller = "home";
            var get_function = "check_clinic_no";
            var error_alert = " we could not process your request right now (:";
            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + upn,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    var isempty = jQuery.isEmptyObject(response);
                    if (isempty) {
                        $(".fname").val("");
                        $(".mname").val("");
                        $(".lname").val("");
                        $("#dob").val("");
                        $(".frequency").val("");
                        $(".mobile").val("");
                        $(".altmobile").val("");
                        $(".sharename").val("");
                        $(".appointment_date").val("");
                        $(".p_apptype3").val("");
                        $(".p_custommsg").val("");
                        $(".new_continue_div").show();
                    } else {
                        $.each(response, function (i, value) {


                            var client_name = value.f_name + " " + value.m_name + " " + value.l_name;
                            swal("Error", "Clininc number already exists and is registered under : " + client_name + " ", "warning");
                            $(".new_continue_div").hide();
                        });
                    }





                }, error: function (data) {
                    $('.loader').hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");
                }

            });
        });
        $(".transfer_check_clinic_number").click(function () {
            $('.loader').show();
            var mfl_no = $(".transfer_mfl_no").val();
            var clinic_number = $(".transfer_clinic_number").val();
            var upn = mfl_no + clinic_number;
            var controller = "home";
            var get_function = "check_clinic_no";
            var error_alert = " we could not process your request right now (:";
            $.ajax({
                type: "GET", async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + upn,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    var isempty = jQuery.isEmptyObject(response);
                    if (isempty) {

//                        swal("Error", "Clininc number  : " + upn + " was not found in the  system ", "warning");




                        swal({
                            title: "Clinic No : " + upn + " was not found in the System , do you want to add it ? ",
                            text: " ",
                            html: true,
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            closeOnConfirm: true
                        },
                                function () {
                                    $('.loader').show();
                                    $('.loader').hide();
                                    $(".transfer_continue_div").show();
                                });









                    } else {
                        $.each(response, function (i, value) {




                            $('.loader').show();
                            //get data
                            var data_id = $(this).closest('tr').find('input[name="client_id"]').val();
                            var controller = "home";
                            var get_function = "get_client_transfer_data";
                            var error_alert = "Sorry , we could not process your request right now (:";
                            $.ajax({
                                type: "GET",
                                async: true,
                                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + upn,
                                dataType: "JSON",
                                success: function (response) {
                                    $('.loader').hide();
                                    $.each(response, function (i, value) {



                                        $('.transfer_client_id').val(value.client_id);
                                        //$('.transfer_clinic_number').val(value.clinic_number);
                                        $('.transfer_fname').val(value.f_name);
                                        $('.transfer_mname').val(value.m_name);
                                        $('.transfer_lname').val(value.l_name);
                                        $('.transfer_dob').val(value.dob);
                                        //$('#edit_condition').val(value.client_status);


                                        $('.transfer_condition option[value="' + value.client_status + '"]').attr("selected", "selected");
                                        $('.transfer_group option[value=' + value.group_id + ']').attr("selected", "selected");
                                        $('.transfer_facilities option[value=' + value.facility_id + ']').attr("selected", "selected");
                                        $('.transfer_frequency').val(value.txt_frequency);
                                        $('.transfer_time option[value=' + value.txt_time + ']').attr("selected", "selected");
                                        $('.transfer_mobile').val(value.phone_no);
                                        $('.transfer_altmobile').val(value.alt_phone_no);
                                        $('.transfer_sharename').val(value.shared_no_name);
                                        $('.transfer_lang option[value=' + value.language_id + ']').attr("selected", "selected");
                                        $('.transfer_smsenable option[value=' + value.smsenable + ']').attr("selected", "selected");
                                        $('.transfer_appointment_date').val(value.appntmnt_date);
                                        $('.transfer_client_status option[value=' + value.status + ']').attr("selected", "selected");
                                        $('.transfer_gender option[value=' + value.gender + ']').attr("selected", "selected");
                                        $('.transfer_marital option[value=' + value.marital + ']').attr("selected", "selected");
                                        $('.transfer_motivational_enable option[value=' + value.motivational_enable + ']').attr("selected", "selected");
                                        $('.transfer_wellnessenable option[value=' + value.wellness_enable + ']').attr("selected", "selected");




                                        var enrollment_date = value.enrollment_date;
                                        var res = enrollment_date.substring(0, 10);
                                        var new_enrollment_date = res.split("-").reverse().join("/");

                                        $('.transfer_enrollment_date').val(new_enrollment_date);

                                        var art_date = value.art_date;
                                        var art_res = art_date.substring(0, 10);
                                        var new_art_date = art_res.split("-").reverse().join("/");


                                        $('.transfer_ART_Start_date').val(new_art_date);
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
                            var error_alert = "Sorry , we could not process your request right now (:";
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

                                        $('#edit_created_at').val(value.created_at);
                                        $('#edit_timestamp').val(value.timestamp);
                                    });
                                }, error: function (data) {
                                    $('.loader').hide();
                                    sweetAlert("Oops...", "" + error_alert + "", "error");
                                }

                            });
                            $(".transfer_continue_div").show();
                        });
                    }





                }, error: function (data) {
                    $('.loader').hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");
                }

            });
        });
        $(".cancel_add_client").click(function () {

            $(".clinic_number").val('');
            $(".fname").val('');
            $(".lname").val('');
            $(".mname").val('');
            $(".phone_no").val('');
            $(".dob").val('');
            $(".frequency").val('');
            $(".mobile").val('');
            $(".altmobile").val('');
            $(".sharename").val('');
            $(".frequency").val('');
            $(".appointment_date").val('');
            $(".p_apptype3").val('');
            $(".p_custommsg").val('');
        });
        $(".submit_add_transfer_div").click(function () {

            var controller = "home";
            var submit_function = "transfer_client";
            var form_class = "add_transfer_form";
            var success_alert = "Client added successfully ... :) ";
            var error_alert = "Sorry , we could not process your request right now (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
//





            $(".clinic_number").empty();
            $(".fname").empty();
            $(".lname").empty();
            $(".mname").empty();
            $(".phone_no").empty();
            $(".dob").empty();
            $(".frequency").empty();
            $(".mobile").empty();
            $(".altmobile").empty();
            $(".sharename").empty();
            $(".frequency").empty();
            $(".appointment_date").empty();
            $(".p_apptype3").empty();
            $(".p_custommsg").empty();
            var enrollment_date = $(".ART_Start_date").val();
            var ART_start_date = $(".enrollment_date").val();
            var enrollments_date = new Date(enrollment_date).getTime();
            var ART_date = new Date(ART_start_date).getTime();
            if (new Date(enrollment_date).getTime() <= new Date(ART_start_date).getTime())
            {//compare end <=, not >=.get
                //your code here

                //  swal("Success", "Enrollment date is less than ART Date  ", "success");




            } else {
                // swal("Warning", "Enrollment date cannot be greater than ART Date  ", "warning");

            }





        });
        $(".submit_add_new_div").click(function () {

            var controller = "home";
            var submit_function = "add_client";
            var form_class = "add_new_form";
            var success_alert = "Client added successfully ... :) ";
            var error_alert = "Sorry , we could not process your request right now (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);



            $(".clinic_number").empty();
            $(".fname").empty();
            $(".lname").empty();
            $(".mname").empty();
            $(".phone_no").empty();
            $(".dob").empty();
            $(".frequency").empty();
            $(".mobile").empty();
            $(".altmobile").empty();
            $(".sharename").empty();
            $(".frequency").empty();
            $(".appointment_date").empty();
            $(".p_apptype3").empty();
            $(".p_custommsg").empty();
            var enrollment_date = $(".ART_Start_date").val();
            var ART_start_date = $(".enrollment_date").val();
            var enrollments_date = new Date(enrollment_date).getTime();
            var ART_date = new Date(ART_start_date).getTime();
            if (new Date(enrollment_date).getTime() <= new Date(ART_start_date).getTime())
            {//compare end <=, not >=.get

            } else {
                // swal("Warning", "Enrollment date cannot be greater than ART Date  ", "warning");

            }


        });
    });
</script>














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


                    <div class="container" id="exTab1">
                        <ul  class="nav nav-pills">
                            <li class="active">
                                <a  href="#new_client" data-toggle="tab">New Client</a>
                            </li>
                            <li><a href="#transfer_in" data-toggle="tab">Transfer In</a>
                            </li>


                        </ul>

                        <div class="tab-content clearfix">
                            <div class="tab-pane active" id="new_client">




                            </div>
                            <div class="tab-pane " id="transfer_in">





                            </div>


                        </div>
                    </div>












                    <div class="panel-footer">
                        Get   in touch: support.tech@mhealthkenya.org                             </div>

                </div>        












            </div>



        </div>
    </div>
    <!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>


<!--END MAIN WRAPPER -->


