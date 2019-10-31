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




        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!--                        <h4 class="card-title">A lis st</h4>
                                                <h6 class="card-subtitle">A list of Partners in the  system </h6>-->
                        <div class="table-responsive m-t-40">





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
                                                <th>Serial No</th>
                                                <th>National ID / Passport</th>
                                                <th>Client Name</th>
                                                <th>Phone No</th>
                                                <th>DoB</th>
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
                                                        <td><?php echo $value->national_id; ?></td>


                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td>XXXXXX XXXXXXX</td>

                                                        <?php
                                                    }
                                                    ?>


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
                                                <th>Serial No </th>
                                                <th>Client Name</th>
                                                <th>Phone No</th>
                                                <th>DoB</th>
                                                <th>Clinic</th>
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
                                                        <td>XXXXXXX XXXXXX</td>
                                                        <td>XXXXXX XXXXXXX</td>
                                                        <td>XXXXXX XXXXXXX</td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td><?php echo $value->dob; ?></td>
                                                    <td><?php echo $value->clinic_name; ?></td>
                                                    <td><?php echo $value->group_name; ?></td>
                                                    <td><?php echo $value->client_status; ?></td>
                                                    <td><?php echo $value->STATUS; ?></td>
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
                                                <th>Serial no </th>
                                                <th>Client Name</th>
                                                <th>Phone No</th>
                                                <th>DoB </th>
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
                                                        <td>XXXXXXX XXXXXX</td>
                                                        <td>XXXXXX XXXXXXX</td>
                                                        <td>XXXXXX XXXXXXX</td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td><?php echo $value->dob; ?></td>
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







                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card card-outline-primary">
                                            <div class="card-header">
                                                <h4 class="m-b-0 text-white">Edit Client</h4>
                                            </div>
                                            <div class="card-body">







                                                <form class="edit_form" id="edit_form">




                                                    <?php
                                                    $csrf = array(
                                                        'name' => $this->security->get_csrf_token_name(),
                                                        'hash' => $this->security->get_csrf_hash()
                                                    );
                                                    ?>

                                                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />




                                                    <div class="row">
                                                        <div class="col-sm-6 ">



                                                            <input type="hidden" id="edit_client_id" class="client_id" name="client_id"/>

                                                            <h2> Client Personal Information </h2> 
                                                            <label class='control-label'>Unique Patient Number* (Unique value : ) Once entered , cannot be changed </label>
                                                            <input class='form-control' type='text' required=''  name='clinic_number'  id='edit_clinic_number' >
                                                            <label class='control-label'>First Name </label> 
                                                            <input class='form-control fname input-rounded input-sm' required="" type='text' name='fname'  id='edit_fname' >

                                                            <label class='control-label'>Middle Name </label>  
                                                            <input type='text' required="" class='form-control mname input-rounded input-sm ' name='mname'  id='edit_mname'>

                                                            <label class='control-label'>Last Name</label>  
                                                            <input class='form-control lame input-rounded input-sm' type='text' name='lname'  id='edit_lname' >

                                                            <label class='control-label'> Date of Birth: </label>
                                                            <input class='form-control dob input-rounded input-sm' required="" readonly="" type='text' name='p_year'  id='edit_dob' >





                                                            <label class='control-label'>  Gender : <span style="color: red">*</span> </label> 
                                                            <select class='form-control gender input-rounded input-sm' required="" name='gender' id='edit_gender'>
                                                                <option value=''>Please select </option>
                                                                <?php foreach ($genders as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                <?php }
                                                                ?>
                                                            </select>


                                                            <label class='control-label'>  Marital Status : <span style="color: red">*</span> </label> 
                                                            <select class='form-control marital input-rounded input-sm' required="" name='marital' id='edit_marital'>
                                                                <option value=''>Please select </option>
                                                                <?php foreach ($maritals as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->marital; ?></option>
                                                                <?php }
                                                                ?>
                                                            </select>




                                                            <label class='control-label'> Treatment</label>

                                                            <select class='form-control condition input-rounded input-sm' required="" name='condition' id='edit_condition'>
                                                                <option value=''> </option>
                                                                <option value='ART'>ART </option>
                                                                <option value='Pre ART'>Pre ART </option>
                                                            </select>







                                                            <label class='control-label'> HIV Enrollment Date: </label> <span style="color: red">*</span>
                                                            <input class='form-control enrollment_date date input-rounded input-sm' readonly="" required="" placeholder="Date enrolled to HIV Care" type='text' name='enrollment_date' id='edit_enrollment_date' >




                                                            <label class='control-label'> ART Start Date: </label> 
                                                            <input class='form-control  date input-rounded input-sm' readonly="" placeholder="ART Start Date" type='text' name='art_date' id='edit_art_date' >








                                                            <label class='control-label'> Select Facility Attached : </label>  
                                                            <?php
                                                            $facility_id = $this->session->userdata('facility_id');
                                                            if (empty($facility_id)) {
                                                                ?>
                                                                <select class='form-control facilities input-rounded input-sm' required="" name='facilities' id='edit_facilities' >
                                                                    <option value=''>Please select </option>
                                                                    <?php foreach ($facilities as $value) {
                                                                        ?>
                                                                        <option value="<?php echo $value->mfl_code; ?>"><?php echo " " . $value->facility_name . " => " . $value->mfl_code . "   " . $value->county_name . "  " . $value->sub_county_name; ?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <input readonly="" type="text" name="facilities" class="form-control facilities input-rounded input-sm" id="edit_facilities" value="<?php echo $facility_id; ?>" />
                                                                <?php
                                                            }
                                                            ?>

                                                        </div>
                                                        <div class="col-sm-6 ">



                                                            <label class='control-label'>Cell Phone Number (Own) </label> 
                                                            <input class='form-control mobile input-rounded input-sm' required=""  type='text' name='mobile' id='edit_mobile'   >
                                                            <label class='control-label'>  Preferred Lang :  </label> 
                                                            <select class='form-control lang input-rounded input-sm' name='lang' id='edit_lang'>
                                                                <option value=''>Please select </option>
                                                                <?php foreach ($langauges as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                <?php }
                                                                ?>
                                                            </select>

                                                            <label class='control-label'>  Enable Message Alerts? </label> 
                                                            <select class='form-control smsenable input-rounded input-sm' required="" name='smsenable' id='edit_smsenable'>
                                                                <option value=''> </option>
                                                                <option value='Yes'> Yes </option>
                                                                <option value='No'> No </option>
                                                            </select>

                                                            <div class="consent_date_div" id="consent_date_div" style="display: none;">

                                                                <label class='control-label'> Consent Date: </label> 
                                                                <input class='form-control consent_date dob input-rounded input-sm' readonly=""  type='text' name='consent_date'  placeholder='Please enter Consent Date' id='edit_consent_date'>
                                                            </div>







                                                            <label class='control-label'>  Receive Weekly Motivational/Informative Messages? <span style="color: red">*</span> </label> 
                                                            <select class='form-control edit_motivational_enable input-rounded input-sm ' required="" name='motivational_enable' id='edit_motivational_enable'>
                                                                <option value=''>Please select : </option>
                                                                <option value='Yes'> Yes </option>
                                                                <option value='No'> No </option>
                                                            </select>



                                                            <label class='control-label'>Preferred Messaging Time </label> 
                                                            <select name='time' class='form-control time input-rounded input-sm' id='edit_time'>"
                                                                <option value=''>Please select </option>
                                                                <?php foreach ($times as $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                                                <?php }
                                                                ?>
                                                            </select>


                                                            <label class='control-label'>  Client Status :  </label> 
                                                            <select class='form-control edit_status input-rounded input-sm' required="" name='status' id='edit_status'>
                                                                <option value=''> Please select : </option>
                                                                <option value='Active'> Active </option>
                                                                <option value='Disabled'> Disabled </option>
                                                                <option value="Transfered Out">Transfered Out</option>
                                                                <option value="Deceased">Deceased</option>
                                                            </select>
                                                            <div id="transfer_date_div" class="transfer_date_div" style="display: none;">
                                                                <label class='control-label'>Transfer Date</label> 
                                                                <input class='form-control transfer_date date input-rounded input-sm ' readonly=""  type='text' name='transfer_date' placeholder="Please enter Transfer Date " id='edit_transfer_date'>
                                                                <label class='control-label'>Please specify new Clinic</label> 
                                                                <input class='form-control transfer_new_clinic input-rounded input-sm'  type='text' name='transfer_new_clinic' placeholder="Please enter New Clinic MFL Code " id='edit_transfer_new_clinic'>
                                                                <span class="selected_facility_name" id="selected_facility_name"></span>
                                                            </div>

                                                            <div class='form'>
                                                                <!--                                                                    <h2> Client Appointment Information </h2> 
                                                                                                                                    <label class='control-label'> Next Appointment Date </lable> 
                                                                                                                                        <input type='text' readonly=""  class='form-control appointment_date' name='apptdate' id='appointment_date'/> 
                                                                                                                                        <input type='hidden'  name='appointment_type' id='edit_appointment_type' value='Appointment' class='form-control' readonly/>
                                                                                                                                        <label class='control-label'> Appointment Status </label>
                                                                                                                                        <input type='hidden' class='form-control' name='appstatus' id='edit_appstatus' value='Booked'/>
                                                                
                                                                                                                                        <div class="appointment_kept_div" id="appointment_kept_div" style="display: inline;">
                                                                
                                                                                                                                        </div>
                                                                
                                                                                                                                        <label class='control-label'> Appointment Type 1: </label>
                                                                                                                                        <select class='form-control' name='p_apptype1' id='edit_p_apptype1' >
                                                                                                                                            <option value=''>Please Select : </option>
                                                                <?php
                                                                foreach ($appointment_types as $value) {
                                                                    ?>
                                                                                                                                                            <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                                                                                        </select>-->


                                                                <input type="hidden" name="appointment_id" class="appointment_id form-control" id="edit_appointment_id" />

                                                                <hr>

                                                                <button type="submit" class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update Client</button>
                                                                <button type="button" class="cancel_add_client btn btn-danger btn-small" id="cancel_add_client">Cancel</button>

                                                            </div>


                                                        </div>
                                                    </div>







                                                </form>








                                            </div>
                                        </div></div></div>















                                <div class="panel-body  formData" id="editForm">
                                    <h2 id="actionLabel">Edit Client</h2>






                                </div>




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




<!--END MAIN WRAPPER -->



<script type="text/javascript">
    $(document).ready(function () {

        $(".transfer_new_clinic").keyup(function () {
            var transfer_mfl_no = $(".transfer_new_clinic").val();
            if (transfer_mfl_no.length < 5) {

            } else if (transfer_mfl_no.length == 5) {

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
                            $(".selected_facility_name").empty();
                        } else {

                            $.each(response, function (i, value) {


                                var facility_name = "You selected : " + value.name;
                                $(".selected_facility_name").append(facility_name);
                                $(".load_client_details").show();
                            });
                        }





                    }, error: function (data) {
                        $('.loader').hide();
                        sweetAlert("Oops...", "" + error_alert + "", "error");
                    }

                });
            } else if (transfer_mfl_no.length > 5) {
                $(".transfer_new_clinic").val("");
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

        $(".edit_status").change(function () {
            var status_value = this.value;

            if (status_value == "Transfer Out") {
                $(".transfer_date_div").show();
            } else {
                $(".transfer_date").empty();
                $(".transfer_date_div").hide();
            }

        });




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



                        var status_value = value.smsenable;

                        if (status_value === "Yes") {
                            $(".consent_date_div").show();
                            $(".consent_date").empty();
                            //var consent_date = "<label class='control-label'> Consent Date : </label>    <input class='form-control consent_date date'  type='text' name='consent_date'  placeholder='Please enter Consent Date' id='edit_consent_date'>";
                            //$(".consent_date_div").append(consent_date);

                            var consent_date = value.consent_date;
                            if (!consent_date) {
                            } else {


                                var consent_res = consent_date.substring(0, 10);
                                var new_consent_date = consent_res.split("-").reverse().join("/");

                                $('.consent_date').val(new_consent_date);
                            }

                        } else {
                            $(".consent_date").empty();
                            $(".consent_date_div").hide();
                        }




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
            var error_alert = "An Error Ocurred";

            $('.loader').show();

            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>" + controller + "/" + get_function2 + "/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".appointment_kept_div").empty();
                    $('.loader').hide();
                    $.each(response, function (i, value) {


                        var appointment_date = value.appntmnt_date;
                        if (appointment_date) {

                            var appointment_kept = "<label class='control-label'> Was the  previous appointment Kept ? :</label>\n\
                    <select class='form-control' name='app_kept' id='app_kept' required='' > \n\
                     <option value=''>Please select :</option> <option value='Yes'>Yes</option>\n\
                     <option value='No'>No</option> \n\
                    </select>";
                            $('.appointment_kept_div').append(appointment_kept);
                        }


                        $("#edit_p_custommsg").empty();

                        $("#edit_p_apptype3").empty();

                        $("#edit_p_apptype2").empty();

                        $("#appointment_date").empty();



                        $("#edit_appointment_id").val(value.appointment_id);

                        var appntmnt_date = value.appntmnt_date;
                        var res = appntmnt_date.substring(0, 10);
                        var appntmnt_date = res.split("-").reverse().join("/");

                        $('#appointment_date').val(appntmnt_date);

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
            var error_alert = "Success (:";

            submit_data(controller, submit_function, form_class, success_alert, error_alert);


        });









    });
</script>




<!--END MAIN WRAPPER -->




