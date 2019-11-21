<?php
ini_set('max_execution_time', '0'); 
ini_set('memory_limit', '1024M');


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reports extends MY_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();

        $this->load->library('session');

        $this->check_access();
        $this->data = new DBCentral();
    }

    function index() {

        // echo __DIR__;
        redirect("Reports/dashboard", "refresh");
    }

    function check_access() {
        $logged_in = $this->session->userdata("logged_in");

        if ($logged_in) {
            $first_access = $this->session->userdata('first_access');
            $user_id = $this->session->userdata('user_id');
            if ($first_access == "Yes") {
                redirect("reset/reset_pass/$user_id", "refresh");
            } else {
                
            }
        } else {
            redirect("Login", "refresh");
        }
    }

    function county_target() {
        $sql = "Select * from vw_county_performance";
        $query = $this->db->query($sql)->result();
        foreach ($query as $value) {
            $actual_counties = $value->actual_counties;
            $target_counties = $value->target_counties;

            $percentage = ($actual_counties / $target_counties) * 100;

            echo json_encode($percentage);
        }
    }

    function facility_target() {
        $sql = "Select * from vw_facility_performance";
        $query = $this->db->query($sql)->result();
        foreach ($query as $value) {
            $actual_facilities = $value->actual_facilities;
            $target_facilities = $value->target_facilities;

            $percentage = ($actual_facilities / $target_facilities) * 100;

            echo json_encode($percentage);
        }
    }

    function client_target() {
        $sql = "Select sum(actual_clients) as actual_clients, sum(target_clients) as target_clients  from vw_client_performance_monitor";
        $query = $this->db->query($sql)->result();
        foreach ($query as $value) {
            $actual_clients = $value->actual_clients;
            $target_clients = $value->target_clients;

            $percentage = ($actual_clients / $target_clients) * 100;

            echo json_encode($percentage);
        }
    }

    function partner_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $this->db->where('status', 'Active');
        $num_results = $this->db->count_all_results();
        echo json_encode($num_results);
    }

    function facility_info() {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        if ($access_level === 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }
        if ($access_level === 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $facility_id = $this->session->userdata('facility_id');



        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $this->db->select('id');
        $this->db->from('partner_facility');
        if ($access_level == 'Facility') {
            $this->db->where('partner_id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        } else {
            
        }
        $this->db->where('status', 'Active');


        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)):
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        if (!empty($date_from)) {
            $this->db->where('partner_facility.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('partner_facility.created_at <=', $formated_date_to);
        }
        if (!empty($county_id)) {
            $this->db->where('partner_facility.county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        }



        $num_results = $this->db->count_all_results();
        echo json_encode($num_results);
    }

    function users_info() {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('users');
        if ($access_level == 'Facility') {
            $this->db->where('facility_id', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('sub_county_id', $sub_county_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        echo json_encode($num_results);
    }

    function content_info() {


        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $this->db->select('id');
        $this->db->from('content');

        $num_results = $this->db->count_all_results();
        echo json_encode($num_results);
    }

    function module_info() {



        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $this->db->select('id');
        $this->db->from('module');

        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function ok_info() {


        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('couinty_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function not_ok_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function deactivated_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->where('client.status', 'Disabled');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('sub_county_id', $sub_county_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function appointments_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        //$this->db->where('tbl_appointment.appntmnt_date >=', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function today_appointments_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.appntmnt_date =', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function counties_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('county');
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function sub_counties_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('sub_county');
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function notified_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $this->db->where('app_status', 'Notified');
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function booked_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $this->db->where('app_status', 'Booked');
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function missed_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $this->db->where('app_status', 'Missed');
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function defaulted_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $this->db->where('app_status', 'Defaultd');
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function groups_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function adolescents_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '3');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function pmtct_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '1');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function tb_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '2');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function new_clients_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '4');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function un_suppressed_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '5');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function art_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '7');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function lactating_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '8');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function paeds_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '9');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function hib_tb_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('groups', 'groups.id=client.group_id');
        $this->db->where('groups.id', '10');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function weekly_checkins_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function responded_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function sender_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('sender');
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function pending_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function late_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function unrecognised_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function appointment_report() {
        $data['appointments'] = $this->db->query("SELECT CONCAT(`f_name`,' ',`m_name`,' ',`l_name`) AS client_name ,
 dob, tbl_client.client_status AS TYPE , `clinic_number`,`phone_no`,
 `alt_phone_no`,`shared_no_name`,`smsenable`,tbl_client.status AS client_status,
  `tbl_partner`.`partner_id`,`tbl_master_facility`.`code` AS mfl_code,
   tbl_master_facility.`name` AS facility_name, tbl_gender.`name` AS gender_name,
    tbl_marital_status.`marital` ,tbl_language.`id` AS language_id, tbl_language.`name` AS language_name ,
     enrollment_date,art_date,appntmnt_date,app_type_1,
      app_status,app_msg,tbl_groups.`name` AS group_name , tbl_appointment_types.id as appointment_type_id , tbl_appointment_types.name as appointment_type FROM tbl_client
 INNER JOIN tbl_appointment ON tbl_appointment.`client_id` = tbl_client.`id`
  INNER JOIN tbl_language ON tbl_language.id = tbl_client.`language_id`
  INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id`
   INNER JOIN tbl_gender ON tbl_gender.id = tbl_client.`gender`
   INNER JOIN tbl_marital_status ON tbl_marital_status.`id` = tbl_client.`marital`
   INNER JOIN	tbl_master_facility ON tbl_master_facility.`code` = tbl_client.`mfl_code` INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1
   WHERE tbl_appointment.`appntmnt_date` >= '2017-01-01'  ")->result();


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            $this->load->template('Reports/appointment_reports');
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    function client_report() {


        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        if ($access_level == "Admin") {

            $data['clients'] = $this->db->query("
             	SELECT CONCAT(`f_name`,' ',`m_name`,' ',`l_name`) AS client_name ,
	 dob, client_status, `clinic_number`,`phone_no`,
	 `alt_phone_no`,`shared_no_name`,`smsenable`,tbl_client.status AS client_status,
	 `tbl_master_facility`.`code` AS mfl_code,tbl_county.name as  county_name, tbl_sub_county.name as sub_county_name,
	   tbl_master_facility.`name` AS facility_name, tbl_gender.`name` AS gender_name,
	    tbl_marital_status.`marital` ,tbl_language.`id` AS language_id, tbl_language.`name` AS language_name ,
	     enrollment_date,art_date,tbl_groups.`name` AS group_name ,
		 smsenable,wellness_enable,motivational_enable,tbl_client.created_at FROM tbl_client
	  INNER JOIN tbl_language ON tbl_language.id = tbl_client.`language_id`
	  INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id`
	   INNER JOIN tbl_gender ON tbl_gender.id = tbl_client.`gender`
	   INNER JOIN tbl_marital_status ON tbl_marital_status.`id` = tbl_client.`marital`
	   INNER JOIN	tbl_master_facility ON tbl_master_facility.`code` = tbl_client.`mfl_code`
           INNER JOIN tbl_sub_county on tbl_sub_county.id = tbl_master_facility.Sub_County_ID
           inner join tbl_county on tbl_county.id = tbl_master_facility.county_id
   ")->result();
        } elseif ($access_level == "Partner") {

            $data['clients'] = $this->db->query("
             	SELECT CONCAT(`f_name`,' ',`m_name`,' ',`l_name`) AS client_name ,
	 dob, client_status, `clinic_number`,`phone_no`,
	 `alt_phone_no`,`shared_no_name`,`smsenable`,tbl_client.status AS client_status,
	 `tbl_master_facility`.`code` AS mfl_code,tbl_county.name as  county_name, tbl_sub_county.name as sub_county_name,
	   tbl_master_facility.`name` AS facility_name, tbl_gender.`name` AS gender_name,
	    tbl_marital_status.`marital` ,tbl_language.`id` AS language_id, tbl_language.`name` AS language_name ,
	     enrollment_date,art_date,tbl_groups.`name` AS group_name ,
		 smsenable,wellness_enable,motivational_enable,tbl_client.created_at FROM tbl_client
	  INNER JOIN tbl_language ON tbl_language.id = tbl_client.`language_id`
	  INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id`
	   INNER JOIN tbl_gender ON tbl_gender.id = tbl_client.`gender`
	   INNER JOIN tbl_marital_status ON tbl_marital_status.`id` = tbl_client.`marital`
	   INNER JOIN	tbl_master_facility ON tbl_master_facility.`code` = tbl_client.`mfl_code`
           INNER JOIN tbl_sub_county on tbl_sub_county.id = tbl_master_facility.Sub_County_ID
           inner join tbl_county on tbl_county.id = tbl_master_facility.county_id 
           inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code where tbl_partner_facility.partner_id='$partner_id'
   ")->result();
        } elseif ($access_level == "Facility") {

            $data['clients'] = $this->db->query("
             	SELECT CONCAT(`f_name`,' ',`m_name`,' ',`l_name`) AS client_name ,
	 dob, client_status, `clinic_number`,`phone_no`,
	 `alt_phone_no`,`shared_no_name`,`smsenable`,tbl_client.status AS client_status,
	 `tbl_master_facility`.`code` AS mfl_code,tbl_county.name as  county_name, tbl_sub_county.name as sub_county_name,
	   tbl_master_facility.`name` AS facility_name, tbl_gender.`name` AS gender_name,
	    tbl_marital_status.`marital` ,tbl_language.`id` AS language_id, tbl_language.`name` AS language_name ,
	     enrollment_date,art_date,tbl_groups.`name` AS group_name ,
		 smsenable,wellness_enable,motivational_enable,tbl_client.created_at FROM tbl_client
	  INNER JOIN tbl_language ON tbl_language.id = tbl_client.`language_id`
	  INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id`
	   INNER JOIN tbl_gender ON tbl_gender.id = tbl_client.`gender`
	   INNER JOIN tbl_marital_status ON tbl_marital_status.`id` = tbl_client.`marital`
	   INNER JOIN	tbl_master_facility ON tbl_master_facility.`code` = tbl_client.`mfl_code`
           INNER JOIN tbl_sub_county on tbl_sub_county.id = tbl_master_facility.Sub_County_ID
           inner join tbl_county on tbl_county.id = tbl_master_facility.county_id where tbl_master_facility.code='$facility_id'
   ")->result();
        } else {

            $data['clients'] = $this->db->query("
             	SELECT CONCAT(`f_name`,' ',`m_name`,' ',`l_name`) AS client_name ,
	 dob, client_status, `clinic_number`,`phone_no`,
	 `alt_phone_no`,`shared_no_name`,`smsenable`,tbl_client.status AS client_status,
	 `tbl_master_facility`.`code` AS mfl_code,tbl_county.name as  county_name, tbl_sub_county.name as sub_county_name,
	   tbl_master_facility.`name` AS facility_name, tbl_gender.`name` AS gender_name,
	    tbl_marital_status.`marital` ,tbl_language.`id` AS language_id, tbl_language.`name` AS language_name ,
	     enrollment_date,art_date,tbl_groups.`name` AS group_name ,
		 smsenable,wellness_enable,motivational_enable,tbl_client.created_at,tbl_client.age_group as age_group FROM tbl_client
	  INNER JOIN tbl_language ON tbl_language.id = tbl_client.`language_id`
	  INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id`
	   INNER JOIN tbl_gender ON tbl_gender.id = tbl_client.`gender`
	   INNER JOIN tbl_marital_status ON tbl_marital_status.`id` = tbl_client.`marital`
	   INNER JOIN	tbl_master_facility ON tbl_master_facility.`code` = tbl_client.`mfl_code`
           INNER JOIN tbl_sub_county on tbl_sub_county.id = tbl_master_facility.Sub_County_ID
           inner join tbl_county on tbl_county.id = tbl_master_facility.county_id WHERE tbl_client.created_at <='2017-04-31'
   ")->result();
        }




        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Reports/client_reports');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    function get_gender_reports() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);

        if (!empty($date_from)):
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('tbl_gender.`name` AS name ,COUNT(tbl_client.`gender`) AS value');
        $this->db->from('client');
        $this->db->join('gender', 'gender.id = client.gender');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("gender"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    function get_marital_reports() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        if (!empty($date_from)):

            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('tbl_marital_status.`marital` AS NAME, COUNT(tbl_client.`marital`) AS VALUE');
        $this->db->from('client');
        $this->db->join('marital_status', 'marital_status.id = client.marital');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("marital_status.`marital`"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();




        echo json_encode($get_query);
    }

    function get_client_type() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)):


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('client_type as k , COUNT(client_type) as v');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("client_type"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();






        echo json_encode($get_query);
    }

    function get_client_age_group() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)):


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;

        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('COUNT(age_group) AS v , age_group as k');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("age_group"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();




        echo json_encode($get_query);
    }

    function get_client_category() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('tbl_groups.`name` AS k , COUNT(group_id) AS v');
        $this->db->from('client');
        $this->db->join('tbl_groups', 'tbl_groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("tbl_groups.id"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();




        echo json_encode($get_query);
    }

    function get_client_language() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }

        $this->db->select('tbl_language.`name` AS NAME , COUNT(language_id) AS VALUE');
        $this->db->from('client');
        $this->db->join('tbl_language', 'tbl_language.id = client.language_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):


        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("tbl_language.id"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();



        echo json_encode($get_query);
    }

    function get_client_condition() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }

        $this->db->select('tbl_client.`client_status` AS k , COUNT(tbl_client.id) AS v ');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("client_status"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();




        echo json_encode($get_query);
    }

    function get_client_registration() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }

        $this->db->select('tbl_client.`entry_point` AS k , COUNT(tbl_client.id) AS v');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;



        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("client.entry_point"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();




        echo json_encode($get_query);
    }

    function get_client_appointment() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }

        $this->db->select('tbl_client.`entry_point` AS k , COUNT(tbl_client.id) AS v');
        $this->db->from('client');
        $this->db->join('appointment', 'appointment.client_id = client.id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;



        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("appointment.entry_point"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();




        echo json_encode($get_query);
    }

    function get_client_status() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }

        $this->db->select('tbl_client.`status` AS k , COUNT(tbl_client.id) AS v');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;



        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by("client.status"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();




        echo json_encode($get_query);
    }

    function consented_clients() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }

        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }


        $consented_clients = "SELECT COUNT(tbl_client.id) FROM tbl_client INNER JOIN tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code WHERE 1 ";
        $consented_clients .= " AND smsenable='YES' AND  tbl_partner_facility.`status`='Active' ";

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $consented_clients .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $consented_clients .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $consented_clients .= " AND county_id = '$county_id' ";
        }


        if (!empty($sub_county_id)) {
            $consented_clients .= " AND sub_county_id = '$sub_county_id' ";
        }


        if (!empty($mfl_code)) {
            $consented_clients .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($formated_date_from)) {
            $consented_clients .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }


        if (!empty($formated_date_to)) {
            $consented_clients .= " AND tbl_client.created_at <= '$formated_date_to' ";
        }


        $all_clients = " SELECT COUNT(tbl_client.id) FROM tbl_client INNER JOIN tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $all_clients .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $all_clients .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $all_clients .= " AND county_id = '$county_id' ";
        }


        if (!empty($sub_county_id)) {
            $all_clients .= " AND sub_county_id = '$sub_county_id' ";
        }


        if (!empty($mfl_code)) {
            $all_clients .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($formated_date_from)) {
            $all_clients .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }


        if (!empty($formated_date_to)) {
            $all_clients .= " AND tbl_client.created_at >= '$formated_date_to' ";
        }


        $get_query = $this->db->query("SELECT ($consented_clients) AS consented_clients , ($all_clients) AS all_clients")->result();

        if (!empty($json)) {
            if ($get_query == "0") {
                $get_query = "0";
                echo json_encode($get_query);
            } else {
                echo json_encode($get_query);
            }
        } else {
            if ($get_query == "0") {
                $get_query = "0";
                //echo json_encode($get_query);
                return $get_query;
            } else {
                //echo json_encode($get_query);
                return $get_query;
            }
        }
    }

    function consented_clients_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }

        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }


        $consented_clients = "SELECT COUNT(tbl_client.id) FROM tbl_client INNER JOIN tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code WHERE 1 ";
        $consented_clients .= " AND smsenable='YES' ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $consented_clients .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $consented_clients .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $consented_clients .= " AND county_id = '$county_id' ";
        }


        if (!empty($sub_county_id)) {
            $consented_clients .= " AND sub_county_id = '$sub_county_id' ";
        }


        if (!empty($mfl_code)) {
            $consented_clients .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($formated_date_from)) {
            $consented_clients .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }


        if (!empty($formated_date_to)) {
            $consented_clients .= " AND tbl_client.created_at <= '$formated_date_to' ";
        }


        $all_clients = " SELECT COUNT(tbl_client.id) FROM tbl_client INNER JOIN tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $all_clients .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $all_clients .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        endif;


        if (!empty($county_id)) {
            $all_clients .= " AND county_id = '$county_id' ";
        }


        if (!empty($sub_county_id)) {
            $all_clients .= " AND sub_county_id = '$sub_county_id' ";
        }


        if (!empty($mfl_code)) {
            $all_clients .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($formated_date_from)) {
            $all_clients .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }


        if (!empty($formated_date_to)) {
            $all_clients .= " AND tbl_client.created_at <= '$formated_date_to' ";
        }


        $get_query = $this->db->query("SELECT ($consented_clients) AS consented_clients , ($all_clients) AS all_clients")->result();

        if ($get_query == "0") {
            $get_query = "0";
            echo json_encode($get_query);
        } else {
            echo json_encode($get_query);
        }
    }
    function summaryappoitmentfilter() {
        $data['date_from'] = $this->input->post('date_from');
        $data['date_to'] =  $this->input->post('date_to');
      
       if (!empty($data['date_from'])){
        $date_from = str_replace('-', '-',$data['date_from'] );
        $formated_date_from = date("Y-m-d", strtotime($date_from));

       }else{
        $formated_date_from = date("Y-m-d", strtotime('-30 days'));
        $data['date_from'] =  date("d-m-Y", strtotime('-30 days'));
      
           
       }
    
            if (!empty($data['date_to'])){
        $date_to = str_replace('-', '-',$data['date_to'] );
        $formated_date_to = date("Y-m-d", strtotime($date_to));

        }   else{
            $formated_date_to = date("Y-m-d");
            $data['date_to'] =  date("d-m-Y");
        }

 

                    $access_level = $this->session->userdata('access_level');
                    if ($access_level == 'Facility') {
                        redirect("Reports/facility_home", "refresh");
                    } else {
                        $partner_id = $this->session->userdata('partner_id');
                        $county_id = $this->session->userdata('county_id');
                        $sub_county_id = $this->session->userdata('subcounty_id');
                        $facility_id = $this->session->userdata('facility_id');
                        $access_level = $this->session->userdata('access_level');


                            if ($access_level == "Partner") {

                                $query = $this->db->query("SELECT
                        `tbl_partner_facility`.`partner_id` AS `Partner_ID`,
                        `tbl_appointment`.`appntmnt_date` AS `Appointment_Date`,
                        count( DISTINCT `tbl_appointment`.`id` ) AS `Total_Appointments`,
                        sum(
                        ( CASE WHEN ( ( `tbl_appointment`.`appointment_kept` = 'Yes' ) AND ( `tbl_appointment`.`visit_type` = 'Scheduled' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Kept_Appointments`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`visit_type` = 'Un-Scheduled' ) THEN 1 ELSE 0 END ) ) AS `Un_Scheduled`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`app_status` = 'Defaulted' ) THEN 1 ELSE 0 END ) ) AS `Defaulted_Appointments`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`app_status` = 'Missed' ) THEN 1 ELSE 0 END ) ) AS `Missed_Appointments`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`app_status` = 'LTFU' ) THEN 1 ELSE 0 END ) ) AS `LTFU_Appointments`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 3 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Declined_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 5 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Returned_To_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 6 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Self_Transfer`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 7 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Dead`,
                        sum( ( CASE WHEN ( isnull( `tbl_clnt_outcome`.`fnl_outcome` ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) ) AS `No_Final_Outcome`,
                        sum(
                        ( CASE WHEN ( ( `tbl_other_final_outcome`.`outcome` <> NULL ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Other_Outcome`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 3 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Declined_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 5 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Returned_To_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 6 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Self_Transfer`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 7 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Dead`,
                        sum( ( CASE WHEN ( isnull( `tbl_clnt_outcome`.`fnl_outcome` ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) ) AS `Defaulted_No_Final_Outcome`,
                        sum(
                        ( CASE WHEN ( ( `tbl_other_final_outcome`.`outcome` <> NULL ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Other_Outcome` 
                    FROM
                        (
                        (
                        (
                        (
                        (
                        (
                        ( `tbl_appointment` JOIN `tbl_client` ON ( ( `tbl_client`.`id` = `tbl_appointment`.`client_id` ) ) )
                        LEFT JOIN `tbl_clnt_outcome` ON ( ( `tbl_clnt_outcome`.`appointment_id` = `tbl_appointment`.`id` ) ) 
                        )
                        LEFT JOIN `tbl_outcome` ON ( ( `tbl_outcome`.`id` = `tbl_clnt_outcome`.`outcome` ) ) 
                        )
                        LEFT JOIN `tbl_final_outcome` ON ( ( `tbl_final_outcome`.`id` = `tbl_clnt_outcome`.`fnl_outcome` ) ) 
                        )
                        LEFT JOIN `tbl_other_final_outcome` ON ( ( `tbl_other_final_outcome`.`client_outcome_id` = `tbl_clnt_outcome`.`id` ) ) 
                        )
                        JOIN `tbl_partner_facility` ON ( ( `tbl_partner_facility`.`mfl_code` = `tbl_client`.`mfl_code` ) ) 
                        )
                        JOIN `tbl_master_facility` ON ( ( `tbl_master_facility`.`code` = `tbl_client`.`mfl_code` ) ) 
                        )
                        WHERE tbl_appointment.appntmnt_date > '$formated_date_from' AND tbl_appointment.appntmnt_date < '$formated_date_to'
                        AND tbl_partner_facility.partner_id = '$partner_id' 
                    " );
                      $data['appnts']  = $query->result();
                    
                        // } elseif ($access_level == "County") {
                        //     $appnts .= " AND appointment_counts.county_id = '$county_id' ";
                        // } elseif ($access_level == "Sub County") {
                        //     $appnts .= " AND appointment_counts.sub_county_id='$sub_county_id' ";
                        // } elseif ($access_level == "Facility") {
                        //     $appnts .= " AND appointment_counts.mfl_code = '$facility_id' ";
                        // } else {
                            
                        // }
                        //$appnts .=  'LIMIT 100';



                        $data['side_functions'] = $this->data->get_side_modules();
                        $data['top_functions'] = $this->data->get_top_modules();
                        $data['output'] = $this->get_access_level();
                        //$data['appnts'] = $this->db->query($appnts)->result();




                $this->load->vars($data);
                $this->load->template('Reports/summaryappoitment');
                $function_name = $this->uri->segment(2);
            
                        }else{

                                
                        $query = $this->db->query("SELECT
                        `tbl_partner_facility`.`partner_id` AS `Partner_ID`,
                        `tbl_appointment`.`appntmnt_date` AS `Appointment_Date`,
                        count( DISTINCT `tbl_appointment`.`id` ) AS `Total_Appointments`,
                        sum(
                        ( CASE WHEN ( ( `tbl_appointment`.`appointment_kept` = 'Yes' ) AND ( `tbl_appointment`.`visit_type` = 'Scheduled' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Kept_Appointments`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`visit_type` = 'Un-Scheduled' ) THEN 1 ELSE 0 END ) ) AS `Un_Scheduled`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`app_status` = 'Defaulted' ) THEN 1 ELSE 0 END ) ) AS `Defaulted_Appointments`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`app_status` = 'Missed' ) THEN 1 ELSE 0 END ) ) AS `Missed_Appointments`,
                        sum( ( CASE WHEN ( `tbl_appointment`.`app_status` = 'LTFU' ) THEN 1 ELSE 0 END ) ) AS `LTFU_Appointments`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 3 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Declined_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 5 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Returned_To_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 6 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Self_Transfer`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 7 ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Dead`,
                        sum( ( CASE WHEN ( isnull( `tbl_clnt_outcome`.`fnl_outcome` ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) ) AS `No_Final_Outcome`,
                        sum(
                        ( CASE WHEN ( ( `tbl_other_final_outcome`.`outcome` <> NULL ) AND ( `tbl_appointment`.`app_status` = 'Missed' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Other_Outcome`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 3 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Declined_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 5 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Returned_To_Care`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 6 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Self_Transfer`,
                        sum(
                        ( CASE WHEN ( ( `tbl_clnt_outcome`.`fnl_outcome` = 7 ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Dead`,
                        sum( ( CASE WHEN ( isnull( `tbl_clnt_outcome`.`fnl_outcome` ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) ) AS `Defaulted_No_Final_Outcome`,
                        sum(
                        ( CASE WHEN ( ( `tbl_other_final_outcome`.`outcome` <> NULL ) AND ( `tbl_appointment`.`app_status` = 'Defaulted' ) ) THEN 1 ELSE 0 END ) 
                        ) AS `Defaulted_Other_Outcome` 
                    FROM
                        (
                        (
                        (
                        (
                        (
                        (
                        ( `tbl_appointment` JOIN `tbl_client` ON ( ( `tbl_client`.`id` = `tbl_appointment`.`client_id` ) ) )
                        LEFT JOIN `tbl_clnt_outcome` ON ( ( `tbl_clnt_outcome`.`appointment_id` = `tbl_appointment`.`id` ) ) 
                        )
                        LEFT JOIN `tbl_outcome` ON ( ( `tbl_outcome`.`id` = `tbl_clnt_outcome`.`outcome` ) ) 
                        )
                        LEFT JOIN `tbl_final_outcome` ON ( ( `tbl_final_outcome`.`id` = `tbl_clnt_outcome`.`fnl_outcome` ) ) 
                        )
                        LEFT JOIN `tbl_other_final_outcome` ON ( ( `tbl_other_final_outcome`.`client_outcome_id` = `tbl_clnt_outcome`.`id` ) ) 
                        )
                        JOIN `tbl_partner_facility` ON ( ( `tbl_partner_facility`.`mfl_code` = `tbl_client`.`mfl_code` ) ) 
                        )
                        JOIN `tbl_master_facility` ON ( ( `tbl_master_facility`.`code` = `tbl_client`.`mfl_code` ) ) 
                        )
                        WHERE tbl_appointment.appntmnt_date >= '$formated_date_from' AND tbl_appointment.appntmnt_date <= '$formated_date_to' 
                    " );
                            $data['appnts']  = $query->result();



                            
                        }
                    }
    }
    function summaryappoitment() {

        $access_level = $this->session->userdata('access_level');
        if ($access_level == 'Facility') {
            redirect("Reports/facility_home", "refresh");
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');


            $appnts = " SELECT * FROM appointment_counts ";


            // $query = $this->db->query("SELECT SUM(amount) AS supplier_payments_sum
            // FROM `tbl_supplier_payments` WHERE schooldetail_id=$school_id  AND status = 1");
            // $appnts = $query->result();

            if ($access_level == "Partner") {

               $appnts = " SELECT * FROM partner_appointment_counts WHERE partner_id='$partner_id' ";
            } elseif ($access_level == "County") {
                $appnts .= " AND appointment_counts.county_id = '$county_id' ";
            } elseif ($access_level == "Sub County") {
                $appnts .= " AND appointment_counts.sub_county_id='$sub_county_id' ";
            } elseif ($access_level == "Facility") {
                $appnts .= " AND appointment_counts.mfl_code = '$facility_id' ";
            } else {
                
            }



            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $data['output'] = $this->get_access_level();
            $data['appnts'] = $this->db->query($appnts)->result();




            $this->load->vars($data);
            $this->load->template('Reports/summaryappoitment');
            $function_name = $this->uri->segment(2);
        }
    }

    function get_consented_clients() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }

        $this->db->select('tbl_client.`smsenable` AS k , COUNT(tbl_client.id) AS v');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        $this->db->where('smsenable', 'Yes');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }



        $this->db->group_by("smsenable"); // Produces: GROUP BY Marital Status
        $get_query = $this->db->get()->result_array();



    //        $get_query = $this->db->query("SELECT tbl_client.`smsenable` AS k , COUNT(tbl_client.id) AS v FROM tbl_client  GROUP BY tbl_client.`smsenable`")->result();
        echo json_encode($get_query);
    }

    function client_info() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        $date_from = str_replace('-', '-', $date_from);
        $formated_date_from = date("Y-m-d", strtotime($date_from));

        $date_to = str_replace('-', '-', $date_to);
        $formated_date_to = date("Y-m-d", strtotime($date_to));

        $this->db->select('clinic_number');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }




        $this->db->group_by('clinic_number');

        $num_results = $this->db->count_all_results();
        if (!empty($json)) {
            if ($num_results == "0") {
                $num_results = "0";
                echo json_encode($num_results);
            } else {
                echo json_encode($num_results);
            }
        } else {
            if ($num_results == "0") {
                $num_results = "0";
                return $num_results;
            } else {
                return $num_results;
            }
        }
    }

    function appointment_info_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function appointment_status_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }

        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }


        $appointments_sql = "SELECT 
  tbl_partner_facility.`partner_id`,
  tbl_partner_facility.`county_id`,
  tbl_partner_facility.`sub_county_id`,
  tbl_partner_facility.`mfl_code`,
  COUNT(tbl_appointment.id) AS no_appointments ,
  COUNT((CASE WHEN ((`tbl_appointment`.`active_app` = '0') AND  (`tbl_appointment`.`app_status` = 'Missed')) THEN 9 ELSE NULL END)) AS `missed_appointments`,
  COUNT((CASE WHEN ((`tbl_appointment`.`active_app` = '0') AND  (`tbl_appointment`.`app_status` = 'Defaulted')) THEN 9 ELSE NULL END)) AS `defaulted_appointments`,
  COUNT((CASE WHEN ((`tbl_appointment`.`active_app` = '0') AND  (`tbl_appointment`.`app_status` = 'LTFU')) THEN 9 ELSE NULL END)) AS `LTFU_appointments`,
    COUNT((CASE WHEN ((`tbl_appointment`.`active_app` = '0') AND  (`tbl_appointment`.`appointment_kept` = 'Yes')) THEN 9 ELSE NULL END)) AS `honored_appointments`,
    COUNT((CASE WHEN ((`tbl_appointment`.`active_app` = '1') ) THEN 0 ELSE NULL END)) AS `future_appointments`
 
FROM
  tbl_client 
  INNER JOIN tbl_appointment 
    ON tbl_appointment.`client_id` = tbl_client.`id` 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` WHERE 1  ";



        if (!empty($formated_date_from)) {
            $appointments_sql .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }


        if (!empty($formated_date_to)) {
            $appointments_sql .= " AND tbl_client.created_at <= '$formated_date_to' ";
        }


        if ($access_level === "Admin"):
            $appointments_sql .= " GROUP BY tbl_partner_facility.`partner_id`";
        endif;

        if ($access_level == "Partner"):
            $appointments_sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
            $appointments_sql = " GROUP BY tbl_partner_facility.`partner_id`";
        endif;


        if ($access_level == "Facility"):
            $appointments_sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
            $appointments_sql = " GROUP BY tbl_partner_facility.`mfl_code`";
        endif;

        if (!empty($county_id)) {
            $appointments_sql .= " AND county_id = '$county_id' ";
            $appointments_sql = " GROUP BY tbl_partner_facility.`county_id`";
        }


        if (!empty($sub_county_id)) {
            $appointments_sql .= " AND sub_county_id = '$sub_county_id' ";
            $appointments_sql = " GROUP BY tbl_partner_facility.`sub_county_id`";
        }


        if (!empty($mfl_code)) {
            $appointments_sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }






        $get_query = $this->db->query($appointments_sql)->result();
        //// $this->output->enable_profiler(TRUE);
        if ($get_query == "0") {
            $get_query = "0";
            echo json_encode($get_query);
        } else {
            echo json_encode($get_query);
        }
    }

    function active_appointment_info_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('appointment.active_app', '1');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function old_appointment_info_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('appointment.active_app', '0');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function count_future_appointments() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $current_date = date("Y-m-d");
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.appntmnt_date >=', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();
        //// $this->output->enable_profiler(TRUE);
        return $num_results;
    }

    function count_future_appointments_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.appntmnt_date >=', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function count_past_appointments() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $current_date = date("Y-m-d");
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');

        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();

//        // $this->output->enable_profiler(TRUE);
        return $num_results;
    }

    function count_past_appointments_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();
        //        // $this->output->enable_profiler(TRUE);

        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function count_today_appointments() {
        //// $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.appntmnt_date', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();

        //echo $num_results;
        return $num_results;
    }

    function count_today_appointments_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.appntmnt_date', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function count_honored_appointments() {
        //// $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '0');
        $this->db->where('tbl_appointment.appointment_kept', 'Yes');
        $this->db->where('tbl_appointment.appntmnt_date <=', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();

        //echo $num_results;
        return $num_results;
    }

    function count_honored_appointments_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '0');
        $this->db->where('tbl_appointment.appointment_kept', 'Yes');
        $this->db->where('tbl_appointment.appntmnt_date <=', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function count_missed_appointments() {
        //// $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.app_status', 'Missed');
        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();

        //echo $num_results;
        return $num_results;
    }

    function count_missed_appointments_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.app_status', 'Missed');
        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }


        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function count_defaulted_appointments() {
        //// $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.app_status', 'Defaulted');
        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();

        //echo $num_results;
        return $num_results;
    }

    function count_defaulted_appointments_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.app_status', 'Defaulted');
        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by('appointment.id'); // add group_by

        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }
    function TracingOutcome() {
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
       

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['access_level'] = $access_level;
        $data['partner_id'] = $partner_id;
        $data['facility_id'] = $facility_id;
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $formated_date_from = date("Y-m-d", strtotime('-7 days'));
        $formated_date_to = date("Y-m-d");
       


        

        $access_level = $this->session->userdata('access_level');
        
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');


             //$appnts = " SELECT * FROM outcome_report WHERE 1 LIMIT 100 ";

            // if ($access_level == "Partner") {

            //     $appnts = " SELECT * FROM partner_outcome_report WHERE partner_id='$partner_id' ";
            // } elseif ($access_level == "County") {
            //     $appnts .= " AND outcome_report.county_id = '$county_id' ";
            // } elseif ($access_level == "Sub County") {
            //     $appnts .= " AND outcome_report.sub_county_id='$sub_county_id' ";
            // } elseif ($access_level == "Facility") {
            //     $appnts .= " AND outcome_report.mfl_code = '$facility_id' ";
            // } else {
                
            // }

            // if (!empty($date_from)) {
            //     $appnts .= " AND partner_outcome_report.Appointment_Date >= '$formated_date_from' ";
           
            // }
            // if (!empty($date_to)) {
            //     $appnts .= " AND partner_outcome_report.Appointment_Date <= '$formated_date_to' ";
             
            // }

            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $data['filtered_partner'] = $this->get_partner_filters();
            $data['filtered_county'] = $this->get_county_filtered_values();
            $data['output'] = $this->get_access_level();
             //$data['appnts'] = $this->db->query($appnts)->result();




             $this->load->vars($data);
            $this->load->template('Reports/tracingoutcome');
            $function_name = $this->uri->segment(2);
    
    
}
    function TracingOutcomefilter() {

        
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);

        if (!empty($date_from)):
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;
        
       
        

        // if ($access_level === "Admin"):

        // endif;

        // if ($access_level == "Partner"):
        //     $this->db->where('partner_id', $partner_id);
        // endif;
        // if ($access_level == "Facility"):
        //     $this->db->where('mfl_code', $facility_id);
        // endif;
        // if (!empty($county_id)) {
        //     $this->db->where('county_id', $county_id);
        // }
        // if (!empty($sub_county_id)) {
        //     $this->db->where('sub_county_id', $sub_county_id);
        // }
        // if (!empty($mfl_code)) {
        //     $this->db->where('mfl_code', $mfl_code);
        // }

        // if (!empty($date_from)) {
        //     $this->db->where('created_at >= ', $formated_date_from);
        // }
        // if (!empty($date_to)) {
        //     $this->db->where('created_at <=', $formated_date_to);
        // }
        // // $this->db->group_by("UPN"); // Produces: GROUP BY Gender
        // $query = $this->db->get();
        // if ($query->num_rows() < 2500) {
        //     $get_query = $query->result_array();

        //     echo json_encode($get_query);
        // }
        // else {
        //     $filename = "Outcome Report";




        //     $this->load->library("excel");
        //     $object = new PHPExcel();

        //     $object->setActiveSheetIndex(0);

        //     $table_columns = array("Clinic Number", "MFL Code", "Facility", "Gender",
        //         "Group Name", "Marital", "Partner", "Created At", "Month Year", "Language",
        //         "TXT Time", "County", "Sub County", "Status");



        //     $column = 0;

        //     foreach ($table_columns as $field) {
        //         $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
        //         $column++;
        //     }

        //     $results_data = $query->result();

        //     $excel_row = 2;

        //     foreach ($results_data as $row) {
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->clinic_number);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->mfl_code);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->facility_name);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->gender);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->group_name);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->marital);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->partner_name);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->created_at);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->month_year);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->LANGUAGE);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->txt_time);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->month_year);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->sub_county);
        //         $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->created_at);
        //         $excel_row++;
        //     }


        //     $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
        //     //header('Content-Type: application/vnd.ms-excel');
        //     //header('Content-Disposition: attachment;filename="Client Report.xlsx"');
        //     $a = $filename . date("Y-m-d H:i:s ") . '.xlsx';
        //     $object_writer->save(__DIR__ . '/ExtractReport/' . $a);
        //     $file_location = __DIR__ . '/ExtractReport/' . $a;

        //     $email = $this->session->userdata('email');
        //     $full_name = $this->session->userdata('Fullname');
        //     $subject = "Client Tracing Outcome Report";
        //     $msg = "<h4> Dear  $full_name , </h4> </br> ";
        //     $msg .= "<p> Please find attached Client report from the  system as per your request </p> <br>";
        //     $msg .= "Kind Regrards, <br>";
        //     $msg .= "Ushauri Support Team.  ";


        //     $this->send_mail($full_name, $email, $subject, $file_location, $msg);

        //     $info_msg = "Too much data";
        //     echo json_encode($info_msg);
        // }


        // $data['side_functions'] = $this->data->get_side_modules();
        // $data['top_functions'] = $this->data->get_top_modules();
        // $data['output'] = $this->get_access_level();

        // $data['side_functions'] = $this->data->get_side_modules();
        // $data['top_functions'] = $this->data->get_top_modules();
        // $data['output'] = $this->get_access_level();
       


        

        // $access_level = $this->session->userdata('access_level');
        // if ($access_level == 'Facility') {
        //     redirect("Reports/facility_home", "refresh");
        // } else {
        //     $partner_id = $this->session->userdata('partner_id');
        //     $county_id = $this->session->userdata('county_id');
        //     $sub_county_id = $this->session->userdata('subcounty_id');
        //     $facility_id = $this->session->userdata('facility_id');
        //     $access_level = $this->session->userdata('access_level');


           $appnts = " SELECT * FROM tbl_outcome_report_raw WHERE 1";

            if ($access_level == "Partner") {
                $appnts = " SELECT * FROM tbl_outcome_report_raw WHERE partner_id='$partner_id'";

          
            } elseif ($access_level == "County") {
                $appnts = " SELECT * FROM tbl_outcome_report_raw WHERE county_id='$county_id'";
               
            } elseif ($access_level == "Sub County") {
                $appnts = " SELECT * FROM tbl_outcome_report_raw WHERE sub_county_id='$sub_county_id'";
             
            } elseif ($access_level == "Facility") {
                $appnts = " SELECT * FROM tbl_outcome_report_raw WHERE mfl_code='$facility_id'";
               
            } else {
                
            }

             if (!empty($county_id)) {
      
                  $appnts .= " AND tbl_outcome_report_raw.county_id = '$county_id' ";
            }
            if (!empty($sub_county_id)) {
         
                $appnts .= " AND tbl_outcome_report_raw.sub_county_id='$sub_county_id' ";
            }
            if (!empty($mfl_code)) {
          
                $appnts .= " AND tbl_outcome_report_raw.mfl_code = '$mfl_code' ";
                
            }

            if (!empty($date_from)) {
                $appnts .= " AND tbl_outcome_report_raw.Appointment_Date >= '$formated_date_from' ";
           
            }
            if (!empty($date_to)) {
                $appnts .= " AND tbl_outcome_report_raw.Appointment_Date <= '$formated_date_to' ";
             
            }

            $get_query =  $this->db->query($appnts)->result_array();
        echo json_encode($get_query);
    }

    function count_LTFU_appointments() {
        //// $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.app_status', 'LTFU');
        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();

        //echo $num_results;
        return $num_results;
    }

    function count_LTFU_appointments_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');
        $this->db->where('tbl_appointment.active_app', '1');
        $this->db->where('tbl_appointment.app_status', 'LTFU');
        $this->db->where('tbl_appointment.appntmnt_date <', 'CURDATE()', FALSE);
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment.appntmnt_date <=', $formated_date_to);
        }


        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function client_info_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }
        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $this->db->select('clinic_number');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        if ($access_level == 'Facility') {
            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('partner_facility.mfl_code', $facility_id);
        endif;

        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('partner_facility.mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('client.created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('client.created_at <=', $formated_date_to);
        }



        $num_results = $this->db->count_all_results();


        if ($num_results == "0") {
            $num_results = "0";
            echo json_encode($num_results);
        } else {
            echo json_encode($num_results);
        }
    }

    function filter_county() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');




        $county_id = $this->uri->segment(3);
        $query = "SELECT tbl_county.name AS county_name , tbl_county.id AS county_id FROM tbl_county INNER JOIN tbl_partner_facility ON tbl_partner_facility.county_id = tbl_county.id WHERE 1 ";
        $query .= " AND tbl_partner_facility.partner_id ='$county_id'";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;
        $query .= " GROUP BY tbl_partner_facility.county_id";

        $get_query = $this->db->query($query)->result();
        echo json_encode($get_query);
    }

    function filter_sub_county() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');




        $county_id = $this->uri->segment(3);
        $query = "SELECT tbl_sub_county.name AS sub_county_name , tbl_sub_county.id AS sub_county_id FROM tbl_sub_county INNER JOIN tbl_partner_facility ON tbl_partner_facility.sub_county_id = tbl_sub_county.id WHERE 1 ";
        $query .= " AND tbl_partner_facility.county_id='$county_id'";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;
        $query .= " GROUP BY tbl_partner_facility.sub_county_id";

        $get_query = $this->db->query($query)->result();
        echo json_encode($get_query);
    }

    function filter_time() {
        $facility_id =$this->uri->segment(3);
        $query = "select DISTINCT time from `Monthly_Appointment_Summary` WHERE 1 ";
        $query .= " AND mfl_code = '$facility_id' ";


        $get_query = $this->db->query($query)->result();
        echo json_encode($get_query);
    }

    function filter_facilities() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $sub_county_id = $this->uri->segment(3);
        $query = "SELECT tbl_master_facility.name AS facility_name , tbl_master_facility.code AS mfl_code FROM tbl_master_facility INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_master_facility.code WHERE 1 ";
        $query .= " AND tbl_master_facility.sub_county_id='$sub_county_id' ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        $get_query = $this->db->query($query)->result();
        echo json_encode($get_query);
    }

    function count_appointments() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        $this->db->select('appointment.id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('appointment', 'appointment.client_id=client.id');

        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->group_by('appointment.id'); // add group_by
        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function count_facilities() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');





        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }





        $this->db->select('id');
        $this->db->from('partner_facility');
        if ($access_level == 'Facility') {
            $this->db->where('partner_id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        } else if ($access_level == 'County') {
            $this->db->where('partner_facility.county_id', $county_id);
        } else if ($access_level == 'Sub County') {
            $this->db->where('partner_facility.sub_county_id', $sub_county_id);
        } else {
            
        }
        $this->db->where('status', 'Active');
        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function county_info_json() {



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $sql = "  SELECT COUNT( DISTINCT county_id) as counties FROM tbl_partner_facility  where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at => $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at =< $formated_date_to  ";
        }


        if (!empty($partner_id)) {
            $sql .= "  AND partner_id = $partner_id  ";
        }
        if (!empty($county_id)) {
            $sql .= "  AND county_id = $county_id  ";
        }
        if (!empty($sub_county_id)) {
            $sql .= "  AND sub_county_id = $sub_county_id  ";
        }
        if (!empty($mfl_code)) {
            $sql .= "  AND mfl_code = $mfl_code  ";
        }




        $this->db->query($sql);


        $num_results = $this->db->query($sql)->result();
        foreach ($num_results as $values) {
            $counties = $values->counties;
            echo json_encode($counties);
        }
    }

    function count_counties() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('sub_county_id');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);





        $sql = "  SELECT COUNT( DISTINCT county_id) as counties FROM tbl_partner_facility  where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at => $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at =< $formated_date_to  ";
        }


        if (!empty($partner_id)) {
            $sql .= "  AND partner_id = $partner_id  ";
        }
        if (!empty($county_id)) {
            $sql .= "  AND county_id = $county_id  ";
        }
        if (!empty($sub_county_id)) {
            $sql .= "  AND sub_county_id = $sub_county_id  ";
        }
        if (!empty($mfl_code)) {
            $sql .= "  AND mfl_code = $mfl_code  ";
        }




        $num_results = $this->db->query($sql)->result();
        foreach ($num_results as $values) {
            $counties = $values->counties;
            return $counties;
        }
    }

    function sub_county_info_json() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);
        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $sql = " SELECT COUNT( DISTINCT sub_county_id) as sub_counties FROM tbl_partner_facility where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at >= $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at <= $formated_date_to  ";
        }


        if (!empty($county_id)) {
            $sql .= "  AND county_id = $county_id  ";
        }
        if (!empty($sub_county_id)) {
            $sql .= "  AND sub_county_id = $sub_county_id  ";
        }
        if (!empty($mfl_code)) {
            $sql .= "  AND mfl_code = $mfl_code  ";
        }


        $num_results = $this->db->query($sql)->result();
        foreach ($num_results as $values) {
            $sub_counties = $values->sub_counties;
            //return $sub_counties;


            echo json_encode($sub_counties);
        }
    }

    function count_subcounties() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('sub_county_id');

        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);





        $sql = " SELECT COUNT( DISTINCT sub_county_id) as sub_counties FROM tbl_partner_facility where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at => $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at =< $formated_date_to  ";
        }



        if (!empty($partner_id)) {
            $sql .= "  AND partner_id = $partner_id  ";
        }

        if (!empty($county_id)) {
            $sql .= "  AND county_id = $county_id  ";
        }
        if (!empty($sub_county_id)) {
            $sql .= "  AND sub_county_id = $sub_county_id  ";
        }
        if (!empty($facility_id)) {
            $sql .= "  AND mfl_code = $facility_id  ";
        }





        $num_results = $this->db->query($sql)->result();
        foreach ($num_results as $values) {
            $sub_counties = $values->sub_counties;
            return $sub_counties;
        }
    }

    function count_partners() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $this->db->select('id');
        $this->db->from('partner');
        if ($access_level == 'Facility') {
            $this->db->where('id', $partner_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('id', $partner_id);
        } else {
            
        }
        $this->db->where('status', 'Active');
        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function count_messages() {



        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $sql = "SELECT count(`tbl_clnt_outgoing`.id) AS no_messages FROM tbl_clnt_outgoing 
                   INNER JOIN tbl_client ON tbl_client.id = tbl_clnt_outgoing.`clnt_usr_id`
                   INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code`
                    WHERE 1  ";

        $sql .= " AND tbl_clnt_outgoing.`recepient_type`='Client'";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_clnt_outgoing.created_at >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_clnt_outgoing.created_at <= '$formated_date_to' ";
        }



        $query = $this->db->query($sql)->result();
        foreach ($query as $value) {
            $no_message = $value->no_messages;
            return $no_message;
        }
    }

    function count_messages_json() {



        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $sql = "SELECT count(`tbl_clnt_outgoing`.id) AS no_messages FROM tbl_clnt_outgoing 
                   INNER JOIN tbl_client ON tbl_client.id = tbl_clnt_outgoing.`clnt_usr_id`
                   INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code`
                    WHERE 1  ";

        $sql .= " AND tbl_clnt_outgoing.`recepient_type`='Client'";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_clnt_outgoing.created_at >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_clnt_outgoing.created_at <= '$formated_date_to' ";
        }



        $query = $this->db->query($sql)->result();
        foreach ($query as $value) {
            $no_message = $value->no_messages;
            echo json_encode($no_message);
        }
    }

    function get_messages_queued_dist() {



        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $sql = "SELECT `tbl_message_types`.`name` AS message_type ,COUNT(`tbl_clnt_outgoing`.id) AS no_messages FROM tbl_clnt_outgoing 
                   INNER JOIN tbl_client ON tbl_client.id = tbl_clnt_outgoing.`clnt_usr_id` INNER JOIN `tbl_message_types` ON `tbl_message_types`.`id` = `tbl_clnt_outgoing`.`message_type_id`
                   INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
                    WHERE 1  ";




        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_clnt_outgoing.created_at >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_clnt_outgoing.created_at <= '$formated_date_to' ";
        }

        $sql .= " AND tbl_clnt_outgoing.`recepient_type`='Client' GROUP BY `tbl_message_types`.`id` ";


        $query = $this->db->query($sql)->result();
        echo json_encode($query);
    }

    function count_wellness_checkins() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('sms_checkin', 'sms_checkin.client_id=client.id');
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function count_wellness_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('sms_checkin', 'sms_checkin.client_id=client.id');
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $num_results = $this->db->count_all_results();
        echo json_encode($num_results);
    }

    function count_ok_checkins_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('sms_checkin', 'sms_checkin.client_id=client.id');
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->where('sms_checkin.response_type', 'Positive');
        $num_results = $this->db->count_all_results();
        echo json_encode($num_results);
    }

    function count_not_ok_checkins_json() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('sms_checkin', 'sms_checkin.client_id=client.id');
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->where('sms_checkin.response_type', 'Negative');
        $num_results = $this->db->count_all_results();
        echo json_encode($num_results);
    }

    function count_ok_checkins() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('sms_checkin', 'sms_checkin.client_id=client.id');
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->where('sms_checkin.response_type', 'Positive');
        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function count_not_ok_checkins() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('sms_checkin', 'sms_checkin.client_id=client.id');
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->where('sms_checkin.response_type', 'Negative');
        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function count_un_recognised() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('client');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=client.mfl_code');
        $this->db->join('sms_checkin', 'sms_checkin.client_id=client.id');
        if ($access_level == 'Facility') {

            $this->db->where('client.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }
        $this->db->where('sms_checkin.response_type', 'Other');
        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function broadcasts() {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $this->db->select('id');
        $this->db->from('broadcast');
        $this->db->join('partner_facility', 'partner_facility.mfl_code=broadcast.mfl_code');
        if ($access_level == 'Facility') {

            $this->db->where('broadcast.mfl_code', $facility_id);
        } else if ($access_level == 'Partner') {
            $this->db->where('partner_facility.partner_id', $partner_id);
        } else {
            
        }

        $num_results = $this->db->count_all_results();
        return $num_results;
    }

    function render_data() {

        $get_active_clients = $this->db->query("SELECT COUNT(k.id) as count  FROM(SELECT s.id,s.`created_at`,t.`status` FROM tbl_client s LEFT JOIN  `tbl_client` t ON t.`id`= s.`id` ) k where k.status='Active' GROUP BY k.status,MONTH(k.created_at)")->result();
        $get_disabled_clients = $this->db->query("SELECT COUNT(k.id) as count  FROM(SELECT s.id,s.`created_at`,t.`status` FROM tbl_client s LEFT JOIN  `tbl_client` t ON t.`id`= s.`id` ) k where k.status='Disabled' GROUP BY k.status,MONTH(k.created_at)")->result();
        $get_dead_clients = $this->db->query("SELECT COUNT(k.id) as count  FROM(SELECT s.id,s.`created_at`,t.`status` FROM tbl_client s LEFT JOIN  `tbl_client` t ON t.`id`= s.`id` ) k where k.status='Dead'  GROUP BY k.status,MONTH(k.created_at)")->result();
        $get_months = $this->db->query("SELECT MONTHNAME(k.created_at) as month FROM  tbl_client k  GROUP BY MONTH(k.created_at)")->result();
        $mon = '';

        $categories = array();
        foreach ($get_months as $m):
            array_push($categories, $m->month);

        endforeach;
        $active_clients = array();
        foreach ($get_active_clients as $m):
            array_push($active_clients, $m->count);

        endforeach;
        $disabled_clients = array();
        foreach ($get_disabled_clients as $m):
            array_push($disabled_clients, $m->count);

        endforeach;
        $dead_clients = array();
        foreach ($get_dead_clients as $m):
            array_push($dead_clients, $m->count);

        endforeach;


        print_r($disabled_clients);
        exit();


        $chart = new Highchart();
        $chart->chart->renderTo = "cont";
        $chart->chart->type = "column";
        $chart->title->text = "Monthly Client Status";
        $chart->subtitle->text = "Source: t4a.org";

        $chart->xAxis->categories = $categories;

        $chart->yAxis->min = 0;
        $chart->yAxis->title->text = "Rainfall (mm)";
        $chart->legend->layout = "vertical";
        $chart->legend->backgroundColor = "#FFFFFF";
        $chart->legend->align = "left";
        $chart->legend->verticalAlign = "top";
        $chart->legend->x = 100;
        $chart->legend->y = 70;
        $chart->legend->floating = 1;
        $chart->legend->shadow = 1;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '' + this.x +': '+ this.y +' mm';}");

        $chart->plotOptions->column->pointPadding = 0.2;
        $chart->plotOptions->column->borderWidth = 0;

        $chart->series[] = array(
            'name' => "Active",
            'data' => $active_clients
        );

        $chart->series[] = array(
            'name' => "Disabled",
            'data' => $disabled_clients
        );

        $chart->series[] = array(
            'name' => "Deceased",
            'data' => $dead_clients
        );




        echo '<html>
    <head>
    <title>Basic column</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' .
        $chart->printScripts() .
        '</head>
    <body>
        <div id="cont"></div>
        <script type="text/javascript">' . $chart->render("chart1") . '</script></body>';
    }

    function transfer_in() {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        if ($access_level == "Facility") {
            $clients = $this->db->query("SELECT a.file_no ,  a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name,a.f_name,a.m_name,a.l_name ,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code`  AND a.mfl_code='$facility_id' GROUP BY a.`id` ")->result();
        } else if ($access_level == "Partner") {

            $clients = $this->db->query("SELECT  a.file_no ,  a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name,a.f_name,a.m_name,a.l_name ,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code ,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` AND g.partner_id='$partner_id' GROUP BY a.`id` ")->result();
        } else if ($access_level == "County") {

            $clients = $this->db->query("SELECT a.file_no ,  a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name,a.f_name,a.m_name,a.l_name ,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code ,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` AND g.county_id='$county_id' GROUP BY a.`id` ")->result();
        } else if ($access_level == "Sub County") {

            $clients = $this->db->query("SELECT a.file_no, a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name ,a.f_name,a.m_name,a.l_name ,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code ,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` AND g.sub_county_id='$sub_county_id' GROUP BY a.`id` ")->result();
        } else {

            $clients = $this->db->query("SELECT a.file_no, a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name ,a.f_name,a.m_name,a.l_name, a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital` ,g.partner_id as partner_id,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` GROUP BY a.`id` ")->result();
        }

        $groupings = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );


        $time = array(
            'table' => 'time',
            'where' => array('status' => 'Active')
        );

        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );


        $genders = array(
            'table' => 'gender',
            'where' => array('status' => 'Active')
        );

        $maritals = array(
            'table' => 'marital_status',
            'where' => array('status' => 'Active')
        );



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['clients'] = $clients;
        $data['maritals'] = $this->data->commonGet($maritals);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Reports/transfered_clients');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    function transfer_out() {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }

        if ($access_level == "Facility") {
            $clients = $this->db->query("SELECT a.file_no ,   a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name ,a.f_name,a.m_name,a.l_name,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code`  AND a.prev_clinic='$facility_id'")->result();
        } else if ($access_level == "Partner") {

            $clients = $this->db->query("SELECT a.file_no ,  a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name ,a.f_name,a.m_name,a.l_name,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code ,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` AND g.partner_id='$partner_id'  ")->result();
        } else if ($access_level == "County") {

            $clients = $this->db->query("SELECT a.file_no ,   a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name ,a.f_name,a.m_name,a.l_name,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code ,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` AND g.county_id='$county_id'  ")->result();
        } else if ($access_level == "Sub County") {

            $clients = $this->db->query("SELECT a.file_no ,   a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name ,a.f_name,a.m_name,a.l_name,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code ,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital`,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` AND g.sub_county_id='$sub_county_id'  ")->result();
        } else {

            $clients = $this->db->query("SELECT a.file_no ,  a.`clinic_number`,CONCAT(a.f_name,' ',a.m_name,' ',a.l_name) AS client_name ,a.f_name,a.m_name,a.l_name,a.dob,a.phone_no,a.client_status,
    a.`mfl_code`,a.`prev_clinic` AS old_mfl_code,b.`name` AS new_clinic,
    b.`code`,c.`name` AS prev_clinic , d.`name` AS gender_name , e.`name` AS group_name , f.`marital` ,g.partner_id as partner_id,a.transfer_date
    FROM tbl_client a , tbl_master_facility b, tbl_master_facility c ,tbl_gender d , tbl_groups e,tbl_marital_status f , tbl_partner_facility g
    WHERE a.`mfl_code` = b.`code` AND a.`prev_clinic` = c.`code` AND d.id = a.`gender` AND e.`id` = a.`group_id` AND f.id=a.`marital` AND g.`mfl_code` = a.`mfl_code` ")->result();
        }

        $groupings = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );


        $time = array(
            'table' => 'time',
            'where' => array('status' => 'Active')
        );

        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );


        $genders = array(
            'table' => 'gender',
            'where' => array('status' => 'Active')
        );

        $maritals = array(
            'table' => 'marital_status',
            'where' => array('status' => 'Active')
        );



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['clients'] = $clients;
        $data['maritals'] = $this->data->commonGet($maritals);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Reports/transfered_out');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    /*
      No of Counties in T4A Count
     * 
     *      */

    function no_counties() {



        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        $sql = "  SELECT COUNT( DISTINCT county_id) as counties FROM tbl_partner_facility  where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at => $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at =< $formated_date_to  ";
        }
        if (!empty($county_id)) {
            $sql .= " AND county_id = $county_id";
        }



        $results = $this->db->query($sql)->result();

        echo json_encode($results);
    }

    /*
      No of Sub Counties in T4A Count
     * 
     *      */

    function no_sub_counties() {



        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = " SELECT COUNT( DISTINCT sub_county_id) as sub_counties FROM tbl_partner_facility where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at => $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at =< $formated_date_to  ";
        }

        if (!empty($county_id)) {
            $sql .= "and partner_facility.county_id = '$county_id'";
        }
        if (!empty($sub_county_id)) {
            $sql .= "and partner_facility.sub_county_id = '$sub_county_id'";
        }


        $results = $this->db->query($sql)->result();

        echo json_encode($results);
    }

    /*
      No of Facilities in T4A Count
     * 
     *      */

    function no_facilities() {



        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }





        $sql = " SELECT COUNT( DISTINCT mfl_code) as facilities FROM tbl_partner_facility where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at => $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at =< $formated_date_to  ";
        }

        if (!empty($county_id)) {
            $sql .= " AND partner_facility.county_id = $county_id";
        }
        if (!empty($sub_county_id)) {
            $sql .= " AND partner_facility.sub_county_id = $sub_county_id";
        }

        $results = $this->db->query($sql)->result();

        echo json_encode($results);
    }

    /*
      No of Clients in T4A Count
     * 
     *      */

    function no_clients() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }




        $sql = " SELECT COUNT( DISTINCT clinic_number) as clients FROM tbl_client inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code where 1 ";

        if (!empty($date_from)) {


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND created_at => $formated_date_from  ";
        }



        if (!empty($date_to)) {


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND created_at =< $formated_date_to  ";
        }

        if (!empty($county_id)) {
            $sql .= " AND partner_facility.county_id = $county_id";
        }
        if (!empty($sub_county_id)) {
            $sql .= " AND partner_facility.sub_county_id = $sub_county_id";
        }


        $results = $this->db->query($sql)->result();

        echo json_encode($results);
    }

    /*

     * Cummulative number of counties in T4A System
     *      */

    function cummulative_counties() {

//        $partner_id = $this->session->userdata('partner_id');
//        $facility_id = $this->session->userdata('facility_id');
//        $access_level = $this->session->userdata('access_level');
//
//        $county_id = $this->input->post('county', TRUE);
//        $sub_county_id = $this->input->post('sub_county', TRUE);
//        $mfl_code = $this->input->post('facility', TRUE);
//        $date_from = $this->input->post('date_from', TRUE);
//        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }




        $sub_query = " SELECT MONTH(created_at) AS MONTH,YEAR(created_at) AS YEAR, COUNT(DISTINCT county_id) AS `count`
            FROM tbl_partner_facility WHERE 1";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sub_query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sub_query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sub_query .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sub_query .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sub_query .= " AND  tbl_partner_facility.county_id IS NOT NULL
        GROUP BY MONTH(created_at)
        ORDER BY MONTH(created_at),county_id";


        $main_query = "SELECT d.MONTH,d.YEAR, CONCAT(d.MONTH,' ',d.YEAR) AS MONTH_YEAR,
       @running_sum:=@running_sum + d.count AS running
  FROM ( $sub_query ) d
  JOIN (SELECT @running_sum := 0 AS dummy) dummy";

        $results = $this->db->query($main_query)->result();
        echo json_encode($results);
    }

    /*

     * Cummulative number of counties in T4A System
     *      */

    function cummulative_sub_counties() {






        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }





        $sub_query = " SELECT MONTH(created_at) AS MONTH,YEAR(created_at) AS YEAR, COUNT(DISTINCT Sub_County_ID) AS `count`
            FROM tbl_partner_facility WHERE 1";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sub_query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sub_query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sub_query .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sub_query .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sub_query .= " AND  tbl_partner_facility.Sub_County_ID IS NOT NULL
        GROUP BY MONTH(created_at)
        ORDER BY MONTH(created_at),sub_county_id";


        $main_query = "SELECT d.MONTH,d.YEAR,CONCAT(d.MONTH,' ',d.YEAR) AS MONTH_YEAR,
       @running_sum:=@running_sum + d.count AS running
  FROM ( $sub_query ) d
  JOIN (SELECT @running_sum := 0 AS dummy) dummy";

        $results = $this->db->query($main_query)->result();
        echo json_encode($results);
    }

    /*

     * Cummulative number of facilities in T4A System
     *      */

    function cummulative_facilities() {









        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }





        $sub_query = " SELECT MONTH(created_at) AS MONTH,YEAR(created_at) AS YEAR, COUNT(DISTINCT mfl_code) AS `count`
            FROM tbl_partner_facility WHERE 1";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sub_query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sub_query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sub_query .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sub_query .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sub_query .= " AND  tbl_partner_facility.mfl_code IS NOT NULL
        GROUP BY MONTH(created_at)
        ORDER BY MONTH(created_at),mfl_code ";


        $main_query = "SELECT d.MONTH,d.YEAR,CONCAT(d.MONTH,' ',d.YEAR) AS MONTH_YEAR,
       @running_sum:=@running_sum + d.count AS running
  FROM ( $sub_query ) d
  JOIN (SELECT @running_sum := 0 AS dummy) dummy";

        $results = $this->db->query($main_query)->result();
        echo json_encode($results);
    }

    /*

     * 
     * New Facilities in T4A Per Month
     *      */

    function new_facilties_per_month() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }




        $sub_query = " SELECT COUNT( DISTINCT id) as count, MONTH(created_at) AS MONTH , YEAR(created_at) AS YEAR, CONCAT(MONTH(created_at),' ', YEAR(created_at)) AS MONTH_YEAR  FROM tbl_partner_facility WHERE 1";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sub_query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sub_query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sub_query .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sub_query .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sub_query .= " AND tbl_partner_facility.mfl_code IS NOT NULL
        GROUP BY MONTH(created_at)
        ORDER BY MONTH(created_at) ";


        $main_query = "SELECT d.MONTH,d.YEAR,
       @running_sum:=@running_sum + d.count AS running
  FROM ( $sub_query ) d
  JOIN (SELECT @running_sum := 0 AS dummy) dummy";

        $results = $this->db->query($sub_query)->result();
        echo json_encode($results);
    }

    /*

     * New Clients per Facility on T4A 
     *  */

    function new_clients_per_facility() {



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }





        $sql = "SELECT COUNT(DISTINCT tbl_client.id) as client_count , MONTH(tbl_client.created_at) as month, YEAR(tbl_client.created_at) as YEAR , CONCAT(MONTH(tbl_client.created_at),' ',YEAR(tbl_client.created_at)) AS MONTH_YEAR , tbl_partner_facility.mfl_code,tbl_master_facility.name as facility_name FROM tbl_client "
                . "INNER JOIN tbl_master_facility ON tbl_master_facility.code = tbl_Client.`mfl_code`"
                . " INNER JOIN tbl_county ON tbl_county.id = tbl_master_facility.`county_id`"
                . " INNER JOIN tbl_sub_county ON tbl_sub_county.id = tbl_master_facility.`Sub_County_ID` INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code WHERE 1 ";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sql .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;
        $sql .= " GROUP BY tbl_client.mfl_code , MONTH(tbl_client.created_at), YEAR(tbl_client.created_at) ";

        $results = $this->db->query($sql)->result();
        echo json_encode($results);
    }

    /*
     * 
     * No of Consented Clients in the  System per Timeline
     */

    function no_consented_clients() {



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        $sql = "SELECT COUNT(DISTINCT tbl_client.id) as client_count , MONTH(tbl_client.created_at) as month, YEAR(tbl_client.created_at) as YEAR , CONCAT(MONTH(tbl_client.created_at),' ',YEAR(tbl_client.created_at)) AS MONTH_YEAR , tbl_partner_facility.mfl_code,tbl_master_facility.name as facility_name FROM tbl_client "
                . "INNER JOIN tbl_master_facility ON tbl_master_facility.code = tbl_Client.`mfl_code`"
                . " INNER JOIN tbl_county ON tbl_county.id = tbl_master_facility.`county_id`"
                . " INNER JOIN tbl_sub_county ON tbl_sub_county.id = tbl_master_facility.`Sub_County_ID` INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sql .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;
        $sql .= " AND tbl_client.smsenable='YES' GROUP BY tbl_client.mfl_code , MONTH(tbl_client.created_at), YEAR(tbl_client.created_at) ";

        $results = $this->db->query($sql)->result();
        echo json_encode($results);
    }

    /*
     *
     * No of clients who kept their appointments for the  month  
     *      */

    function no_client_appointments() {




        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = "SELECT COUNT(tbl_appointment.id) AS no_of_appointments FROM tbl_appointment"
                . " INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id`"
                . " WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sql .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sql .= " AND tbl_appointment.`appntmnt_date` >= CURRENT_DATE";

        $results = $this->db->query($sql)->result();
        echo json_encode($results);
    }

    function T4A_Counties() {



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = " SELECT tbl_partner_facility.county_id,sub_county_id, tbl_county.name AS county_name, tbl_sub_county.`name` AS sub_county_name
            FROM tbl_partner_facility INNER JOIN tbl_county ON tbl_county.id = tbl_partner_facility.`county_id`
	   INNER JOIN tbl_sub_county ON tbl_sub_county.`id` = tbl_partner_facility.`sub_county_id`"
                . " WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sql .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;


        $results = $this->db->query($sql)->result();
        echo json_encode($results);
    }

    function T4A_new_Counties() {









        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = " SELECT tbl_partner_facility.county_id,sub_county_id, tbl_county.name AS county_name, tbl_sub_county.`name` AS sub_county_name FROM tbl_partner_facility INNER JOIN tbl_county ON tbl_county.id = tbl_partner_facility.`county_id`
	   INNER JOIN tbl_sub_county ON tbl_sub_county.`id` = tbl_partner_facility.`sub_county_id`  WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sql .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sql .= " AND  MONTH(tbl_partner_facility.`created_at`)=MONTH(CURRENT_DATE) ";
        $results = $this->db->query($sql)->result();
        echo json_encode($results);













//        
//        
//        $results = $this->db->query(" SELECT tbl_partner_facility.county_id,sub_county_id, tbl_county.name AS county_name, tbl_sub_county.`name` AS sub_county_name FROM tbl_partner_facility INNER JOIN tbl_county ON tbl_county.id = tbl_partner_facility.`county_id`
//	   INNER JOIN tbl_sub_county ON tbl_sub_county.`id` = tbl_partner_facility.`sub_county_id` WHERE MONTH(tbl_partner_facility.`created_at`)=MONTH(CURRENT_DATE)")->result();
    }

    function new_facilties_by_keph_level() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);





        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = " SELECT * FROM tbl_partner_facility"
                . " INNER JOIN tbl_master_facility ON tbl_master_facility.`code` = tbl_partner_facility.`mfl_code`"
                . " WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sql .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sql .= " AND MONTH(tbl_partner_facility.`created_at`) = MONTH(CURRENT_DATE) GROUP BY keph_level ";
        $results = $this->db->query($sql)->result();
        echo json_encode($results);
    }

    function new_facilties_by_facility_type() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);





        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = " SELECT * FROM tbl_partner_facility"
                . " INNER JOIN tbl_master_facility ON tbl_master_facility.`code` = tbl_partner_facility.`mfl_code`"
                . " WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sql .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sql .= " AND MONTH(tbl_partner_facility.`created_at`) = MONTH(CURRENT_DATE) GROUP BY facility_type";
        $results = $this->db->query($sql)->result();
        echo json_encode($results);
    }

    function cummulative_consented_clients() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);








        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sub_query = " SELECT MONTH(tbl_client.created_at) AS MONTH,YEAR(tbl_client.created_at) AS YEAR,tbl_groups.`name` AS group_name, tbl_groups.id AS group_id, COUNT(DISTINCT tbl_client.id) AS `count`
            FROM tbl_client INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id` INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code  WHERE 1";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sub_query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sub_query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sub_query .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sub_query .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sub_query .= " AND tbl_client.id IS NOT NULL AND tbl_client.`created_at` IS NOT NULL AND tbl_client.smsenable='YES' AND row(tbl_client.group_id) IN (Select id as group_id from tbl_groups)
        GROUP BY MONTH(tbl_client.created_at),group_id
        ORDER BY MONTH(tbl_client.created_at),group_id ";


        $main_query = "SELECT d.MONTH,d.YEAR,d.group_name,d.group_id,CONCAT(d.MONTH,' ',d.YEAR) AS MONTH_YEAR,
       @running_sum:=@running_sum + d.count AS running
  FROM (  $sub_query ) d
  JOIN (SELECT @running_sum := 0 AS dummy) dummy";

        $results = $this->db->query($main_query)->result();
        echo json_encode($results);
    }

    function cummulative_client_per_group() {



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }




        $sub_query = " SELECT MONTH(tbl_client.created_at) AS MONTH,YEAR(tbl_client.created_at) AS YEAR,tbl_groups.`name` AS group_name, tbl_groups.id AS group_id, COUNT(DISTINCT tbl_client.id) AS `count`
            FROM tbl_client INNER JOIN tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id` WHERE 1";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sub_query .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sub_query .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)):
            $sub_query .= " AND tbl_partner_facility.county_id='$county_id' ";
        endif;


        if (!empty($sub_county_id)):
            $sub_query .= " AND tbl_partner_faciltiy.sub_county_id='$sub_county_id' ";
        endif;


        if (!empty($date_from)) :


            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= "  AND tbl_partner_facility.created_at => $formated_date_from  ";
        endif;



        if (!empty($date_to)):


            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= "  AND tbl_partner_facility.created_at =< $formated_date_to  ";
        endif;

        $sub_query .= " AND tbl_client.id IS NOT NULL AND tbl_client.`created_at` IS NOT NULL
        GROUP BY group_id, MONTH(tbl_client.created_at)
        ORDER BY group_id, MONTH(tbl_client.created_at) ";


        $main_query = "SELECT d.MONTH,d.YEAR,d.group_name,d.group_id,CONCAT(d.MONTH,' ',d.YEAR) AS MONTH_YEAR,
       @running_sum:=@running_sum + d.count AS running
  FROM (  $sub_query ) d
  JOIN (SELECT @running_sum := 0 AS dummy) dummy";

        $results = $this->db->query($main_query)->result();
        echo json_encode($results);
    }

    function appointments_mapping() {
        $get_appointments = $this->db->query("Select id, client_id from tbl_appointment ")->result();
        foreach ($get_appointments as $value) {
            $appointment_id = $value->id;
            $client_id = $value->client_id;
            $get_appointments_arch = $this->db->query("Select id,appointment_id, client_id from tbl_appointment_arch where client_id='$client_id'")->result();
            foreach ($get_appointments_arch as $value) {
                $arch_id = $value->id;
                $appointment_id_arch = $value->appointment_id;
                $client_id_arch = $value->client_id;

                if ($appointment_id === $appointment_id_arch) {
                    echo 'THey exists </br>';
                } else {
                    $data_update = array(
                        'appointment_id' => $appointment_id
                    );
                    $this->db->where('id', $arch_id);
                    $this->db->update('appointment_arch', $data_update);
                }
            }
        }
    }

    function consented_clients_gender() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);





        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }





        $sql = "SELECT tbl_gender.`name` AS gender ,COUNT(tbl_client.ID) AS total_client  FROM tbl_client INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code INNER JOIN tbl_gender ON tbl_gender.id = tbl_client.gender  WHERE 1 ";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;




        if (!empty($county_id)) {
            $sql .= "AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id ='$sub_county_id'";
        }

        if (!empty($facility_id)) {
            $sql .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_client.created_at <= '$formated_date_to' ";
        }



        $sql .= "AND  tbl_client.`smsenable`='Yes' GROUP BY tbl_client.`gender` ";

        $query = $this->db->query($sql)->result();
        echo json_encode($query);
    }

    function consented_clients_marital() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = "SELECT tbl_marital_status.`marital` AS marital ,COUNT(tbl_client.ID) AS total_client  FROM tbl_client INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code INNER JOIN tbl_marital_status ON tbl_marital_status.id = tbl_client.marital  WHERE 1 ";


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;



        if (!empty($county_id)) {
            $sql .= "AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id ='$sub_county_id'";
        }

        if (!empty($facility_id)) {
            $sql .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_client.created_at <= '$formated_date_to' ";
        }



        $sql .= "AND  tbl_client.`smsenable`='Yes' GROUP BY tbl_client.`marital` ";


        $query = $this->db->query($sql)->result();
        echo json_encode($query);
    }

    function consented_clients_groups() {


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);







        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = "SELECT tbl_groups.`name` AS group_name ,COUNT(tbl_client.ID) AS total_client  FROM tbl_client INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code INNER JOIN tbl_groups ON tbl_groups.id = tbl_client.group_id  WHERE 1 ";

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;


        if (!empty($county_id)) {
            $sql .= "AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id ='$sub_county_id'";
        }

        if (!empty($facility_id)) {
            $sql .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        }

        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_client.created_at >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_client.created_at <= '$formated_date_to' ";
        }



        $sql .= "AND  tbl_client.`smsenable`='Yes' GROUP BY tbl_client.`group_id` ";


        $query = $this->db->query($sql)->result();
        echo json_encode($query);
    }

    function appointment_status_distribution() {
        //header('Content-Type: application/json');
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);





        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $missed_sql = " Select 
  'Missed',
  count(DISTINCT tbl_appointment.id) as total
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.`client_id` 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
where `tbl_appointment`.`active_app` = '1' 
  AND `tbl_appointment`.`app_status` = 'Missed' and tbl_appointment.`appntmnt_date` < curdate() ";
        $defaulted_sql = "Select 
  'Defaulted',
  count(DISTINCT tbl_appointment.id) as total
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.`client_id` 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
where `tbl_appointment`.`active_app` = '1' 
  AND `tbl_appointment`.`app_status` = 'Defaulted' and tbl_appointment.`appntmnt_date` < curdate() ";
        $ltfu_sql = "Select 
  'LTFU',
  count(DISTINCT tbl_appointment.id) as total
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.`client_id` 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
where `tbl_appointment`.`active_app` = '1' 
  AND `tbl_appointment`.`app_status` = 'LTFU' and tbl_appointment.`appntmnt_date` < curdate() ";
        $honored_sql = " Select 
  'Honored ',
  count(DISTINCT tbl_appointment.id) as total
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.`client_id` 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
where `tbl_appointment`.`active_app` = '0' 
  AND `tbl_appointment`.`appointment_kept`='Yes' and tbl_appointment.`appntmnt_date` < curdate() ";
        $future_sql = " Select 
  'Future ',
  count(DISTINCT tbl_appointment.id) as total
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.`client_id` 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
where `tbl_appointment`.`active_app` = '1' and tbl_appointment.`appntmnt_date` >= curdate() ";







        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $missed_sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
            $defaulted_sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
            $ltfu_sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
            $honored_sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
            $future_sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $missed_sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
            $defaulted_sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
            $ltfu_sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
            $honored_sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
            $future_sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $missed_sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
            $defaulted_sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
            $ltfu_sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
            $honored_sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
            $future_sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $missed_sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
            $defaulted_sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
            $ltfu_sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
            $honored_sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
            $future_sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $missed_sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
            $defaulted_sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
            $ltfu_sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
            $honored_sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
            $future_sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $missed_sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
            $defaulted_sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
            $ltfu_sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
            $honored_sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
            $future_sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $missed_sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
            $defaulted_sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
            $ltfu_sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
            $honored_sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
            $future_sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
        }


