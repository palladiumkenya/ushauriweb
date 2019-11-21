<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Users extends MY_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();


        //$this->load->library("infobip");

        $this->data = new DBCentral();
    }

    function index() {

        $this->load->view("chore_v");
    }

 



}
