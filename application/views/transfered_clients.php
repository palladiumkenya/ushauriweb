
<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                Transfered Out Clients 
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
                                        <th>Condition</th>
                                        <th>Previous Clinic</th>
                                        <th>New Clinic</th>


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
                                                <?php
                                            } else {
                                                ?>

                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <?php
                                            }
                                            ?>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->old_mfl_code . " " . $value->prev_clinic; ?></td>
                                            <td><?php echo $value->code . " " . $value->new_clinic; ?></td>


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
                                        <th>Condition</th>
                                        <th>Previous Clinic</th>
                                        <th>New Clinic</th>


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
                                                <?php
                                            } else {
                                                ?>

                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <?php
                                            }
                                            ?>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->old_mfl_code . " " . $value->prev_clinic; ?></td>
                                            <td><?php echo $value->code . " " . $value->new_clinic; ?></td>


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
                                        <th>Condition</th>
                                        <th>Previous Clinic</th>
                                        <th>New Clinic</th>


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
                                                <?php
                                            } else {
                                                ?>

                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <td>XXXXXX XXXXXXX</td>
                                                <?php
                                            }
                                            ?>
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->old_mfl_code . " " . $value->prev_clinic; ?></td>
                                            <td><?php echo $value->code . " " . $value->new_clinic; ?></td>


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



                </div>
            </div>                <div class="panel-footer">
                Get   in touch: support.tech@mhealthkenya.org                             </div>

        </div>        






    </div>



</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>








<!--END MAIN WRAPPER -->