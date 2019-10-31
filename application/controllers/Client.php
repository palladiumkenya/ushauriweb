<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Client extends MY_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();


        //$this->load->library("infobip");

        $this->data = new DBCentral();
    }

    function index() {

        $this->load->view("maps");
    }

    function gmaps() {

        $this->load->view("gmaps");
    }

    function gmaps2() {

        $this->load->view("gmaps2");
    }

    function get_data() {
        // Select all the rows in the markers table
        $query = "SELECT * FROM tbl_county WHERE 1";
        $result = $this->db->query($query)->result_array();


        echo json_encode($result);
    }

    function maps() {
        $this->load->library('session');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $access_level = $this->session->userdata('access_level');
        // Select all the rows in the markers table
        $query = "SELECT * FROM vw_client_county_maps WHERE 1";


        if ($access_level == "Partner") {

            $query .= " AND vw_client_county_maps.partner_id='$partner_id' ";
        } elseif ($access_level == "County") {
            $query .= " AND vw_client_county_maps.county_id = '$county_id' ";
        } elseif ($access_level == "Sub County") {
            $query .= " AND vw_client_county_maps.sub_county_id='$sub_county_id' ";
        } elseif ($access_level == "Facility") {
            $query .= " AND vw_client_county_maps.mfl_code = '$facility_id' ";
        } else {
            
        }
        $result = $this->db->query($query)->result_array();

        //print_r($result);
        header("Content-type: text/xml");
        // Start XML file, echo parent node




        echo '<markers>';

//        echo 'Found ///';
//        exit();
        // Iterate through the rows, printing XML nodes for each
        foreach ($result as $row) {
            // Add to XML document node
            echo '<marker ';
            echo 'id="' . $row['id'] . '" ';
            echo 'name="' . $this->parseToXML($row['name']) . '" ';
            echo 'address="' . $this->parseToXML($row['no_clients'] . " " . $row['no_appointments']) . '" ';
            echo 'lat="' . $row['lat'] . '" ';
            echo 'lng="' . $row['lng'] . '" ';
            echo 'type="' . $row['Clients'] . '" ';
            echo '/>';
        }

// End XML file
        echo '</markers>';
    }
    function facility_maps() {
        $this->load->library('session');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $access_level = $this->session->userdata('access_level');
        // Select all the rows in the markers table
        $query = "SELECT * FROM vw_client_summary_report WHERE 1";


        if ($access_level == "Partner") {

            $query .= " AND vw_client_summary_report.partner_id='$partner_id' ";
        } elseif ($access_level == "County") {
            $query .= " AND vw_client_summary_report.county_id = '$county_id' ";
        } elseif ($access_level == "Sub County") {
            $query .= " AND vw_client_summary_report.sub_county_id='$sub_county_id' ";
        } elseif ($access_level == "Facility") {
            $query .= " AND vw_client_summary_report.mfl_code = '$facility_id' ";
        } else {
            
        }
        //$query .= " AND lat IS NOT NULL AND lng IS NOT NULL ";
        $result = $this->db->query($query)->result_array();
       
        //print_r($result);
        header("Content-type: text/xml");
        // Start XML file, echo parent node




        echo '<markers>';


        // Iterate through the rows, printing XML nodes for each
        foreach ($result as $row) {
            // Add to XML document node
            echo '<marker ';
            echo 'id="' . $row['mfl_code'] . '" ';
            echo 'name="' . $this->parseToXML($row['facility_name']) . '" ';
            echo 'address="No of Clients : ' . $this->parseToXML($row['no_clients'] . " No of Appointments : " . $row['no_appointments']) . '" ';
            echo 'lat="' . $row['lat'] . '" ';
            echo 'lng="' . $row['lng'] . '" ';
            echo 'type="Clients " ';
            echo '/>';
        }

// End XML file
        echo '</markers>';
    }

    function parseToXML($htmlStr) {
        $xmlStr = str_replace('<', '&lt;', $htmlStr);
        $xmlStr = str_replace('>', '&gt;', $xmlStr);
        $xmlStr = str_replace('"', '&quot;', $xmlStr);
        $xmlStr = str_replace("'", '&#39;', $xmlStr);
        $xmlStr = str_replace("&", '&amp;', $xmlStr);
        return $xmlStr;
    }

}