//        $missed_sql .= " GROUP BY tbl_appointment.id ";
//        $defaulted_sql .= " GROUP BY tbl_appointment.id ";
//        $ltfu_sql .= " GROUP BY tbl_appointment.id ";
//        $honored_sql .= " GROUP BY tbl_appointment.id ";
//        $future_sql .= " GROUP BY tbl_appointment.id ";




        $union_sql = $missed_sql . " UNION " . $defaulted_sql . " UNION " . $ltfu_sql . "UNION " . $honored_sql . " UNION " . $future_sql;




        //echo 'UNION SQL => ' . $union_sql;

        $query = $this->db->query($union_sql)->result();
        echo json_encode($query);










//
//
//        $sql = "
//SELECT tbl_appointment.`app_status` , (
//    CASE 
//        WHEN app_status = 'Booked' THEN COUNT(tbl_appointment.`id`)
//        WHEN app_status = 'Notified' THEN COUNT(tbl_appointment.`id`)
//        WHEN app_status = 'Missed' THEN COUNT(tbl_appointment.`id`)
//        WHEN app_status = 'Defaulted' THEN COUNT(tbl_appointment.`id`)
//        ELSE COUNT(tbl_appointment.`id`)
//    END) AS total ,COUNT(tbl_appointment.`id`) AS appointment_status FROM tbl_appointment INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` WHERE 1";
//
//        if ($access_level === "Admin"):
//
//        endif;
//
//        if ($access_level == "Partner"):
//            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
//        endif;
//
//
//        if ($access_level == "Facility"):
//            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
//        endif;
//
//        if (!empty($county_id)) {
//            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
//        }
//
//        if (!empty($sub_county_id)) {
//            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
//        }
//        if (!empty($mfl_code)) {
//            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
//        }
//
//
//
//        if (!empty($date_from)) {
//            $date_from = str_replace('-', '-', $date_from);
//            $formated_date_from = date("Y-m-d", strtotime($date_from));
//            $sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
//        }
//        if (!empty($date_to)) {
//            $date_to = str_replace('-', '-', $date_to);
//            $formated_date_to = date("Y-m-d", strtotime($date_to));
//            $sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
//        }
//
//
//
//
//        $sql .= "  GROUP BY tbl_appointment.`app_status`";
//
//        $query = $this->db->query($sql)->result();
//        echo json_encode($query);
    }

    function appointment_distribution_by_booked() {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = "
SELECT tbl_groups.`name` AS group_name , tbl_appointment.`app_status` , (
    CASE 
        WHEN app_status = 'Booked' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Notified' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Missed' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Defaulted' THEN COUNT(tbl_appointment.`id`)
        ELSE COUNT(tbl_appointment.`id`)
    END) AS total ,COUNT(tbl_appointment.`id`) AS appointment_status FROM tbl_appointment 
    INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` 
    INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id` 
    INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
    WHERE 1 ";

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
        }



        $sql .= " AND tbl_appointment.app_status='Booked' GROUP BY tbl_groups.`name`,tbl_appointment.`app_status` ";

        $query = $this->db->query($sql)->result();

        echo json_encode($query);
    }

    function appointment_distribution_by_notified() {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);




        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = "
