
<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                All clients who have consented to receive SMS Alerts from the  System
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
                                        <th>Phone No</th>
                                        <th>DoB </th>
                                        <th>Type</th>
                                        <th>Treatment</th>
                                        <th>Status</th>
                                        <th>Enrollment Date</th>
                                        <th>ART Date</th>
                                        <th>Facility </th>
                                        <th>Sub County</th>
                                        <th>County</th>

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
                                            <td><?php echo $value->group_name; ?></td>
                                            <td><?php echo $value->client_status; ?></td>
                                            <td><?php echo $value->status; ?></td>
                                            <td><?php echo $value->enrollment_date; ?></td>

                                            <td><?php echo $value->art_date; ?></td>

                                            <td><?php echo $value->facility; ?></td>
                                            <td><?php echo $value->sub_county; ?></td>
                                            <td><?php echo $value->county; ?></td>

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
                        } else {
                            ?>

                            <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>UPN</th>
                                        <th>Client Name</th>
                                        <th>Phone No </th>
                                        <th>DoB  </th>
                                        <th>Type</th>
                                        <th>Treatment</th>
                                        <th>Status</th>
                                        <th>Enrollment Date</th>
                                        <th>ART Date</th>
                                        <th>Facility </th>
                                        <th>Sub County</th>
                                        <th>County</th>

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

                                            <td><?php echo $value->facility; ?></td>
                                            <td><?php echo $value->sub_county; ?></td>
                                            <td><?php echo $value->county; ?></td>

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