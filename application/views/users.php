<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                System Users 
            </div>   
            <div >


                <div class="panel-body"> 

                    <button class="add_btn btn btn-primary btn-small" id="add_btn">Add User</button>
                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>User Name</th>
                                    <th>DoB</th>
                                    <th>Phone No</th>
                                    <th>Email</th>
                                    <th>Access Level</th>
                                    <th>Status</th>
                                    <th>Date Added</th>
                                    <th>Time Stamp</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Reset Pass</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($users as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->f_name . " " . $value->m_name . " " . $value->l_name; ?></td>
                                        <td><?php echo $value->dob; ?></td>
                                        <td><?php echo $value->phone_no; ?></td>
                                        <td><?php echo $value->e_mail; ?></td>
                                        <td><?php echo $value->access_level; ?></td>
                                        <td><?php
                                            $status = $value->status;
                                            if ($status == "Active") {
                                                ?>
                                                <span style="color: green"><?php echo $status; ?></span>
                                                <?php
                                            } else {
                                                ?>
                                                <span style="color: red"><?php echo $status; ?></span>
                                                <?php
                                            }
                                            ?></td>
                                        <td><?php echo $value->created_at; ?></td>
                                        <td><?php echo $value->updated_at; ?></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                            <button class="btn btn-primary edit_btn" id="edit_btn">Edit </button></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                            <button class="btn btn-primary delete_btn" id="delete_btn">Delete </button></td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $value->id; ?>" class="id"/>
                                            <button class="btn btn-primary reset_btn" id="reset_btn">Reset </button></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>

                        <input type="hidden" name="user_access_level" id="user_access_level" class="user_access_level form-control" value="<?php echo $this->session->userdata('access_level'); ?>"/>
                    </div>









                    <div class="add_div" style="display: none;">

                        <div class="panel-body  formData" id="addForm">
                            <h2 id="actionLabel">Add User</h2>

                            <form class="form add_form" id="add_form">
                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                <div class="form-group">
                                    <label>First Name : </label> 
                                    <input type="text"  required="" name="f_name" class="form-control f_name"/>

                                </div> 


                                <div class="form-group">
                                    <label>Middle Name : </label> 
                                    <input type="text"  required="" name="m_name" class="form-control m_name"/>

                                </div> 

                                <div class="form-group">
                                    <label>Last Name : </label> 
                                    <input type="text"  name="l_name" class="form-control l_name"/>

                                </div> 


                                <div class="form-group">
                                    <label>D.o.B : </label> 
                                    <input type="text" readonly=""  name="dob" class="form-control dob"/>

                                </div> 


                                <div class="form-group">
                                    <label>E mail : </label> 
                                    <input type="email"  required="" name="e_mail" class="form-control e_mail"/>

                                </div> 

                                <div class="form-group">
                                    <label>Phone No : </label> 
                                    <input type="text"  required="" name="phone_no" pattern="^(([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+$" placeholder="Phone No should be 10 Digits " id="phone_no" class="form-control phone_no"/>

                                </div> 









                                <div class="form-group">
                                    <label>Access Level : </label> 
                                    <!--<input type="text"  required="" name="access_level" class="form-control access_level"/>-->
                                    <select name="access_level" class="form-control add_access_level" required="" id="add_access_level" >
                                        <option value="">Please select : </option>
                                        <?php
                                        foreach ($access_levels as $value) {
                                            ?>
                                            <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div> 


                                <span class="loading-img add_loading_option" style="display: none;">Please wait , Loading Roles ...</span>


                                <div class="add_dynamic_options" id="add_dynamic_options" style="display: none">


                                    <div class="form-group">
                                        <label>Role Name : </label> 
                                        <select name="role_names" required="" class="form-control add_role_names" id="role_names" >
                                            <option value="">Please select</option>


                                        </select>
                                    </div>




                                    <div class="add_donor_div" id="add_donor_div">
                                        <div class="form-group">
                                            <label>Donor : </label>

                                            <select class="form-control donor_id"  id="donor_id" name="donor_id">
                                                <option value="">Please select : </option>
                                                <?php foreach ($donors as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                                <?php }
                                                ?>
                                            </select> 

                                        </div>
                                    </div>
                                    <div class="add_county_div" id="add_county_div">
                                        <div class="form-group">
                                            <label>County : </label>

                                            <select class="form-control county_id add_county_id"  id="county_id" name="county_id">
                                                <option value="">Please select : </option>
                                                <?php foreach ($counties as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                                <?php }
                                                ?>
                                            </select> 

                                        </div>
                                    </div>
                                    <div class="add_subcounty_div" id="add_subcounty_div">

                                        <div class="form-group">
                                            <label>Sub County : </label>

                                            <select class="form-control add_subcounty_id"  id="add_subcounty_id" name="subcounty_id">
                                                <option value="">Please select : </option>

                                            </select> 

                                        </div>
                                    </div>

                                    <div class="add_partner_div" id="add_partner_div">
                                        <div class="form-group">
                                            <label>Partner : </label>
                                            <select class="form-control partner_id"  id="partner_id" name="partner_id">
                                                <option value="">Please select : </option>
                                                <?php foreach ($partners as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                                <?php }
                                                ?>
                                            </select> 
                                        </div> 
                                    </div>

                                    <div class="add_facilty_div" id="add_facility_div">
                                        <div class="form-group">
                                            <label>Facility : </label>
                                            <select class="form-control facility_id"  id="facility_id" name="facility_id">
                                                <option value="">Please select : </option>
                                                <?php foreach ($facilities as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->code ?>"> <?php echo $value->name ?></option>
                                                <?php }
                                                ?>
                                            </select> 
                                        </div>  

                                    </div>



                                    <div class="form-group">
                                        <label>View Patients Bio Data ? : </label> 
                                        <select name="bio_data" class="form-control bio_data" id="bio_data" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Receive Daily Reports ? : </label> 
                                        <select name="daily_report" class="form-control daily_report" id="daily_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Receive Weekly Reports : </label> 
                                        <select name="weekly_report" class="form-control weekly_report" id="weekly_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Receive Monthly Reports : </label> 
                                        <select name="monthly_report" class="form-control monthly_report" id="monthly_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Receive 3 Months Reports : </label> 
                                        <select name="month3_report" class="form-control month3_report" id="month3_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Receive  6 Months Reports : </label> 
                                        <select name="month6_report" class="form-control month6_report" id="month6_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Receive Yearly Reports : </label> 
                                        <select name="yearly_report" class="form-control yearly_report" id="yearly_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Status : </label> 
                                        <select name="status" class="form-control status" id="status" >
                                            <option value="">Please select</option>
                                            <option value="Active">Active</option>
                                            <option value="Disabled">Disabled</option>
                                        </select>
                                    </div>



                                    <!--                                </div>-->



                                    <button class="submit_add_div btn btn-success btn-small" id="submit_add_div">Add User</button>
                                    <button class="close_add_div btn btn-danger btn-small" id="close_add_div">Cancel</button>


                                </div>

                            </form>





                        </div>




                    </div>






                    <div class="edit_div" style="display: none;">

                        <div class="panel-body  formData" id="editForm">
                            <h2 id="actionLabel">Edit User</h2>

                            <form class="form edit_form" id="edit_form">
                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                <div class="form-group">
                                    <label>First Name : </label> 
                                    <input type="text"  required="" name="f_name" class="form-control f_name" id="edit_f_name"/>

                                </div> 


                                <div class="form-group">
                                    <label>Middle Name : </label> 
                                    <input type="text"  required="" name="m_name" class="form-control m_name" id="edit_m_name"/>

                                </div> 

                                <div class="form-group">
                                    <label>Last Name : </label> 
                                    <input type="text"  required="" name="l_name" class="form-control l_name" id="edit_l_name"/>

                                </div> 


                                <div class="form-group">
                                    <label>D.o.B : </label> 
                                    <input type="text" readonly=""  required="" name="dob" id="edit_dob" class="form-control dob" id="edit_dob"/>

                                </div> 


                                <div class="form-group">
                                    <label>E mail : </label> 
                                    <input type="email"  required="" name="e_mail" class="form-control e_mail" id="edit_e_mail"/>

                                </div> 

                                <div class="form-group">
                                    <label>Phone No : </label> 
                                    <input type="text"  required="" name="phone_no" id="edit_phone_no" class="form-control phone_no"  pattern="^(([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+$" placeholder="Phone No should be 10 Digits "  id="edit_phone_no"/>

                                </div>
                                <div class="form-group">
                                    <label>Access Level : </label> 
                                    <select name="access_level" class="form-control edit_access_level" id="edit_access_level" >
                                        <option value="">Please select</option>
                                        <?php
                                        foreach ($access_levels as $value) {
                                            ?>
                                            <option value="<?php echo $value->name; ?>"><?php echo $value->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <span class="loading-img edit_loading_option" style="display: none;">Please wait , Loading Roles ...</span>

                                <div class="edit_dynamic_options" id="edit_dynamic_options" style="display: none;">

                                    <div class="form-group">
                                        <label>Role Name : </label> 
                                        <select name="role_names" required="" class="form-control edit_role_names" id="edit_role_names" >
                                            <option value="">Please select</option>


                                        </select>
                                    </div>


                                    <div class="form-group donor_div" id="edit_donor_div">
                                        <label>Donor : </label>

                                        <select class="form-control donor_id"  id="edit_donor_id" name="donor_id">
                                            <option value="">Please select : </option>
                                            <?php foreach ($donors as $value) {
                                                ?>
                                                <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                            <?php }
                                            ?>
                                        </select> 

                                    </div>


                                    <div class="edit_county_div" id="edit_county_div">
                                        <div class="form-group">
                                            <label>County : </label>

                                            <select class="form-control county_id edit_county_id"  id="county_id" name="county_id">
                                                <option value="">Please select : </option>
                                                <?php foreach ($counties as $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                                <?php }
                                                ?>
                                            </select> 

                                        </div>
                                    </div>
                                    <div class="edit_subcounty_div" id="edit_subcounty_div">

                                        <div class="form-group">
                                            <label>Sub County : </label>

                                            <select class="form-control edit_subcounty_id"  id="edit_subcounty_id" name="subcounty_id">
                                                <option value="">Please select : </option>

                                            </select> 

                                        </div>
                                    </div>






                                    <div class="form-group partner_div" id="edit_partner_div">
                                        <label>Partner : </label>
                                        <select class="form-control partner_id"  id="edit_partner_id" name="partner_id">
                                            <option value="">Please select : </option>
                                            <?php foreach ($partners as $value) {
                                                ?>
                                                <option value="<?php echo $value->id ?>"> <?php echo $value->name ?></option>
                                            <?php }
                                            ?>
                                        </select> 
                                    </div>  


                                    <div class="form-group facility_div" id="edit_facility_div">
                                        <label>Facility : </label>
                                        <select class="form-control facility_id"  id="edit_facility_id" name="facility_id">
                                            <option value="">Please select : </option>
                                            <?php foreach ($facilities as $value) {
                                                ?>
                                                <option value="<?php echo $value->code ?>"> <?php echo $value->name ?></option>
                                            <?php }
                                            ?>
                                        </select> 
                                    </div>  

                                    <div class="form-group">
                                        <label>View Patients Bio Data ? : </label> 
                                        <select name="bio_data" class="form-control bio_data" id="edit_bio_data" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Receive Daily Reports ? : </label> 
                                        <select name="daily_report" class="form-control daily_report" id="edit_daily_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Receive Weekly Reports : </label> 
                                        <select name="weekly_report" class="form-control weekly_report" id="edit_weekly_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Receive Monthly Reports : </label> 
                                        <select name="monthly_report" class="form-control monthly_report" id="edit_monthly_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Receive 3 Months Reports : </label> 
                                        <select name="month3_report" class="form-control month3_report" id="edit_month3_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Receive  6 Months Reports : </label> 
                                        <select name="month6_report" class="form-control month6_report" id="edit_month6_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Receive Yearly Reports : </label> 
                                        <select name="yearly_report" class="form-control yearly_report" id="edit_yearly_report" >
                                            <option value="">Please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>













                                    <div class="form-group">
                                        <label>Status : </label> 
                                        <select name="status" class="form-control status" id="edit_status" >
                                            <option value="">Please select</option>
                                            <option value="Active">Active</option>
                                            <option value="Disabled">Disabled</option>
                                        </select>
                                    </div>





                                    <input type="hidden" name="user_id" class="form-control user_id" id="edit_user_id" />




                                    <button class="submit_edit_div btn btn-success btn-small" id="submit_edit_div">Update User</button>
                                    <button class="close_edit_div btn btn-danger btn-small" id="close_edit_div">Cancel</button>

                                </div>


                            </form>





                        </div>




                    </div>





                    <div class="delete_div" style="display: none;">


                        <div class="panel-body  formData" id="deleteForm">
                            <h2 id="actionLabel">Delete User</h2>


                            <form class="form delete_form" id="delete_form">
                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <p><span class="delete_description"></span></p>


                                <input type="hidden" name="user_id" class="form-control user_id" id="delete_user_id" />




                                <button class="submit_delete_div btn btn-success btn-small" id="submit_delete_div">Delete User</button>
                                <button class="close_delete_div btn btn-danger btn-small" id="close_delete_div">Cancel</button>
                            </form>





                        </div>




                    </div>


                    <div class="reset_div" style="display: none;">


                        <div class="panel-body  formData" id="resetForm">
                            <h2 id="actionLabel">Reset User</h2>


                            <form class="form reset_form" id="reset_form">
                                <?php
                                $csrf = array(
                                    'name' => $this->security->get_csrf_token_name(),
                                    'hash' => $this->security->get_csrf_hash()
                                );
                                ?>

                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                                <p><span class="reset_description"></span></p>


                                <input type="hidden" name="user_id" class="form-control user_id" id="reset_user_id" />




                                <button class="submit_reset_div btn btn-success btn-small" id="submit_reset_div">Reset User</button>
                                <button class="close_reset_div btn btn-danger btn-small" type="button" id="close_reset_div">Cancel</button>
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



<input type="text" name="user_county_id" value="<?php echo $this->session->userdata('county_id'); ?>" class="form-control user_county_id" id="user_county_id"/>




<script type="text/javascript">
    $(document).ready(function () {







        var user_access_level = $(".user_access_level").val();

        if (user_access_level == "Admin") {

//            $(".add_access_level option[value='Facility']").remove();
//            $(".edit_access_level option[value='Facility']").remove();

        } else if (user_access_level == "Partner") {
            $(".add_access_level option[value='Admin']").remove();
            $(".add_access_level option[value='Donor']").remove();
            $(".edit_access_level option[value='Admin']").remove();
            $(".edit_access_level option[value='Donor']").remove();

        } else if (user_access_level == "Facility") {
            $(".add_access_level option[value='Admin']").remove();
            $(".add_access_level option[value='Donor']").remove();
            $(".add_access_level option[value='Partner']").remove();
            $(".add_access_level option[value='County']").remove();
            $(".add_access_level option[value='Sub County']").remove();
            $(".add_access_level option[value='Partner']").remove();
            $(".edit_access_level option[value='Admin']").remove();
            $(".edit_access_level option[value='Donor']").remove();
            $(".edit_access_level option[value='Partner']").remove();
            $(".edit_access_level option[value='County']").remove();
            $(".edit_access_level option[value='Sub County']").remove();
            $(".edit_access_level option[value='Partner']").remove();
        } else if (user_access_level == "Donor") {
            $(".add_access_level option[value='Admin']").remove();
            $(".add_access_level option[value='Facility']").remove();
            $(".add_access_level option[value='Partner']").remove();
            $(".add_access_level option[value='County']").remove();
            $(".add_access_level option[value='Sub County']").remove();
            $(".add_access_level option[value='Partner']").remove();
            $(".edit_access_level option[value='Admin']").remove();
            $(".edit_access_level option[value='Facility']").remove();
            $(".edit_access_level option[value='Partner']").remove();
            $(".edit_access_level option[value='County']").remove();
            $(".edit_access_level option[value='Sub County']").remove();
            $(".edit_access_level option[value='Partner']").remove();


        } else if (user_access_level == "County") {
            $(".add_access_level option[value='Admin']").remove();
            $(".add_access_level option[value='Donor']").remove();
            $(".add_access_level option[value='Partner']").remove();
            $(".edit_access_level option[value='Admin']").remove();
            $(".edit_access_level option[value='Donor']").remove();
            $(".edit_access_level option[value='Partner']").remove();

            get_county_facilities();


        } else {
            $(".add_access_level option[value='Admin']").remove();
            $(".add_access_level option[value='Facility']").remove();
            $(".add_access_level option[value='Partner']").remove();
            $(".add_access_level option[value='Donor']").remove();
            $(".add_access_level option[value='County']").remove();
            $(".add_access_level option[value='Sub County']").remove();




            $(".edit_access_level option[value='Admin']").remove();
            $(".edit_access_level option[value='Facility']").remove();
            $(".edit_access_level option[value='Partner']").remove();
            $(".edit_access_level option[value='Donor']").remove();
            $(".edit_access_level option[value='County']").remove();
            $(".edit_access_level option[value='Sub County']").remove();

        }





        $("#add_access_level").change(function () {
            $(".add_loading_option").show();
            $(".add_dynamic_options").hide();
            var access_level = this.value;
            var plse_select = "<option value=''>Please Select : </option>";
            $.ajax({
                url: "<?php echo base_url() ?>admin/get_access_roles/" + access_level + "",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".add_loading_option").hide();
                    $(".add_dynamic_options").show();
                    $(".add_role_names").empty();

                    $(".add_role_names").append(plse_select);
                    $.each(data, function (i, key) {
                        var option = "<option value=" + key.id + ">" + key.name + "</option>";
                        $(".add_role_names").append(option);
                    });
                }, error: function (errorThrown) {

                }
            });
        });


        function get_county_facilities() {



            $(".add_loading_option").show();
            $(".add_dynamic_options").hide();
            var county_id = $(".user_county_id").val();

            var plse_select = "<option value=''>Please Select : </option>";
            $.ajax({
                url: "<?php echo base_url() ?>admin/get_county_facilities/" + county_id + "",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {

                    $(".add_loading_option").hide();
                    //$(".add_dynamic_options").show();
                    $(".facility_id").empty();
//                    $("#edit_facility_id").empty();

                    $(".facility_id").append(plse_select);
                    $.each(data, function (i, key) {
                        var option = "<option value=" + key.id + ">" + key.name + "</option>";
                        $(".facility_id").append(option);
                    });
                }, error: function (errorThrown) {

                }
            });













        }




        $(".add_county_id").change(function () {
            $(".add_loading_option").show();
            $(".add_dynamic_options").hide();
            var county_id = this.value;

            var plse_select = "<option value=''>Please Select : </option>";
            $.ajax({
                url: "<?php echo base_url() ?>admin/get_sub_counties/" + county_id + "",
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    $(".add_loading_option").hide();
                    $(".add_dynamic_options").show();
                    $(".add_subcounty_id").empty();

                    $(".add_subcounty_id").append(plse_select);
                    $.each(data, function (i, key) {
                        var option = "<option value=" + key.id + ">" + key.name + "</option>";
                        $(".add_subcounty_id").append(option);
                    });
                }, error: function (errorThrown) {

                }
            });
        });
        $(".edit_county_id").change(function () {
            $(".edit_loading_option").show();
            $(".edit_dynamic_options").hide();
            var county_id = this.value;

            var plse_select = "<option value=''>Please Select : </option>";
            $.ajax({
                url: "<?php echo base_url() ?>admin/get_sub_counties/" + county_id + "",
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    $(".edit_loading_option").hide();
                    $(".edit_dynamic_options").show();
                    $(".edit_subcounty_id").empty();

                    $(".edit_subcounty_id").append(plse_select);
                    $.each(data, function (i, key) {
                        var option = "<option value=" + key.id + ">" + key.name + "</option>";
                        $(".edit_subcounty_id").append(option);
                    });
                }, error: function (errorThrown) {

                }
            });
        });




        $("#edit_access_level").change(function () {
            $(".edit_loading_option").show();
            $(".edit_dynamic_options").hide();
            var access_level = this.value;
            var plse_select = "<option value=''>Please Select : </option>";
            $.ajax({
                url: "<?php echo base_url() ?>admin/get_access_roles/" + access_level + "",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".edit_loading_option").hide();
                    $(".edit_dynamic_options").show();
                    $(".edit_role_names").empty();

                    $(".edit_role_names").append(plse_select);
                    $.each(data, function (i, key) {
                        var option = "<option value=" + key.id + ">" + key.name + "</option>";
                        $(".edit_role_names").append(option);
                    });
                }, error: function (errorThrown) {

                }
            });
        });





        $("#edit_access_level").change(function () {

            var access_level = this.value;
            if (access_level == "Admin") {

                $('select#edit_facility_id option').removeAttr("selected");
                $("#edit_donor_div").show();
                $("#edit_partner_div").hide();
                $("#edit_facility_div").hide();
            } else if (access_level == "Partner") {

                $('select#edit_donor_id option').removeAttr("selected");
                $("#edit_donor_div").hide();
                $("#edit_partner_div").show();
                $("#edit_facility_div").hide();
            } else if (access_level == "Facility") {
                $('select#edit_partner_id option').removeAttr("selected");
                $('select#edit_donor_id option').removeAttr("selected");
                $("#edit_donor_div").hide();
                $("#edit_partner_div").hide();
                $("#edit_facility_div").show();
            } else if (access_level == "Donor") {

                $('select#edit_partner_id option').removeAttr("selected");
                $('select#edit_facility_id option').removeAttr("selected");
                $("#edit_donor_div").show();
                $("#edit_partner_div").hide();
                $("#edit_facility_div").hide();
            }
            $("#edit_dynamic_options").show();



        });




        $("#add_access_level").change(function () {
            var access_level = this.value;

            if (access_level == "Admin") {
                $('select#partner_id option').removeAttr("selected");
                $('select#facility_id option').removeAttr("selected");


                $("#add_donor_div").show();
                $("#add_partner_div").hide();
                $("#add_facility_div").hide();
                $("#add_subcounty_div").hide();
                $("#add_county_div").hide();
            } else if (access_level == "Partner") {
                $('select#donor_id option').removeAttr("selected");
                $('select#facility_id option').removeAttr("selected");

                $("#add_donor_div").hide();
                $("#add_partner_div").show();
                $("#add_facility_div").hide();
                $("#add_subcounty_div").hide();
                $("#add_county_div").hide();
            } else if (access_level == "Facility") {
                $('select#partner_id option').removeAttr("selected");
                $('select#donor_id option').removeAttr("selected");

                $("#add_donor_div").hide();
                $("#add_partner_div").hide();
                $("#add_facility_div").show();
                $("#add_subcounty_div").hide();
                $("#add_county_div").hide();
            } else if (access_level == "Donor") {
                $('select#partner_id option').removeAttr("selected");
                $('select#facility_id option').removeAttr("selected");

                $("#add_donor_div").show();
                $("#add_partner_div").hide();
                $("#add_facility_div").hide();
                $("#add_subcounty_div").hide();
                $("#add_county_div").hide();


            } else if (access_level == "County") {
                $('select#partner_id option').removeAttr("selected");
                $('select#facility_id option').removeAttr("selected");
                $('select#donor_id option').removeAttr("selected");
                $("#add_subcounty_div").hide();
                $("#add_county_div").show();
                $("#add_partner_div").hide();
                $("#add_donor_div").hide();
                $("#add_facility_div").hide();

            } else if (access_level == "Sub County") {
                $('select#partner_id option').removeAttr("selected");
                $('select#facility_id option').removeAttr("selected");
                $('select#donor_id option').removeAttr("selected");
                $('select#county_id option').removeAttr("selected");

                $("#add_county_div").show();
                $("#add_subcounty_div").show();

                $("#add_partner_div").hide();
                $("#add_donor_div").hide();
                $("#add_facility_div").hide();
            }
            //$("#add_dynamic_options").show();



        });









        $(document).on('click', ".add_btn", function () {
            $(".name").empty();


            $(".f_name").empty();

            $(".m_name").empty();

            $(".l_name").empty();

            $(".dob").empty();

            $(".phone_no").empty();

            $(".e_mail").empty();



            $(".add_div").show();
            $(".table_div").hide();


        });


        function get_access_level(access_level) {
            $(".edit_loading_option").show();
            $(".edit_dynamic_options").hide();

            var plse_select = "<option value=''>Please Select : </option>";
            $.ajax({
                url: "<?php echo base_url() ?>admin/get_access_roles/" + access_level + "",
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    $(".edit_loading_option").hide();
                    $(".edit_dynamic_options").show();
                    $(".edit_role_names").empty();

                    $(".edit_role_names").append(plse_select);
                    $.each(data, function (i, key) {
                        var option = "<option value=" + key.id + ">" + key.name + "</option>";
                        $(".edit_role_names").append(option);
                    });
                }, error: function (errorThrown) {

                }
            });
        }

        $(document).on('click', ".edit_btn", function () {
            $(".loader").show();

            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var error_alert = "An Error Ocurred";



            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_user_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {
                        $("#edit_user_id").empty();



                        $("#edit_f_name").empty();

                        $("#edit_m_name").empty();

                        $("#edit_l_name").empty();

                        $("#edit_dob").empty();

                        $("#edit_phone_no").empty();

                        $("#edit_e_mail").empty();


                        $('#edit_user_id').val(value.id);
                        $('#edit_status option[value=' + value.status + ']').attr("selected", "selected");
                        // $('#edit_access_level option[value=' + value.access_level + ']').attr("selected", "selected");
                        $('#edit_access_level option[value="' + value.access_level + '"]').attr("selected", "selected");
                        $('#edit_donor_id option[value=' + value.donor_id + ']').attr("selected", "selected");
                        $('#edit_partner_id option[value=' + value.partner_id + ']').attr("selected", "selected");
                        $('#edit_facility_id option[value=' + value.facility_id + ']').attr("selected", "selected");
                        $('#edit_bio_data option[value=' + value.view_client + ']').attr("selected", "selected");
                        $('#edit_f_name').val(value.f_name);
                        $('#edit_m_name').val(value.m_name);
                        $('#edit_l_name').val(value.l_name);
                        $('#edit_phone_no').val(value.phone_no);
                        $('#edit_dob').val(value.dob);
                        $('#edit_e_mail').val(value.e_mail);
                        $('#edit_created_at').val(value.created_at);
                        $('#edit_timestamp').val(value.timestamp);
                        $('#edit_daily_report option[value=' + value.daily_report + ']').attr("selected", "selected");
                        $('#edit_weekly_report option[value=' + value.weekly_report + ']').attr("selected", "selected");
                        $('#edit_monthly_report option[value=' + value.monthly_report + ']').attr("selected", "selected");
                        $('#edit_month3_report option[value=' + value.month3_report + ']').attr("selected", "selected");
                        $('#edit_month6_report option[value=' + value.month6_report + ']').attr("selected", "selected");
                        $('#edit_yearly_report option[value=' + value.Yearly_report + ']').attr("selected", "selected");



                        var access_level = value.access_level;
                        console.log("Access level => " + access_level);

                        if (access_level == "Admin") {

                            $('select#edit_facility_id option').removeAttr("selected");
                            $("#edit_donor_div").show();
                            $("#edit_partner_div").hide();
                            $("#edit_facility_div").hide();
                            $("#edit_county_div").hide();
                            $("#edit_subcounty_div").hide();
                            get_access_level(access_level);
                        } else if (access_level == "Partner") {

                            $('select#edit_donor_id option').removeAttr("selected");
                            $("#edit_donor_div").hide();
                            $("#edit_partner_div").show();
                            $("#edit_facility_div").hide();
                            $("#edit_county_div").hide();
                            $("#edit_subcounty_div").hide();
                            get_access_level(access_level);
                        } else if (access_level == "Facility") {
                            $('select#edit_partner_id option').removeAttr("selected");
                            $('select#edit_donor_id option').removeAttr("selected");
                            $("#edit_donor_div").hide();
                            $("#edit_partner_div").hide();
                            $("#edit_facility_div").show();
                            $("#edit_county_div").hide();
                            $("#edit_subcounty_div").hide();
                            get_access_level(access_level);
                        } else if (access_level == "Donor") {

                            $('select#edit_partner_id option').removeAttr("selected");
                            $('select#edit_facility_id option').removeAttr("selected");
                            $("#edit_donor_div").show();
                            $("#edit_partner_div").hide();
                            $("#edit_facility_div").hide();
                            $("#edit_county_div").hide();
                            $("#edit_subcounty_div").hide();
                            get_access_level(access_level);

                        } else if (access_level == "County") {

                            $('select#edit_partner_id option').removeAttr("selected");
                            $('select#edit_donor_id option').removeAttr("selected");
                            $("#edit_donor_div").hide();
                            $("#edit_partner_div").hide();
                            $("#edit_facility_div").hide();
                            $("#edit_county_div").show();
                            $("#edit_subcounty_div").hide();
                            get_access_level(access_level);

                        } else if (access_level == "Sub County") {

                            $('select#edit_partner_id option').removeAttr("selected");
                            $('select#edit_donor_id option').removeAttr("selected");
                            $("#edit_donor_div").hide();
                            $("#edit_partner_div").hide();
                            $("#edit_facility_div").hide();
                            $("#edit_county_div").show();
                            $("#edit_subcounty_div").show();
                            get_access_level(access_level);
                        }
                        $("#edit_dynamic_options").show();






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
            var error_alert = "An Error Ocurred";


            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_user_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {
                        $("#delete_user_id").empty();

                        $('#delete_user_id').val(value.id);
                        $('#delete_f_name').val(value.f_name);
                        $('#delete_m_name').val(value.m_name);
                        $('#delete_l_name').val(value.l_name);

                        var delete_descripton = "Do you want to delete User : " + value.f_name + " " + value.m_name + " " + value.l_name + "";
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

        $(document).on('click', ".reset_btn", function () {

            $(".loader").show();
            //get data
            var data_id = $(this).closest('tr').find('input[name="id"]').val();
            var error_alert = "An Error Ocurred";


            $.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_user_data/" + data_id,
                dataType: "JSON",
                success: function (response) {
                    $(".loader").hide();
                    $.each(response, function (i, value) {
                        $("#reset_user_id").empty();

                        $('#reset_user_id').val(value.id);
                        $('#reset_f_name').val(value.f_name);
                        $('#reset_m_name').val(value.m_name);
                        $('#reset_l_name').val(value.l_name);

                        var reset_descripton = "Do you want to reset password for  User : " + value.f_name + " " + value.m_name + " " + value.l_name + "";
                        $(".reset_description").append(reset_descripton);
                    });


                    $(".reset_div").show();
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

        $(".close_delete_div").click(function () {
            $(".delete_div").hide();
            $(".table_div").show();
        });


        $(".close_edit_div").click(function () {
            $(".edit_div").hide();
            $(".table_div").show();
        });


        $(".close_reset_div").click(function () {
            $(".reset_div").hide();
            $(".table_div").show();
        });





        $(".submit_add_div").click(function () {
            var controller = "admin";
            var submit_function = "add_user";
            var form_class = "add_form";
            var success_alert = "User added successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });



        $(".submit_edit_div").click(function () {
            var controller = "admin";
            var submit_function = "edit_user";
            var form_class = "edit_form";
            var success_alert = "User data updated successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });




        $(".submit_delete_div").click(function () {
            var controller = "admin";
            var submit_function = "delete_user";
            var form_class = "delete_form";
            var success_alert = "User data delete successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });
        $(".submit_reset_div").click(function () {
            var controller = "admin";
            var submit_function = "reset_user";
            var form_class = "reset_form";
            var success_alert = "User password reset successfully ... :) ";
            var error_alert = "An Error Ocurred";
            submit_data(controller, submit_function, form_class, success_alert, error_alert);
        });






    });
</script>




<!--END MAIN WRAPPER -->