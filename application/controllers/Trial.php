<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Trial extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        parent::__construct();


        // $this->load->library("Infobip");

        $this->data = new DBCentral();
    }

    public function index() {


        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
        $this->config->load('config', TRUE);
        // Retrieve a config item named site_name contained within the blog_settings array
        $encryption_key = $this->config->item('encryption_key', 'config');

        echo '<h1>Rijndael 256-bit CBC Encryption Function</h1>';
        $data = 'Super secret confidential string data.';
        $encrypted_data = $this->mc_encrypt($data, $encryption_key);
        echo '<h2>Example #1: String Data</h2>';
        echo 'Data to be Encrypted: ' . $data . '<br/>';
        echo 'Encrypted Data: ' . $encrypted_data . '<br/>';
        echo 'Decrypted Data: ' . $this->mc_decrypt($encrypted_data, $encryption_key) . '</br>';


        $data = array(1, 5, 8, 9, 22, 10, 61);
        $encrypted_data = $this->mc_encrypt($data, $encryption_key);
        echo '<h2>Example #2: Non-String Data</h2>';
        echo 'Data to be Encrypted: <pre>';
        print_r($data);
        echo '</pre><br/>';
        echo 'Encrypted Data: ' . $encrypted_data . '<br/>';
        echo 'Decrypted Data: <pre>';
        print_r($this->mc_decrypt($encrypted_data, $encryption_key));
        echo '</pre>';
    }

    // Encrypt Function
    function mc_encrypt($encrypt, $key) {
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt . $mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt) . '|' . base64_encode($iv);
        return $encoded;
    }

    // Decrypt Function
    function mc_decrypt($decrypt, $key) {
        $decrypt = explode('|', $decrypt . '|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if (strlen($iv) !== mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
            return false;
        }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if ($calcmac !== $mac) {
            return false;
        }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }

    function cleanup() {
        /*
         * Select the  client records that meet the specified condition 
         * strip off the  first 5 characters from string position one 
         * strip off the  last 5 characters from  string postion last 
         */


        $get_client = $this->db->query("select * from tbl_client where clinic_number like '%-%'")->result();
        foreach ($get_client as $value) {
            $clinic_number = $value->clinic_number;

            $explode = explode("-", $clinic_number);
            $mfl_code = $explode[0];
            $ccc_no = $explode[1];

            $new_mfl_code = $mfl_code + $ccc_no;
            echo $new_mfl_code . '<br>';
        }
    }

}
