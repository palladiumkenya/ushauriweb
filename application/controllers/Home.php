<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Home extends MY_Controller
{
    public $data = '';

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        $this->check_access();
        $this->data = new DBCentral();
    }

    public function validation()
    {
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $this->load->vars($data);
        $data['output'] = $this->get_access_level();
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/validation');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function index()
    {
        redirect("Reports/dashboard", "refresh");
    }

    public function filter_highcharts_dashboard()
    {
        $partner_id = $this->input->post('partner', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);

        $bar_clients_data = $this->data->getAggregateBarClientsData($partner_id, $county_id, $sub_county_id, $mfl_code);
        $bar_appointmens_data = $this->data->getAggregateBarAppointmentsData($partner_id, $county_id, $sub_county_id, $mfl_code);
        $records = $this->data->getAggregateReports($partner_id, $county_id, $sub_county_id, $mfl_code);
        
        $target_active_clients = 0;
        $total_clients = 0;
        $consented_clients = 0;
        $future_appointments = 0;

        foreach ($records as $record) {
            $target_active_clients =  $target_active_clients + $record['Target_Clients'];
            $total_clients = $total_clients + $record['Clients'];
            $consented_clients = $consented_clients + $record['Consented'];
            $future_appointments = $future_appointments + $record['Future_Appointments'];
        }
        if ($target_active_clients < 1) {
            $target_active_clients = 1;
        }
        $data['data'] = $records;
        $data['bar_clients_data'] = $bar_clients_data;
        $data['bar_appointments_data'] = $bar_appointmens_data;
        $data['target_active_clients'] = $target_active_clients;
        $data['total_clients'] = $total_clients;
        $data['percentage_uptake'] = round((($total_clients / $target_active_clients) * 100), 1);
        $data['consented_clients'] = $consented_clients;
        $data['future_appointments'] = $future_appointments;
        $data['facilities'] = count($records);

        echo json_encode($data);
    }

    public function highchartDashboard()
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');

        $partner_id = $this->input->post('partner', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);

        $bar_clients_data = $this->data->getAggregateBarClientsData($partner_id, $county_id, $sub_county_id, $mfl_code);
        $bar_appointmens_data = $this->data->getAggregateBarAppointmentsData($partner_id, $county_id, $sub_county_id, $mfl_code);
      

        $records = $this->data->getAggregateReports($partner_id, $county_id, $sub_county_id, $mfl_code);

        $target_active_clients = 0;
        $total_clients = 0;
        $consented_clients = 0;
        $future_appointments = 0;

        foreach ($records as $record) {
            $target_active_clients =  $target_active_clients + $record['Target_Clients'];
            $total_clients = $total_clients + $record['Clients'];
            $consented_clients = $consented_clients + $record['Consented'];
            $future_appointments = $future_appointments + $record['Future_Appointments'];
            ;
        }
        $data['data'] = $records;
        $data['bar_clients_data'] = $bar_clients_data;
        $data['bar_appointments_data'] = $bar_appointmens_data;
        $data['target_active_clients'] = $target_active_clients;
        $data['total_clients'] = $total_clients;
        $data['percentage_uptake'] = round((($total_clients / $target_active_clients) * 100), 1);
        $data['consented_clients'] = $consented_clients;
        $data['future_appointments'] = $future_appointments;
        $data['facilities'] = count($records);
        $data['access_level'] = $access_level;
        $data['partner_id'] = $partner_id;
        $data['facility_id'] = $facility_id;
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['filtered_partner'] = $this->get_partner_filters();
        $data['filtered_county'] = $this->get_county_filtered_values();
        $this->load->vars($data);
        $this->load->template('Home/highcharts_dashboard');
    }
    public function tableDashboard()
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');

        $partner_id = $this->input->post('partner', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);

        $table_records = $this->data->getAggregateTableData($partner_id, $county_id, $sub_county_id, $mfl_code);
        $marriage_records = $this->data->getAggregateMarriageData($partner_id, $county_id, $sub_county_id, $mfl_code);


        $data['data'] = $table_records;
        $data['marriage_records'] = $marriage_records;
        $data['access_level'] = $access_level;
        $data['partner_id'] = $partner_id;
        $data['facility_id'] = $facility_id;
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['filtered_partner'] = $this->get_partner_filters();
        $data['filtered_county'] = $this->get_county_filtered_values();
        $this->load->vars($data);
        //echo json_encode($data);
        $this->load->template('Home/tablechart');
    }

    public function jsondata()
    {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        if ($access_level == 'Facility') {
            $client_options = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.mfl_code' => $facility_id, 'client.status' => 'active', 'client.clinic_id' => $clinic_id)
            );
        } elseif ($access_level == 'Partner') {
            $client_options = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender', 'partner_facility' => 'client.mfl_code = partner_facility.mfl_code'),
                'where' => array('partner_facility.partner_id' => $partner_id, 'client.status' => 'Active')
            );
        } elseif ($access_level == 'County') {
            $client_options = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender', 'partner_facility' => 'client.mfl_code = partner_facility.mfl_code'),
                'where' => array('partner_facility.county_id' => $county_id, 'client.status' => 'Active')
            );
        } elseif ($access_level == 'Sub County') {
            $client_options = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender', 'partner_facility' => 'client.mfl_code = partner_facility.mfl_code'),
                'where' => array('partner_facility.sub_county_id' => $sub_county_id, 'client.status' => 'Active')
            );
        } else {
            $client_options = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.status' => 'Active')
            );
        }




        $output = $this->data->commonGet($client_options);

        echo json_encode($output);
    }

    public function check_access()
    {
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

    public function dashboard_results()
    {
        //header('Content-Type: application/json');
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $access_level = $this->session->userdata('access_level');
        if ($access_level == 'Facility') {
            $query = $this->db->query("SELECT app_status as label  FROM tbl_appointment "
                . " INNER JOIN tbl_notification_flow ON tbl_notification_flow.`notification_type` = tbl_appointment.`app_status` "
                . "  INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` "
                . " INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` "
                . " WHERE app_status IN (SELECT notification_type FROM tbl_notification_flow) AND tbl_partner_facility.mfl_code='$facility_id' AND appntmnt_date > '1970-01-01' and tbl_client.status='Active' ")->result_array();
        } elseif ($access_level == 'Partner') {
            $query = $this->db->query("SELECT app_status as label  FROM tbl_appointment "
                . " INNER JOIN tbl_notification_flow ON tbl_notification_flow.`notification_type` = tbl_appointment.`app_status` "
                . " INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` "
                . " INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` "
                . " WHERE app_status IN (SELECT notification_type FROM tbl_notification_flow) AND tbl_partner_facility.partner_id='$partner_id' AND appntmnt_date > '1970-01-01' and tbl_client.status ='Active' ")->result_array();
        } elseif ($access_level == 'County') {
            $query = $this->db->query("SELECT app_status as label  FROM tbl_appointment "
                . " INNER JOIN tbl_notification_flow ON tbl_notification_flow.`notification_type` = tbl_appointment.`app_status` "
                . " INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` "
                . " INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` "
                . " WHERE app_status IN (SELECT notification_type FROM tbl_notification_flow) AND tbl_partner_facility.county_id='$county_id' AND appntmnt_date > '1970-01-01' and tbl_client.status='Active' ")->result_array();
        } elseif ($access_level == 'Sub County') {
            $query = $this->db->query("SELECT app_status as label  FROM tbl_appointment "
                . " INNER JOIN tbl_notification_flow ON tbl_notification_flow.`notification_type` = tbl_appointment.`app_status` "
                . " INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` "
                . " INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` "
                . " WHERE app_status IN (SELECT notification_type FROM tbl_notification_flow) AND tbl_partner_facility.sub_county_id='$sub_county_id' AND appntmnt_date > '1970-01-01' and tbl_client.status='Active' ")->result_array();
        } else {
            $query = $this->db->query("SELECT app_status as label  FROM tbl_appointment "
                . " INNER JOIN tbl_notification_flow ON tbl_notification_flow.`notification_type` = tbl_appointment.`app_status` "
                . " INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.`client_id` "
                . " INNER JOIN tbl_partner_facility ON tbl_partner_facility.`mfl_code` = tbl_client.`mfl_code` "
                . " WHERE app_status IN (SELECT notification_type FROM tbl_notification_flow) AND appntmnt_date > '1970-01-01' and tbl_client.status='Active' ")->result_array();
            //$query = $this->db->query("SELECT app_status as label FROM `tbl_appointment`")->result();
        }


        echo json_encode($query);
    }

    public function get_transfer_mfl_no()
    {
        $mfl_no = $this->uri->segment(3);
        $mfl_info = array(
            'table' => 'master_facility',
            'where' => array('code' => $mfl_no)
        );
        $get_data = $this->data->commonGet($mfl_info);
        echo json_encode($get_data);
    }

    public function clients()
    {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $subcounty_id = $this->session->userdata('subcounty_id');
        $access_level = $this->session->userdata('access_level');

        $clinic_id = $this->session->userdata('clinic_id');


        if ($access_level == "Donor") { //Donor level access
            $clients = array(
                'select' => 'client.file_no , groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number,national_id,file_no,client.client_status ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.status' => 'Active'),
                'order' => array('enrollment_date' => 'DESC')
            );


            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );
        } elseif ($access_level == "Partner") { //Partner level access

            $clients = array(
                'select' => '*',
                'table' => 'tbl_client_raw_report',
                'where' => array('partner_id' => $partner_id),
                'order' => array('enrollment_date' => 'DESC')
            );

            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );
        } elseif ($access_level == "County") { //County level access
            $clients = array(
                'select' => 'client.file_no ,groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number,national_id,file_no,client.client_status,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'gender' => 'gender.id = client.gender',
                    'partner_facility' => 'client.mfl_code = partner_facility.mfl_code'
                ),
                'where' => array(
                    'client.status' => 'Active',
                    'partner_facility.county_id' => $county_id
                ),
                'order' => array('enrollment_date' => 'DESC')
            );


            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array(
                    'partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'county.id = master_facility.county_id',
                    'sub_county' => 'sub_county.id = master_facility.sub_county_id'
                ),
                'where' => array('partner_facility.status' => 'Active', 'master_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") { //Sub County level access
            $clients = array(
                'select' => 'client.file_no ,groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number,national_id,file_no,client.client_status  ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'gender' => 'gender.id = client.gender',
                    'partner_facility' => 'client.mfl_code = partner_facility.mfl_code'
                ),
                'where' => array(
                    'client.status' => 'Active',
                    'partner_facility.sub_county_id' => $subcounty_id
                ),
                'order' => array('enrollment_date' => 'DESC')
            );


            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array(
                    'partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'county.id = master_facility.county_id',
                    'sub_county' => 'sub_county.id = master_facility.sub_county_id'
                ),
                'where' => array('partner_facility.status' => 'Active', 'sub_county.id' => $subcounty_id)
            );
        } elseif ($access_level == "Facility") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );

            $clients = array(
                'select' => '*',
                'table' => 'tbl_client_raw_report',
                'where' => array('mfl_code' => $facility_id),
                'order' => array('enrollment_date' => 'DESC')
            );
        } else {
            $clients = array(
                'select' => '*',
                'table' => 'tbl_client_raw_report',
                'order' => array('enrollment_date' => 'DESC')
            );

            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );
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
        $appointment_types = array(
            'table' => 'appointment_types',
            'where' => array('status' => 'Active')
        );





        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['clients'] = $this->data->commonGet($clients);
        $data['appointment_types'] = $this->data->commonGet($appointment_types);
        $data['facilities'] = $this->data->commonGet($facilities);
        $data['maritals'] = $this->data->commonGet($maritals);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);


        $function_name = $this->uri->segment(2);
        //// $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/clients');
            } else {
                $this->load->template('Home/clients');
                //                echo 'Unauthorised Access';
                //                exit();
            }
        }
    }

    public function deactivated()
    {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $subcounty_id = $this->session->userdata('subcounty_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');


        if ($access_level == "Donor") { //Donor level access
            $clients = array(
                'select' => ' client.file_no ,  groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,'
                    . ' client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name, '
                    . ' gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id , county.name as county_name, sub_county.name as sub_county ,master_facility.name as facility_name',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'gender' => 'gender.id = client.gender',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'county' => 'county.id = partner_facility.county_id', 'sub_county' => 'sub_county.id = partner_facility.sub_county_id', 'master_facility' => 'master_facility.code = partner_facility.mfl_code'
                ),
                'where' => array('client.status' => 'Deceased')
            );
        } elseif ($access_level == "Partner") {
            //Partner level access

            $clients = "SELECT a.id, a.f_name, a.m_name, a.l_name, a.clinic_number, a.file_no, a.status AS ST, d.name AS county_name, e.name AS sub_county, f.name AS facility_name , a.phone_no, a.dob, b.name, a.created_at FROM tbl_client a INNER JOIN tbl_groups b ON b.id = a.group_id INNER JOIN tbl_partner_facility c ON c.mfl_code = a.mfl_code INNER JOIN tbl_county d ON d.id = c.county_id INNER JOIN tbl_sub_county e ON e.id = c.sub_county_id INNER JOIN tbl_master_facility f ON f.`code` = c.mfl_code
            WHERE a.partner_id = '$partner_id' AND a.status =  'Deceased' OR a.status= 'Dead' AND a.partner_id = '$partner_id'";
        } elseif ($access_level == "Facility") {
            $clients = "SELECT a.id, a.f_name, a.m_name, a.l_name, a.clinic_number, a.file_no, a.status AS ST, a.phone_no, a.dob, b.name, a.created_at FROM tbl_client a INNER JOIN tbl_groups b ON b.id = a.group_id WHERE a.mfl_code = '$facility_id' AND a.status =  'Deceased' OR a.status= 'Dead' AND a.mfl_code = '$facility_id'";
        } elseif ($access_level == "County") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id, 'partner_facility.county_id' => $county_id)
            );

            $clients = array(
                'select' => ' client.file_no ,  groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,'
                    . ' client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name, '
                    . ' gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id , county.name as county_name, sub_county.name as sub_county ,master_Facility.name as facility_name',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'gender' => 'gender.id = client.gender',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'county' => 'county.id = partner_facility.county_id', 'sub_county' => 'sub_county.id = partner_facility.sub_county_id', 'master_facility' => 'master_facility.code = partner_facility.mfl_code'
                ),
                'where' => array(
                    'client.status' => 'Disabled',
                    'partner_facility.county_id' => $county_id,
                    'client.partner_id' => $partner_id
                )
            );
        } elseif ($access_level == "Sub County") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array(
                    'partner_facility.status' => 'Active',
                    'partner_facility.partner_id' => $partner_id,
                    'partner_facility.sub_county_id' => $subcounty_id
                )
            );

            $clients = array(
                'select' => ' client.file_no ,  groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,'
                    . ' client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name, '
                    . ' gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id , county.name as county_name, sub_county.name as sub_county ,master_Facility.name as facility_name',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'gender' => 'gender.id = client.gender',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'county' => 'county.id = partner_facility.county_id', 'sub_county' => 'sub_county.id = partner_facility.sub_county_id', 'master_facility' => 'master_facility.code = partner_facility.mfl_code'
                ),
                'where' => array(
                    'client.status' => 'Disabled',
                    'partner_facility.sub_county_id' => $subcounty_id,
                    'client.partner_id' => $partner_id
                )
            );
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

        $facilities = array(
            'table' => 'master_facility',
            'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
            'where' => array('partner_facility.status' => 'Active')
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
        // $data['clients'] = $this->data->commonGet($clients);
        $data['clients'] = $this->db->query($clients)->result();
        $data['facilities'] = $this->data->commonGet($facilities);
        $data['maritals'] = $this->data->commonGet($maritals);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        // $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/deactivated');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function deceased()
    {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');

        if ($access_level == "Donor") { //Donor level access
            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.status' => 'Deceased')
            );
        } elseif ($access_level == "Partner") { //Partner level access
            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.status' => 'Deceased', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id, 'partner_facility.mfl_code' => $facility_id)
            );

            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.status' => 'Deceased', 'client.mfl_code' => $facility_id, 'client.partner_id' => $partner_id, 'client.clinic_id' => $clinic_id)
            );
        } elseif ($access_level == "County") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array(
                    'partner_facility.status' => 'Active',
                    'partner_facility.partner_id' => $partner_id,
                    'partner_facility.mfl_code' => $facility_id
                )
            );

            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'gender' => 'gender.id = client.gender',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code '
                ),
                'where' => array(
                    'client.status' => 'Deceased',
                    'client.mfl_code' => $facility_id,
                    'partner_facility.county_id' => $county_id
                )
            );
        } elseif ($access_level == "Sub County") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id, 'partner_facility.mfl_code' => $facility_id)
            );

            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'gender' => 'gender.id = client.gender',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array(
                    'client.status' => 'Deceased', 'client.mfl_code' => $facility_id,
                    'partner_facility.sub_county_id' => $sub_county_id
                )
            );
        } else {
            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.status' => 'Deceased')
            );
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

        $facilities = array(
            'table' => 'master_facility',
            'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
            'where' => array('partner_facility.status' => 'Active')
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
        $data['clients'] = $this->data->commonGet($clients);
        $data['facilities'] = $this->data->commonGet($facilities);
        $data['maritals'] = $this->data->commonGet($maritals);
        $data['output'] = $this->get_access_level();

        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/deceased');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function get_client_transfer_data()
    {
        $client_id = $this->uri->segment(3);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');



        $client_details = array(
            'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                . 'client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable,client.marital,client.gender'
                . ',wellness_enable,motivational_enable,client.facility_id',
            'table' => 'client',
            'join' => array('language' => 'language.id = client.language_id', 'marital_status' => 'marital_status.id = client.marital', 'gender' => 'gender.id = client.gender', 'groups' => 'groups.id = client.group_id'),
            'where' => array('client.clinic_number' => $client_id)
        );



        $data = $this->data->commonGet($client_details);
        echo json_encode($data);
    }

    public function get_client_data()
    {
        $client_id = $this->uri->segment(3);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');


        $client_details = array(
            'select' => ' groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                . 'client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable,client.marital,client.gender'
                . ',wellness_enable,motivational_enable,client.facility_id',
            'table' => 'client',
            'join' => array(
                'language' => 'language.id = client.language_id',
                'marital_status' => 'marital_status.id = client.marital',
                'gender' => 'gender.id = client.gender',
                'groups' => 'groups.id = client.group_id'
            ),
            'where' => array('client.id' => $client_id, 'client.status' => 'Active')
        );



        $data = $this->data->commonGet($client_details);
        echo json_encode($data);
    }

    public function get_appointment_data()
    {
        $appointment_id = $this->uri->segment(3);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $appointment_types = $this->input->post('appointment_types', true);


        $client_details = array(
            'select' => 'client.id as client_id, client.f_name,client.m_name,client.l_name,'
                . 'client.phone_no,appointment.id as appointment_id,appointment.appntmnt_date,'
                . 'appointment.app_type_1 appointment_types.name as appointment_types, appointment_types.id as appointment_types_id ',
            'table' => 'appointment',
            'join' => array('client' => 'client.id = appointment.client_id', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'),
            'where' => array('appointment.id' => $appointment_id, 'active_app' => '1', 'client.status' => 'Active'),
            'limit' => '1'
        );





        $data = $this->data->commonGet($client_details);
        echo json_encode($data);
    }

    public function get_appointment_client_data()
    {
        $client_id = $this->uri->segment(3);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $appointment_types = $this->input->post('appointment_types', true);


        $client_details = array(
            'select' => 'appointment.id as appointment_id,appointment.appntmnt_date,'
                . 'appointment.app_type_1,'
                . '      appointment_types.name as appointment_types , appointment_types.id as appointment_types_id',
            'table' => 'appointment',
            'join' => array('client' => 'client.id = appointment.client_id', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'),
            'where' => array('client_id' => $client_id, 'active_app' => '1', 'client.status' => 'Active'),
            'limit' => '1'
        );





        $data = $this->data->commonGet($client_details);
        echo json_encode($data);
    }

    public function edit_client()
    {
        $client_id = $this->uri->segment(3);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $client_details = array(
            'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                . ',appointment.appntmnt_date,appointment.app_type_1,      appointment_types.id as appointment_types_id, appointment_types.name as appointment_types',
            'table' => 'client',
            'join' => array(
                'language' => 'language.id = client.language_id',
                'groups' => 'groups.id = client.group_id',
                'appointment' => 'appointment.client_id = client.id', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
            ),
            'where' => array('client.status' => 'Active', 'client.id' => $client_id)
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
        $genders = array(
            'table' => 'gender',
            'where' => array('status' => 'Active')
        );

        $facilities = array(
            'table' => 'master_facility',
            'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
            'where' => array('partner_facility.status' => 'Active')
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['facilities'] = $this->data->commonGet($facilities);
        $data['client_details'] = $this->data->commonGet($client_details);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/edit_client');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function client()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');



        if ($access_level == "Donor") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );
        } elseif ($access_level == "Partner") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id)
            );
        } elseif ($access_level == "County") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array(
                    'partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'county.id = master_facility.county_id',
                    'sub_county' => 'sub_county.id = master_facility.sub_county_id'
                ),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array(
                    'partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'county.id = master_facility.county_id',
                    'sub_county' => 'sub_county.id = master_facility.sub_county_id'
                ),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } elseif ($access_level == "Facility") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id)
            );
        } else {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );
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
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/client');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function appointment()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');




        if ($access_level == "Donor") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );
        } elseif ($access_level == "Partner") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id)
            );
        } elseif ($access_level == "County") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array(
                    'partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'county.id = master_facility.county_id',
                    'sub_county' => 'sub_county.id = master_facility.sub_county_id'
                ),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array(
                    'partner_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'county.id = master_facility.county_id',
                    'sub_county' => 'sub_county.id = master_facility.sub_county_id'
                ),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } elseif ($access_level == "Facility") {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id)
            );
        } else {
            $facilities = array(
                'select' => 'master_facility.name as facility_name, master_facility.id as facility_id, master_facility.code as mfl_code,county.name as county_name,sub_county.name as sub_county_name',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id'),
                'where' => array('partner_facility.status' => 'Active')
            );
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
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/appointment');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function transfer_client()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $registration_type = $this->input->post('registration_type');
        $clinic_number = $this->input->post('clinic_number', true);
        $fname = $this->input->post('fname', true);
        $mname = $this->input->post('mname', true);
        $lname = $this->input->post('lname', true);
        $p_year = $this->input->post('p_year', true);
        $condition = $this->input->post('condition', true);
        $group = $this->input->post('group', true);
        $facilities = $this->input->post('facilities', true);
        $frequency = $this->input->post('frequency', true);
        $time = $this->input->post('time', true);
        $mobile = $this->input->post('mobile', true);
        $altmobile = $this->input->post('altmobile', true);
        $sharename = $this->input->post('sharename', true);
        $lang = $this->input->post('lang', true);
        $smsenable = $this->input->post('smsenable', true);
        $appointment_types = $this->input->post('appointment_types', true);
        $p_apptype1 = $this->input->post('p_apptype1', true);
        $p_apptype2 = $this->input->post('p_apptype2', true);
        $p_apptype3 = $this->input->post('p_apptype3', true);
        $custom_appointsms = $this->input->post('custom_appointsms', true);
        $apptdate = $this->input->post('apptdate', true);
        $sent_flag = $this->input->post('sent_flag', true);
        $status = $this->input->post('status', true);
        $gender = $this->input->post('gender', true);
        $status = $this->input->post('status', true);
        $partner_id = $this->input->post('partner_name', true);
        $marital = $this->input->post('marital', true);
        $enrollment_date = $this->input->post('enrollment_date', true);
        $art_date = $this->input->post('art_date', true);
        $wellnessenable = $this->input->post('wellnessenable', true);
        $motivational_enable = $this->input->post('motivational_enable', true);
        $consent_date = $this->input->post('consent_date', true);
        $today = date("Y-m-d H:i:s");


        $upn = $facility_id . $clinic_number;



        $this->db->select('clinic_number');
        $this->db->from('client');
        $this->db->where('clinic_number', $upn);
        $num_results = $this->db->count_all_results();

        $transaction = $this->data->transfer_client($registration_type, $clinic_number, $fname, $mname, $lname, $p_year, $condition, $group, $facilities, $frequency, $time, $mobile, $altmobile, $sharename, $lang, $smsenable, $appointment_types, $p_apptype1, $p_apptype2, $p_apptype3, $custom_appointsms, $today, $apptdate, $sent_flag, $status, $gender, $marital, $enrollment_date, $art_date, $wellnessenable, $motivational_enable, $consent_date);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function add_client()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $registration_type = $this->input->post('registration_type');
        $clinic_number = $this->input->post('clinic_number', true);
        $fname = $this->input->post('fname', true);
        $mname = $this->input->post('mname', true);
        $lname = $this->input->post('lname', true);
        $p_year = $this->input->post('p_year', true);
        $condition = $this->input->post('condition', true);
        $group = $this->input->post('group', true);
        $facilities = $this->input->post('facilities', true);
        $frequency = $this->input->post('frequency', true);
        $time = $this->input->post('time', true);
        $mobile = $this->input->post('mobile', true);
        $altmobile = $this->input->post('altmobile', true);
        $sharename = $this->input->post('sharename', true);
        $lang = $this->input->post('lang', true);
        $smsenable = $this->input->post('smsenable', true);
        $appointment_types = $this->input->post('appointment_types', true);
        $p_apptype1 = $this->input->post('p_apptype1', true);
        $p_apptype2 = $this->input->post('p_apptype2', true);
        $p_apptype3 = $this->input->post('p_apptype3', true);
        $custom_appointsms = $this->input->post('custom_appointsms', true);
        $apptdate = $this->input->post('apptdate', true);
        $sent_flag = $this->input->post('sent_flag', true);
        $status = $this->input->post('status', true);
        $gender = $this->input->post('gender', true);
        $status = $this->input->post('status', true);
        $partner_id = $this->input->post('partner_name', true);
        $marital = $this->input->post('marital', true);
        $enrollment_date = $this->input->post('enrollment_date', true);
        $art_date = $this->input->post('art_date', true);
        $wellnessenable = $this->input->post('wellnessenable', true);
        $motivational_enable = $this->input->post('motivational_enable', true);
        $consent_date = $this->input->post('consent_date', true);
        $today = date("Y-m-d H:i:s");


        $upn = $facility_id . $clinic_number;



        $this->db->select('clinic_number');
        $this->db->from('client');
        $this->db->where('clinic_number', $upn);
        $num_results = $this->db->count_all_results();
        if ($num_results == "0") {
            $transaction = $this->data->add_client($registration_type, $clinic_number, $fname, $mname, $lname, $p_year, $condition, $group, $facilities, $frequency, $time, $mobile, $altmobile, $sharename, $lang, $smsenable, $appointment_types, $p_apptype1, $p_apptype2, $p_apptype3, $custom_appointsms, $today, $apptdate, $sent_flag, $status, $gender, $marital, $enrollment_date, $art_date, $wellnessenable, $motivational_enable, $consent_date);
            if ($transaction) {
                $response = array(
                    'response' => $transaction
                );
                echo json_encode([$response]);
            } else {
                $response = array(
                    'response' => $transaction
                );
                echo json_encode([$response]);
            }
        } else {
            $response = array(
                'response' => 'Taken'
            );
            echo json_encode($response);
        }
    }

    public function update_client()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $clinic_id = $this->input->post('clinic_id', true);
        $clinic_number = $this->input->post('clinic_number', true);
        $fname = $this->input->post('fname', true);
        $mname = $this->input->post('mname', true);
        $lname = $this->input->post('lname', true);
        $p_year = $this->input->post('p_year', true);
        $condition = $this->input->post('condition', true);
        $group = $this->input->post('group', true);
        $facilities = $this->input->post('facilities', true);
        $frequency = $this->input->post('frequency', true);
        $time = $this->input->post('time', true);
        $mobile = $this->input->post('mobile', true);
        $altmobile = $this->input->post('altmobile', true);
        $sharename = $this->input->post('sharename', true);
        $lang = $this->input->post('lang', true);
        $smsenable = $this->input->post('smsenable', true);
        $appointment_types = $this->input->post('appointment_types', true);
        $p_apptype1 = $this->input->post('p_apptype1', true);
        $p_apptype2 = $this->input->post('p_apptype2', true);
        $p_apptype3 = $this->input->post('p_apptype3', true);
        $custom_appointsms = $this->input->post('custom_appointsms', true);
        $apptdate = $this->input->post('apptdate', true);
        $gender = $this->input->post('gender', true);
        $marital = $this->input->post('marital', true);
        $status = $this->input->post('status', true);
        $partner_id = $this->input->post('partner_name', true);
        $client_id = $this->input->post('client_id');
        $wellnessenable = $this->input->post('wellnessenable', true);
        $motivational_enable = $this->input->post('motivational_enable', true);
        $enrollment_date = $this->input->post('enrollment_date', true);
        $art_date = $this->input->post('art_date', true);
        $transfer_date = $this->input->post('transfer_date', true);
        $transfer_new_clinic = $this->input->post('transfer_new_clinic', true);
        $consent_date = $this->input->post('consent_date', true);
        $app_kept = $this->input->post('app_kept', true);



        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->update_client($client_id, $clinic_id, $clinic_number, $fname, $mname, $lname, $p_year, $condition, $group, $facilities, $frequency, $time, $mobile, $altmobile, $sharename, $lang, $smsenable, $appointment_types, $p_apptype1, $p_apptype2, $p_apptype3, $custom_appointsms, $today, $apptdate, $status, $gender, $marital, $enrollment_date, $art_date, $wellnessenable, $motivational_enable, $transfer_date, $transfer_new_clinic, $consent_date, $app_kept);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function check_clinic_no()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $clinic_number = $this->uri->segment(3);
        $partner_id = $this->input->post('partner_id');
        $query = $this->db->query("SELECT clinic_number,f_name,m_name,l_name,phone_no,id FROM tbl_client where clinic_number='$clinic_number'");

        $result = $query->result_array();

        echo json_encode($result);
    }

    public function appointments()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        //        $clinic_id = $this->session->userdata('clinic_id');
        //// $this->output->enable_profiler(TRUE);

        $access_level = $this->session->userdata('access_level');

        if ($access_level == "Partner") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );

            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' and active_app='1'  ";
        } elseif ($access_level == "Facility") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );

            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number, tbl_client.file_no,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . "tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_client.mfl_code='$facility_id' and active_app='1'";
        } elseif ($access_level == "County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . "tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id' and active_app='1' ";
        } elseif ($access_level == "Sub County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency, tbl_client.file_no,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . " tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id' and active_app='1' ";
        } else {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );






            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' and active_app='1' ";
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

        $app_types = array(
            'table' => 'appointment_types',
            'where' => array('status' => 'Active')
        );

        $data['appointment_types'] = $this->data->commonGet($app_types);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['appointments'] = $this->db->query($query)->result();
        $data['app_types'] = $this->data->commonGet($app_types);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/appointments');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function old_appointments()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $clinic_id = $this->session->userdata('clinic_id');
        $access_level = $this->session->userdata('access_level');

        if ($access_level == "Partner") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );



            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' and active_app='0'  ";
        } elseif ($access_level == "Facility") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_client.mfl_code='$facility_id' and active_app='0' and tbl_client.clinic_id='$clinic_id' ";
        } elseif ($access_level == "County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id' and active_app='0' ";
        } elseif ($access_level == "Sub County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id' and active_app='0' ";
        } else {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );






            $query = "Select tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                . " tbl_appointment.app_type_1,"
                . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                . " WHERE tbl_client.status = 'Active' and active_app='0' ";
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

        $app_types = array(
            'table' => 'appointment_types',
            'where' => array('status' => 'Active')
        );

        $data['appointment_types'] = $this->data->commonGet($app_types);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['genders'] = $this->data->commonGet($genders);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['appointments'] = $this->db->query($query)->result();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/old_appointments');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function get_current_appointments()
    {
        $app_id = $this->uri->segment(4);
        $id_all = $this->uri->segment(3);



        $get_app_date = $this->db->query("Select appntmnt_date,app_type_1 from tbl_appointment where id='$app_id'")->result();

        foreach ($get_app_date as $value) {
            $appntmnt_date = $value->appntmnt_date;
            $appointment_type_id = $value->app_type_1;

            //$get_days_appointments = $this->db->query("Select * from tbl_appointment where appntmnt_date='$appntmnt_date'")->result();



            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');


            $query = " ";

            if ($access_level == "Partner") {
                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE 1   ";
                $query .= " AND tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' AND tbl_appointment.appntmnt_date = '$appntmnt_date' and active_app='1'  ";
            } elseif ($access_level == "County") {
                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                    . " WHERE 1 ";
                $query .= "AND tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id'  AND tbl_appointment.appntmnt_date = '$appntmnt_date'  and active_app='1'   ";
            } elseif ($access_level == "Sub County") {
                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                    . " WHERE 1   ";
                $query .= " AND tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id'  AND tbl_appointment.appntmnt_date = '$appntmnt_date' and active_app='1'   ";
            } elseif ($access_level == "Facility") {
                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE 1 ";
                $query .= " AND tbl_client.status = 'Active' AND tbl_client.mfl_code='$facility_id'  AND tbl_appointment.appntmnt_date = '$appntmnt_date' ";
            } else {
                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE 1 ";
                $query .= " AND tbl_client.status = 'Active' AND tbl_appointment.appntmnt_date = '$appntmnt_date' and active_app='1'  ";
            }


            if ($id_all === "id_all") {
                //$query .= " AND app_type_1 = '$id_all'";
            } else {
                $query .= " AND tbl_appointment.app_type_1='$appointment_type_id' ";
            }

            $query .= " order by app_type_1 DESC";

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
            $data['appointments'] = $this->db->query($query)->result();
            $data['output'] = $this->get_access_level();


            $this->load->vars($data);
            $function_name = $this->uri->segment(2);

            if (empty($function_name)) {
            } else {
                $check_auth = $this->check_authorization($function_name);
                if ($check_auth) {
                    $this->load->template('Home/cal_appointments');
                } else {
                    $this->load->template('Home/cal_appointments');
                }
            }
        }
    }
    public function get_todays_unscheduled()
    {
        $access_level = $this->session->userdata('access_level');
        $facility_id = $this->session->userdata('facility_id');

        $app_id = $this->uri->segment(3);
        $get_app_date = $this->db->query("Select appntmnt_date,app_type_1 from tbl_appointment where id='$app_id'")->result();

        foreach ($get_app_date as $value) {
            $appntmnt_date = $value->appntmnt_date;
            if ($access_level == "Facility") {
                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " , CONCAT( tbl_appointment.appntmnt_date, ', Attended: ',  tbl_appointment.unscheduled_date) AS appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE 1 ";
                $query .= " AND tbl_client.status = 'Active' AND tbl_client.mfl_code='$facility_id'  AND tbl_appointment.appntmnt_date = '$appntmnt_date' AND visit_type = 'Un-Scheduled'  ";
            }
            $data['appointments'] = $this->db->query($query)->result();
            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $this->load->vars($data);

            $this->load->template('Home/cal_appointments');
        }
    }
    public function get_todays_confirmed()
    {
        $access_level = $this->session->userdata('access_level');
        $facility_id = $this->session->userdata('facility_id');

        $app_id = $this->uri->segment(3);
        $get_app_date = $this->db->query("Select appntmnt_date,app_type_1 from tbl_appointment where id='$app_id'")->result();

        foreach ($get_app_date as $value) {
            $appntmnt_date = $value->appntmnt_date;
            if ($access_level == "Facility") {
                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE 1 ";
                $query .= " AND tbl_client.status = 'Active' AND tbl_client.mfl_code='$facility_id'  AND tbl_appointment.appntmnt_date = '$appntmnt_date' AND active_app = '0' AND date_attended='$appntmnt_date'  ";
            }
            $data['appointments'] = $this->db->query($query)->result();
            $data['side_functions'] = $this->data->get_side_modules();
            $data['top_functions'] = $this->data->get_top_modules();
            $this->load->vars($data);

            $this->load->template('Home/cal_appointments');
        }
    }
    public function today_appointments()
    {
        $type = $this->uri->segment(3);
        if ($type === 'id') {
            $this->get_current_appointments();
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');
            $clinic_id = $this->session->userdata('clinic_id');



            if ($access_level == "Partner") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
                );



                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1, "
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1'   ";
            } elseif ($access_level == "County") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
                );



                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1, "
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                    . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id'  AND tbl_appointment.appntmnt_date = CURDATE()  and active_app='1'  ";
            } elseif ($access_level == "Sub County") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
                );


                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1, "
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                    . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id'  AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1'   ";
            } elseif ($access_level == "Facility") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
                );



                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1, "
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.mfl_code='$facility_id' AND tbl_appointment.appntmnt_date = CURDATE()  ";
            } else {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active')
                );






                $query = "Select tbl_client.file_no, tbl_appointment.id as appointment_id,tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1, "
                    . "      tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER  JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1'  ";
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
            $data['today_appointments'] = $this->db->query($query)->result();
            $data['output'] = $this->get_access_level();

            $this->load->vars($data);
            $function_name = $this->uri->segment(2);

            if (empty($function_name)) {
            } else {
                $check_auth = $this->check_authorization($function_name);
                if ($check_auth) {
                    $this->load->template('Home/today_appointments');
                } else {
                    $this->load->template('Home/today_appointments');

                    //                    echo 'Unauthorised Access';
                    //                    exit();
                }
            }
        }
    }

    public function get_incoming_messages()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $client_id = $this->uri->segment(3);

        $get_client_bio_data = $this->db->query("Select * from tbl_client where id='$client_id' LIMIT 1")->result();
        foreach ($get_client_bio_data as $value) {
            $phone_no = $value->phone_no;
            $mobile = substr($phone_no, -9);
            $len = strlen($mobile);
            if ($len < 10) {
                $phone_no = "+254" . $mobile;
            }
            $incoming_msgs_query = "Select * from tbl_responses where source='$phone_no' ";

            $get_incoming_msgs = $this->db->query($incoming_msgs_query)->result();
            echo json_encode($get_incoming_msgs);
        }
    }

    public function get_appointment_logs()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        $client_id = $this->uri->segment(3);
        if ($access_level == "Partner") {
            $client_details = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        } elseif ($access_level == "County") {
            $client_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        } elseif ($access_level == "Facility") {
            $client_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        } else {
            $client_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        }


        $data = $this->data->commonGet($client_details);
        echo json_encode($data);
    }

    public function get_client_outcomes()
    {
        $client_id = $this->uri->segment(3);


        $data = $this->db->query(" select * from vw_clnt_outcome WHERE client_id = '$client_id' ")->result_array();
        echo json_encode($data);
    }

    public function get_message_logs()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        $client_id = $this->uri->segment(3);
        if ($access_level == "Partner") {
            $client_details = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        } elseif ($access_level == "County") {
            $client_details = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        } elseif ($access_level == "Facility") {
            $client_details = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        } else {
            $client_details = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,message_types.name as msg_type,tbl_clnt_outgoing.updated_at as sent_on,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable ,client.facility_id,tbl_clnt_outgoing.msg',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.clnt_usr_id = client.id',
                    'message_types' => 'message_types.id = tbl_clnt_outgoing.message_type_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id, 'tbl_clnt_outgoing.recepient_type' => 'Client'),
                'order ' => array('tbl_clnt_outgoing.updated_at')
            );
        }


        $data = $this->data->commonGet($client_details);
        echo json_encode($data);
    }

    public function get_wellness_logs()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $client_id = $this->uri->segment(3);
        if ($access_level == "Partner") {
            $client_details = array(
                'select' => ' tbl_client.file_no, clinic_number,
	CONCAT( f_name, " ", m_name, " ", l_name ) AS client_name,
	tbl_sms_checkin.msg,
	tbl_sms_checkin.source,
	tbl_sms_checkin.destination,
	tbl_sms_checkin.response_type,
	DATE_FORMAT( tbl_sms_checkin.created_at, "%M %d %Y" ) AS date_sent,
	tbl_partner_facility.mfl_code,
	tbl_partner_facility.county_id,
	tbl_partner_facility.sub_county_id,
	tbl_partner_facility.partner_id  ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_sms_checkin' => 'tbl_sms_checkin.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id),
                'order ' => array('tbl_sms_checkin.created_at')
            );
        } elseif ($access_level == "County") {
            $client_details = array(
                'select' => ' tbl_client.file_no,  clinic_number,
	CONCAT( f_name, " ", m_name, " ", l_name ) AS client_name,
	tbl_sms_checkin.msg,
	tbl_sms_checkin.source,
	tbl_sms_checkin.destination,
	tbl_sms_checkin.response_type,
	DATE_FORMAT( tbl_sms_checkin.created_at, "%M %d %Y" ) AS date_sent,
	tbl_partner_facility.mfl_code,
	tbl_partner_facility.county_id,
	tbl_partner_facility.sub_county_id,
	tbl_partner_facility.partner_id  ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_sms_checkin' => 'tbl_sms_checkin.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id),
                'order ' => array('tbl_sms_checkin.created_at')
            );
        } elseif ($access_level == "Facility") {
            $client_details = array(
                'select' => ' tbl_client.file_no, clinic_number,
	CONCAT( f_name, " ", m_name, " ", l_name ) AS client_name,
	tbl_sms_checkin.msg,
	tbl_sms_checkin.source,
	tbl_sms_checkin.destination,
	tbl_sms_checkin.response_type,
	DATE_FORMAT( tbl_sms_checkin.created_at, "%M %d %Y" ) AS date_sent,
	tbl_partner_facility.mfl_code,
	tbl_partner_facility.county_id,
	tbl_partner_facility.sub_county_id,
	tbl_partner_facility.partner_id  ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_sms_checkin' => 'tbl_sms_checkin.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id),
                'order ' => array('tbl_sms_checkin.created_at')
            );
        } else {
            $client_details = array(
                'select' => ' tbl_client.file_no, clinic_number,
	CONCAT( f_name, " ", m_name, " ", l_name ) AS client_name,
	tbl_sms_checkin.msg,
	tbl_sms_checkin.source,
	tbl_sms_checkin.destination,
	tbl_sms_checkin.response_type,
	DATE_FORMAT( tbl_sms_checkin.created_at, "%M %d %Y" ) AS date_sent,
	tbl_partner_facility.mfl_code,
	tbl_partner_facility.county_id,
	tbl_partner_facility.sub_county_id,
	tbl_partner_facility.partner_id  ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'tbl_sms_checkin' => 'tbl_sms_checkin.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'client.id' => $client_id),
                'order ' => array('tbl_sms_checkin.created_at')
            );
        }


        $data = $this->data->commonGet($client_details);
        echo json_encode($data);
    }

    public function ltfu_clients()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        //// $this->output->enable_profiler(TRUE);

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



        if ($access_level == "Partner") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );




            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at, appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array(
                    'client.status' => 'Active',
                    'app_status' => 'LTFU',
                    'client.partner_id' => $partner_id
                )
            );
        } elseif ($access_level == "Facility") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );




            $notified_details = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ), 'where' => array('app_status' => 'LTFU', 'client.mfl_code' => $facility_id)
            );
        } elseif ($access_level == "County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );




            $notified_details = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('app_status' => 'LTFU', 'partner_facilty.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );




            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('app_status' => 'LTFU', 'partner_facilty.sub_county_id' => $sub_county_id)
            );
        } else {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );




            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('app_status' => 'LTFU')
            );
        }






        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['notified'] = $this->data->commonGet($notified_details);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/ltfu');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function checkins()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');


        if ($access_level == "Partner") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('client.partner_id', $partner_id);
            $this->db->group_by('tbl_clnt_outgoing.id');
            $this->db->group_by('sms_checkin.id');

            $checkins = $this->db->get();
        } elseif ($access_level == "Facility") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('client.mfl_code', $facility_id);
            $this->db->where('client.clinic_id', $clinic_id);
            $this->db->group_by('tbl_clnt_outgoing.id');
            $this->db->group_by('sms_checkin.id');
            $checkins = $this->db->get();
        } else {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->group_by('tbl_clnt_outgoing.id');
            $this->db->group_by('sms_checkin.id');
            $checkins = $this->db->get();
        }





        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['weekly_checkin'] = ($checkins);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/weekly_checkin');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function responded_checkins()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        if ($access_level == "Partner") {
            $checkins = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'DATE_FORMAT(tbl_tbl_clnt_outgoing.created_at, "%d %M %Y %k %i %s")as  date_sent,tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status,',
                'table' => 'client',
                'join' => array(
                    'groups' => 'groups.id = client.group_id',
                    'sms_checkin' => 'sms_checkin.client_id = client.id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.destination = client.phone_no'
                ),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $checkins = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'DATE_FORMAT(tbl_tbl_clnt_outgoing.created_at, "%d %M %Y %k %i %s")as  date_sent,tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status,',
                'table' => 'client',
                'join' => array(
                    'groups' => 'groups.id = client.group_id',
                    'sms_checkin' => 'sms_checkin.client_id = client.id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.destination = client.phone_no'
                ),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id, 'client.clinic_id' => $clinic_id)
            );
        } else {
            $checkins = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'DATE_FORMAT(tbl_tbl_clnt_outgoing.created_at, "%d %M %Y %k %i %s")as  date_sent,tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status,',
                'table' => 'client',
                'join' => array(
                    'groups' => 'groups.id = client.group_id',
                    'sms_checkin' => 'sms_checkin.client_id = client.id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.destination = client.phone_no'
                ),
                'where' => array('client.status' => 'Active')
            );
        }



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['weekly_checkin'] = $this->data->commonGet($checkins);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/responded_checkins');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function pending_checkins()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $clinic_id = $this->session->userdata('clinic_id');

        $checkins = array(
            'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                . 'DATE_FORMAT(tbl_tbl_clnt_outgoing.created_at, "%d %M %Y %k %i %s")as  date_sent,tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status,',
            'table' => 'client',
            'join' => array(
                'groups' => 'groups.id = client.group_id',
                'sms_checkin' => 'sms_checkin.client_id = client.id',
                'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.destination = client.phone_no'
            ),
            'where' => array('client.status' => 'Active')
        );


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['weekly_checkin'] = $this->data->commonGet($checkins);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/pending_checkins');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function late_checkins()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        if ($access_level == "Partner") {
            $checkins = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'DATE_FORMAT(tbl_clnt_outgoing.created_at, "%d %M %Y %k %i %s")as  date_sent,tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status,',
                'table' => 'client',
                'join' => array(
                    'groups' => 'groups.id = client.group_id',
                    'sms_checkin' => 'sms_checkin.client_id = client.id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.destination = client.phone_no'
                ),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $checkins = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'DATE_FORMAT(tbl_clnt_outgoing.created_at, "%d %M %Y %k %i %s")as  date_sent,tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status,',
                'table' => 'client',
                'join' => array(
                    'groups' => 'groups.id = client.group_id',
                    'sms_checkin' => 'sms_checkin.client_id = client.id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.destination = client.phone_no'
                ),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id, 'client.clinic_id' => $clinic_id)
            );
        } else {
            $checkins = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'DATE_FORMAT(tbl_clnt_outgoing.created_at, "%d %M %Y %k %i %s")as  date_sent,tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status,',
                'table' => 'client',
                'join' => array(
                    'groups' => 'groups.id = client.group_id',
                    'sms_checkin' => 'sms_checkin.client_id = client.id',
                    'tbl_clnt_outgoing' => 'tbl_clnt_outgoing.destination = client.phone_no'
                ),
                'where' => array('client.status' => 'Active')
            );
        }


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['output'] = $this->get_access_level();
        $data['weekly_checkin'] = $this->data->commonGet($checkins);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/late_checkins');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function unrecognised_checkins()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        if ($access_level == "Partner") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('client.partner_id', $partner_id);
            $this->db->where('sms_checkin.response_type', 'Other');
            $this->db->group_by('client_id');

            $checkins = $this->db->get();
        } elseif ($access_level == "Facility") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('client.mfl_code', $facility_id);
            $this->db->where('client.clinic_id', $clinic_id);
            $this->db->where('sms_checkin.response_type', 'Other');
            $this->db->group_by('client_id');
            $checkins = $this->db->get();
        } else {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('sms_checkin.response_type', 'Other');
            $this->db->group_by('client_id');
            $checkins = $this->db->get();
        }


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['weekly_checkin'] = $checkins->result();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        //// $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/unrecognised_checkins');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function ok_clients()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->sesion->userdata('clinic_id');
        if ($access_level == "Partner") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('client.partner_id', $partner_id);
            $this->db->where('sms_checkin.response_type', 'Positive');
            $this->db->group_by('client_id');

            $checkins = $this->db->get();
        } elseif ($access_level == "Facility") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('client.mfl_code', $facility_id);
            $this->db->where('client.clinic_id', $clinic_id);
            $this->db->where('sms_checkin.response_type', 'Positive');
            $this->db->group_by('client_id');
            $checkins = $this->db->get();
        } else {
            $this->db->select(" tbl_client.file_no,  groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('sms_checkin.response_type', 'Positive');
            $this->db->group_by('client_id');
            $checkins = $this->db->get();
        }




        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['ok_clients'] = $checkins->result();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        //// $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/ok_clients');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function not_ok_clients()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        if ($access_level == "Partner") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('sms_checkin.response_type', 'Negative');
            $this->db->where('client.partner_id', $partner_id);
            $this->db->group_by('client_id');
            $checkins = $this->db->get();
        } elseif ($access_level == "Facility") {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent , "
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('sms_checkin.response_type', 'Negative');
            $this->db->where('client.mfl_code', $facility_id);
            $this->db->where('client.clinic_id', $clinic_id);
            $this->db->group_by('client_id');
            $checkins = $this->db->get();
        } else {
            $this->db->select(" tbl_client.file_no, groups.name as group_name,groups.id as group_id, "
                . " f_name,m_name,l_name,dob,client.status,phone_no, "
                . " client.clinic_number,DATE_FORMAT(tbl_clnt_outgoing.created_at, '%d %M %Y %k %i %s')as  date_sent,"
                . "sms_checkin.msg,sms_checkin.created_at as response_date,"
                . "tbl_clnt_outgoing.responded,client.id as client_id,client.clinic_number,client.client_status");
            $this->db->from('client');
            $this->db->join('groups', 'groups.id=client.group_id');
            $this->db->join('sms_checkin', 'sms_checkin.client_id = client.id');
            $this->db->join('tbl_clnt_outgoing', 'tbl_clnt_outgoing.clnt_usr_id = client.id');
            $this->db->where('client.status', 'Active');
            $this->db->where('sms_checkin.response_type', 'Negative');
            $this->db->group_by('client_id');

            $checkins = $this->db->get();
        }


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['output'] = $this->get_access_level();
        $data['not_ok_clients'] = $checkins->result();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        //// $this->output->enable_profiler(TRUE);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/not_ok_clients');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function booked_clients()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
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



        if ($access_level == "Partner") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );



            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Booked', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Booked', 'client.mfl_code' => $facility_id, 'client.clinic_id' => $clinic_id)
            );
        } elseif ($access_level == "County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );




            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Booked', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );




            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Booked', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } else {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );




            $notified_details = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1 ',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Booked')
            );
        }






        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['notified'] = $this->data->commonGet($notified_details);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/booked');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function missed_clients()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');


        if ($access_level == "Partner") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );



            $missed_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Missed', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $missed_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Missed', 'client.mfl_code' => $facility_id, 'client.clinic_id' => $clinic_id)
            );
        } elseif ($access_level == "County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );


            $missed_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Missed', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $missed_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Missed', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } else {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );


            $missed_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Missed')
            );
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





        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['notified'] = $this->data->commonGet($missed_clients);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        //// $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/missed');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function defaulted_clients()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');

        if ($access_level == "Partner") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );




            $defaulted_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Defaulted', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $defaulted_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Defaulted', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $defaulted_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Defaulted', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } elseif ($access_level == "Facility") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );



            $defaulted_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Defaulted', 'client.mfl_code' => $facility_id, 'client.clinic_id' => $clinic_id)
            );
        } else {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );



            $defaulted_clients = array(
                'select' => ' tbl_client.file_no, appointment.id as appointment_id,groups.name as group_name,groups.id as group_id,language.name as language_name ,app_type_1,appointment_types.name as appointment_types,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable'
                    . ',appointment.appntmnt_date,appointment.app_msg,appointment.updated_at,appointment.app_type_1,      appointment.no_calls,appointment.no_msgs,appointment.home_visits',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'appointment_types' => 'appointment_types.id = appointment.app_type_1'
                ),
                'where' => array('client.status' => 'Active', 'app_status' => 'Defaulted')
            );
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





        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['notified'] = $this->data->commonGet($defaulted_clients);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/defaulted');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function notified_clients()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        //// $this->output->enable_profiler(TRUE);
        if ($access_level == "Partner") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );



            $notified_details = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,app_type_1,tbl_appointment_types.id as appointment_types_id , tbl_appointment_types.name as appointment_types,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . "tbl_client.created_at as enrollment_date,tbl_client.updated_at,tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . "tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . ",tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,tbl_appointment.app_type_1  "
                . " from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id "
                . " inner join tbl_groups on tbl_groups.id = tbl_client.group_id "
                . " inner join tbl_appointment on tbl_appointment.client_id =tbl_client.id "
                . " inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1"
                . " where tbl_client.status='Active'  and DATE(appntmnt_date)  >= CURDATE() and active_app='1' and tbl_client.partner_id='$partner_id'";
        } elseif ($access_level == "County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );


            $notified_details = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,app_type_1,tbl_appointment_types.id as appointment_types_id , tbl_appointment_types.name as appointment_types,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . "tbl_client.created_at as enrollment_date,tbl_client.updated_at,tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . "tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . ",tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,tbl_appointment.app_type_1  "
                . " from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id "
                . " inner join tbl_groups on tbl_groups.id = tbl_client.group_id "
                . " inner join tbl_appointment on tbl_appointment.client_id =tbl_client.id "
                . " inner join tbl_master_facility on tbl_master_facility.code = tbl_client.mfl_code"
                . " inner JOIN tbl_county  ON tbl_county.id = tbl_master_facility.county_id"
                . " inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1"
                . " where tbl_client.status='Active' and DATE(appntmnt_date)  >= CURDATE() and active_app='1' and tbl_master_facility.county_id='$county_id'";
        } elseif ($access_level == "Sub County") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );




            $notified_details = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,app_type_1,tbl_appointment_types.id as appointment_types_id , tbl_appointment_types.name as appointment_types,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . "tbl_client.created_at as enrollment_date,tbl_client.updated_at,tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . "tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . ",tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,tbl_appointment.app_type_1  "
                . " from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id "
                . " inner join tbl_groups on tbl_groups.id = tbl_client.group_id "
                . " inner join tbl_master_facility on tbl_master_facility.code = tbl_client.mfl_code"
                . " inner join tbl_appointment on tbl_appointment.client_id =tbl_client.id "
                . " inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1"
                . " where tbl_client.status='Active'  and DATE(appntmnt_date)  >= CURDATE() and active_app='1' and tbl_master_facility.Sub_County_ID='$sub_county_id'";
        } elseif ($access_level == "Facility") {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
            );
            $notified_details = "Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,app_type_1,tbl_appointment_types.id as appointment_types_id , tbl_appointment_types.name as appointment_types,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . "tbl_client.created_at as enrollment_date,tbl_client.updated_at,tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . "tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . ",tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,tbl_appointment.app_type_1  "
                . " from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id "
                . " inner join tbl_groups on tbl_groups.id = tbl_client.group_id "
                . "inner join tbl_master_facility on tbl_master_facility.code = tbl_client.mfl_code "
                . "inner JOIN `tbl_county`  ON `tbl_county`.`id` = `tbl_master_facility`.county_id "
                . " inner join tbl_appointment on tbl_appointment.client_id =tbl_client.id "
                . " inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1"
                . " where tbl_client.status='Active'  and DATE(appntmnt_date)  >= CURDATE() and active_app='1' and tbl_client.mfl_code='$facility_id' ";
        } else {
            $appointments = array(
                'table' => 'appointment',
                'join' => array('client' => 'client.id = appointment.client_id'),
                'where' => array('client.status' => 'Active')
            );

            $notified_details = " Select tbl_client.file_no, tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,app_type_1,tbl_appointment_types.id as appointment_types_id , tbl_appointment_types.name as appointment_types,"
                . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                . "tbl_client.created_at as enrollment_date,tbl_client.updated_at,tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                . "tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                . ",tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,tbl_appointment.app_type_1  "
                . " from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id "
                . " inner join tbl_groups on tbl_groups.id = tbl_client.group_id "
                . " inner join tbl_appointment on tbl_appointment.client_id =tbl_client.id "
                . " inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1"
                . " where tbl_client.status='Active'  and DATE(appntmnt_date)  >= CURDATE() and active_app='1' ";
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




        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['notified'] = $this->db->query($notified_details)->result();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/notified');
            } else {
                $this->load->template('Home/notified');
                //                echo 'Unauthorised Access';
                //                exit();
            }
        }
    }

    public function update_appointment()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $client_id = $this->input->post('client_id', true);
        $app_date = $this->input->post('apptdate', true);
        $app_type_1 = $this->input->post('app_type_1', true);
        //$transaction = $this->data->update_appointment($client_id, $app_date);

        $appointment_id = $this->input->post('appointment_id', true);

        $app_kept = $this->input->post('app_kept', true);
        $transaction = $this->data->update_appointment($client_id, $app_date, $app_kept, $appointment_id, $app_type_1);




        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function edit_appointment()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $client_id = $this->input->post('client_id', true);

        $app_date = $this->input->post('appointment_date', true);
        $app_type = $this->input->post('p_apptype1', true);

        $appointment_id = $this->input->post('appointment_id', true);

        $app_kept = $this->input->post('app_kept', true);
        //echo 'Values => Clnt ID => ' . $client_id . '<br> App Date => ' . $app_date . '<br> App kept => ' . $app_kept . '<br> App ID => ' . $appointment_id . '<br> App type => ' . $app_type_1;

        $transaction = $this->data->edit_appointment($client_id, $app_date, $app_kept, $appointment_id, $app_type);




        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function client_groups()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $clinic_id = $this->session->userdata('clinic_id');
        if ($access_level == "Partner") {
            $client_groups = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('groups' => 'groups.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $client_groups = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('groups' => 'groups.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id, 'client.clinic_id' => $clinic_id)
            );
        } else {
            $client_groups = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('groups' => 'groups.id = client.group_id'),
                'where' => array('client.status' => 'Active')
            );
        }



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['client_group'] = $this->data->commonGet($client_groups);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/client_groups');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function pmtct()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        if ($access_level == "Partner") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinci.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.clinic_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '2', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '2', 'client.mfl_code' => $facility_id)
            );
        } else {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '2')
            );
        }




        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['pmtct_group'] = $this->data->commonGet($client_clinic);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        //// $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/pmtct_group');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function adolescents()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        if ($access_level == "Partner") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '3', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "County") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array(
                    'clinic' => 'clinic.id = client.group_id', 'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'clinic.id' => '3', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "County") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array(
                    'clinic' => 'clinic.id = client.group_id', 'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'clinic.id' => '3', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } elseif ($access_level == "Facility") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '3', 'client.mfl_code' => $facility_id)
            );
        } else {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '3')
            );
        }


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['adolescent_group'] = $this->data->commonGet($client_clinic);
        $this->load->vars($data);
        $function_name = $this->uri->segment(3);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/adolescent_group');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    public function adults()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        if ($access_level == "Partner") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '1', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "County") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array(
                    'clinic' => 'clinic.id = client.group_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'clinic.id' => '1', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $client_clinic = array(
                'select' => 'tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array(
                    'clinic' => 'clinic.id = client.group_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'clinic.id' => '1', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } elseif ($access_level == "Facility") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '1', 'client.mfl_code' => $facility_id)
            );
        } else {
            $client_clinic = array(
                'select' => 'tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '1')
            );
        }


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $ata['adult_group'] = $this->data->commonGet($client_clinic);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/adult_group', $ata);
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function tb()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        if ($access_level == "Partner") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '4', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "County") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array(
                    'clinic' => 'clinic.id = client.group_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'clinic.id' => '4', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "Sub County") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array(
                    'clinic' => 'clinic.id = client.group_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'clinic.id' => '4', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } elseif ($access_level == "Facility") {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '4', 'client.mfl_code' => $facility_id)
            );
        } else {
            $client_clinic = array(
                'select' => ' tbl_client.file_no, clinic.name as group_name,clinic.id as group_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name',
                'table' => 'client',
                'join' => array('clinic' => 'clinic.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'clinic.id' => '4')
            );
        }


        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $ata['tb_group'] = $this->data->commonGet($client_clinic);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/tb_group', $ata);
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function manual_sms()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        if ($access_level == "Partner") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id)
            );

            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable,appointment.appntmnt_date AS appntmnt_date',
                'table' => 'client',
                'join' => array('language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id'),
                'where' => array('client.status' => 'Active', 'smsenable' => 'Yes', 'client.partner_id' => $partner_id)
            );
        } elseif ($access_level == "County") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id)
            );

            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable,appointment.appntmnt_date AS appntmnt_date',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'smsenable' => 'Yes', 'partner_facility.county_id' => $county_id)
            );
        } elseif ($access_level == "County") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id)
            );

            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable,appointment.appntmnt_date AS appntmnt_date',
                'table' => 'client',
                'join' => array(
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'appointment' => 'appointment.client_id = client.id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code'
                ),
                'where' => array('client.status' => 'Active', 'smsenable' => 'Yes', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } elseif ($access_level == "Facility") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id)
            );

            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable,appointment.appntmnt_date AS appntmnt_date',
                'table' => 'client',
                'join' => array('language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'appointment' => 'appointment.client_id = client.id'),
                'where' => array('client.status' => 'Active', 'smsenable' => 'Yes', 'client.mfl_code' => $facility_id)
            );
        } else {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active')
            );

            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,client.clinic_number,'
                    . 'client.created_at as enrollment_date,client.updated_at,client.id as client_id,client.clinic_number,client.client_status,client.txt_frequency,'
                    . 'client.txt_time,client.alt_phone_no,client.shared_no_name,client.smsenable,appointment.appntmnt_date AS appntmnt_date',
                'table' => 'client',
                'join' => array('language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'appointment' => 'appointment.client_id = client.id'),
                'where' => array('smsenable' => 'Yes', 'client.status' => 'Active')
            );
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



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['clients'] = $this->data->commonGet($clients);
        $data['facilities'] = $this->data->commonGet($facilities);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        // // $this->output->enable_profiler(TRUE);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/manual_sms');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function send_manual_sms()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $client_id = $this->input->post('client_id', true);
        $destination = $this->input->post('mobile', true);
        //$source = $this->input->post();
        $msg = $this->input->post('message', true);
        $transaction = $this->data->send_manual_sms($client_id, $destination, $msg);

        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function broadcast_sms()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
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
        $access_levels = array(
            'table' => 'access_level',
            'where' => array('status' => 'Active')
        );


        if ($access_level == "Facility") {
        } else {
        }
        $counties = array(
            'table' => 'county',
            'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
            'where' => array('county.status' => 'Active')
        );


        $sub_counties = array(
            'table' => 'sub_county',
            'join' => array('partner_facility' => 'partner_facility.sub_county_id = sub_county.id'),
            'where' => array('sub_county.status' => 'Active')
        );

        $data['counties'] = $this->db->query("SELECT tbl_county.`id` , tbl_county.`name` FROM tbl_county INNER JOIN tbl_partner_facility ON tbl_partner_facility.`county_id` = tbl_county.`id` GROUP BY tbl_county.`id`")->result();

        $data['sub_counties'] = $this->data->commonGet($sub_counties);

        $data['access_levels'] = $this->data->commonGet($access_levels);
        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/broadcast_sms');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function count_target_clients()
    {
        $group = $this->input->post('group', true);
        $date_time = $this->input->post('date_time', true);
        $broadcast_date = $this->input->post('broadcast_date', true);
        $target_group = $this->input->post('target_group', true);
        $default_time = $this->input->post('default_time', true);
        $defaultsms = $this->input->post('defaultsms', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);


        $appointment_from = $this->input->post('appointment_from', true);
        $appointment_to = $this->input->post('appointment_to', true);

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $sql = "";
        if ($target_group === "1") {
            $sql .= "Select count(tbl_client.id) as no_clients from tbl_client inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code where 1 ";
        } elseif ($target_group === "2") {
            $sql .= "Select count(tbl_client.id) as no_clients from tbl_client inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id  where 1 ";

            if (!empty($appointment_from)) {
                $appointment_from = str_replace('-', '-', $appointment_from);
                $appointment_from = date("Y-m-d", strtotime($appointment_from));

                $sql .= " AND tbl_appointment.appntmnt_date >= $appointment_from ";
            }
            if (!empty($appointment_to)) {
                $appointment_to = str_replace('-', '-', $appointment_to);
                $appointment_to = date("Y-m-d", strtotime($appointment_to));

                $sql .= " AND tbl_appointment.appntmnt_date <= $appointment_to ";
            }
        } elseif ($target_group === "3") {
            $sql .= "Select count(tbl_client.id) as no_clients from tbl_client inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id  where 1 ";
            $sql .= " AND tbl_appointment.appntmnt_date = 'Missed' ";
        } elseif ($target_group === "4") {
            echo $target_group;
            $sql .= "Select count(tbl_client.id) as no_clients from tbl_client inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id  where 1 ";
            $sql .= " AND tbl_appointment.appntmnt_date = 'Defaulted' ";
        } elseif ($target_group === "5") {
            echo $target_group;
            $sql .= "Select count(tbl_client.id) as no_clients from tbl_client inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id  where 1 ";
            $sql .= " AND tbl_appointment.appntmnt_date = 'LTFU' ";
        } else {
            $sql .= "Select count(tbl_client.id) as no_clients from tbl_client inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code where 1 ";
        }
        if (!empty($county_id)) {
            $sql .= " AND county_id = '$county_id' ";
        }
        if (!empty($sub_county_id)) {
            $sql .= " AND sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_client.mfl_code = '$mfl_code' ";
        }
        if (!empty($group)) {
            if ($group == "All") {
            } else {
                $sql .= " AND tbl_client.group_id='$group' ";
            }
        }

        if ($access_level == "Partner") {
            $sql .= "AND tbl_partner_facility.partner_id='$partner_id'";
        } elseif ($access_level == "Facility") {
            $sql .= "AND tbl_partner_facility.mfl_code='$facility_id'";
        } else {
        }

        $sql .= "AND tbl_client.status='Active'";



        $client_data = $this->db->query($sql)->result();
        //// $this->output->enable_profiler(TRUE);
        echo json_encode($client_data);
    }

    public function count_target_users()
    {
        //// $this->output->enable_profiler(TRUE);
        $target_access_level = $this->input->post('access_level', true);

        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $sql = "";
        if ($access_level === "Partner") {

            //Partner Access Level
            $sql .= "Select count(DISTINCT tbl_users.id) as no_users from tbl_users inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id where 1 ";
        } elseif ($access_level === "Facility") {

            //Facility Access Level
            $sql .= "Select count(DISTINCT tbl_users.id) as no_users from tbl_users inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id where 1 ";
        } elseif ($access_level === "Admin") {

            //Administration in the  System
            //            $sql .= "Select count(DISTINCT tbl_users.id) as no_users from tbl_users inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id where 1 ";
            //Administration in the  System
            $sql .= "Select count(DISTINCT tbl_users.id) as no_users from tbl_users  ";
            // $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id";


            if (!empty($target_access_level)) {
                if ($target_access_level == '4') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id";
                }
                if ($target_access_level == '5') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.county_id = tbl_users.county_id";
                }
                if ($target_access_level == '6') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.sub_county_id = tbl_users.subcounty_id";
                }
                if ($target_access_level == '3') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_partner.id";
                }
                if ($target_access_level == '2') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.donor_id = tbl_donor.id";
                }
                if ($target_access_level == '1') {
                    $sql .= "  ";
                }
            }

            $sql .= " ";
            $sql .= " where 1 ";
        } else {
            //Administration in the  System
            $sql .= "Select count(DISTINCT tbl_users.id) as no_users from tbl_users  ";
            // $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id";


            if (!empty($target_access_level)) {
                if ($target_access_level == '4') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id";
                }
                if ($target_access_level == '5') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.county_id = tbl_users.county_id";
                }
                if ($target_access_level == '6') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.sub_county_id = tbl_users.sub_county_id";
                }
                if ($target_access_level == '3') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_partner.id";
                }
                if ($target_access_level == '2') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.donor_id = tbl_donor.id";
                }
                if ($target_access_level == '1') {
                    $sql .= "  ";
                }
            }
            $sql .= " ";
            $sql .= " where 1 ";
        }
        if (!empty($county_id)) {
            $sql .= " AND tbl_partner_facility.county_id = '$county_id' ";
        }
        if (!empty($sub_county_id)) {
            // echo ''.$sub_county_id;
            $sql .= " AND tbl_partner_facility.sub_county_id = '$sub_county_id' ";
        }
        if (!empty($mfl_code)) {
            $sql .= " AND tbl_client.mfl_code = '$mfl_code' ";
        }
        if ($access_level == "Partner") {
            $sql .= "AND tbl_partner_facility.partner_id='$partner_id' ";
        } elseif ($access_level == "Facility") {
            $sql .= "AND tbl_partner_facility.mfl_code='$facility_id' and tbl_users.access_level='Facility' ";
        } else {
            if (!empty($target_access_level)) {
                if ($target_access_level == '4') {
                    $sql .= "AND  tbl_users.access_level='Facility' ";
                    if (!empty($mfl_code)) {
                        $sql .= " AND tbl_partner_facility.mfl_code='$facility_id' ";
                    }
                }
                if ($target_access_level == '5') {
                    $sql .= " and tbl_users.access_level='County' ";
                    if (!empty($county_id)) {
                        $sql .= " AND tbl_partner_facility.county_id='$county_id' ";
                    }
                }
                if ($target_access_level == '6') {
                    $sql .= " and tbl_users.access_level='Sub County'";

                    if (!empty($county_id)) {
                        $sql .= " AND tbl_partner_facility.sub_county_id='$sub_county_id'  ";
                    }
                }
                if ($target_access_level == '3') {
                    $sql .= " and tbl_users.access_level='Partner'";

                    if (!empty($partner_id)) {
                        $sql .= " AND tbl_partner_facility.partner_id='$partner_id'  ";
                    }
                }
                if ($target_access_level == '2') {
                    $sql .= " and tbl_users.access_level='Donor'";
                }
                if ($target_access_level == '1') {
                    $sql .= " and tbl_users.access_level='Admin'";
                }
            }
        }


        $client_data = $this->db->query($sql)->result();
        echo json_encode($client_data);
    }

    public function set_user_broadcast_sms()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $group = $this->input->post('group', true);
        $date_time = $this->input->post('date_time', true);

        $target_group = $this->input->post('target_group', true);
        $default_time = $this->input->post('default_time', true);
        $defaultsms = $this->input->post('defaultsms', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);


        $target_access_level = $this->input->post('access_level', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $broadcast_date = $this->input->post('broadcast_date', true);
        $broadcast_time = $this->input->post('broadcast_time', true);
        $broadcast_message = $this->input->post('broadcast_message', true);




        $transaction = $this->data->set_user_broadcast_sms($target_access_level, $county_id, $sub_county_id, $mfl_code, $broadcast_date, $broadcast_time, $broadcast_message);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function set_broadcast_sms()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $group = $this->input->post('group', true);
        $date_time = $this->input->post('date_time', true);
        $broadcast_date = $this->input->post('broadcast_date', true);
        $target_group = $this->input->post('target_group', true);
        $default_time = $this->input->post('default_time', true);
        $defaultsms = $this->input->post('defaultsms', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $appointment_from = $this->input->post('appointment_to', true);
        $appointment_to = $this->input->post('appointment_to', true);

        $transaction = $this->data->set_broadcast_sms($group, $date_time, $broadcast_date, $target_group, $default_time, $defaultsms, $county_id, $sub_county_id, $mfl_code, $appointment_from, $appointment_to);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function update_broadcast_sms()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $group = $this->input->post('group', true);
        $date_time = $this->input->post('date_time', true);
        $broadcast_date = $this->input->post('broadcast_date', true);
        $target_group = $this->input->post('target_group', true);
        $default_time = $this->input->post('default_time', true);
        $defaultsms = $this->input->post('defaultsms', true);
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $broadcast_id = $this->input->post('broadcast_id', true);

        $transaction = $this->data->update_broadcast_sms($broadcast_id, $group, $date_time, $broadcast_date, $target_group, $default_time, $defaultsms, $county_id, $sub_county_id, $mfl_code);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function get_broadcast_data()
    {
        $broadcast_id = $this->uri->segment(3);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');


        $broadcast_data = "Select * from tbl_broadcast where id='$broadcast_id'";



        $data = $this->db->query($broadcast_data)->result();
        echo json_encode($data);
    }

    public function broadcast_report()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

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


        if ($access_level == "Facility") {
        } else {
        }
        $counties = array(
            'table' => 'county',
            'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
            'where' => array('county.status' => 'Active')
        );


        $sub_counties = array(
            'table' => 'sub_county',
            'join' => array('partner_facility' => 'partner_facility.sub_county_id = sub_county.id'),
            'where' => array('sub_county.status' => 'Active')
        );

        $data['counties'] = $this->db->query("SELECT tbl_county.`id` , tbl_county.`name` FROM tbl_county INNER JOIN tbl_partner_facility ON tbl_partner_facility.`county_id` = tbl_county.`id` GROUP BY tbl_county.`id`")->result();


        $sql = "SELECT a.`is_approved`, a.`name`,a.`created_at`,a.`status` , COUNT(b.id) AS no_clients ,a.`msg` , b.`broadcast_date` , a.`id` , b.`recepient_type` FROM tbl_broadcast a , tbl_sms_queue b , tbl_client c , tbl_partner_facility d WHERE 1 ";
        if ($access_level == "Facility") {
            $sql .= "AND  d.mfl_code='$facility_id' ";
        } elseif ($access_level == "Partner") {
            $sql .= "AND  d.partner_id ='$partner_id' ";
        } else {
        }
        $sql .= " AND a.id = b.`broadcast_id` AND b.`clnt_usr_id` = c.`id` AND c.`mfl_code` = d.`mfl_code` AND b.status='Active' GROUP BY a.`id` ";


        $user_sql = "SELECT a.`is_approved`, a.`name`,a.`created_at`,a.`status` , COUNT(b.id) AS no_clients ,a.`msg` , b.`broadcast_date` , a.`id` , b.`recepient_type` FROM tbl_broadcast a , tbl_sms_queue b , tbl_users c , tbl_partner_facility d WHERE 1 ";
        if ($access_level == "Facility") {
            $user_sql .= "AND  d.mfl_code='$facility_id' ";
        } elseif ($access_level == "Partner") {
            $user_sql .= "AND  d.partner_id ='$partner_id' ";
        } else {
        }
        $user_sql .= " AND a.id = b.`broadcast_id` AND b.`clnt_usr_id` = c.`id` AND c.`facility_id` = d.`mfl_code` AND b.status='Active' and b.recepient_type='User' GROUP BY a.`id` ";


        $data['sub_counties'] = $this->data->commonGet($sub_counties);

        $data['groupings'] = $this->data->commonGet($groupings);
        $data['times'] = $this->data->commonGet($time);
        $data['langauges'] = $this->data->commonGet($languages);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['broadcasts'] = $this->db->query($sql)->result();
        $data['user_broadcasts'] = $this->db->query($user_sql)->result();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/broadcast_report');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    public function bulk_add_counties()
    {
        $objPHPExcel = new PHPExcel_Reader_CSV();
    }

    public function csv()
    {
        if (isset($_FILES['userfile'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = '1000';

            $this->load->library('upload', $config);

            // If upload failed, display error
            if (!$this->upload->do_upload()) {
                $data = array('error' => $this->upload->display_errors());
                $data['result'] = $this->upload_csv->get_results();
                $this->load->view('upload_results', $data);
            } else {
                $file_data = $this->upload->data();
                $file_path = './uploads/' . $file_data['file_name'];

                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $notification_msg = '';
                    $notification_type = '';
                    $dntn_id = '';
                    foreach ($csv_array as $row) {
                        $donation_id = $row['SID'];
                        $check_donation_id_existence = $this->db->query("Select donation_id from result where donation_id='$donation_id'")->num_rows();
                        //print_r($check_donation_id_existence);
                        if ($check_donation_id_existence <= 0) {
                            $insert_data = array(
                                'donation_id' => $row['SID'],
                                'hepatitisb' => $row['CODE 1'],
                                'hepatitisc' => $row['CODE 2'],
                                'hiv' => $row['CODE 3'],
                                'syphilis' => $row['CODE 4'],
                            );
                            $this->upload_csv->insert_csv($insert_data);
                            $notification_type = 'success';
                            $notification_msg .= $this->session->set_flashdata('success', 'Data Imported Succesfully');
                        } elseif ($check_donation_id_existence >= 1) {
                            //echo 'Donation id : ' . $check_donation_id_existence . '<br>';
                            $dntn_id .= $row['SID'] . ', ';
                            $notification_msg .= $this->session->set_flashdata('success', 'SID: ' . $dntn_id . 'Already exists in the system,Kindly check your data and try again');
                        }
                    }
                    $notify;

                    redirect(base_url() . 'home/csv');
                } else {
                    $data['error'] = "Error occured,Please Try Again!";
                }
            }
        } else {
            $data['result'] = $this->upload_csv->get_results();
            $data['error'] = '';    //initialize image upload error array to empty

            $this->load->view('upload_results', $data);
        }
    }

    public function importcsv()
    {
        $data['result'] = $this->upload_csv->get_results();
        $data['error'] = '';    //initialize image upload error array to empty

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';

        $this->load->library('upload', $config);
        //echo 'One';
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('upload_results', $error);
        } else {
            $file_data = $this->upload->data();
            $file_path = './uploads/' . $file_data['file_name'];

            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                foreach ($csv_array as $row) {
                    $insert_data = array(
                        'donation_id' => $row['SID'],
                        'hepatitisb' => $row['CODE 1'],
                        'hepatitisc' => $row['CODE 2'],
                        'hiv' => $row['CODE 3'],
                        'syphilis' => $row['CODE 4'],
                    );
                    $this->upload_csv->insert_csv($insert_data);
                }
                $this->session->set_flashdata('success', 'Data Imported Succesfully');
                redirect(base_url() . 'home/csv');

                //echo "<pre>"; print_r($insert_data);
            } else {
                $data['error'] = "Error occured,Please Try Again!";
                // $this->load->view('upload_results', $data);
            }
        }
    }

    public function sum_appointment_status()
    {
        $query = $this->db->query("SELECT COUNT(id) as value , tbl_appointment.`app_status`  from tbl_appointment where app_status IN (SELECT notification_type from tbl_notification_flow where notification_type !='Other') ")->result();
        echo json_encode($query);
    }

    public function chart()
    {
        $this->load->view("chart");
    }

    public function date_converter()
    {
        //$query = $this->db->get_where('client',array());
        $query = $this->db->query("Select * from tbl_client where birth_date ");

        foreach ($query->result() as $row) {
            $dob = $row->dob;
            $client_id = $row->id;

            $date = str_replace('/', '-', $dob);
            $new_date = date('Y-m-d', strtotime($date));
            if ($new_date == "1970-01-01") {
            } else {
                echo 'Old DoB : ' . $dob . '   New DoB : ' . $new_date . '</br>';
                $data_update = array(
                    'birth_date' => $new_date
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $data_update);
            }
        }
    }

    public function age_group_calculator()
    {
        //$get_client = $this->db->get('client');
        $query = $this->db->query("SELECT *,
  CASE
    WHEN DATEDIFF(NOW(), birth_date) / 365.25 BETWEEN 0 AND  9 THEN 'Paeds'
    WHEN DATEDIFF(NOW(), birth_date) / 365.25 BETWEEN 10 AND 19 THEN 'Adolescents'
        ELSE 'Adults'
  END AS age_group
FROM tbl_client where age_group IS NOT NULL")->result();
        foreach ($query as $value) {
            $client_id = $value->id;
            $age_group = $value->age_group;
            $data_array = array(
                'age_group' => $age_group
            );
            $this->db->where('id', $client_id);
            $this->db->update('client', $data_array);
        }
    }

    public function client_grouping_calculator()
    {
        $sql = $this->db->query("Select * from tbl_client ")->result();
        foreach ($sql as $value) {
            $client_id = $value->id;
            $dob = $value->dob;
            $current_grouping = $value->group_id;
            echo 'DOB => ' . $dob . '<br>';
            $dob = str_replace('/', '-', $dob);
            $dob = date("Y-m-d", strtotime($dob));

            $current_date = date("Y-m-d");
            $current_date = date_create($current_date);
            $new_dob = date_create($dob);
            $date_diff = date_diff($new_dob, $current_date);
            $diff = $date_diff->format("%R%a days");
            //echo 'Days difference => ' . $diff . '<br>';
            $diff = substr($diff, 0);
            $diff = (int) $diff;
            // echo ($diff);
            $category = "";
            if ($diff >= 3650 and $diff <= 6935) {
                //Adolescent
                $category .= 2;
            } elseif ($diff >= 7300) {
                //Adult
                $category .= 1;
            }
            $current_grouping = (int) $current_grouping;
            $category = (int) $category;
            if (strcmp($category, $current_grouping) === 0) {
            } else {
                echo 'New Category => ' . $category . '</br>';

                $this->db->trans_start();

                $data_update = array(
                    'group_id' => $category
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $data_update);

                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                } else {
                }
            }
        }
    }

    public function consented_clients()
    {
        $county_id = $this->uri->segment(3);
        $sub_county_id = $this->uri->segment(4);
        $mfl_code = $this->uri->segment(5);
        $date_from = $this->uri->segment(6);
        $date_to = $this->uri->segment(7);
        $json = $this->uri->segment(8);


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

    public function consent_report()
    {
        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        //// $this->output->enable_profiler(TRUE);


        if ($access_level == "Donor") { //Donor level access
            $clients = array(
                'select' => 'tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,master_facility.name as facility,county.name as county, sub_county.name as sub_county , '
                    . 'client.clinic_number,client.client_status ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id',
                    'partner_facility' => 'partner_facility.mfl_code = client.mfl_code',
                    'master_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'master_facility.county_id = county.id',
                    'sub_county' => 'master_facility.sub_county_id = sub_county.id'
                ),
                'where' => array('client.status' => 'Active', 'client.smsenable' => 'Yes'),
                'order' => array('enrollment_date' => 'DESC')
            );

            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active')
            );
        } elseif ($access_level == "Partner") { //Partner level access
            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,master_facility.name as facility,county.name as county, sub_county.name as sub_county , '
                    . 'client.clinic_number,client.client_status ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'master_facility' => 'master_facility.code = partner_facility.mfl_code', 'county' => 'master_facility.county_id = county.id', 'sub_county' => 'master_facility.sub_county_id = sub_county.name'),
                'where' => array('client.status' => 'Active', 'client.smsenable' => 'Yes', 'client.partner_id' => $partner_id),
                'order' => array('enrollment_date' => 'DESC')
            );

            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id)
            );
        } elseif ($access_level == "Facility") {
            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id, 'partner_facility.mfl_code' => $facility_id)
            );

            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.clinic_number,client.client_status  ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array('gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id', 'groups' => 'groups.id = client.group_id', 'gender' => 'gender.id = client.gender'),
                'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id, 'client.smsenable' => 'Yes'),
                'order' => array('enrollment_date' => 'DESC')
            );
        } else {
            $clients = array(
                'select' => ' tbl_client.file_no, groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,master_facility.name as facility,county.name as county, sub_county.name as sub_county , '
                    . 'client.clinic_number,client.client_status ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,'
                    . 'client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender', 'marital_status' => 'marital_status.id = client.marital', 'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id', 'partner_facility' => 'partner_facility.mfl_code = client.mfl_code', 'master_facility' => 'master_facility.code = partner_facility.mfl_code',
                    'county' => 'master_facility.county_id = county.id', 'sub_county' => 'master_facility.sub_county_id = sub_county.id'
                ),
                'where' => array('client.status' => 'Active', 'client.smsenable' => 'Yes'),
                'order' => array('enrollment_date' => 'DESC')
            );


            $facilities = array(
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'master_facility.code = partner_facility.mfl_code'),
                'where' => array('partner_facility.status' => 'Active')
            );
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
        $data['clients'] = $this->data->commonGet($clients);
        $data['facilities'] = $this->data->commonGet($facilities);
        $data['maritals'] = $this->data->commonGet($maritals);
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);








        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/consent_report');
            } else {
                $this->load->template('Home/consent_report');

                // echo 'Unauthorised Access';
                //exit();
            }
        }
    }

    public function approve_broadcast()
    {
        $broadcast_id = $this->uri->segment(3);


        $transaction = $this->data->approve_broadcast($broadcast_id);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function disapprove_broadcast()
    {
        $broadcast_id = $this->uri->segment(3);

        $reason = $this->input->post('reason');
        $transaction = $this->data->disapprove_broadcast($broadcast_id, $reason);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function add_appointment()
    {
        $transaction = $this->data->add_appointment();
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function trial_function()
    {
        $get_no_scheduled_appointments_clients = $this->db->query("  SELECT 
  COUNT(
    DISTINCT tbl_appointment.`client_id`
  ) AS scheduled_appointments,
  DATE_FORMAT(`appntmnt_date`, '%M %Y') AS APPOINTMENT_MONTH,
  DATE_FORMAT(`appntmnt_date`, '%M') AS MONTH 
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.`id` = tbl_appointment.`client_id` 
GROUP BY APPOINTMENT_MONTH 
ORDER BY `appntmnt_date` ASC ")->result();
        $x = 'C';
        foreach ($get_no_scheduled_appointments_clients as $value) {
            $scheduled_appointments = $value->scheduled_appointments;
            $appointment_month = $value->MONTH;
            $cell_value = $x . '5';
            echo 'Cell Value => ' . $cell_value . '<br>';


            $x++;
        }
    }

    public function download_defaulter_register()
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');


        if ($access_level == 'Facility') {
            $facility_name = '';
            $mfl_code = '';
            $sub_county = '';
            $county = '';
            $start_date = date('d-m-Y');

            $get_facility_details = "Select * from vw_facility_list where mfl_code='$facility_id'";
            $query_facility = $this->db->query($get_facility_details)->result();
            foreach ($query_facility as $value) {
                $facility_name .= $value->facility_name;
                $mfl_code .= $value->mfl_code;
                $sub_county .= $value->sub_county;
                $county .= $value->county;
            }

            //echo 'Values => '.$facility_name.$mfl_code.$sub_county.$county;
            //load our new PHPExcel library
            $this->load->library('excel');

            $file = './files/Defaulter_tracing_register.xlsx';

            //read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($file);

            $objPHPExcel->setActiveSheetIndexByName('Cover Page');

            $objPHPExcel->getActiveSheet()->setCellValue('C6', $facility_name);
            $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C7', $mfl_code);
            $objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C8', $sub_county);
            $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C9', $county);
            $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C10', $start_date);
            $objPHPExcel->getActiveSheet()->getStyle('C10')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C10')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



            $objPHPExcel->setActiveSheetIndexByName('Montlhy summaries');

            $get_no_scheduled_appointments_clients = $this->db->query("  SELECT 
                    
      COUNT(
        DISTINCT tbl_appointment.`client_id`
      ) AS scheduled_appointments,
      DATE_FORMAT(`appntmnt_date`, '%M %Y') AS APPOINTMENT_MONTH,
      DATE_FORMAT(`appntmnt_date`, '%M') AS MONTH 
    FROM
      tbl_appointment 
      INNER JOIN tbl_client 
        ON tbl_client.`id` = tbl_appointment.`client_id` WHERE tbl_client.mfl_code='$facility_id' AND visit_type='Scheduled'
    GROUP BY APPOINTMENT_MONTH 
    ORDER BY `appntmnt_date` ASC ")->result();
            $x = 'C';
            foreach ($get_no_scheduled_appointments_clients as $value) {
                $scheduled_appointments = $value->scheduled_appointments;
                $appointment_month = $value->MONTH;
                $scheduled_appointments_cell = $x . '5';
                $month_cell = $x . '3';
                $month = $objPHPExcel->getActiveSheet()->getCell($month_cell)->getValue();

                if ($appointment_month == "January") {
                    $objPHPExcel->getActiveSheet()->setCellValue('C5', $scheduled_appointments);
                } elseif ($appointment_month == "February") {
                    $objPHPExcel->getActiveSheet()->setCellValue('D5', $scheduled_appointments);
                } elseif ($appointment_month == "March") {
                    $objPHPExcel->getActiveSheet()->setCellValue('E5', $scheduled_appointments);
                } elseif ($appointment_month == "April") {
                    $objPHPExcel->getActiveSheet()->setCellValue('F5', $scheduled_appointments);
                } elseif ($appointment_month == "May") {
                    $objPHPExcel->getActiveSheet()->setCellValue('G5', $scheduled_appointments);
                } elseif ($appointment_month == "June") {
                    $objPHPExcel->getActiveSheet()->setCellValue('H5', $scheduled_appointments);
                } elseif ($appointment_month == "July") {
                    $objPHPExcel->getActiveSheet()->setCellValue('I5', $scheduled_appointments);
                } elseif ($appointment_month == "August") {
                    $objPHPExcel->getActiveSheet()->setCellValue('J5', $scheduled_appointments);
                } elseif ($appointment_month == "September") {
                    $objPHPExcel->getActiveSheet()->setCellValue('K5', $scheduled_appointments);
                } elseif ($appointment_month == "October") {
                    $objPHPExcel->getActiveSheet()->setCellValue('L5', $scheduled_appointments);
                } elseif ($appointment_month == "November") {
                    $objPHPExcel->getActiveSheet()->setCellValue('M5', $scheduled_appointments);
                } elseif ($appointment_month == "December") {
                    $objPHPExcel->getActiveSheet()->setCellValue('N5', $scheduled_appointments);
                }



                $x++;
            }








            $get_defaulted_appointments_summary = $this->db->query("  SELECT 
  COUNT(
    DISTINCT tbl_appointment.`client_id`
  ) AS appointments_summary,
  DATE_FORMAT(`appntmnt_date`, '%M %Y') AS APPOINTMENT_MONTH,
  DATE_FORMAT(`appntmnt_date`, '%M') AS MONTH 
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.`id` = tbl_appointment.`client_id` 
WHERE tbl_appointment.`active_app` = '1' 
  AND tbl_appointment.`appntmnt_date` < CURDATE() AND tbl_client.mfl_code = '$facility_id'
GROUP BY APPOINTMENT_MONTH 
ORDER BY `appntmnt_date` ASC ")->result();
            $x = 'C';
            foreach ($get_defaulted_appointments_summary as $value) {
                $appointments_summary = $value->appointments_summary;
                $missed_month = $value->MONTH;
                $miissed_appointments_cell = $x . '6';
                $month_cell = $x . '3';
                $month = $objPHPExcel->getActiveSheet()->getCell($month_cell)->getValue();

                if ($missed_month == "January") {
                    $objPHPExcel->getActiveSheet()->setCellValue('C6', $appointments_summary);
                } elseif ($missed_month == "February") {
                    $objPHPExcel->getActiveSheet()->setCellValue('D6', $appointments_summary);
                } elseif ($missed_month == "March") {
                    $objPHPExcel->getActiveSheet()->setCellValue('E6', $appointments_summary);
                } elseif ($missed_month == "April") {
                    $objPHPExcel->getActiveSheet()->setCellValue('F6', $appointments_summary);
                } elseif ($missed_month == "May") {
                    $objPHPExcel->getActiveSheet()->setCellValue('G6', $appointments_summary);
                } elseif ($missed_month == "June") {
                    $objPHPExcel->getActiveSheet()->setCellValue('H6', $appointments_summary);
                } elseif ($missed_month == "July") {
                    $objPHPExcel->getActiveSheet()->setCellValue('I6', $appointments_summary);
                } elseif ($missed_month == "August") {
                    $objPHPExcel->getActiveSheet()->setCellValue('J6', $appointments_summary);
                } elseif ($missed_month == "September") {
                    $objPHPExcel->getActiveSheet()->setCellValue('K6', $appointments_summary);
                } elseif ($missed_month == "October") {
                    $objPHPExcel->getActiveSheet()->setCellValue('L6', $appointments_summary);
                } elseif ($missed_month == "November") {
                    $objPHPExcel->getActiveSheet()->setCellValue('M6', $appointments_summary);
                } elseif ($missed_month == "December") {
                    $objPHPExcel->getActiveSheet()->setCellValue('N6', $appointments_summary);
                }



                $x++;
            }




            $get_clnt_ountcome_summary = $this->db->query("  SELECT 
  COUNT(
    DISTINCT `tbl_clnt_outcome`.`id`
  ) AS tracing_outcome,
  DATE_FORMAT(`appntmnt_date`, '%M %Y') AS APPOINTMENT_MONTH,
  DATE_FORMAT(`appntmnt_date`, '%M') AS MONTH 
FROM
  tbl_clnt_outcome 
  INNER JOIN tbl_appointment 
    ON tbl_appointment.`id` = tbl_clnt_outcome.`appointment_id` 
  INNER JOIN tbl_client 
    ON tbl_client.`id` = tbl_clnt_outcome.`client_id` 
WHERE tbl_appointment.`active_app` = '1' 
  AND tbl_appointment.`appntmnt_date` < CURDATE() AND tbl_client.mfl_code='$facility_id'
GROUP BY `APPOINTMENT_MONTH` 
ORDER BY `appntmnt_date` ASC ")->result();
            $x = 'C';
            foreach ($get_clnt_ountcome_summary as $value) {
                $tracing_outcome = $value->tracing_outcome;
                $tracing_month = $value->MONTH;
                $miissed_appointments_cell = $x . '6';
                $month_cell = $x . '3';
                $month = $objPHPExcel->getActiveSheet()->getCell($month_cell)->getValue();

                if ($tracing_month == "January") {
                    $objPHPExcel->getActiveSheet()->setCellValue('C7', $tracing_outcome);
                } elseif ($tracing_month == "February") {
                    $objPHPExcel->getActiveSheet()->setCellValue('D7', $tracing_outcome);
                } elseif ($tracing_month == "March") {
                    $objPHPExcel->getActiveSheet()->setCellValue('E7', $tracing_outcome);
                } elseif ($tracing_month == "April") {
                    $objPHPExcel->getActiveSheet()->setCellValue('F7', $tracing_outcome);
                } elseif ($tracing_month == "May") {
                    $objPHPExcel->getActiveSheet()->setCellValue('G7', $tracing_outcome);
                } elseif ($tracing_month == "June") {
                    $objPHPExcel->getActiveSheet()->setCellValue('H7', $tracing_outcome);
                } elseif ($tracing_month == "July") {
                    $objPHPExcel->getActiveSheet()->setCellValue('I7', $tracing_outcome);
                } elseif ($tracing_month == "August") {
                    $objPHPExcel->getActiveSheet()->setCellValue('J7', $tracing_outcome);
                } elseif ($missed_month == "September") {
                    $objPHPExcel->getActiveSheet()->setCellValue('K7', $tracing_outcome);
                } elseif ($tracing_month == "October") {
                    $objPHPExcel->getActiveSheet()->setCellValue('L7', $tracing_outcome);
                } elseif ($tracing_month == "November") {
                    $objPHPExcel->getActiveSheet()->setCellValue('M7', $tracing_outcome);
                } elseif ($tracing_month == "December") {
                    $objPHPExcel->getActiveSheet()->setCellValue('N7', $tracing_outcome);
                }



                $x++;
            }



            $get_clnt_returned_to_care = $this->db->query("  SELECT 
  COUNT(
    DISTINCT tbl_appointment.`client_id`
  ) AS returned_clients,
  DATE_FORMAT(`appntmnt_date`, '%M %Y') AS APPOINTMENT_MONTH,
  DATE_FORMAT(`appntmnt_date`, '%M') AS MONTH 
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.`id` = tbl_appointment.`client_id` 
WHERE tbl_appointment.`active_app` = '0' 
  AND tbl_appointment.`appntmnt_date` < CURDATE() AND tbl_client.mfl_code = '$facility_id' AND appointment_kept='No'
GROUP BY APPOINTMENT_MONTH 
ORDER BY `appntmnt_date` ASC ")->result();
            $x = 'C';
            foreach ($get_clnt_returned_to_care as $value) {
                $returned_clients = $value->returned_clients;
                $missed_month = $value->MONTH;
                $miissed_appointments_cell = $x . '6';
                $month_cell = $x . '3';
                $month = $objPHPExcel->getActiveSheet()->getCell($month_cell)->getValue();

                if ($missed_month == "January") {
                    $objPHPExcel->getActiveSheet()->setCellValue('C8', $returned_clients);
                } elseif ($missed_month == "February") {
                    $objPHPExcel->getActiveSheet()->setCellValue('D8', $returned_clients);
                } elseif ($missed_month == "March") {
                    $objPHPExcel->getActiveSheet()->setCellValue('E8', $returned_clients);
                } elseif ($missed_month == "April") {
                    $objPHPExcel->getActiveSheet()->setCellValue('F8', $returned_clients);
                } elseif ($missed_month == "May") {
                    $objPHPExcel->getActiveSheet()->setCellValue('G8', $returned_clients);
                } elseif ($missed_month == "June") {
                    $objPHPExcel->getActiveSheet()->setCellValue('H8', $returned_clients);
                } elseif ($missed_month == "July") {
                    $objPHPExcel->getActiveSheet()->setCellValue('I8', $returned_clients);
                } elseif ($missed_month == "August") {
                    $objPHPExcel->getActiveSheet()->setCellValue('J8', $returned_clients);
                } elseif ($missed_month == "September") {
                    $objPHPExcel->getActiveSheet()->setCellValue('K8', $returned_clients);
                } elseif ($missed_month == "October") {
                    $objPHPExcel->getActiveSheet()->setCellValue('L8', $returned_clients);
                } elseif ($missed_month == "November") {
                    $objPHPExcel->getActiveSheet()->setCellValue('M8', $returned_clients);
                } elseif ($missed_month == "December") {
                    $objPHPExcel->getActiveSheet()->setCellValue('N8', $returned_clients);
                }



                $x++;
            }









            $objPHPExcel->setActiveSheetIndexByName('Register');
            $defaulter_clients = $this->db->query("SELECT * FROM vw_defaulter_tracing_register_clients where mfl_code='$facility_id'")->result();
            $first_column = 6;
            $other_column = 4;
            $i = 1;
            $cell_no = 4;
            $upper_column = 7;
            $lower_column = 9;
            foreach ($defaulter_clients as $value) {
                $clinic_number = $value->clinic_number;
                $client_name = $value->client_name;

                $age = $value->age;
                $sex = $value->sex;
                $enrollment_date = $value->enrollment_date;
                $stable = $value->stable;
                $art_date = $value->art_date;
                $art = $value->art;
                $art_cohort_month = $value->art_cohort_month;
                $date_of_missed_appointment = $value->date_of_missed_appointment;
                $client_phone_no = $value->client_phone_no;
                $treatment_supporter_name = $value->treatment_supporter_name;
                $treatment_supporter_no = $value->treatment_supporter_no;
                $physical_address = $value->physical_address;
                $mfl_code = $value->mfl_code;
                if ($i == 1) {
                    $objPHPExcel->getActiveSheet()->setCellValue("A$first_column", $i);
                    $first_column++;
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue("A$first_column", $i);
                    $first_column + 4;
                }

                $i++;
                if ($upper_column == 7 and $lower_column == 9) {
                } else {
                    $upper_column = $upper_column + 3;
                    $lower_column = $lower_column + 3;
                }


                $objPHPExcel->getActiveSheet()->setCellValue("B$upper_column", $client_name);
                $objPHPExcel->getActiveSheet()->setCellValue("B$lower_column", $clinic_number);
                $objPHPExcel->getActiveSheet()->setCellValue("C$upper_column", $art);
                $objPHPExcel->getActiveSheet()->setCellValue("C$lower_column", $sex);
                $objPHPExcel->getActiveSheet()->setCellValue("D$upper_column", $stable);
                $objPHPExcel->getActiveSheet()->setCellValue("D$lower_column", $age);
                $objPHPExcel->getActiveSheet()->setCellValue("E$upper_column", $enrollment_date);
                $objPHPExcel->getActiveSheet()->setCellValue("E$lower_column", $art_cohort_month);
                $objPHPExcel->getActiveSheet()->setCellValue("F$upper_column", $date_of_missed_appointment);
                $objPHPExcel->getActiveSheet()->setCellValue("F$lower_column", $client_phone_no);
                $objPHPExcel->getActiveSheet()->setCellValue("G$upper_column", $treatment_supporter_name);
                $objPHPExcel->getActiveSheet()->setCellValue("G$lower_column", $treatment_supporter_no);
                $objPHPExcel->getActiveSheet()->setCellValue("H$upper_column", $physical_address);

                $upper_column++;
                $lower_column++;
            }





























            $filename = 'Defaulters_Register.xlsx'; //save our workbook as this file name
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            //force user to download the Excel file without writing it to server's HD
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 2017 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }
    }

    public function download_app_diary()
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');


        if ($access_level == 'Facility') {
            $facility_name = '';
            $mfl_code = '';
            $sub_county = '';
            $county = '';
            $start_date = date('d-m-Y');

            $get_facility_details = "Select * from vw_facility_list where mfl_code='$facility_id'";
            $query_facility = $this->db->query($get_facility_details)->result();
            foreach ($query_facility as $value) {
                $facility_name .= $value->facility_name;
                $mfl_code .= $value->mfl_code;
                $sub_county .= $value->sub_county;
                $county .= $value->county;
            }



            //load our new PHPExcel library
            $this->load->library('excel');

            $file = './files/Care_Treatment_Diary.xlsx';

            //read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($file);

            $objPHPExcel->setActiveSheetIndexByName('Cover Page');
            //$objPHPExcel->getActiveSheet()->setTitle('Test Appointment Diary');
            $objPHPExcel->getActiveSheet()->setCellValue('C6', $facility_name);
            $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C7', $mfl_code);
            $objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C8', $sub_county);
            $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C9', $county);
            $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C9')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->setCellValue('C10', $start_date);
            $objPHPExcel->getActiveSheet()->getStyle('C10')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('C10')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->setActiveSheetIndexByName('Revised-C&T-Diary');
            $scheduled_visits = $this->db->query("SELECT clinic_number,client_name,gender,age,appointment_attended,status,stable,DATE_FORMAT(date_attended,'%d/%m/%Y') as date_attended,DATE_FORMAT(next_re_fill_appointment,'%d/%m/%Y') as next_re_fill_appointment ,DATE_FORMAT(next_clinical_appointment,'%d/%m/%Y') as next_clinical_appointment FROM vw_scheduled_appointments where mfl_code='$facility_id'")->result();
            $first_column = 4;
            $second_column = 4;
            foreach ($scheduled_visits as $value) {
                $clinic_number = $value->clinic_number;
                $client_name = $value->client_name;
                $gender = $value->gender;
                $age = $value->age;
                $appointment_attended = $value->appointment_attended;
                $status = $value->status;
                $stable = $value->stable;
                $date_attended = $value->date_attended;
                $next_re_fill_appointment = $value->next_re_fill_appointment;
                $next_clinical_appointment = $value->next_clinical_appointment;


                if ($first_column <= 43) {
                    $objPHPExcel->getActiveSheet()->setCellValue("B$first_column", $clinic_number);
                    $objPHPExcel->getActiveSheet()->setCellValue("C$first_column", $client_name);
                    $objPHPExcel->getActiveSheet()->setCellValue("D$first_column", $gender);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$first_column", $age);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$first_column", $appointment_attended);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$first_column", $stable);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$first_column", $date_attended);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$first_column", $next_re_fill_appointment);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$first_column", $next_clinical_appointment);
                    $first_column++;
                } elseif ($first_column > 43 and $second_column <= 43) {
                    $objPHPExcel->getActiveSheet()->setCellValue("L$second_column", $clinic_number);
                    $objPHPExcel->getActiveSheet()->setCellValue("M$second_column", $client_name);
                    $objPHPExcel->getActiveSheet()->setCellValue("N$second_column", $gender);
                    $objPHPExcel->getActiveSheet()->setCellValue("O$second_column", $age);
                    $objPHPExcel->getActiveSheet()->setCellValue("P$second_column", $appointment_attended);
                    $objPHPExcel->getActiveSheet()->setCellValue("Q$second_column", $stable);
                    $objPHPExcel->getActiveSheet()->setCellValue("R$second_column", $date_attended);
                    $objPHPExcel->getActiveSheet()->setCellValue("S$second_column", $next_re_fill_appointment);
                    $objPHPExcel->getActiveSheet()->setCellValue("T$second_column", $next_clinical_appointment);
                    $second_column++;
                }
            }



            $defaulter_booking_visits = $this->db->query("SELECT * FROM vw_defaulter_bookings_visits where mfl_code='$facility_id'")->result();
            $third_column = 46;
            $fourth_column = 46;
            foreach ($defaulter_booking_visits as $value) {
                $clinic_number = $value->clinic_number;
                $client_name = $value->client_name;
                $gender = $value->gender;
                $age = $value->age;
                $purpose_of_visit = $value->purpose_of_visit;
                $status = $value->status;
                $stable = $value->stable;

                $missed_appointment_date = $value->missed_appointment_date;
                $fnl_outcome_dte = $value->fnl_outcome_dte;
                $next_clinical_appointment_date = $value->next_clinical_appointment_date;


                if ($third_column <= 55) {
                    $objPHPExcel->getActiveSheet()->setCellValue("B$third_column", $clinic_number);
                    $objPHPExcel->getActiveSheet()->setCellValue("C$third_column", $client_name);
                    $objPHPExcel->getActiveSheet()->setCellValue("D$third_column", $gender);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$third_column", $age);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$third_column", $purpose_of_visit);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$third_column", $stable);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$third_column", $missed_appointment_date);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$third_column", $fnl_outcome_dte);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$third_column", $next_clinical_appointment_date);
                    $third_column++;
                } elseif ($third_column > 55 and $fourth_column <= 55) {
                    $objPHPExcel->getActiveSheet()->setCellValue("L$fourth_column", $clinic_number);
                    $objPHPExcel->getActiveSheet()->setCellValue("M$fourth_column", $client_name);
                    $objPHPExcel->getActiveSheet()->setCellValue("N$fourth_column", $gender);
                    $objPHPExcel->getActiveSheet()->setCellValue("O$fourth_column", $age);
                    $objPHPExcel->getActiveSheet()->setCellValue("P$fourth_column", $appointment_attended);
                    $objPHPExcel->getActiveSheet()->setCellValue("Q$fourth_column", $stable);
                    $objPHPExcel->getActiveSheet()->setCellValue("R$fourth_column", $missed_appointment_date);
                    $objPHPExcel->getActiveSheet()->setCellValue("S$fourth_column", $fnl_outcome_dte);
                    $objPHPExcel->getActiveSheet()->setCellValue("T$fourth_column", $next_clinical_appointment_date);
                    $fourth_column++;
                }
            }




            $unscheduled_visits = $this->db->query("SELECT * FROM vw_unscheduled_appointments where mfl_code='$facility_id'")->result();
            $fifth_column = 58;
            $sixth_column = 58;
            foreach ($unscheduled_visits as $value) {
                $clinic_number = $value->clinic_number;
                $client_name = $value->client_name;
                $gender = $value->gender;
                $age = $value->age;
                $purpose_of_visit = $value->expliain_app;

                $stable = $value->stable;

                $date_booked = $value->date_booked;
                $next_re_fill_appointment = $value->next_re_fill_appointment;

                $next_clinical_appointment = $value->next_clinical_appointment;


                if ($fifth_column <= 67) {
                    $objPHPExcel->getActiveSheet()->setCellValue("B$fifth_column", $clinic_number);
                    $objPHPExcel->getActiveSheet()->setCellValue("C$fifth_column", $client_name);
                    $objPHPExcel->getActiveSheet()->setCellValue("D$fifth_column", $gender);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$fifth_column", $age);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$fifth_column", $purpose_of_visit);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$fifth_column", $stable);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$fifth_column", $date_booked);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$fifth_column", $next_re_fill_appointment);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$fifth_column", $next_clinical_appointment);
                    $fifth_column++;
                } elseif ($fifth_column > 67 and $sixth_column <= 67) {
                    $objPHPExcel->getActiveSheet()->setCellValue("L$sixth_column", $clinic_number);
                    $objPHPExcel->getActiveSheet()->setCellValue("M$sixth_column", $client_name);
                    $objPHPExcel->getActiveSheet()->setCellValue("N$sixth_column", $gender);
                    $objPHPExcel->getActiveSheet()->setCellValue("O$sixth_column", $age);
                    $objPHPExcel->getActiveSheet()->setCellValue("P$sixth_column", $appointment_attended);
                    $objPHPExcel->getActiveSheet()->setCellValue("Q$sixth_column", $stable);
                    $objPHPExcel->getActiveSheet()->setCellValue("R$sixth_column", $date_booked);
                    $objPHPExcel->getActiveSheet()->setCellValue("S$sixth_column", $next_re_fill_appointment);
                    $objPHPExcel->getActiveSheet()->setCellValue("T$sixth_column", $next_clinical_appointment);
                    $sixth_column++;
                }
            }

            $scheduled_visits_attended = $this->db->query("SELECT SUM(no_of_appointments) AS no_of_appointments,mfl_code FROM VW_SCHEDULED_VISITS_ATTENDED where mfl_code='$facility_id' group by mfl_code")->result();


            $vw_missed_appointments = $this->db->query("SELECT SUM(no_of_appointments) AS no_of_appointments,mfl_code FROM `vw_missed_appointments` where mfl_code='$facility_id' group by mfl_code ")->result();
            $vw_unscheduled_appointments = $this->db->query("SELECT SUM(no_of_appointments) AS no_of_appointments,mfl_code FROM vw_unscheduled_vists where mfl_code='$facility_id' group by mfl_code ")->result();
            $clinical_missed_appointment = $this->db->query("SELECT SUM(no_of_appointments) AS no_of_appointments,mfl_code FROM vw_missed_clinical_appointments where mfl_code='$facility_id' group by mfl_code")->result();
            // $clinical_missed_appointment = "";
            $booked_attended_clinic = $this->db->query(" SELECT SUM(no_of_appointments) AS no_of_appointments,mfl_code FROM vw_scheduled_visits_attended where mfl_code='$facility_id' GROUP BY mfl_code ")->result();

            $scheduled_arv_pick = $this->db->query("Select SUM(no_of_appointments) AS no_of_appointments,mfl_code from vw_scheduled_Refill_appointments where mfl_code='$facility_id' GROUP BY mfl_code")->result();
            $missed_arv_pick = $this->db->query("SELECT SUM(no_of_appointments) AS no_of_appointments,mfl_code FROM vw_missed_Refill_appointments where mfl_code='$facility_id' GROUP BY mfl_code")->result();
            $unscheduled_arv = $this->db->query(" SELECT SUM(no_of_appointments) AS no_of_appointments,mfl_code FROM VW_UNSCHEDULED_REFILLS where mfl_code='$mfl_code' GROUP BY mfl_code ")->result();


            foreach ($scheduled_visits_attended as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("C72", $no_of_appointments);
            }
            foreach ($vw_missed_appointments as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("C73", $no_of_appointments);
            }
            foreach ($vw_unscheduled_appointments as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("C74", $no_of_appointments);
            }

            foreach ($clinical_missed_appointment as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("I73", $no_of_appointments);
            }
            foreach ($booked_attended_clinic as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("I72", $no_of_appointments);
            }
            foreach ($scheduled_arv_pick as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("N72", $no_of_appointments);
            }
            foreach ($unscheduled_arv as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("N74", $no_of_appointments);
            }
            foreach ($missed_arv_pick as $value) {
                $no_of_appointments = $value->no_of_appointments;
                $objPHPExcel->getActiveSheet()->setCellValue("N73", $no_of_appointments);
            }


            $filename = 'Care_Treatment_Diary_2018.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
        }
    }

    public function appointment_diary()
    {
        $type = $this->uri->segment(3);
        if ($type === 'id') {
            $this->get_current_appointments();
        } else {
            $partner_id = $this->session->userdata('partner_id');
            $county_id = $this->session->userdata('county_id');
            $sub_county_id = $this->session->userdata('subcounty_id');
            $facility_id = $this->session->userdata('facility_id');
            $access_level = $this->session->userdata('access_level');



            if ($access_level == "Partner") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.partner_id' => $partner_id)
                );

                $query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "   tbl_  no_calls,no_msgs,home_visits,tbl_appointment.id as appointment_id,tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id  INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1"
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.partner_id='$partner_id' AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1'   ";


                $scheduled_visits = "SELECT * FROM vw_scheduled_appointments where partner_id='$partner_id' group by partner_id";
                $un_scheduled_visits = "SELECT * FROM vw_unscheduled_appointments where partner_id='$partner_id' group by partner_id";
                $defaulter_booking_visits = "SELECT * FROM vw_defaulter_bookings_visits where partner_id='$partner_id' group by partner_id";
                $scheduled_visits_attended = "SELECT * FROM VW_SCHEDULED_VISITS_ATTENDED where partner_id='$partner_id' group by partner_id";
                $vw_missed_appointments = "SELECT * FROM `vw_missed_appointments` where partner_id='$partner_id' group by partner_id ";
                $vw_unscheduled_appointments = "SELECT * FROM vw_unscheduled_vists where partner_id='$partner_id' group by partner_id ";
                $booked_attended_clinic = "  SELECT * FROM vw_scheduled_visits_attended;";
                $clinical_missed_appointment = "SELECT * FROM vw_missed_clinical_appointments";
                $scheduled_arv_pick = "Select * from vw_scheduled_Refill_appointments";
                $missed_arv_pick = "SELECT * FROM vw_missed_Refill_appointments";
                $unscheduled_arv = " SELECT * FROM VW_UNSCHEDULED_REFILLS";
            } elseif ($access_level == "County") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
                );



                $query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "   tbl_  no_calls,no_msgs,home_visits,tbl_appointment.id as appointment_id ,tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_Type from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code INNER JOIN tbl_appointment_types ON tbl_appointment_types.id = tbl_appointment.app_type_i "
                    . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.county_id='$county_id'  AND tbl_appointment.appntmnt_date = CURDATE()  and active_app='1'   ";


                $scheduled_visits = "SELECT * FROM vw_scheduled_appointments";
                $un_scheduled_visits = "SELECT * FROM vw_unscheduled_appointments";
                $defaulter_booking_visits = "SELECT * FROM vw_defaulter_bookings_visits";
                $scheduled_visits_attended = "SELECT * FROM VW_SCHEDULED_VISITS_ATTENDED where county_id='$county_id' group by county_id";
                $vw_missed_appointments = "SELECT * FROM `vw_missed_appointments` where county_id='$county_id' group by county_id ";
                $vw_unscheduled_appointments = "SELECT * FROM vw_unscheduled_vists where county_id='$county_id' group by county_id ";
                $clinical_missed_appointment = "SELECT * FROM vw_missed_clinical_appointments where county_id='$county_id' group by county_id ";
                $booked_attended_clinic = "  SELECT * FROM vw_scheduled_visits_attended;";

                $scheduled_arv_pick = "Select * from vw_scheduled_Refill_appointments";
                $missed_arv_pick = "SELECT * FROM vw_missed_Refill_appointments";
                $unscheduled_arv = " SELECT * FROM VW_UNSCHEDULED_REFILLS";
            } elseif ($access_level == "Sub County") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
                );



                $query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "   tbl_  no_calls,no_msgs,home_visits,tbl_appointment.id as appointment_id , tbl_appointment_types.id as appointment_types_id , tbl_appointment_types.name as appointment_types  from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                    . " INNER JOIN  tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_partner_facility.sub_county_id='$sub_county_id'  AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1'   ";



                $scheduled_visits = "SELECT * FROM vw_scheduled_appointments";
                $un_scheduled_visits = "SELECT * FROM vw_unscheduled_appointments";
                $defaulter_booking_visits = "SELECT * FROM vw_defaulter_bookings_visits";
                $scheduled_visits_attended = "SELECT * FROM VW_SCHEDULED_VISITS_ATTENDED where sub_county_id='$sub_county_id' group by sub_county_id";
                $vw_missed_appointments = "SELECT * FROM `vw_missed_appointments` where sub_county_id='$county_id' group by sub_county_id ";
                $vw_unscheduled_appointments = "SELECT * FROM vw_unscheduled_vists where sub_county_id='$county_id' group by sub_county_id ";
                $clinical_missed_appointment = "SELECT * FROM vw_missed_clinical_appointments where sub_county_id='$sub_county_id' group by sub_county_id ";

                $booked_attended_clinic = "  SELECT * FROM vw_scheduled_visits_attended;";

                $scheduled_arv_pick = "Select * from vw_scheduled_Refill_appointments";
                $missed_arv_pick = "SELECT * FROM vw_missed_Refill_appointments";
                $unscheduled_arv = " SELECT * FROM VW_UNSCHEDULED_REFILLS";
            } elseif ($access_level == "Facility") {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active', 'client.mfl_code' => $facility_id)
                );



                $query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "   tbl_  no_calls,no_msgs,home_visits,tbl_appointment.id as appointment_id, tbl_appointment_types.id as appointment_types_id , tbl_appointment_types.name as appointment_types from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_client.mfl_code='$facility_id'  AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1'   ";


                $scheduled_visits = "SELECT * FROM vw_scheduled_appointments where mfl_code='$facility_id' ";
                $un_scheduled_visits = "SELECT * FROM vw_unscheduled_appointments where mfl_code='$facility_id' ";
                $defaulter_booking_visits = "SELECT * FROM vw_defaulter_bookings_visits where mfl_code='$facility_id' ";
                $scheduled_visits_attended = "SELECT * FROM VW_SCHEDULED_VISITS_ATTENDED where mfl_code='$facility_id' group by mfl_code";
                $vw_missed_appointments = "SELECT * FROM `vw_missed_appointments` where mfl_code='$facility_id' group by mfl_code ";
                $vw_unscheduled_appointments = "SELECT * FROM vw_unscheduled_vists where mfl_code='$facility_id' group by mfl_code ";
                $clinical_missed_appointment = "SELECT * FROM vw_missed_clinical_appointments where mfl_code='$facility_id' group by mfl_code";
                // $clinical_missed_appointment = "";
                $booked_attended_clinic = "  SELECT * FROM vw_scheduled_visits_attended;";

                $scheduled_arv_pick = "Select * from vw_scheduled_Refill_appointments";
                $missed_arv_pick = "SELECT * FROM vw_missed_Refill_appointments";
                $unscheduled_arv = " SELECT * FROM VW_UNSCHEDULED_REFILLS";
            } else {
                $appointments = array(
                    'table' => 'appointment',
                    'join' => array('client' => 'client.id = appointment.client_id'),
                    'where' => array('client.status' => 'Active')
                );

                $query = "Select tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name ,"
                    . " tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.clinic_number,"
                    . " tbl_client.created_at as created_at,tbl_client.enrollment_date,tbl_client.art_date,tbl_client.updated_at,"
                    . "tbl_client.id as client_id,tbl_client.clinic_number,tbl_client.client_status,tbl_client.txt_frequency,"
                    . " tbl_client.txt_time,tbl_client.alt_phone_no,tbl_client.shared_no_name,tbl_client.smsenable"
                    . " ,tbl_appointment.appntmnt_date,tbl_appointment.app_msg,tbl_appointment.updated_at,"
                    . " tbl_appointment.app_type_1,"
                    . "   tbl_  no_calls,no_msgs,home_visits,tbl_appointment.id as appointment_id , tbl_appointment_types.id as appointment_types_id, tbl_appointment_types.name as appointment_types  from tbl_client"
                    . " INNER JOIN tbl_language ON tbl_language.id = tbl_client.language_id"
                    . " INNER JOIN tbl_groups on tbl_groups.id = tbl_client.group_id"
                    . " INNER JOIN tbl_appointment on tbl_appointment.client_id = tbl_client.id inner join tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
                    . " WHERE tbl_client.status = 'Active' AND tbl_appointment.appntmnt_date = CURDATE() and active_app='1'  ";


                $scheduled_visits = "SELECT * FROM vw_scheduled_appointments";
                $un_scheduled_visits = "SELECT * FROM vw_unscheduled_appointments";
                $defaulter_booking_visits = "SELECT * FROM vw_defaulter_bookings_visits";
                $scheduled_visits_attended = "SELECT * FROM VW_SCHEDULED_VISITS_ATTENDED";
                $vw_missed_appointments = "SELECT * FROM `vw_missed_appointments`  ";
                $vw_unscheduled_appointments = "SELECT * FROM vw_unscheduled_vists ";

                $booked_attended_clinic = "  SELECT * FROM vw_scheduled_visits_attended;";

                $clinical_missed_appointment = "SELECT * FROM vw_missed_clinical_appointments where 1";

                $scheduled_arv_pick = "Select * from vw_scheduled_Refill_appointments";
                $missed_arv_pick = "SELECT * FROM vw_missed_Refill_appointments";
                $unscheduled_arv = " SELECT * FROM VW_UNSCHEDULED_REFILLS";
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
            $data['scheduled_visit'] = $this->db->query($scheduled_visits)->result();
            $data['defaulter_visit'] = $this->db->query($defaulter_booking_visits)->result();
            $data['unscheduled_visit'] = $this->db->query($un_scheduled_visits)->result();
            $data['scheduled_visits_attended'] = $this->db->query($scheduled_visits_attended)->result();
            $data['missed_appointments'] = $this->db->query($vw_missed_appointments)->result();
            $data['vw_unscheduled_appointments'] = $this->db->query($vw_unscheduled_appointments)->result();
            $data['booked_attended_clinic'] = $this->db->query($vw_unscheduled_appointments)->result();
            $data['clinic_missed_appointment'] = $this->db->query($vw_unscheduled_appointments)->result();
            $data['scheduled_arv_pick'] = $this->db->query($vw_unscheduled_appointments)->result();
            $data['missed_arv_pick'] = $this->db->query($vw_unscheduled_appointments)->result();
            $data['unscheduled_arv'] = $this->db->query($unscheduled_arv)->result();
            $data['output'] = $this->get_access_level();


            $this->load->vars($data);
            $function_name = $this->uri->segment(2);
            // // $this->output->enable_profiler(TRUE);

            if (empty($function_name)) {
            } else {
                $check_auth = $this->check_authorization($function_name);
                if ($check_auth) {
                    $this->load->template('Home/appointment_diary');
                } else {
                    $this->load->template('Home/appointment_diary');
                    //                    echo 'Unauthorised Access';
                    //                    exit();
                }
            }
        }
    }

    public function count_all_appointments()
    {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment "
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id inner join "
            . " tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
            . " where active_app is NOT NULL")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    public function count_current_appointments()
    {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id inner join"
            . " tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where active_app='1' and appntmnt_date > CURDATE()")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    public function count_missed_appointments()
    {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where active_app='1' and appntmnt_date < CURDATE() and app_status='Missed' and tbl_client.status='Active' ")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    public function count_defaulted_appointments()
    {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment  "
            . "inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where active_app='1' and appntmnt_date < CURDATE() and app_status='Defaulted' and tbl_client.status='Active' ")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    public function count_LTFU_appointments()
    {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where active_app='1' and appntmnt_date < CURDATE() and app_status='LTFU' and tbl_client.status='Active'")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    public function count_Today_appointments()
    {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment "
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . " inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
            . " where active_app='1' and appntmnt_date = CURDATE() and tbl_client.status='Active'")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    public function get_appointment_month()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('DATE_FORMAT(tbl_appointment.appntmnt_date, "%M %Y") AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by("DATE_FORMAT(tbl_appointment.appntmnt_date, '%M %Y')"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_appointment_gender()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('gender.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('gender', 'gender.id = client.gender');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by("gender.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_appointment_marital()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('marital_status.marital AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('marital_status', 'marital_status.id = client.marital');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by("marital_status.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_appointment_grouping()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('groups.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by("groups.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_appointment_condition()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('client.client_status AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }

        $this->db->group_by("client.client_status"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_current_appointment_month()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('DATE_FORMAT(tbl_appointment.appntmnt_date, "%M %Y") AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date >= ', 'CURDATE()', false);
        $this->db->group_by("DATE_FORMAT(tbl_appointment.appntmnt_date, '%M %Y')"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_current_appointment_gender()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('gender.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('gender', 'gender.id = client.gender');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date >= ', 'CURDATE()', false);
        $this->db->group_by("gender.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_current_appointment_marital()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('marital_status.marital AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('marital_status', 'marital_status.id = client.marital');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date >= ', 'CURDATE()', false);
        $this->db->group_by("marital_status.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_current_appointment_grouping()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('groups.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date >= ', 'CURDATE()', false);
        $this->db->group_by("groups.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_current_appointment_condition()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('client.client_status AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date >= ', 'CURDATE()', false);
        $this->db->group_by("client.client_status"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_missed_appointment_month()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('DATE_FORMAT(tbl_appointment.appntmnt_date, "%M %Y") AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Missed');
        $this->db->group_by("DATE_FORMAT(tbl_appointment.appntmnt_date, '%M %Y')"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_missed_appointment_gender()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('gender.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('gender', 'gender.id = client.gender');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Missed');
        $this->db->group_by("gender.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_missed_appointment_marital()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('marital_status.marital AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('marital_status', 'marital_status.id = client.marital');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Missed');
        $this->db->group_by("marital_status.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_missed_appointment_grouping()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('groups.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Missed');
        $this->db->group_by("groups.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_missed_appointment_condition()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('client.client_status AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Missed');;
        $this->db->group_by("client.client_status"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_defaulted_appointment_month()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('DATE_FORMAT(tbl_appointment.appntmnt_date, "%M %Y") AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date  <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Defaulted');
        $this->db->group_by("DATE_FORMAT(tbl_appointment.appntmnt_date, '%M %Y')"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_defaulted_appointment_gender()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('gender.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('gender', 'gender.id = client.gender');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Defaulted');
        $this->db->group_by("gender.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_defaulted_appointment_marital()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('marital_status.marital AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('marital_status', 'marital_status.id = client.marital');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Defaulted');
        $this->db->group_by("marital_status.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_defaulted_appointment_grouping()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('groups.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Defaulted');
        $this->db->group_by("groups.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_defaulted_appointment_condition()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('client.client_status AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Defaulted');;
        $this->db->group_by("client.client_status"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_LTFU_appointment_month()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('DATE_FORMAT(tbl_appointment.appntmnt_date, "%M %Y") AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'LTFU');
        $this->db->group_by("DATE_FORMAT(tbl_appointment.appntmnt_date, '%M %Y')"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_LTFU_appointment_gender()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('gender.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('gender', 'gender.id = client.gender');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'LTFU');
        $this->db->group_by("gender.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_LTFU_appointment_marital()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('marital_status.marital AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('marital_status', 'marital_status.id = client.marital');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'LTFU');
        $this->db->group_by("marital_status.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_LTFU_appointment_grouping()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('groups.name AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'LTFU');
        $this->db->group_by("groups.id"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function get_LTFU_appointment_condition()
    {
        // // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);
        $mfl_code = $this->input->post('facility', true);
        $date_from = $this->input->post('date_from', true);
        $date_to = $this->input->post('date_to', true);

        if (!empty($date_from)) :
            $date_from = str_replace('-', '-', $date_from);
            $formated_date_from = date("Y-m-d", strtotime($date_from));
        endif;
        if (!empty($date_to)) :
            $date_to = str_replace('-', '-', $date_to);
            $formated_date_to = date("Y-m-d", strtotime($date_to));
        endif;

        $this->db->select('client.client_status AS name ,COUNT(tbl_appointment.`id`) AS value');
        $this->db->from('appointment');
        $this->db->join('client', 'client.id = appointment.client_id');
        $this->db->join('groups', 'groups.id = client.group_id');
        $this->db->join('partner_facility', 'partner_facility.mfl_code = client.mfl_code');
        if ($access_level === "Admin") :

        endif;

        if ($access_level == "Partner") :
            $this->db->where('partner_facility.partner_id', $partner_id);
        endif;


        if ($access_level == "Facility") :
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
            $this->db->where('tbl_appointment.appntmnt_date >= ', $formated_date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('tbl_appointment.appntmnt_date <=', $formated_date_to);
        }
        $this->db->where('tbl_appointment.appntmnt_date <= ', 'CURDATE()', false);
        $this->db->where('tbl_appointment.app_status ', 'Defaulted');;
        $this->db->group_by("client.client_status"); // Produces: GROUP BY Gender
        $get_query = $this->db->get()->result_array();

        echo json_encode($get_query);
    }

    public function client_profile()
    {
        $partner_id = $this->session->userdata('partner_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

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




        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();

        $data['all_appointments'] = $this->count_all_appointments();
        $data['current_appointments'] = $this->count_current_appointments();
        $data['missed_appointments'] = $this->count_missed_appointments();
        $data['defaulted_appointments'] = $this->count_defaulted_appointments();
        $data['LTFU_appointments'] = $this->count_LTFU_appointments();
        $data['Today_appointments'] = $this->count_Today_appointments();

        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Home/client_profile');
            } else {
                $this->load->template('Home/client_profile');
                //echo 'Unauthorised Access';
                //exit();
            }
        }
    }

    public function get_client_profile()
    {
        $upn = $this->input->post('upn', true);
        $facility_id = $this->session->userdata('facility_id');
        $partner_id = $this->session->userdata('partner_id');

        $access_level = $this->session->userdata('access_level');

        if ($access_level == "Partner") {
            // echo $partner_id;
            // exit;
            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.file_no,client.clinic_number,client.client_status ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id'
                ),
                'where' => array('client.clinic_number' => $upn, 'client.partner_id' => $partner_id)
            );
        } else {
            $clients = array(
                'select' => 'groups.name as group_name,groups.id as group_id,language.name as language_name ,'
                    . ' language.id as language_id, f_name,m_name,l_name,dob,client.status,phone_no,'
                    . 'client.file_no,client.clinic_number,client.client_status ,concat(f_name,m_name, l_name) as client_name,client.created_at as created_at,client.enrollment_date,client.art_date,client.updated_at,client.id as client_id,gender.name as gender_name,gender.name as gender_name,marital_status.marital,gender.id as gender_id,marital_status.id as marital_id',
                'table' => 'client',
                'join' => array(
                    'gender' => 'gender.id = client.gender',
                    'marital_status' => 'marital_status.id = client.marital',
                    'language' => 'language.id = client.language_id',
                    'groups' => 'groups.id = client.group_id'
                ),
                'where' => array('client.clinic_number' => $upn, 'client.mfl_code' => $facility_id)
            );
        }

        $cclient_info = $this->data->commonGet($clients);

        echo json_encode($cclient_info);
    }

    public function count_client_all_appointments()
    {
        $upn = $this->input->post('upn', true);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment "
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id inner join "
            . " tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
            . " where active_app is NOT NULL  and tbl_client.clinic_number='$upn'")->result_array();

        echo json_encode($query);
    }

    public function count_client_current_appointments()
    {
        $upn = $this->input->post('upn', true);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id inner join"
            . " tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where 1 and active_app='1' and DATE(appntmnt_date) >= CURDATE()  and tbl_client.clinic_number='$upn'")->result_array();

        echo json_encode($query);
    }

    public function count_client_kept_appointments()
    {
        $upn = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where 1 and active_app='0' and DATE(appntmnt_date) < CURDATE() and appointment_kept='Yes'  and tbl_client.clinic_number='$upn'")->result_array();

        echo json_encode($query);
    }

    public function count_client_missed_appointments()
    {
        $upn = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where 1 and DATE(appntmnt_date) < CURDATE() and app_status='Missed'  and tbl_client.clinic_number='$upn'")->result_array();

        echo json_encode($query);
    }

    public function count_client_defaulted_appointments()
    {
        $upn = $this->input->post('upn', true);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment  "
            . "inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where 1 and DATE(appntmnt_date) < CURDATE() and app_status='Defaulted'  and tbl_client.clinic_number='$upn'")->result_array();

        echo json_encode($query);
    }

    public function count_client_LTFU_appointments()
    {
        $upn = $this->input->post('upn', true);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
            . " where 1 and DATE(appntmnt_date) < CURDATE() and app_status='LTFU'  and tbl_client.clinic_number='$upn'")->result_array();

        echo json_encode($query);
    }

    public function count_client_Today_appointments()
    {
        $upn = $this->input->post('upn', true);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment "
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . " inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
            . " where 1 and DATE(appntmnt_date) = CURDATE() and tbl_client.clinic_number='$upn'")->result();

        foreach ($query as $value) {
            $num = $value->num;
            echo json_encode($num);
        }
    }

    public function count_client_appointments_type()
    {
        $upn = $this->input->post('upn', true);
        $query = $this->db->query("Select count(DISTINCT tbl_appointment.id)as num, tbl_appointment_types.name as app_type from tbl_appointment "
            . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
            . " inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code INNER JOIN tbl_appointment_types on tbl_appointment_types.id = tbl_appointment.app_type_1 "
            . " where tbl_client.clinic_number='$upn' group by app_type_1")->result_array();

        echo json_encode($query);
    }

    public function add_call_action()
    {
        $appointment_id = $this->input->post('appointment_id', true);
        $reason = $this->input->post('reason', true);


        $transaction = $this->data->add_call_action($appointment_id, $reason);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function add_homevisit_action()
    {
        $appointment_id = $this->input->post('appointment_id', true);
        $reason = $this->input->post('reason', true);


        $transaction = $this->data->add_homevisit_action($appointment_id, $reason);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function add_msg_action()
    {
        $appointment_id = $this->input->post('appointment_id', true);
        $reason = $this->input->post('reason', true);

        $client_id = $this->input->post('client_id', true);
        $transaction = $this->data->add_msg_action($appointment_id, $reason, $client_id);
        if ($transaction) {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        }
    }

    public function defaulter_tracing()
    {
    }
}
