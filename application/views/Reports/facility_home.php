
<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Facility Home</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->










        <div class="row" >
            <div class="row padding-left quick_links_div" style="padding-left: 10px;"> 
                <button class="btn btn-primary add_client_link" style="padding-left: 10px;" name="add_client_link" id="add_client_link"> <icon class="icon-plus-sign"></icon>Add Client</button>

                <!--<a class="btn btn-primary add_client_link" style="padding-left: 10px;" id="add_client_link"> <icon class="icon-plus-sign"></icon>Add Client </a>-->
        <!--        <button class="add_appointment_btn btn btn-primary btn-small" id="add_appointment_btn"><i class="icon-plus-sign"></i>Add Appointment</button>-->

            </div>



            <div class="add_client_div col-lg-14" style="display: none;">

<!--                <a class="btn btn-primary go_back_link" style="padding-left: 10px;" id="go_back_link"> <icon class="fa fa-backward"></icon>View Dashboard </a>-->


                <button class="btn btn-default go_back_link" name="go_back_link" id="go_back_link"> <i class="fa fa-backward"></i> View Dashboard</button>


                <div class="container col-lg-14" id="exTab1">
                    <ul  class="nav nav-pills">
                        <li class="active">
                            <a  href="#new_client" data-toggle="tab">New Client</a>
                        </li>
                        <li><a href="#transfer_in" data-toggle="tab">Transfer In</a>
                        </li>


                    </ul>

                    <div class="tab-content clearfix col-lg-14">
                        <div class="tab-pane active col-lg-14" id="new_client">

                            <form class="add_new_form" id="add_new_form">
                                <div class='row'>
                                    <div class='col-xs-14 form-group'>





                                        <section class='panel'>
                                            <header class='panel-heading'> 
                                                <h4> Client Personal Information </h4>  
                                            </header>
                                            <div class='panel-body'>


                                                <div class='col-xs-4 form-group'>


                                                    <div class='form'>

                                                        <?php
                                                        $csrf = array(
                                                            'name' => $this->security->get_csrf_token_name(),
                                                            'hash' => $this->security->get_csrf_hash()
                                                        );
                                                        ?>

                                                        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                                        <input type="hidden" name="registration_type" value="New" class="form-control registration_type input-rounded input-sm" id="registration_type " readonly=""/>

                                                        <label class='control-label'>Unique Patient Number* (Unique value : )</label><span style="color: red">*</span>
                                                        <input type="text" readonly="" value="<?php echo $this->session->userdata('facility_id'); ?>" class="form-control mfl_code input-rounded input-sm" id="mfl_code" name="mfl_code" placeholder="MFL Code"/>
                                                        <input class='form-control add_new_clinic_number input-rounded input-sm ' placeholder="Enter Unique Patient Number"  type='text' required='' name='clinic_number' id='clinic_number' >
                                                        <br>
                                                        <button type="button" class="add_new_check_clinic_number btn btn-default" id="add_new_check_clinic_number">Check Clinic No : </button>

                                                        <div class="new_continue_div" style="display: none;">



                                                            <label class='control-label'>First Name </label> <span style="color: red">*</span>
                                                            <input class='form-control fname input-rounded input-sm' placeholder="First Name" required="" type='text' name='fname' id='fname' >

                                                            <label class='control-label'>Middle Name </label> <span style="color: red">*</span> 
                                                            <input type='text' class='form-control mname input-rounded input-sm' placeholder="Middle Name"  name='mname' id='mname'>

                                                            <label class='control-label'>Last Name</label>  <span style="color: red">*</span>
                                                            <input class='form-control lname input-rounded input-sm'  placeholder="Last Name" type='text' name='lname' id='lname' >

                                                            <label class='control-label'> Date of Birth: </label> <span style="color: red">*</span>
                                                            <input class='form-control dob input-rounded input-sm' readonly="" required="" placeholder="Date of Birth" type='text' name='p_year' id='dob' >













                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="col-xs-4 form-group">



                                                    <div class="new_continue_div" style="display: none;">

                                                        <div class=''>


                                                            <div class='form'>






                                                                <label class='control-label'>  Gender : <span style="color: red">*</span> </label> 
                                                                <select class='form-control gender input-rounded input-sm' required="" name='gender' id='gender'>
                                                                    <option value=''>Please select </option>
                                                                    <?php foreach ($genders as $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>


                                                                <label class='control-label'>  Marital Status : <span style="color: red">*</span> </label> 
                                                                <select class='form-control marital input-rounded input-sm' required="" name='marital' id='marital'>
                                                                    <option value=''>Please select </option>
                                                                    <?php foreach ($maritals as $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->marital; ?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>


                                                                <label class='control-label'> Treatment: </label> <span style="color: red">*</span>

                                                                <select class='form-control condition input-rounded input-sm' required="" name='condition' id='condition'>
                                                                    <option value=''> </option>

                                                                    <option value='ART'>ART </option>
                                                                    <option value='Pre ART'>Pre ART </option>
                                                                </select>




                                                                <label class='control-label'> HIV Enrollment Date: </label> <span style="color: red">*</span>
                                                                <input class='form-control enrollment_date  dob input-rounded input-sm' readonly="" required="" placeholder="Date enrolled to HIV Care" type='text' name='enrollment_date' id='enrollment_date' >




                                                                <label class='control-label'> ART Start Date: </label> 
                                                                <input class='form-control ART_Start_date  dob input-rounded input-sm' readonly=""  placeholder="ART Start Date" type='text' name='art_date' id='art_date' >





                                                                <label class='control-label'>Cell Phone Number (Own) <span style="color: red">*</span> </label> 
                                                                <input class='form-control mobile input-rounded input-sm' value="07" placeholder="Client Phone No e.g 0714345678 "   type='text' name='mobile' id='mobile'   >




                                                            </div>




                                                        </div>

                                                    </div> 













                                                </div>


                                                <div class='col-xs-4 form-group'>
                                                    <div class="new_continue_div" style="display: none;">

                                                        <div class=''>


                                                            <div class='form'>







                                                                <?php
                                                                $facility_id = $this->session->userdata('facility_id');
                                                                if (empty($facility_id)) {
                                                                    ?>
                                                                    <label class='control-label'>  Facility Attached : </label>  <span style="color: red">*</span> 
                                                                    <select class='form-control facilities input-rounded input-sm' required="" name='facilities' id='facilities' >
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
                                                                    <input readonly="" type="hidden" name="facilities" class="form-control facilities input-rounded input-sm" id="facilities" value="<?php echo $facility_id; ?>" />
                                                                    <?php
                                                                }
                                                                ?>








                                                                <label class='control-label'>  Communication language  : <span style="color: red">*</span> </label> 
                                                                <select class='form-control lang input-rounded input-sm' required="" name='lang' id='lang'>
                                                                    <option value=''>Please select </option>
                                                                    <?php foreach ($langauges as $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>


                                                                <label class='control-label'>  Enable Message Alerts? <span style="color: red">*</span> </label> 
                                                                <select class='form-control smsenable input-rounded input-sm' required="" name='smsenable' id='smsenable'>
                                                                    <option value=''>Please select : </option>
                                                                    <option value='Yes'> Yes </option>
                                                                    <option value='No'> No </option>
                                                                </select>



                                                                <div class="consent_date_div" id="consent_date_div" style="display: none;">

                                                                    <label class='control-label'> Consent Date: </label> 
                                                                    <input class='form-control consent_date dob input-rounded input-sm ' readonly=""  type='text' name='consent_date'  placeholder='Please enter Consent Date' id='consent_date'>
                                                                </div>


                                                                <label class='control-label'>  Receive Weekly Motivational/Informative Message Alerts? <span style="color: red">*</span> </label> 
                                                                <select class='form-control motivational_enable input-rounded input-sm' required="" name='motivational_enable' id='motivational_enable'>
                                                                    <option value=''>Please select : </option>
                                                                    <option value='Yes'> Yes </option>
                                                                    <option value='No'> No </option>
                                                                </select>



                                                                <label class='control-label'>  Preferred Messaging Time : <span style="color: red">*</span> </label> 
                                                                <select class='form-control time input-rounded input-sm' required="" name='time' id='time'>
                                                                    <option value=''>Please select </option>
                                                                    <?php foreach ($times as $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>


                                                                <label class='control-label'>  Client Status <span style="color: red">*</span> </label> 
                                                                <select class='form-control client_status input-rounded input-sm ' required="" name='status' id='status'>
                                                                    <option value=''>Please Select : </option>
                                                                    <option value='Active'> Active </option>
                                                                    <option value="Disabled">Disabled</option>
                                                                    <option value="Transfered Out">Transfered Out</option>
                                                                    <option value='Deceased'> Deceased </option>
                                                                </select>



                                                                <div id="transfer_date_div" class="transfer_date_div" style="display: none;">
                                                                    <label class='control-label'>Transfer Date</label> 
                                                                    <input class='form-control transfer_date date input-rounded input-sm '  readonly="" type='text' name='transfer_date' placeholder="Please enter Trnasfer Date " id='edit_transfer_date'>

                                                                </div>

                                                                <hr>
                                                                <button type="submit" class="submit_add_new_div btn btn-success btn-small" id="submit_add_new_div">Add Client</button>
                                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>

                                                            </div> 


                                                        </div>

                                                    </div> 



                                                </div>






                                            </div>


                                        </section>













                                    </div>
                                </div>   

                            </form>


                        </div>
                        <div class="tab-pane " id="transfer_in">

                            <form class="add_transfer_form" id="add_transfer_form">
                                <div class='row'>
                                    <div class='col-xs-14 form-group'>
                                        <section class='panel'>
                                            <header class='panel-heading'> 
                                                <h4>Client Transfer Window</h4>
                                            </header>
                                            <div class='panel-body'>  

                                            </div>  </section> 
                                        <div class='col-xs-4 form-group'>


                                            <div class='form'>


                                                <?php
                                                $csrf = array(
                                                    'name' => $this->security->get_csrf_token_name(),
                                                    'hash' => $this->security->get_csrf_hash()
                                                );
                                                ?>

                                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                                <input type="hidden" name="registration_type" value="Transfer" class="form-control registration_type" id="registration_type " readonly=""/>
                                                <input type="text" name="facility_id" class="transfer_mfl_no form-control" placeholder="Please enter MFL No : " id="transfer_mfl_no"/>
                                                <span class="facility_loading" id="facility_loading" style="display: none;">Please wait , loading Facility Name : </span>
                                                <span class="facility_name" id="facility_name"></span>
                                                <br>


                                                <div class="load_client_details" id="load_client_details" style="display: none;">





                                                    <h4> Client Personal Information </h4> 
                                                    <label class='control-label'>Unique Patient Number* (Unique value : )</label><span style="color: red">*</span>
                                                    <input class='form-control transfer_clinic_number' placeholder="Enter Unique Patient Number"  type='text' required='' name='clinic_number' id='clinic_number' >
                                                    <br>
                                                    <button type="button" class="transfer_check_clinic_number btn btn-default" id="transfer_check_clinic_number">Check Clinic No : </button>

                                                    <div class="transfer_continue_div" style="display: none;">



                                                        <label class='control-label'>First Name </label> <span style="color: red">*</span>
                                                        <input class='form-control transfer_fname' placeholder="First Name" required="" type='text' name='fname' id='fname' >

                                                        <label class='control-label'>Middle Name </label> <span style="color: red">*</span> 
                                                        <input type='text' class='form-control transfer_mname' placeholder="Middle Name" required="" name='mname' id='mname'>

                                                        <label class='control-label'>Last Name</label>  <span style="color: red">*</span>
                                                        <input class='form-control transfer_lname'  placeholder="Last Name" type='text' name='lname' id='lname' >

                                                        <label class='control-label'> Date of Birth: </label> <span style="color: red">*</span>
                                                        <input class='form-control transfer_dob' required="" readonly="" placeholder="Date of Birth" type='text' name='p_year' id='dob' >








                                                    </div>









                                                </div>




                                            </div> 



                                        </div>
                                        <div class='col-xs-4 form-group'>


                                            <div class='form'>


                                                <div class="transfer_continue_div" id="transfer_continue_div" style="display: none;">





                                                    <label class='control-label'>  Gender : <span style="color: red">*</span> </label> 
                                                    <select class='form-control transfer_gender' required="" name='gender' id='gender'>
                                                        <option value=''>Please select </option>
                                                        <?php foreach ($genders as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>


                                                    <label class='control-label'>  Marital Status : <span style="color: red">*</span> </label> 
                                                    <select class='form-control transfer_marital' required="" name='marital' id='marital'>
                                                        <option value=''>Please select </option>
                                                        <?php foreach ($maritals as $value) {
                                                            ?>
                                                            <option value="<?php echo $value->id; ?>"><?php echo $value->marital; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>




                                                    <label class='control-label'> Treatment: </label> <span style="color: red">*</span>

                                                    <select class='form-control transfer_condition' required="" name='condition' id='condition'>
                                                        <option value=''> </option>

                                                        <option value='ART'>ART </option>
                                                        <option value='Pre ART'>Pre ART </option>
                                                    </select>


                                                    <label class='control-label'> HIV Enrollment Date: </label> <span style="color: red">*</span>
                                                    <input class='form-control transfer_enrollment_date  dob' readonly="" required="" placeholder="Date enrolled to HIV Care" type='text' name='enrollment_date' id='enrollment_date' >




                                                    <label class='control-label'> ART Start Date: </label> 
                                                    <input class='form-control transfer_ART_Start_date  dob' readonly=""  placeholder="ART Start Date" type='text' name='art_date' id='art_date' >





                                                    <label class='control-label'>  Facility Attached : </label>  <span style="color: red">*</span> 
                                                    <?php
                                                    $facility_id = $this->session->userdata('facility_id');
                                                    if (empty($facility_id)) {
                                                        ?>
                                                        <select class='form-control transfer_facilities' required="" name='facilities' id='facilities' >
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





                                                    <label class='control-label'>Cell Phone Number (Own) <span style="color: red">*</span> </label> 
                                                    <input class='form-control transfer_mobile' required="" placeholder="Client Phone No e.g 0714345678" pattern="^(([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+$" type='text' name='mobile' id='mobile'   >



                                                </div>



                                            </div>




                                        </div> 



                                    </div>

                                    <div class='col-xs-4 form-group'>


                                        <div class="transfer_continue_div" id="transfer_continue_div" style="display: none;">



                                            <label class='control-label'> Alternative Phone Number </label>
                                            <input class='form-control transfer_altmobile' type='text' placeholder="Alternative Phone No e.g 0714345678" pattern="^(([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+$" name='altmobile' id='altmobile'>
                                            <label class='control-label'>If shared, name</label> 
                                            <input class='form-control transfer_sharename' type='text' placeholder="Shared Name " name='sharename' id='sharename'>
                                            <label class='control-label'>  Communication lang : <span style="color: red">*</span> </label> 
                                            <select class='form-control transfer_lang' required="" name='lang' id='lang'>
                                                <option value=''>Please select </option>
                                                <?php foreach ($langauges as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                <?php }
                                                ?>
                                            </select>

                                            <label class='control-label'>  Enable Message Alerts? <span style="color: red">*</span> </label> 
                                            <select class='form-control transfer_smsenable' required="" name='smsenable' id='smsenable'>
                                                <option value=''>Please select : </option>
                                                <option value='Yes'> Yes </option>
                                                <option value='No'> No </option>
                                            </select>


                                            <div class="transfer_consent_date_div" id="transfer_consent_date_div" style="display: none;">

                                                <label class='control-label'> Consent Date: </label> 
                                                <input class='form-control transfer_consent_date dob' readonly=""  type='text' name='consent_date'  placeholder='Please enter Consent Date' id='transfer_consent_date'>
                                            </div>





                                            <label class='control-label'>  Client Status <span style="color: red">*</span> </label> 
                                            <select class='form-control transfer_client_status' required="" name='status' id='status'>
                                                <option value=''>Please Select : </option>
                                                <option value='Active'> Active </option>
                                                <option value="Disabled">Disabled</option>
                                                <option value='Deceased'> Deceased </option>
                                            </select>






                                            <hr> 

                                            <button type="submit" class="submit_add_transfer_div btn btn-success btn-small" id="submit_add_transfer_div">Add Client</button>
                                            <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>


                                        </div>
                                    </div>




                                </div>


                        </div>
                    </div>   

                    </form>





                </div>


            </div>
        </div>




        <div class="add_appointment_div" style="display: none;">


            <div class="panel-body  formData" id="editForm">
                <h4 id="actionLabel"> Client Appointment</h4>


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

                                            <button type="button" class="go_back_view btn btn-default btn-small" id="go_back_view"><i class="fa fa-backward  "></i>Go Back</button>

                                            <h4> Client Personal Information </h4> 
                                            <label class='control-label'>Unique Patient Number* (Unique value : )</label><span style="color: red">*</span>
                                            <input type="text" readonly="" value="<?php echo $this->session->userdata('facility_id'); ?>" class="form-control mfl_code" id="mfl_code" name="mfl_code" placeholder="MFL Code"/>
                                            <input class='form-control app_new_clinic_number' placeholder="Enter Unique Patient Number"  type='text' required='' name='clinic_number' id='clinic_number' >
                                            <br>
                                            <button type="button" class="new_check_clinic_number btn btn-default" id="new_check_clinic_number">Check Clinic No : </button>


                                            <div class="hide_other_info" id="hide_other_info" style="display: none;">

                                                <input type="hidden" name="client_id" id="app_client_id" class="app_client_id form-control"/>
                                                <label class='control-label'>First Name </label> 
                                                <input class='form-control add_fname' readonly="" type='text' name='fname'  id='add_fname' >

                                                <label class='control-label'>Middle Name </label>  
                                                <input type='text' class='form-control add_mname' readonly="" name='mname'  id='add_mname'>

                                                <label class='control-label'>Last Name</label>  
                                                <input class='form-control add_lname' type='text' readonly="" name='lname'  id='add_lname' >

                                                <label class='control-label'>Cell Phone Number (Own) </label> 
                                                <input class='form-control add_mobile' readonly=""  type='text' name='mobile' id='add_mobile'   >




                                                <label class='control-label'> Appointment Type : </label>
                                                <select class='form-control' name='appointment_type' id='p_apptype1' >
                                                    <option value=''>Please Select : </option>
                                                    <?php
                                                    foreach ($app_types as $value) {
                                                        ?>
                                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>




                                                <label class='control-label'> Next Appointment Date </lable> 
                                                    <input type='text' readonly="" placeholder="DD/MM/YYYY" class='form-control appointment_date' name='appointment_date' id='appointment_date'/> 


                                            </div>       </div>
                                        <div class="hide_other_info" style="display: none;">


                                            <button type="submit" class="submit_add_appointment btn btn-success btn-small" id="submit_add_appointment"><i class="icon-save"></i>Submit</button>
                                            <button type="button" class="cancel_add_appointment_form btn btn-danger btn-small" id="cancel_add_appointment_form"><i class="icon-stop"></i>Cancel</button>
                                        </div>

                                    </div> </section></div>










                        </div>
                    </div>   






                </form>





            </div>  


        </div>

















        <div id="dashboard_info" class="dashboard_info">



            <div class="card">
                <div class="card-title">
                    <h4>DashBoard</h4>

                </div>
                <div class="card-body">



                    <div id="dashboard_info" class="dashboard_info">






                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">


                                    <div class="top_div">
                                        <div class="row">
                                            <div class=" col-md-3 col-xs-5 col-lg-3 col-sm-3">

                                                <section class="panel">
                                                    <div class=" ">
                                                        <a href="javascript:void()" class="no_clients_btn" id="no_clients_btn">

                                                            <h4 class="client_tile" > Clients :  <?php
                                                                echo number_format($client_info);
                                                                ?></h4>

                                                        </a>

                                                    </div>
                                                </section>


                                            </div>  



                                            <div class=" col-md-3 col-xs-5 col-lg-3 col-sm-3">
                                                <section class="panel">

                                                    <div class=" ">
                                                        <a href="javascript:void()" class="consented_client_btn" id="consented_client_btn">


                                                            <h4 class="consent_tile"> Consented Clients : <?php
                                                                foreach ($consented_clients as $value) {
                                                                    $clients = $value->all_clients;
                                                                    $consented_clients = $value->consented_clients;
                                                                    echo number_format($consented_clients);
                                                                }
                                                                ?></h4>
                                                        </a>

                                                    </div>

                                                </section>
                                            </div>







                                            <div class=" col-md-3 col-xs-5 col-lg-3 col-sm-3">
                                                <section class="panel">


                                                    <a href="javascript:void()" class="appointments_btn" id="appointments_btn">
                                                        <div class=" ">

                                                            <h4 class="appointments_tile" >  Appointments :   <?php echo number_format($appointments); ?></h4>
                                                        </div> 
                                                    </a>


                                                </section>
                                            </div>







                                            <div class=" col-md-3 col-xs-5 col-lg-3 col-sm-3">
                                                <section class="panel">

                                                    <a href="javascript:void()" class="messages_btn" id="messages_btn">
                                                        <div class="  messages_tile_div">

                                                            <h4 class="messages_tile" >Messages Sent : <?php echo number_format($messages_sent); ?></h4>
                                                        </div> 
                                                    </a>

                                                </section>
                                            </div>

                                        </div>






                                    </div>



                                    <div class="col-lg-12">


                                        <div class="info_breakdown" style="display: none;">

                                            <div class="col-lg-2">
                                                <button class="btn btn-default go_back_info" name="go_back_info" id="go_back_info"> <i class="fa fa-backward"></i></button>
                                            </div>

                                            <div class="percentage_counties_explained_div col-md-12" id="percentage_counties_explained_div" style="display: none;" >


                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <p id="percentage_counties_gauge" class="percentage_counties_gauge" ></p>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="percentage_counties_div" id="percentage_counties_div">

                                                        </div>

                                                    </div>
                                                </div>







                                            </div>
                                            <div class="percentage_facilities_explained_div col-md-12" id="percentage_facilities_explained_div" style="display: none;" >


                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <p id="percentage_facilities_gauge" class="percentage_facilities_gauge" ></p>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="percentage_facilities_div" id="percentage_facilities_div">

                                                        </div>

                                                    </div>
                                                </div>



                                            </div>
                                            <div class="percentage_clients_explained_div col-md-12" id="percentage_clients_explained_div" style="display: none;" >


                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <p id="percentage_clients_gauge" class="percentage_clients_gauge" ></p>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="percentate_clients_div" id="percentate_clients_div">

                                                        </div>

                                                    </div>
                                                </div>



                                            </div>


                                            <div class="client_explained_div col-md-12" id="client_explained_div" style="display: none;">
                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <div id="gender_chart" class="gender_chart" ></div> 
                                                        <br>
                                                        <div class="gender_report_div" id="gender_report_div">

                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">

                                                        <div id="language_chart" class="language_chart" ></div>
                                                        <br>
                                                        <div class="language_report_div" id="language_report_div">                 
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <p id="marital_piechart" class="marital_piechart" ></p>
                                                        <div class="marital_report_div" id="marital_report_div"></div>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <p id="client_type_chart" class="client_type_chart" ></p>
                                                        <div class="type_report_div" id="type_report_div"></div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <p id="client_type_barchart" class="client_type_barchart" ></p>
                                                        <div class="client_category_report_div" id="client_category_report_div"></div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <p id="client_age_group_barchart" class="client_age_group_barchart" ></p>
                                                        <div class="client_age_group_report_div" id="client_age_group_report_div"></div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p id="client_condition_chart" class="client_condition_chart" ></p>
                                                        <div class="client_condition_report_div" id="client_condition_report_div"></div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <p id="client_status_chart" class="client_status_chart" ></p>
                                                        <div class="client_status_report_div" id="client_status_report_div"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p id="client_reg_chart" class="client_reg_chart" ></p>
                                                        <div class="client_reg_report_div" id="client_reg_report_div"></div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p id="cummulative_client_grouping" class="cummulative_client_grouping" ></p>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="consented_explained_div col-md-12" id="consented_explained_div" style="display: none;" >


                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <div id="consented_client_chart" class="consented_client_chart" ></div>
                                                        <div class="client_consented_report_div" id="client_consented_report_div"></div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="consented_client_json" class="consented_client_json"></div>
                                                        <div class="client_consent_report_div"></div>


                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p id="consent_gauge" class="consent_gauge"></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="consent_client_gender_chart" class="consent_client_gender_chart"></div>
                                                        <div class="client_consented_gender_report_div" id="client_consented_gender_report_div"></div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div id="consent_client_marital_chart" class="consent_client_marital_chart"></div>
                                                        <div class="client_consented_marital_report_div" id="client_consented_marital_report_div"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="consent_client_group_chart" class="consent_client_group_chart"></div>
                                                        <div class="client_consented_category_report_div" id="client_consented_category_report_div"></div>
                                                    </div>
                                                </div>



                                            </div>

                                            <div class="appointment_explained_div col-md-12" id="appointment_explained_div" style="display: none;" >
                                                <div class="row">

                                                    <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">


                                                            <div class=" ">

                                                                <h4 class="future_appointments_tile" > Future Appointments : <?php echo number_format($active_appointments); ?></h4>
                                                            </div>


                                                        </section>
                                                    </div>



                                                    <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">


                                                            <a href="javascript:void()" class="past_appointments_btn" id="past_appointments_btn">
                                                                <div class=" ">

                                                                    <h4 class="past_appointments_tile" > Past Appointments :  <?php echo number_format($old_appointments); ?></h4>
                                                                </div> 
                                                            </a>

                                                        </section>
                                                    </div> 



                                                    <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">


                                                            <a href="<?php echo base_url(); ?>home/today_appointments" class="" id="" data-toggle="tooltip" title="Click to view more!" >
                                                                <div class=" ">

                                                                    <h4 class="today_appointments_tile"> Appointments Today :  <?php echo number_format($count_today_appointments); ?></h4>
                                                                </div> 
                                                            </a>

                                                        </section>
                                                    </div>

                                                    <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">





                                                            <a href="javascript:void()" class="appointments_kept_btn" id="appointments_kept_btn">
                                                                <div class=" ">

                                                                    <h4 class="appointments_kept" >  Appointments Kept :  <?php echo number_format($honored_appointments); ?></h4>
                                                                </div> 
                                                            </a>




                                                        </section>
                                                    </div>





                                                </div>
                                                <div class="row past_appointments_breakdown" style="display: none;">
                                                    <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">

                                                            <div class=" ">

                                                                <h4 class="honored_appointments_tile" > Honored Appointments  :  <?php echo number_format($honored_appointments); ?></h4>
                                                            </div>
                                                        </section>
                                                    </div> <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">

                                                            <div class=" ">

                                                                <h4 class="missed_appointments_tile" > Missed Appointments :  <?php echo number_format($count_missed_appointments); ?></h4>
                                                            </div>
                                                        </section>
                                                    </div> <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">

                                                            <div class=" ">

                                                                <h4 class="defaulted_appointments_tile" > Defaulted Appointments :  <?php echo number_format($count_defaulted_appointments); ?></h4>
                                                            </div>
                                                        </section>
                                                    </div> <div class="  col-lg-3 col-sm-3">
                                                        <section class="panel">

                                                            <div class=" ">

                                                                <h4 class="LTFU_appointments_tile" > Lost to Follow Up :  <?php echo number_format($LTFU_appointments); ?></h4>
                                                            </div>
                                                        </section>
                                                    </div>  
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div id="appointment_status_chart" class="appointment_status_chart" ></div>
                                                        <div class="client_appointment_status_report_div" id="client_appointment_status_report_div"></div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="appointment_distribution_by_booked_chart" class="appointment_distribution_by_booked_chart"></div>
                                                        <div class="client_booked_appointment_status_report_div" id="client_booked_appointment_status_report_div"></div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div id="appointment_distribution_by_notified_chart" class="appointment_distribution_by_notified_chart" ></div>
                                                        <div class="client_notified_appointment_status_report_div" id="client_notified_appointment_status_report_div"></div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div id="appointment_distribution_by_missed_chart" class="appointment_distribution_by_missed_chart"></div>
                                                        <div class="client_missed_appointment_status_report_div" id="client_missed_appointment_status_report_div"></div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div id="appointment_distribution_by_defaulted_chart" class="appointment_distribution_by_defaulted_chart" ></div>
                                                        <div class="client_defaulted_appointment_status_report_div" id="client_defaulted_appointment_status_report_div"></div>

                                                    </div>
                                                    <div class="col-md-6">

                                                        <p id="client_appointment_chart" class="client_appointment_chart" ></p>
                                                        <div class="client_appointment_report_div" id="client_appointment_report_div"></div>
                                                    </div>
                                                </div>







                                            </div>

                                            <div class="messages_explained_div" id="messages_explained_div" style="display: none;" >


                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <div id="message_distribution_pie_chart" class="message_distribution_pie_chart" ></div>
                                                        <br>
                                                        <div class="message_distribution_report_div" id="message_distribution_report_div">

                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="message_distribution_bar_chart" id="message_distribution_bar_chart">

                                                        </div>
                                                        <br>



                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">


                                                        <p id="message_distribution_status" class="message_distribution_status" ></p>

                                                    </div>

                                                </div>


                                            </div>



                                        </div>









                                    </div>




                                </div>



                            </div>

                        </div>



                        <!--DYNAMIC CHART ENDS HERE-->




                        <div class="appointment_report_div">

                            <div class="state col-lg-14">
                                <div class="row">


                                    <div class=" col-lg-6 col-sm-6">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <i class="icon-table"></i>Today Appointments
                                            </div>


                                            <div class="panel-body">
                                                <table id="today_app_table" class="today_app_table  table_wrapper table-bordered table-condensed table-hover table-responsive table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>UPN</th>
                                                            <th>Serial No</th>
                                                            <th>Client Name</th>
                                                            <th>Phone No</th>
                                                            <th>Appointment Date</th>
                                                            <th>Appointment Type</th>
                                                            <th>Other Appointment Type</th>
                                                            <th>No of Actions</th>



                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach ($today_appointments as $value) {
                                                            ?>
                                                            <tr>
                                                                <td class="a-center"><?php echo $i; ?></td>



                                                                <?php
                                                                $view_client = $this->session->userdata('view_client');

                                                                if ($view_client == "Yes") {
                                                                    ?>
                                                                    <td>
                                                                        <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                                        <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                                            <?php echo $value->clinic_number; ?>
                                                                        </button>

                                                                    </td>
                                                                    <td><?php echo $value->file_no; ?></td>
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
                                                                    <td>XXXXXX XXXXXXX</td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <td><?php echo $value->appntmnt_date; ?></td>
                                                                <td><?php echo $value->appointment_type_name; ?></td>
                                                                <td><?php echo $value->Other_Appointment; ?></td>
                                                                <td><?php echo "No of Calls : " . $value->no_calls . "<br> No of Msgs : " . $value->no_msgs . "<br> No of Visits : " . $value->home_visits . "<br> "; ?></td>

                                                                <?php
                                                                $access_level = $this->session->userdata('access_level');
                                                                if ($access_level == "Donor") {
                                                                    ?>

                                                                    <?php
                                                                } else {
                                                                    ?>




                                                                    <?php
                                                                }
                                                                ?> </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>



                                        </div>
                                    </div>   
                                    <div class=" col-lg-6 col-sm-6">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <i class="icon-table"></i>Missed Appointments
                                            </div>


                                            <div class="panel-body">
                                                <table id="missed_table" class="missed_table table_wrapper table-bordered table-condensed table-hover table-responsive table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>UPN</th>
                                                            <th>Serial no </th>
                                                            <th>Client Name</th>
                                                            <th>Phone No</th>
                                                            <th>Appointment Date</th>
                                                            <th>Appointment Type</th>
                                                            <th>No of Actions</th>



                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach ($missed_appointments as $value) {
                                                            ?>
                                                            <tr>
                                                                <td class="a-center"><?php echo $i; ?></td>



                                                                <?php
                                                                $view_client = $this->session->userdata('view_client');

                                                                if ($view_client == "Yes") {
                                                                    ?>
                                                                    <td>
                                                                        <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                                        <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                                            <?php echo $value->clinic_number; ?>
                                                                        </button>

                                                                    </td>
                                                                    <td><?php echo $value->file_no; ?></td>
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
                                                                    <td>XXXXXX XXXXXXX</td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <td><?php echo $value->appntmnt_date; ?></td>
                                                                <td><?php echo $value->appointment_type_name; ?></td>
                                                                <td><?php echo "No of Calls : " . $value->no_calls . "<br> No of Msgs : " . $value->no_msgs . "<br> No of Visits : " . $value->home_visits . "<br> "; ?></td>

                                                                <?php
                                                                $access_level = $this->session->userdata('access_level');
                                                                if ($access_level == "Donor") {
                                                                    ?>

                                                                    <?php
                                                                } else {
                                                                    ?>





                                                                    <?php
                                                                }
                                                                ?> </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>



                                        </div>
                                    </div>   


                                </div>




                            </div>



                            <div class="state col-lg-12">
                                <div class="row">


                                    <div class=" col-lg-6 col-sm-6">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <i class="icon-table"></i>Defaulted Appointments
                                            </div>


                                            <div class="panel-body">
                                                <table id="defaulted_table" class="defaulted_table table-bordered table-condensed table-hover table-responsive table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>UPN</th>
                                                            <th>Serial no </th>
                                                            <th>Client Name</th>
                                                            <th>Phone No</th>
                                                            <th>Appointment Date</th>
                                                            <th>Appointment Type</th>
                                                            <th>No of Actions</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach ($defaulted_appointments as $value) {
                                                            ?>
                                                            <tr>
                                                                <td class="a-center"><?php echo $i; ?></td>



                                                                <?php
                                                                $view_client = $this->session->userdata('view_client');

                                                                if ($view_client == "Yes") {
                                                                    ?>
                                                                    <td>
                                                                        <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                                        <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                                            <?php echo $value->clinic_number; ?>
                                                                        </button>

                                                                    </td>
                                                                    <td><?php echo $value->file_no; ?></td>
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
                                                                    <td>XXXXXX XXXXXXX</td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <td><?php echo $value->appntmnt_date; ?></td>
                                                                <td><?php echo $value->appointment_type_name; ?></td>
                                                                <td><?php echo "No of Calls : " . $value->no_calls . "<br> No of Msgs : " . $value->no_msgs . "<br> No of Visits : " . $value->home_visits . "<br> "; ?></td>

                                                                <?php
                                                                $access_level = $this->session->userdata('access_level');
                                                                if ($access_level == "Donor") {
                                                                    ?>

                                                                    <?php
                                                                } else {
                                                                    ?>




                                                                    <?php
                                                                }
                                                                ?> </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>



                                        </div>
                                    </div>   
                                    <div class=" col-lg-6 col-sm-6">
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <i class="icon-table"></i>LTFU  Appointments
                                            </div>


                                            <div class="panel-body">
                                                <table id="ltfu_table" class="ltfu_table table-bordered table-condensed table-hover table-responsive table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>UPN</th>
                                                            <th>Serial no </th>
                                                            <th>Client Name</th>
                                                            <th>Phone No</th>
                                                            <th>Appointment Date</th>
                                                            <th>Appointment Type</th>
                                                            <th>No of Actions</th>
                                                            <th>Appointment Message</th>



                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach ($ltfu_appointments as $value) {
                                                            ?>
                                                            <tr>
                                                                <td class="a-center"><?php echo $i; ?></td>



                                                                <?php
                                                                $view_client = $this->session->userdata('view_client');

                                                                if ($view_client == "Yes") {
                                                                    ?>
                                                                    <td>
                                                                        <input type="hidden" id="client_id" name="client_id" class="client_id form-control" value="<?php echo $value->client_id; ?>"/>
                                                                        <button class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                                            <?php echo $value->clinic_number; ?>
                                                                        </button>

                                                                    </td>
                                                                    <td><?php echo $value->file_no; ?></td>
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
                                                                    <td>XXXXXX XXXXXXX</td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <td><?php echo $value->appntmnt_date; ?></td>
                                                                <td><?php echo $value->appointment_type_name; ?></td>
                                                                <td><?php echo "No of Calls : " . $value->no_calls . "<br> No of Msgs : " . $value->no_msgs . "<br> No of Visits : " . $value->home_visits . "<br> "; ?></td>
                                                                <td><?php echo $value->app_msg; ?></td>
                                                                <?php
                                                                $access_level = $this->session->userdata('access_level');
                                                                if ($access_level == "Donor") {
                                                                    ?>

                                                                    <?php
                                                                } else {
                                                                    ?>




                                                                    <?php
                                                                }
                                                                ?> </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>



                                        </div>
                                    </div>   


                                </div>




                            </div>




                        </div>





                    </div>



                </div>
            </div>





            <div class=" col-lg-14 col-sm-14">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="icon-dashboard"></i>DashBoard
                    </div>





                </div>
            </div> 





        </div>











        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->




<script src="https://code.jquery.com/jquery-1.12.4.js"></script>





<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/alasql/0.4.0/alasql.min.js"></script>
<script type="text/javascript">




    $(document).ready(function () {



        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$4');
            }
            return val;
        }



        $(".county_btn").click(function () {
            $(".consented_explained_div").hide();
            $(".appointment_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").show();
            $(".messages_explained_div").hide();
            $(".top_div").hide();
            $(".info_breakdown").show();
            $(".filter_div").hide();
        });
        $(".facility_btn").click(function () {
            $(".consented_explained_div").hide();
            $(".appointment_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").show();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
            $(".top_div").hide();
            $(".info_breakdown").show();
            $(".filter_div").hide();
        });
        $(".consented_client_btn").click(function () {
            $(".consented_explained_div").show();
            $(".appointment_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
            $(".top_div").hide();
            $(".info_breakdown").show();
            $(".filter_div").hide();
        });
        $(".no_clients_btn").click(function () {
            $(".consented_explained_div").hide();
            $(".appointment_explained_div").hide();
            $(".client_explained_div").show();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
            $(".top_div").hide();
            $(".info_breakdown").show();
            $(".filter_div").hide();
        });
        $(".appointments_btn").click(function () {

            $(".consented_explained_div").hide();
            $(".appointment_explained_div").show();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").hide();
            $(".top_div").hide();
            $(".info_breakdown").show();
            $(".filter_div").hide();
        });
        $(".messages_btn").click(function () {

            $(".consented_explained_div").hide();
            $(".appointment_explained_div").hide();
            $(".client_explained_div").hide();
            $(".percentage_facilities_explained_div").hide();
            $(".percentage_counties_explained_div").hide();
            $(".messages_explained_div").show();
            $(".top_div").hide();
            $(".info_breakdown").show();
            $(".filter_div").hide();
        });
        $(".go_back_info").click(function () {
            $(".top_div").show();
            $(".info_breakdown").hide();
            $(".filter_div").show();
        });
        $(".past_appointments_btn").click(function () {
            $(".past_appointments_breakdown").show();
            $(".info_breakdown").show();
            $(".top_div").hide();
            $(".filter_div").hide();
        });




        $(".filter_dashboard").click(function () {
            var county = $(".filter_county").val();
            var sub_county = $(".filter_sub_county").val();
            var facility = $(".filter_facility").val();
            var date_from = $(".date_from").val();
            var date_to = $(".date_to").val();




            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_date_from = "";
            var selected_date_to = "";

            if (date_from.length > 0) {
                selected_date_from = "From : " + $(".date_from").val() + " - ";
            }

            if (date_to.length > 0) {
                selected_date_to = "To : " + $(".date_to").val();
            }


            if (county != "") {
                selected_county = "For " + $(".filter_county option:selected").text() + " County ";

            }
            if (sub_county != "") {
                selected_sub_county = ", " + $(".filter_sub_county option:selected").text() + "Sub County ";

            }
            if (facility != "") {
                selected_facility = "," + $(".filter_facility option:selected").text() + " ";

            }

            var description_one = "" + selected_county + " " + selected_sub_county + "  " + selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            var tokenizer = $(".tokenizer").val();
            draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two, tokenizer);


            county_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            sub_county_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            client_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            consent_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            partners_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            facilities_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            active_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            old_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            today_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            current_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            missed_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer);
            messages_tile(county, sub_county, facility, date_from, date_to, tokenizer);

        });
        var county = $(".filter_county").val();
        var sub_county = $(".filter_sub_county").val();
        var facility = $(".filter_facility").val();
        var date_from = $(".date_from").val();
        var date_to = $(".date_to").val();
        var tokenizer = $(".tokenizer").val();
        var description_one = "";
        var description_two = "";

        draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two, tokenizer);
        function draw_chart(county, sub_county, facility, date_from, date_to, description_one, description_two, tokenizer) {

            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }
            var processed_json = new Array();

            $(".gender_report_div").empty();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_gender_reports/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    for (i = 0; i < data.length; i++) {
                        processed_json.push([data[i].name, parseInt(data[i].value)]);
                    }


                    // draw chart
                    Highcharts.chart('gender_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Gender</br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: processed_json
                            }]
                    });



                    create_gender_report(data);



                }, error: function (errorThrown) {

                }
            });



            var language_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_language/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        language_json.push([data[i].NAME, parseInt(data[i].VALUE)]);
                    }

                    // draw chart
                    Highcharts.chart('language_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Language </br> ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: language_json
                            }]
                    });



                    create_client_language_report(data);

                }, error: function (errorThrown) {

                }
            });






            var marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_marital_reports/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        marital_json.push([data[i].NAME, parseInt(data[i].VALUE)]);
                    }

                    // draw chart
                    Highcharts.chart('marital_piechart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Marital Status ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: marital_json
                            }]
                    });


                    create_client_marital_report(data);
                }, error: function (errorThrown) {

                }
            });






            var client_type_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_type/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_type_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_type_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Type ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: client_type_json
                            }]
                    });

                    create_client_type_report(data);
                }, error: function (error) {

                }

            });



            var client_type_json4 = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_category/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_type_json4.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart

                    Highcharts.chart('client_type_barchart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Distribution by Category ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: client_type_json4
                            }]
                    });
                    create_client_category_report(data);
                }, error: function (errorThrown) {

                }});


            var client_age_group_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_age_group/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_age_group_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart

                    Highcharts.chart('client_age_group_barchart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Distribution by Age Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: client_age_group_json
                            }]
                    });
                    create_client_age_group_report(data);
                }, error: function (errorThrown) {

                }
            });





            var client_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_status/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_status_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_status_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Status ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: client_status_json
                            }]
                    });
                    create_client_status_report(data);

                }, error: function (errorThrown) {

                }
            });






            var client_reg_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_registration/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_reg_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_reg_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Registration Point ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: client_reg_json
                            }]
                    });
                    create_client_reg_report(data);

                }, error: function (errorThrown) {

                }
            });

            var client_appointment_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_appointment/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        client_appointment_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_appointment_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Appointment Distribution by Entry Point ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Appointments',
                                colorByPoint: true,
                                data: client_appointment_json
                            }]
                    });
                    create_client_appointment_report(data);

                }, error: function (errorThrown) {

                }
            });


            var consented_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_consented_clients/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('consented_client_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Consented for SMS Alerts ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: consented_json
                            }]
                    });
                    create_client_consented_report(data);
                }, error: function (errorThrown) {

                }
            });









            var consented_column_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_consented_clients/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_column_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart

                    Highcharts.chart('consented_client_json', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Consented for SMS Alerts ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: consented_column_json
                            }]
                    });
                }, error: function (errorThrown) {

                }
            });







            var condition_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_condition/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        condition_json.push([data[i].k, parseInt(data[i].v)]);
                    }

                    // draw chart
                    Highcharts.chart('client_condition_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Client Distribution by Condition ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: condition_json
                            }]
                    });

                    create_client_condition_report(data);

                }, error: function (errorThrown) {

                }
            });














            var appointment_status_json = new Array();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_status_distribution/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_status_json.push([data[i].Missed, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_status_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Appointment Status ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: appointment_status_json
                            }]
                    });
                    create_client_appointment_status_report(data);
                }, error: function (errorThrown) {

                }});








            var appointment_booked_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_booked/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_booked_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_booked_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Booked Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: appointment_booked_status_json
                            }]
                    });

                    create_client_booked_appointment_status_report(data);

                }, error: function (errorThrown) {

                }});

            var appointment_notified_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_notified/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_notified_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_notified_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Notified  Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: appointment_notified_status_json
                            }]
                    });

                    create_client_notified_appointment_status_report(data);

                }, error: function (errorThrown) {

                }});



            var appointment_missed_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_missed/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_missed_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_missed_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Missed Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: appointment_missed_status_json
                            }]
                    });

                    create_client_missed_appointment_status_report(data);

                }, error: function (errorThrown) {

                }});


            var appointment_defaulted_status_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/appointment_distribution_by_defaulted/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        appointment_defaulted_status_json.push([data[i].group_name, parseInt(data[i].total)]);
                    }

                    // draw chart

                    Highcharts.chart('appointment_distribution_by_defaulted_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Client Defaulted Appointment Status Distribution by Group ' + final_description + ' '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Clients",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Clients',
                                data: appointment_defaulted_status_json
                            }]
                    });
                    create_client_defaulted_appointment_status_report(data);


                }, error: function (errorThrown) {

                }});



