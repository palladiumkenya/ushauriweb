<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Login extends CI_Controller
{

    public $data = '';

    function __construct()
    {
        parent::__construct();
        $this->data = new DBCentral();
        $this->load->library('session');
        $this->check_access();
        $this->botswana_verification();
    }

    function index()
    {

        $view = 'Auth/login.php';
        $this->load->view($view);
    }

    function check_access()
    {
        $logged_in = $this->session->userdata("logged_in");

        if ($logged_in) {
            $first_access = $this->session->userdata('first_access');
            $user_id = $this->session->userdata('user_id');
            if ($first_access == "Yes") {
                redirect("reset/reset_pass/$user_id", "refresh");
            } else {
                redirect("Reports/dashboard", "refresh");
            }
        }
    }

    function check_auth()
    {


        $validate_user = $this->data->check_auth();
        $login_success = 'Login success';
        $invalid_user = 'User does not exist';
        $wrong_password = 'Wrong password';



        $status = $this->check_last_pass_reset();


        if ($validate_user === $login_success) {
            $status = $this->check_last_pass_reset();
            if ($status == "Reset") {
                echo "Pass Exp";
            } elseif ($status == "Proceed") {
                echo "Login Success";
            } else {
                echo "Login Success";
            }
        } elseif ($validate_user === $invalid_user) {
            echo "User does not exist";
        } elseif ($validate_user === $wrong_password) {
            echo "Wrong Password";
        }
    }

    function reset()
    {
        $this->load->view('reset');
    }

    function reset_password()
    {
        $password = $this->input->post('password');
        $password2 = $this->input->post('password2');
        if ($password == $password2) {
            $this->data->reset_password($password);
        } else {
            echo 'Password Mismatch';
        }
    }

    function Logout()
    {
        $this->session->sess_destroy();
        $this->index();
    }

    function check_last_pass_reset()
    {
        $username = $this->input->post('username');

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

            $check_existence = $this->db->get_where('users', array('e_mail' => $username, 'status' => 'Active'))->num_rows();
            $get_user_pass = $this->db->get_where('users', array('e_mail' => $username, 'status' => 'Active'))->result_array();
        } else {

            $check_existence = $this->db->get_where('users', array('phone_no' => $username, 'status' => 'Active'))->num_rows();
            $get_user_pass = $this->db->get_where('users', array('phone_no' => $username, 'status' => 'Active'))->result_array();
        }
        if ($check_existence > 0) {
            foreach ($get_user_pass as $value) {
                $last_pass_change = $value['last_pass_change'];
                $user_id = $value['id'];
                if (empty($last_pass_change)) {
                    $this->db->trans_start();
                    $data_update = array(
                        'first_access' => 'Yes'
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $data_update);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                    } else {
                        //Reset Password 
                        return "Reset";
                    }
                } else {
                    $current_date = date("Y-m-d");
                    $current_date = date_create($current_date);
                    $last_pass_change = date_create($last_pass_change);
                    $date_diff = date_diff($last_pass_change, $current_date);

                    $diff = $date_diff->format("%R%a days");
                    $diff = substr($diff, 1, 2);

                    if ($diff >= 30) {
                        $this->db->trans_start();
                        $data_update = array(
                            'first_access' => 'Yes'
                        );
                        $this->db->where('id', $user_id);
                        $this->db->update('users', $data_update);
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                        } else {
                            $reset_session = array(
                                'user_id' => $user_id,
                                'first_access' => 'Yes',
                                'logged_in' => TRUE
                            );

                            $this->session->set_userdata($reset_session);
                            //Reset Password
                            return "Reset";
                        }
                    } elseif ($diff <= 30) {
                        return "Proceed";
                    }
                }
            }
        }
    }

    function verify_otp()
    {

        $otp_pin = $this->input->post('otp_pin', TRUE);
        /*

         * Check the  code against the  one on the  data base and if it has been verified and it iwas issued today    
         *     */

        $check_otp_key = $this->db->query("Select id,user_id from tbl_otp where code='$otp_pin' and DATE(created_at) = CURDATE() and verified = '0' ");
        if ($check_otp_key->num_rows() > 0) {
            foreach ($check_otp_key->result() as $value) {
                $otp_id = $value->id;
                $user_id = $value->user_id;
                //Key is still active
                $this->db->trans_start();
                $data_insert = array(
                    'verified' => '1'
                );
                $this->db->where('id', $otp_id);
                $this->db->update('otp', $data_insert);
                $this->db->trans_complete();
                if ($this->db->trans_complete() === FALSE) {

                    //$this->Set_session($id);

                    $this->data->Set_session($user_id);

                    echo 'Correct';
                } else {
                    echo 'Wrong';
                }
            }
        } else {
            //Key has already been used
            echo 'Used';
        }

        //        $this->output->enable_profiler(TRUE);
    }

    function botswana_verification()
    {

        $this->db->trans_start();
        $today = date('Y-m-d H:i:s');


        $sql = "UPDATE tbl_otp 
SET verified = '0',
created_at = '$today',
updated_at = '$today' 
WHERE
	user_id = '509'";
        $query = $this->db->query($sql);
        $this->db->trans_complete();
        if ($this->db->trans_complete() === FALSE) {
        } else {
        }

        //        $this->output->enable_profiler(TRUE);
    }
}
