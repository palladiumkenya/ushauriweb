<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DBCentral extends CI_Model
{
    public function getAllData($table_name)
    {
        return $this->db->get("$table_name")->result_array();
    }

    public function get_Active_Data($table_name, $status)
    {
        return $this->db->get_where("$table_name", array("status" => "$status"))->result_array();
    }

    //    function search_master_facility($search_value) {
    //        $search_param = $this->uri->segment(3);
    //        $this->db->like('code', $search_value);
    //        $this->db->or_like('name', $search_value);
    //        $query = $this->db->get('master_facility');
    //        return $query->result();
    //    }

    public function search_master_facility($q)
    {
        $query = "Select * from tbl_master_facility where name like '%$q%' or code like'%$q%'";
        $query = $this->db->query($query);

        foreach ($query->result_array() as $value) {
            $new_row['label'] = htmlentities($value['code'] . "  " . $value['name']);
            $new_row['value'] = htmlentities($value['code']);
            $row_set[] = $new_row; //build an array

            echo json_encode($row_set);
        }
    }

    public function search_incoming($search_value)
    {
        // $this->output->enable_profiler(TRUE);
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');


        $this->db->select('*');

        $this->db->like('tbl_responses.source', $search_value);
        $this->db->order_by('created_at', 'ASC');

        $this->db->limit(1000);
        $query = $this->db->get('responses');


        return $query->result();
    }

    public function search_outgoing($search_value)
    {
        // $this->output->enable_profiler(TRUE);
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');


        $this->db->select('*');

        $this->db->like('tbl_outgoing.destination', $search_value);
        $this->db->order_by('created_at', 'ASC');

        $this->db->limit(1000);
        $query = $this->db->get('outgoing');


        return $query->result();
    }

    public function search_audit_trail($search_value)
    {
        // $this->output->enable_profiler(TRUE);
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');




        $query = $this->db->query("SELECT * FROM `vw_audit_trail` WHERE `phone_no` LIKE '%$search_value%' ESCAPE '!' ORDER BY `created_at` DESC ");


        return $query->result();
    }

    public function search_facility($search_value)
    {
        // $this->output->enable_profiler(TRUE);
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');


        $this->db->select('tbl_master_facility.id as facility_id,tbl_master_facility.name as facility_name, tbl_master_facility.code as mfl_code,tbl_master_facility.county_id as county_id, tbl_master_facility.sub_county_id as sub_county_id,tbl_master_facility.consituency_id as consituentcy_id,tbl_master_facility.owner, county.name as county_name , sub_county.name as sub_county_name , consituency.name as consituency_name ');
        if ($access_level == 'County') {
            $this->db->where('tbl_master_facility.county_id', $county_id);
        }
        if ($access_level == 'Sub County') {
            $this->db->where('tbl_master_facility.sub_county_id', $sub_county_id);
        }
        $this->db->where('assigned', '0');
        $this->db->like('tbl_master_facility.code', $search_value);
        $this->db->or_like('tbl_master_facility.name', $search_value);
        $this->db->join('county', 'county.id = master_facility.county_id');
        $this->db->join('sub_county', 'sub_county.id = master_facility.sub_county_id');
        $this->db->join('consituency', 'consituency.id = master_facility.consituency_id');

        $this->db->limit(100);
        $query = $this->db->get('master_facility');
        //        $sql = "Select donor.id as donor_id, blood_group.id as group_id,blood_group.name as blood_name, id_no as id_no,sname,oname,cell_no,home_no,gender"
        //                . " from donor inner join blood_group on donor.blood_group = blood_group.id where id_no like '%$search_value%' or home_no like '%$search_value%' or  cell_no like '%$search_value%'";
        //
        //        $query = $this->db->query($sql);

        return $query->result();
    }

    public function commonGet($options)
    {
        $select = false;
        $table = false;
        $join = false;
        $order = false;
        $limit = false;
        $offset = false;
        $where = false;
        $or_where = false;
        $single = false;
        $where_not_in = false;
        $like = false;
        $group = false;

        extract($options);

        if ($select != false) {
            $this->db->select($select);
        }

        if ($table != false) {
            $this->db->from($table);
        }

        if ($where != false) {
            $this->db->where($where);
        }

        if ($where_not_in != false) {
            foreach ($where_not_in as $key => $value) {
                if (count($value) > 0) {
                    $this->db->where_not_in($key, $value);
                }
            }
        }

        if ($like != false) {
            $this->db->like($like);
        }

        if ($or_where != false) {
            $this->db->or_where($or_where);
        }

        if ($limit != false) {
            if (!is_array($limit)) {
                $this->db->limit($limit);
            } else {
                foreach ($limit as $limitval => $offset) {
                    $this->db->limit($limitval, $offset);
                }
            }
        }


        if ($order != false) {
            foreach ($order as $key => $value) {
                if (is_array($value)) {
                    foreach ($order as $orderby => $orderval) {
                        $this->db->order_by($orderby, $orderval);
                    }
                } else {
                    $this->db->order_by($key, $value);
                }
            }
        }


        if ($group != false) {
            $this->db->group_by($group);
        }


        if ($join != false) {
            foreach ($join as $key => $value) {
                if (is_array($value)) {
                    if (count($value) == 3) {
                        $this->db->join($value[0], $value[1], $value[2]);
                    } else {
                        foreach ($value as $key1 => $value1) {
                            $this->db->join($key1, $value1);
                        }
                    }
                } else {
                    $this->db->join($key, $value);
                }
            }
        }


        $query = $this->db->get();

        if ($single) {
            return $query->row();
        }


        return $query->result();
    }

    public function Set_session($id)
    {
        $subcounty_id = "";
        $this->db->trans_start();
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('clinic', 'clinic.id = users.clinic_id');
        $this->db->where('users.id', $id);
        $get_user_details = $this->db->get()->result();

        foreach ($get_user_details as $value) {
            $fname = $value->f_name;
            $mname = $value->m_name;
            $lname = $value->l_name;
            $facility_id = $value->facility_id;
            $email = $value->e_mail;
            $partner_id = $value->partner_id;
            $donor_id = $value->donor_id;
            $first_access = $value->first_access;
            $access_level = $value->access_level;
            $status = $value->status;
            $phone_no = $value->phone_no;
            $view_client = $value->view_client;
            $county_id = $value->county_id;
            $subcounty_id = $value->subcounty_id;
            $clinic_id = $value->clinic_id;
            $clinic_name = $value->name;




            if (!empty($donor_id) and $donor_id > 0 and $access_level == 'Donor') {
                $get_donor_logo = $this->db->get_where('donor', array('id' => $donor_id))->result();
                foreach ($get_donor_logo as $value) {
                    $donor_logo = $value->donor_logo;
                    $donor_name = $value->name;
                    $newsession = array(
                        'user_id' => $id,
                        'Fullname' => $fname . ' ' . $mname . ' ' . $lname,
                        'email' => $email,
                        'logged_in' => true,
                        'status' => $status,
                        'partner_id' => $partner_id,
                        'donor_id' => $donor_id,
                        'first_access' => $first_access,
                        'access_level' => $access_level,
                        'phone_no' => $phone_no,
                        'facility_id' => $facility_id,
                        'logo' => $donor_logo,
                        'view_client' => $view_client,
                        'donor_name' => $donor_name
                    );
                }
            } elseif (!empty($partner_id) and $partner_id > 0 and $access_level == 'Partner') {
                $get_partner_logo = $this->db->get_where('partner', array('id' => $partner_id))->result();
                foreach ($get_partner_logo as $value) {
                    $partner_logo = $value->name;
                    $partner_name = $value->name;
                    $newsession = array(
                        'user_id' => $id,
                        'Fullname' => $fname . ' ' . $mname . ' ' . $lname,
                        'email' => $email,
                        'logged_in' => true,
                        'status' => $status,
                        'partner_id' => $partner_id,
                        'donor_id' => $donor_id,
                        'first_access' => $first_access,
                        'access_level' => $access_level,
                        'phone_no' => $phone_no,
                        'facility_id' => $facility_id,
                        'logo' => $partner_logo,
                        'view_client' => $view_client,
                        'partner_name' => $partner_name
                    );
                    $this->session->set_userdata($newsession);
                }
            } elseif (!empty($county_id) and $county_id > 0 and $access_level == 'County') {
                $get_county = $this->db->get_where('county', array('id' => $county_id))->result();
                foreach ($get_county as $value) {
                    $county_name = $value->name;

                    $newsession = array(
                        'user_id' => $id,
                        'Fullname' => $fname . ' ' . $mname . ' ' . $lname,
                        'email' => $email,
                        'logged_in' => true,
                        'status' => $status,
                        'partner_id' => $partner_id,
                        'donor_id' => $donor_id,
                        'first_access' => $first_access,
                        'access_level' => $access_level,
                        'phone_no' => $phone_no,
                        'county_id' => $county_id,
                        'view_client' => $view_client,
                        'county_name' => $county_name
                    );
                }
            } elseif (!empty($subcounty_id) and $subcounty_id > 0 and $access_level == 'Sub County') {
                $get_sub_county = $this->db->get_where('sub_county', array('id' => $subcounty_id))->result();
                foreach ($get_sub_county as $value) {
                    $sub_county_name = $value->name;
                    $newsession = array(
                        'user_id' => $id,
                        'Fullname' => $fname . ' ' . $mname . ' ' . $lname,
                        'email' => $email,
                        'logged_in' => true,
                        'status' => $status,
                        'partner_id' => $partner_id,
                        'donor_id' => $donor_id,
                        'first_access' => $first_access,
                        'access_level' => $access_level,
                        'phone_no' => $phone_no,
                        'subcounty_id' => $subcounty_id,
                        'view_client' => $view_client,
                        'sub_county_name' => $sub_county_name
                    );
                }
            } else {
                $newsession = array(
                    'user_id' => $id,
                    'Fullname' => $fname . ' ' . $mname . ' ' . $lname,
                    'email' => $email,
                    'logged_in' => true,
                    'status' => $status,
                    'partner_id' => $partner_id,
                    'donor_id' => $donor_id,
                    'first_access' => $first_access,
                    'access_level' => $access_level,
                    'phone_no' => $phone_no,
                    'facility_id' => $facility_id,
                    'view_client' => $view_client,
                    'admin__name' => 'Admin'
                );
            }
            if (!empty($facility_id) and $facility_id > 0 and $access_level == 'Facility') {
                $get_facility_name = $this->db->get_where('master_facility', array('code' => $facility_id))->result();
                foreach ($get_facility_name as $value) {
                    $facility_name = $value->name;


                    $get_clinic_name = $this->db->get_where('clinic', array('id' => $clinic_id))->result();
                    foreach ($get_clinic_name as $value) {
                        $clinic_name = $value->name;
                        $clinic_id = $value->id;

                        $newsession = array(
                            'user_id' => $id,
                            'Fullname' => $fname . ' ' . $mname . ' ' . $lname,
                            'email' => $email,
                            'logged_in' => true,
                            'status' => $status,
                            'partner_id' => $partner_id,
                            'donor_id' => $donor_id,
                            'first_access' => $first_access,
                            'access_level' => $access_level,
                            'phone_no' => $phone_no,
                            'facility_id' => $facility_id,
                            'view_client' => $view_client,
                            'facility_name' => $facility_name,
                            'clinic_id' => $clinic_id,
                            'clinic_name' => $clinic_name
                        );
                    }
                }
            }



            $this->session->set_userdata($newsession);

            $description = "User :$id logged in successfully to the  system.";

            $this->log_action($description);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
        } else {
        }
    }

    public function generate_otp($length = 6)
    {
        $str = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function check_auth()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');



        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $check_existence = $this->db->get_where('users', array('e_mail' => $username, 'status' => 'Active'))->num_rows();
            $get_user_pass = $this->db->get_where('users', array('e_mail' => $username, 'status' => 'Active'))->result_array();
        } else {
            $check_existence = $this->db->get_where('users', array('phone_no' => $username, 'status' => 'Active'))->num_rows();
            $get_user_pass = $this->db->get_where('users', array('phone_no' => $username, 'status' => 'Active'))->result_array();
        }




        if ($check_existence == 1) {
            foreach ($get_user_pass as $value) {
                $pass = $value['password'];
                $id = $value['id'];
                $user_id = $value['id'];
                $phone_no = $value['phone_no'];
                $first_access = $value['first_access'];
                $crypted_pass = crypt($password, $value['password']);

                if ($crypted_pass === $pass) {
                    if ($first_access == 'Yes') {
                        $Set_session = $this->Set_session($user_id);
                    } else {



                        // $one_time_pass = $this->generate_otp();
                        // $user_id = $id;


                        // $verified = '0';



                        // $this->db->trans_start();
                        // $data_insert = array(
                        //     'user_id' => $user_id,
                        //     'client_ip' => $_SERVER['REMOTE_ADDR'],
                        //     'public_ip' => $_SERVER['REMOTE_ADDR'],
                        //     'verified' => $verified,
                        //     'code' => $one_time_pass
                        // );

                        // $this->db->insert('tbl_otp', $data_insert);

                        // $this->db->trans_complete();
                        // if ($this->db->trans_status() === FALSE) {

                        // } else {

                        // }




                        // $destination = $phone_no;

                        // // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        // $this->config->load('config', TRUE);
                        // // Retrieve a config item named site_name contained within the blog_settings array
                        // $source = $this->config->item('shortcode', 'config');

                        // $msg = "Your OTP Code is : " . $one_time_pass . " ";
                        // $user_outgoing = "User";

                        // $send_message = $this->send_message($source, $destination, $msg,$user_outgoing);
                        // if ($send_message) {
                        //     $this->db->trans_start();

                        //     $data_outgoing = array(
                        //         'source' => $source,
                        //         'destination' => $destination,
                        //         'msg' => $msg,
                        //         'status' => 'Sent',
                        //         'message_type_id' => '5',
                        //         'responded' => 'No',
                        //         'clnt_usr_id' => $user_id,
                        //         'recepient_type' => 'User',
                        //         'created_by' => '1'
                        //     );
                        //     $this->db->insert('usr_outgoing', $data_outgoing);


                        //     $this->db->trans_complete();
                        //     if ($this->db->trans_status() === FALSE) {

                        //     } else {

                        //     }
                        // } else {
                        //     $this->db->trans_start();

                        //     $data_outgoing = array(
                        //         'source' => $source,
                        //         'destination' => $destination,
                        //         'msg' => $msg,
                        //         'status' => 'Not Sent',
                        //         'message_type_id' => '5',
                        //         'responded' => 'No',
                        //         'clnt_usr_id' => $user_id,
                        //         'recepient_type' => 'User',
                        //         'created_by' => '1'
                        //     );
                        //     $this->db->insert('usr_outgoing', $data_outgoing);


                        //     $this->db->trans_complete();
                        //     if ($this->db->trans_status() === FALSE) {

                        //     } else {

                        //     }
                        // }
                        $Set_session = $this->Set_session($user_id);
                        return 'Login success';
                    }
                } else {
                    return 'Wrong password';
                }
            }
        } else {
            return 'User does not exist';
        }
    }

    public function reset_password($password, $user_id)
    {
        $this->db->trans_start();
        $new_password = $this->cryptPass($password);
        $first_access = "No";
        $today = date('Y-m-d');
        $post_data = array(
            'password' => $new_password,
            'first_access' => $first_access,
            'last_pass_change' => $today,
            'updated_by' => $user_id
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "User :$user_id reset his/her password.";
            $this->log_action($description);
            return true;
        }
    }

    public function check_username($username)
    {
        return $this->db->get_where('users', array('username' => $username))->result_array();
    }

    public function check_phoneno($phoneno)
    {
        return $this->db->get_where('users', array('phone_no' => $phoneno));
    }

    public function assign_partner_facility($mfl_code, $status, $partner_id, $today)
    {
        $avg_active_clients = $this->input->post('avg_active_clients', true);
        $user_id = $this->session->userdata('user_id');
        $this->db->trans_start();
        $assigned = 1;

        $facility_option = array(
            'table' => 'master_facility',
            'where' => array('code' => $mfl_code)
        );
        $get_facility_info = $this->commonGet($facility_option);
        foreach ($get_facility_info as $value) {
            $sub_county_id = $value->Sub_County_ID;
            $county_id = $value->county_id;
            $status = "Active";
            $post_data = array(
                'mfl_code' => $mfl_code,
                'status' => $status,
                'partner_id' => $partner_id,
                'created_at' => $today,
                'county_id' => $county_id,
                'sub_county_id' => $sub_county_id,
                'status' => $status,
                'created_by' => $user_id,
                'avg_clients' => $avg_active_clients
            );
            $this->db->insert('partner_facility', $post_data);

            $data_update = array(
                'assigned' => $assigned,
                'updated_by' => $user_id
            );
            $this->db->where('code', $mfl_code);
            $this->db->update('master_facility', $data_update);
        }


        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Assigned partner a facility with MFL Code : $mfl_code.";
            $this->log_action($description);
            return true;
        }
    }

    public function check_email($e_mail)
    {
        return $this->db->get_where('users', array('e_mail' => $e_mail));
    }

    public function check_user()
    {
        $f_name = $this->input->post('f_name', true);
        $l_name = $this->input->post('m_name', true);
        $m_name = $this->input->post('l_name', true);
        $status = $this->input->post('status', true);
        $partner_id = $this->input->post('partner_id', true);
        $facility_id = $this->input->post('facility_id', true);
        $donor_id = $this->input->post('donor_id', true);
        $dob = $this->input->post('dob');
        $e_mail = $this->input->post('e_mail');
        $phone_no = $this->input->post('phone_no');
        $access_level = $this->input->post('access_level', true);
        $daily_report = $this->input->post('daily_report', true);
        $weekly_report = $this->input->post('weekly_report', true);
        $monthly_report = $this->input->post('monthly_report', true);
        $month3_report = $this->input->post('month3_report', true);
        $month6_report = $this->input->post('month6_report', true);
        $yearly_report = $this->input->post('yearly_report', true);
        $role_names = $this->input->post('role_names', true);
        $view_bio_data = $this->input->post('bio_data', true);
        $rcv_app_list = $this->input->post('rcv_app_list', true);
        $county_id = $this->input->post('county_id', true);
        $sub_county_id = $this->input->post('subcounty_id', true);
        $clinic_id = $this->input->post('clinic_id', true);
        $today = date("Y-m-d H:i:s");

        $check_dob = str_replace('/', '-', $dob);
        $check_dob = date("Y-m-d", strtotime($check_dob));
        $current_year = date('Y');
        $unix_dob = strtotime($check_dob);
        $year_dob = date('Y', $unix_dob);
        $current_age = $current_year - $year_dob;
        $created_by = $this->session->userdata('user_id');

        $check_phone = $this->db->get_where('tbl_users', array('phone_no' => $phone_no))->num_rows();
        $check_mail = $this->db->get_where('tbl_users', array('e_mail' => $e_mail))->num_rows();
        $getMOB = $this->db->get_where('tbl_users', array('phone_no' => $phone_no))->result_array();

        if ($check_phone >= 1) {
            return 'PExists';
        } elseif ($check_mail >= 1) {
            return 'EExists';
        } elseif ($current_age < 18) {
            return 'UnderAge';
        } elseif ($check_phone == 0 && $check_mail == 0 && $current_age >= 18) {
            $this->data->add_user($created_by, $f_name, $m_name, $l_name, $dob, $e_mail, $status, $partner_id, $donor_id, $facility_id, $today, $phone_no, $access_level, $daily_report, $weekly_report, $monthly_report, $month3_report, $month6_report, $yearly_report, $role_names, $view_bio_data, $rcv_app_list, $county_id, $sub_county_id, $clinic_id);
            return 'Done';
        }
    }

    public function add_user($created_by, $f_name, $m_name, $l_name, $dob, $e_mail, $status, $partner_id, $donor_id, $facility_id, $today, $phone_no, $access_level, $daily_report, $weekly_report, $monthly_report, $month3_report, $month6_report, $yearly_report, $role_names, $view_bio_data, $rcv_app_list, $county_id, $sub_county_id, $clinic_id)
    {
        if (empty($clinic_id)) {
            $clinic_id = '1';
        } else {
        }
        $this->db->trans_start();
        $password = $this->cryptPass($phone_no);
        $first_access = "Yes";
        if ($access_level == "Partner") {
            if (empty($facility_id)) {
                $post_data = array(
                    'f_name' => $f_name,
                    'm_name' => $m_name,
                    'l_name' => $l_name,
                    'dob' => $dob,
                    'e_mail' => $e_mail,
                    'status' => $status,
                    'partner_id' => $partner_id,
                    'created_at' => $today,
                    'password' => $password,
                    'phone_no' => $phone_no,
                    'first_access' => $first_access,
                    'access_level' => $access_level,
                    'daily_report' => $daily_report,
                    'weekly_report' => $weekly_report,
                    'monthly_report' => $monthly_report,
                    'month3_report' => $month3_report,
                    'month6_report' => $month6_report,
                    'Yearly_report' => $yearly_report,
                    'view_client' => $view_bio_data,
                    'created_by' => $created_by,
                    'role_id' => $role_names
                );
            } else {
                $new_partner_id = $this->session->userdata('partner_id');
                $post_data = array(
                    'f_name' => $f_name,
                    'm_name' => $m_name,
                    'l_name' => $l_name,
                    'dob' => $dob,
                    'e_mail' => $e_mail,
                    'status' => $status,
                    'partner_id' => $new_partner_id,
                    'donor_id' => $donor_id,
                    'facility_id' => $facility_id,
                    'created_at' => $today,
                    'password' => $password,
                    'phone_no' => $phone_no,
                    'first_access' => $first_access,
                    'access_level' => $access_level,
                    'daily_report' => $daily_report,
                    'weekly_report' => $weekly_report,
                    'monthly_report' => $monthly_report,
                    'month3_report' => $month3_report,
                    'month6_report' => $month6_report,
                    'Yearly_report' => $yearly_report,
                    'view_client' => $view_bio_data,
                    'created_by' => $created_by,
                    'role_id' => $role_names,
                    'clinic_id' => $clinic_id
                );
            }
        } elseif ($access_level == "Facility") {
            $partner_id = $this->db->get_where('partner_facility', array('mfl_code' => $facility_id))->result();
            foreach ($partner_id as $value) {
                $new_partner_id = $value->partner_id;

                $post_data = array(
                    'f_name' => $f_name,
                    'm_name' => $m_name,
                    'l_name' => $l_name,
                    'dob' => $dob,
                    'e_mail' => $e_mail,
                    'status' => $status,
                    'facility_id' => $facility_id,
                    'partner_id' => $new_partner_id,
                    'created_at' => $today,
                    'password' => $password,
                    'phone_no' => $phone_no,
                    'first_access' => $first_access,
                    'access_level' => $access_level,
                    'daily_report' => $daily_report,
                    'weekly_report' => $weekly_report,
                    'monthly_report' => $monthly_report,
                    'month3_report' => $month3_report,
                    'month6_report' => $month6_report,
                    'Yearly_report' => $yearly_report,
                    'view_client' => $view_bio_data,
                    'created_by' => $created_by,
                    'rcv_app_list' => $rcv_app_list,
                    'role_id' => $role_names,
                    'clinic_id' => $clinic_id
                );
            }
        } elseif ($access_level == "County") {
            $partner_id = $this->db->get_where('partner_facility', array('county_id' => $county_id))->result();
            foreach ($partner_id as $value) {
                $new_partner_id = $value->partner_id;
                $post_data = array(
                    'f_name' => $f_name,
                    'm_name' => $m_name,
                    'l_name' => $l_name,
                    'dob' => $dob,
                    'e_mail' => $e_mail,
                    'status' => $status,
                    'created_at' => $today,
                    'password' => $password,
                    'phone_no' => $phone_no,
                    'first_access' => $first_access,
                    'access_level' => $access_level,
                    'daily_report' => $daily_report,
                    'weekly_report' => $weekly_report,
                    'monthly_report' => $monthly_report,
                    'month3_report' => $month3_report,
                    'month6_report' => $month6_report,
                    'Yearly_report' => $yearly_report,
                    'view_client' => $view_bio_data,
                    'created_by' => $created_by,
                    'county_id' => $county_id,
                    'partner_id' => $new_partner_id,
                    'role_id' => $role_names
                );
            }
        } elseif ($access_level == "Sub County") {
            $partner_id = $this->db->get_where('partner_facility', array('sub_county_id' => $sub_county_id))->result();
            foreach ($partner_id as $value) {
                $new_partner_id = $value->partner_id;

                $post_data = array(
                    'f_name' => $f_name,
                    'm_name' => $m_name,
                    'l_name' => $l_name,
                    'dob' => $dob,
                    'e_mail' => $e_mail,
                    'status' => $status,
                    'created_at' => $today,
                    'password' => $password,
                    'phone_no' => $phone_no,
                    'first_access' => $first_access,
                    'access_level' => $access_level,
                    'daily_report' => $daily_report,
                    'weekly_report' => $weekly_report,
                    'monthly_report' => $monthly_report,
                    'month3_report' => $month3_report,
                    'month6_report' => $month6_report,
                    'Yearly_report' => $yearly_report,
                    'view_client' => $view_bio_data,
                    'created_by' => $created_by,
                    'subcounty_id' => $sub_county_id,
                    'partner_id' => $new_partner_id,
                    'role_id' => $role_names
                );
            }
        } else {
            $post_data = array(
                'f_name' => $f_name,
                'm_name' => $m_name,
                'l_name' => $l_name,
                'dob' => $dob,
                'e_mail' => $e_mail,
                'status' => $status,
                'created_at' => $today,
                'password' => $password,
                'phone_no' => $phone_no,
                'first_access' => $first_access,
                'access_level' => $access_level,
                'daily_report' => $daily_report,
                'weekly_report' => $weekly_report,
                'monthly_report' => $monthly_report,
                'month3_report' => $month3_report,
                'month6_report' => $month6_report,
                'Yearly_report' => $yearly_report,
                'view_client' => $view_bio_data,
                'created_by' => $created_by,
                'role_id' => $role_names
            );
        }

        $this->db->insert('users', $post_data);

        $last_insert_id = $this->db->insert_id();

        $get_role_modules = array(
            'table' => 'role_module',
            'where' => array('role_id' => $role_names, 'status' => 'Active')
        );

        $module_data = $this->data->commonGet($get_role_modules);

        foreach ($module_data as $module_value) {
            $module_id = $module_value->module_id;
            $data = array(
                'user_id' => $last_insert_id,
                'module_id' => $module_id,
                'created_at' => $today,
                'created_by' => $created_by,
                'role_id' => $role_names
            );
            $this->db->insert('user_permission', $data);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        }
    }


    public function delete_user_roles($user_id)
    {
        $this->db->trans_start();
        $query = $this->db->query("select id from tbl_user_permission where user_id='$user_id'");
        foreach ($query->result() as $value) {
            $id = $value->id;
            $this->db->delete('user_permission', array('id' => $id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function update_user($f_name, $m_name, $l_name, $dob, $e_mail, $status, $partner_id, $donor_id, $facility_id, $today, $phone_no, $user_id, $access_level, $daily_report, $weekly_report, $monthly_report, $month3_report, $month6_report, $yearly_report, $role_name)
    {
        //  echo 'Output => f NAME => ' . $f_name . '</br> m NAME ' . $m_name . '</br> L NAME ' . $l_name . '</br> DOB  ' . $dob . '</br> EMAIL ' . $e_mail . '</br> STATUS ' . $status . '</br> PARTNER ID ' . $partner_id . '</br> DONOR ID  ' . $donor_id . '</br> FACILTY ID  ' . $facility_id . '</br> TODAY  ' . $today . '</br> PHONE NO  ' . $phone_no . '</br> USER ID  ' . $user_id . '</br> ACCESS LEVEL  ' . $access_level . '</br> DAILY REPORT ' .
        // $daily_report . '</br> WEEKLY REPORT ' . $weekly_report . '</br> MONTHLY REPORT ' . $monthly_report . '</br> MONTH 3 REPORT  ' . $month3_report . '</br> MONTH 6 REPORT  ' . $month6_report . '</br> YEARLY REPORT  ' . $yearly_report . '</br> ROLE ID  ' . $role_name;
        $updated_by = $this->session->userdata('user_id');

        $data_update = array(
            'f_name ' => $f_name,
            'm_name' => $m_name,
            'l_name' => $l_name,
            'dob' => $dob,
            'e_mail' => $e_mail,
            'status' => $status,
            'partner_id' => $partner_id,
            'donor_id' => $donor_id,
            'facility_id' => $facility_id,
            'phone_no' => $phone_no,
            'access_level' => $access_level,
            'daily_report' => $daily_report,
            'weekly_report' => $weekly_report,
            'monthly_report' => $monthly_report,
            'month3_report' => $month3_report,
            'month6_report' => $month6_report,
            'Yearly_report' => $yearly_report,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data_update);





        $this->db->delete('user_permission', array('user_id' => $user_id));




        $get_role_modules = array(
            'table' => 'role_module',
            'where' => array('role_id' => $role_name, 'status' => 'Active')
        );

        $module_data = $this->data->commonGet($get_role_modules);

        foreach ($module_data as $module_value) {
            $module_id = $module_value->module_id;

            // echo 'Module ID: ' . $module_id . '</br>';


            $user_permission_data = array(
                'user_id' => $user_id,
                'module_id' => $module_id,
                'created_at' => $today,
                'created_by' => $updated_by
            );


            $this->db->insert('user_permission', $user_permission_data);
        }
    }

    public function edit_user($f_name, $m_name, $l_name, $dob, $e_mail, $status, $partner_id, $donor_id, $facility_id, $today, $phone_no, $user_id, $access_level, $daily_report, $weekly_report, $monthly_report, $month3_report, $month6_report, $yearly_report, $role_name, $view_bio_data, $rcv_app_list, $county_id, $subcounty_id, $clinic_id)
    {
        if (empty($clinic_id)) {
            $clinic_id = '3';
        } else {
        }
        $check_email = $this->db->query("Select count(id) as email_check from tbl_users where e_mail='$e_mail'");
        $check_phoneno = $this->db->query("Select count(id) as phone_check from tbl_users where phone_no='$phone_no'");
        $check_email_existence = $check_email->num_rows();
        $check_phoneno_existence = $check_phoneno->num_rows();


        $check_dob = str_replace('/', '-', $dob);
        $check_dob = date("Y-m-d", strtotime($check_dob));
        $current_year = date('Y');
        $unix_dob = strtotime($check_dob);
        $year_dob = date('Y', $unix_dob);
        $current_age = $current_year - $year_dob;
        $updated_by = $this->session->userdata('user_id');

        if ($current_age < 18) {
            $msg = "Under Age";
            return $msg;
        } else {
            if ($check_email_existence > 1 or $check_phoneno_existence > 1) {
                $get_email_count = $check_email->results();
                $get_phoneno_count = $check_phoneno->results();
                foreach ($get_email_count as $value_1) {
                    foreach ($get_phoneno_count as $value_2) {
                    }
                }

                foreach ($get_email_count as $value_1) {
                    foreach ($get_phoneno_count as $value_2) {
                    }
                }
                if ($check_email_existence > 1 and $check_phoneno_existence > 1) {
                    $msg = "Phone Email Taken";
                } elseif ($check_email_existence > 1) {
                    $msg = "Email Taken";
                } elseif ($check_phoneno_existence > 1) {
                    $msg = "Phone Taken";
                }
                return $msg;
            } else {
                $this->db->trans_start();


                if ($access_level == "Partner") {
                    if (empty($facility_id)) {
                        $post_data = array(
                            'f_name' => $f_name,
                            'm_name' => $m_name,
                            'l_name' => $l_name,
                            'dob' => $dob,
                            'e_mail' => $e_mail,
                            'status' => $status,
                            'partner_id' => $partner_id,
                            'phone_no' => $phone_no,
                            'access_level' => $access_level,
                            'daily_report' => $daily_report,
                            'weekly_report' => $weekly_report,
                            'monthly_report' => $monthly_report,
                            'month3_report' => $month3_report,
                            'month6_report' => $month6_report,
                            'Yearly_report' => $yearly_report,
                            'view_client' => $view_bio_data,
                            'updated_by' => $updated_by,
                            'role_id' => $role_name
                        );
                        $this->db->where('id', $user_id);
                        $this->db->update('users', $post_data);
                    } else {
                        $new_partner_id = $this->session->userdata('partner_id');
                        if ($new_partner_id == '0') {
                            $partner_id = $partner_id;
                        } else {
                            $partner_id = $new_partner_id;
                        }

                        $post_data = array(
                            'f_name' => $f_name,
                            'm_name' => $m_name,
                            'l_name' => $l_name,
                            'dob' => $dob,
                            'e_mail' => $e_mail,
                            'status' => $status,
                            'partner_id' => $partner_id,
                            'phone_no' => $phone_no,
                            'access_level' => $access_level,
                            'daily_report' => $daily_report,
                            'weekly_report' => $weekly_report,
                            'monthly_report' => $monthly_report,
                            'month3_report' => $month3_report,
                            'month6_report' => $month6_report,
                            'Yearly_report' => $yearly_report,
                            'view_client' => $view_bio_data,
                            'updated_by' => $updated_by,
                            'role_id' => $role_name
                        );
                        $this->db->where('id', $user_id);
                        $this->db->update('users', $post_data);
                    }
                } elseif ($access_level == "Facility") {
                    $partner_id = $this->db->get_where('partner_facility', array('mfl_code' => $facility_id))->result();
                    foreach ($partner_id as $value) {
                        $new_partner_id = $value->partner_id;

                        $post_data = array(
                            'f_name' => $f_name,
                            'm_name' => $m_name,
                            'l_name' => $l_name,
                            'dob' => $dob,
                            'e_mail' => $e_mail,
                            'status' => $status,
                            'partner_id' => $new_partner_id,
                            'facility_id' => $facility_id,
                            'phone_no' => $phone_no,
                            'access_level' => $access_level,
                            'daily_report' => $daily_report,
                            'weekly_report' => $weekly_report,
                            'monthly_report' => $monthly_report,
                            'month3_report' => $month3_report,
                            'month6_report' => $month6_report,
                            'Yearly_report' => $yearly_report,
                            'view_client' => $view_bio_data,
                            'updated_by' => $updated_by,
                            'rcv_app_list' => $rcv_app_list,
                            'role_id' => $role_name,
                            'clinic_id' => $clinic_id
                        );
                        $this->db->where('id', $user_id);
                        $this->db->update('users', $post_data);
                    }
                } elseif ($access_level == "County") {
                    $post_data = array(
                        'f_name' => $f_name,
                        'm_name' => $m_name,
                        'l_name' => $l_name,
                        'dob' => $dob,
                        'e_mail' => $e_mail,
                        'status' => $status,
                        'created_at' => $today,
                        'phone_no' => $phone_no,
                        'access_level' => $access_level,
                        'daily_report' => $daily_report,
                        'weekly_report' => $weekly_report,
                        'monthly_report' => $monthly_report,
                        'month3_report' => $month3_report,
                        'month6_report' => $month6_report,
                        'Yearly_report' => $yearly_report,
                        'view_client' => $view_bio_data,
                        'created_by' => $updated_by,
                        'county_id' => $county_id,
                        'role_id' => $role_name
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $post_data);
                } elseif ($access_level == "Sub County") {
                    $post_data = array(
                        'f_name' => $f_name,
                        'm_name' => $m_name,
                        'l_name' => $l_name,
                        'dob' => $dob,
                        'e_mail' => $e_mail,
                        'status' => $status,
                        'created_at' => $today,
                        'phone_no' => $phone_no,
                        'access_level' => $access_level,
                        'daily_report' => $daily_report,
                        'weekly_report' => $weekly_report,
                        'monthly_report' => $monthly_report,
                        'month3_report' => $month3_report,
                        'month6_report' => $month6_report,
                        'Yearly_report' => $yearly_report,
                        'view_client' => $view_bio_data,
                        'updated_by' => $updated_by,
                        'subcounty_id' => $subcounty_id,
                        'role_id' => $role_name
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $post_data);
                } elseif ($access_level == "Donor") {
                    $post_data = array(
                        'f_name' => $f_name,
                        'm_name' => $m_name,
                        'l_name' => $l_name,
                        'dob' => $dob,
                        'e_mail' => $e_mail,
                        'status' => $status,
                        'donor_id' => $donor_id,
                        'phone_no' => $phone_no,
                        'access_level' => $access_level,
                        'daily_report' => $daily_report,
                        'weekly_report' => $weekly_report,
                        'monthly_report' => $monthly_report,
                        'month3_report' => $month3_report,
                        'month6_report' => $month6_report,
                        'Yearly_report' => $yearly_report,
                        'view_client' => $view_bio_data,
                        'updated_by' => $updated_by,
                        'role_id' => $role_name
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $post_data);
                } elseif ($access_level == "Admin") {
                    $post_data = array(
                        'f_name' => $f_name,
                        'm_name' => $m_name,
                        'l_name' => $l_name,
                        'dob' => $dob,
                        'e_mail' => $e_mail,
                        'status' => $status,
                        'phone_no' => $phone_no,
                        'access_level' => $access_level,
                        'daily_report' => $daily_report,
                        'weekly_report' => $weekly_report,
                        'monthly_report' => $monthly_report,
                        'month3_report' => $month3_report,
                        'month6_report' => $month6_report,
                        'Yearly_report' => $yearly_report,
                        'view_client' => $view_bio_data,
                        'updated_by' => $updated_by,
                        'role_id' => $role_name
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $post_data);
                } else {
                    $post_data = array(
                        'f_name' => $f_name,
                        'm_name' => $m_name,
                        'l_name' => $l_name,
                        'dob' => $dob,
                        'e_mail' => $e_mail,
                        'status' => $status,
                        'phone_no' => $phone_no,
                        'access_level' => $access_level,
                        'daily_report' => $daily_report,
                        'weekly_report' => $weekly_report,
                        'monthly_report' => $monthly_report,
                        'month3_report' => $month3_report,
                        'month6_report' => $month6_report,
                        'Yearly_report' => $yearly_report,
                        'view_client' => $view_bio_data,
                        'updated_by' => $updated_by,
                        'role_id' => $role_name
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $post_data);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                } else {
                    $description = "User details updated  for User ID : $user_id";
                    $this->log_action($description);

                    //#1 => Check if current roles are the  same
                    //#2 = >If same, check for any new modules that are not assisgned to the user
                    // #3 => Assign user the  new modules in the  system
                    //#4 => If roles are different , mark the  current roles to Disabled
                    //#5 => Assign new role to the  userand give him/her the role modules
                    // echo 'User ID =>  ' . $user_id . '<br>';
                    $get_current_roles_qry = $this->db->query("Select max(role_id) as role_id from tbl_user_permission where user_id='$user_id' AND status='Active' LIMIT 1 ");
                    $check_role_existence = $get_current_roles_qry->num_rows();
                    if ($check_role_existence > 0) {
                        //echo 'Role Exists ..<br>';
                        //Role Exists

                        $get_current_roles = $get_current_roles_qry->result();
                        foreach ($get_current_roles as $value) {
                            $role_id = $value->role_id;
                            //echo 'Current Role => ' . $role_id . 'Role Name => ' . $role_name . '</br>';
                            if ($role_id == $role_name) {
                                //echo 'Role has not changed, check if new modules were added to the role names <br> ';
                                //Role has not changed, check if new modules were added to the role names
                                $get_current_modules = $this->db->query("Select * from tbl_role_module where role_id='$role_name' and status='Active'")->result();
                                foreach ($get_current_modules as $value) {
                                    $role_module_id = $value->module_id;

                                    // echo 'Role Module ID => ' . $role_module_id . '</br>';
                                    $check_user_permission_existence = $this->db->query("Select * from tbl_user_permission where module_id='$role_module_id' and role_id='$role_id' and user_id='$user_id' and status='Active' ")->num_rows();
                                    if ($check_user_permission_existence > 0) {
                                        //Module Exist
                                        //echo 'Module exists in the  Role Name <br> ';
                                    } else {
                                        //Module does not exist....
                                        // echo 'New Module ' . $role_module_id . 'was added to the Role Name <br> ';
                                        $this->db->trans_start();

                                        $data_insert = array(
                                            'module_id' => $role_module_id,
                                            'user_id' => $user_id,
                                            'role_id' => $role_id,
                                            'status' => 'Active',
                                            'created_by' => $updated_by,
                                            'created_at' => $today
                                        );
                                        $this->db->insert('user_permission', $data_insert);

                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === false) {
                                        } else {
                                            $description = "New User permissions created  for User ID : $user_id";
                                            $this->log_action($description);
                                        }
                                    }
                                }
                            } else {
                                //echo '1=> Role has changed    2=> Update table user permission mark all permission to be active <br> ';
                                //Role has changed
                                //Update table user permission mark all permission to be active
                                $get_current_user_permission_qry = $this->db->query("Select * from tbl_user_permission where user_id='$user_id' and role_id='$role_id'  ")->result();
                                foreach ($get_current_user_permission_qry as $value) {
                                    $user_pessmision_id = $value->id;
                                    // echo 'User permission marked as In Active are => ' . $user_pessmision_id . '';
                                    $this->db->trans_start();
                                    $user_permission_update = array(
                                        'status' => 'Disabled',
                                        'updated_by' => $updated_by
                                    );
                                    $this->db->where('id', $user_pessmision_id);
                                    $this->db->update('user_permission', $user_permission_update);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === false) {
                                    } else {
                                        $description = "User permissions updated  for User ID : $user_id";
                                        $this->log_action($description);
                                    }
                                }

                                $get_new_user_roles = $this->db->query("Select * from tbl_role_module where role_id='$role_name' and status='Active' ")->result();
                                foreach ($get_new_user_roles as $value) {
                                    $role_id = $value->role_id;
                                    $module_id = $value->module_id;
                                    // echo 'New Modules assigned to users => ' . $module_id . ' and role id =>  ' . $role_id . '';

                                    $this->db->trans_start();

                                    $user_permission_insert = array(
                                        'module_id' => $module_id,
                                        'user_id' => $user_id,
                                        'role_id' => $role_id,
                                        'status' => 'Active',
                                        'created_by' => $updated_by,
                                        'created_at' => $today
                                    );
                                    $this->db->insert('user_permission', $user_permission_insert);

                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === false) {
                                    } else {
                                        $description = "New User permissions created  for User ID : $user_id";
                                        $this->log_action($description);
                                    }
                                }
                            }
                        }




                        //Check if User permission was assigned previously but should be reomoved from the  system currently
                        //Get the active user permission from the  system
                        //Check if the  user persmissions exists in the  table role module

                        $get_user_persmission = $this->db->query("Select * from tbl_user_permission where user_id='$user_id' and status='Active'");
                        $count_user_permission_rows = $get_user_persmission->num_rows();
                        if ($count_user_permission_rows > 1) {
                            $get_user_perm = $get_user_persmission->result();
                            foreach ($get_user_perm as $value) {
                                $role_id = $value->role_id;
                                $module_id = $value->module_id;
                                $user_permission_id = $value->id;

                                $check_role_module_existence = $this->db->query("Select * from tbl_role_module where role_id='$role_id' and module_id='$module_id' and status='Active'");
                                $role_module_num_rows = $check_role_module_existence->num_rows();
                                if ($role_module_num_rows > 0) {
                                    //Ignore , do no do anything
                                    //  echo 'Ingnore do nothing ...';
                                    // echo 'User permission ID => ' . $user_permission_id . '<br>';
                                } else {
                                    //echo 'Do something ....';
                                    //echo 'User permission ID => ' . $user_permission_id . '<br>';
                                    //Update the  user permission from
                                    $this->db->trans_start();
                                    $data_array = array(
                                        'status' => 'Disabled',
                                        'updated_by' => $updated_by
                                    );
                                    $this->db->where('id', $user_permission_id);
                                    $this->db->update('user_permission', $data_array);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === false) {
                                    } else {
                                        $description = "User permissions updated  for User ID : $user_id";
                                        $this->log_action($description);
                                    }
                                }
                            }
                        } else {
                        }
                    } else {
                        //Role Does not Exist
                        //Role has changed
                        //Update table user permission mark all permission to be active
                        //echo '1=> Role Does not Exist Role has changed   Update table user permission mark all permission to be active <br>  ';
                        $get_current_user_permission_qry = $this->db->query("Select * from tbl_user_permission where user_id='$user_id' ")->result();
                        foreach ($get_current_user_permission_qry as $value) {
                            $user_pessmision_id = $value->id;

                            $this->db->trans_start();
                            $user_permission_update = array(
                                'status' => 'Disabled',
                                'updated_by' => $updated_by
                            );
                            $this->db->where('id', $user_pessmision_id);
                            $this->db->update('user_permission', $user_permission_update);
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === false) {
                            } else {
                                $description = "User permissions updated  for User ID : $user_id";
                                $this->log_action($description);
                            }
                        }
                        // echo '  Get All new roles and assign them to the  user <br> ';
                        //Get All new roles and assign them to the  user
                        $get_new_user_roles = $this->db->query("Select * from tbl_role_module where role_id='$role_name'")->result();
                        foreach ($get_new_user_roles as $value) {
                            $role_id = $value->role_id;
                            $module_id = $value->module_id;


                            $this->db->trans_start();

                            $user_permission_insert = array(
                                'role_id' => $role_id,
                                'module_id' => $module_id,
                                'user_id' => $user_id,
                                'created_by' => $updated_by,
                                'created_at' => $today
                            );
                            $this->db->insert('user_permission', $user_permission_insert);

                            $this->db->trans_complete();
                            if ($this->db->trans_status() === false) {
                                return false;
                            } else {
                                $description = "New user permissions created  for User ID : $user_id";
                                $this->log_action($description);
                                return true;
                            }
                        }
                    }
                }


                return true;
            }
        }
    }

    public function edit_partner_facility($mfl_code, $partner_name, $avg_clients, $partner_facility_id, $today)
    {
        $this->db->trans_start();
        $status = "Active";
        $post_data = array(
            'mfl_code' => $mfl_code,
            'partner_id' => $partner_name,
            'avg_clients' => $avg_clients,
            'status' => $status
        );
        $this->db->where('id', $partner_facility_id);
        $this->db->update('partner_facility', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated :$partner_facility_id  from the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function delete_partner_facility($partner_facility_id)
    {
        $this->db->trans_start();
        $get_mfl_code = $this->db->get_where('partner_facility', array('id' => $partner_facility_id));
        if ($get_mfl_code->num_rows() > 0) {
            foreach ($get_mfl_code->result() as $value) {
                $mfl_code = $value->mfl_code;
                $data_update = array(
                    'assigned' => '0'
                );
                $this->db->where('code', $mfl_code);
                $this->db->update('master_facility', $data_update);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                } else {
                    $this->db->trans_start();
                    $this->db->delete('partner_facility', array('id' => $partner_facility_id));
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === false) {
                        return false;
                    } else {
                        $description = "Permanently deleted :$partner_facility_id  from the  system.";
                        $this->log_action($description);
                        return true;
                    }
                }
            }
        } else {
        }
    }

    public function delete_user($user_id)
    {
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted :$user_id  from the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function reset_user($user_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $get_user_details = $this->db->get_where('users', array('id' => $user_id));
        $count_row = $get_user_details->num_rows();
        if ($count_row > 0) :
            $get_row = $get_user_details->result();
            foreach ($get_row as $value) :
                $phone_no = $value->phone_no;
                $get_password = $this->cryptPass($phone_no);
                $this->db->trans_start();

                $post_data = array(
                    'first_access' => "Yes",
                    'password' => $get_password,
                    'updated_by' => $updated_by
                );
                $this->db->where('id', $user_id);
                $this->db->update('users', $post_data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    return false;
                } else {
                    $description = "Password reset for User ID : $user_id in the  System .";
                    $this->log_action($description);
                    return true;
                }
            endforeach;
        endif;
    }

    public function cryptPass($input, $rounds = 9)
    {
        $salt = "";
        $saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        for ($i = 0; $i < 22; $i++) :
            $salt .= $saltChars[array_rand($saltChars)];
        endfor;
        return crypt($input, sprintf("$2y$%02d$", $rounds) . $salt);
    }

    public function add_module($name, $controller, $function, $status, $today, $level, $description, $span, $icon)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'module' => $name,
            'controller' => $controller,
            'function' => $function,
            'status' => $status,
            'created_at' => $today,
            'status' => $status,
            'level' => $level,
            'description' => $description,
            'span_class' => $span,
            'icon_class' => $icon,
            'created_by' => $created_by
        );
        $this->db->insert('module', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added Module $name  to the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function edit_module($module_id, $name, $controller, $function, $status, $today, $level, $description, $span, $icon)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'module' => $name,
            'controller' => $controller,
            'function' => $function,
            'status' => $status,
            'created_at' => $today,
            'status' => $status,
            'level' => $level,
            'description' => $description,
            'span_class' => $span,
            'icon_class' => $icon,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $module_id);
        $this->db->update('module', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Module $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function delete_module($module_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $module_id);
        $this->db->update('module', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted  Module ID $module_id  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function add_partner($name, $description, $location, $status, $partner_type_id, $e_mail, $phone_no, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'location' => $location,
            'status' => $status,
            'partner_type_id' => $partner_type_id,
            'e_mail' => $e_mail,
            'phone_no' => $phone_no,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('partner', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Partner $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function edit_partner($partner_id, $name, $description, $location, $status, $partner_type_id, $e_mail, $phone_no, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'location' => $location,
            'status' => $status,
            'partner_type_id' => $partner_type_id,
            'e_mail' => $e_mail,
            'phone_no' => $phone_no,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $partner_id);
        $this->db->update('partner', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Partner $name  details in  the  system.";

            $this->log_action($description);
            return true;
        }
    }

    public function delete_partner($partner_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $partner_id);
        $this->db->update('partner', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Teemporarily deleted  Partner $partner_id  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function add_role($name, $description, $status, $access_level, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'access_level' => $access_level,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('role', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Role $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function edit_role($role_id, $name, $description, $status, $access_level, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'access_level' => $access_level,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $role_id);
        $this->db->update('role', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Role $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function delete_role($role_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $role_id);
        $this->db->update('role', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted Role ID $role_id  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function add_donor($name, $description, $status, $e_mail, $phone_no, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'e_mail' => $e_mail,
            'phone_no' => $phone_no,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('donor', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Donor $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function edit_donor($donor_id, $name, $description, $status, $e_mail, $phone_no)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'e_mail' => $e_mail,
            'phone_no' => $phone_no,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $donor_id);
        $this->db->update('donor', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Donor $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function delete_donor($donor_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $donor_id);
        $this->db->update('donor', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted Donor ID $donor_id  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function add_group($name, $description, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('groups', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Group $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function edit_group($group_id, $name, $description, $status)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $group_id);
        $this->db->update('groups', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Group $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function delete_group($group_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $group_id);
        $this->db->update('groups', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted Group ID $group_id  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function add_county($name, $code, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'code' => $code,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('county', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Group $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function edit_county($county_id, $name, $code, $status)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'code' => $code,
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $county_id);
        $this->db->update('county', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Group $name  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function delete_county($county_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $county_id);
        $this->db->update('county', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted Group ID $county_id  details in  the  system.";
            $this->log_action($description);
            return true;
        }
    }

    public function add_sender($name, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('sender', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Language $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_sender($sender_id, $name, $status, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $sender_id);
        $this->db->update('sender', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Language $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_sender($sender_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $sender_id);
        $this->db->update('sender', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporariy deleted  Language $sender_id  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function add_language($name, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('language', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Language $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_language($language_id, $name, $status, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $language_id);
        $this->db->update('language', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Language $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_language($language_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $language_id);
        $this->db->update('language', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporariy deleted  Language $language_id  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function add_frequency($name, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('frequency', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Frequency  $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_frequency($frequency_id, $name, $status, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $frequency_id);
        $this->db->update('frequency', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated   Frequency $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_frequency($frequency_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $frequency_id);
        $this->db->update('frequency', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted $frequency_id  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function add_time($name, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('time', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Time  $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_time($time_id, $name, $status, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $time_id);
        $this->db->update('time', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated   Time $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_time($time_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $time_id);
        $this->db->update('time', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted $time_id  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function add_content($content, $response, $status, $message_type_id, $language_id, $groups_id, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'content' => $content,
            'identifier' => $response,
            'message_type_id' => $message_type_id,
            'language_id' => $language_id,
            'group_id' => $groups_id,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('content', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Content $content  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_content($content_id, $content, $response, $status, $message_type_id, $language_id, $groups_id, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'content' => $content,
            'identifier' => $response,
            'message_type_id' => $message_type_id,
            'language_id' => $language_id,
            'group_id' => $groups_id,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $content_id);
        $this->db->update('content', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Content $content  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_content($content_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $content_id);
        $this->db->update('content', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted content ID :  $content_id  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function add_message($message, $response, $status, $message_type_id, $language_id, $groups_id, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'message' => $message,
            'identifier' => $response,
            'message_type_id' => $message_type_id,
            'language_id' => $language_id,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('messages', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Content $message  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_message($message_id, $message, $response, $status, $message_type_id, $language_id, $groups_id, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'message' => $message,
            'identifier' => $response,
            'message_type_id' => $message_type_id,
            'language_id' => $language_id,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $message_id);
        $this->db->update('messages', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Content $message  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_message($message_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $message_id);
        $this->db->update('messages', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporarily deleted message ID :  $message_id  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function add_client($registration_type, $clinic_number, $fname, $mname, $lname, $p_year, $condition, $group, $facilities, $frequency, $time, $mobile, $altmobile, $sharename, $lang, $smsenable, $appointment_type, $p_apptype1, $p_apptype2, $p_apptype3, $custom_appointsms, $today, $appdate, $sent_flag, $status, $gender, $marital, $enrollment_date, $art_date, $motivational_enable)
    {
        // $check_clinic_number = $this->db->get_where('client', array('clinic_number' => $clinic_number))->num_rows();
        $created_by = $this->session->userdata('user_id');
        $facility_id = $this->session->userdata('facility_id');
        $upn = $facility_id . $clinic_number;
        $check_clinic_number = $this->db->get_where('client', array('clinic_number' => $upn))->num_rows();

        if ($check_clinic_number > 0) {
            return true;
        } else {
            $this->db->trans_start();
            $enrollment_date2 = $enrollment_date;

            if (empty($enrollment_date)) {
            } else {
                $user_id = $this->session->userdata('user_id');
                if (!empty($enrollment_date)) {
                    $enrollment_date = str_replace('/', '-', $enrollment_date);
                    $enrollment_date = date("Y-m-d", strtotime($enrollment_date));
                }

                if (!empty($p_year)) {
                    $check_p_year = str_replace('/', '-', $p_year);
                    $unix_dob = strtotime(date("Y-m-d", strtotime($check_p_year)));
                }

                if (!empty($enrollment_date)) {
                    $check_enrollment_date = str_replace('/', '-', $enrollment_date);
                    $unix_enrollment_date = strtotime(date("Y-m-d", strtotime($check_enrollment_date)));

                    $date_diff = $unix_enrollment_date - $unix_dob;

                    if ($date_diff > 1) {
                    } else {
                        $msg = "Enrollment greater than DoB";
                        return $msg;
                    }
                }
            }

            if (empty($art_date)) {
            } else {
                $art_date = str_replace('/', '-', $art_date);
                $art_date = date("Y-m-d", strtotime($art_date));





                $check_p_year = str_replace('/', '-', $p_year);
                $unix_dob = strtotime(date("Y-m-d", strtotime($check_p_year)));

                $check_art_date = str_replace('/', '-', $art_date);
                $unix_art_date = strtotime(date("Y-m-d", strtotime($check_art_date)));

                $date_diff = $unix_art_date - $unix_dob;

                if ($date_diff > 1) {
                } else {
                    $msg = "ART greater than DoB";
                    return $msg;
                }
            }








            if (empty($art_date) and empty($enrollment_date)) {
            } else {
                $check_art_date = str_replace('/', '-', $art_date);
                $check_art_date = date("Y-m-d", strtotime($check_art_date));
                $unix_art_date = strtotime($check_art_date);

                $check_enrollment_date = str_replace('/', '-', $enrollment_date);
                $check_enrollment_date = date("Y-m-d", strtotime($check_enrollment_date));
                $unix_enrollment_date = strtotime($check_enrollment_date);

                $date_diff = $unix_enrollment_date - $unix_art_date;

                if ($date_diff > 1) {
                    $msg = "ART less than Enrollment";
                    return $msg;
                } else {
                }
            }




            if ($registration_type == "New") {
                $facility_id = $this->session->userdata('facility_id');



                $upn = $facility_id . $clinic_number;
                $client_type = "New";
            } elseif ($registration_type == "Transfer") {
                $client_type = "Transfer";
                $facility_id = $this->input->post('facility_id', true);

                $upn = $facility_id . $clinic_number;
            }


            $p_year = str_replace('/', '-', $p_year);
            $p_year = date("Y-m-d", strtotime($p_year));

            $current_date = date("Y-m-d");
            $current_date = date_create($current_date);
            $new_dob = date_create($p_year);
            $date_diff = date_diff($new_dob, $current_date);
            $diff = $date_diff->format("%R%a days");
            //$diff = substr($diff, 1, 2);

            $diff = substr($diff, 0);
            $diff = (int) $diff;

            $category = "";
            if ($diff >= 3650 and $diff <= 6935) {
                //Adolescent
                $category .= 2;
            } elseif ($diff >= 7300) {
                //Adult
                $category .= 1;
            }





            $partner_id = $this->session->userdata('partner_id');
            $mfl_code = $this->session->userdata('facility_id');
            $post_data = array(
                'clinic_number' => $facility_id . $clinic_number,
                'f_name' => $fname,
                'm_name' => $mname,
                'l_name' => $lname,
                'dob' => $p_year,
                'client_status' => $condition,
                'txt_frequency' => $frequency,
                'txt_time' => $time,
                'phone_no' => $mobile,
                'alt_phone_no' => $altmobile,
                'shared_no_name' => $sharename,
                'created_at' => $today,
                'group_id' => $category,
                'language_id' => $lang,
                'facility_id' => $facilities,
                'smsenable' => $smsenable,
                'created_at' => $today,
                'status' => $status,
                'partner_id' => $partner_id,
                'mfl_code' => $mfl_code,
                'gender' => $gender,
                'marital' => $marital,
                'enrollment_date' => $enrollment_date,
                'art_date' => $art_date,
                'motivational_enable' => $motivational_enable,
                'client_type' => $client_type,
                'created_by' => $user_id,
                'entry_point' => 'Web',
                'welcome_sent' => 'No'
            );
            $this->db->insert('client', $post_data);
            $client_id = $this->db->insert_id();

            //            echo "Client ID => ".$client_id;

            if (empty($appdate)) {
            } else {
                $appointment_existense = $this->db->get_where('appointment', array('client_id' => $client_id, 'app_type_1' => $p_apptype1, 'active_app' => '1'));
                $check_appointment_existense = $appointment_existense->num_rows();
                if ($check_appointment_existense > 0) {
                    //Do nothing ...
                } else {
                    $appdate = str_replace('/', '-', $appdate);
                    $appdate = date("Y-m-d", strtotime($appdate));

                    $app_status = "Booked";
                    $sent_flag = "0";
                    $appnt_data = array(
                        'appntmnt_date' => $appdate,
                        'app_type_1' => $p_apptype1,
                        'created_at' => $today,
                        'app_status' => $app_status,
                        'client_id' => $client_id,
                        'created_by' => $user_id,
                        'active_app' => '1',
                        'entry_point' => 'Web'
                    );
                    $this->db->insert('appointment', $appnt_data);
                }
            }






            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                return false;
            } else {
                $this->db->trans_start();

                $data_update = array(
                    'welcome_sent' => 'No'
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $data_update);

                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                } else {
                }
                $description = "Added  Client $clinic_number  details in  the  system.";
                $this->log_action($description);

                $description = "Booked  Client $clinic_number for an appointment on $appdate in  the  system.";
                $this->log_action($description);

                return true;
            }
        }
    }

    public function transfer_client($registration_type, $clinic_number, $fname, $mname, $lname, $p_year, $condition, $group, $facilities, $frequency, $time, $mobile, $altmobile, $sharename, $lang, $smsenable, $appointment_type, $p_apptype1, $p_apptype2, $p_apptype3, $custom_appointsms, $today, $appdate, $sent_flag, $status, $gender, $marital, $enrollment_date, $art_date, $wellnessenable, $motivational_enable)
    {
        $this->db->trans_start();

        $updated_by = $this->session->userdata('user_id');

        if (empty($enrollment_date)) {
        } else {
            $user_id = $this->session->userdata('user_id');
            if (!empty($enrollment_date)) {
                $enrollment_date = str_replace('/', '-', $enrollment_date);
                $enrollment_date = date("Y-m-d", strtotime($enrollment_date));
            }

            if (!empty($p_year)) {
                $check_p_year = str_replace('/', '-', $p_year);
                $unix_dob = strtotime(date("Y-m-d", strtotime($check_p_year)));
            }

            if (!empty($enrollment_date)) {
                $check_enrollment_date = str_replace('/', '-', $enrollment_date);
                $unix_enrollment_date = strtotime(date("Y-m-d", strtotime($check_enrollment_date)));

                $date_diff = $unix_enrollment_date - $unix_dob;

                if ($date_diff > 1) {
                } else {
                    $msg = "Enrollment greater than DoB";
                    return $msg;
                }
            }
        }

        if (empty($art_date)) {
        } else {
            $art_date = str_replace('/', '-', $art_date);
            $art_date = date("Y-m-d", strtotime($art_date));





            $check_p_year = str_replace('/', '-', $p_year);
            $unix_dob = strtotime(date("Y-m-d", strtotime($check_p_year)));

            $check_art_date = str_replace('/', '-', $art_date);
            $unix_art_date = strtotime(date("Y-m-d", strtotime($check_art_date)));

            $date_diff = $unix_art_date - $unix_dob;

            if ($date_diff > 1) {
            } else {
                $msg = "ART greater than DoB";
                return $msg;
            }
        }








        if (empty($art_date) and empty($enrollment_date)) {
        } else {
            $check_art_date = str_replace('/', '-', $art_date);
            $check_art_date = date("Y-m-d", strtotime($check_art_date));
            $unix_art_date = strtotime($check_art_date);

            $check_enrollment_date = str_replace('/', '-', $enrollment_date);
            $check_enrollment_date = date("Y-m-d", strtotime($check_enrollment_date));
            $unix_enrollment_date = strtotime($check_enrollment_date);

            $date_diff = $unix_enrollment_date - $unix_art_date;

            if ($date_diff > 1) {
                $msg = "ART less than Enrollment";
                return $msg;
            } else {
            }
        }




        if ($registration_type == "New") {
            $client_type = "Transfer";
            $facility_id = $this->input->post('facility_id', true);

            $upn = $facility_id . $clinic_number;
        } elseif ($registration_type == "Transfer") {
            $client_type = "Transfer";
            $facility_id = $this->input->post('facility_id', true);

            $upn = $facility_id . $clinic_number;
        }



        $check_clinic_no = $this->db->get_where('client', array('clinic_number' => $upn));
        if ($check_clinic_no->num_rows() > 0) {
            //Update
            $get_client_results = $check_clinic_no->result();

            foreach ($get_client_results as $value) {
                $client_id = $value->id;
                $previous_mfl_no = $value->mfl_code;
                $partner_id = $this->session->userdata('partner_id');
                $mfl_code = $this->session->userdata('facility_id');
                $post_data = array(
                    'clinic_number' => $upn,
                    'f_name' => $fname,
                    'm_name' => $mname,
                    'l_name' => $lname,
                    'dob' => $p_year,
                    'client_status' => $condition,
                    'txt_frequency' => $frequency,
                    'txt_time' => $time,
                    'phone_no' => $mobile,
                    'alt_phone_no' => $altmobile,
                    'shared_no_name' => $sharename,
                    'created_at' => $today,
                    'group_id' => $group,
                    'language_id' => $lang,
                    'facility_id' => $facilities,
                    'smsenable' => $smsenable,
                    'created_at' => $today,
                    'status' => $status,
                    'partner_id' => $partner_id,
                    'mfl_code' => $mfl_code,
                    'gender' => $gender,
                    'marital' => $marital,
                    'enrollment_date' => $enrollment_date,
                    'art_date' => $art_date,
                    'wellness_enable' => $wellnessenable,
                    'motivational_enable' => $motivational_enable,
                    'client_type' => $client_type,
                    'created_by' => $user_id,
                    'prev_clinic' => $previous_mfl_no,
                    'updated_by' => $updated_by
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $post_data);
            }
        } else {
            //Insert


            $facility_id = $this->input->post('facility_id', true);
            $partner_id = $this->session->userdata('partner_id');
            ///$mfl_code = $this->session->userdata('facility_id');
            $post_data = array(
                'clinic_number' => $upn,
                'f_name' => $fname,
                'm_name' => $mname,
                'l_name' => $lname,
                'dob' => $p_year,
                'client_status' => $condition,
                'txt_frequency' => $frequency,
                'txt_time' => $time,
                'phone_no' => $mobile,
                'alt_phone_no' => $altmobile,
                'shared_no_name' => $sharename,
                'created_at' => $today,
                'group_id' => $group,
                'language_id' => $lang,
                'facility_id' => $facilities,
                'smsenable' => $smsenable,
                'created_at' => $today,
                'status' => $status,
                'partner_id' => $partner_id,
                'mfl_code' => $mfl_code,
                'gender' => $gender,
                'marital' => $marital,
                'enrollment_date' => $enrollment_date,
                'art_date' => $art_date,
                'wellness_enable' => $wellnessenable,
                'motivational_enable' => $motivational_enable,
                'client_type' => $client_type,
                'created_by' => $user_id,
                'prev_clinic' => $facility_id,
                'created_by' => $updated_by
            );
            $this->db->insert('client', $post_data);
            $client_id = $this->db->insert_id();
        }



        if ($smsenable == "Yes") {
        }
        if (empty($appdate)) {
        } else {
            $appdate = str_replace('/', '-', $appdate);
            $appdate = date("Y-m-d", strtotime($appdate));

            $app_status = "Booked";
            $sent_flag = "0";
            $appnt_data = array(
                'appntmnt_date' => $appdate,
                'app_type_1' => $p_apptype1,
                'created_at' => $today,
                'app_status' => $app_status,
                'client_id' => $client_id,
                'created_by' => $user_id
            );
            $this->db->where('client_id', $client_id);
            $this->db->update('appointment', $appnt_data);
        }






        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Client $clinic_number  details in  the  system.";
            $this->log_action($description);

            $description = "Booked  Client $clinic_number for an appointment on $appdate in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function get_welcome_message($language, $message_type)
    {
        $content = array(
            'select' => 'content,content.id',
            'table' => 'content',
            'join' => array('message_types' => 'message_types.id = content.message_type_id'),
            'where' => array('content.language_id' => $language, 'message_types.name' => $message_type)
        );

        $data = $this->commonGet($content);

        return $data;
    }

    public function get_sender($partner_id)
    {
        $sender = array(
            'select' => 'name',
            'table' => 'sender',
            'where' => array('partner_id' => $partner_id, 'status' => 'Active')
        );

        $data = $this->commonGet($sender);
        foreach ($data as $value) {
            $sender = $value->name;

            return $sender;
        }
    }

    public function get_outgoing_sms($language_id, $group_id, $message_type, $identifier)
    {
        $content = array(
            'select' => 'content',
            'table' => 'content',
            'join' => array('message_types' => 'message_types.id = content.message_type_id', 'groups' => 'groups.id = content.group_id'),
            'where' => array('content.language_id' => $language_id, 'content.group_id' => $group_id, 'message_types.name' => $message_type, 'content.identifier' => $identifier)
        );

        $data = $this->commonGet($content);

        foreach ($data as $value) {
            $content = $value->content;

            return $content;
        }
    }

    public function update_client($client_id, $clinic_id, $clinic_number, $fname, $mname, $lname, $p_year, $condition, $group, $facilities, $frequency, $time, $mobile, $altmobile, $sharename, $lang, $smsenable, $appointment_type, $p_apptype1, $p_apptype2, $p_apptype3, $custom_appointsms, $today, $appdate, $status, $gender, $marital, $enrollment_date, $art_date, $wellnessenable, $motivational_enable, $app_kept)
    {
        $this->db->trans_start();



        $user_id = $this->session->userdata('user_id');
        if (empty($enrollment_date) and empty($art_date)) {
        } elseif (empty($enrollment_date) and !empty($art_date)) {
            $art_date = str_replace('/', '-', $art_date);
            $art_date = date("Y-m-d", strtotime($art_date));
        } elseif (!empty($enrollment_date) and empty($art_date)) {
            $enrollment_date = str_replace('/', '-', $enrollment_date);
            $enrollment_date = date("Y-m-d", strtotime($enrollment_date));
        } else {
            $art_date = str_replace('/', '-', $art_date);
            $art_date = date("Y-m-d", strtotime($art_date));
            $enrollment_date = str_replace('/', '-', $enrollment_date);
            $enrollment_date = date("Y-m-d", strtotime($enrollment_date));
        } {
        }




        $p_year = str_replace('/', '-', $p_year);
        $p_year = date("Y-m-d", strtotime($p_year));

        $current_date = date("Y-m-d");
        $current_date = date_create($current_date);
        $new_dob = date_create($p_year);
        $date_diff = date_diff($new_dob, $current_date);
        $diff = $date_diff->format("%R%a days");
        //$diff = substr($diff, 1, 2);

        $diff = substr($diff, 0);
        $diff = (int) $diff;

        $category = "";
        if ($diff >= 3650 and $diff <= 6935) {
            //Adolescent
            $category .= 2;
        } elseif ($diff >= 7300) {
            //Adult
            $category .= 1;
        }













        $partner_id = $this->session->userdata('partner_id');
        $post_data = array(
            'clinic_number' => $clinic_number,
            'f_name' => $fname,
            'm_name' => $mname,
            'l_name' => $lname,
            'dob' => $p_year,
            'client_status' => $condition,
            'txt_frequency' => $frequency,
            'txt_time' => $time,
            'phone_no' => $mobile,
            'alt_phone_no' => $altmobile,
            'shared_no_name' => $sharename,
            'created_at' => $today,
            'group_id' => $category,
            'language_id' => $lang,
            'facility_id' => $facilities,
            'smsenable' => $smsenable,
            'status' => $status,
            'partner_id' => $partner_id,
            'gender' => $gender,
            'marital' => $marital,
            'enrollment_date' => $enrollment_date,
            'art_date' => $art_date,
            'wellness_enable' => $wellnessenable,
            'motivational_enable' => $motivational_enable,
            'updated_by' => $user_id,
            'welcome_sent' => 'No'
        );
        $this->db->where('id', $client_id);
        $this->db->update('client', $post_data);





        if (empty($appdate)) {
        } else {
            $appdate = str_replace('/', '-', $appdate);
            $new_appdate = date("Y-m-d", strtotime($appdate));

            $appointment_existense = $this->db->get_where('appointment', array('client_id' => $client_id, 'app_type_1' => $p_apptype1, 'active_app' => '1'));
            $check_appointment_existense = $appointment_existense->num_rows();


            if ($check_appointment_existense > 0) {
                $appointment_query = $appointment_existense->result();
                foreach ($appointment_query as $value) {
                    $sent_flag = "0";
                    $id = $value->id;
                    $client_id = $value->client_id;
                    $appntmnt_date = $value->appntmnt_date;
                    $app_type_1 = $value->app_type_1;


                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;
                    $app_status = $value->app_status;
                    $app_msg = $value->app_msg;

                    $appnt_data = array(
                        'client_id' => $client_id,
                        'appntmnt_date' => $appntmnt_date,
                        'app_type_1' => $app_type_1,
                        'created_at' => $created_at,
                        'client_id' => $client_id,
                        'updated_at' => $updated_at,
                        'app_msg' => $app_msg,
                        'created_by' => $user_id
                    );
                    $this->db->insert('appointment_arch', $appnt_data);
                }




                if ($smsenable == "Yes" and !empty($appdate)) {
                    $appnt_data = array(
                        'updated_by' => $user_id,
                        'appointment_kept' => $app_kept,
                        'active_app' => '0'
                    );
                    $this->db->where('client_id', $client_id);
                    $this->db->where('app_type_1', $app_type_1);
                    $this->db->update('appointment', $appnt_data);
                } else {
                    $appnt_data = array(
                        'updated_by' => $user_id,
                        'appointment_kept' => $app_kept,
                        'active_app' => '0'
                    );
                    $this->db->where('client_id', $client_id);
                    $this->db->where('app_type_1', $app_type_1);
                    $this->db->update('appointment', $appnt_data);
                }







                $app_status = "Booked";
                $appnt_data = array(
                    'appntmnt_date' => $new_appdate,
                    'app_type_1' => $p_apptype1,
                    'created_at' => $today,
                    'app_status' => $app_status,
                    'client_id' => $client_id,
                    'sent_status' => 'Not Sent',
                    'created_by' => $user_id,
                    'active_app' => '1',
                    'entry_point' => 'Web'
                );

                $this->db->insert('appointment', $appnt_data);
            } else {
                // echo 'Not found , do an inseert of new appointment......';
                $app_status = "Booked";
                $appnt_data = array(
                    'appntmnt_date' => $new_appdate,
                    'app_type_1' => $p_apptype1,
                    'created_at' => $today,
                    'app_status' => $app_status,
                    'client_id' => $client_id,
                    'sent_status' => 'Not Sent',
                    'created_by' => $user_id,
                    'active_app' => '1',
                    'entry_point' => 'Web'
                );

                $this->db->insert('appointment', $appnt_data);
            }
        }


        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated client no $clinic_number details in the  system.";
            $this->log_action($description);


            $description = "Updated appointment for client no $clinic_number to $appdate in the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function add_appointment()
    {
        $this->db->trans_start();
        $client_id = $this->input->post('client_id', true);
        $app_date = $this->input->post('appointment_date', true);
        $app_type_1 = $this->input->post('appointment_type', true);
        $transacted_by = $this->session->userdata('user_id');

        $appdate = str_replace('/', '-', $app_date);
        $appdate = date("Y-m-d", strtotime($appdate));


        $get_client = $this->db->query("Select * from tbl_appointment where client_id='$client_id' and app_type_1='$app_type_1'");
        $get_client_row = $get_client->num_rows();


        if ($get_client_row > 0) {
            //Update client Appointemnt
            $get_client_result = $get_client->result();
            foreach ($get_client_result as $appointment_value) {
                $id = $appointment_value->id;
                $client_id = $appointment_value->client_id;
                $appntmnt_date = $appointment_value->appntmnt_date;
                $app_type_1 = $appointment_value->app_type_1;
                $created_at = $appointment_value->created_at;
                $updated_at = $appointment_value->updated_at;
                $app_status = $appointment_value->app_status;
                $app_msg = $appointment_value->app_msg;

                $appnt_data = array(
                    'client_id' => $client_id,
                    'appntmnt_date' => $appntmnt_date,
                    'app_type_1' => $app_type_1,
                    'created_at' => $created_at,
                    'app_status' => $app_status,
                    'client_id' => $client_id,
                    'updated_at' => $updated_at,
                    'app_msg' => $app_msg,
                    'app_status' => $app_status,
                    'created_by' => $transacted_by
                );
                $this->db->insert('appointment_arch', $appnt_data);
                $description = "Archived previous appointment details for Client ID $client_id .";
                // $this->log_action($description);
                //                $group_id = $client_value->group_id;
                //                $language_id = $client_value->language_id;
                //
                //                $client_name = " " . $client_value->f_name . " ";
                //
                //                $message_type = "Appointment";
                //                $identifier = 1;
                //                $get_msg = $this->get_outgoing_sms($language_id, $group_id, $message_type, $identifier);
                //
                //                $app_status = "Booked";
                //
                //
                //                $new_msg = str_replace("XXX", $client_name, $get_msg);
                //                $appointment_date = date("d-m-Y", strtotime($app_date));
                //                $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);
                //
                $appointment_update = array(
                    'active_app' => '0'
                );
                $this->db->where('id', $id);
                $this->db->update('appointment', $appointment_update);


                $description = "Updated appointment details for client id $client_id .";

                //$this->log_action($description);
                //Insert New Appointment .....


                $appdate = str_replace('/', '-', $app_date);
                $appdate = date("Y-m-d", strtotime($appdate));
                $today = date('Y-m-d H:i:s');
                $app_status = "Booked";
                $sent_flag = "0";
                $appnt_data = array(
                    'appntmnt_date' => $appdate,
                    'app_type_1' => $app_type_1,
                    'created_at' => $today,
                    'app_status' => $app_status,
                    'client_id' => $client_id,
                    'created_by' => $transacted_by,
                    'active_app' => '1',
                    'entry_point' => 'Web'
                );
                $this->db->insert('appointment', $appnt_data);
            }
        } else {
            //Create client Appointment ...
            //Insert New Appointment .....


            $appdate = str_replace('/', '-', $app_date);
            $appdate = date("Y-m-d", strtotime($appdate));
            $today = date('Y-m-d H:i:s');
            $app_status = "Booked";
            $sent_flag = "0";
            $appnt_data = array(
                'appntmnt_date' => $appdate,
                'app_type_1' => $app_type_1,
                'created_at' => $today,
                'app_status' => $app_status,
                'client_id' => $client_id,
                'created_by' => $transacted_by,
                'active_app' => '1',
                'entry_point' => 'Web'
            );
            $this->db->insert('appointment', $appnt_data);
        }






        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function update_appointment($client_id, $app_date, $app_kept, $appointment_id, $app_type_1)
    {
        $appointment_type = $this->db->get_where('appointment', array('client_id' => $client_id, 'app_type_1' => $app_type_1, 'active_app' => '1'));
        $check_appointment_existence = $appointment_type->num_rows();

        if ($check_appointment_existence > 0) {
            //Appointment exists , do an update on previous appointment and insert a new appointment....



            $user_id = $this->session->userdata('user_id');
            $app_date = str_replace('/', '-', $app_date);
            $app_date = date("Y-m-d", strtotime($app_date));
            $this->db->trans_start();
            $client_options = array(
                'table' => 'client',
                'where' => array('id' => $client_id, 'status' => 'Active')
            );

            $client_data = $this->commonGet($client_options);

            foreach ($client_data as $client_value) {
                $client_id = $client_value->id;



                $get_client = $this->db->query("Select * from tbl_appointment where id='$appointment_id'");
                $get_client_row = $get_client->num_rows();


                if ($get_client_row >= 1) {
                    $get_client_result = $get_client->result();
                    foreach ($get_client_result as $appointment_value) {
                        $id = $appointment_value->id;
                        $client_id = $appointment_value->client_id;
                        $appntmnt_date = $appointment_value->appntmnt_date;
                        $app_type_1 = $appointment_value->app_type_1;
                        $created_at = $appointment_value->created_at;
                        $updated_at = $appointment_value->updated_at;
                        $app_status = $appointment_value->app_status;
                        $app_msg = $appointment_value->app_msg;

                        $appnt_data = array(
                            'client_id' => $client_id,
                            'appntmnt_date' => $appntmnt_date,
                            'app_type_1' => $app_type_1,
                            'created_at' => $created_at,
                            'app_status' => $app_status,
                            'client_id' => $client_id,
                            'updated_at' => $updated_at,
                            'app_msg' => $app_msg,
                            'app_status' => $app_status,
                            'appointment_kept' => $app_kept,
                            'created_by' => $user_id
                        );
                        $this->db->insert('appointment_arch', $appnt_data);
                        $description = "Archived previous appointment details for Client ID $client_id .";
                        $this->log_action($description);


                        $group_id = $client_value->group_id;
                        $language_id = $client_value->language_id;

                        $client_name = " " . $client_value->f_name . " ";

                        $message_type = "Appointment";
                        $identifier = 1;
                        $get_msg = $this->get_outgoing_sms($language_id, $group_id, $message_type, $identifier);

                        $app_status = "Booked";


                        $new_msg = str_replace("XXX", $client_name, $get_msg);
                        $appointment_date = date("d-m-Y", strtotime($app_date));
                        $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);

                        $appointment_update = array(
                            'appointment_kept' => $app_kept,
                            'active_app' => '0'
                        );
                        $this->db->where('id', $id);
                        $this->db->update('appointment', $appointment_update);


                        $description = "Updated appointment details for client id $client_id .";

                        $this->log_action($description);


                        //Insert New Appointment .....


                        $appdate = str_replace('/', '-', $appdate);
                        $appdate = date("Y-m-d", strtotime($appdate));
                        $today = date('Y-m-d H:i:s');
                        $app_status = "Booked";
                        $sent_flag = "0";
                        $appnt_data = array(
                            'appntmnt_date' => $appdate,
                            'app_type_1' => $app_type_1,
                            'created_at' => $today,
                            'app_status' => $app_status,
                            'client_id' => $client_id,
                            'created_by' => $user_id,
                            'active_app' => '1',
                            'entry_point' => 'Web'
                        );
                        $this->db->insert('appointment', $appnt_data);
                    }
                } else {
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                return false;
            } else {
                return true;
            }
        } else {
            //New Appointment, do an insert


            $appdate = str_replace('/', '-', $appdate);
            $appdate = date("Y-m-d", strtotime($appdate));
            $today = date('Y-m-d H:i:s');
            $app_status = "Booked";
            $sent_flag = "0";
            $appnt_data = array(
                'appntmnt_date' => $appdate,
                'app_type_1' => $app_type_1,
                'created_at' => $today,
                'app_status' => $app_status,
                'client_id' => $client_id,
                'created_by' => $user_id,
                'active_app' => '1',
                'entry_point' => 'Web'
            );
            $this->db->insert('appointment', $appnt_data);
        }
    }

    public function edit_appointment($client_id, $app_date, $app_kept, $appointment_id, $app_type)
    {
        $appointment_type = $this->db->get_where('appointment', array('id' => $appointment_id));
        $check_appointment_existence = $appointment_type->num_rows();

        if ($check_appointment_existence > 0) {
            //Appointment exists , do an update on previous appointment and insert a new appointment....



            $user_id = $this->session->userdata('user_id');
            $app_date = str_replace('/', '-', $app_date);
            $app_date = date("Y-m-d", strtotime($app_date));
            $this->db->trans_start();
            $client_options = array(
                'table' => 'client',
                'where' => array('id' => $client_id, 'status' => 'Active')
            );

            $client_data = $this->commonGet($client_options);

            foreach ($client_data as $client_value) {
                $client_id = $client_value->id;



                $get_client = $this->db->query("Select * from tbl_appointment where id='$appointment_id'");
                $get_client_row = $get_client->num_rows();


                if ($get_client_row >= 1) {
                    $get_client_result = $get_client->result();
                    foreach ($get_client_result as $appointment_value) {
                        $id = $appointment_value->id;
                        $client_id = $appointment_value->client_id;
                        $appntmnt_date = $appointment_value->appntmnt_date;
                        $app_type_1 = $appointment_value->app_type_1;

                        $created_at = $appointment_value->created_at;
                        $updated_at = $appointment_value->updated_at;
                        $app_status = $appointment_value->app_status;
                        $app_msg = $appointment_value->app_msg;

                        $appnt_data = array(
                            'client_id' => $client_id,
                            'appntmnt_date' => $appntmnt_date,
                            'app_type_1' => $app_type_1,
                            'created_at' => $created_at,
                            'app_status' => $app_status,
                            'client_id' => $client_id,
                            'updated_at' => $updated_at,
                            'app_msg' => $app_msg,
                            'app_status' => $app_status,
                            'appointment_kept' => $app_kept,
                            'created_by' => $user_id
                        );
                        $this->db->insert('appointment_arch', $appnt_data);
                        $description = "Archived previous appointment details for Client ID $client_id .";
                        $this->log_action($description);


                        $group_id = $client_value->group_id;
                        $language_id = $client_value->language_id;

                        $client_name = " " . $client_value->f_name . " ";

                        $message_type = "Appointment";
                        $identifier = 1;
                        $get_msg = $this->get_outgoing_sms($language_id, $group_id, $message_type, $identifier);

                        $app_status = "Booked";


                        $new_msg = str_replace("XXX", $client_name, $get_msg);
                        $appointment_date = date("d-m-Y", strtotime($app_date));
                        $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);



                        $description = "Updated appointment details for client id $client_id .";

                        $this->log_action($description);


                        //Insert New Appointment .....


                        $appdate = str_replace('/', '-', $app_date);
                        $appdate = date("Y-m-d", strtotime($appdate));
                        $today = date('Y-m-d H:i:s');
                        $app_status = "Booked";
                        $sent_flag = "0";
                        $appnt_data = array(
                            'appntmnt_date' => $appdate,
                            'app_type_1' => $app_type,
                            'created_at' => $today,
                            'app_status' => $app_status,
                            'client_id' => $client_id,
                            'created_by' => $user_id,
                            'active_app' => '1',
                            'entry_point' => 'Web',
                            'app_msg' => $cleaned_msg
                        );
                        $this->db->where('id', $id);
                        $this->db->update('appointment', $appnt_data);
                    }
                } else {
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                return false;
            } else {
                return true;
            }
        } else {
            //New Appointment, do an insert


            $appdate = str_replace('/', '-', $appdate);
            $appdate = date("Y-m-d", strtotime($appdate));
            $today = date('Y-m-d H:i:s');
            $app_status = "Booked";
            $sent_flag = "0";
            $appnt_data = array(
                'appntmnt_date' => $appdate,
                'app_type_1' => $app_type_1,
                'created_at' => $today,
                'app_status' => $app_status,
                'client_id' => $client_id,
                'created_by' => $user_id,
                'active_app' => '1',
                'entry_point' => 'Web'
            );
            $this->db->insert('appointment', $appnt_data);
        }
    }

    public function send_manual_sms($client_id, $destination, $msg)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();

        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
        $this->config->load('config', true);
        // Retrieve a config item named site_name contained within the blog_settings array
        $source = $this->config->item('shortcode', 'config');



        $today = date("Y-m-d H:i:s");
        $status = "Not Sent";
        $message_type_id = 5;

        $post_data = array(
            'destination' => $destination,
            'source' => $source,
            'msg' => $msg,
            'status' => $status,
            'created_at' => $today,
            'clnt_usr_id' => $client_id,
            'message_type_id' => '5',
            'created_by' => $created_by,
            'recepient_type' => 'Client'
        );
        $this->db->insert('clnt_outgoing', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Sent a manual SMS to $destination through the System.";
            $this->log_action($description);

            return true;
        }
    }

    public function get_all_active_clients($group)
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


        if ($group == "All") {
            if ($access_level == "Partner") {
                $client_options = array(
                    'table' => 'client',
                    'where' => array('status' => 'Active', 'client.partner_id' => $partner_id)
                );
            } elseif ($access_level == "Facility") {
                $client_options = array(
                    'table' => 'client',
                    'where' => array('status' => 'Active', 'mfl_code' => $facility_id)
                );
            } else {
                $client_options = array(
                    'table' => 'client',
                    'where' => array('status' => 'Active')
                );
            }
        } else {
            if (empty($group)) {
                if ($access_level == "Partner") {
                    $client_options = array(
                        'table' => 'client',
                        'where' => array('status' => 'Active', 'client.partner_id' => $partner_id)
                    );
                } elseif ($access_level == "Facility") {
                    $client_options = array(
                        'table' => 'client',
                        'where' => array('status' => 'Active', 'mfl_code' => $facility_id)
                    );
                } else {
                    $client_options = array(
                        'table' => 'client',
                        'where' => array('status' => 'Active')
                    );
                }
            } else {
                if ($access_level == "Partner") {
                    $client_options = array(
                        'table' => 'client',
                        'where' => array('status' => 'Active', 'client.partner_id' => $partner_id)
                    );
                } elseif ($access_level == "Facility") {
                    $client_options = array(
                        'table' => 'client',
                        'where' => array('status' => 'Active', 'mfl_code' => $facility_id)
                    );
                } else {
                    $client_options = array(
                        'table' => 'client',
                        'where' => array('status' => 'Active')
                    );
                }
            }
        }


        $client_data = $this->commonGet($client_options);

        return $client_data;
    }

    public function get_all_active_users($target_access_level, $county_id, $sub_county_id, $mfl_code)
    {
        // $this->output->enable_profiler(TRUE);
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');


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
            $sql .= "Select DISTINCT * from tbl_users inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id where 1 ";
        } elseif ($access_level === "Facility") {

            //Facility Access Level
            $sql .= "Select DISTINCT * from tbl_users inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id where 1 ";
        } elseif ($access_level === "Admin") {

            //Administration in the  System
            //            $sql .= "Select count(DISTINCT tbl_users.id) as no_users from tbl_users inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id where 1 ";
            //Administration in the  System
            $sql .= "Select DISTINCT *  from tbl_users  ";
            // $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id";


            if (!empty($target_access_level)) {
                if ($target_access_level == '4') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_users.facility_id";
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
            //Administration in the System
            $sql .= "Select DISTINCT * from tbl_users  ";
            // $sql .= "inner join tbl_partner_facility on tbl_partner_facility.partner_id = tbl_users.partner_id";


            if (!empty($target_access_level)) {
                if ($target_access_level == '4') {
                    $sql .= "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_users.facility_id";
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



        return $client_data;
    }

    public function get_all_active_clients_appointments($group, $county_id, $sub_county_id, $mfl_code, $appointment_from, $appointment_to, $target_group)
    {
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $access_level = $this->session->userdata('access_level');

        $today = date("Y-m-d H:i:s");

        $sql = "Select * from tbl_client inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id inner join tbl_groups on tbl_groups.id = tbl_client.group_id WHERE 1 ";

        if ($group == "All") {
            if ($access_level == "Partner") {
                $sql .= " AND appntmnt_date >= CURDATE() and tbl_client.partner_id='$partner_id' ";
            } elseif ($access_level == "Facility") {
                $sql .= " AND appntmnt_date >= CURDATE() and tbl_client.mfl_code='$facility_id'  ";
            } else {
                $sql .= " AND appntmnt_date >= CURDATE() ";
            }
        } else {
            if (empty($group)) {
                if ($access_level == "Partner") {
                    $sql .= " AND appntmnt_date >= CURDATE() and tbl_client.partner_id='$partner_id' ";
                } elseif ($access_level == "Facility") {
                    $sql .= "  AND appntmnt_date >= CURDATE() and tbl_client.mfl_code='$facility_id' ";
                } else {
                    $sql .= " AND appntmnt_date >= CURDATE() ";
                }
            } else {
                if ($access_level == "Partner") {
                    $sql .= " AND  tbl_client.status='Active' and tbl_appointment.status='Active' and appntmnt_date >= CURDATE() and tbl_groups.id='$group' and tbl_client.partner_id='$partner_id'  ";
                } elseif ($access_level == "Facility") {
                    $sql .= " AND tbl_client.status='Active' and tbl_appointment.status='Active' and appntmnt_date >= CURDATE() and tbl_groups.id='$group' and tbl_client.mfl_code='$facility_id'  ";
                } else {
                    $sql .= " AND tbl_client.status='Active' and tbl_appointment.status='Active' and appntmnt_date >= CURDATE() and tbl_groups.id='$group'  ";
                }
            }
        }















        if ($target_group === "1") {
        } elseif ($target_group === "2") {
            if (!empty($appointment_from)) {
                $appointment_from = str_replace('/', '-', $appointment_from);
                $appointment_from = date("Y-m-d", strtotime($appointment_from));

                $sql .= " AND tbl_appointment.appntmnt_date >= $appointment_from ";
            }
            if (!empty($appointment_to)) {
                $appointment_to = str_replace('/', '-', $appointment_to);
                $appointment_to = date("Y-m-d", strtotime($appointment_to));

                $sql .= " AND tbl_appointment.appntmnt_date <= $appointment_to ";
            }
        } elseif ($target_group === "3") {
            $sql .= " AND tbl_appointment.appntmnt_date = 'Missed' ";
        } elseif ($target_group === "4") {
            echo $target_group;
            $sql .= " AND tbl_appointment.appntmnt_date = 'Defaulted' ";
        } elseif ($target_group === "5") {
            echo $target_group;
            $sql .= " AND tbl_appointment.appntmnt_date = 'LTFU' ";
        } else {
        }









        $query = $this->db->query($sql);

        $client_data = $query;
        //echo $sql;
        return $client_data;
    }

    public function approve_broadcast($broadcast_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $data_update = array(
            'is_approved' => 'Yes',
            'updated_by' => $updated_by
        );
        $this->db->where('id', $broadcast_id);
        $this->db->update('broadcast', $data_update);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function disapprove_broadcast($broadcast_id, $reason)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $data_update = array(
            'is_approved' => 'No',
            'reason' => $reason,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $broadcast_id);
        $this->db->update('broadcast', $data_update);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function approve_facility($mfl_code)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $data_update = array(
            'is_approved' => 'Yes',
            'updated_by' => $updated_by
        );
        $this->db->where('mfl_code', $mfl_code);
        $this->db->update('partner_facility', $data_update);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function disapprove_facility($mfl_code, $reason)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $data_update = array(
            'is_approved' => 'No',
            'reason' => $reason,
            'updated_by' => $updated_by
        );
        $this->db->where('mfl_code', $mfl_code);
        $this->db->update('partner_facility', $data_update);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function set_user_broadcast_sms($target_access_level, $county_id, $sub_county_id, $mfl_code, $broadcast_date, $broadcast_time, $broadcast_message)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();

        $broadcast_date = str_replace('/', '-', $broadcast_date);
        $broadcast_date = date("Y-m-d", strtotime($broadcast_date));
        $broadcast_name = $this->input->post('broadcast_name', true);
        $today = date("Y-m-d H:i:s");
        $data_insert = array(
            'name' => $broadcast_name,
            'created_at' => $today,
            'status' => 'Active',
            'msg' => $broadcast_message,
            'time_id' => $broadcast_time,
            'county_id' => $county_id,
            'sub_county_id' => $sub_county_id,
            'mfl_code' => $mfl_code,
            'broadcast_date' => $broadcast_date,
            'created_by' => $created_by
        );
        $this->db->insert('broadcast', $data_insert);
        $broadcast_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $county_id = $this->input->post('county', true);
            $sub_county_id = $this->input->post('sub_county', true);


            $this->db->trans_start();

            $msg = $broadcast_message;

            $clients = $this->get_all_active_users($target_access_level, $county_id, $sub_county_id, $mfl_code);

            foreach ($clients as $value) {
                $user_name = $value->f_name . " " . $value->m_name . " " . $value->l_name;
                $phone_no = $value->phone_no;
                $user_id = $value->id;
                $user_mfl_code = $value->facility_id;


                $new_msg = str_replace("XXX", $user_name, $msg);


                $destination = $phone_no;
                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', true);
                // Retrieve a config item named site_name contained within the blog_settings array
                $source = $this->config->item('shortcode', 'config');
                $today = date("Y-m-d H:i:s");
                $status = "Not Sent";
                $post_data = array(
                    'broadcast_id' => $broadcast_id,
                    'destination' => $destination,
                    'source' => $source,
                    'msg' => $new_msg,
                    'sms_status' => $status,
                    'created_at' => $today,
                    'time_id' => $broadcast_time,
                    'broadcast_date' => $broadcast_date,
                    'clnt_usr_id' => $user_id,
                    'recepient_type' => 'User',
                    'mfl_code' => $user_mfl_code,
                    'status' => 'Active',
                    'created_by' => $created_by
                );
                $this->db->insert('sms_queue', $post_data);






                $description = "Sent a user broadcast message to $destination .";
                $this->log_action($description);
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function set_broadcast_sms($group, $date_time, $broadcast_date, $target_group, $default_time, $defaultsms, $county_id, $sub_county_id, $mfl_code, $appointment_from, $appointment_to)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();

        $broadcast_date = str_replace('/', '-', $broadcast_date);
        $broadcast_date = date("Y-m-d", strtotime($broadcast_date));
        $broadcast_name = $this->input->post('broadcast_name', true);
        $today = date("Y-m-d H:i:s");
        $data_insert = array(
            'name' => $broadcast_name,
            'created_at' => $today,
            'status' => 'Active',
            'msg' => $defaultsms,
            'group_id' => $group,
            'time_id' => $default_time,
            'county_id' => $county_id,
            'sub_county_id' => $sub_county_id,
            'mfl_code' => $mfl_code,
            'target_group' => $target_group,
            'broadcast_date' => $broadcast_date,
            'created_by' => $created_by
        );
        $this->db->insert('broadcast', $data_insert);
        $broadcast_id = $this->db->insert_id();
        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);

        //$source = $this->get_sender($partner_id);
        $msg = $defaultsms;
        if ($target_group === "1") {
            $clients = $this->get_all_active_clients($group, $county_id, $sub_county_id, $mfl_code);

            foreach ($clients as $value) {
                $client_name = $value->f_name . " " . $value->m_name . " " . $value->l_name;
                $phone_no = $value->phone_no;
                $alt_phone_no = $value->alt_phone_no;
                $client_id = $value->id;
                $client_mfl_code = $value->mfl_code;
                if (empty($phone_no)) {
                    $destination = $alt_phone_no;
                } else {
                    $destination = $phone_no;
                }


                $source = "40146";
                $today = date("Y-m-d H:i:s");
                $status = "Not Sent";
                $post_data = array(
                    'broadcast_id' => $broadcast_id,
                    'destination' => $destination,
                    'source' => $source,
                    'msg' => $msg,
                    'sms_status' => $status,
                    'created_at' => $today,
                    'time_id' => $default_time,
                    'broadcast_date' => $broadcast_date,
                    'clnt_usr_id' => $client_id,
                    'recepient_type' => 'Client',
                    'mfl_code' => $client_mfl_code,
                    'status' => 'Active',
                    'created_by' => $created_by
                );
                $this->db->insert('sms_queue', $post_data);

                $description = "Sent a broadcast message to $destination .";
                $this->log_action($description);
            }
        } elseif ($target_group === "2") {
            $clients = $this->get_all_active_clients_appointments($group, $county_id, $sub_county_id, $mfl_code, $appointment_from, $appointment_to, $target_group);
            if ($clients->num_rows() > 0) {
            } else {
                foreach ($clients->result() as $value) {
                    $client_name = $value->f_name . " " . $value->m_name . " " . $value->l_name;
                    $phone_no = $value->phone_no;
                    $alt_phone_no = $value->alt_phone_no;
                    $client_id = $value->id;
                    $client_mfl_code = $value->mfl_code;
                    if (empty($phone_no)) {
                        $destination = $alt_phone_no;
                    } else {
                        $destination = $phone_no;
                    }


                    $source = "40146";
                    $today = date("Y-m-d H:i:s");
                    $status = "Not Sent";
                    $post_data = array(
                        'broadcast_id' => $broadcast_id,
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $msg,
                        'sms_status' => $status,
                        'created_at' => $today,
                        'time_id' => $date_time,
                        'broadcast_date' => $broadcast_date,
                        'clnt_usr_id' => $client_id,
                        'recepient_type' => 'Client',
                        'mfl_code' => $client_mfl_code,
                        'created_by' => $created_by
                    );
                    $this->db->insert('sms_queue', $post_data);
                    $description = "Sent a broadcast message to $destination .";
                    $this->log_action($description);
                }
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    //function edit_notification_flow($notification_id, $days, $status) {

    public function update_broadcast_sms($broadcast_id, $group, $date_time, $broadcast_date, $target_group, $default_time, $defaultsms, $county_id, $sub_county_id, $mfl_code)
    {
        $updated_by = $this->session->userdata('user_id');

        $county_id = $this->input->post('county', true);
        $sub_county_id = $this->input->post('sub_county', true);

        $broadcast_date = str_replace('/', '-', $broadcast_date);
        $broadcast_date = date("Y-m-d", strtotime($broadcast_date));
        $broadcast_name = $this->input->post('broadcast_name', true);
        $today = date("Y-m-d H:i:s");
        $this->db->trans_start();
        $data_insert = array(
            'name' => $broadcast_name,
            'created_at' => $today,
            'status' => 'Active',
            'msg' => $defaultsms,
            'group_id' => $group,
            'time_id' => $default_time,
            'county_id' => $county_id,
            'sub_county_id' => $sub_county_id,
            'mfl_code' => $mfl_code,
            'target_group' => $target_group,
            'broadcast_date' => $broadcast_date,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $broadcast_id);
        $this->db->update('broadcast', $data_insert);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
        } else {
            $get_broadcast_sms_queue = $this->db->get_where("sms_queue", array('broadcast_id' => $broadcast_id, 'status' => 'Active'))->result();
            foreach ($get_broadcast_sms_queue as $broadcast_value) {
                $this->db->trans_start();
                $sms_queue_id = $broadcast_value->id;
                $source = "40146";
                $today = date("Y-m-d H:i:s");
                $status = "Not Sent";
                $post_data = array(
                    'status' => "Temp Delete",
                    'updated_by' => $updated_by
                );

                $this->db->update('sms_queue', $post_data);
                $this->db->where('id', $sms_queue_id);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                } else {
                    $description = "Temporarily deleted  broadcast message $broadcast_name  and message ID $sms_queue_id.";
                    $this->log_action($description);

                    //$source = $this->get_sender($partner_id);
                    $msg = $defaultsms;
                    if ($target_group === "1") {
                        $clients = $this->get_all_active_clients($group, $county_id, $sub_county_id, $mfl_code);

                        foreach ($clients as $value) {
                            $client_name = $value->f_name . " " . $value->m_name . " " . $value->l_name;
                            $phone_no = $value->phone_no;
                            $alt_phone_no = $value->alt_phone_no;
                            $client_id = $value->id;
                            $client_mfl_code = $value->mfl_code;
                            if (empty($phone_no)) {
                                $destination = $alt_phone_no;
                            } else {
                                $destination = $phone_no;
                            }



                            $source = "40146";
                            $today = date("Y-m-d H:i:s");
                            $status = "Not Sent";
                            $this->db->trans_start();
                            $post_data = array(
                                'broadcast_id' => $broadcast_id,
                                'destination' => $destination,
                                'source' => $source,
                                'msg' => $msg,
                                'sms_status' => $status,
                                'created_at' => $today,
                                'time_id' => $date_time,
                                'broadcast_date' => $broadcast_date,
                                'clnt_usr_id' => $client_id,
                                'recepient_type' => 'Client',
                                'mfl_code' => $client_mfl_code,
                                'updated_by' => $updated_by
                            );
                            $this->db->insert('sms_queue', $post_data);


                            $this->db->trans_complete();
                            if ($this->db->trans_status() === false) {
                                // return FALSE;
                            } else {
                                $description = "Set a broadcast message to $destination .";
                                $this->log_action($description);
                                // return TRUE;
                            }
                        }
                    } elseif ($target_group === "2") {
                        $clients = $this->get_all_active_clients_appointments($group, $county_id, $sub_county_id, $mfl_code);

                        foreach ($clients as $value) {
                            $client_name = $value->f_name . " " . $value->m_name . " " . $value->l_name;
                            $phone_no = $value->phone_no;
                            $alt_phone_no = $value->alt_phone_no;
                            $client_id = $value->id;
                            $client_mfl_code = $value->mfl_code;
                            if (empty($phone_no)) {
                                $destination = $alt_phone_no;
                            } else {
                                $destination = $phone_no;
                            }


                            $source = "40146";
                            $today = date("Y-m-d H:i:s");
                            $status = "Not Sent";

                            $this->db->trans_start();

                            $post_data = array(
                                'broadcast_id' => $broadcast_id,
                                'destination' => $destination,
                                'source' => $source,
                                'msg' => $msg,
                                'sms_status' => $status,
                                'created_at' => $today,
                                'time_id' => $date_time,
                                'broadcast_date' => $broadcast_date,
                                'clnt_usr_id' => $client_id,
                                'recepient_type' => 'Client',
                                'mfl_code' => $client_mfl_code,
                                'created_by' => $updated_by
                            );
                            $this->db->insert('sms_queue', $post_data);


                            $this->db->trans_complete();
                            if ($this->db->trans_status() === false) {
                                // return FALSE;
                            } else {
                                $description = "Sent a broadcast message to $destination .";
                                $this->log_action($description);
                                //return TRUE;
                            }
                        }
                    }
                }
            }
        }
    }

    public function edit_notification_flow($notification_id, $days, $status)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();

        $post_data = array(
            'status' => $status,
            'days' => $days,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $notification_id);
        $this->db->update('notification_flow', $post_data);
        $description = "Updated notification flow details for $notification_id.";
        $this->log_action($description);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function log_action($description)
    {
        $this->db->trans_start();
        $today = date("Y-m-d H:i:s");
        $user_id = $this->session->userdata('user_id');

        $post_data = array(
            'user_id' => $user_id,
            'description' => $description,
            'created_at' => $today
        );
        $this->db->insert('sys_logs', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function get_top_modules()
    {
        $user_id = $this->session->userdata('user_id');
        $user_role_options = array(
            'table' => 'module',
            'join' => array('user_permission' => 'user_permission.module_id= module.id'),
            'where' => array('module.status' => 'Active', 'user_id' => $user_id, 'level' => '1', 'user_permission.status' => 'Active'),
            'order' => array('user_permission.module_id' => 'ASC')
        );

        $data = $this->data->commonGet(
            $user_role_options
        );
        return $data;
    }

    public function get_side_modules()
    {
        $user_id = $this->session->userdata('user_id');
        //        $user_role_options = array(
        //            'table' => 'module',
        //            'join' => array('user_permission' => 'user_permission.module_id= module.id'),
        //            'where' => array('module.status' => 'Active', 'user_id' => $user_id, 'user_permission.status' => 'Active'),
        //            'order' => array('module.order' => 'ASC')
        //        );
        //
        //        $data = $this->data->commonGet($user_role_options);

        $query = "SELECT   * 
FROM
  tbl_module 
  INNER JOIN tbl_user_permission 
    ON tbl_user_permission.module_id = tbl_module.id 
WHERE tbl_module.status = 'Active' 
  AND user_id = '$user_id' 
  AND tbl_user_permission.status = 'Active' GROUP BY tbl_module.`id`
ORDER BY tbl_module.order ASC";
        $data = $this->db->query($query)->result_array();

        return $data;
    }

    public function add_message_types($name, $description, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('message_types', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added message type $name";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_message_types($name, $description, $status, $today, $message_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'description' => $description,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $message_id);
        $this->db->update('message_types', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Edited message type $name";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_message_types($message_type_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $message_type_id);
        $this->db->update('message_types', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added message type ID $message_type_id";
            $this->log_action($description);
            return true;
        }
    }

    public function add_call_action($appointment_id, $reason)
    {
        $this->db->trans_start();
        $data_update = array(
            'no_calls' => $reason
        );
        $this->db->where('id', $appointment_id);
        $this->db->update('appointment', $data_update);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function add_homevisit_action($appointment_id, $reason)
    {
        $this->db->trans_start();
        $data_update = array(
            'home_visits' => $reason
        );
        $this->db->where('id', $appointment_id);
        $this->db->update('appointment', $data_update);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function add_msg_action($appointment_id, $reason, $client_id)
    {
        $today = date("Y-m-d H:i:s");
        $user_id = $this->session->userdata('user_id');
        $this->db->trans_start();
        $data_insert = array(
            'msg' => $reason,
            'appointment_id' => $appointment_id,
            'created_at' => $today,
            'created_by' => $user_id
        );
        $this->db->insert('tbl_appointment_msgs', $data_insert);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
        } else {
            $get_client = $this->db->get_where('client', array('id' => $client_id))->result();
            foreach ($get_client as $value) {
                $phone_no = $value->phone_no;


                //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', true);
                // Retrieve a config item named site_name contained within the blog_settings array
                $source = $this->config->item('shortcode', 'config');
                $this->db->trans_start();
                $outgoing = array(
                    'destination' => $phone_no,
                    'msg' => $reason,
                    'responded' => 'No',
                    'status' => 'Not Sent',
                    'message_type_id' => '5',
                    'source' => $source,
                    'clnt_usr_id' => $client_id,
                    'recepient_type' => 'Client',
                    'content_id' => ' ',
                    'created_at' => $today,
                    'created_by' => $user_id
                );
                $this->db->insert('outgoing', $outgoing);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                } else {
                    $get_client_app_msg = $this->db->get_where('appointment', array('id' => $appointment_id))->result();
                    foreach ($get_client_app_msg as $value) {
                        $no_msgs = $value->no_msgs + 1;
                        $this->db->trans_start();
                        $data_update = array(
                            'no_msgs' => $no_msgs,
                            'updated_by' => $user_id
                        );
                        $this->db->where('id', $appointment_id);
                        $this->db->update('appointment', $data_update);
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === false) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            }
        }
    }

    public function add_target_county($name, $status, $today)
    {
        $this->db->trans_start();
        $data_insert = array(
            'county_id' => $name,
            'status' => $status,
            'created_at' => $today
        );
        $this->db->insert('target_county', $data_insert);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function edit_target_county($target_county_id, $county_id, $status, $today)
    {
        $this->db->trans_start();
        $data_insert = array(
            'county_id' => $county_id,
            'status' => $status,
            'created_at' => $today
        );
        $this->db->where('id', $target_county_id);
        $this->db->update('target_county', $data_insert);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function add_target_facilities($mfl_code, $status, $today)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->trans_start();
        $data_insert = array(
            'mfl_code' => $mfl_code,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $user_id
        );
        $this->db->insert('target_facility', $data_insert);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function edit_target_facilities($target_facilities_id, $mfl_code, $status, $today)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->trans_start();
        $data_insert = array(
            'mfl_code' => $mfl_code,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $user_id
        );
        $this->db->where('id', $target_facilities_id);
        $this->db->update('target_facility', $data_insert);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }




    public function send_message($source, $destination, $msg, $outgoing_id)
    {
        $this->load->library('africastalking');
        //Africa's Talking Library.
        // require_once('AfricasTalkingGateway.php');
        //Africa's Talking API key and username.
        $username = 'mhealthkenya';
        $apikey = '9318d173cb9841f09c73bdd117b3c7ce3e6d1fd559d3ca5f547ff2608b6f3212';

        //Shortcode.

        $gateway = new AfricasTalkingGateway($username, $apikey);
        try {
            $results = $gateway->sendMessage($destination, $msg, $source);

            foreach ($results as $result) {
                $number = $result->number;
                $status = $result->status;
                $messageid = $result->messageId;
                $cost = $result->cost;
                $statusCode = $result->statusCode;
            }
            return true;
        } catch (GatewayException $e) {
            echo "Oops an error encountered while sending: " . $e->getMessage();
            return false;
        }
    }



    public function add_clinic($name, $status, $today)
    {
        $created_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'created_by' => $created_by
        );
        $this->db->insert('clinic', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Added  Language $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function edit_clinic($clinic_id, $name, $status, $today)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $post_data = array(
            'name' => $name,
            'status' => $status,
            'created_at' => $today,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $clinic_id);
        $this->db->update('clinic', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Updated  Language $name  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function delete_clinic($clinic_id)
    {
        $updated_by = $this->session->userdata('user_id');
        $this->db->trans_start();
        $status = "Disabled";
        $post_data = array(
            'status' => $status,
            'updated_by' => $updated_by
        );
        $this->db->where('id', $clinic_id);
        $this->db->update('clinic', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            $description = "Temporariy deleted  Language $clinic_id  details in  the  system.";
            $this->log_action($description);

            return true;
        }
    }

    public function getAggregateReports($partner, $county, $sub_county, $mfl_code)
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $this->db->select('*');
        $this->db->from('national_dashboard');
        if (!empty($partner)) {
            $this->db->where('partner_id', $partner);
        }
        if (!empty($county)) {
            $this->db->where('county_id', $county);
        }
        if (!empty($sub_county)) {
            $this->db->where('sub_county_id', $sub_county);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }

        if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        }
        if ($access_level == 'Facility') {
            $this->db->where('mfl_code', $facility_id);
        }
        if ($access_level == 'County') {
            $this->db->where('county_id', $county_id);
        }
        if ($access_level == 'Sub County') {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAggregateBarClientsData($partner, $county, $sub_county, $mfl_code)
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $this->db->select('*');
        $this->db->from('main_bar_clients_aggregate');
        if (!empty($partner)) {
            $this->db->where('partner_id', $partner);
        }
        if (!empty($county)) {
            $this->db->where('county_id', $county);
        }
        if (!empty($sub_county)) {
            $this->db->where('sub_county_id', $sub_county);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }
      
        if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        }
        if ($access_level == 'Facility') {
            $this->db->where('mfl_code', $facility_id);
        }
        if ($access_level == 'County') {
            $this->db->where('county_id', $county_id);
        }
        if ($access_level == 'Sub County') {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAggregateBarAppointmentsData($partner, $county, $sub_county, $mfl_code)
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $this->db->select('*');
        $this->db->from('main_bar_appointments_aggregate');
        if (!empty($partner)) {
            $this->db->where('partner_id', $partner);
        }
        if (!empty($county)) {
            $this->db->where('county_id', $county);
        }
        if (!empty($sub_county)) {
            $this->db->where('sub_county_id', $sub_county);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }

        if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        }
        if ($access_level == 'Facility') {
            $this->db->where('mfl_code', $facility_id);
        }
        if ($access_level == 'County') {
            $this->db->where('county_id', $county_id);
        }
        if ($access_level == 'Sub County') {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAggregateTableData($partner, $county, $sub_county, $mfl_code)
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $this->db->select('*');
        $this->db->from('age_distinction');
        if (!empty($partner)) {
            $this->db->where('partner_id', $partner);
        }
        if (!empty($county)) {
            $this->db->where('county_id', $county);
        }
        if (!empty($sub_county)) {
            $this->db->where('sub_county_id', $sub_county);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }

        if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        }
        if ($access_level == 'Facility') {
            $this->db->where('mfl_code', $facility_id);
        }
        if ($access_level == 'County') {
            $this->db->where('county_id', $county_id);
        }
        if ($access_level == 'Sub County') {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAggregateMarriageData($partner, $county, $sub_county, $mfl_code)
    {
        $access_level = $this->session->userdata('access_level');
        $partner_id = $this->session->userdata('partner_id');
        $facility_id = $this->session->userdata('facility_id');
        $county_id = $this->session->userdata('county_id');
        $sub_county_id = $this->session->userdata('subcounty_id');
        $this->db->select('*');
        $this->db->from('mariage_distribution');
        if (!empty($partner)) {
            $this->db->where('partner_id', $partner);
        }
        if (!empty($county)) {
            $this->db->where('county_id', $county);
        }
        if (!empty($sub_county)) {
            $this->db->where('sub_county_id', $sub_county);
        }
        if (!empty($mfl_code)) {
            $this->db->where('mfl_code', $mfl_code);
        }

        if ($access_level == 'Partner') {
            $this->db->where('partner_id', $partner_id);
        }
        if ($access_level == 'Facility') {
            $this->db->where('mfl_code', $facility_id);
        }
        if ($access_level == 'County') {
            $this->db->where('county_id', $county_id);
        }
        if ($access_level == 'Sub County') {
            $this->db->where('sub_county_id', $sub_county_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
}
