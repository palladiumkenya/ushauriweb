<?php

require __DIR__ . '/vendor/autoload.php';
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class IL_chore extends MY_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();


        //$this->load->library("infobip");

        $this->data = new DBCentral();
    }

    function index() {
        
    }

    function process_registration() {




//        $str = (file_get_contents("" . getcwd() . "\integration_layer\ADT^A04 - Patient Registration.json"));
//        echo ($str);
//        $json = json_decode($str); // decode the JSON into an associative array
//        echo gettype($json);
//        echo '<pre>' . print_r($json, true) . '</pre>';
//
//
//
        // copy file content into a string var
        $json_file = file_get_contents("" . getcwd() . "\integration_layer\ADT^A04 - Patient Registration.json");

// convert the string to a json object
        $jfo = json_decode($json_file, TRUE);
        // echo '<pre>' . print_r($jfo, true) . '</pre>';
        // read the title value
        $SENDING_APPLICATION = $jfo['MESSAGE_HEADER']['SENDING_APPLICATION'];
        // copy the posts array to a php var
        $SENDING_FACILITY = $jfo['MESSAGE_HEADER']['SENDING_FACILITY'];
        //$PATIENT_IDENTIFICATION = $jfo->PATIENT_IDENTIFICATION;

        $PATIENT_IDENTIFICATION = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['ID'];
        $IDENTIFIER_TYPE = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['IDENTIFIER_TYPE'];
        $ASSIGNING_AUTHORITY = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['ASSIGNING_AUTHORITY'];
        echo 'SENDING_APPLICATION : => ' . $SENDING_APPLICATION . '<br> SENDING FACILITY : ' . $SENDING_FACILITY . '<br>';
        echo 'PATIENT IDENTIFICATION : => ' . $PATIENT_IDENTIFICATION . '<br> IDENTIFIER TYPE : ' . $IDENTIFIER_TYPE . '<br>';
        $INTERNAL_PATIENT_ID = $jfo['PATIENT_IDENTIFICATION']['INTERNAL_PATIENT_ID'];
        //echo '<pre>' . print_r($INTERNAL_PATIENT_ID, true) . '</pre>';
        //exit();

        foreach ($INTERNAL_PATIENT_ID as $value) {

            $ID = $value['ID'];
            $IDENTIFIER_TYPE = $value['IDENTIFIER_TYPE'];
            $ASSIGNING_AUTHORITY = $value['ASSIGNING_AUTHORITY'];
            echo 'ID : ' . $ID . '<br> IDENTIFIER TYPE : ' . $IDENTIFIER_TYPE . '<br> ASSIGNING AUTHORITY : ' . $ASSIGNING_AUTHORITY . '<br>';
        }

        $FIRST_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['FIRST_NAME'];
        $MIDDLE_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['MIDDLE_NAME'];
        $LAST_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['LAST_NAME'];


        echo 'FIRST NAME : ' . $FIRST_NAME . '<br> MIDDLE NAME : ' . $MIDDLE_NAME . '<br> LAST NAME : ' . $LAST_NAME . '</br>';

        $MOTHER_MAIDEN_NAME = $jfo['PATIENT_IDENTIFICATION']['MOTHER_MAIDEN_NAME'];
        $DATE_OF_BIRTH = $jfo['PATIENT_IDENTIFICATION']['DATE_OF_BIRTH'];
        $SEX = $jfo['PATIENT_IDENTIFICATION']['SEX'];
        $PATIENT_ADDRESS = $jfo['PATIENT_IDENTIFICATION']['PATIENT_ADDRESS'];
        $VILLAGE = $jfo['PATIENT_IDENTIFICATION']['PATIENT_ADDRESS']['PHYSICAL_ADDRESS']['VILLAGE'];
        $WARD = $jfo['PATIENT_IDENTIFICATION']['PATIENT_ADDRESS']['PHYSICAL_ADDRESS']['WARD'];
        $SUB_COUNTY = $jfo['PATIENT_IDENTIFICATION']['PATIENT_ADDRESS']['PHYSICAL_ADDRESS']['SUB_COUNTY'];
        $COUNTY = $jfo['PATIENT_IDENTIFICATION']['PATIENT_ADDRESS']['PHYSICAL_ADDRESS']['COUNTY'];
        $POSTAL_ADDRESS = $jfo['PATIENT_IDENTIFICATION']['PATIENT_ADDRESS']['POSTAL_ADDRESS'];
        $PHONE_NUMBER = $jfo['PATIENT_IDENTIFICATION']['PHONE_NUMBER'];
        $MARITAL_STATUS = $jfo['PATIENT_IDENTIFICATION']['MARITAL_STATUS'];
        $DEATH_DATE = $jfo['PATIENT_IDENTIFICATION']['DEATH_DATE'];
        $DEATH_INDICATOR = $jfo['PATIENT_IDENTIFICATION']['DEATH_INDICATOR'];
        $NOKFIRST_NAME = $jfo['NEXT_OF_KIN']['NOK_NAME']['FIRST_NAME'];
        $NOKMIDDLE_NAME = $jfo['NEXT_OF_KIN']['NOK_NAME']['MIDDLE_NAME'];
        $NOKLAST_NAME = $jfo['NEXT_OF_KIN']['NOK_NAME']['LAST_NAME'];
        $RELATIONSHIP = $jfo['NEXT_OF_KIN']['RELATIONSHIP'];
        $ADDRESS = $jfo['NEXT_OF_KIN']['ADDRESS'];
        $NOKPHONE_NUMBER = $jfo['NEXT_OF_KIN']['PHONE_NUMBER'];
        $NOKSEX = $jfo['NEXT_OF_KIN']['SEX'];
        $NOKDATE_OF_BIRTH = $jfo['NEXT_OF_KIN']['DATE_OF_BIRTH'];

        echo 'MOTHER MAIDEN NAME : ' . $MOTHER_MAIDEN_NAME . '<br> DATE OF BIRTH : ' . $DATE_OF_BIRTH . '<br> SEX : ' . $SEX . '<br>';

        // echo '<pre>' . print_r($PATIENT_ADDRESS, true) . '</pre>';
        // listing posts
//        foreach ($PATIENT_IDENTIFICATION as $post) {
//            print_r($post);
//            $PATIENT_IDENTIFICATION =  $post->PATIENT_IDENTIFICATION;
//            foreach ($PATIENT_IDENTIFICATION as $patient_identifier){
//                $ID =  $patient_identifier->ID;
//                $IDENTIFIER_TYPE = $patient_identifier->IDENTIFIER_TYPE;
//                $ASSIGNING_AUTHORITY = $patient_identifier->ASSIGNING_AUTHORITY;
//                $INTERNAL_PATIENT_ID = $patient_identifier->INTERNAL_PATIENT_ID;
//                foreach ($INTERNAL_PATIENT_ID as $value) {
//                    
//                }
//            }
//        }
    }

    function process_appointment() {


        // copy file content into a string var
        $json_file = file_get_contents("" . getcwd() . "\integration_layer\SIU^S12 - Appointment Scheduled.json");

        // convert the string to a json object
        $jfo = json_decode($json_file, TRUE);
        //
        // echo '<pre>' . print_r($jfo, true) . '</pre>';
        // read the title value
        $MESSAGE_HEADER = $jfo['MESSAGE_HEADER'];
        $SENDING_APPLICATION = $jfo['MESSAGE_HEADER']['SENDING_APPLICATION'];
        // copy the posts array to a php var
        $SENDING_FACILITY = $jfo['MESSAGE_HEADER']['SENDING_FACILITY'];
        $RECEIVING_APPLICATION = $jfo['MESSAGE_HEADER']['RECEIVING_APPLICATION'];
        $RECEIVING_FACILITY = $jfo['MESSAGE_HEADER']['RECEIVING_FACILITY'];
        $MESSAGE_DATETIME = $jfo['MESSAGE_HEADER']['MESSAGE_DATETIME'];
        $SECURITY = $jfo['MESSAGE_HEADER']['SECURITY'];
        $MESSAGE_TYPE = $jfo['MESSAGE_HEADER']['MESSAGE_TYPE'];
        $PROCESSING_ID = $jfo['MESSAGE_HEADER']['PROCESSING_ID'];
        //$PATIENT_IDENTIFICATION = $jfo->PATIENT_IDENTIFICATION;

        $PATIENT_IDENTIFICATION = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['ID'];
        $IDENTIFIER_TYPE = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['IDENTIFIER_TYPE'];
        $ASSIGNING_AUTHORITY = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['ASSIGNING_AUTHORITY'];
        echo 'SENDING_APPLICATION : => ' . $SENDING_APPLICATION . '<br> SENDING FACILITY : ' . $SENDING_FACILITY . '<br>';
        echo 'PATIENT IDENTIFICATION : => ' . $PATIENT_IDENTIFICATION . '<br> IDENTIFIER TYPE : ' . $IDENTIFIER_TYPE . '<br>';
        $INTERNAL_PATIENT_ID = $jfo['PATIENT_IDENTIFICATION']['INTERNAL_PATIENT_ID'];
        //echo '<pre>' . print_r($INTERNAL_PATIENT_ID, true) . '</pre>';
        //exit();

        foreach ($INTERNAL_PATIENT_ID as $value) {

            $ID = $value['ID'];
            $IDENTIFIER_TYPE = $value['IDENTIFIER_TYPE'];
            $ASSIGNING_AUTHORITY = $value['ASSIGNING_AUTHORITY'];
            echo 'ID : ' . $ID . '<br> IDENTIFIER TYPE : ' . $IDENTIFIER_TYPE . '<br> ASSIGNING AUTHORITY : ' . $ASSIGNING_AUTHORITY . '<br>';
        }

        $FIRST_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['FIRST_NAME'];
        $MIDDLE_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['MIDDLE_NAME'];
        $LAST_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['LAST_NAME'];


        echo 'FIRST NAME : ' . $FIRST_NAME . '<br> MIDDLE NAME : ' . $MIDDLE_NAME . '<br> LAST NAME : ' . $LAST_NAME . '</br>';

        $APPOINTMENT_INFORMATION = $jfo['APPOINTMENT_INFORMATION'];
        echo '<pre>' . print_r($APPOINTMENT_INFORMATION, true) . '</pre>';

        $PLACER_APPOINTMENT_NUMBER = $jfo['APPOINTMENT_INFORMATION'][0]['PLACER_APPOINTMENT_NUMBER'];
        $PLACER_NUMBER = $jfo['APPOINTMENT_INFORMATION'][0]['PLACER_APPOINTMENT_NUMBER']['NUMBER'];
        $PLACER_ENTITY = $jfo['APPOINTMENT_INFORMATION'][0]['PLACER_APPOINTMENT_NUMBER']['ENTITY'];
        echo 'NUMBER : ' . $PLACER_NUMBER . '<br> ENTITY : ' . $PLACER_ENTITY . '<br>';

        $APPOINTMENT_REASON = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_REASON'];
        $APPOINTMENT_TYPE = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_TYPE'];
        $APPOINTMENT_DATE = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_DATE'];
        $APPOINTMENT_PLACING_ENTITY = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_PLACING_ENTITY'];
        $APPOINTMENT_LOCATION = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_LOCATION'];
        $ACTION_CODE = $jfo['APPOINTMENT_INFORMATION'][0]['ACTION_CODE'];
        $APPOINTMENT_NOTE = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_NOTE'];
        $APPOINTMENT_HONORED = $jfo['APPOINTMENT_INFORMATION'][0]['APPINTMENT_HONORED'];

        echo 'APPOINTMENT_REASON : ' . $APPOINTMENT_REASON . '<br> APPOINTMENT_TYPE : ' . $APPOINTMENT_TYPE . '<br>'
        . ' APPOINTMENT_DATE : ' . $APPOINTMENT_DATE . '<br> APPINTMENT PLACING ENTITY : ' . $APPOINTMENT_PLACING_ENTITY . '<br> '
        . 'APPOINTMENT LOCATION ' . $APPOINTMENT_LOCATION . '<BR> ACTION  CODE : ' . $ACTION_CODE . '<br> APPOINTMENT NOTTE : ' . $APPOINTMENT_NOTE . '<br>  APPOINTMENT HONORED : ' . $APPOINTMENT_HONORED;
    }

    function process_reschedule_appointment() {

        // copy file content into a string var
        $json_file = file_get_contents("" . getcwd() . "\integration_layer\SIU^S12 - Appointment Rescheduled.json");

        // convert the string to a json object
        $jfo = json_decode($json_file, TRUE);
        //
        //echo '<pre>' . print_r($jfo, true) . '</pre>';
        // read the title value
        $MESSAGE_HEADER = $jfo['MESSAGE_HEADER'];
        $SENDING_APPLICATION = $jfo['MESSAGE_HEADER']['SENDING_APPLICATION'];
        // copy the posts array to a php var
        $SENDING_FACILITY = $jfo['MESSAGE_HEADER']['SENDING_FACILITY'];
        $RECEIVING_APPLICATION = $jfo['MESSAGE_HEADER']['RECEIVING_APPLICATION'];
        $RECEIVING_FACILITY = $jfo['MESSAGE_HEADER']['RECEIVING_FACILITY'];
        $MESSAGE_DATETIME = $jfo['MESSAGE_HEADER']['MESSAGE_DATETIME'];
        $SECURITY = $jfo['MESSAGE_HEADER']['SECURITY'];
        $MESSAGE_TYPE = $jfo['MESSAGE_HEADER']['MESSAGE_TYPE'];
        $PROCESSING_ID = $jfo['MESSAGE_HEADER']['PROCESSING_ID'];
        //$PATIENT_IDENTIFICATION = $jfo->PATIENT_IDENTIFICATION;

        $PATIENT_IDENTIFICATION = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['ID'];
        $IDENTIFIER_TYPE = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['IDENTIFIER_TYPE'];
        $ASSIGNING_AUTHORITY = $jfo['PATIENT_IDENTIFICATION']['EXTERNAL_PATIENT_ID']['ASSIGNING_AUTHORITY'];
        echo 'SENDING_APPLICATION : => ' . $SENDING_APPLICATION . '<br> SENDING FACILITY : ' . $SENDING_FACILITY . '<br>';
        echo 'PATIENT IDENTIFICATION : => ' . $PATIENT_IDENTIFICATION . '<br> IDENTIFIER TYPE : ' . $IDENTIFIER_TYPE . '<br>';
        $INTERNAL_PATIENT_ID = $jfo['PATIENT_IDENTIFICATION']['INTERNAL_PATIENT_ID'];
        //echo '<pre>' . print_r($INTERNAL_PATIENT_ID, true) . '</pre>';
        //exit();

        foreach ($INTERNAL_PATIENT_ID as $value) {

            $ID = $value['ID'];
            $IDENTIFIER_TYPE = $value['IDENTIFIER_TYPE'];
            $ASSIGNING_AUTHORITY = $value['ASSIGNING_AUTHORITY'];
            echo 'ID : ' . $ID . '<br> IDENTIFIER TYPE : ' . $IDENTIFIER_TYPE . '<br> ASSIGNING AUTHORITY : ' . $ASSIGNING_AUTHORITY . '<br>';
        }

        $FIRST_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['FIRST_NAME'];
        $MIDDLE_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['MIDDLE_NAME'];
        $LAST_NAME = $jfo['PATIENT_IDENTIFICATION']['PATIENT_NAME']['LAST_NAME'];


        echo 'FIRST NAME : ' . $FIRST_NAME . '<br> MIDDLE NAME : ' . $MIDDLE_NAME . '<br> LAST NAME : ' . $LAST_NAME . '</br>';

        $APPOINTMENT_INFORMATION = $jfo['APPOINTMENT_INFORMATION'];
        echo '<pre>' . print_r($APPOINTMENT_INFORMATION, true) . '</pre>';

        $PLACER_APPOINTMENT_NUMBER = $jfo['APPOINTMENT_INFORMATION'][0]['PLACER_APPOINTMENT_NUMBER'];
        $PLACER_NUMBER = $jfo['APPOINTMENT_INFORMATION'][0]['PLACER_APPOINTMENT_NUMBER']['NUMBER'];
        $PLACER_ENTITY = $jfo['APPOINTMENT_INFORMATION'][0]['PLACER_APPOINTMENT_NUMBER']['ENTITY'];
        echo 'NUMBER : ' . $PLACER_NUMBER . '<br> ENTITY : ' . $PLACER_ENTITY . '<br>';

        $APPOINTMENT_REASON = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_REASON'];
        $APPOINTMENT_TYPE = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_TYPE'];
        $APPOINTMENT_DATE = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_DATE'];
        $APPOINTMENT_PLACING_ENTITY = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_PLACING_ENTITY'];
        $APPOINTMENT_LOCATION = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_LOCATION'];
        $ACTION_CODE = $jfo['APPOINTMENT_INFORMATION'][0]['ACTION_CODE'];
        $APPOINTMENT_NOTE = $jfo['APPOINTMENT_INFORMATION'][0]['APPOINTMENT_NOTE'];
        $APPOINTMENT_HONORED = $jfo['APPOINTMENT_INFORMATION'][0]['APPINTMENT_HONORED'];

        echo 'APPOINTMENT_REASON : ' . $APPOINTMENT_REASON . '<br> APPOINTMENT_TYPE : ' . $APPOINTMENT_TYPE . '<br>'
        . ' APPOINTMENT_DATE : ' . $APPOINTMENT_DATE . '<br> APPINTMENT PLACING ENTITY : ' . $APPOINTMENT_PLACING_ENTITY . '<br> '
        . 'APPOINTMENT LOCATION ' . $APPOINTMENT_LOCATION . '<BR> ACTION  CODE : ' . $ACTION_CODE . '<br> APPOINTMENT NOTTE : ' . $APPOINTMENT_NOTE . '<br>  APPOINTMENT HONORED : ' . $APPOINTMENT_HONORED;
    }

}
