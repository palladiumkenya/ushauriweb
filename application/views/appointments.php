
<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->

        <?php
        $client_ip_addess = $_SERVER['REMOTE_ADDR'];

        $username = 'admin'; // Username  
        if ($_SERVER['REMOTE_ADDR'] == "41.215.81.58") {
            $server = 'http://192.168.0.7/trusted';  // Tableau URL  
        } else {
            $server = 'http://41.215.81.58/trusted';  // Tableau URL  
        }

        $view = '/views/T4AUSHAURI-UpdatedAppointmentsDashboard/AppointmentsUpdatedDashboard?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no'; // View URL 



        $ch = curl_init($server); // Initializes cURL session 
      


        $data = array('username' => $username); // What data to send to Tableau Server  


        curl_setopt($ch, CURLOPT_POST, true); // Tells cURL to use POST method  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // What data to post  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return ticket to variable  


        $ticket = curl_exec($ch); // Execute cURL function and retrieve ticket  
        curl_close($ch); // Close cURL session  
        ?>  


        <iframe src="<?= $server ?>/<?= $ticket ?>/<?= $view ?>" width="100%" height="652px;" ></iframe>  








        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->




