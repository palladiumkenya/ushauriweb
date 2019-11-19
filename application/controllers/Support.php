<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Support extends MY_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();

        $this->load->library('session');
        $this->check_access();

        $this->data = new DBCentral();
    }

    function index() {
        redirect("Reports/dashboard", "refresh");
    }

    function check_access() {
        $logged_in = $this->session->userdata("logged_in");

        if ($logged_in) {
            
        } else {
            redirect("Login", "refresh");
        }
    }

    function get_facility_details() {
        $mfl_code = $this->uri->segment(3);
        $options = array(
            'table' => 'master_facility',
            'where' => array('code' => $mfl_code));
        $facility_data = $this->data->commonGet($options);

        echo json_encode($facility_data);
    }

    public function search_master_facility() {

        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->operations_model->get_active_clients($q);
        }
    }

    
    #INCOMING MESSSAGES STARTS FROM HERE 
    function search_incoming() {

        $search_value = $this->uri->segment(3);
        $returned_value = $this->data->search_incoming($search_value);


        if (empty($returned_value)) {
            echo json_encode($returned_value);
        } else {
            echo json_encode($returned_value);
        }
    }


    function incoming() {
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                
            } else {
//                
            }
        }
        $options = array(
            'select' => 'master_facility.name , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type',
            'table' => 'master_facility',
            'join' => array('county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
            'where' => array('assigned' => '0', 'county.status' => 'Active', 'sub_county.status' => 'Active'));
        $partner_id = $this->session->userdata('partner_id');

        if ($partner_id == 0) {
            $partner = array(
                'table' => 'partner',
                'where' => array('status' => 'Active')
            );
        } else {
            $partner = array(
                'table' => 'partner',
                'where' => array('status' => 'Active', 'id' => $partner_id)
            );
        }


        $data['facilities'] = $this->data->commonGet($options);
        $data['partners'] = $this->data->commonGet($partner);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('support/incoming');
            } else {
                $this->load->template('support/incoming');
//                echo 'Unauthorised Access';
//                exit();
            }
        }
    }
    
    #INCOMING MESSAGES ENDS HERE 
    
    
    #OUTGOING MESSAGE STARTS HERE
    function search_outgoing() {

        $search_value = $this->uri->segment(3);
        $returned_value = $this->data->search_outgoing($search_value);


        if (empty($returned_value)) {
            echo json_encode($returned_value);
        } else {
            echo json_encode($returned_value);
        }
    }

    
    function outgoing() {
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                
            } else {
//                
            }
        }
        $options = array(
            'select' => 'master_facility.name , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type',
            'table' => 'master_facility',
            'join' => array('county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
            'where' => array('assigned' => '0', 'county.status' => 'Active', 'sub_county.status' => 'Active'));
        $partner_id = $this->session->userdata('partner_id');

        if ($partner_id == 0) {
            $partner = array(
                'table' => 'partner',
                'where' => array('status' => 'Active')
            );
        } else {
            $partner = array(
                'table' => 'partner',
                'where' => array('status' => 'Active', 'id' => $partner_id)
            );
        }


        $data['facilities'] = $this->data->commonGet($options);
        $data['partners'] = $this->data->commonGet($partner);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('support/outgoing');
            } else {
                $this->load->template('support/outgoing');
//                echo 'Unauthorised Access';
//                exit();
            }
        }
    }
    #OUTGOING MESSAGES ENDS HERE
    #
    #
    #
    #AUDIT TRAIL MESSAGE STARTS HERE
    function search_audit_trail() {

        $search_value = $this->uri->segment(3);
        $returned_value = $this->data->search_audit_trail($search_value);


        if (empty($returned_value)) {
            echo json_encode($returned_value);
        } else {
            echo json_encode($returned_value);
        }
    }

    
    function audit_trails() {
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                
            } else {
//                
            }
        }
        $options = array(
            'select' => 'master_facility.name , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type',
            'table' => 'master_facility',
            'join' => array('county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
            'where' => array('assigned' => '0', 'county.status' => 'Active', 'sub_county.status' => 'Active'));
        $partner_id = $this->session->userdata('partner_id');

        if ($partner_id == 0) {
            $partner = array(
                'table' => 'partner',
                'where' => array('status' => 'Active')
            );
        } else {
            $partner = array(
                'table' => 'partner',
                'where' => array('status' => 'Active', 'id' => $partner_id)
            );
        }


        $data['facilities'] = $this->data->commonGet($options);
        $data['partners'] = $this->data->commonGet($partner);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
            
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('support/audit_trail');
            } else {
                $this->load->template('support/audit_trail');
//                echo 'Unauthorised Access';
//                exit();
            }
        }
    }
    #OUTGOING MESSAGES ENDS HERE
 
    //FACILITIES MODULE ENDS HERE 


    function generate_token() {

        $csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        ?>

        <input type="text" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
        <?php
    }

}
