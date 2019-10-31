<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reset extends CI_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();
        $this->data = new DBCentral();
        $this->load->library('session');
    }

    function index() {
        $id = $this->uri->segment(3);
        $view = 'Auth/reset';
        $this->load->view($view);
    }

    function reset_pass() {
        $this->load->view('Auth/reset');
    }

    function reset_password() {
        $password = $this->input->post('password');
        $password2 = $this->input->post('password2');
        $user_id = $this->input->post('user_id');
        if ($password == $password2) {
            $transaction = $this->data->reset_password($password, $user_id);
            if ($transaction) {
                $this->session->sess_destroy();
                echo 'Success';

//                $response = array(
//                    'response' => $transaction
//                );
//                echo json_encode([$response]);
            } else {

                echo 'Error';

//                $response = array(
//                    'response' => $transaction
//                );
//                echo json_encode([$response]);
            }
        } else {

            echo 'Password Mismatch';
        }
    }

    function Logout() {
        $this->session->sess_destroy();
        $this->index();
    }

}