SELECT tbl_groups.`name` AS group_name , tbl_appointment.`app_status` , (
    CASE 
        WHEN app_status = 'Booked' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Notified' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Missed' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Defaulted' THEN COUNT(tbl_appointment.`id`)
        ELSE COUNT(tbl_appointment.`id`)
    END) AS total ,COUNT(tbl_appointment.`id`) AS appointment_status FROM tbl_appointment 
    INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` 
    INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id` 
    INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
    WHERE 1 ";

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
        }


        $sql .= " AND tbl_appointment.app_status='Notified'  GROUP BY tbl_groups.`name`,tbl_appointment.`app_status` ";

        $query = $this->db->query($sql)->result();

        echo json_encode($query);
    }

    function appointment_distribution_by_missed() {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = "
SELECT tbl_groups.`name` AS group_name , tbl_appointment.`app_status` , (
    CASE 
        WHEN app_status = 'Booked' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Notified' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Missed' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Defaulted' THEN COUNT(tbl_appointment.`id`)
        ELSE COUNT(tbl_appointment.`id`)
    END) AS total ,COUNT(tbl_appointment.`id`) AS appointment_status FROM tbl_appointment 
    INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` 
    INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id` 
    INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
    WHERE 1 ";

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
        }


        $sql .= " AND tbl_appointment.app_status='Missed'  GROUP BY tbl_groups.`name`,tbl_appointment.`app_status` ";

        $query = $this->db->query($sql)->result();

        echo json_encode($query);
    }

    function appointment_distribution_by_defaulted() {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        $sql = "
SELECT tbl_groups.`name` AS group_name , tbl_appointment.`app_status` , (
    CASE 
        WHEN app_status = 'Booked' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Notified' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Missed' THEN COUNT(tbl_appointment.`id`)
        WHEN app_status = 'Defaulted' THEN COUNT(tbl_appointment.`id`)
        ELSE COUNT(tbl_appointment.`id`)
    END) AS total ,COUNT(tbl_appointment.`id`) AS appointment_status FROM tbl_appointment 
    INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` 
    INNER JOIN tbl_groups ON tbl_groups.`id` = tbl_client.`group_id` 
    INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
    WHERE 1 ";

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_appointment.appntmnt_date >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_appointment.appntmnt_date <= '$formated_date_to' ";
        }



        $sql .= " AND tbl_appointment.app_status='Defaulted' GROUP BY tbl_groups.`name`,tbl_appointment.`app_status` ";

        $query = $this->db->query($sql)->result();

        echo json_encode($query);
    }

    function percentage_counties() {
        $sql1 = "Select count(id) as target_county from tbl_target_county  ";
        $sql2 = "SELECT COUNT( DISTINCT `county_id`) FROM tbl_partner_facility ";

        $main_sql = "Select ($sql1) as target_counties , ($sql2) as actual_counties ";

        $query = $this->db->query($main_sql)->result();
        echo json_encode($query);
    }

    function percentage_facilities() {



        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');





        $sql1 = "Select count(id) as target_facilities from tbl_target_facility  ";
        $sql2 = "SELECT COUNT( DISTINCT `mfl_code`) FROM tbl_partner_facility ";

        $main_sql = "Select ($sql1) as target_facilities , ($sql2) as actual_facilities ";

        $query = $this->db->query($main_sql)->result();
        echo json_encode($query);
    }

    function count_msgs_sent() {


        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }



        $sql = "SELECT (`tbl_clnt_outgoing`.id) AS no_messages FROM tbl_clnt_outgoing 
                   INNER JOIN tbl_client ON tbl_client.id = tbl_clnt_outgoing.`clnt_usr_id`
                   INNER JOIN `tbl_message_types` ON tbl_message_types.`id` = tbl_clnt_outgoing.`message_type_id`
                   INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code`
                    WHERE 1  ";

        $sql .= " AND tbl_clnt_outgoing.`recepient_type`='Client'";



        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $sql .= " AND tbl_partner_facility.partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }

        if (!empty($sub_county_id)) {
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_partner_facility.mfl_code = '$mfl_code' ";
        }



        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
            $sql .= " AND tbl_clnt_outgoing.created_at >= '$formated_date_from' ";
        }
        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
            $sql .= " AND tbl_clnt_outgoing.created_at <= '$formated_date_to' ";
        }

        $query = $this->db->query($sql)->result();
        echo json_encode($query);
    }

    function cummulative_client_by_group() {
        $get_month_years = $this->db->query("SELECT DATE_FORMAT(e.created_at,'%b %y') AS month_year  FROM tbl_client AS e ,tbl_groups AS g WHERE e.group_id = g.id
GROUP BY MONTHNAME(e.created_at) ORDER BY e.`created_at`")->result();
        $get_group_name = $this->db->query("SELECT DATE_FORMAT(e.created_at,'%b %y') AS month_year ,g.name AS group_name,
    COUNT(e.id) AS count_clients,e.created_at FROM tbl_client AS e ,tbl_groups AS g WHERE e.group_id = g.id
GROUP BY MONTHNAME(e.created_at),e.group_id ORDER BY g.name,e.`created_at`")->result();
        $get_cummulative_result = $this->db->query("SELECT DATE_FORMAT(e.created_at,'%b %y') AS month_year ,g.name AS group_name,
    COUNT(e.id) AS count_clients,e.created_at
FROM tbl_client AS e ,tbl_groups AS g WHERE e.group_id = g.id
GROUP BY MONTHNAME(e.created_at),e.group_id ORDER BY g.name,e.`created_at`")->result();
        $final_array = array();
        $final = array();
        $client_count = 0;

        $month_array = array();
        $group_array = array();
        $result_array = array();
        foreach ($get_month_years as $month_value) {
            $month_name = $month_value->month_year;
            array_push($month_array, $month_name);

            $get_group_name = $this->db->query("SELECT DATE_FORMAT(e.created_at,'%b %y') AS month_year ,g.name AS group_name,
    COUNT(e.id) AS count_clients,e.created_at FROM tbl_client AS e ,tbl_groups AS g WHERE e.group_id = g.id AND  DATE_FORMAT(e.created_at,'%b %y') ='$month_name'
GROUP BY MONTHNAME(e.created_at),e.group_id ORDER BY g.name,e.`created_at`")->result();
            echo 'Start fisrt group ...</br>';
            foreach ($get_group_name as $group_names) {
                $group_name = $group_names->group_name;
                echo 'Group Name => ' . $group_name . '</br> ';
                array_push($group_array, $group_name);
                $get_client_count = $get_cummulative_result = $this->db->query("SELECT DATE_FORMAT(e.created_at,'%b %y') AS month_year ,g.name AS group_name,
    COUNT(e.id) AS count_clients,e.created_at
FROM tbl_client AS e ,tbl_groups AS g WHERE e.group_id = g.id AND DATE_FORMAT(e.created_at,'%b %y') ='$month_name' and g.name='$group_name'
GROUP BY MONTHNAME(e.created_at),e.group_id ORDER BY g.name,e.`created_at`")->result();
                foreach ($get_client_count as $value) {
                    $count_clients = $value->count_clients;
                    array_push($result_array, $count_clients);
                    echo 'Count clients' . $count_clients . '</br>';
                }
                echo 'End first fgroup</br> ';
                array_push($group_array, $result_array);
            }
        }


        echo json_encode($group_array);
    }

    function clnt_no_app() {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $sql = " SELECT tbl_client.id, file_no,
            clinic_number,
  f_name,
  m_name,
  l_name,
  phone_no,
  tbl_client.status,
  enrollment_date,
  art_date,
  tbl_master_facility.name as facility_name,
  tbl_sub_county.name as sub_county_name,
  tbl_county.name as county_name 
FROM
  tbl_client 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.mfl_code 
  INNER JOIN tbl_county 
    ON tbl_county.id = tbl_partner_facility.county_id 
  INNER JOIN tbl_sub_county 
    on tbl_sub_county.id = tbl_partner_facility.sub_county_id 
  INNER JOIN tbl_master_facility 
    on tbl_master_facility.code = tbl_partner_facility.mfl_code 
WHERE 1 ";

        if ($access_level == "Partner") {

            $sql .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } elseif ($access_level == "County") {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        } elseif ($access_level == "Sub County") {
            $sql .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } elseif ($access_level == "Facility") {
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        } else {
            
        }

        $sql .= "AND tbl_client.id NOT IN (SELECT client_id FROM tbl_appointment) ";



        $groupings = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );


        $time = array(
            'table' => 'time',
            'where' => array('status' => 'Active')
        );

        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );

        $genders = array(
            'table' => 'gender',
            'where' => array('status' => 'Active')
        );



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['appointments'] = $this->db->query($sql)->result();
        $data['access_level'] = $this->get_access_level();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Reports/clnt_no_apps');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    function clnt_report() {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $sql = " SELECT * FROM vw_client_summary_report WHERE 1 ";

        if ($access_level == "Partner") {

            $sql .= " AND vw_client_summary_report.partner_id='$partner_id' ";
        } elseif ($access_level == "County") {
            $sql .= " AND vw_client_summary_report.county_id = '$county_id' ";
        } elseif ($access_level == "Sub County") {
            $sql .= " AND vw_client_summary_report.sub_county_id='$sub_county_id' ";
        } elseif ($access_level == "Facility") {
            $sql .= " AND vw_client_summary_report.mfl_code = '$facility_id' ";
        } else {
            $sql = " SELECT * FROM vw_client_summary_report ";
        }



        $groupings = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );


        $time = array(
            'table' => 'time',
            'where' => array('status' => 'Active')
        );

        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );

        $genders = array(
            'table' => 'gender',
            'where' => array('status' => 'Active')
        );



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['appointments'] = $this->db->query($sql)->result();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        // $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Reports/client_report');
            } else {
                $this->load->template('Reports/client_report');
//                echo 'Invalid Access';
//                exit();
            }
        }
    }

    function user_report() {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $sql = " SELECT * FROM vw_user_access_report  ";

        if ($access_level == "Partner") {
            $sql .= " INNER JOIN tbl_partner_facility on tbl_partner_facility.partner_id = vw_user_access_report.mapping_id WHERE 1 ";
            $sql .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } elseif ($access_level == "County") {
            $sql .= " INNER JOIN tbl_partner_facility on tbl_partner_facility.county_id = vw_user_access_report.mapping_id WHERE 1 ";
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        } elseif ($access_level == "Sub County") {
            $sql .= " INNER JOIN tbl_partner_facility on tbl_partner_facility.sub_county_id = vw_user_access_report.mapping_id WHERE 1 ";
            $sql .= " AND tbl_partner_facility.sub_county_id ='$sub_county_id' ";
        } elseif ($access_level == "Facility") {
            $sql .= " INNER JOIN tbl_partner_facility on tbl_partner_facility.mfl_code = vw_user_access_report.mapping_id  WHERE 1 ";
            $sql .= " AND tbl_partner_facility.mfl_code = '$facility_id' ";
        } else {
            
        }



        $groupings = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );


        $time = array(
            'table' => 'time',
            'where' => array('status' => 'Active')
        );

        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );

        $genders = array(
            'table' => 'gender',
            'where' => array('status' => 'Active')
        );



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['user_report'] = $this->db->query($sql)->result();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Reports/user_report');
            } else {
                $this->load->template('Reports/user_report');
//                echo 'Invalid Access';
//                exit();
            }
        }
    }

    function show_calendar() {

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
       
        $this->load->vars($data);
        $this->load->template('Reports/app_calendar');
    }

    function get_count_appointments() {
        $url_path = base_url() . 'home/get_current_appointments/id_all/';
        $access_level = $this->session->userdata('access_level');

        $query = " SELECT CONCAT( 'Total App : ', COUNT( tbl_appointment.id ) ) AS title, tbl_appointment.`appntmnt_date` AS start,
	                CONCAT( '$url_path', tbl_appointment.id ) AS url FROM tbl_appointment	
                    INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	                INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	                INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
                    WHERE 1 ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= " GROUP BY tbl_appointment.appntmnt_date ";
        $output = $this->db->query($query)->result();
        echo json_encode($output);
       // $this->output->enable_profiler(TRUE);
    }

    function get_count_vl() {
        $url_path = base_url() . 'home/get_current_appointments/id/';

        $access_level = $this->session->userdata('access_level');

        $query = " SELECT CONCAT( 'Viral Load : ', COUNT( tbl_appointment.id ) ) AS title, tbl_appointment.`appntmnt_date` AS start,
	                CONCAT( '$url_path', tbl_appointment.id ) AS url FROM tbl_appointment
                    INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
                    INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
                    INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
                    WHERE 1  and tbl_appointment_types.name LIKE '%Viral Load%' ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= " AND tbl_appointment.status='Active' GROUP BY appntmnt_date ";
        $sql = $this->db->query($query)->result();

        echo json_encode($sql);
    }


    function get_unscheduled_appointments(){
        $url_path = base_url() . 'home/get_todays_unscheduled/';
        $access_level = $this->session->userdata('access_level');

        $query = " SELECT CONCAT( 'Unscheduled : ', COUNT( tbl_appointment.id ) ) AS title, tbl_appointment.`appntmnt_date` AS start,
	                CONCAT('$url_path',  tbl_appointment.id ) AS url FROM tbl_appointment	
                    INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	                INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	                INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
                    WHERE visit_type = 'Un-Scheduled' ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= " GROUP BY tbl_appointment.appntmnt_date ";
        $output = $this->db->query($query)->result();
        echo json_encode($output); 
    }

    function get_count_confirmed(){
        $url_path = base_url() . 'home/get_todays_confirmed/';
        $access_level = $this->session->userdata('access_level');

        $query = " SELECT CONCAT( 'Honored : ', COUNT( tbl_appointment.id ) ) AS title, tbl_appointment.`appntmnt_date` AS start,
	                CONCAT('$url_path',  tbl_appointment.id ) AS url FROM tbl_appointment	
                    INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	                INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	                INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
                    WHERE active_app = '0' AND appointment_kept = 'Yes' AND visit_type <> 'Un-Scheduled' ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= " GROUP BY tbl_appointment.appntmnt_date ";
        $output = $this->db->query($query)->result();
        echo json_encode($output); 
    }

    function trial() {
        $query = " SELECT
	CONCAT( 'Total Appointments : ', COUNT( tbl_appointment.id ) ) AS title,
	tbl_appointment.`appntmnt_date` AS start,
	CONCAT( ' ', tbl_appointment.id ) AS url 
FROM
	tbl_appointment
	INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
WHERE
	1
  ";
        $output = $this->db->query($query)->result();
        echo json_encode($output);
    }

    function get_count_re_fill() {
        $url_path = base_url() . 'home/get_current_appointments/id/';

        $access_level = $this->session->userdata('access_level');

        $query = " SELECT CONCAT( 'Re Fill : ', COUNT( tbl_appointment.id ) ) AS title, tbl_appointment.`appntmnt_date` AS start,
	                CONCAT( '$url_path', tbl_appointment.id ) AS url FROM tbl_appointment
                    INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
                    INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
                    INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
                    WHERE 1  and tbl_appointment_types.name LIKE '%Fill%' ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= " AND tbl_appointment.status='Active' GROUP BY appntmnt_date ";
        $sql = $this->db->query($query)->result();

        echo json_encode($sql);
    }

    function get_count_clinical() {
        $url_path = base_url() . 'home/get_current_appointments/id/';

        $access_level = $this->session->userdata('access_level');

        $query = " SELECT CONCAT( 'Clinical review : ', COUNT( tbl_appointment.id ) ) AS title,
	                tbl_appointment.`appntmnt_date` AS start, CONCAT( '$url_path', tbl_appointment.id ) AS url 
                    FROM tbl_appointment
	                INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	                INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	                INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
                    WHERE	1  and tbl_appointment_types.name LIKE '%Clinical%'";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= "  AND tbl_appointment.status='Active' GROUP BY appntmnt_date ";
        $sql = $this->db->query($query)->result();

        echo json_encode($sql);
    }

    function get_count_adherence() {
        $url_path = base_url() . 'home/today_appointments/id/';


        $access_level = $this->session->userdata('access_level');

        $query = " SELECT
	CONCAT( 'Enhanced Adherence : ', COUNT( tbl_appointment.id ) ) AS title,
	tbl_appointment.`appntmnt_date` AS start,
	CONCAT( '$url_path', tbl_appointment.id ) AS url 
FROM
	tbl_appointment
	INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
WHERE
	1 and tbl_appointment_types.name LIKE '%Adherence%'
  ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= "  AND tbl_appointment.status='Active' GROUP BY appntmnt_date ";
        $sql = $this->db->query($query)->result();

        echo json_encode($sql);
    }

    function get_count_enhanced_adherence() {
        $url_path = base_url() . 'home/today_appointments/id/';


        $access_level = $this->session->userdata('access_level');

        $query = " SELECT
	CONCAT( 'Enhanced Adherence : ', COUNT( tbl_appointment.id ) ) AS title,
	tbl_appointment.`appntmnt_date` AS start,
	CONCAT( '$url_path', tbl_appointment.id ) AS url 
FROM
	tbl_appointment
	INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
WHERE
	1 and tbl_appointment_types.name LIKE '% Adherence%'
  ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= "  AND tbl_appointment.status='Active' GROUP BY appntmnt_date ";
        $sql = $this->db->query($query)->result();




        echo json_encode($sql);
    }

    function get_count_other() {
        $url_path = base_url() . 'home/today_appointments/id/';


        $access_level = $this->session->userdata('access_level');

        $query = " SELECT
	CONCAT( 'Other : ', COUNT( tbl_appointment.id ) ) AS title,
	tbl_appointment.`appntmnt_date` AS start,
	CONCAT( '$url_path', tbl_appointment.id ) AS url 
FROM
	tbl_appointment
	INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_1 
	INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id
	INNER JOIN tbl_partner_facility ON tbl_partner_facility.mfl_code = tbl_client.mfl_code
WHERE
	1 and tbl_appointment_types.name LIKE '%Other%'
  ";


        if ($access_level == "Partner") {
            $partner_id = $this->session->userdata('partner_id');
            $query .= " AND tbl_partner_facility.partner_id='$partner_id' ";
        } else if ($access_level == "County") {
            $county_id = $this->session->userdata('county_id');
            $query .= " AND tbl_partner_facility.partner_id='$county_id' ";
        } else if ($access_level == "Sub County") {
            $sub_county_id = $this->session->userdata('sub_county_id');
            $query .= " AND tbl_partner_facility.sub_county_id='$sub_county_id' ";
        } else if ($access_level == "Facility") {
            $mfl_code = $this->session->userdata('facility_id');
            $query .= " AND tbl_partner_facility.mfl_code='$mfl_code' ";
        } else {
            $query .= " ";
        }
        $query .= "  AND tbl_appointment.status='Active' GROUP BY appntmnt_date ";
        $sql = $this->db->query($query)->result();




        echo json_encode($sql);
    }

    function art_attended_summary() {
        $get_query = "SELECT 
  `tbl_appointment`.`app_status` AS app_status,
  COUNT(
    CASE
      WHEN tbl_gender.`name` = 'Male' 
      THEN tbl_appointment.id 
    END
  ) Male1,
  COUNT(
    CASE
      WHEN tbl_gender.`name` = 'Female' 
      THEN tbl_appointment.id 
    END
  ) Female1 
FROM
  tbl_client 
  INNER JOIN tbl_gender 
    ON tbl_gender.`id` = tbl_client.`gender` 
  INNER JOIN tbl_appointment 
    ON tbl_appointment.`client_id` = tbl_client.id 
  INNER JOIN tbl_partner_facility 
    ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` 
WHERE tbl_appointment.`app_status` IS NOT NULL 
  AND ROUND(
    DATEDIFF(CURRENT_DATE, (tbl_client.dob)) / 365,
    0
  ) > 15 
  AND tbl_client.client_status = 'Pre ART' 
  AND tbl_appointment.appntmnt_date < CURDATE() 
GROUP BY tbl_appointment.`app_status` ";

        $results = $this->db->query($get_query)->result();
        echo json_encode($results);
    }

    function facility_home() {



        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['filtered_county'] = $this->get_county_filtered_values();
        $data['consented_clients'] = $this->consented_clients();
        $data['counties'] = $this->count_counties();
        $data['sub_counties'] = $this->count_subcounties();
        $data['partners'] = $this->count_partners();
        $data['facilities'] = $this->count_facilities();
        $data['appointments'] = $this->count_appointments();
        $data['messages_sent'] = $this->count_messages();
        //$data['active_appointments'] = $this->count_active_appointments();
        // $data['old_appointments'] = $this->count_old_appointments();
        $data['no_today_appointments'] = $this->count_today_appointments();
        $data['messages_sent'] = $this->count_messages();
        $data['active_appointments'] = $this->count_future_appointments();
        $data['old_appointments'] = $this->count_past_appointments();
        $data['count_today_appointments'] = $this->count_today_appointments();
        $data['honored_appointments'] = $this->count_honored_appointments();
        $data['count_missed_appointments'] = $this->count_missed_appointments();
        $data['count_defaulted_appointments'] = $this->count_defaulted_appointments();
        $data['LTFU_appointments'] = $this->count_LTFU_appointments();





        $data['client_info'] = $this->client_info();




        if ($access_level == "Partner") {

            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id)
            );

            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );



            $query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id ,"
                    . " tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1' order by app_type_1 DESC  ";

            $missed_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "   fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id ,"
                    . " tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id  "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' AND app_status='Missed' AND tbl_appointment.appntmnt_date < CURDATE() and active_app='1' order by appntmnt_date DESC  ";

            $defaulted_query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id ,"
                    . "tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1  "
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' AND app_status='Defaulted' AND tbl_appointment.appntmnt_date < CURDATE() and active_app='1' order by appntmnt_date DESC  ";


            $ltfu_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id , "
                    . " tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' AND app_status='LTFU'  AND tbl_appointment.appntmnt_date < CURDATE() and active_app='1' order by appntmnt_date DESC  ";
        } elseif ($access_level == "County") {


            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'county.id = master_facility.county_id',
                    'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.county_id' => $county_id)
            );


            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id  "
                    . ",tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id'   AND tbl_appointment.appntmnt_date = CURDATE()  and active_app='1' order by appntmnt_date DESC  ";


            $missed_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id , "
                    . " tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id' AND app_status='Defaulted'  AND tbl_appointment.appntmnt_date < CURDATE()  and active_app='1' order by appntmnt_date DESC  ";


            $ltfu_query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id , "
                    . " tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id' and app_status='LTFU'  AND tbl_appointment.appntmnt_date < CURDATE()  and active_app='1' order by appntmnt_date DESC  ";
        } elseif ($access_level == "Sub County") {

                    $facilities = array(
                        'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                        'table' => 'master_facility',
                        'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                            'county' => 'county.id = master_facility.county_id',
                            'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                        'where' => array('partner_facility.status' => 'Active', 'partner_facility.sub_county_id' => $sub_county_id)
                    );

                    $appointments = array(
                        'table' => 'appointment',
                        'join' => array('client' => 'client.id = appointment.client_id'),
                        'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
                    );



                    $query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                            . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                            . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                            . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                            . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                            . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                            . " tbl_appointment.app_type_1,"
                            . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id , "
                            . "tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                            . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                            . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                            . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                            . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                            . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                            . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id'  AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1' order by appntmnt_date DESC  ";

                    $missed_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                            . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                            . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                            . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                            . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                            . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                            . " tbl_appointment.app_type_1,"
                            . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id ,"
                            . " tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                            . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                            . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                            . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                            . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                            . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                            . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id' AND app_status='Missed'  AND tbl_appointment.appntmnt_date < CURDATE() and active_app='1' order by appntmnt_date DESC  ";

                    $defaulted_query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                            . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                            . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                            . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                            . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                            . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                            . " tbl_appointment.app_type_1,"
                            . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id , "
                            . "tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                            . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                            . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                            . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                            . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                            . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                            . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id' AND app_status='Defaulted'  AND tbl_appointment.appntmnt_date < CURDATE() and active_app='1' order by appntmnt_date DESC  ";

                    $ltfu_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                            . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                            . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                            . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                            . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                            . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                            . " tbl_appointment.app_type_1,"
                            . "  fnl_trcing_outocme,no_calls,no_msgs,home_visits,fnl_outcome_dte,tbl_appointment.id as appointment_id ,"
                            . "tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                            . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                            . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                            . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                            . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                            . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                            . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id' and app_status='LTFU' AND tbl_appointment.appntmnt_date < CURDATE() and active_app='1' order by appntmnt_date DESC  ";
        } elseif ($access_level == "Facility") {

            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id)
            );

            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $query = "SELECT * FROM tbl_todays_appointment_query WHERE mfl_code = '$facility_id' ";


            $missed_query = "SELECT * FROM tbl_missed_query WHERE mfl_code = '$facility_id'";


            $defaulted_query = "SELECT * FROM tbl_defaulted_query WHERE mfl_code = '$facility_id'";


            $ltfu_query = "SELECT * FROM tbl_ltfu_query WHERE mfl_code = '$facility_id'";
        } else {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );

            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );






            $query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1, "
                    . "  fnl_trcing_outocme ,tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1' order by appntmnt_date DESC ";



            $missed_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1, "
                    . "  fnl_trcing_outocme ,tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_appointment.appntmnt_date < CURDATE() AND app_status='Missed' and active_app='1' order by appntmnt_date DESC ";



            $defaulted_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . "  tbl_appointment.app_type_1, "
                    . "  fnl_trcing_outocme ,tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_appointment.appntmnt_date < CURDATE() AND app_status='Defaulted' and active_app='1' order by appntmnt_date DESC ";



            $ltfu_query = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . "  tbl_appointment.app_type_1, "
                    . "  fnl_trcing_outocme ,tbl_appointment_types.id as appointment_type_id, tbl_appointment_types.name as appointment_type_name from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id "
                    . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_appointment.appntmnt_date < CURDATE() AND app_status='LTFU' and active_app='1' order by appntmnt_date DESC ";
        }





        $genders = array(
            'table' => 'gender',
            'where' => array('status' => 'Active')
        );



        $groupings = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );


        $time = array(
            'table' => 'time',
            'where' => array('status' => 'Active')
        );

        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );




        $maritals = array(
            'table' => 'marital_status',
            'where' => array('status' => 'Active')
        );

        $app_types = array(
            'table' => 'appointment_types',
            'where' => array('status' => 'Active')
        );






        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['facilities'] = $this->data->commonGet($facilities);
        $data['maritals'] = $this->data->commonGet($maritals);
        $data['app_types'] = $this->data->commonGet($app_types);

        $data['today_appointments'] = $this->db->query($query)->result();
        $data['missed_appointments'] = $this->db->query($missed_query)->result();
        $data['defaulted_appointments'] = $this->db->query($defaulted_query)->result();
        $data['ltfu_appointments'] = $this->db->query($ltfu_query)->result();
        $this->load->vars($data);
        $this->load->template('Reports/facility_home');
        $function_name = $this->uri->segment(2);
    }

    function generate_appointment_diary() {
        $this->load->library('Excel');
        $input_file = getcwd() . '/documents/sys_reports/Defaulter_tracing_register.xlsx';






        try {
            /// it will be your file name that you are posting with a form or can pass static name $_FILES["file"]["name"];
            $objPHPExcel = PHPExcel_IOFactory::load($input_file);

            $inputFileType = PHPExcel_IOFactory::identify($input_file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

            //read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($input_file);

            $get_faciliity = $this->db->query("SELECT 
  tbl_master_facility.`code` AS mfl_code,
  tbl_master_facility.`name` AS facility_name,
  tbl_county.`name` AS county_name,tbl_sub_county.`name` AS sub_county,tbl_county.id as county_id
FROM
  tbl_master_facility 
  INNER JOIN tbl_county 
    ON tbl_county.`id` = tbl_master_facility.`county_id` 
  INNER JOIN tbl_sub_county 
    ON tbl_sub_county.id = tbl_master_facility.`Sub_County_ID` LIMIT 1 ")->result();
            print_r($get_faciliity);
            foreach ($get_faciliity as $value) {
                $county_id = $value->county_id;
                $mfl_code = $value->mfl_code;



                $mfl_code = $value->mfl_code;
                $facility_name = $value->facility_name;
                $county_name = $value->county_name;
                $sub_county_name = $value->sub_county;
                $start_date = date("d-m-Y H:i:s");


                $objPHPExcel->setActiveSheetIndex(0);



                $objPHPExcel->getActiveSheet()->setCellValue('C6', $facility_name);
                $objPHPExcel->getActiveSheet()->setCellValue('C7', $mfl_code);
                $objPHPExcel->getActiveSheet()->setCellValue('C8', $sub_county_name);
                $objPHPExcel->getActiveSheet()->setCellValue('C9', $county_name);
                $objPHPExcel->getActiveSheet()->setCellValue('C10', $start_date);

                $get_client_records_sql = $this->db->query("Select * from vw_defaulter_tracing_details where mfl_code='$mfl_code' and county_id='$county_id' ")->result();
                $i = 1;
                $a = 7;
                $b = 9;
//                foreach ($get_client_records_sql as $value) {
//                    $client_id = $value->client_id;
//                    $client_name = $value->client_name;
//                    $clinic_number = $value->clinic_number;
//                    $gender = $value->gender;
//                    $art_date = $value->art_date;
//                    $age = $value->age;
//                    $enrollment_date = $value->enrollment_date;
//                    $art_cohort_month = $value->ART_COHORT_MONTH;
//                    $dob = $value->dob;
//                    $today = $value->today;
//                    $missed_appointment_date = $value->missed_appointment_date;
//                    $client_phone = $value->client_phone;
//                    $trtmnt_supporter_name = $value->trtmnt_supprtr_name;
//                    $trtmnt_supporter_phone_no = $value->trtmnt_sprtr_phone_no;
//                    $mfl_code = $value->mfl_code;
//                    $county_id = $value->county_id;
//                    $sub_county_id = $value->sub_county_id;
//                     $objPHPExcel->setActiveSheetIndex(0);
//                    $objPHPExcel->getActiveSheet()->setCellValue("A'$a'", $i);
//                    $objPHPExcel->getActiveSheet()->setCellValue("B'$a'", $client_name);
//                    $objPHPExcel->getActiveSheet()->setCellValue("B'$b'", $clinic_number);
//                    $objPHPExcel->getActiveSheet()->setCellValue("C'$a'", $art_date);
//                    $objPHPExcel->getActiveSheet()->setCellValue("C'$b'", $age);
//                    $objPHPExcel->getActiveSheet()->setCellValue("D'$a'", $dob);
//                    $objPHPExcel->getActiveSheet()->setCellValue("D'$b'", $enrollment_date);
//                    $objPHPExcel->getActiveSheet()->setCellValue("E'$a'", $art_cohort_month);
//                    $objPHPExcel->getActiveSheet()->setCellValue("E'$b'", $missed_appointment_date);
//                    $objPHPExcel->getActiveSheet()->setCellValue("F'$a'", $client_phone);
//                    $objPHPExcel->getActiveSheet()->setCellValue("F'$b'", $trtmnt_supporter_name);
//                    $objPHPExcel->getActiveSheet()->setCellValue("G'$a'", $trtmnt_supporter_phone_no);
//                    $objPHPExcel->getActiveSheet()->setCellValue("G'$b'", $clinic_number);
//                    $objPHPExcel->getActiveSheet()->setCellValue("H'$a'", $client_name);
//                    $objPHPExcel->getActiveSheet()->setCellValue("H'$b'", $clinic_number);
//                    $i++;
//                    $b + 4;
//                    $a + 5;
//                }
            }


            //prepare download
            $filename = mt_rand(1, 100000) . '.xls'; //just some random filename
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
            $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

            exit; //done.. exiting!
        } catch (Exception $e) {
            $this->resp->success = FALSE;
            $this->resp->msg = 'Error Uploading file';
            echo json_encode($this->resp);
            exit;
        }
    }

    function dashboard() {
//            $k = new /Ghunti/HighchartsPHP/Highchart;
        $access_level = $this->session->userdata('access_level');
        if ($access_level == 'Facility') {
            redirect("Reports/facility_home", "refresh");
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');





            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $data['output'] = $this->get_access_level();




            $this->load->vars($data);
            $this->load->template('dashboard/geographical');

            //// $this->output->enable_profiler(TRUE);
        }
    }

    function clients_dashboard() {
//            $k = new /Ghunti/HighchartsPHP/Highchart;
        $access_level = $this->session->userdata('access_level');
        if ($access_level == 'Facility') {
            redirect("Reports/facility_home", "refresh");
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');


            $sql = " SELECT * FROM vw_client_summary_report WHERE 1 ";

            if ($access_level == "Partner") {

                $sql .= " AND vw_client_summary_report.partner_id='$partner_id' ";
            } elseif ($access_level == "County") {
                $sql .= " AND vw_client_summary_report.county_id = '$county_id' ";
            } elseif ($access_level == "Sub County") {
                $sql .= " AND vw_client_summary_report.sub_county_id='$sub_county_id' ";
            } elseif ($access_level == "Facility") {
                $sql .= " AND vw_client_summary_report.mfl_code = '$facility_id' ";
            } else {
                
            }



            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $data['output'] = $this->get_access_level();




            $this->load->vars($data);
            $this->load->template('dashboard/clients');
            $function_name = $this->uri->segment(2);

            //// $this->output->enable_profiler(TRUE);
        }
    }

    function appointments_dashboard() {
//            $k = new /Ghunti/HighchartsPHP/Highchart;
$access_level = $this->session->userdata('access_level');
if ($access_level == 'Facility') {
    redirect("Reports/facility_home", "refresh");
} else {
    $partner_id = $this->session->userdata('partner_id');
    $county_id = $this->session->userdata('county_id');
    $sub_county_id = $this->session->userdata('subcounty_id');
    $facility_id = $this->session->userdata('facility_id');
    $access_level = $this->session->userdata('access_level');


    $sql = " SELECT * FROM vw_client_summary_report WHERE 1 ";

    if ($access_level == "Partner") {

        $sql .= " AND vw_client_summary_report.partner_id='$partner_id' ";
    } elseif ($access_level == "County") {
        $sql .= " AND vw_client_summary_report.county_id = '$county_id' ";
    } elseif ($access_level == "Sub County") {
        $sql .= " AND vw_client_summary_report.sub_county_id='$sub_county_id' ";
    } elseif ($access_level == "Facility") {
        $sql .= " AND vw_client_summary_report.mfl_code = '$facility_id' ";
    } else {
        
    }



    $data['side_functions'] = $this->data->get_side_modules();
    $data['top_functions'] = $this->data->get_top_modules();
    $data['output'] = $this->get_access_level();




    $this->load->vars($data);
    $this->load->template('dashboard/appointments');
    $function_name = $this->uri->segment(2);

    //// $this->output->enable_profiler(TRUE);
}
    }

    function messages_dashboard() {
//            $k = new /Ghunti/HighchartsPHP/Highchart;
        $access_level = $this->session->userdata('access_level');
        if ($access_level == 'Facility') {
            redirect("Reports/facility_home", "refresh");
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');


            $sql = " SELECT * FROM vw_client_summary_report WHERE 1 ";

            if ($access_level == "Partner") {

                $sql .= " AND vw_client_summary_report.partner_id='$partner_id' ";
            } elseif ($access_level == "County") {
                $sql .= " AND vw_client_summary_report.county_id = '$county_id' ";
            } elseif ($access_level == "Sub County") {
                $sql .= " AND vw_client_summary_report.sub_county_id='$sub_county_id' ";
            } elseif ($access_level == "Facility") {
                $sql .= " AND vw_client_summary_report.mfl_code = '$facility_id' ";
            } else {
                
            }



            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $data['output'] = $this->get_access_level();




            $this->load->vars($data);
            $this->load->template('dashboard/messages');
            $function_name = $this->uri->segment(2);

            //// $this->output->enable_profiler(TRUE);
        }
    }

    function marital_dashboard() {
        //            $k = new /Ghunti/HighchartsPHP/Highchart;
        $access_level = $this->session->userdata('access_level');
        if ($access_level == 'Facility') {
            redirect("Reports/facility_home", "refresh");
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');


            $sql = " SELECT * FROM vw_client_summary_report WHERE 1 ";

            if ($access_level == "Partner") {

                $sql .= " AND vw_client_summary_report.partner_id='$partner_id' ";
            } elseif ($access_level == "County") {
                $sql .= " AND vw_client_summary_report.county_id = '$county_id' ";
            } elseif ($access_level == "Sub County") {
                $sql .= " AND vw_client_summary_report.sub_county_id='$sub_county_id' ";
            } elseif ($access_level == "Facility") {
                $sql .= " AND vw_client_summary_report.mfl_code = '$facility_id' ";
            } else {
                
            }



            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $data['output'] = $this->get_access_level();




            $this->load->vars($data);
            $this->load->template('dashboard/report1');
            $function_name = $this->uri->segment(2);

            //// $this->output->enable_profiler(TRUE);
        }
    }

    function app_message_dashboard() {

        $access_level = $this->session->userdata('access_level');
        if ($access_level == 'Facility') {
            redirect("Reports/facility_home", "refresh");
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');


            $sql = " SELECT * FROM vw_client_summary_report WHERE 1 ";

            if ($access_level == "Partner") {

                $sql .= " AND vw_client_summary_report.partner_id='$partner_id' ";
            } elseif ($access_level == "County") {
                $sql .= " AND vw_client_summary_report.county_id = '$county_id' ";
            } elseif ($access_level == "Sub County") {
                $sql .= " AND vw_client_summary_report.sub_county_id='$sub_county_id' ";
            } elseif ($access_level == "Facility") {
                $sql .= " AND vw_client_summary_report.mfl_code = '$facility_id' ";
            } else {
                
            }



            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $data['output'] = $this->get_access_level();




            $this->load->vars($data);
            $this->load->template('dashboard/report2');
            $function_name = $this->uri->segment(2);
        }
    }

    function clients_extract() {

        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        
        $data['access_level'] = $access_level;
        $data['partner_id'] = $partner_id;
        $data['facility_id'] = $facility_id;
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['filtered_partner'] = $this->get_partner_filters();
        $data['filtered_county'] = $this->get_county_filtered_values();


        $this->load->vars($data);
        $this->load->template('extract/client');
    }

    function reporting_time() {
        $query = $this->db->query("select DISTINCT time from `Monthly_Appointment_Summary` group by time")->result();

        return $query;
    }

    function monthly_appointment_report() {

        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['access_level'] = $access_level;
        $data['partner_id'] = $partner_id;
        $data['facility_id'] = $facility_id;
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['filtered_partner'] = $this->get_partner_filters();
        $data['filtered_county'] = $this->get_county_filtered_values();
        $data['filtered_time'] = $this->reporting_time(); 


        $this->load->vars($data);
        $this->load->template('Reports/monthly_appointment_report');
    }

    function get_monthly_appointment_report() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $filter_time = $this->input->post('filter_time');
        $partner_id = $this->input->post('partner', TRUE);



        $this->db->select('*');
        $this->db->from('Monthly_Appointment_Summary');

        $query = "Select * from Monthly_Appointment_Summary where 1 ";
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):

            $query .= " and partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):

            $query .= " and mfl_code = '$facility_id' ";
        endif;

        if (!empty($partner_id)) {


            $query .= " and partner_id = '$partner_id' ";
        }

        if (!empty($county_id)) {


            $query .= " and county_id = '$county_id' ";
        }
        if (!empty($sub_county_id)) {


            $query .= " and sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $query .= " and mfl_code = '$mfl_code' ";
        }

        if (!empty($filter_time)) {

            $query .= " and time LIKE '%$filter_time%' ";
        }




        $get_query = $this->db->query($query)->result_array();

        echo json_encode($get_query);
    }

    function get_client_reports() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);

        if (!empty($date_from)):
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('*');
        $this->db->from('client_report ');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('created_at <=', $formated_date_to);
        }

        $this->db->group_by("clinic_number"); // Produces: GROUP BY Gender
        $query = $this->db->get();
        if ($query->num_rows() < 2500) {
            $get_query = $query->result_array();

            echo json_encode($get_query);
        } else {
            $filename = "Client Report";




            $this->load->library("excel");
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("Clinic Number", "MFL Code", "Facility", "Gender",
                "Group Name", "Marital", "Partner", "Created At", "Month Year", "Language",
                "TXT Time", "County", "Sub County", "Status");



            $column = 0;

            foreach ($table_columns as $field) {
                $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }

            $results_data = $query->result();

            $excel_row = 2;

            foreach ($results_data as $row) {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->clinic_number);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->mfl_code);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->facility_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->gender);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->group_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->marital);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->partner_name);
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->created_at);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->month_year);
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->LANGUAGE);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->txt_time);
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->month_year);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->sub_county);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->created_at);
                $excel_row++;
            }


            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
            //header('Content-Type: application/vnd.ms-excel');
            //header('Content-Disposition: attachment;filename="Client Report.xlsx"');
            $a = $filename . date("Y-m-d H:i:s ") . '.xlsx';
            $object_writer->save(__DIR__ . '/ExtractReport/' . $a);
            $file_location = __DIR__ . '/ExtractReport/' . $a;

            $email = $this->session->userdata('email');
            $full_name = $this->session->userdata('Fullname');
            $subject = "Client Report Extract";
            $msg = "<h4> Dear  $full_name , </h4> </br> ";
            $msg .= "<p> Please find attached Client report from the  system as per your request </p> <br>";
            $msg .= "Kind Regrards, <br>";
            $msg .= "Ushauri Support Team.  ";


            $this->send_mail($full_name, $email, $subject, $file_location, $msg);

            $info_msg = "Too much data";
            echo json_encode($info_msg);
        }
    }

    function send_mail($full_name = null, $email = null, $subject = null, $file_location = null, $msg = null) {
        $attachment = $file_location;
        $to = $email;
        $bcc = "";
        $cc = "";

        $send_email = $this->send_email($to, $msg, $cc, $bcc, $subject, $attachment);
    }

    function appointments_extract() {

        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['filtered_partner'] = $this->get_partner_filters();
        $data['filtered_county'] = $this->get_county_filtered_values();


        $this->load->vars($data);
        $this->load->template('extract/appointment');
    }

    function get_appointment_reports() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->input->post('partner', TRUE);

        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);

        if (!empty($date_from)):
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('*');
        $this->db->from('client_appointment_report ');


        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($partner_id)) {
            $this->db->where('partner_id', $partner_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('appointment_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('appointment_date <=', $formated_date_to);
        }

        $this->db->group_by("clinic_number"); // Produces: GROUP BY Gender
        $query = $this->db->get();



        if ($query->num_rows() <= 2500) {


            $get_query = $query->result_array();

            echo json_encode($get_query);
        } else {

            $info_msg = "Too much data";
            echo json_encode($info_msg);

            $this->email_appointmnt_report($query);
        }
    }

    function email_appointmnt_report($query) {
        $filename = "Client Appointment Report";


        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array("Clinic Number", "Gender", "Group Name", "Marital",
            "Appointment Name", "Appointment Month Year", "Appointment Status", "Created At", "Partner Name ", "County",
            "Sub County", "MFL Code", "Facility ");



        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $results_data = $query->result();

        $excel_row = 2;

        foreach ($results_data as $row) {
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->clinic_number);
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->gender);
            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->group_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->marital);
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->appointment_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->month_year);
            $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->appp_status);
            $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->created_at);
            $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->partner_name);
            $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->county);
            $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->sub_county);
            $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->mfl_code);
            $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->facility_name);
            $excel_row++;
        }


        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
        //header('Content-Type: application/vnd.ms-excel');
        //header('Content-Disposition: attachment;filename="Client Report.xlsx"');
        $a = $filename . date("Y-m-d H:i:s ") . '.xlsx';
        $object_writer->save(__DIR__ . '/ExtractReport/' . $a);
        $file_location = __DIR__ . '/ExtractReport/' . $a;

        $email = $this->session->userdata('email');
        $full_name = $this->session->userdata('Fullname');
        $subject = "Client Appointment Report Extract";
        $msg = "<h4> Dear  $full_name , </h4> </br> ";
        $msg .= "<p> Please find attached Client Appointment report from the  system as per your request </p> <br>";
        $msg .= "Kind Regrards, <br>";
        $msg .= "Ushauri Support Team.  ";


        $this->send_mail($full_name, $email, $subject, $file_location, $msg);
    }

    function messages_extract() {

        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['access_level'] = $access_level;
        $data['partner_id'] = $partner_id;
        $data['facility_id'] = $facility_id;
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['filtered_partner'] = $this->get_partner_filters();
        $data['filtered_county'] = $this->get_county_filtered_values();


        $this->load->vars($data);
        $this->load->template('extract/message');
    }

    function get_message_reports() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', TRUE);
        $sub_county_id = $this->input->post('sub_county', TRUE);
        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);

        if (!empty($date_from)):
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)):
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('*');
        $this->db->from('client_message_report ');
        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $this->db->where('partner_id', $partner_id);
        endif;


        if ($access_level == "Facility"):
            $this->db->where('mfl_code', $facility_id);
        endif;
        if (!empty($county_id)) {
            $this->db->where('county_id', $county_id);
        }
        if (!empty($sub_county_id)) {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }

        if (!empty($date_from)) {
            $this->db->where('created_at >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('created_at <=', $formated_date_to);
        }

        $this->db->group_by("clinic_number"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    function lab_investigation() {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');



        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $mfl_code = $this->input->post('facility', TRUE);
        $date_from = $this->input->post('date_from', TRUE);
        $date_to = $this->input->post('date_to', TRUE);


        if ($access_level == 'County') {
            $county_id = $this->session->userdata('county_id');
        } else {
            $county_id = $this->input->post('county', TRUE);
        }

        if ($access_level == 'Sub County') {
            $sub_county_id = $this->session->userdata('subcounty_id');
        } else {
            $sub_county_id = $this->input->post('sub_county', TRUE);
        }


        if (!empty($date_from)) {
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        }

        if (!empty($date_to)) {
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        }
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();


        $lab_investigation = " select partner_id , partner_name , county_id,county_name,sub_county_id,sub_county_name,mfl_code ,facility_name , age_group ,gender, CASE 
  WHEN age_group = '< 1' THEN COUNT( lab_investigation)
   WHEN age_group = '1-9' THEN COUNT( lab_investigation)
   WHEN age_group = '10-14' THEN COUNT( lab_investigation)
   WHEN age_group = '15-19' THEN COUNT( lab_investigation)
   WHEN age_group = '20-24' THEN COUNT( lab_investigation)
   WHEN age_group = '25-29' THEN COUNT( lab_investigation)
   WHEN age_group = '30-34' THEN COUNT( lab_investigation)
   WHEN age_group = '35-39' THEN COUNT( lab_investigation)
   WHEN age_group = '40-49' THEN COUNT( lab_investigation)
   WHEN age_group = '50+'  THEN COUNT( lab_investigation)
    ELSE count( lab_investigation) END AS `no_lab_investigation`  from vw_lab_investigation WHERE 1 ";

        if ($access_level === "Admin"):

        endif;

        if ($access_level == "Partner"):
            $lab_investigation .= " AND partner_id = '$partner_id' ";
        endif;


        if ($access_level == "Facility"):
            $lab_investigation .= " AND mfl_code = '$facility_id' ";
        endif;

        if (!empty($county_id)) {
            $lab_investigation .= " AND county_id = '$county_id' ";
        }


        if (!empty($sub_county_id)) {
            $lab_investigation .= " AND sub_county_id = '$sub_county_id' ";
        }


        if (!empty($mfl_code)) {
            $lab_investigation .= " AND mfl_code = '$mfl_code' ";
        }



        if (!empty($formated_date_from)) {
            $lab_investigation .= " AND created_at >= '$formated_date_from' ";
        }


        if (!empty($formated_date_to)) {
            $lab_investigation .= " AND created_at <= '$formated_date_to' ";
        }



        $lab_investigation .= " group by facility_name,`age_group` order by facility_name,age_group,gender ";



        $get_query = $this->db->query($lab_investigation)->result();

        $data['output'] = $get_query;

        $this->load->vars($data);
        $function_name = $this->uri->segment(2);





        $check_auth = $this->check_authorization($function_name);
        if ($check_auth) {
            $this->load->template('Reports/lab_investigation');
        } else {
            $this->load->template('Reports/lab_investigation');

// echo 'Unauthorised Access';
//exit();
        }
    }

}
