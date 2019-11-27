
<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Clients Dahsboard</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->

        <?php
        $client_ip_addess = $_SERVER['REMOTE_ADDR'];

        $server_url = base_url();

        $username = 'admin'; // Username  
        if ($_SERVER['REMOTE_ADDR'] == "41.215.81.58") {
          $server = 'https://tableau.mhealthkenya.co.ke/trusted';  // Tableau URL   
        } else {
            $server = 'https://tableau.mhealthkenya.co.ke/trusted';  // Tableau URL   
        }


        $access_level = $this->session->userdata('access_level');

        if ($access_level == 'Admin' or $access_level == 'Donor' or $access_level == 'National') {

            if ($server_url === 'https://ushauritest.mhealthkenya.co.ke/') {
                //Test Link 

                $view = "/views/T4AUshauri-GEOGRAPHICALDASHBOARDUPDATED1232_0/CLIENTSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no";
            } else {
                //Live Link
                $view = "/views/Ushauri-SUMMARYDASHBOARDS/CLIENTSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no"; // View URL 
            }
        } elseif ($access_level == 'Partner') {
            $partner_name = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($this->session->userdata('partner_name')))));
         
            $partner_id = $this->session->userdata('partner_id');

            $embeded_url = "&partner_name=" . $partner_name;



            if ($server_url === 'https://ushauritest.mhealthkenya.co.ke/') {
                //Test Link 
                $view = "/views/T4AUshauri-GEOGRAPHICALDASHBOARDUPDATED1232_0/PARTNERSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:display_count=no&:showAppBanner=false&Partner=Egpaf&County=Homa%20Bay&:showVizHome=no.$embeded_url";
               
            } else {
                //Live Link
                $view = "/views/Ushauri-SUMMARYDASHBOARDS/PARTNERSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no.$embeded_url"; // View URL 
            }
//             $sql = "SELECT
// 	tbl_county.`name` AS county_name,
// 	tbl_county.id AS county_id 
// FROM
// 	`tbl_partner_facility`
// 	INNER JOIN tbl_county ON tbl_county.id = tbl_partner_facility.county_id 
// WHERE
// 	tbl_partner_facility.partner_id = $partner_id GROUP BY tbl_county.id";
//             $embeded_url = "&Partner=" . $partner_name;
//             $query = $this->db->query($sql);
//             if ($query->num_rows() > 0) {
//                 foreach ($query->result() as $row) {
//                     $county_name = $row->county_name;
//                     $county_id = $row->county_id;

//                     $embeded_url .= "&County=" . $county_name;
//                 }
//             }

        } elseif ($access_level == 'County') {
            $county = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($this->session->userdata('county_name')))));

            $county_id = $this->session->userdata('county_id');
            $sql = " SELECT
	tbl_sub_county.`name` AS sub_county_name,
	tbl_sub_county.id AS sub_county_id,
	tbl_county.`name` AS county_name,
	tbl_county.id AS county_id 
FROM
	`tbl_partner_facility`
	INNER JOIN tbl_county ON tbl_county.id = tbl_partner_facility.county_id
	INNER JOIN tbl_sub_county ON tbl_sub_county.county_id = tbl_county.id 
WHERE
	tbl_county.id = $county_id GROUP BY
	tbl_sub_county.id ";
            $embeded_url = "&County=" . $county;
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $county_name = $row->county_name;
                    $sub_county = $row->sub_county_name;
                    $embeded_url .= "&Sub County=" . $sub_county;
                }
            }

            if ($server_url === 'https://ushauritest.mhealthkenya.co.ke/') {
                //Test Link 
                $view = "/views/T4AUshauri-GEOGRAPHICALDASHBOARDUPDATED1232_0/COUNTYSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no&$embeded_url";
            } else {
                //Live Link
                $view = "/views/Ushauri-SUMMARYDASHBOARDS/COUNTYSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no.$embeded_url"; // View URL 
            }
        } elseif ($access_level == 'Sub County') {
            $sub_county = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($this->session->userdata('sub_county_name')))));


            $sub_county_id = $this->session->userdata('subcounty_id');
            $sql = " SELECT
	tbl_sub_county.`name` AS sub_county_name,
	tbl_sub_county.id AS sub_county_id,
	tbl_county.`name` AS county_name,
	tbl_county.id AS county_id,
	tbl_master_facility.`name` AS facility_name 
FROM
	`tbl_partner_facility`
	INNER JOIN tbl_county ON tbl_county.id = tbl_partner_facility.county_id
	INNER JOIN tbl_sub_county ON tbl_sub_county.county_id = tbl_county.id
	INNER JOIN tbl_master_facility ON tbl_master_facility.Sub_County_ID = tbl_sub_county.id  
WHERE
	tbl_sub_county.id = $sub_county_id GROUP BY
	tbl_master_facility.code ";
            $embeded_url = "&Sub County=" . $sub_county;
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $facility_name = $row->facility_name;

                    $embeded_url .= "&Facility=" . $facility_name;
                }
            }
            if ($server_url === 'https://ushauritest.mhealthkenya.co.ke/') {
                //Test Link 
                $view = "/views/T4AUshauri-GEOGRAPHICALDASHBOARDUPDATED1232_0/SUBCOUNTYSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no&$embeded_url";
            } else {
                //Live Link
                $view = "/views/Ushauri-SUMMARYDASHBOARDS/SUBCOUNTYSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no.$embeded_url"; // View URL 
            }
        } elseif ($access_level == 'Facility') {
            $facility = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($this->session->userdata('facility_name')))));

            if ($server_url === 'https://ushauritest.mhealthkenya.co.ke/') {
                //Test Link
                $view = "/views/T4AUshauri-GEOGRAPHICALDASHBOARDUPDATED1232_0/FacilityGeographicalMapDashboard?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no&$embeded_url";
            } else {
                //Live Link
                $view = "/views/Ushauri-GEOGRAPHICALDASHBOARD/FACILITYSUMMARYREPORT?iframeSizedToWindow=true&:embed=y&:showAppBanner=false&:display_count=no&:showVizHome=no&Facility=$facility"; // View URL 
            }
        } else {
            
        }




        $ch = curl_init($server); // Initializes cURL session 




        $data = array('username' => $username); // What data to send to Tableau Server  



        curl_setopt($ch, CURLOPT_POST, true); // Tells cURL to use POST method  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // What data to post  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return ticket to variable 
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


        //curl_close($ch); // Close cURL session  
        $ticket = curl_exec($ch); // Execute cURL function and retrieve ticket

        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            
        }





        $clnd_view = str_replace(' ', '%20', $view);
        $url = $server . '/' . $ticket . '/' . $clnd_view;
        ?>  


        <iframe src="<?= $server ?>/<?= $ticket ?>/<?= $clnd_view ?>" width="100%" height="652px;" ></iframe>  








        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->