//        








            var messages_queue_json = new Array();





            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_messages_queued_dist/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        messages_queue_json.push([data[i].message_type, parseInt(data[i].no_messages)]);
                    }

                    // draw chart
                    Highcharts.chart('message_distribution_pie_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Messages queued distribution </br> ' + final_description + ' (Pie Chart) '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Messages',
                                colorByPoint: true,
                                data: messages_queue_json
                            }]
                    });



                    create_message_distribution_report(data);

                }, error: function (errorThrown) {

                }
            });

            var messages_queue_clmn_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_messages_queued_dist/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        messages_queue_clmn_json.push([data[i].message_type, parseInt(data[i].no_messages)]);
                    }

                    // draw chart

                    Highcharts.chart('message_distribution_bar_chart', {
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: 'Messages queued distribution ' + final_description + ' (Bar Chart) '
                        },
                        subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        xAxis: {
                            type: 'category',
                            allowDecimals: false,
                            title: {
                                text: ""
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "No of Messages",
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        }, plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        }, legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                                name: 'Messages',
                                data: messages_queue_clmn_json
                            }]
                    });

                }, error: function (errorThrown) {

                }
            });



























            var consent_json = new Array();

            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/consented_clients_json/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var consented_clients = response[0].consented_clients;
                    var all_clients = response[0].all_clients;

                    var consent_val = ((consented_clients / all_clients) * 100).toFixed(1);
                    // Populate series
                    for (i = 0; i < response.length; i++) {
                        consent_json.push([parseInt(consent_val)]);
                    }






                    Highcharts.chart('consent_gauge', {
                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },
                        title: {
                            text: 'Percentage of Clients consented ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: [{
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x4: 0, y4: 1},
                                        stops: [
                                            [0, '#FFF'],
                                            [1, '#333']
                                        ]
                                    },
                                    borderWidth: 0,
                                    outerRadius: '109%'
                                }, {
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x4: 0, y4: 1},
                                        stops: [
                                            [0, '#333'],
                                            [1, '#FFF']
                                        ]
                                    },
                                    borderWidth: 1,
                                    outerRadius: '107%'
                                }, {
                                    // default background
                                }, {
                                    backgroundColor: '#DDD',
                                    borderWidth: 0,
                                    outerRadius: '105%',
                                    innerRadius: '103%'
                                }]
                        },
                        // the value axis
                        yAxis: {
                            min: 0,
                            max: 100,
                            minorTickInterval: 'auto',
                            minorTickWidth: 1,
                            minorTickLength: 10,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',
                            tickPixelInterval: 30,
                            tickWidth: 4,
                            tickPosition: 'inside',
                            tickLength: 10,
                            tickColor: '#666',
                            labels: {
                                step: 4,
                                rotation: 'auto'
                            },
                            title: {
                                text: 'Consented'
                            },
                            plotBands: [{
                                    from: 80,
                                    to: 100,
                                    color: '#55BF3B' // green
                                }, {
                                    from: 40,
                                    to: 80,
                                    color: '#DDDF0D' // yellow
                                }, {
                                    from: 0,
                                    to: 40,
                                    color: '#DF5353' // red
                                }]
                        },
                        series: [{
                                name: 'Consented ',
                                data: consent_json,
                                tooltip: {
                                    valueSuffix: '%'
                                }, credits: {
                                    enabled: false
                                }
                            }]

                    })






                }
            });

            var target_counties_json = new Array();

            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/percentage_counties/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var actual_counties = response[0].actual_counties;
                    var target_counties = response[0].target_counties;

                    var percentage_counties = ((actual_counties / target_counties) * 100).toFixed(1);
                    // Populate series
                    for (i = 0; i < response.length; i++) {
                        target_counties_json.push([parseInt(percentage_counties)]);
                    }






                    Highcharts.chart('percentage_counties_gauge', {
                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },
                        title: {
                            text: 'Percentage of Counties covered ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: [{
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x4: 0, y4: 1},
                                        stops: [
                                            [0, '#FFF'],
                                            [1, '#333']
                                        ]
                                    },
                                    borderWidth: 0,
                                    outerRadius: '109%'
                                }, {
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x4: 0, y4: 1},
                                        stops: [
                                            [0, '#333'],
                                            [1, '#FFF']
                                        ]
                                    },
                                    borderWidth: 1,
                                    outerRadius: '107%'
                                }, {
                                    // default background
                                }, {
                                    backgroundColor: '#DDD',
                                    borderWidth: 0,
                                    outerRadius: '105%',
                                    innerRadius: '103%'
                                }]
                        },
                        // the value axis
                        yAxis: {
                            min: 0,
                            max: 100,
                            minorTickInterval: 'auto',
                            minorTickWidth: 1,
                            minorTickLength: 10,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',
                            tickPixelInterval: 30,
                            tickWidth: 4,
                            tickPosition: 'inside',
                            tickLength: 10,
                            tickColor: '#666',
                            labels: {
                                step: 4,
                                rotation: 'auto'
                            },
                            title: {
                                text: 'Consented'
                            },
                            plotBands: [{
                                    from: 80,
                                    to: 100,
                                    color: '#55BF3B' // green
                                }, {
                                    from: 40,
                                    to: 80,
                                    color: '#DDDF0D' // yellow
                                }, {
                                    from: 0,
                                    to: 40,
                                    color: '#DF5353' // red
                                }]
                        },
                        series: [{
                                name: 'Target counties ',
                                data: target_counties_json,
                                tooltip: {
                                    valueSuffix: ' %'
                                }, credits: {
                                    enabled: false
                                }
                            }]

                    })






                }
            });

            var target_facilities_json = new Array();

            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/percentage_facilities/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var actual_facilities = response[0].actual_facilities;
                    var target_facilities = response[0].target_facilities;

                    var percentage_facilities = ((actual_facilities / target_facilities) * 100).toFixed(1);
                    // Populate series
                    for (i = 0; i < response.length; i++) {
                        target_facilities_json.push([parseInt(percentage_facilities)]);
                    }






                    Highcharts.chart('percentage_facilities_gauge', {
                        chart: {
                            type: 'gauge',
                            plotBackgroundColor: null,
                            plotBackgroundImage: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },
                        title: {
                            text: 'Percentage of Facilities covered ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        pane: {
                            startAngle: -150,
                            endAngle: 150,
                            background: [{
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x4: 0, y4: 1},
                                        stops: [
                                            [0, '#FFF'],
                                            [1, '#333']
                                        ]
                                    },
                                    borderWidth: 0,
                                    outerRadius: '109%'
                                }, {
                                    backgroundColor: {
                                        linearGradient: {x1: 0, y1: 0, x4: 0, y4: 1},
                                        stops: [
                                            [0, '#333'],
                                            [1, '#FFF']
                                        ]
                                    },
                                    borderWidth: 1,
                                    outerRadius: '107%'
                                }, {
                                    // default background
                                }, {
                                    backgroundColor: '#DDD',
                                    borderWidth: 0,
                                    outerRadius: '105%',
                                    innerRadius: '103%'
                                }]
                        },
                        // the value axis
                        yAxis: {
                            min: 0,
                            max: 100,
                            minorTickInterval: 'auto',
                            minorTickWidth: 1,
                            minorTickLength: 10,
                            minorTickPosition: 'inside',
                            minorTickColor: '#666',
                            tickPixelInterval: 30,
                            tickWidth: 4,
                            tickPosition: 'inside',
                            tickLength: 10,
                            tickColor: '#666',
                            labels: {
                                step: 4,
                                rotation: 'auto'
                            },
                            title: {
                                text: 'Consented'
                            },
                            plotBands: [{
                                    from: 80,
                                    to: 100,
                                    color: '#55BF3B' // green
                                }, {
                                    from: 40,
                                    to: 80,
                                    color: '#DDDF0D' // yellow
                                }, {
                                    from: 0,
                                    to: 40,
                                    color: '#DF5353' // red
                                }]
                        },
                        series: [{
                                name: 'Target counties ',
                                data: target_facilities_json,
                                tooltip: {
                                    valueSuffix: '%'
                                }, credits: {
                                    enabled: false
                                }
                            }]

                    })






                }
            });












            var consented_gender_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/consented_clients_gender/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {

                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_gender_json.push([data[i].gender, parseInt(data[i].total_client)]);
                    }

                    // draw chart
                    Highcharts.chart('consent_client_gender_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Consented Client Distribution by Gender ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: consented_gender_json
                            }]
                    });
                    create_client_consented_gender_report(data);
                }, error: function (errorThrown) {

                }
            });

            var consented_marital_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/consented_clients_marital/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_marital_json.push([data[i].marital, parseInt(data[i].total_client)]);
                    }

                    // draw chart
                    Highcharts.chart('consent_client_marital_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Consented Client Distribution by Marital Status ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: consented_marital_json
                            }]
                    });
                    create_client_consented_marital_report(data);
                }, error: function (errorThrown) {

                }
            });
            var consented_group_json = new Array();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/consented_clients_groups/",
                dataType: 'JSON',
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (data) {
                    // Populate series
                    for (i = 0; i < data.length; i++) {
                        consented_group_json.push([data[i].group_name, parseInt(data[i].total_client)]);
                    }

                    // draw chart
                    Highcharts.chart('consent_client_group_chart', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Consented Client Distribution by Group ' + final_description + ' '
                        }, subtitle: {
                            text: 'Source: <a href="http://t4a.mhealthkenya.org">T4A</a>'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        }, credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    }
                                }
                            }
                        },
                        series: [{
                                name: 'Clients',
                                colorByPoint: true,
                                data: consented_group_json
                            }]
                    });
                    create_client_consented_category_report(data);

                }, error: function (errorThrown) {

                }
            });



































        }







        function appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".appointments_tile").empty();
            $(".appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/appointment_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".appointments_tile").empty();
                    $(".appointments_tile").append("Appointments " + response);
                }, error: function (data) {

                }
            })
        }
        function active_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".active_appointments_tile").empty();
            $(".active_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/active_appointment_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".active_appointments_tile").empty();
                    $(".active_appointments_tile").append("Open Appointments : " + response);
                }, error: function (data) {

                }
            })
        }
        function old_appointment_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".old_appointments_tile").empty();
            $(".old_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/old_appointment_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".old_appointments_tile").empty();
                    $(".old_appointments_tile").append("Closed  Appointments : " + response);
                }, error: function (data) {

                }
            })
        }

        function today_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".today_appointments_tile").empty();
            $(".today_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_today_appointments_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".today_appointments_tile").empty();
                    $(".today_appointments_tile").append("Today Appointment/s" + response);
                }, error: function (data) {

                }
            })
        }
        function current_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".current_appointments_tile").empty();
            $(".current_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_current_appointments_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".current_appointments_tile").empty();
                    $(".current_appointments_tile").append("Current Appointments : " + response);
                }, error: function (data) {

                }
            })
        }
        function missed_appointments_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".missed_appointments_tile").empty();
            $(".missed_appointments_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_missed_appointments_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".missed_appointments_tile").empty();
                    $(".missed_appointments_tile").append("Missed Appointments : " + response);
                }, error: function (data) {

                }
            })
        }



        function messages_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".messages_tile").empty();
            $(".messages_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/count_messages_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".messages_tile").empty();
                    $(".messages_tile").append("Messages Sent : " + response);
                }, error: function (data) {

                }
            })
        }



        function county_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".county_tile").empty();
            $(".county_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/county_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".county_tile").empty();
                    $(".county_tile").append("County/ies : " + response);
                }, error: function (data) {

                }
            })
        }



        function sub_county_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".sub_county_tile").empty();
            $(".sub_county_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/sub_county_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var response = commaSeparateNumber(response);
                    $(".sub_county_tile").empty();
                    $(".sub_county_tile").append("Sub County/ies : " + response);
                }, error: function (data) {

                }
            })
        }



        function client_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".client_tile").empty();
            $(".client_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/client_info_json/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {

                    $(".client_tile").empty();
                    var response = commaSeparateNumber(response);
                    $(".client_tile").append("Clients : " + response);
                }, error: function (data) {

                }
            })
        }






        function consent_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".consent_tile").empty();
            $(".consent_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/consented_clients_json/' + county + '/' + sub_county + '/' + facility + '/' + date_from + '/' + date_to + '/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    var consented_clients = response[0].consented_clients;
                    var response = commaSeparateNumber(consented_clients);
                    //var all_clients = response[0].all_clients;

                    // var response = ((consented_clients / all_clients) * 100).toFixed(1);
                    $(".consent_tile").empty();
                    $(".consent_tile").append("Consented Clients : " + response);
                }, error: function (data) {

                }
            });
        }


        function  partners_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".partner_tile").empty();
            $(".partner_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/partner_info/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    $(".partner_tile").empty();
                    $(".partner_tile").append("Partner/s : " + response);
                }, error: function (data) {

                }
            })
        }

        function facilities_tile(county, sub_county, facility, date_from, date_to, tokenizer) {
            $(".facilities_tile").empty();
            $(".facilities_tile").append('<h5>Loading ,Please wait ...</h5>');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>reports/facility_info/',
                dataType: "json",
                data: {county: county, sub_county: sub_county, facility: facility, date_from: date_from, date_to: date_to, tokenizer: tokenizer},
                success: function (response) {
                    $(".facilities_tile").empty();
                    $(".facilities_tile").append("Facility/ies : " + response);
                }, error: function (data) {

                }
            })
        }


        $('.cancel_add_appointment_form').click(function () {
            $(".add_client_div").hide();
            $(".dashboard_info").show();
            $(".quick_links_div").show();
            $(".add_app").show();
            $(".add_appointment_div").hide();
            $(".add_appointment_btn").show();
        });
        $('.add_client_link').click(function () {
            $(".add_client_div").show();
            $(".dashboard_info").hide();
            $(".quick_links_div").hide();
            $(".add_appointment_div").hide();
        });
        $('.go_back_link').click(function () {
            $(".add_client_div").hide();
            $(".dashboard_info").show();
            $(".quick_links_div").show();
            $(".add_appointment_div").hide();
        });

        $(document).on('click', ".go_back_view", function () {
            $(".add_appointment_div").hide();
            $(".table_div").show();
            $(".edit_div").hide();
            $(".add_appointment_btn").show();




            $(".add_client_div").hide();
            $(".dashboard_info").show();
            $(".quick_links_div").show();
            $(".add_appointment_div").hide();





        });



        $(document).on('click', ".add_appointment_btn", function () {
            $(".add_appointment_div").show();
            $(".table_div").hide();
            $(".edit_div").hide();
            $(".add_appointment_btn").hide();


            $(".add_client_div").hide();
            $(".dashboard_info").hide();
            $(".quick_links_div").hide();



        });

    });






