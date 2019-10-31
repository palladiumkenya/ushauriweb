<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/libraries/AfricasTalkingGateway.php";

class AT extends AfricasTalkingGateway {

    public function __construct() {
        parent::__construct();
    }

}
