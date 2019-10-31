<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">Client Report</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->




        <!-- Start Page Content -->
        <div class="row">
        <form class="form-inline" role="form" id="addUserggg" action="<?php echo base_url() ?>Reports/summaryappoitmentfilter" method="post" role="form">
        <input type="text" name="date_from" id="date_from" class="form-controL date_from input-rounded input-sm " value="<?php print $date_from; ?>" placeholder="Date From : "/>

                <input type="text" name="date_to" id="date_to" class="form-control date_to input-rounded input-sm " value="<?php print $date_to; ?>" placeholder="Date To : "/>
                <input type="submit" class="btn btn-primary" id="submit_button" value="Submit" />


                </form>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Summary Appointment</h4>
                        <!-- <h6 class="card-subtitle"> List of Client Groupings </h6> -->
                        <div class="table-responsive m-t-40">




                                <div class="table_div">

  <input type="hidden" name="report_name" class="report_name input-rounded input-sm form-control " id="report_name" value="Clients Export Report"/>


                                    <!-- <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                  <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No</th>
                                   

                                </tr>
                            </thead>
                            <tbody>
                              
                                    <tr>
                                        <td class="a-center">Total Appointments</td>


                                        <td><?php echo  $appnts[0]->Total_Appointments; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Kept</td>


                                        <td><?php echo   $appnts[0]->Kept_Appointments; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Missed</td>


                                        <td><?php echo  $appnts[0]->Missed_Appointments; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Defaulted</td>


                                        <td><?php echo $appnts[0]->Defaulted_Appointments; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Un-Scheduled</td>


                                        <td><?php echo $appnts[0]->Un_Scheduled; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Declined Care</td>


                                        <td><?php echo $appnts[0]->Declined_Care; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Returned To Care</td>


                                        <td><?php echo $appnts[0]->Returned_To_Care; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Self Transfer</td>


                                        <td><?php echo $appnts[0]->Self_Transfer; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Dead</td>


                                        <td><?php echo $appnts[0]->Dead; ?></td>
                                   


                                    </tr>
                                    <tr>
                                        <td class="a-center">Dead</td>


                                        <td><?php echo $appnts[0]->Other_Outcome; ?></td>
                                   


                                    </tr>
                                      <tr>
                                        <td class="a-center">Dead</td>


                                        <td><?php echo $appnts[0]->Defaulted_Declined_Care; ?></td>
                                   


                                    </tr>
                                     <tr>
                                        <td class="a-center">Dead</td>


                                        <td><?php echo $appnts[0]->Defaulted_Returned_To_Care; ?></td>
                                   


                                    </tr>
                                     <tr>
                                        <td class="a-center">Dead</td>


                                        <td><?php echo $appnts[0]->Defaulted_Self_Transfer; ?></td>
                                   


                                    </tr>
                                     <tr>
                                        <td class="a-center">Dead</td>


                                        <td><?php echo $appnts[0]->Defaulted_Dead; ?></td>
                                   


                                    </tr>
                                     <tr>
                                        <td class="a-center">Dead</td>


                                        <td><?php echo $appnts[0]->Defaulted_Other_Outcome; ?></td>
                                   


                                    </tr>
                              
                            </tbody>
                      
                                    </table> -->


                                </div>


           



                            <?php
                            $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                            );
                            ?>




                        </div>
                        <link href="https://fonts.googleapis.com/css?family=Gochi+Hand" rel="stylesheet">

<script src="https://balkangraph.com/js/latest/OrgChart.js"></script>

<div id="tree"></div>

<script>
    var Total_Appoitments =<?php echo $appnts[0]->Total_Appointments; ?>;
    var Kept_Appointments = <?php echo   $appnts[0]->Kept_Appointments; ?>;
    var Missed_Appoitments = <?php echo $appnts[0]->Missed_Appointments; ?>; 
    var Un_Scheduled = <?php echo $appnts[0]->Un_Scheduled; ?>;
    var  All_Defaulted_Appointments=<?php echo $appnts[0]->Defaulted_Appointments; ?>; 
    var Defaulted_Appointments = <?php echo   $appnts[0]->Kept_Appointments; ?>;
    var lftu = <?php echo $appnts[0]->Defaulted_Appointments; ?>;
    var Declined_Care = <?php echo   $appnts[0]->Declined_Care; ?>;
    var Returned_To_Care = <?php echo   $appnts[0]->Returned_To_Care; ?>;
    var Self_Transfer = <?php echo   $appnts[0]->Self_Transfer; ?>;
    var Dead = <?php echo   $appnts[0]->Dead; ?>;
    var Other_Outcome = <?php echo   $appnts[0]->Other_Outcome; ?>;
    var Defaulted_Declined_Care = <?php echo   $appnts[0]->Defaulted_Declined_Care; ?>;
    var Defaulted_Returned_To_Care = <?php echo   $appnts[0]->Defaulted_Returned_To_Care; ?>;
    var Defaulted_Self_Transfer = <?php echo   $appnts[0]->Defaulted_Self_Transfer; ?>;
    var Defaulted_Dead = <?php echo   $appnts[0]->Defaulted_Dead; ?>;
    var Defaulted_Other_Outcome = <?php echo   $appnts[0]->Defaulted_Other_Outcome; ?>;
     window.onload = function () {
   var chart = new OrgChart(document.getElementById("tree"), {
       template: "derek",
       enableDragDrop: true,
       toolbar: true,
       menu: {
           pdf: { text: "Export PDF" },
           png: { text: "Export PNG" },
           svg: { text: "Export SVG" },
           csv: { text: "Export CSV" }
       },
       nodeMenu: {
           details: { text: "Details" },

       },
       nodeBinding: {
           field_0: "name",
           field_1: "title",
           img_0: "img",
           field_number_children: "field_number_children"
       },
       nodes: [
           { id: 1, name: Total_Appoitments, title: 'All Appointments'},
           { id: 2, pid: 1, name: Kept_Appointments, title: 'Kept_Appointments'},
           { id: 3, pid: 1, name: Missed_Appoitments, title: 'Missed'}, 
           { id: 4, pid: 3, name: Declined_Care, title: 'Declined Care'}, 
           { id: 5, pid: 7, name: Returned_To_Care, title: 'Returned_To_Care'},
           { id: 6, pid: 4, name: Self_Transfer, title: 'Self_Transfer'},
           { id: 7, pid: 6, name: Dead, title: 'Dead'},
           { id: 8, pid: 5, name: Other_Outcome, title: 'Other_Outcome'},
           { id: 9, pid: 1, name: Un_Scheduled, title: 'Un_Scheduled'},
           { id: 10, pid: 3, name: All_Defaulted_Appointments, title: 'Defaulted_Appointments'},
           { id: 11, pid: 10, name: Defaulted_Declined_Care, title: 'Defaulted_Declined_Care'}, 
           { id: 12, pid: 11, name: Defaulted_Returned_To_Care, title: 'Defaulted_Returned_To_Care'},
           { id: 13, pid: 12, name: Defaulted_Self_Transfer, title: 'Defaulted_Self_Transfer'},
           { id: 14, pid: 13, name: Defaulted_Dead, title: 'Defaulted_Dead'},
           { id: 15, pid: 14, name: Defaulted_Other_Outcome, title: 'Defaulted_Other_Outcome'}
          
       ]
   });
};
   </script> 
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



