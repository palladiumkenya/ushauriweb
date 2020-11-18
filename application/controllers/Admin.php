<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin extends MY_Controller
{

    public $data = '';

    function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->check_access();

        $this->data = new DBCentral();
    }

    function index()
    {
        redirect("Reports/dashboard", "refresh");
    }

    function check_access()
    {
        $logged_in = $this->session->userdata("logged_in");

        if ($logged_in) {
        } else {
            redirect("Login", "refresh");
        }
    }

    function my_facilities()
    {

        $donor_id = $this->session->userdata('donor_id');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');
        $sub_county_id = $this->session->userdata('subcounty_id');



        $county_id = $this->session->userdata('county_id');

        if ($access_level == 'Admin') {
            $options = array(
                'select' => 'keph_level,is_approved,master_facility.name,master_facility.owner,county.name as county,sub_county.name as sub_county , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type,partner_facility.is_approved , partner_facility.created_at,partner_facility.id as partner_facility_id  ',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'partner_facility.mfl_code = master_facility.code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
                'where' => array('county.status' => 'Active', 'sub_county.status' => 'Active')
            );
        } else if ($access_level == 'Partner') {

            $options = array(
                'select' => 'keph_level,master_facility.name,master_facility.owner,county.name as county,sub_county.name as sub_county , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type,partner_facility.is_approved  , partner_facility.created_at ,partner_facility.id as partner_facility_id  ',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'partner_facility.mfl_code = master_facility.code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
                'where' => array('county.status' => 'Active', 'sub_county.status' => 'Active', 'partner_facility.partner_id' => $partner_id)
            );
        } else if ($access_level == 'County') {
            $options = array(
                'select' => 'keph_level,master_facility.name,master_facility.owner,county.name as county,sub_county.name as sub_county , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type,partner_facility.is_approved  , partner_facility.created_at ,partner_facility.id as partner_facility_id  ',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'partner_facility.mfl_code = master_facility.code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
                'where' => array('county.status' => 'Active', 'sub_county.status' => 'Active', 'partner_facility.county_id' => $county_id)
            );
        } else if ($access_level == 'Sub County') {
            $options = array(
                'select' => 'keph_level,master_facility.name,master_facility.owner,county.name as county,sub_county.name as sub_county , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type,partner_facility.is_approved  , partner_facility.created_at ,partner_facility.id as partner_facility_id  ',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'partner_facility.mfl_code = master_facility.code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
                'where' => array('county.status' => 'Active', 'sub_county.status' => 'Active', 'partner_facility.sub_county_id' => $sub_county_id)
            );
        } else if ($access_level == 'Facility') {
            $options = array(
                'select' => 'keph_level,master_facility.name,master_facility.owner,county.name as county,sub_county.name as sub_county , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type,partner_facility.is_approved  , partner_facility.created_at ,partner_facility.id as partner_facility_id  ',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'partner_facility.mfl_code = master_facility.code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
                'where' => array('county.status' => 'Active', 'sub_county.status' => 'Active', 'partner_facility.mfl_code' => $facility_id)
            );
        } else {
            $options = array(
                'select' => 'keph_level,master_facility.name,master_facility.owner,county.name as county,sub_county.name as sub_county , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type,partner_facility.is_approved  , partner_facility.created_at ,partner_facility.id as partner_facility_id  ',
                'table' => 'master_facility',
                'join' => array('partner_facility' => 'partner_facility.mfl_code = master_facility.code', 'county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
                'where' => array('county.status' => 'Active', 'sub_county.status' => 'Active')
            );
        }




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
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);



        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Reports/my_facilities');
            } else {
                echo 'Invalid Access';
                exit();
            }
        }
    }

    function get_facility_details()
    {
        $mfl_code = $this->uri->segment(3);
        $options = array(
            'table' => 'master_facility',
            'where' => array('code' => $mfl_code)
        );
        $facility_data = $this->data->commonGet($options);

        echo json_encode($facility_data);
    }

    public function search_master_facility()
    {

        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->operations_model->get_active_clients($q);
        }
    }

    function search_facility()
    {

        $search_value = $this->uri->segment(3);
        $returned_value = $this->data->search_facility($search_value);


        if (empty($returned_value)) {
            echo json_encode($returned_value);
        } else {
            echo json_encode($returned_value);
        }
    }

    //FACILITIES MODULE STARTS HERE
    function facilities()
    {
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
        $options = array(
            'select' => 'master_facility.name , master_facility.id,master_facility.code,county.name as county_name,sub_county.name as sub_county_name,master_facility.facility_type',
            'table' => 'master_facility',
            'join' => array('county' => 'county.id = master_facility.county_id', 'sub_county' => 'sub_county.id = master_facility.sub_county_id', 'partner_facility' => 'partner_facility.mfl_code = master_facility.code'),
            'where' => array('assigned' => '0', 'county.status' => 'Active', 'sub_county.status' => 'Active')
        );
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
                $this->load->template('Admin/facilities');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function assign_partner_facility()
    {
        $mfl_code = $this->input->post('mfl_code', TRUE);
        $status = $this->input->post('status', TRUE);
        $partner_id = $this->input->post('partner_name', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->assign_partner_facility($mfl_code, $status, $partner_id, $today);
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

    //FACILITIES MODULE ENDS HERE 
    //PARTNER CRUD STARTS 
    function partners()
    {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
        $partner_type = array(
            'table' => 'partner_type',
            'where' => array('status' => 'Active')
        );





        $partners = array(
            'select' => 'partner.name as partner_name ,partner.e_mail, partner.id, partner.description,partner.phone_no,partner.location,partner.created_at,partner.updated_at,partner.status,partner_type.id as partner_type_id,partner_type.name as partner_type_name',
            'table' => 'partner',
            'join' => array('partner_type' => 'partner_type.id = partner.partner_type_id')
        );





        $data['partners'] = $this->data->commonGet($partners);
        $data['partner_type'] = $this->data->commonGet($partner_type);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/partners');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_logo()
    {
        $this->db->trans_start();
        $user_type = $this->uri->segment(3);
        if ($user_type == "Donor") {
            $get_donor_id = $this->db->query('Select max(id)as donor_id from tbl_donor LIMIT 1')->result();
            foreach ($get_donor_id as $value) {
                $donor_id = $value->donor_id;


                $config['upload_path'] = './uploads/logo/Donor';
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['overwrite'] = TRUE;
                $config['max_size'] = 10000;
                //$config['max_width'] = 100;
                //$config['max_height'] = 57;

                $this->load->library('upload', $config);




                if (isset($_FILES['file']['name'])) {
                    if (0 < $_FILES['file']['error']) {
                        echo 'Error during file upload' . $_FILES['file']['error'];
                    } else {
                        if (file_exists('./uploads/logo/Donor/' . $_FILES['file']['name'])) {
                            echo 'File already exists : ./uploads/logo/Donor/' . $_FILES['file']['name'];
                        } else {


                            if (!$this->upload->do_upload('file')) {
                                echo $this->upload->display_errors();
                            } else {

                                $image = $this->upload->data();
                                $file_path = $image['file_path'];
                                $file = $image['full_path'];
                                $file_ext = $image['file_ext'];



                                //resize:

                                $config['image_library'] = 'gd2';
                                $config['source_image'] = $image['full_path'];
                                $config['maintain_ratio'] = TRUE;
                                $config['width'] = 100;
                                $config['height'] = 57;

                                $this->load->library('image_lib', $config);

                                $this->image_lib->resize();

                                $newfile = './uploads/logo/Donor/' . $donor_id . $file_ext;
                                $oldfile = './uploads/logo/Donor/' . $_FILES['file']['name'];


                                $data_update = array(
                                    'donor_logo' => $donor_id . $file_ext
                                );
                                $this->db->where('id', $donor_id);
                                $this->db->update('donor', $data_update);

                                rename($oldfile, $newfile);
                            }
                        }
                    }
                } else {
                    echo 'Please choose a file';
                }
            }
        } elseif ($user_type == "Partner") {
            $get_partner_id = $this->db->query('Select max(id)as partner_id from tbl_partner LIMIT 1')->result();
            foreach ($get_partner_id as $value) {
                $partner_id = $value->partner_id;



                //upload file
                $config['upload_path'] = './uploads/logo/Partner';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['overwrite'] = TRUE;
                $config['max_size'] = 1000;
                // $config['max_width'] = 100;
                // $config['max_height'] = 57;

                $this->load->library('upload', $config);

                if (isset($_FILES['file']['name'])) {
                    if (0 < $_FILES['file']['error']) {
                        echo 'Error during file upload' . $_FILES['file']['error'];
                    } else {
                        if (file_exists('./uploads/logo/Partner/' . $_FILES['file']['name'])) {
                            echo 'File already exists : ./uploads/logo/Partner/' . $_FILES['file']['name'];
                        } else {
                            //$this->load->library('upload');

                            if (!$this->upload->do_upload('file')) {
                                echo $this->upload->display_errors();
                            } else {



                                $image = $this->upload->data();
                                $file_path = $image['file_path'];
                                $file = $image['full_path'];
                                $file_ext = $image['file_ext'];


                                //resize:

                                $config['image_library'] = 'gd2';
                                $config['source_image'] = $image['full_path'];
                                $config['maintain_ratio'] = TRUE;
                                $config['width'] = 100;
                                $config['height'] = 57;

                                $this->load->library('image_lib', $config);

                                $this->image_lib->resize();



                                echo 'File successfully uploaded : uploads/' . $_FILES['file']['name'];



                                $newfile = './uploads/logo/Partner/' . $partner_id . $file_ext;
                                $oldfile = './uploads/logo/Partner/' . $_FILES['file']['name'];


                                $data_update = array(
                                    'partner_logo' => $partner_id . $file_ext
                                );
                                $this->db->where('id', $partner_id);
                                $this->db->update('partner', $data_update);

                                rename($oldfile, $newfile);
                            }
                        }
                    }
                } else {
                    echo 'Please choose a file';
                }
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function add_partner_logo()
    {

        $get_partner_id = $this->db->query('Select max(id)as partner_id from tbl_partner LIMIT 1')->result();
        foreach ($get_partner_id as $value) {
            $partner_id = $value->partner_id;



            //upload file
            $config['upload_path'] = './uploads/logo';
            $config['allowed_types'] = '*';
            $config['max_filename'] = '255';
            $config['encrypt_name'] = FALSE;
            $config['max_size'] = '4096'; //1 MB



            if (isset($_FILES['file']['name'])) {
                if (0 < $_FILES['file']['error']) {
                    echo 'Error during file upload' . $_FILES['file']['error'];
                } else {
                    if (file_exists('./uploads/' . $_FILES['file']['name'])) {
                        echo 'File already exists : ./uploads/' . $_FILES['file']['name'];
                    } else {
                        //$this->load->library('upload');
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('file')) {
                            echo $this->upload->display_errors();
                        } else {



                            $image = $this->upload->data();
                            $file_path = $image['file_path'];
                            $file = $image['full_path'];
                            $file_ext = $image['file_ext'];





                            echo 'File successfully uploaded : uploads/' . $_FILES['file']['name'];



                            $newfile = './uploads/logo/' . $partner_id . $file_ext;
                            $oldfile = './uploads/logo/' . $_FILES['file']['name'];


                            $data_update = array(
                                'partner_logo' => $partner_id . $file_ext
                            );
                            $this->db->where('id', $partner_id);
                            $this->db->update('partner', $data_update);

                            rename($oldfile, $newfile);
                        }
                    }
                }
            } else {
                echo 'Please choose a file';
            }
        }
    }

    function add_partner()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $location = $this->input->post('location', TRUE);
        $status = $this->input->post('status', TRUE);
        $partner_type_id = $this->input->post('partner_type_id', TRUE);
        $e_mail = $this->input->post('e_mail');
        $phone_no = $this->input->post('phone_no');
        //$partner_logo = $_FILES['file']['name'];

        $check_donor_existence = $this->db->get_where('donor', array('name' => $name));
        if ($check_donor_existence->num_rows() > 0) {
            $transaction = 'Partner_Exists';
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $today = date("Y-m-d H:i:s");
            $transaction = $this->data->add_partner($name, $description, $location, $status, $partner_type_id, $e_mail, $phone_no, $today);
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
    }

    function rename_partner_logo()
    {

        $current_file = FCPATH . 'public/images/tmp/';
        $new_file = FCPATH . 'public/images/tmp/';
        rename($oldDir . $file, $newDir . $file);
    }

    function get_partner_data()
    {
        $partner_id = $this->uri->segment(3);

        $partner_data = array(
            'select' => 'partner.name as partner_name,partner.e_mail , partner.id, partner.description,partner.phone_no,partner.location,partner.created_at,partner.updated_at,partner.status,partner_type.id as partner_type_id,partner_type.name as partner_type_name',
            'table' => 'partner',
            'join' => array('partner_type' => 'partner_type.id = partner.partner_type_id'),
            'where' => array('partner.status' => 'Active', 'partner.id' => $partner_id)
        );

        $data = $this->data->commonGet($partner_data);
        echo json_encode($data);
    }

    function edit_partner()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $location = $this->input->post('location', TRUE);
        $status = $this->input->post('status', TRUE);
        $partner_type_id = $this->input->post('partner_type_id', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $partner_id = $this->input->post('partner_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_partner($partner_id, $name, $description, $location, $status, $partner_type_id, $e_mail, $phone_no, $today);
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

    function delete_partner()
    {
        $partner_id = $this->input->post('partner_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_partner($partner_id);
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

    //PARTNER CRUDS ENDS HERE
    //USER CRUD STARTS HERE 
    function users()
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');

        $access_level = $this->session->userdata('access_level');
        $donor_id = "0";
        $partner_id = "0";
        $facility_id = "0";
        $county_id = "0";
        $subcounty_id = "0";



        if ($access_level == 'Admin') {



            $options = array(
                'select' => 'f_name,m_name,l_name,dob,phone_no,e_mail,access_level,clinic.name as clinic_name, users.id as id ,users.status, users.created_at, users.updated_at',
                'table' => 'users',
                'join' => array('clinic' => 'clinic.id = users.clinic_id'),
                'where' => array('users.status' => 'Active'),
                'order' => array('f_name' => 'ASC')
            );

            $partner = array(
                'table' => 'partner',
                'where' => array('partner.status' => 'Active')
            );


            $donor = array(
                'table' => 'donor',
                'where' => array('donor.status' => 'Active')
            );



            $partner_facility = array(
                'select' => 'master_facility.name , master_facility.code, master_facility.id',
                'table' => 'partner_facility',
                'join' => array('master_facility' => 'master_facility.code = partner_facility.mfl_code', 'partner' => 'partner_facility.partner_id = partner.id'),
                'where' => array('partner_facility.status' => 'Active'),
                'order' => array('master_facility.name' => 'ASC')
            );

            $counties = array(
                'select' => ' county.name , county.code, county.id',
                'table' => 'county',
                'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
                'where' => array('county.status' => 'Active'),
                'group' => ('county.id')
            );
        } else {
            if ($access_level == 'Donor') {
                $donor_id += $this->session->userdata('donor_id');


                $options = array(
                    'select' => 'f_name,m_name,l_name,dob,phone_no,e_mail,access_level,clinic.name as clinic_name, users.id as id ,users.status, users.created_at, users.updated_at',
                    'table' => 'users',
                    'join' => array('clinic' => 'clinic.id = users.clinic_id'),
                    'where' => array('users.status' => 'Active', 'donor_id' => $donor_id, 'partner_id' => $partner_id, 'facility_id' => $facility_id),
                    'order' => array('f_name' => 'ASC')
                );

                $partner = array(
                    'table' => 'partner',
                    'where' => array('partner.status' => 'Active', 'id' => $partner_id)
                );


                $donor = array(
                    'table' => 'donor',
                    'where' => array('donor.status' => 'Active', 'id' => $donor_id)
                );



                $partner_facility = array(
                    'select' => 'master_facility.name , master_facility.code, master_facility.id',
                    'table' => 'partner_facility',
                    'join' => array('master_facility' => 'master_facility.code = partner_facility.mfl_code', 'partner' => 'partner_facility.partner_id = partner.id'),
                    'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id, 'partner_facility.partner_id' => $partner_id),
                    'order' => array('master_facility.name' => 'ASC')
                );

                $counties = array(
                    'select' => ' county.name , county.code, county.id',
                    'table' => 'county',
                    'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
                    'where' => array('county.status' => 'Active'),
                    'group' => ('county.id')
                );
            } else if ($access_level == 'Partner') {
                $donor_id += $this->session->userdata('donor_id');
                $partner_id += $this->session->userdata('partner_id');

                $facility = " ";

                $options = array(
                    'select' => 'f_name,m_name,l_name,dob,phone_no,e_mail,access_level,clinic.name as clinic_name, users.id as id ,users.status, users.created_at, users.updated_at',
                    'table' => 'users',
                    'join' => array('clinic' => 'clinic.id = users.clinic_id'),
                    'where' => array('users.status' => 'Active', 'partner_id' => $partner_id),
                    'order' => array('f_name' => 'ASC')
                );

                $partner = array(
                    'table' => 'partner',
                    'where' => array('partner.status' => 'Active', 'id' => $partner_id)
                );


                $donor = array(
                    'table' => 'donor',
                    'where' => array('donor.status' => 'Active', 'id' => $donor_id)
                );



                $partner_facility = array(
                    'select' => 'master_facility.name , master_facility.code, master_facility.id',
                    'table' => 'partner_facility',
                    'join' => array('master_facility' => 'master_facility.code = partner_facility.mfl_code', 'partner' => 'partner_facility.partner_id = partner.id'),
                    'where' => array('partner_facility.status' => 'Active', 'partner_facility.partner_id' => $partner_id),
                    'order' => array('master_facility.name' => 'ASC')
                );
                $counties = array(
                    'select' => ' county.name , county.code, county.id',
                    'table' => 'county',
                    'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
                    'where' => array('county.status' => 'Active', 'partner_facility.partner_id' => $partner_id),
                    'group' => ('county.id')
                );
            } else if ($access_level == 'Facility') {
                $donor_id += $this->session->userdata('donor_id');
                $partner_id += $this->session->userdata('partner_id');
                $facility_id += $this->session->userdata('facility_id');



                $options = array(
                    'select' => 'f_name,m_name,l_name,dob,phone_no,e_mail,access_level,clinic.name as clinic_name, users.id as id ,users.status, users.created_at, users.updated_at',
                    'table' => 'users',
                    'join' => array('clinic' => 'clinic.id = users.clinic_id'),
                    'where' => array('users.status' => 'Active', 'partner_id' => $partner_id, 'facility_id' => $facility_id),
                    'order' => array('f_name' => 'ASC')
                );

                $partner = array(
                    'table' => 'partner',
                    'where' => array('partner.status' => 'Active', 'id' => $partner_id)
                );


                $donor = array(
                    'table' => 'donor',
                    'where' => array('donor.status' => 'Active', 'id' => $donor_id)
                );



                $partner_facility = array(
                    'select' => 'master_facility.name , master_facility.code, master_facility.id',
                    'table' => 'partner_facility',
                    'join' => array('master_facility' => 'master_facility.code = partner_facility.mfl_code', 'partner' => 'partner_facility.partner_id = partner.id'),
                    'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id, 'partner_facility.partner_id' => $partner_id),
                    'order' => array('master_facility.name' => 'ASC')
                );

                $counties = array(
                    'select' => ' county.name , county.code, county.id',
                    'table' => 'county',
                    'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
                    'where' => array('county.status' => 'Active', 'partner_facility.mfl_code' => $facility_id),
                    'group' => ('county.id')
                );
            } else if ($access_level == 'County') {
                $donor_id += $this->session->userdata('donor_id');
                $partner_id += $this->session->userdata('partner_id');
                $facility_id += $this->session->userdata('facility_id');
                $county_id += $this->session->userdata('county_id');



                $options = array(
                    'select' => 'f_name,m_name,l_name,dob,phone_no,e_mail,access_level,clinic.name as clinic_name, users.id as id ,users.status, users.created_at, users.updated_at',
                    'table' => 'users',
                    'join' => array('clinic' => 'clinic.id = users.clinic_id'),
                    'where' => array('users.status' => 'Active', 'donor_id' => $donor_id, 'partner_id' => $partner_id, 'county_id' => $county_id),
                    'order' => array('f_name' => 'ASC')
                );

                $partner = array(
                    'table' => 'partner',
                    'where' => array('partner.status' => 'Active', 'id' => $partner_id)
                );


                $donor = array(
                    'table' => 'donor',
                    'where' => array('donor.status' => 'Active', 'id' => $donor_id)
                );



                $partner_facility = array(
                    'select' => 'master_facility.name , master_facility.code, master_facility.id',
                    'table' => 'partner_facility',
                    'join' => array('master_facility' => 'master_facility.code = partner_facility.mfl_code', 'partner' => 'partner_facility.partner_id = partner.id'),
                    'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id, 'partner_facility.partner_id' => $partner_id, 'partner_facility.county_id' => $county_id),
                    'order' => array('master_facility.name' => 'ASC')
                );


                $counties = array(
                    'select' => ' county.name , county.code, county.id',
                    'table' => 'county',
                    'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
                    'where' => array('county.status' => 'Active', 'partner_facility.county_id' => $county_id),
                    'group' => ('county.id')
                );
            } else if ($access_level == 'Sub County') {
                $donor_id += $this->session->userdata('donor_id');
                $partner_id += $this->session->userdata('partner_id');
                $facility_id += $this->session->userdata('facility_id');
                $subcounty_id += $this->session->userdata('subcounty_id');



                $options = array(
                    'select' => 'f_name,m_name,l_name,dob,phone_no,e_mail,access_level,clinic.name as clinic_name, users.id as id ,users.status, users.created_at, users.updated_at',
                    'table' => 'users',
                    'join' => array('clinic' => 'clinic.id = users.clinic_id'),
                    'where' => array('users.status' => 'Active', 'donor_id' => $donor_id, 'partner_id' => $partner_id, 'facility_id' => $facility_id, 'subcounty_id' => $subcounty_id),
                    'order' => array('f_name' => 'ASC')
                );

                $partner = array(
                    'table' => 'partner',
                    'where' => array('status' => 'Active', 'id' => $partner_id)
                );


                $donor = array(
                    'table' => 'donor',
                    'where' => array('status' => 'Active', 'id' => $donor_id)
                );



                $partner_facility = array(
                    'select' => 'master_facility.name , master_facility.code, master_facility.id',
                    'table' => 'partner_facility',
                    'join' => array('master_facility' => 'master_facility.code = partner_facility.mfl_code', 'partner' => 'partner_facility.partner_id = partner.id'),
                    'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id, 'partner_facility.partner_id' => $partner_id, 'partner_facility.sub_county_id' => $subcounty_id),
                    'order' => array('master_facility.name' => 'ASC')
                );


                $counties = array(
                    'select' => ' county.name , county.code, county.id',
                    'table' => 'county',
                    'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
                    'where' => array('county.status' => 'Active', 'partner_facilty.sub_county_id' => $subcounty_id),
                    'group' => ('county.id')
                );
            } else {
                $donor_id += "";
                $partner_id += "";
                $facility_id += "";


                $options = array(
                    'select' => 'f_name,m_name,l_name,dob,phone_no,e_mail,access_level,clinic.name as clinic_name, users.id as id ,users.status, users.created_at, users.updated_at',
                    'table' => 'users',
                    'join' => array('clinic' => 'clinic.id = users.clinic_id'),
                    'where' => array('users.status' => 'Active', 'donor_id' => $donor_id, 'partner_id' => $partner_id, 'facility_id' => $facility_id),
                    'order' => array('f_name' => 'ASC')
                );

                $partner = array(
                    'table' => 'partner',
                    'where' => array('status' => 'Active', 'id' => $partner_id)
                );


                $donor = array(
                    'table' => 'donor',
                    'where' => array('status' => 'Active', 'id' => $donor_id)
                );



                $partner_facility = array(
                    'select' => 'master_facility.name , master_facility.code, master_facility.id',
                    'table' => 'partner_facility',
                    'join' => array('master_facility' => 'master_facility.code = partner_facility.mfl_code', 'partner' => 'partner_facility.partner_id = partner.id'),
                    'where' => array('partner_facility.status' => 'Active', 'partner_facility.mfl_code' => $facility_id, 'partner_facility.partner_id' => $partner_id),
                    'order' => array('master_facility.name' => 'ASC')
                );


                $counties = array(
                    'select' => ' county.name , county.code, county.id',
                    'table' => 'county',
                    'join' => array('partner_facility' => 'partner_facility.county_id = county.id'),
                    'where' => array('county.status' => 'Active'),
                    'group' => ('county.id')
                );
            }
        }

        $clinics = array(
            'table' => 'clinic',
            'where' => array(
                'status' => 'Active',
                'name !=' => 'Not Assigned'
            )
        );


        $access_levels = array(
            'table' => 'access_level',
            'where' => array('status' => 'Active')
        );


        $data['output'] = $this->get_access_level();
        $data['output'] = $this->get_access_level();
        $data['add_access_levels'] = $this->data->commonGet($access_levels);
        $data['access_levels'] = $this->data->commonGet($access_levels);
        $data['users'] = $this->data->commonGet($options);
        $data['partners'] = $this->data->commonGet($partner);
        $data['donors'] = $this->data->commonGet($donor);
        $data['clinics'] = $this->data->commonGet($clinics);
        $data['counties'] = $this->data->commonGet($counties);
        $data['facilities'] = $this->data->commonGet($partner_facility);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $function_name = $this->uri->segment(2);

        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {

                $this->load->vars($data);
                $this->load->template('Admin/users');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function check_no()
    {
        $validate_user = $this->data->check_user();
        $Email = "EExists";
        $Phone = "PExists";
        $UnderAge = "UnderAge";
        $Success = "Done";
        if ($validate_user === $Success) {
            echo json_encode("Success");
        } elseif ($validate_user === $Email) {
            echo json_encode("EExists");
        } elseif ($validate_user === $Phone) {
            echo json_encode("PExists");
        } elseif ($validate_user === $UnderAge) {
            echo json_encode("UnderAge");
        }
    }

    function add_donor()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail');
        $phone_no = $this->input->post('phone_no');
        $donor_logo = $this->input->post('donor_logo');

        $check_donor_existence = $this->db->get_where('donor', array('name' => $name));
        if ($check_donor_existence->num_rows() > 0) {
            $transaction = 'Donor_Exists';
            $response = array(
                'response' => $transaction
            );
            echo json_encode([$response]);
        } else {
            $today = date("Y-m-d H:i:s");
            $transaction = $this->data->add_donor($name, $description, $status, $e_mail, $phone_no, $today);
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
    }

    function get_user_data()
    {
        $user_id = $this->uri->segment(3);

        $user_data = array(
            'table' => 'users',
            'where' => array('id' => $user_id)
        );

        $data = $this->data->commonGet($user_data);
        echo json_encode($data);
    }

    function get_facility_data()
    {
        $partner_facility_id = $this->uri->segment(3);

        $user_data = array(
            'select' => 'partner_facility.mfl_code, master_facility.name as facility_name, county.name as county_name, '
                . ' sub_county.name as sub_county, partner.name, partner.id,partner_facility.avg_clients, '
                . ' partner.id as partner_id,partner_facility.id as partner_facility_id',
            'table' => 'partner_facility',
            'join' => array(
                'master_facility' => 'master_facility.code = partner_facility.mfl_code',
                'county' => 'county.id = master_facility.county_id',
                'sub_county' => 'sub_county.id = master_facility.sub_county_id',
                'partner' => 'partner.id = partner_facility.partner_id'
            ),
            'where' => array('partner_facility.id' => $partner_facility_id)
        );

        $data = $this->data->commonGet($user_data);
        echo json_encode($data);
    }

    function edit_partner_facility()
    {
        $mfl_code = $this->input->post('mfl_code', TRUE);
        $partner_name = $this->input->post('partner_name', TRUE);
        $avg_clients = $this->input->post('avg_clients', TRUE);
        $partner_facility_id = $this->input->post('partner_facility_id', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_partner_facility($mfl_code, $partner_name, $avg_clients, $partner_facility_id, $today);
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

    function delete_partner_facility()
    {

        $partner_facility_id = $this->input->post('partner_facility_id', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_partner_facility($partner_facility_id);
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

    function edit_user()
    {
        $f_name = $this->input->post('f_name', TRUE);
        $l_name = $this->input->post('m_name', TRUE);
        $m_name = $this->input->post('l_name', TRUE);
        $status = $this->input->post('status', TRUE);
        $partner_id = $this->input->post('partner_id', TRUE);
        $facility_id = $this->input->post('facility_id', TRUE);
        $donor_id = $this->input->post('donor_id', TRUE);
        $dob = $this->input->post('dob', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $access_level = $this->input->post('access_level', TRUE);
        $daily_report = $this->input->post('edit_daily_report', TRUE);
        $weekly_report = $this->input->post('edit_weekly_report', TRUE);
        $monthly_report = $this->input->post('edit_monthly_report', TRUE);
        $month3_report = $this->input->post('edit_month3_report', TRUE);
        $month6_report = $this->input->post('edit_month6_report', TRUE);
        $yearly_report = $this->input->post('edit_yearly_report', TRUE);
        $role_name = $this->input->post('role_names', TRUE);
        $view_bio_data = $this->input->post('edit_bio_data', TRUE);
        $rcv_app_list = $this->input->post('edit_rcv_app_list', TRUE);
        $county_id = $this->input->post('county_id', TRUE);
        $subcounty_id = $this->input->post('subcounty_id', TRUE);
        $clinic_id = $this->input->post('clinic_id', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_user($f_name, $m_name, $l_name, $dob, $e_mail, $status, $partner_id, $donor_id, $facility_id, $today, $phone_no, $user_id, $access_level, $daily_report, $weekly_report, $monthly_report, $month3_report, $month6_report, $yearly_report, $role_name, $view_bio_data, $rcv_app_list, $county_id, $subcounty_id, $clinic_id);
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

    function delete_user()
    {
        $user_id = $this->input->post('user_id', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_user($user_id);
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

    function reset_user()
    {
        $user_id = $this->input->post('user_id', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->reset_user($user_id);
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

    //USER CRUD ENDS HERE
    //DONOR CRUD STARTS HERE
    function donors()
    {

        $donors = array(
            'table' => 'donor'
        );


        $data['donors'] = $this->data->commonGet($donors);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/donors');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function get_donor_data()
    {
        $donor_id = $this->uri->segment(3);

        $donor_data = array(
            'table' => 'donor',
            'where' => array('donor.status' => 'Active', 'donor.id' => $donor_id)
        );

        $data = $this->data->commonGet($donor_data);
        echo json_encode($data);
    }

    function edit_donor()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $donor_id = $this->input->post('donor_id', TRUE);
        $donor_logo = $this->input->post('donor_logo', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_donor($donor_id, $name, $description, $status, $e_mail, $phone_no, $today);
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

    function delete_donor()
    {
        $donor_id = $this->input->post('donor_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_donor($donor_id);
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

    //DONOR CRUD ENDS HERE 
    //
    //




    //ROLE CRUD STARTS HERE
    function role()
    {

        $roles = array(
            'table' => 'role'
        );
        $access_level = array(
            'table' => 'access_level',
            'where' => array('status' => 'Active')
        );

        $data['access_level'] = $this->data->commonGet($access_level);
        $data['roles'] = $this->data->commonGet($roles);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);

        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/role');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_role()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $access_level = $this->input->post('access_level');


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_role($name, $description, $status, $access_level, $today);
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

    function get_role_data()
    {
        $role_id = $this->uri->segment(3);

        $role_data = array(
            'table' => 'role',
            'where' => array('role.id' => $role_id),
            'order' => array('name' => 'ASC')
        );

        $data = $this->data->commonGet($role_data);
        echo json_encode($data);
    }

    function edit_role()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $access_level = $this->input->post('access_level', TRUE);

        $role_id = $this->input->post('role_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_role($role_id, $name, $description, $status, $access_level, $today);
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

    function delete_role()
    {
        $role_id = $this->input->post('role_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_role($role_id);
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

    //ROLE CRUD ENDS HERE 
    //
    //
    //LANGUAGE CRUD STARTS HERE 
    function languages()
    {

        $languages = array(
            'table' => 'language'
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['languages'] = $this->data->commonGet($languages);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/languages');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_language()
    {
        $name = $this->input->post('name', TRUE);
        $status = $this->input->post('status', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_language($name, $status, $today);
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

    function get_language_data()
    {
        $language_id = $this->uri->segment(3);

        $language_data = array(
            'table' => 'language',
            'where' => array('language.status' => 'Active', 'language.id' => $language_id)
        );

        $data = $this->data->commonGet($language_data);
        echo json_encode($data);
    }

    function edit_language()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $language_id = $this->input->post('language_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_language($language_id, $name, $status, $today);
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

    function delete_language()
    {
        $language_id = $this->input->post('language_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_language($language_id);
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

    //LANGUAGE CRUD ENDS HERE 
    //county_tier CRUD STARTS HERE 
    function county_tier()
    {

        $county_tier = array(
            'table' => 'county_tier'
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['county_tier'] = $this->data->commonGet($county_tier);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/county_tier');
            } else {
                $this->load->template('Admin/county_tier');

                //echo 'Unauthorised Access';
                //exit();
            }
        }
    }

    function add_county_tier()
    {
        $name = $this->input->post('name', TRUE);
        $status = $this->input->post('status', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_county_tier($name, $status, $today);
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

    function get_county_tier_data()
    {
        $county_tier_id = $this->uri->segment(3);

        $county_tier_data = array(
            'table' => 'county_tier',
            'where' => array('county_tier.status' => 'Active', 'county_tier.id' => $county_tier_id)
        );

        $data = $this->data->commonGet($county_tier_data);
        echo json_encode($data);
    }

    function edit_county_tier()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $county_tier_id = $this->input->post('county_tier_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_county_tier($county_tier_id, $name, $status, $today);
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

    function delete_county_tier()
    {
        $county_tier_id = $this->input->post('county_tier_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_county_tier($county_tier_id);
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

    //COUNTY CRUD ENDS HERE 
    //
    //LANGUAGE CRUD STARTS HERE 
    function senders()
    {

        $senders = array(
            'table' => 'sender'
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['senders'] = $this->data->commonGet($senders);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('sender');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_sender()
    {
        $name = $this->input->post('name', TRUE);
        $status = $this->input->post('status', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_sender($name, $status, $today);
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

    function get_sender_data()
    {
        $sender_id = $this->uri->segment(3);

        $sender_data = array(
            'table' => 'sender',
            'where' => array('sender.status' => 'Active', 'sender.id' => $sender_id)
        );

        $data = $this->data->commonGet($sender_data);
        echo json_encode($data);
    }

    function edit_sender()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $sender_id = $this->input->post('sender_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_sender($sender_id, $name, $status, $today);
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

    function delete_sender()
    {
        $sender_id = $this->input->post('sender_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_sender($sender_id);
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

    //LANGUAGE CRUD ENDS HERE 
    //TIME CRUD STARTS HERE 
    function time()
    {

        $time = array(
            'table' => 'time'
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['time'] = $this->data->commonGet($time);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/time');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_time()
    {
        $name = $this->input->post('name', TRUE);
        $status = $this->input->post('status');
        $today = date("Y-m-d H:i:s");

        $transaction = $this->data->add_time($name, $status, $today);
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

    function get_time_data()
    {
        $time_id = $this->uri->segment(3);

        $time_data = array(
            'table' => 'time',
            'where' => array('time.status' => 'Active', 'time.id' => $time_id)
        );

        $data = $this->data->commonGet($time_data);
        echo json_encode($data);
    }

    function edit_time()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $time_id = $this->input->post('time_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_time($time_id, $name, $status, $today);
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

    function delete_time()
    {
        $time_id = $this->input->post('time_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_time($time_id);
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

    //TIME CRUD ENDS HERE 
    //TARGET COUNTY CRUD STARTS HERE 
    function target_county()
    {


        $target_county = array(
            'table' => 'target_county',
            'join' => array('county' => 'county.id = target_county.county_id')
        );

        $target_county = $this->db->query("Select tbl_county.name,tbl_county.status, tbl_target_county.id, tbl_target_county.created_at,tbl_target_county.updated_at from tbl_target_county"
            . " inner join tbl_county on tbl_county.id = tbl_target_county.county_id"
            . " where tbl_target_county.status='Active' ")->result();

        $counties = "Select 
  * 
from
  tbl_county 
where tbl_county.status = 'Active' 
  AND id NOT IN 
  (Select 
    county_id 
  from
    tbl_target_county)";

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['target_county'] = ($target_county);
        $data['counties'] = $this->db->query($counties)->result();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/target_county');
            } else {
                $this->load->template('admin/target_county');
                //echo 'Unauthorised Access';
                //exit();
            }
        }
    }

    function add_target_county()
    {
        $name = $this->input->post('county_id', TRUE);
        $status = $this->input->post('status');
        $today = date("Y-m-d H:i:s");

        $transaction = $this->data->add_target_county($name, $status, $today);
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

    function get_target_county_data()
    {
        //// $this->output->enable_profiler(TRUE);
        $target_county_id = $this->uri->segment(3);

        //        $target_county_data = array(
        //            'table' => 'target_county',
        //            'join' => array('county' => 'county.id = target_county.county_id'),
        //            'where' => array('target_county.status' => 'Active', 'target_county.id' => $target_county_id)
        //        );
        //
        //        $data = $this->data->commonGet($target_county_data);
        $data = $this->db->query("Select tbl_county.name,tbl_county.status, tbl_target_county.id from tbl_target_county"
            . " inner join tbl_county on tbl_county.id = tbl_target_county.county_id"
            . " where tbl_target_county.status='Active' and tbl_target_county.id='$target_county_id'")->result();
        echo json_encode($data);
    }

    function edit_target_county()
    {
        $county_id = $this->input->post('county_id', TRUE);

        $status = $this->input->post('status', TRUE);
        $target_county_id = $this->input->post('target_county_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_target_county($target_county_id, $county_id, $status, $today);
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

    function delete_target_county()
    {
        $target_county_id = $this->input->post('target_county_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_target_county($target_county_id);
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

    //TARGET COUNTY CRUD ENDS HERE 
    //TARGET COUNTY CRUD STARTS HERE 
    function target_facilities()
    {


        $target_facilities = array(
            'table' => 'target_facility',
            'join' => array('master_facility' => 'master_facility.code = target_facility.mfl_code')
        );



        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['target_facility'] = $this->data->commonGet($target_facilities);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('target_facilities');
            } else {
                $this->load->template('target_facilities');
                //echo 'Unauthorised Access';
                //exit();
            }
        }
    }

    function add_target_facilities()
    {
        $mfl_code = $this->input->post('mfl_code', TRUE);
        $status = $this->input->post('status');
        $today = date("Y-m-d H:i:s");

        $transaction = $this->data->add_target_facilities($mfl_code, $status, $today);
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

    function get_target_facility_data()
    {
        $target_facilities_id = $this->uri->segment(3);

        $target_facilities_data = array(
            'table' => 'target_facility',
            'join' => array('master_facility' => 'master_facility.code = target_facility.mfl_code'),
            'where' => array('target_facility.status' => 'Active', 'target_facility.mfl_code' => $target_facilities_id)
        );

        $data = $this->data->commonGet($target_facilities_data);
        echo json_encode($data);
    }

    function edit_target_facility()
    {
        $mfl_code = $this->input->post('mfl_code', TRUE);
        $status = $this->input->post('status', TRUE);
        $target_facilities_id = $this->input->post('target_facilities_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_target_facilities($target_facilities_id, $mfl_code, $status, $today);
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

    function delete_target_facilities()
    {
        $target_facilities_id = $this->input->post('target_facilities_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_target_facilities($target_facilities_id);
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

    //TARGET COUNTY CRUD ENDS HERE 
    //CONTENT CRUD STARTS 
    function contents()
    {

        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');



        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );

        $message_types = array(
            'table' => 'message_types',
            'where' => array('status' => 'Active')
        );
        $groups = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );




        $contents = array(
            'select' => 'content.id,content.content ,content.message_type_id, content.language_id,'
                . ' content.status,content.created_at,content.updated_at,content.identifier,content.group_id,'
                . 'content.status as content_status,message_types.name as message_type_name,language.name as language_name,'
                . 'groups.name as group_name',
            'table' => 'content',
            'join' => array('message_types' => 'message_types.id = content.message_type_id', 'language' => 'language.id = content.language_id', 'groups' => 'groups.id = content.group_id')
        );


        $response = array(
            'table' => 'notification_flow',
            'where' => array('status' => 'Active', 'notification_type' => 'Other')
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['response'] = $this->data->commonGet($response);
        $data['output'] = $this->get_access_level();
        $data['contents'] = $this->data->commonGet($contents);
        $data['message_types'] = $this->data->commonGet($message_types);
        $data['groups'] = $this->data->commonGet($groups);
        $data['languages'] = $this->data->commonGet($languages);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/contents');
            } else {
                $this->load->template('Admin/contents');
                //                echo 'Unauthorised Access';
                //                exit();
            }
        }
    }

    function add_content()
    {
        $content = $this->input->post('content', TRUE);
        $response = $this->input->post('response', TRUE);
        $status = $this->input->post('status', TRUE);
        $message_type_id = $this->input->post('message_type_id', TRUE);
        $language_id = $this->input->post('language_id', TRUE);
        $groups_id = $this->input->post('groups_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_content($content, $response, $status, $message_type_id, $language_id, $groups_id, $today);
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

    function get_content_data()
    {
        $content_id = $this->uri->segment(3);

        $content_data = array(
            'select' => 'content.id,content.content ,content.message_type_id, content.language_id,'
                . ' content.status,content.created_at,content.updated_at,content.identifier,content.group_id,'
                . 'content.status as content_status,message_types.name as message_type_name,language.name as language_name,'
                . 'groups.name as group_name',
            'table' => 'content',
            'join' => array('message_types' => 'message_types.id = content.message_type_id', 'language' => 'language.id = content.language_id', 'groups' => 'groups.id = content.group_id'),
            'where' => array('content.status' => 'Active', 'content.id' => $content_id)
        );

        $data = $this->data->commonGet($content_data);
        echo json_encode($data);
    }

    function edit_content()
    {
        $content = $this->input->post('content', TRUE);
        $response = $this->input->post('response', TRUE);
        $status = $this->input->post('status', TRUE);
        $message_type_id = $this->input->post('message_type_id', TRUE);
        $language_id = $this->input->post('language_id', TRUE);
        $groups_id = $this->input->post('groups_id', TRUE);
        $content_id = $this->input->post('content_id', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_content($content_id, $content, $response, $status, $message_type_id, $language_id, $groups_id, $today);
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

    function delete_content()
    {
        $content_id = $this->input->post('content_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_content($content_id);
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

    //CONTENT CRUDS ENDS HERE
    //MESSAGE CRUD STARTS 
    function messages()
    {
        //// $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');



        $languages = array(
            'table' => 'language',
            'where' => array('status' => 'Active')
        );

        $message_types = array(
            'table' => 'message_types',
            'where' => array('status' => 'Active')
        );
        $groups = array(
            'table' => 'groups',
            'where' => array('status' => 'Active')
        );




        $messages = array(
            'select' => 'messages.id,messages.message ,messages.message_type_id, messages.language_id,'
                . ' messages.status,messages.created_at,messages.updated_at,'
                . 'messages.status as message_status,message_types.name as message_type_name,language.name as language_name,',
            'table' => 'messages',
            'join' => array('message_types' => 'message_types.id = messages.message_type_id', 'language' => 'language.id = messages.language_id')
        );


        $response = array(
            'table' => 'notification_flow',
            'where' => array('status' => 'Active', 'notification_type' => 'Other')
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['response'] = $this->data->commonGet($response);
        $data['output'] = $this->get_access_level();
        $data['messages'] = $this->data->commonGet($messages);
        $data['message_types'] = $this->data->commonGet($message_types);
        $data['groups'] = $this->data->commonGet($groups);
        $data['languages'] = $this->data->commonGet($languages);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin\messages');
            } else {
                $this->load->template('Admin\messages');
                //                echo 'Unauthorised Access';
                //                exit();
            }
        }
    }

    function add_message()
    {
        $message = $this->input->post('message', TRUE);
        $response = $this->input->post('response', TRUE);
        $status = $this->input->post('status', TRUE);
        $message_type_id = $this->input->post('message_type_id', TRUE);
        $language_id = $this->input->post('language_id', TRUE);
        $groups_id = $this->input->post('groups_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_message($message, $response, $status, $message_type_id, $language_id, $groups_id, $today);
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

    function get_message_data()
    {
        $message_id = $this->uri->segment(3);

        $message_data = array(
            'select' => 'messages.id,messages.message ,messages.message_type_id, messages.language_id,'
                . ' messages.status,messages.created_at,messages.updated_at,'
                . 'messages.status as message_status,message_types.name as message_type_name,language.name as language_name,',
            'table' => 'messages',
            'join' => array('message_types' => 'message_types.id = messages.message_type_id', 'language' => 'language.id = messages.language_id'),
            'where' => array('messages.status' => 'Active', 'messages.id' => $message_id)
        );

        $data = $this->data->commonGet($message_data);
        echo json_encode($data);
    }

    function edit_message()
    {
        $message = $this->input->post('message', TRUE);
        $response = $this->input->post('response', TRUE);
        $status = $this->input->post('status', TRUE);
        $message_type_id = $this->input->post('message_type_id', TRUE);
        $language_id = $this->input->post('language_id', TRUE);
        $groups_id = $this->input->post('groups_id', TRUE);
        $message_id = $this->input->post('message_id', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_message($message_id, $message, $response, $status, $message_type_id, $language_id, $groups_id, $today);
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

    function delete_message()
    {
        $message_id = $this->input->post('message_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_message($message_id);
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

    //MESSAGE CRUDS ENDS HERE


    function notification_conf()
    {
        $notifications = array(
            'table' => 'notification_flow',
            'where' => array('status' => 'Active')
        );
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['notifications'] = $this->data->commonGet($notifications);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/notification_conf');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function get_notificationtype_data()
    {
        $id = $this->uri->segment(3);
        $time_data = array(
            'table' => 'notification_flow',
            'where' => array('notification_flow.status' => 'Active', 'notification_flow.id' => $id)
        );

        $data = $this->data->commonGet($time_data);
        echo json_encode($data);
    }

    function edit_notification_flow()
    {
        $notification_id = $this->input->post('notificaiton_id', TRUE);
        $days = $this->input->post('days', TRUE);
        $status = $this->input->post('status', TRUE);
        $transaction = $this->data->edit_notification_flow($notification_id, $days, $status);
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

    //MODULE MANAGEMET CRUD STARTS 
    function module()
    {


        $modules = array(
            'table' => 'module'
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['modules'] = $this->data->commonGet($modules);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/modules');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_module()
    {
        $name = $this->input->post('name', TRUE);
        $controller = $this->input->post('controller', TRUE);
        $function = $this->input->post('function', TRUE);
        $status = $this->input->post('status', TRUE);
        $level = $this->input->post('level', TRUE);
        $description = $this->input->post('description', TRUE);
        $span = $this->input->post('span', TRUE);
        $icon = $this->input->post('icon', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_module($name, $controller, $function, $status, $today, $level, $description, $span, $icon);
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

    function get_module_data()
    {
        $module_id = $this->uri->segment(3);

        $module_data = array(
            'table' => 'module',
            'where' => array('module.id' => $module_id)
        );

        $data = $this->data->commonGet($module_data);
        echo json_encode($data);
    }

    function edit_module()
    {
        $name = $this->input->post('name', TRUE);
        $controller = $this->input->post('controller', TRUE);
        $status = $this->input->post('status', TRUE);
        $function = $this->input->post('function', TRUE);
        $level = $this->input->post('level', TRUE);
        $module_id = $this->input->post('module_id', TRUE);
        $description = $this->input->post('description', TRUE);
        $span = $this->input->post('span', TRUE);
        $icon = $this->input->post('icon', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_module($module_id, $name, $controller, $function, $status, $today, $level, $description, $span, $icon);
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

    function delete_module()
    {
        $module_id = $this->input->post('module_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_module($module_id);
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

    //MODULE CRUDS ENDS HERE


    function roles()
    {

        $modules = array(
            'table' => 'module'
        );

        $users = array(
            'table' => 'users',
            'where' => array('status' => 'Active')
        );
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['users'] = $this->data->commonGet($users);
        $data['modules'] = $this->data->commonGet($modules);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin\roles');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function get_user_permissions()
    {
        $user_id = $this->uri->segment(3);
        $module_data = array(
            'table' => 'user_permission',
            'where' => array('status' => 'Active', 'user_id' => $user_id)
        );

        $data = $this->data->commonGet($module_data);
        echo json_encode($data);
    }

    function assign_roles()
    {
        $this->db->trans_start();
        $functions = $this->input->post('functions');
        $temp = $this->input->post('functions');
        $user_id = $this->input->post('user_name');

        $today = date("Y-m-d H:i:s");
        $status = "Active";
        $delete_access = $this->delete_access($user_id);
        for ($i = 0; $i < count($temp); $i++) {

            $data_insert = array(
                'module_id' => $functions[$i],
                'user_id' => $user_id,
                'created_at' => $today,
                'status' => $status
            );
            $this->db->insert('user_permission', $data_insert);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = FALSE;
            $response = array(
                'response' => $response
            );
            echo json_encode([$response]);
        } else {
            $response = TRUE;
            $response = array(
                'response' => $response
            );
            echo json_encode([$response]);
        }
    }

    function delete_access($user_id)
    {
        $this->db->trans_start();
        $query = $this->db->query("select id from tbl_user_permission where user_id='$user_id'");
        foreach ($query->result() as $value) {
            $id = $value->id;
            $this->db->delete('user_permission', array('id' => $id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
        } else {
            $success = "SUCCESS";
            return $success;
        }
    }

    function role_modules()
    {

        $modules = array(
            'table' => 'module',
            'order' => array('module' => 'ASC')
        );

        $users = array(
            'table' => 'role',
            'where' => array('status' => 'Active'),
            'order' => array('name' => 'ASC')
        );
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['roles'] = $this->data->commonGet($users);
        $data['modules'] = $this->data->commonGet($modules);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/role_modules');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function get_role_permissions()
    {
        $role_id = $this->uri->segment(3);
        $module_data = array(
            'table' => 'role_module',
            'where' => array('status' => 'Active', 'role_id' => $role_id),
            'order' => array('module_id' => 'ASC')
        );

        $data = $this->data->commonGet($module_data);
        echo json_encode($data);
    }

    function assign_roles_modules()
    {
        $this->db->trans_start();
        $functions = $this->input->post('functions');
        $temp = $this->input->post('functions');
        $role_id = $this->input->post('role_name');
        $user_id = $this->session->userdata('user_id');

        $today = date("Y-m-d H:i:s");
        $status = "Active";




        $get_all_current_roles = $this->db->get_where('role_module', array('role_id' => $role_id, 'status' => 'Active'))->result();
        foreach ($get_all_current_roles as $current_role_value) {
            $db_role_id = $current_role_value->role_id;
            $db_module_id = $current_role_value->module_id;
            $role_module_id = $current_role_value->id;
            echo 'Role ID => ' . $db_role_id . '<br> Module ID => ' . $db_module_id . '<br>';
            if (in_array($db_module_id, $functions)) {
                echo 'Module already exists in the system ...Do nothing ....<br>';
            } else {
                echo 'Value does not exist in the posted array ...<br> Mark this role as InActive and insert the new role to the system ....';
                $mark_module_inactive = array(
                    'status' => 'Disabled'
                );
                $this->db->where('id', $role_module_id);
                $this->db->update('role_module', $mark_module_inactive);
            }
        }







        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = FALSE;
            $response = array(
                'response' => $response
            );
            echo json_encode([$response]);
        } else {

            //Check if module was added to the  table role module , if it wasn't then add it 

            for ($i = 0; $i < count($temp); $i++) {
                $posted_module_id = $functions[$i];
                echo 'Module Id => ' . $posted_module_id . '<br>';

                $check_if_added = $this->db->get_where('role_module', array('module_id' => $posted_module_id, 'role_id' => $role_id, 'status' => 'Active'))->num_rows();
                if ($check_if_added > 0) {
                    //Do Nothing ...
                    echo 'Module ID => ' . $posted_module_id . ' already exists in the  system , do nothing. ... <br> ';
                } else {
                    echo 'Module ID => ' . $posted_module_id . ' does not exist in the  system , lets add it from role id => ' . $role_id . ' <br> ';
                    //Add the  module to the  system ....
                    $this->db->trans_start();

                    $data_insert = array(
                        'module_id' => $posted_module_id,
                        'role_id' => $role_id,
                        'created_at' => $today,
                        'status' => $status,
                        'created_by' => $user_id
                    );
                    $this->db->insert('role_module', $data_insert);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                    } else {
                        echo 'Role ID added successfully ....<br>';
                    }
                }
            }


            $get_all_current_permissions = $this->db->get_where('user_permission', array('role_id' => $role_id, 'status' => 'Active'))->result();
            foreach ($get_all_current_permissions as $current_permission_value) {
                $db_role_id = $current_permission_value->role_id;
                $db_module_id = $current_permission_value->module_id;
                $user_permission_id = $current_permission_value->id;
                echo 'Role ID => ' . $db_role_id . '<br> Module ID => ' . $db_module_id . '<br>';
                if (in_array($db_module_id, $functions)) {
                    echo 'Module ID=> ' . $db_module_id . ' already exists in the user permission table , do nothing to it ...Do nothing ....<br>';
                } else {
                    echo 'Module ID=> ' . $db_module_id . '  does not exist in the posted user permission array ...<br> Mark this user permisson as InActive and insert the new user permissions  to the system ....';
                    $this->db->trans_start();
                    $mark_module_inactive = array(
                        'status' => 'Disabled'
                    );
                    $this->db->where('id', $role_module_id);
                    $this->db->update('user_permission', $mark_module_inactive);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                    } else {
                    }
                }
            }













            $get_user_permissions = $this->db->get_where('user_permission', array('module_id' => $posted_module_id, 'role_id' => $role_id, 'status' => 'Active'));
            $check_if_usr_perm_added = $get_user_permissions->num_rows();
            if ($check_if_usr_perm_added > 0) {
                //Do Nothing ...
                echo 'Module ID => ' . $posted_module_id . ' already exists in the  User permission for the  User , do nothing. ... <br> ';
            } else {
                echo 'Module ID => ' . $posted_module_id . ' does not exist in the  User permission for the  User  , lets add it  for Role ID => ' . $role_id . '<br> ';
                //Add the  module to the  system ....
                $get_user_permissions2 = $this->db->get_where('user_permission', array('role_id' => $role_id, 'status' => 'Active'));
                foreach ($get_user_permissions2->result() as $user_perm_value) {
                    $end_user_id = $user_perm_value->user_id;
                    $this->db->trans_start();

                    $data_insert = array(
                        'module_id' => $posted_module_id,
                        'role_id' => $role_id,
                        'created_at' => $today,
                        'status' => $status,
                        'created_by' => $user_id,
                        'user_id' => $end_user_id
                    );
                    $this->db->insert('user_permission', $data_insert);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                    } else {
                    }
                }
            }
        }
    }

    function delete_role_modules_rights($role_id, $functions)
    {





        $this->db->trans_start();



        for ($i = 0; $i < count($functions); $i++) {
            $module_id = $functions[$i];

            $query = $this->db->query("select id from tbl_role_module where role_id='$role_id' and module_id='$module_id' and status='Active'");

            foreach ($query->result() as $value) {
                $id = $value->id;
                $data_array = array(
                    'status' => 'In Active'
                );
                $this->db->where('id', $id);
                $this->db->update('role_module', $data_array);
                //$this->db->delete('role_module', array('id' => $id));
            }
        }




        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
        } else {
            //$success = "SUCCESS";
            return TRUE;
        }
    }

    //MESSAGE TYPES CRUD STARTS HERE
    function message_types()
    {

        $donors = array(
            'table' => 'message_types'
        );


        $data['message_types'] = $this->data->commonGet($donors);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin\message_types');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_message_type()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_message_types($name, $description, $status, $today);
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

    function get_message_type_data()
    {
        $message_types_id = $this->uri->segment(3);

        $donor_data = array(
            'table' => 'message_types',
            'where' => array('id' => $message_types_id)
        );

        $data = $this->data->commonGet($donor_data);
        echo json_encode($data);
    }

    function edit_message_type()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $message_id = $this->input->post('message_type_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_message_types($name, $description, $status, $today, $message_id);
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

    function delete_message_type()
    {
        $message_type_id = $this->input->post('message_type_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_message_types($message_type_id);
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

    //MESSAGE TYPES CRUD ENDS HERE 
    //GROUP CRUD STARTS HERE
    function groups()
    {

        $groups = array(
            'table' => 'groups'
        );


        $data['groups'] = $this->data->commonGet($groups);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {

                $this->load->template('Admin/groups');
            } else {
                $this->load->template('Admin/groups');
                //                echo 'Unauthorised Access';
                //                exit();
            }
        }
    }

    function add_group()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);



        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_group($name, $description, $status, $today);
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

    function get_group_data()
    {
        $group_id = $this->uri->segment(3);

        $group_data = array(
            'table' => 'groups',
            'where' => array('status' => 'Active', 'id' => $group_id)
        );

        $data = $this->data->commonGet($group_data);
        echo json_encode($data);
    }

    function edit_group()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $group_id = $this->input->post('group_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_group($group_id, $name, $description, $status, $e_mail, $phone_no, $today);
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

    function delete_group()
    {
        $group_id = $this->input->post('group_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_group($group_id);
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

    //GROUP CRUD ENDS HERE
    //COUNTY CRUD STARTS HERE
    function county()
    {

        $counties = array(
            'table' => 'county'
        );



        $county_tiers = array(
            'table' => 'county',
            'where' => array('status' => 'Active')
        );


        $data['counties'] = $this->data->commonGet($counties);
        $data['county_tier'] = $this->data->commonGet($county_tiers);
        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/counties');
            } else {
                echo 'Unauthorised Access';
                exit();
            }
        }
    }

    function add_county()
    {
        $name = $this->input->post('name', TRUE);
        $code = $this->input->post('code', TRUE);
        $status = $this->input->post('status', TRUE);
        $tier = $this->input->post('tier', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_county($name, $code, $status, $today, $tier);
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

    function get_county_data()
    {
        $county_id = $this->uri->segment(3);

        $county_data = array(
            'table' => 'county',
            'where' => array('status' => 'Active', 'id' => $county_id)
        );

        $data = $this->data->commonGet($county_data);
        echo json_encode($data);
    }

    function edit_county()
    {
        $name = $this->input->post('name', TRUE);
        $code = $this->input->post('code', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $county_id = $this->input->post('county_id', TRUE);
        $tier = $this->input->post('tier', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_county($county_id, $name, $code, $status, $tier);
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

    function delete_county()
    {
        $county_id = $this->input->post('county_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_county($county_id);
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

    //COUNTY CRUD ENDS HERE




    function get_sub_counties()
    {
        $access_level = $this->uri->segment(3);

        $county_data = array(
            'table' => 'sub_county',
            'where' => array('status' => 'Active', 'county_id' => $access_level)
        );

        $data = $this->data->commonGet($county_data);
        echo json_encode($data);
    }

    function get_county_facilities()
    {
        $county_id = $this->uri->segment(3);

        $county_facilities = $this->db->query("Select * from tbl_master_facility inner join tbl_partner_facility on  tbl_partner_facility.mfl_code = tbl_master_facility.code where tbl_partner_facility.county_id = '$county_id'")->result();


        echo json_encode($county_facilities);
    }

    function get_sub_county_facilities()
    {
        $sub_county_id = $this->uri->segment(3);

        $county_facilities = $this->db->query("Select * from tbl_master_facility inner join tbl_partner_facility on  tbl_partner_facility.mfl_code = tbl_master_facility.code where tbl_partner_facility.sub_county_id = '$sub_county_id'")->result();


        echo json_encode($county_facilities);
    }

    function get_access_roles()
    {
        $access_level = $this->uri->segment(3);
        $access_level = urldecode($access_level);

        $county_data = array(
            'table' => 'role',
            'where' => array('status' => 'Active', 'access_level' => $access_level)
        );

        $data = $this->data->commonGet($county_data);
        echo json_encode($data);
    }

    function approve_facility()
    {
        $broadcast_id = $this->uri->segment(3);


        $transaction = $this->data->approve_facility($broadcast_id);
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

    function disapprove_facility()
    {
        $mfl_code = $this->uri->segment(3);

        $reason = $this->input->post('reason');
        $transaction = $this->data->disapprove_facility($mfl_code, $reason);
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

    function generate_token()
    {

        $csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
?>

        <input type="text" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
<?php
    }

    //
    //LANGUAGE CRUD STARTS HERE 
    function clinics()
    {

        $clinics = array(
            'table' => 'clinic'
        );

        $data['side_functions'] = $this->data->get_side_modules();
        $data['top_functions'] = $this->data->get_top_modules();
        $data['output'] = $this->get_access_level();
        $data['clinics'] = $this->data->commonGet($clinics);
        $this->load->vars($data);
        $function_name = $this->uri->segment(2);
        if (empty($function_name)) {
        } else {
            $check_auth = $this->check_authorization($function_name);
            if ($check_auth) {
                $this->load->template('Admin/clinics');
            } else {
                $this->load->template('Admin/clinics');
            }
        }
    }

    function add_clinic()
    {
        $name = $this->input->post('name', TRUE);
        $status = $this->input->post('status', TRUE);
        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->add_clinic($name, $status, $today);
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

    function get_clinic_data()
    {
        $clinic_id = $this->uri->segment(3);

        $clinic_data = array(
            'table' => 'clinic',
            'where' => array('clinic.status' => 'Active', 'clinic.id' => $clinic_id)
        );

        $data = $this->data->commonGet($clinic_data);
        echo json_encode($data);
    }

    function edit_clinic()
    {
        $name = $this->input->post('name', TRUE);
        $description = $this->input->post('description', TRUE);
        $status = $this->input->post('status', TRUE);
        $e_mail = $this->input->post('e_mail', TRUE);
        $phone_no = $this->input->post('phone_no', TRUE);
        $clinic_id = $this->input->post('clinic_id', TRUE);

        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->edit_clinic($clinic_id, $name, $status, $today);
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

    function delete_clinic()
    {
        $clinic_id = $this->input->post('clinic_id', TRUE);


        $today = date("Y-m-d H:i:s");
        $transaction = $this->data->delete_clinic($clinic_id);
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

    //LANGUAGE CRUD ENDS HERE
}
