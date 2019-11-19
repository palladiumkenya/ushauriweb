<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Logout extends CI_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();
        $this->data = new DBCentral();
        $this->load->library('session');
    }

    function index() {
        $description = "Logged out of the  system succesfully";
        $transaction_log = $this->data->log_action($description);
        $this->session->sess_destroy();
        redirect("home", "refresh");
    }

}