</script>



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

            } else if (age >= 40) {

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

            } else if (age >= 40) {

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
            } else if (gender == "4") {
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




        $(".submit_add_appointment").click(function () {
            var controller = "home";
            var submit_function = "add_appointment";
            var form_class = "add_form";
            var success_alert = "Client appointment was added successfully ... :) ";
            var error_alert = "Ooops , An Error Occured (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });













        $(".new_check_clinic_number").click(function () {

            $('.loader').show();
            var clinic_number = $(".app_new_clinic_number").val();
            var mfl_code = $(".mfl_code").val();
            var upn = mfl_code + clinic_number;
            var controller = "home";
            var get_function = "check_clinic_no";
            var error_alert = "Sorry  , Error occured (:";
            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function + "/" + upn,
                dataType: "JSON",
                success: function (response) {
                    $('.loader').hide();
                    var isempty = jQuery.isEmptyObject(response);
                    if (isempty) {
                        swal("Error", "Clinic number  : " + upn + " was not found in the  system ", "warning");
                        $(".hide_other_info").hide();
                    } else {
                        $(".hide_other_info").show();


                        $('.add_fname').val(response[0].f_name);
                        $('.add_mname').val(response[0].m_name);
                        $(".add_lname").val(response[0].l_name);
                        $(".add_mobile").val(response[0].phone_no);
                        $(".app_client_id").val(response[0].id);

                        $.each(response, function (i, value) {




                        });
                    }





                }, error: function (data) {
                    $('.loader').hide();
                    sweetAlert("Oops...", "" + error_alert + "", "error");
                }

            });
        });










        $(".add_new_check_clinic_number").click(function () {
            $('.loader').show();
            var clinic_number = $(".add_new_clinic_number").val();
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
                            var get_function4 = "get_appointment_client_data";
                            var error_alert = "Sorry , we could not process your request right now (:";
                            $('.loader').show();
                            $.ajax({
                                type: "GET",
                                async: true,
                                url: "<?php echo base_url(); ?>" + controller + "/" + get_function4 + "/" + data_id,
                                dataType: "JSON",
                                success: function (response) {
                                    $('.loader').hide();
                                    $.each(response, function (i, value) {

                                        $("#edit_p_custommsg").empty();
                                        $("#edit_p_apptype3").empty();
                                        $("#edit_p_apptype4").empty();
                                        $("#appointment_date").empty();
                                        $('#appointment_date').val(value.appntmnt_date);
                                        $('#edit_p_apptype4').val(value.app_type_4);
                                        $('#edit_p_apptype3').val(value.expln_app);
                                        $('#edit_p_custommsg').val(value.custom_txt);

                                        $('#edit_date_added').val(value.date_added);
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


//            if (new_enrollment_date < art_start_date) {
//
//                alert("Enrollment is less than ART Date");
//            } else {
//            }



        });
        $(".submit_add_new_div").click(function () {

            var controller = "home";
            var submit_function = "add_client";
            var form_class = "add_new_form";
            var success_alert = "Client added successfully ... :) ";
            var error_alert = "Sorry , we could not process your request right now (:";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
//
//                // Wait for the DOM to be ready
//                $(function () {
//                    // Initialize form validation on the registration form.
//                    // It has the name attribute "registration"
//                    $("form[name='add_form']").validate({
//                        // Specify validation rules
//                        rules: {
//                            // The key name on the left side is the name attribute
//                            // of an input field. Validation rules are defined
//                            // on the right side
//                            fname: "required",
//                            mname: "required"
////                            ,
////                            email: {
////                                required: true,
////                                // Specify that email should be validated
////                                // by the built-in "email" rule
////                                email: true
////                            },
////                            password: {
////                                required: true,
////                                minlength: 5
////                            }
//                        },
//                        // Specify validation error messages
//                        messages: {
//                            fname: "Please enter your firstname",
//                            mname: "Please enter your lastname"
////                            ,
////                            password: {
////                                required: "Please provide a password",
////                                minlength: "Your password must be at least 5 characters long"
////                            },
////                            email: "Please enter a valid email address"
//                        },
//                        // Make sure the form is submitted to the destination defined
//                        // in the "action" attribute of the form when valid
//                        submitHandler: function (form) {
//                            submit_data(controller, submit_function, form_class, success_alert, error_alert);
//
//                        }
//                    });
//                });
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


//            if (new_enrollment_date < art_start_date) {
//
//                alert("Enrollment is less than ART Date");
//            } else {
//            }



        });







        var table = dTbles('.table').DataTable({
            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            responsive: true,
            "lengthMenu": [[5, 10, 45, 50, -1], [10, 45, 50, "All"]]
        });
        table.buttons().container()
                .appendTo('.table_wrapper .col-sm-6:eq(0)');









    });
</script>




