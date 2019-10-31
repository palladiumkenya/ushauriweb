<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User_Chore extends MY_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();


        //$this->load->library("infobip");

        $this->data = new DBCentral();
    }

    function index() {

        $this->load->view("chore_v");
    }


  

    function transfer_client() {



        $query = $this->db->query("Select * from tbl_responses where msg like '%TRANS%' and processed='No' ")->result();

        foreach ($query as $value) {
            $source = $value->source;
            $destination = $value->destination;
            $msg = $value->msg;
            $response_id = $value->id;
            $mobile = substr($source, -9);
            $len = strlen($mobile);

            if ($len = 9) {

                $source = "0" . $mobile;
            }
            // // // echo 'New From : ' . $source;


            $get_facility = $this->db->query("Select * from tbl_users where phone_no='$source' and access_level='Facility'");
            $check_facility_user = $get_facility->num_rows();
            if ($check_facility_user > 0) {
                $user_exists = $get_facility->num_rows();
                if ($user_exists >= 1) {
                    //User exists
                    $get_user_details = $get_facility->result();

                    foreach ($get_user_details as $value) {

                        $facility_id = $value->facility_id;
                        $partner_id = $value->partner_id;
                        $user_id = $value->id;
                        $new_mfl_code = $value->facility_id;


                        $exploded_msg = explode("*", $msg);
                        $code = $exploded_msg[0];
                        $upn = $exploded_msg[1];


                        $get_client_details = $this->db->get_where('client', array('clinic_number' => $upn));
                        $check_client = $get_client_details->num_rows();
                        if ($check_client > 0) {

                            $get_client = $get_client_details->result();

                            foreach ($get_client as $value) {
                                $this->db->trans_start();

                                $old_mfl_code = $value->mfl_code;
                                $client_id = $value->id;
                                $update_client = array(
                                    'mfl_code' => $new_mfl_code,
                                    'prev_clinic' => $old_mfl_code,
                                    'updated_by' => $user_id
                                );
                                $this->db->where('id', $client_id);
                                $this->db->update('client', $update_client);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {


                                    $created_at = date('Y-m-d H:i:s');
                                    $source = '40148';
                                    $destination = $mobile;
                                    $this->db->trans_start();

                                    $data_outgoing = array(
                                        'destination' => $destination,
                                        'source' => $source,
                                        'msg' => "Transfer of client CCC No : $upn from $old_mfl_code to $new_mfl_code was done successfully. ",
                                        'status' => 'Not Sent',
                                        'message_type_id' => '5',
                                        'responded' => 'No',
                                        'clnt_usr_id' => $user_id,
                                        'recepient_type' => 'User',
                                        'created_at' => $created_at,
                                        'created_by' => $user_id
                                    );
                                    $this->db->insert('outgoing', $data_outgoing);


                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        $this->db->trans_start();
                                        $response_update = array(
                                            'processed' => 'Yes',
                                            'updated_by' => $user_id
                                        );
                                        $this->db->where('id', $response_id);
                                        $this->db->update('responses', $response_update);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    }
                                }
                            }
                        } else {



                            $this->db->trans_start();

                            $created_at = date('Y-m-d H:i:s');
                            $source = '40148';
                            $destination = $mobile;
                            $this->db->trans_start();

                            $data_outgoing = array(
                                'destination' => $destination,
                                'source' => $source,
                                'msg' => "Sorry , Clinic Number $upn does not exist in the  system",
                                'status' => 'Not Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('outgoing', $data_outgoing);


                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                
                            }
                        }
                    }
                } else {

                    $this->db->trans_start();

                    $created_at = date('Y-m-d H:i:s');
                    $source = '40148';
                    $destination = $mobile;
                    $this->db->trans_start();

                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => "Phone No not authorised to access the  system",
                        'status' => 'Not Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'User',
                        'created_at' => $created_at,
                        'created_by' => $user_id
                    );
                    $this->db->insert('outgoing', $data_outgoing);


                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }
            } else {

                $this->db->trans_start();

                $created_at = date('Y-m-d H:i:s');
                $source = '40148';
                $destination = $mobile;
                $this->db->trans_start();

                $data_outgoing = array(
                    'destination' => $destination,
                    'source' => $source,
                    'msg' => "Phone No not authorised to access the  system",
                    'status' => 'Not Sent',
                    'message_type_id' => '5',
                    'responded' => 'No',
                    'recepient_type' => 'Client',
                    'created_at' => $created_at,
                    'created_by' => $user_id
                );
                $this->db->insert('outgoing', $data_outgoing);


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_broadcast() {
        //get current date time 
        $today = date("Y-m-d H:i:s");
        $query = $this->db->query("Select * from tbl_responses where msg like '%BROAD%' and processed='No' ")->result();

        foreach ($query as $value) {
            $source = $value->source;
            $destination = $value->destination;
            $msg = $value->msg;
            $response_id = $value->id;
            $mobile = substr($source, -9);
            $len = strlen($mobile);

            if ($len = 9) {

                $source = "0" . $mobile;
            }

            $outgoing_msg = '';

            $get_facility = $this->db->query("Select tbl_users.partner_id,tbl_users.id as user_id, tbl_users.phone_no,tbl_master_facility.code as mfl_code, tbl_users.facility_id,tbl_partner_facility.county_id, tbl_partner_facility.sub_county_id from tbl_users "
                    . " inner join tbl_master_facility on tbl_master_facility.code = tbl_users.facility_id  inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_master_facility.code"
                    . " where phone_no='$source' and access_level='Facility' LIMIT 1 ");


            $user_exists = $get_facility->num_rows();
            if ($user_exists >= 1) {

                //User exists
                $get_user_details = $get_facility->result();

                foreach ($get_user_details as $value) {

                    $partner_id = $value->partner_id;
                    $user_id = $value->user_id;
                    $mfl_code = $value->facility_id;
                    $county_id = $value->county_id;
                    $sub_county_id = $value->sub_county_id;
                    $user_source = $value->phone_no;



                    $exploded_msg = explode("*", $msg);
                    $code = @$exploded_msg[0];
                    $broadcast_name = @$exploded_msg[1];
                    $target_client = @$exploded_msg[2];
                    $target_group = @$exploded_msg[3];
                    $broadcast_date = @$exploded_msg[4];
                    $broadcast_time = @$exploded_msg[5];
                    $broadcast_message = @$exploded_msg[6];
                    $user_source = $source;
                    $user_destination = $destination;


                    $trgt_clnt_dictionary = "1:2:3"; //1= > YES 2 => NO
                    $trgt_group_dictionary = "1:2"; //1 => Active 2 => Disabled 3 => Dead
                    $exploded_trgt_clnt_dict = explode(":", $trgt_clnt_dictionary);
                    $exploded_trgt_grp_dict = explode(":", $trgt_group_dictionary);

                    $all = $exploded_trgt_clnt_dict[0];
                    $adults = $exploded_trgt_clnt_dict[1];
                    $adolescents = $exploded_trgt_clnt_dict[2];

                    $all_clients = $exploded_trgt_grp_dict[0];
                    $all_active_appointments = $exploded_trgt_grp_dict[1];

                    if ($target_client == $all or $target_client == $adults or $target_client == $adolescents) {
                        if ($target_group == $all_clients or $target_group == $all_active_appointments) {


                            $check_time = $this->db->get_where('time', array('id' => $broadcast_time))->num_rows();
                            if ($check_time > 0) {




                                $broadcast_date = str_replace('/', '-', $broadcast_date);
                                $broadcast_date = date("Y-m-d", strtotime($broadcast_date));
                                $broadcast_name = $this->input->post('broadcast_name', TRUE);
                                $today = date("Y-m-d H:i:s");
                                $data_insert = array(
                                    'name' => $broadcast_name,
                                    'created_at' => $today,
                                    'status' => 'Active',
                                    'msg' => $broadcast_message,
                                    'group_id' => $target_group,
                                    'time_id' => $broadcast_time,
                                    'county_id' => $county_id,
                                    'sub_county_id' => $sub_county_id,
                                    'mfl_code' => $mfl_code,
                                    'target_group' => $target_group,
                                    'broadcast_date' => $broadcast_date
                                );
                                $this->db->insert('broadcast', $data_insert);
                                $broadcast_id = $this->db->insert_id();



                                $msg = $defaultsms;
                                if ($target_group === "1") {
                                    $clients = $this->get_all_active_clients($target_group, $mfl_code);

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


                                        $source = "40148";
                                        $today = date("Y-m-d H:i:s");
                                        $status = "Not Sent";
                                        $post_data = array(
                                            'broadcast_id' => $broadcast_id,
                                            'destination' => $destination,
                                            'source' => $source,
                                            'msg' => $msg,
                                            'sms_status' => $status,
                                            'created_at' => $today,
                                            'time_id' => $broadcast_time,
                                            'broadcast_date' => $broadcast_date,
                                            'clnt_usr_id' => $client_id,
                                            'recepient_type' => 'Client',
                                            'mfl_code' => $client_mfl_code,
                                            'status' => 'Active'
                                        );
                                        $this->db->insert('sms_queue', $post_data);
                                    }
                                } else if ($target_group === "2") {
//            $clients = $this->get_all_active_clients_appointments($target_group, $mfl_code);
//
//            foreach ($clients as $value) {
//                $client_name = $value->f_name . " " . $value->m_name . " " . $value->l_name;
//                 echo $client_name.'<br>';
//                $phone_no = $value->phone_no;
//                $alt_phone_no = $value->alt_phone_no;
//                $client_id = $value->id;
//                $client_mfl_code = $value->mfl_code;
//                if (empty($phone_no)) {
//                    $destination = $alt_phone_no;
//                } else {
//                    $destination = $phone_no;
//                }
//
//
//                $source = "40148";
//                $today = date("Y-m-d H:i:s");
//                $status = "Not Sent";
//                $post_data = array(
//                    'broadcast_id' => $broadcast_id,
//                    'destination' => $destination,
//                    'source' => $source,
//                    'msg' => $msg,
//                    'sms_status' => $status,
//                    'created_at' => $today,
//                    'time_id' => $broadcast_time,
//                    'broadcast_date' => $broadcast_date,
//                    'clnt_usr_id' => $client_id,
//                    'recepient_type' => 'Client',
//                    'mfl_code' => $client_mfl_code
//                );
//                $this->db->insert('sms_queue', $post_data);
//             
//            }
                                }
                            } else {
                                $outgoing_msg .= " Invalid selection for Broadcast time.  ";
                            }
                        } else {
                            $outgoing_msg .= " Invalid selection for Target Group please try again with 1 => All Clients  2 => All Active Appointments   ";
                        }
                    } else {
                        $outgoing_msg .= " Invalid selection for Target Client  please try again with 1 => All  2 => Adults 3 => Adolescents  ";
                    }




                    $this->db->trans_start();




                    //Conditions were not met , queue out going message 
                    $created_at = date('Y-m-d H:i:s');
                    $data_outgoing = array(
                        'destination' => $user_source,
                        'source' => $user_destination,
                        'msg' => "Error encountered = > " . $outgoing_msg,
                        'status' => 'Not Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'clnt_usr_id' => $user_id,
                        'recepient_type' => 'User',
                        'created_at' => $created_at,
                        'created_by' => $user_id
                    );
                    $this->db->insert('outgoing', $data_outgoing);


                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {

                        $this->db->trans_start();
                        $response_update = array(
                            'processed' => 'Yes',
                            'updated_by' => $user_id
                        );
                        $this->db->where('id', $response_id);
                        $this->db->update('responses', $response_update);

                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            
                        }
                    }
                }
            } else {

                $this->db->trans_start();

                $created_at = date('Y-m-d H:i:s');
                $source = '40148';
                $destination = $mobile;
                $this->db->trans_start();

                $data_outgoing = array(
                    'destination' => $destination,
                    'source' => $source,
                    'msg' => "Phone No not authorised to access the  system",
                    'status' => 'Not Sent',
                    'message_type_id' => '5',
                    'responded' => 'No',
                    'recepient_type' => 'User',
                    'created_at' => $created_at,
                    'created_by' => $user_id
                );
                $this->db->insert('outgoing', $data_outgoing);


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {


                    $this->db->trans_start();
                    $response_update = array(
                        'processed' => 'Yes',
                        'updated_by' => $user_id
                    );
                    $this->db->where('id', $response_id);
                    $this->db->update('responses', $response_update);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }
            }
        }
    }

    function get_all_active_clients($target_group, $mfl_code) {


        if ($target_group == "All") {



            $client_options = array(
                'table' => 'client',
                'where' => array('status' => 'Active', 'mfl_code' => $mfl_code)
            );
        } else {


            $client_options = array(
                'table' => 'client',
                'where' => array('status' => 'Active', 'mfl_code' => $mfl_code)
            );
        }


        $client_data = $this->data->commonGet($client_options);

        return $client_data;
    }

    function get_all_active_clients_appointments($target_group, $mfl_code) {

        $today = date("Y-m-d H:i:s");
        if ($target_group == "All") {



            $query = $this->db->query("Select * from tbl_client"
                            . " inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                            . " where appntmnt_date >= CURDATE() and tbl_client.mfl_code='$mfl_code'")->result();
        } else {

            $query = $this->db->query("Select * from tbl_client"
                            . " inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                            . " where appntmnt_date >= CURDATE() and tbl_client.mfl_code='$mfl_code'")->result();
        }


        $client_data = $query;

        return $client_data;
    }

    function process_stop() {
        //get current date time 
        $today = date("Y-m-d H:i:s");
        //Get the content for STOP , by filtering based on the message type ID 6 which caters for the  STOP massages and for the  different languages in the system .
        $get_stop_msg_type = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='6' and logic_flow='1'  ");
        $check_existence = $get_stop_msg_type->num_rows();

        //Check if there's something returned from the  system 
        if ($check_existence >= 1) {

            //If returned get the  result row of the  above $get_stop_msg_type query
            $get_stop_msg = $get_stop_msg_type->result();
            foreach ($get_stop_msg as $value) {
                //Loop through the  specific message and get the  content from the  query e.g STOP 
                $stop_msg = $value->message;
                //Get returl from the  incoming table based on the  STOP message and that was found from the table content and it has nt been processed yet
                $query = $this->db->query("Select * from tbl_responses where msg LIKE '%$stop_msg%' and processed ='No'");
                $check_row = $query->num_rows();
                //Check if there is a return on the above query                                                                                                                                 
                if ($check_row >= 1) {
                    //If result is found , get the  resutls 
                    // // // echo 'Found.....';
                    $get_row = $query->result();
                    foreach ($get_row as $value) {
                        //Loop through the  results and get specific incoming message
                        $source = $value->source;
                        $destination = $value->destination;
                        $msg = $value->msg;
                        // // // echo 'Message Found => ' . $msg . '</br> ';
                        $incoming_id = $value->id;
                        //Truncate the  values in the  source value e.g 254712345678 = > 0712345678
                        $mobile = substr($source, -9);
                        $len = strlen($mobile);

                        if ($len = 9) {

                            $source = "0" . $mobile;
                        }


                        //User the  cleaned source to get client ID from the  system 
                        $get_client_id = $this->db->query("select * from tbl_client where phone_no='$source' and smsenable='Yes'")->result();
                        foreach ($get_client_id as $value) {
                            $client_id = $value->id;
                            $language_id = $value->language_id;
                            $phone_no = $value->phone_no;
                            //Use the  found client ID to update the  client detials and turn off the  message alerts 
                            $this->db->trans_start();


                            $data_update = array(
                                'smsenable' => 'Yes',
                                'updated_by' => '1',
                                'motivational_enable' => 'No'
                            );
                            $this->db->where('id', $client_id);
                            $this->db->update('client', $data_update);
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                //If the  above transaction was successful, then insert the  above transaction details in the
                                //Table Stop Alerts
                                $this->db->trans_start();

                                $stop_alert = array(
                                    'msg' => $msg,
                                    'clnt_usr_id' => $client_id,
                                    'source' => $source,
                                    'destination' => $destination,
                                    'created_at' => $today,
                                    'recepient_type' => 'Client',
                                    'created_by' => '1'
                                );
                                $this->db->insert('stop_alerts', $stop_alert);

                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    //ON successfull insertion of the stop alerts, 
                                    //Update the  incoming record from Not Processed to Processed
                                    $this->db->trans_start();

                                    $data_update = array(
                                        'processed' => 'Yes',
                                        'updated_by' => '1'
                                    );
                                    $this->db->where('id', $incoming_id);
                                    $this->db->update('responses ', $data_update);


                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        //Do Nothing ...
                                        $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='6' and logic_flow='2' and language_id='$language_id'  ")->result();
                                        foreach ($get_outgoing_msg as $value) {

                                            $message = $value->message;
                                            // // // echo 'Outgoing Message => ' . $message . '</br>';
                                            // // // echo 'Client ID => ' . $client_id . '</br>';
                                            $created_at = date('Y-m-d H:i:s');
                                            $source = '40148';
                                            $destination = $phone_no;
                                            $this->db->trans_start();

                                            $data_outgoing = array(
                                                'destination' => $destination,
                                                'source' => $source,
                                                'msg' => $message,
                                                'status' => 'Not Sent',
                                                'message_type_id' => '6',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $client_id,
                                                'recepient_type' => 'Client',
                                                'created_at' => $created_at,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('outgoing', $data_outgoing);


                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    //Do Nothing ...
                }
            }
        } else {
            //Do Nothing ... 
        }
    }

    function process_register() {
        $query = $this->db->query("Select * from tbl_responses where msg like '%REG%' and processed='No' ")->result();

        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $msg = $value->msg;
            $response_id = $value->id;
            $source = '40148';
            $mobile = substr($user_source, -9);
            $len = strlen($mobile);

            if ($len = 9) {

                $user_source = "0" . $mobile;
            }
            //// echo 'Message => ' . $msg . '<br>';
            // // // echo 'Response id => ' . $response_id . ' and Phone Noe : ' . $user_source . '.</br>';

            $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility'");
            $user_exists = $get_facility->num_rows();
            if ($user_exists >= 1) {
                // // // echo 'User Found...';
                //User exists
                $get_user_details = $get_facility->result();

                foreach ($get_user_details as $value) {

                    $facility_id = $value->facility_id;
                    $partner_id = $value->partner_id;
                    $user_id = $value->id;
                    // // // echo 'Incoming  Msg => ' . $msg . '</br>';
                    $exploded_msg = explode("*", $msg);
                    $count_msg = count($exploded_msg);
                    //// echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 18

                    if ($count_msg == 18) {
                        //Success Go Ahead 
                        //// echo 'Success , new application kindly go ahead....';

                        $reg = @$exploded_msg[0]; //CODE = REG => REGISTRATION
                        $upn = @$exploded_msg[1]; //UPN/CCC NO
                        $f_name = @$exploded_msg[2]; //FIRST NAME
                        $m_name = @$exploded_msg[3]; //MIDDLE NAME
                        $l_name = @$exploded_msg[4]; //LAST NAME
                        $dob = @$exploded_msg[5]; //DATE OF BIRTH
                        $gender = @$exploded_msg[6]; //GEDNER
                        $marital = @$exploded_msg[7]; //MARITAL STATUS
                        $condition = @$exploded_msg[8]; //CONDITION
                        $enrollment_date = @$exploded_msg[9]; //ENROLLMENT DATE
                        $art_start_date = @$exploded_msg[10]; //ART START DATE
                        $phone_no = @$exploded_msg[11]; //PHONE NUMBE
                        $language = @$exploded_msg[12]; //LANGUAGE
                        $sms_enable = @$exploded_msg[13]; //SMS ENABLE 
                        $motivation_enable = @$exploded_msg[14]; //MOTIVATIONAL ALERTS ENABLE
                        $messaging_time = @$exploded_msg[15]; //MESSAGING TIME
                        $client_status = @$exploded_msg[16]; //CLIENT STATUS
                        $transaction_type = @$exploded_msg[17];
                        //$regiment = $exploded_msg[16];
                        $client_id = '';
                        // // // echo 'Enrollment date = > #2 =>  ' . $enrollment_date . '</br>';
                        $enrollment_date2 = $enrollment_date;


                        $trans_type_dict = "1:2:3"; //1= > NEW 2 => UPDATE 3=> TRANSFER

                        $exploded_trans_type_dict = explode(":", $trans_type_dict);

                        $new_trans = $exploded_trans_type_dict[0];
                        $update_trans = $exploded_trans_type_dict[1];
                        $transfer_trans = $exploded_trans_type_dict[2];

                        //// echo 'Transaction type => ' . $transaction_type . '<br>';











                        $check_gender = $this->db->get_where('gender', array('id' => $gender))->num_rows();
                        $check_marital_status = $this->db->get_where('marital_status', array('id' => $marital))->num_rows();
                        $check_condition = $this->db->get_where('condition', array('id' => $condition))->num_rows();
                        // $check_grouping = $this->db->get_where('groups', array('id' => $grouping))->num_rows();
                        $check_language = $this->db->get_where('language', array('id' => $language))->num_rows();
                        $sms_enable_dictionary = "1:2"; //1= > YES 2 => NO
                        $status_dictionary = "1:2:3"; //1 => Active 2 => Disabled 3 => Dead
                        $exploded_sms_dict = explode(":", $sms_enable_dictionary);
                        $exploded_status_dict = explode(":", $status_dictionary);

                        $yes = $exploded_sms_dict[0];
                        $no = $exploded_sms_dict[1];

                        $active = $exploded_status_dict[0];
                        $disabled = $exploded_status_dict[1];
                        $dead = $exploded_status_dict[2];
                        $outgoing_msg = '';


                        if ($check_gender > 0 and $check_marital_status > 0 and $check_condition > 0 and $check_language > 0) {

                            if ($sms_enable == $yes) {
                                $sms_lrt = "Yes";
                            } elseif ($sms_enable == $no) {
                                // // // echo 'SMS Enable => ' . $sms_enable . '</br>';
                                $sms_lrt = "No";
                            } else {
                                $outgoing_msg .= " Invalid selection for SMS Alert please try again with 1= > YES 2 => NO   ";
                            }
                            if ($client_status == $active) {
                                $client_stts = "Active";
                            } elseif ($client_status == $disabled) {
                                $client_stts = "Disabled";
                            } elseif ($client_status == $dead) {
                                $client_stts = "Dead";
                            } else {
                                // // // echo 'Client Status ' . $client_status . '<br>';
                                $outgoing_msg .= " Invalid selection for Client Status please try again with 1 => Active 2 => Disabled 3 => Dead  ";
                                //// echo $outgoing_msg;
                            }

                            if (!empty($sms_lrt) and ! empty($client_stts)) {
                                // echo $outgoing_msg;
                                $condition1 = '';
                                $condition2 = '';
                                $condition3 = '';

                                if (empty($enrollment_date)) {
                                    $outgoing_msg .= " Enrollment date cannot be empty  ";
                                } else {
                                    if (!empty($enrollment_date)) {
                                        $enrollment_date = str_replace('/', '-', $enrollment_date);
                                        $enrollment_date = date("Y-m-d", strtotime($enrollment_date));
                                    }

                                    if (!empty($dob)) {
                                        $check_p_year = str_replace('/', '-', $dob);
                                        $unix_dob = strtotime(date("Y-m-d", strtotime($check_p_year)));
                                    }

                                    if (!empty($enrollment_date)) {
                                        $check_enrollment_date = str_replace('/', '-', $enrollment_date);
                                        $unix_enrollment_date = strtotime(date("Y-m-d", strtotime($check_enrollment_date)));

                                        $date_diff = $unix_enrollment_date - $unix_dob;

                                        if ($date_diff > 1) {
                                            $condition1 .= TRUE;
                                        } else {
                                            $msg = " Enrollment Date cannot be greater than DoB  ";
                                            $outgoing_msg .= $msg;
                                        }
                                    }
                                }

                                if (empty($art_start_date)) {
                                    $outgoing_msg .= " ART Start Date cannot be empty  ";
                                } else {

                                    $art_start_date = str_replace('/', '-', $art_start_date);
                                    $art_start_date = date("Y-m-d", strtotime($art_start_date));

                                    $check_p_year = str_replace('/', '-', $dob);
                                    $unix_dob = strtotime(date("Y-m-d", strtotime($check_p_year)));

                                    $check_art_date = str_replace('/', '-', $art_start_date);
                                    $unix_art_date = strtotime(date("Y-m-d", strtotime($check_art_date)));

                                    $date_diff = $unix_art_date - $unix_dob;

                                    if ($date_diff > 1) {
                                        $condition2 .= TRUE;
                                    } else {
                                        $msg = " ART Date cannot be greater than DoB  ";
                                        $outgoing_msg .= $msg;
                                    }
                                }







                                if (empty($art_start_date) and empty($enrollment_date)) {
                                    
                                } else {

                                    $check_art_date = str_replace('/', '-', $art_start_date);
                                    $check_art_date = date("Y-m-d", strtotime($check_art_date));
                                    $unix_art_date = strtotime($check_art_date);

                                    $check_enrollment_date = str_replace('/', '-', $enrollment_date);
                                    $check_enrollment_date = date("Y-m-d", strtotime($check_enrollment_date));
                                    $unix_enrollment_date = strtotime($check_enrollment_date);

                                    $date_diff = $unix_enrollment_date - $unix_art_date;

                                    if ($date_diff > 1) {
                                        $msg = " ART Date cannot be less than Enrollment Date ";
                                        $outgoing_msg .= $msg;
                                    } else {
                                        $condition3 .= TRUE;
                                    }
                                }


                                if ($condition1 and $condition2 and $condition3) {



                                    /* Transaction Types .....
                                     * 
                                     * Transaction #1 =>  Add new client
                                     * Transaction #2 => Update Client
                                     * Transaction #3 => Transfer Client 
                                     */



                                    //// echo 'Conditions met ....';



                                    if ($transaction_type == $new_trans) {
                                        //REGISTER NEW CLIENT GOES IN HERE ...
                                        // // echo 'Insert Transaction was found ....<br>';









                                        $clinic_number = $upn;
                                        // // // echo '<br>' . $clinic_number . '<br>';
                                        $check_client_existence = $this->db->get_where('client', array('clinic_number' => $clinic_number))->num_rows();
                                        // // // echo 'DFVBGRFB F' . $check_client_existence . '<br>';
                                        if ($check_client_existence > 0) {
                                            // // // echo 'Clinic number already exists ... <br> ';
                                            $created_at = date('Y-m-d H:i:s');
                                            $source = '40148';
                                            $user_destination = $phone_no;
                                            $this->db->trans_start();

                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $source,
                                                'msg' => "Client No : $upn already exists in the  system and cannot be registered again, you can either Update client's records or transfer in the  client.  ",
                                                'status' => 'Not Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at,
                                                'created_by' => $user_id
                                            );
                                            $this->db->insert('outgoing', $data_outgoing);


                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                $this->db->trans_start();
                                                $response_update = array(
                                                    'processed' => 'Yes',
                                                    'updated_by' => $user_id
                                                );
                                                $this->db->where('id', $response_id);
                                                $this->db->update('responses', $response_update);

                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        } else {


                                            //Registration Process Begins ......








                                            $dob = str_replace('/', '-', $dob);
                                            $dob = date("Y-m-d", strtotime($dob));

                                            $current_date = date("Y-m-d");
                                            $current_date = date_create($current_date);
                                            $new_dob = date_create($dob);
                                            $date_diff = date_diff($new_dob, $current_date);
                                            $diff = $date_diff->format("%R%a days");
                                            //// // // echo 'Days difference => ' . $diff . '<br>';
                                            $diff = substr($diff, 0);
                                            $diff = (int) $diff;

                                            $category = "";
                                            if ($diff >= 3650 and $diff <= 6935) {
                                                //Adolescent
                                                $category .= 2;
                                            } else if ($diff >= 7300) {
                                                //Adult
                                                $category .= 1;
                                            }






                                            $this->db->trans_start();


                                            $created_at = date('Y-m-d H:i:s');

                                            $data_insert = array(
                                                'clinic_number' => $upn,
                                                'facility_id' => $facility_id,
                                                'mfl_code' => $facility_id,
                                                'f_name' => $f_name,
                                                'm_name' => $m_name,
                                                'l_name' => $l_name,
                                                'dob' => $dob,
                                                'gender' => $gender,
                                                'marital' => $marital,
                                                'client_status' => $condition,
                                                'enrollment_date' => $enrollment_date,
                                                'group_id' => $category,
                                                'phone_no' => $phone_no,
                                                'language_id' => $language,
                                                'smsenable' => $sms_lrt,
                                                'partner_id' => $partner_id,
                                                'status' => $client_stts,
                                                'art_date' => $art_start_date,
                                                'created_at' => $created_at,
                                                'entry_point' => 'Mobile',
                                                'created_by' => $user_id,
                                                'client_type' => 'New',
                                                'txt_time' => $messaging_time,
                                                'motivational_enable' => $motivation_enable
                                            );
                                            $this->db->insert('client', $data_insert);
                                            $client_id .= $this->db->insert_id();
                                            // // // echo $client_id;
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {





                                                $this->db->trans_start();
                                                $response_update = array(
                                                    'processed' => 'Yes'
                                                );
                                                $this->db->where('id', $response_id);
                                                $this->db->update('responses', $response_update);

                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        }
                                    } elseif ($transaction_type == $update_trans) {
                                        //UPDATE CLIENT DETAILS GOES NI HERE ...
                                        //// echo 'Update transaction was Found ...<br>';









                                        $clinic_number = $upn;
                                        // // // echo '<br>Clinic Number => ' . $clinic_number . '<br>';
                                        $client_query = $this->db->get_where('client', array('clinic_number' => $clinic_number));
                                        $check_client_existence = $client_query->num_rows();
                                        // // // echo 'Check found => ' . $check_client_existence . '</br>';
                                        if ($check_client_existence == 0) {
                                            // // // echo 'Clinic number does not exists ... <br> ';
                                            $created_at = date('Y-m-d H:i:s');
                                            $source = '40148';
                                            $user_destination = $phone_no;
                                            $this->db->trans_start();

                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $source,
                                                'msg' => "Update Client Error = > Client No : $upn does not exist in the  system ",
                                                'status' => 'Not Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at,
                                                'created_by' => $user_id
                                            );
                                            $this->db->insert('outgoing', $data_outgoing);


                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                $this->db->trans_start();
                                                $response_update = array(
                                                    'processed' => 'Yes',
                                                    'updated_by' => $user_id
                                                );
                                                $this->db->where('id', $response_id);
                                                $this->db->update('responses', $response_update);

                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        } else {







                                            $dob = str_replace('/', '-', $dob);
                                            $dob = date("Y-m-d", strtotime($dob));

                                            $current_date = date("Y-m-d");
                                            $current_date = date_create($current_date);
                                            $new_dob = date_create($dob);
                                            $date_diff = date_diff($new_dob, $current_date);
                                            $diff = $date_diff->format("%R%a days");
                                            //// // // echo 'Days difference => ' . $diff . '<br>';
                                            $diff = substr($diff, 0);
                                            $diff = (int) $diff;

                                            $category = "";
                                            if ($diff >= 3650 and $diff <= 6935) {
                                                //Adolescent
                                                $category .= 2;
                                            } else if ($diff >= 7300) {
                                                //Adult
                                                $category .= 1;
                                            }

                                            foreach ($client_query->result() as $value) {


                                                $trans_2_client_id = $value->id;


                                                $this->db->trans_start();


                                                $created_at = date('Y-m-d H:i:s');

                                                $data_insert = array(
                                                    'clinic_number' => $upn,
                                                    'facility_id' => $facility_id,
                                                    'mfl_code' => $facility_id,
                                                    'f_name' => $f_name,
                                                    'm_name' => $m_name,
                                                    'l_name' => $l_name,
                                                    'dob' => $dob,
                                                    'gender' => $gender,
                                                    'marital' => $marital,
                                                    'client_status' => $condition,
                                                    'enrollment_date' => $enrollment_date,
                                                    'group_id' => $category,
                                                    'phone_no' => $phone_no,
                                                    'language_id' => $language,
                                                    'smsenable' => $sms_lrt,
                                                    'partner_id' => $partner_id,
                                                    'status' => $client_stts,
                                                    'art_date' => $art_start_date,
                                                    'created_at' => $created_at,
                                                    'entry_point' => 'Mobile',
                                                    'updated_by' => $user_id,
                                                    'txt_time' => $messaging_time,
                                                    'motivational_enable' => $motivation_enable
                                                );
                                                $this->db->where('id', $trans_2_client_id);
                                                $this->db->update('client', $data_insert);
                                                $client_id .= $this->db->insert_id();
                                                // // // echo $client_id;
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {





                                                    $this->db->trans_start();
                                                    $message = "Client ID : $upn was succesfully updated in the  system ";
                                                    $data_outgoing = array(
                                                        'destination' => $user_source,
                                                        'source' => $source,
                                                        'msg' => $message,
                                                        'status' => 'Not Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $client_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at,
                                                        'created_by' => $user_id
                                                    );
                                                    $this->db->insert('outgoing', $data_outgoing);
                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {
                                                        
                                                         $this->db->trans_start();
                                                $response_update = array(
                                                    'processed' => 'Yes',
                                                    'updated_by' => $user_id
                                                );
                                                $this->db->where('id', $response_id);
                                                $this->db->update('responses', $response_update);

                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                                        
                                                        
                                                        
                                                    }
                                                }
                                            }
                                        }
                                    } elseif ($transaction_type == $transfer_trans) {
                                        //TRANSFER CLIENT GOES IN HERE ...
                                        /* Explode clients UPN Number to get his/her enrollment facility 
                                         * Useing the mfl code you can be bale to determine the  client's previous clinic
                                         *  and through the  enrollment officer you can determine his current clinic
                                         */








                                        $previous_facility = substr($upn, 0, 5);
                                        $query_client = $this->db->get_where('client', array('clinic_number' => $upn));
                                        $client_existence = $query_client->num_rows();
                                        if ($client_existence > 0) {
                                            //Client Exists, lets update his/her rocord in the  DBase ...
                                            $get_client_detials = $query_client->result();
                                            foreach ($get_client_detials as $value) {
                                                $trans_3_client_id = $value->id;





                                                $dob = str_replace('/', '-', $dob);
                                                $dob = date("Y-m-d", strtotime($dob));

                                                $current_date = date("Y-m-d");
                                                $current_date = date_create($current_date);
                                                $new_dob = date_create($dob);
                                                $date_diff = date_diff($new_dob, $current_date);
                                                $diff = $date_diff->format("%R%a days");
                                                //// // // echo 'Days difference => ' . $diff . '<br>';
                                                $diff = substr($diff, 0);
                                                $diff = (int) $diff;

                                                $category = "";
                                                if ($diff >= 3650 and $diff <= 6935) {
                                                    //Adolescent
                                                    $category .= 2;
                                                } else if ($diff >= 7300) {
                                                    //Adult
                                                    $category .= 1;
                                                }



                                                $this->db->trans_start();


                                                $created_at = date('Y-m-d H:i:s');

                                                $data_insert = array(
                                                    'clinic_number' => $upn,
                                                    'facility_id' => $facility_id,
                                                    'mfl_code' => $facility_id,
                                                    'f_name' => $f_name,
                                                    'm_name' => $m_name,
                                                    'l_name' => $l_name,
                                                    'dob' => $dob,
                                                    'gender' => $gender,
                                                    'marital' => $marital,
                                                    'client_status' => $condition,
                                                    'enrollment_date' => $enrollment_date,
                                                    'group_id' => $category,
                                                    'phone_no' => $phone_no,
                                                    'language_id' => $language,
                                                    'smsenable' => $sms_lrt,
                                                    'partner_id' => $partner_id,
                                                    'status' => $client_stts,
                                                    'art_date' => $art_start_date,
                                                    'created_at' => $created_at,
                                                    'entry_point' => 'Mobile',
                                                    'updated_by' => $user_id,
                                                    'prev_clinic' => $previous_facility,
                                                    'client_type' => 'Transfer',
                                                    'txt_time' => $messaging_time,
                                                    'motivational_enable' => $motivation_enable
                                                );
                                                $this->db->where('id', $trans_3_client_id);
                                                $this->db->update('client', $data_insert);

                                                // // // echo $client_id;
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {



                                                    $this->db->trans_start();
                                                    $message = "Client ID : $upn was succesfully transfered to your facility  in the system ";
                                                    $data_outgoing = array(
                                                        'destination' => $user_source,
                                                        'source' => $source,
                                                        'msg' => $message,
                                                        'status' => 'Not Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $client_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at,
                                                        'created_by' => $user_id
                                                    );
                                                    $this->db->insert('outgoing', $data_outgoing);
                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {

                                                        $this->db->trans_start();
                                                        $response_update = array(
                                                            'processed' => 'Yes'
                                                        );
                                                        $this->db->where('id', $response_id);
                                                        $this->db->update('responses', $response_update);

                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {
                                                            
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            //Client does not exist lets insert him/her as new record......



                                            $dob = str_replace('/', '-', $dob);
                                            $dob = date("Y-m-d", strtotime($dob));

                                            $current_date = date("Y-m-d");
                                            $current_date = date_create($current_date);
                                            $new_dob = date_create($dob);
                                            $date_diff = date_diff($new_dob, $current_date);
                                            $diff = $date_diff->format("%R%a days");
                                            //// // // echo 'Days difference => ' . $diff . '<br>';
                                            $diff = substr($diff, 0);
                                            $diff = (int) $diff;

                                            $category = "";
                                            if ($diff >= 3650 and $diff <= 6935) {
                                                //Adolescent
                                                $category .= 2;
                                            } else if ($diff >= 7300) {
                                                //Adult
                                                $category .= 1;
                                            }



                                            $this->db->trans_start();


                                            $created_at = date('Y-m-d H:i:s');

                                            $data_insert = array(
                                                'clinic_number' => $upn,
                                                'facility_id' => $facility_id,
                                                'mfl_code' => $facility_id,
                                                'f_name' => $f_name,
                                                'm_name' => $m_name,
                                                'l_name' => $l_name,
                                                'dob' => $dob,
                                                'gender' => $gender,
                                                'marital' => $marital,
                                                'client_status' => $condition,
                                                'enrollment_date' => $enrollment_date,
                                                'group_id' => $category,
                                                'phone_no' => $phone_no,
                                                'language_id' => $language,
                                                'smsenable' => $sms_lrt,
                                                'partner_id' => $partner_id,
                                                'status' => $client_stts,
                                                'art_date' => $art_start_date,
                                                'created_at' => $created_at,
                                                'entry_point' => 'Mobile',
                                                'updated_by' => $user_id,
                                                'prev_clinic' => $previous_facility,
                                                'client_type' => 'Transfer',
                                                'txt_time' => $messaging_time,
                                                'motivational_enable' => $motivation_enable
                                            );

                                            $this->db->insert('client', $data_insert);

                                            // // // echo $client_id;
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {





                                                $this->db->trans_start();
                                                $response_update = array(
                                                    'processed' => 'Yes'
                                                );
                                                $this->db->where('id', $response_id);
                                                $this->db->update('responses', $response_update);

                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        }
                                    }
                                } else {


                                    $this->db->trans_start();




                                    //Conditions were not met , queue out going message 
                                    $created_at = date('Y-m-d H:i:s');
                                    // // // echo 'Out going message => ' . $outgoing_msg . '</br> ';
                                    $data_outgoing = array(
                                        'destination' => $source,
                                        'source' => $user_destination,
                                        'msg' => "Error encountered = > " . $outgoing_msg,
                                        'status' => 'Not Sent',
                                        'message_type_id' => '5',
                                        'responded' => 'No',
                                        'clnt_usr_id' => $user_id,
                                        'recepient_type' => 'User',
                                        'created_at' => $created_at,
                                        'created_by' => $user_id
                                    );
                                    $this->db->insert('outgoing', $data_outgoing);


                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {


                                        $this->db->trans_start();
                                        $response_update = array(
                                            'processed' => 'Yes',
                                            'updated_by' => $user_id
                                        );
                                        $this->db->where('id', $response_id);
                                        $this->db->update('responses', $response_update);

                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }

                                        // // // // echo 'Record inserted successfullly ....';
                                    }
                                }
                            }
                        } else if ($check_gender <= 0 or $check_marital_status <= 0 or $check_condition <= 0 or $check_language <= 0) {


                            $outgoing_msg .= '';
                            if ($check_gender <= 0) {
                                $outgoing_msg .= 'Invalid selection for client Gender ';
                            }
                            if ($check_marital_status <= 0) {

                                $outgoing_msg .= 'Invalid selection for Marital Status ';
                                // // // echo $outgoing_msg;
                            }
                            if ($check_condition <= 0) {
                                $outgoing_msg .= 'Invalid selection for Client Condition ';
                            }


                            if ($check_language <= 0) {
                                $outgoing_msg .= ' Invalid selection for Language ';
                            }



                            $this->db->trans_start();




                            //Conditions were not met , queue out going message 
                            $created_at = date('Y-m-d H:i:s');
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => "Error encountered = > " . $outgoing_msg,
                                'status' => 'Not Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'clnt_usr_id' => $user_id,
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('outgoing', $data_outgoing);


                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {

                                $this->db->trans_start();
                                $response_update = array(
                                    'processed' => 'Yes',
                                    'updated_by' => $user_id
                                );
                                $this->db->where('id', $response_id);
                                $this->db->update('responses', $response_update);

                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    
                                }
                            }
                        }
                    } else {
                        //Failed, please try again ....
                        // echo 'Old application';

                        $this->db->trans_start();




                        //Conditions were not met , queue out going message 
                        $created_at = date('Y-m-d H:i:s');
                        $data_outgoing = array(
                            'destination' => $user_source,
                            'source' => $user_destination,
                            'msg' => "Error encountered = > You need to update your application to the  latest version, kindly contact support for guidance. "
                            . "Ushauri :Getting better one text at a time .",
                            'status' => 'Not Sent',
                            'message_type_id' => '5',
                            'responded' => 'No',
                            'clnt_usr_id' => $user_id,
                            'recepient_type' => 'User',
                            'created_at' => $created_at,
                            'created_by' => $user_id
                        );
                        $this->db->insert('outgoing', $data_outgoing);


                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {

                            $this->db->trans_start();
                            $response_update = array(
                                'processed' => 'Yes',
                                'updated_by' => $user_id
                            );
                            $this->db->where('id', $response_id);
                            $this->db->update('responses', $response_update);

                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                
                            }
                        }
                    }
                }


                $this->db->trans_start();

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    // // // // echo 'Record inserted successfullly ....';
                }
            } else {



                $created_at = date('Y-m-d H:i:s');
                $source = '40148';
                $destination = $mobile;

                $this->db->trans_start();

                $data_outgoing = array(
                    'destination' => $destination,
                    'source' => $source,
                    'msg' => "Phone No not authorised to access the  system",
                    'status' => 'Not Sent',
                    'message_type_id' => '5',
                    'responded' => 'No',
                    'recepient_type' => 'User',
                    'created_at' => $created_at,
                    'created_by' => $user_id
                );
                $this->db->insert('outgoing', $data_outgoing);


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {


                    $this->db->trans_start();
                    $response_update = array(
                        'processed' => 'Yes',
                        'updated_by' => $user_id
                    );
                    $this->db->where('id', $response_id);
                    $this->db->update('responses', $response_update);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }
            }
        }
    }

    function process_appointment() {

        $query = $this->db->query("Select * from tbl_responses where msg like '%APP%' and processed='No' and DATE(created_at) = CURDATE()")->result();
        //Get All New Repsonse
        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $msg = $value->msg;
            $process_id = $value->id;
            $mobile = substr($user_source, -9);
            $len = strlen($mobile);

            if ($len = 9) {

                $user_source = "0" . $mobile;
            }
            // // // echo  'New From : ' . $user_source . '</br>';
            //Check if User is authoriesed
            $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility'");
            $user_exists = $get_facility->num_rows();
            if ($user_exists >= 1) {
                //User exists
                $get_user_details = $get_facility->result();

                foreach ($get_user_details as $value) {


                    $facility_id = $value->facility_id;
                    $partner_id = $value->partner_id;
                    $user_id = $value->id;

                    $exploded_msg = explode("*", $msg);
                    $app = $exploded_msg[0];
                    $upn = $exploded_msg[1];
                    $app_date = $exploded_msg[2];
                    $appointment_type = $exploded_msg[3];
                    $appointment_kept = $exploded_msg[4];
                    $appointment_type_dict = "1:2:3";
                    $appointment_kept_dict = "1:2";

                    #Explode Appointment Type Dictionary
                    $exploded_app_type = explode(":", $appointment_type_dict);

                    $re_fill_code = $exploded_app_type[0];
                    $clinical_review_code = $exploded_app_type[1];
                    $enhance_adherence_code = $exploded_app_type[2];

                    if ($re_fill_code == $appointment_type) {
                        //Re Fill will assigned from here...
                        $appntmnt_type = "Re-Fill";
                    } elseif ($clinical_review_code == $appointment_type) {
                        //Clinical Review will be assigned from here ...
                        $appntmnt_type = "Clinical Review";
                    } elseif ($enhance_adherence_code == $appointment_type) {
                        //Enhance Adherence will be assigned from here ...
                        $appntmnt_type = "Enhanced Adherence";
                    }


                    #Explode Appointment Kept Dictionary
                    $exploded_app_kept = explode(":", $appointment_kept_dict);

                    $app_kept_yes = $exploded_app_kept[0];
                    $app_kept_no = $exploded_app_kept[1];

                    if ($app_kept_yes == $appointment_kept) {
                        //Re Fill will assigned from here...
                        $appntmnt_kept = "Yes";
                    } elseif ($app_kept_no == $appointment_kept) {
                        //Clinical Review will be assigned from here ...
                        $appntmnt_kept = "No";
                    }



                    $app_date = str_replace('/', '-', $app_date);
                    $app_date = date("Y-m-d", strtotime($app_date));
                    if ($app_date == "1970-01-01") {
                        //Invalid Appointment Date 
                        $created_at = date('Y-m-d H:i:s');

                        $data_outgoing = array(
                            'destination' => $user_source,
                            'source' => $user_destination,
                            'msg' => "Invalid Appointment Date , DD/MM/YYYY is the  appropriate date format .  ",
                            'status' => 'Not Sent',
                            'message_type_id' => '5',
                            'responded' => 'No',
                            'recepient_type' => 'User',
                            'created_at' => $created_at,
                            'created_by' => $user_id
                        );
                        $this->db->insert('outgoing', $data_outgoing);


                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {


                            $this->db->trans_start();
                            $response_update = array(
                                'processed' => 'Yes',
                                'updated_by' => $user_id
                            );
                            $this->db->where('id', $process_id);
                            $this->db->update('responses', $response_update);

                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                // End Process Here ....
                            }
                        }
                    } else {
                        //Appointment date is correct proceed to appointment processing
                        //Get Client Details from the  Client Number 
                        //// // // echo  'Clinic Name' . $upn . '</br>';





                        $clinic_number = $upn;
                        $app_status = "Booked";
                        $language_id = '';
                        $group_id = '';


                        $client_data = $this->db->query("Select * from tbl_client where clinic_number='$clinic_number' and status='Active'");

                        $check_client_existence = $client_data->num_rows();
                        if ($check_client_existence > 0) {
                            // // // echo  'Client Was Found....';
                            //Client Was Found ...
                            foreach ($client_data->result() as $client_value) {
                                $client_id = $client_value->id;

                                $group_id = $client_value->group_id;
                                $language_id = $client_value->language_id;

                                $client_name = " " . $client_value->f_name . " ";

                                $client_name = ucwords(strtolower($client_name)) . " ";
                                // // // echo  'Client Name ' . $client_name . '</br>';
                                //Get Previous Appointment  if it exists
                                // // // echo  'Appointment Type => ' . $appntmnt_type . '<br>';

                                $get_client = $this->db->query("Select * from tbl_appointment where client_id='$client_id' and active_app='1' and app_type_1='$appntmnt_type' ");
                                $get_client_row = $get_client->num_rows();


                                if ($get_client_row >= 1) {
                                    //Old Appointment
                                    // // // echo  'Old Appointment';
                                    $get_client_result = $get_client->result();
                                    foreach ($get_client_result as $appointment_value) {
                                        //Archive previous appointments and Update the  new appointments

                                        $id = $appointment_value->id;
                                        $client_id = $appointment_value->client_id;
                                        $appntmnt_date = $appointment_value->appntmnt_date;
                                        $appntmnt_status = $appointment_value->appntmnt_status;
                                        $app_type_1 = $appointment_value->app_type_1;
                                        $app_type_2 = $appointment_value->app_type_2;
                                        $expln_app = $appointment_value->expln_app;
                                        $custom_txt = $appointment_value->custom_txt;
                                        $created_at = $appointment_value->created_at;
                                        $updated_at = $appointment_value->updated_at;
                                        $app_status = $appointment_value->app_status;
                                        $app_msg = $appointment_value->app_msg;

                                        $this->db->trans_start();
                                        $appnt_data = array(
                                            'client_id' => $client_id,
                                            'appntmnt_date' => $appntmnt_date,
                                            'app_type_1' => $app_type_1,
                                            'app_type_2' => $app_type_2,
                                            'expln_app' => $expln_app,
                                            'custom_txt' => $custom_txt,
                                            'created_at' => $created_at,
                                            'app_status' => $app_status,
                                            'client_id' => $client_id,
                                            'updated_at' => $updated_at,
                                            'app_msg' => $app_msg,
                                            'created_by' => $user_id
                                        );
                                        $this->db->insert('appointment_arch', $appnt_data);

                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            // // // echo  'Archived Failed';
                                        } else {
                                            // // // echo  'Archive Success';



                                            $target_group = "All";
                                            $message_type_id = 1;
                                            $logic_flow = 1;
                                            $get_outgoing_msg = $this->get_outgoing_msg($target_group, $message_type_id, $logic_flow, $language_id);


                                            $app_status = "Booked";


                                            $new_msg = str_replace("XXX", $client_name, $get_outgoing_msg);
                                            $appointment_date = date("d-m-Y", strtotime($app_date));
                                            $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);

                                            // // // echo  'Cleaned Msg => ' . $cleaned_msg . '</br>';
                                            $this->db->trans_start();
                                            if ($appntmnt_kept === "Yes") {
                                                $appointment_update = array(
                                                    'active_app' => '0',
                                                    'updated_by' => $user_id,
                                                    'appointment_kept' => $appntmnt_kept,
                                                    'app_status' => 'Notified'
                                                );
                                            } else {
                                                $appointment_update = array(
                                                    'active_app' => '0',
                                                    'updated_by' => $user_id,
                                                    'appointment_kept' => $appntmnt_kept
                                                );
                                            }



                                            $this->db->where('id', $id);
                                            $this->db->update('appointment', $appointment_update);






                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                // // // echo  'Appointment Updated ...';










                                                $target_group = "All";
                                                $message_type_id = 1;
                                                $logic_flow = 1;
                                                $get_outgoing_msg = $this->get_outgoing_msg($target_group, $message_type_id, $logic_flow, $language_id);


                                                $app_status = "Booked";


                                                $new_msg = str_replace("XXX", $client_name, $get_outgoing_msg);
                                                $appointment_date = date("d-m-Y", strtotime($app_date));
                                                $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);
                                                // // // echo  'Cleaned Mesage => ' . $cleaned_msg . '</br> ';
                                                $today = date('Y-m-d H:i:s');
                                                // // // echo  'Cleaned Msg = > ' . $cleaned_msg . '</br>';
                                                $this->db->trans_start();
                                                $appointment_insert = array(
                                                    'app_status' => $app_status,
                                                    'app_msg' => $cleaned_msg,
                                                    'appntmnt_date' => $app_date,
                                                    'status' => 'Active',
                                                    'sent_status' => 'Not Sent',
                                                    'client_id' => $client_id,
                                                    'created_at' => $today,
                                                    'created_by' => $user_id,
                                                    'app_type_1' => $appntmnt_type,
                                                    'entry_point' => 'Mobile'
                                                );

                                                $this->db->insert('appointment', $appointment_insert);
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {


                                                    $this->db->trans_start();




                                                    //Conditions were not met , queue out going message 
                                                    $created_at = date('Y-m-d H:i:s');

                                                    $data_outgoing = array(
                                                        'destination' => $user_source,
                                                        'source' => $user_destination,
                                                        'msg' => "Client $clinic_number appointment was succesfully updated in the  system  ",
                                                        'status' => 'Not Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $user_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at,
                                                        'created_by' => $user_id
                                                    );
                                                    $this->db->insert('outgoing', $data_outgoing);


                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {
                                                        // // // // echo  'Record inserted successfullly ....';


                                                        $this->db->trans_start();
                                                        $response_update = array(
                                                            'processed' => 'Yes'
                                                        );
                                                        $this->db->where('id', $process_id);
                                                        $this->db->update('responses', $response_update);

                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {
                                                            
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    //New Appointment
                                    // // // echo  'New Appointment';
                                    //Insert Into Table Apptointment ang get outgoing message








                                    $target_group = "All";
                                    $message_type_id = 1;
                                    $logic_flow = 1;
                                    $get_outgoing_msg = $this->get_outgoing_msg($target_group, $message_type_id, $logic_flow, $language_id);


                                    $app_status = "Booked";


                                    $new_msg = str_replace("XXX", $client_name, $get_outgoing_msg);
                                    $appointment_date = date("d-m-Y", strtotime($app_date));
                                    $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);
                                    // // // echo  'Cleaned Mesage => ' . $cleaned_msg . '</br> ';
                                    $today = date('Y-m-d H:i:s');
                                    // // // echo  'Cleaned Msg = > ' . $cleaned_msg . '</br>';
                                    $this->db->trans_start();
                                    $appointment_insert = array(
                                        'app_status' => $app_status,
                                        'app_msg' => $cleaned_msg,
                                        'appntmnt_date' => $app_date,
                                        'status' => 'Active',
                                        'sent_status' => 'Not Sent',
                                        'client_id' => $client_id,
                                        'created_at' => $today,
                                        'active_app' => '1',
                                        'created_by' => $user_id,
                                        'app_type_1' => $appntmnt_type,
                                        'entry_point' => 'Mobile'
                                    );

                                    $this->db->insert('appointment', $appointment_insert);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {


                                        // // // echo  'Appointment Booked Successfully ...';


                                        $this->db->trans_start();

                                        $created_at = date('Y-m-d H:i:s');

                                        $data_outgoing = array(
                                            'destination' => $user_source,
                                            'source' => $user_destination,
                                            'msg' => "Client $clinic_number appointment was succesfully updated in the  system  ",
                                            'status' => 'Not Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'clnt_usr_id' => $user_id,
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at
                                        );
                                        $this->db->insert('outgoing', $data_outgoing);


                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            // // // echo  'Record inserted successfullly ....';


                                            $this->db->trans_start();
                                            $response_update = array(
                                                'processed' => 'Yes'
                                            );
                                            $this->db->where('id', $process_id);
                                            $this->db->update('responses', $response_update);

                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            // // // echo  'Cllinic No not found...';
                            // // // echo  'Start';

                            $created_at = date('Y-m-d H:i:s');
                            $source = '40148';
                            $destination = '0' . $mobile;
                            $this->db->trans_start();

                            $data_outgoing = array(
                                'destination' => $destination,
                                'source' => $source,
                                'msg' => " Appointment was not scheduled in the  system , Clinic No $upn was not found in the system ...",
                                'status' => 'Not Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'recepient_type' => 'Client',
                                'created_at' => $created_at
                            );
                            $this->db->insert('outgoing', $data_outgoing);


                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                $this->db->trans_start();

                                $response_update = array(
                                    'processed' => 'Yes'
                                );
                                $this->db->where('id', $process_id);
                                $this->db->update('responses', $response_update);

                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    
                                }
                            }
                        }
                    }
                }
            } else {


                // // // echo  'Not Authorised in the  system ...' . $user_source . '</br>';
                // // // echo  'Start';

                $created_at = date('Y-m-d H:i:s');
                $source = '40148';
                $destination = '0' . $mobile;
                $this->db->trans_start();

                $data_outgoing = array(
                    'destination' => $destination,
                    'source' => $source,
                    'msg' => "Phone No not authorised to access the  system",
                    'status' => 'Not Sent',
                    'message_type_id' => '5',
                    'responded' => 'No',
                    'recepient_type' => 'Client',
                    'created_at' => $created_at
                );
                $this->db->insert('outgoing', $data_outgoing);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    $this->db->trans_start();

                    $response_update = array(
                        'processed' => 'Yes'
                    );
                    $this->db->where('id', $process_id);
                    $this->db->update('responses', $response_update);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }


                // // // echo  'End';
            }
        }
    }

    function get_outgoing_msg($target_group, $message_type_id, $logic_flow, $language_id) {
        $message = '';
        $get_message = $this->db->query("Select * from tbl_messages where target_group='$target_group' and message_type_id='$message_type_id' and logic_flow='$logic_flow' and language_id='$language_id'")->result();
        foreach ($get_message as $value) {
            $message .= $value->message;
        }
        return $message;
    }

    function get_outgoing_sms($language_id, $group_id, $message_type, $identifier) {
        $content = array(
            'select' => 'content',
            'table' => 'content',
            'join' => array('message_types' => 'message_types.id = content.message_type_id', 'groups' => 'groups.id = content.group_id'),
            'where' => array('content.language_id' => $language_id, 'content.group_id' => $group_id, 'message_types.name' => $message_type, 'content.identifier' => $identifier)
        );

        $data = $this->data->commonGet($content);

        foreach ($data as $value) {
            $content = $value->content;

            return $content;
        }
    }

    function send_mail() {

        $this->db->trans_start();

        $this->load->library('email');

        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_user'] = 'support.tech@mhealthkenya.org';
        $config['smtp_pass'] = 'Support.tech123!@#';
        $config['smtp_port'] = 465;
        $this->email->initialize($config);

        $this->email->set_newline("\r\n");

        $get_out_mails = array(
            'table' => 'email',
            'where' => array('sent' => 'No', 'status' => 'Active')
        );
        $outgoing_thread = $this->data->commonGet($get_out_mails);
        foreach ($outgoing_thread as $value) {
            $mail_id = $value->id;
            $source = $value->source;
            $destination = $value->destination;
            $msg = $value->msg;
            $status = $value->status;
            $sent = $value->sent;
            $subject = $value->subject;

            $this->email->from('t4a@mhealthkenya.org', $source);
            $this->email->to($destination);

            $this->email->subject($subject);
            $this->email->message($msg);

            //$csv_file = $this->ExportCSV();

            $this->email->attach(FCPATH . '/documents/sys_reports/appointments.csv');
            // You need to pass FALSE while sending in order for the email data
            // to not be cleared - if that happens, print_debugger() would have
            // nothing to output.
            $this->email->send(FALSE);

            // Will only print the email headers, excluding the message subject and body
            $out_put = $this->email->print_debugger();
            // // // echo 'Output => ' . $out_put;
            $today = date("Y-m-d H:i:s");
            $debugger_insert = array(
                'text' => $out_put,
                'created_at' => $today,
                'created_by' => '1'
            );
            $this->db->insert('email_debugger', $debugger_insert);


            $mail_update = array(
                'sent' => 'Yes',
                'created_by' => '1'
            );
            $this->db->where('id', $mail_id);
            $this->db->update('email', $mail_update);
        }


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            
        } else {
            
        }
    }

    function ExportCSV() {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";

        $query = "Select tbl_appointment.id as APPOINTMENT_ID ,tbl_groups.name as GROUP_NAME,"
                . "tbl_language.name as LAGUAGE_NAME , CONCAT(f_name,' ',m_name,' ' ,l_name) AS CLIENT_NAME,dob AS DOB ,tbl_client.status AS CLIENT_STATUS ,phone_no AS PHONE_NO ,tbl_client.clinic_number AS UNIUE_PATIENT_NUMBER,"
                . " tbl_client.created_at as ENROLLMENT_DATE ,tbl_client.updated_at AS TIME_STAMP,"
                . "tbl_client.id as CLIENT_ID,tbl_client.txt_frequency AS TEXT_FREQUENCY, tbl_client.txt_time AS TEXT_TIME,tbl_client.alt_phone_no AS ALTERNATIVE_PHONE_NO ,tbl_client.shared_no_name AS CLIENT_SHARED_PHONE_NAME,"
                . "tbl_client.smsenable AS ENABLE_SMS ,tbl_appointment.sent_flag AS MESSAGE_sENT ,tbl_appointment.appntmnt_date AS APPOINTMENT_DATE,"
                . "tbl_appointment.app_status AS APPOINTMENT_STATUS,tbl_appointment.app_msg AS APPOINTMENT_MESSAGE,tbl_appointment.updated_at APPOINTMENT_TIME_STAMP "
                . "from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id inner join tbl_groups on tbl_groups.id = tbl_client.group_id inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " where tbl_client.status='Active' and tbl_appointment.sent_flag ='0' group by tbl_client.id  ";
        $result = $this->db->query($query);
        $filename = "appointments.csv";
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        write_file(FCPATH . '/documents/sys_reports/appointments.csv', $data);
        if (!write_file(FCPATH . '/documents/sys_reports/appointments.csv', $data)) {
            // // // echo 'Unable to write the file';
        } else {

            // // // echo 'Report saved succesfully!';
        }
        force_download($filename, $data);
        return $result;
    }

    function email_trial() {

        $verification_code = "Jndfueiviuf3wISUBVCIcbvredgubii";

        $config = array();
        $config['useragent'] = "CodeIgniter";
        $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "localhost";
        $config['smtp_port'] = "25";
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->load->library('email');

        $this->email->initialize($config);

        $this->email->from('harrisdindisamuel@gmail.com', 'admin');
        $this->email->to('hdindi@mhealthkenya.org');
        $this->email->cc('harris.samuel@strathmore.edu');
        // $this->email->bcc($this->input->post('email'));
        $this->email->subject('Registration Verification: Continuous Imapression');
        $msg = "Thanks for signing up!
            Your account has been created, 
            you can login with your credentials after you have activated your account by pressing the url below.
            Please click this link to activate your account:<a href=\"" . base_url('user/verify') . "/{$verification_code}\">Click Here</a>";

        $this->email->message($msg);
        //$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));

        $this->email->send(FALSE);

        // Will only print the email headers, excluding the message subject and body
        $out_put = $this->email->print_debugger();

        // // // echo 'Out put =>' . $out_put . '</br>';
    }

    // Send Gmail to another user
    public function Send_eMail() {

        // Storing submitted values

        $receiver_email = "harrisdindisamuel@gmail.com";

        $subject = "Thi fodnvdniu diu";
        $message = "This is a trial message ....";

        // Configure email library
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = 465;
        $config['smtp_user'] = 'support.tech@mhealthkenya.org';
        $config['smtp_pass'] = 'Support.tech123!@#';

        // Load email library and passing configured values to email library 
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        // Sender email address
        $this->email->from('harrisdindisamuel@gmail.com', "DINDI HARRIS SAMUEL ");
        // Receiver email address
        $this->email->to($receiver_email);
        // Subject of email
        $this->email->subject($subject);
        // Message in email
        $this->email->message($message);

        if ($this->email->send(FALSE)) {
            // // // echo $this->email->print_debugger();
            $output = 'Email Successfully Send !';
        } else {
            // // // echo $this->email->print_debugger();
            $output = '<p class="error_msg">Invalid Gmail Account or Password !</p>';
        }
        // // // echo 'Out put => ' . $output . '</br> ';
    }

    function trial() {
        $this->load->library('email');

        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_user'] = 'support.tech@mhealthkenya.org';
        $config['smtp_pass'] = 'Support.tech123!@#';
        $config['smtp_port'] = 465;
        $this->email->initialize($config);

        $this->email->set_newline("\r\n");

        // Sender email address
        $this->email->from('harrisdindisamuel@gmail.com');
        // Receiver email address
        $this->email->to('harrisdindisamuel@gmail.com');
        // Subject of email
        $this->email->subject('Trial Email');
        // Message in email
        $this->email->message('Message... going...out.....');

        if ($this->email->send(FALSE)) {
           echo $this->email->print_debugger();
            $output = 'Email Successfully Send !';
        } else {
           echo $this->email->print_debugger();
            $output = '<p class="error_msg">Invalid Gmail Account or Password !</p>';
        }
       echo $output;
        // Send email(s) here... 
    }

    function new_trigger() {
        //Get All Clients from the  system who have consented for Wellness and Adherence Messages
        $get_clients = $this->db->query("Select  tbl_groups.name as group_name,tbl_groups.id as group_id,tbl_language.name as language_name , "
                        . "tbl_language.id as language_id, f_name,m_name,l_name,dob,tbl_client.status,phone_no,tbl_client.created_at as enrollment_date,"
                        . "tbl_client.txt_time,tbl_client.txt_frequency,tbl_client.updated_at,tbl_client.id as client_id,tbl_time.name as daytime,"
                        . " wellness_enable,motivational_enable  from tbl_client "
                        . " inner join tbl_language on tbl_language.id = tbl_client.language_id inner join tbl_groups on tbl_groups.id = tbl_client.group_id inner join tbl_time on tbl_time.id = tbl_client.txt_time "
                        . "where tbl_client.status='Active' and tbl_client.wellness_enable = 'YES' OR tbl_client.motivational_enable = 'YES' ")->result();
        foreach ($get_clients as $value) {

            $client_id = $value->client_id;
            $language = $value->language_name;
            $group_id = $value->group_id;
            $language_id = $value->language_id;

            $time = $value->txt_time;
            $daytime = $value->daytime;
            $frequency = $value->txt_frequency;
            $enrollment_date = $value->enrollment_date;
            $phone_no = $value->phone_no;
            $wellness_enable = $value->wellness_enable;
            $motivational_enable = $value->motivational_enable;

            $new_frequency = "+" . $frequency . " Hours";
        }

        //
    }

    function ushauri_msgs() {

        //Get the  frequency of messages that should be going out, 
        //from the  frequency schedule all the messages that should be going out to the clients 
        //Be checking on a dialy basis if the message meets the  criteria and then send it to the  client. 
        //Get the  frequency of message flowing 

        $get_frequency = $this->db->query("Select * from tbl_notification_flow where notification_type='Other' and based_on='Motivational'")->result();
        foreach ($get_frequency as $value) {
            
        }



















        //Get the current week , check the last ougoing message per client level
        // and based on the conditions (if the  conditions were met) , if they were met , schedule the  next message
        //Tables required : => tbl_messages , tbl_clnt_ushauri 
        //1=> Get Clients based on the  date added on the  system 

        $today = date("Y-m-d H:i:s");
        $dayofweek = date('w', strtotime($today));
        //// echo 'Day of week => ' . $dayofweek;

        $ddate = $today;
        $date = new DateTime($ddate);
        $week = $date->format("W");
        $year = date('Y');

        $get_client_day = $this->db->query("SELECT * FROM tbl_client WHERE DAYOFWEEK(created_at)=$dayofweek AND smsenable='Yes' AND motivational_enable='Yes'");
        $check_existnce = $get_client_day->num_rows();

        if ($check_existnce > 0) {

            $get_client_details = $get_client_day->result();

            foreach ($get_client_details as $value) {
                $phone_no = $value->phone_no;
                $client_id = $value->id;
                $language_id = $value->language_id;
                $gender_id = $value->gender;
                $group_id = $value->group_id;
                $get_clnt_ushauri = $this->db->query("Select * from tbl_clnt_ushauri where client_id=$client_id and DATE(`created_at`) = (SELECT MAX(DATE(created_at)) FROM tbl_clnt_ushauri AS b WHERE b.`client_id`='$client_id') GROUP BY client_id LIMIT 1");
                $check_get_clnt_ushauri = $get_clnt_ushauri->num_rows();
                if ($check_get_clnt_ushauri > 0) {
                    //Get the  current stage of previous message
                    $get_clnt_ushauri_data = $get_clnt_ushauri->result();

                    foreach ($get_clnt_ushauri_data as $value) {
                        $clinet_id_value = $value->client_id;
                        $message_id_value = $value->message_id;
                        $week_value = $value->week;
                        $created_at_value = $value->created_at;
                        $day_week_value = $value->day_week;
                        $year_value = $value->year;
                        $logic_flow_id_value = $value->logic_flow_id;
                        // echo 'Year Value ' . $year_value . '   and Current Year ' . $year . '</br>';
                        // echo 'Week value ' . $week_value . ' and Current Week  ' . $week . '</br>';

                        if ($year == $year_value and $week_value == $week) {
                            // echo 'No out going msg, year and week are the same... ';
                        } else {
                            $new_logic_flow = $logic_flow_id_value + 1;
                            // echo 'New Logic Flow ID : => ' . $new_logic_flow . '</br>';
                            if ($new_logic_flow == 3) {
                                $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Adult' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();


                                foreach ($get_outgoing_msg as $value) {
                                    $message = $value->message;
                                    $message_type_id = $value->message_type_id;
                                    $message_id = $value->id;
                                    $logic_flow_id = $value->logic_flow;
                                    $source = "40148";
                                    // echo "Outgoing Mesage 1 => " . $message . '<br>';
                                    $this->db->trans_start();
                                    $outgoing = array(
                                        'destination' => $phone_no,
                                        'msg' => $message,
                                        'responded' => "No",
                                        'status' => "Active",
                                        'message_type_id' => $message_type_id,
                                        'source' => $source,
                                        'clnt_usr_id' => $client_id,
                                        'recepient_type' => 'Client',
                                        'status' => 'Not Sent',
                                        'created_at ' => $today,
                                        'created_by' => '1'
                                    );
                                    $this->db->insert('outgoing', $outgoing);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        $this->db->trans_start();
                                        $clnt_ushauri = array(
                                            'client_id' => $client_id,
                                            'message_id' => $message_id,
                                            'week' => $week,
                                            'created_at' => $today,
                                            'day_week' => $dayofweek,
                                            'year' => $year,
                                            'logic_flow_id' => $logic_flow_id,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('clnt_ushauri', $clnt_ushauri);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    }
                                }
                            }
                            if ($new_logic_flow == 4 or $new_logic_flow == 5) {
                                // echo 'Loginc ID No +>=>' . $new_logic_flow;
                                //Check if client is an Adolescent or Adult
                                //If client is adolescent , queue the message
                                //If client is adult , ignore and append +1 to the loagic flow id and pick the  next message
                                //Key => 1 : Adult 2:Adolescent
                                $get_outgoing_msg = '';
                                if ($group_id == 1) { //Adult
                                    // echo 'Adult' . $new_logic_flow . ' and language ID => ' . $language_id . '<br>';
                                    $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='6' and language_id='$language_id'")->result();


                                    foreach ($get_outgoing_msg as $value) {
                                        $message = $value->message;
                                        $message_type_id = $value->message_type_id;
                                        $message_id = $value->id;
                                        $logic_flow_id = $value->logic_flow;
                                        $source = "40148";
                                        // echo "Outgoing Mesage 1 => " . $message . '<br>';
                                        $this->db->trans_start();
                                        $outgoing = array(
                                            'destination' => $phone_no,
                                            'msg' => $message,
                                            'responded' => "No",
                                            'status' => "Active",
                                            'message_type_id' => $message_type_id,
                                            'source' => $source,
                                            'clnt_usr_id' => $client_id,
                                            'recepient_type' => 'Client',
                                            'status' => 'Not Sent',
                                            'created_at ' => $today,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('outgoing', $outgoing);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            $this->db->trans_start();
                                            $clnt_ushauri = array(
                                                'client_id' => $client_id,
                                                'message_id' => $message_id,
                                                'week' => $week,
                                                'created_at' => $today,
                                                'day_week' => $dayofweek,
                                                'year' => $year,
                                                'logic_flow_id' => $logic_flow_id,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('clnt_ushauri', $clnt_ushauri);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        }
                                    }
                                } else if ($group_id == 2) { //Adolescent
                                    // echo 'Adolescent Grouping ' . $new_logic_flow . '<br>';
                                    if ($new_logic_flow == 4) {

                                        $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Adolescent' and message_type_id='4' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                        foreach ($get_outgoing_msg as $value) {
                                            $message = $value->message;
                                            $message_type_id = $value->message_type_id;
                                            $message_id = $value->id;
                                            $logic_flow_id = $value->logic_flow;
                                            $source = "40148";
                                            // echo "Outgoing Mesage 1 => " . $message . '<br>';
                                            $this->db->trans_start();
                                            $outgoing = array(
                                                'destination' => $phone_no,
                                                'msg' => $message,
                                                'responded' => "No",
                                                'status' => "Active",
                                                'message_type_id' => $message_type_id,
                                                'source' => $source,
                                                'clnt_usr_id' => $client_id,
                                                'recepient_type' => 'Client',
                                                'status' => 'Not Sent',
                                                'created_at ' => $today,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('outgoing', $outgoing);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                $this->db->trans_start();
                                                $clnt_ushauri = array(
                                                    'client_id' => $client_id,
                                                    'message_id' => $message_id,
                                                    'week' => $week,
                                                    'created_at' => $today,
                                                    'day_week' => $dayofweek,
                                                    'year' => $year,
                                                    'logic_flow_id' => $logic_flow_id,
                                                    'created_by' => '1'
                                                );
                                                $this->db->insert('clnt_ushauri', $clnt_ushauri);
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        }
                                    } else {
                                        $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Adolescent' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                        foreach ($get_outgoing_msg as $value) {
                                            $message = $value->message;
                                            $message_type_id = $value->message_type_id;
                                            $message_id = $value->id;
                                            $logic_flow_id = $value->logic_flow;
                                            $source = "40148";
                                            // echo "Outgoing Mesage 1 => " . $message . '<br>';
                                            $this->db->trans_start();
                                            $outgoing = array(
                                                'destination' => $phone_no,
                                                'msg' => $message,
                                                'responded' => "No",
                                                'status' => "Active",
                                                'message_type_id' => $message_type_id,
                                                'source' => $source,
                                                'clnt_usr_id' => $client_id,
                                                'recepient_type' => 'Client',
                                                'status' => 'Not Sent',
                                                'created_at ' => $today,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('outgoing', $outgoing);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                $this->db->trans_start();
                                                $clnt_ushauri = array(
                                                    'client_id' => $client_id,
                                                    'message_id' => $message_id,
                                                    'week' => $week,
                                                    'created_at' => $today,
                                                    'day_week' => $dayofweek,
                                                    'year' => $year,
                                                    'logic_flow_id' => $logic_flow_id,
                                                    'created_by' => '1'
                                                );
                                                $this->db->insert('clnt_ushauri', $clnt_ushauri);
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                
                            }

                            if ($gender_id == 1) {
                                //Get only cilents who are Female 
                                if ($new_logic_flow == 7 or $new_logic_flow == 8 or $new_logic_flow == 26) {

                                    // echo 'Logic ID is equal to 7 or 8 or 26 <br>';
                                    $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Female' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                    foreach ($get_outgoing_msg as $value) {
                                        $message = $value->message;
                                        $message_type_id = $value->message_type_id;
                                        $message_id = $value->id;
                                        $logic_flow_id = $value->logic_flow;
                                        $source = "40148";
                                        // echo "Outgoing Mesage 1 => " . $message . '<br>';
                                        $this->db->trans_start();
                                        $outgoing = array(
                                            'destination' => $phone_no,
                                            'msg' => $message,
                                            'responded' => "No",
                                            'status' => "Active",
                                            'message_type_id' => $message_type_id,
                                            'source' => $source,
                                            'clnt_usr_id' => $client_id,
                                            'recepient_type' => 'Client',
                                            'status' => 'Not Sent',
                                            'created_at ' => $today,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('outgoing', $outgoing);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            $this->db->trans_start();
                                            $clnt_ushauri = array(
                                                'client_id' => $client_id,
                                                'message_id' => $message_id,
                                                'week' => $week,
                                                'created_at' => $today,
                                                'day_week' => $dayofweek,
                                                'year' => $year,
                                                'logic_flow_id' => $logic_flow_id,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('clnt_ushauri', $clnt_ushauri);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        }
                                    }
                                }
                            } else if ($gender_id == 2) {
                                if ($new_logic_flow == 23) {
                                    // echo 'Logic ID is equal to 23 <br>';
                                    $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Male' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                    foreach ($get_outgoing_msg as $value) {
                                        $message = $value->message;
                                        $message_type_id = $value->message_type_id;
                                        $message_id = $value->id;
                                        $logic_flow_id = $value->logic_flow;
                                        $source = "40148";
                                        // echo "Outgoing Mesage 1 => " . $message . '<br>';
                                        $this->db->trans_start();
                                        $outgoing = array(
                                            'destination' => $phone_no,
                                            'msg' => $message,
                                            'responded' => "No",
                                            'status' => "Active",
                                            'message_type_id' => $message_type_id,
                                            'source' => $source,
                                            'clnt_usr_id' => $client_id,
                                            'recepient_type' => 'Client',
                                            'status' => 'Not Sent',
                                            'created_at ' => $today,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('outgoing', $outgoing);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            $this->db->trans_start();
                                            $clnt_ushauri = array(
                                                'client_id' => $client_id,
                                                'message_id' => $message_id,
                                                'week' => $week,
                                                'created_at' => $today,
                                                'day_week' => $dayofweek,
                                                'year' => $year,
                                                'logic_flow_id' => $logic_flow_id,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('clnt_ushauri', $clnt_ushauri);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        }
                                    }
                                } else {
                                    
                                }
                            }

                            if ($new_logic_flow != 3 or $new_logic_flow != 4 or $new_logic_flow != 5 or $new_logic_flow != 7 or $new_logic_flow != 8 or $new_logic_flow != 23 or $new_logic_flow != 26) {

                                // echo 'Logic ID is not equal to 3 or 4 or 5 or 7 or 8 or 23 or 26 <br>';

                                $get_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                foreach ($get_outgoing_msg as $value) {
                                    $message = $value->message;
                                    $message_type_id = $value->message_type_id;
                                    $message_id = $value->id;
                                    $logic_flow_id = $value->logic_flow;
                                    $source = "40148";
                                    // echo "Outgoing Mesage 1 => " . $message . '<br>';
                                    $this->db->trans_start();
                                    $outgoing = array(
                                        'destination' => $phone_no,
                                        'msg' => $message,
                                        'responded' => "No",
                                        'status' => "Active",
                                        'message_type_id' => $message_type_id,
                                        'source' => $source,
                                        'clnt_usr_id' => $client_id,
                                        'recepient_type' => 'Client',
                                        'status' => 'Not Sent',
                                        'created_at ' => $today,
                                        'created_by' => '1'
                                    );
                                    $this->db->insert('outgoing', $outgoing);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        $this->db->trans_start();
                                        $clnt_ushauri = array(
                                            'client_id' => $client_id,
                                            'message_id' => $message_id,
                                            'week' => $week,
                                            'created_at' => $today,
                                            'day_week' => $dayofweek,
                                            'year' => $year,
                                            'logic_flow_id' => $logic_flow_id,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('clnt_ushauri', $clnt_ushauri);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    //Get the  first message from the  system
                    $get_outgoing_msg = $this->db->query("Select * from tbl_messages where message_type_id=2 and logic_flow=1 and language_id='$language_id' ")->result();
                    foreach ($get_outgoing_msg as $value) {
                        $message = $value->message;
                        $message_type_id = $value->message_type_id;
                        $message_id = $value->id;
                        $logic_flow_id = $value->logic_flow;
                        $source = "40148";
                        $this->db->trans_start();
                        // echo "Outgoing Mesage 2 => " . $message . '<br>';
                        $outgoing = array(
                            'destination' => $phone_no,
                            'msg' => $message,
                            'responded' => "No",
                            'status' => "Active",
                            'message_type_id' => $message_type_id,
                            'source' => $source,
                            'clnt_usr_id' => $client_id,
                            'recepient_type' => 'Client',
                            'status' => 'Not Sent',
                            'created_at' => $today,
                            'created_by' => '1'
                        );
                        $this->db->insert('outgoing', $outgoing);
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            $this->db->trans_start();
                            $clnt_ushauri = array(
                                'client_id' => $client_id,
                                'message_id' => $message_id,
                                'week' => $week,
                                'created_at' => $today,
                                'day_week' => $dayofweek,
                                'year' => $year,
                                'logic_flow_id' => $logic_flow_id,
                                'created_by' => '1'
                            );
                            $this->db->insert('clnt_ushauri', $clnt_ushauri);
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                
                            }
                        }
                    }
                }
            }
        } else {
            
        }
    }

    function client_grouping_calculator() {

        $sql = $this->db->query("SELECT * FROM tbl_client WHERE group_id='0' or group_id IS NULL ")->result();
        foreach ($sql as $value) {
            $client_id = $value->id;
            $dob = $value->dob;
            $current_grouping = $value->group_id;
            // // // echo 'DOB => ' . $dob . '<br>';
            $dob = str_replace('/', '-', $dob);
            $dob = date("Y-m-d", strtotime($dob));

            $current_date = date("Y-m-d");
            $current_date = date_create($current_date);
            $new_dob = date_create($dob);
            $date_diff = date_diff($new_dob, $current_date);
            $diff = $date_diff->format("%R%a days");
            //// // // echo 'Days difference => ' . $diff . '<br>';
            $diff = substr($diff, 0);
            $diff = (int) $diff;
            // echo ($diff) . '<br>';
            $category = "";
            if ($diff >= 3650 and $diff <= 7299) {
                // echo 'Adolescent';
                $category .= 2;
            } else if ($diff >= 7300) {
                // echo 'Adult';
                $category .= 1;
            } else if ($diff <= 3649) {
                // echo 'Paeds';
                $category .= 3;
            }
            $current_grouping = (int) $current_grouping;
            $category = (int) $category;
            if (strcmp($category, $current_grouping) === 0) {
                
            } else {
                // echo 'New Category => ' . $category . '</br>';

                $this->db->trans_start();

                $data_update = array(
                    'group_id' => $category,
                    'updated_by' => '1'
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $data_update);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function send_welcome_msg() {
        $get_clients = $this->db->query("SELECT * FROM tbl_client WHERE smsenable='Yes' AND welcome_sent='No' ");
        $check_existence = $get_clients->num_rows();
        if ($check_existence > 0) {
            //Get out going msg
            $get_clients_results = $get_clients->result();
            print($get_clients_results);
            foreach ($get_clients_results as $value) {
                $phone_no = $value->phone_no;
                $client_id = $value->id;
                $smsenable = $value->smsenable;
                $enrollment_date = $value->enrollment_date;
                $lang = $value->language_id;
                $fname = $value->f_name;
                if ($smsenable == "Yes") {
                    $this->db->trans_start();
                    // // // echo 'Update success <br> ';
                    $data_client_update = array(
                        'welcome_sent' => 'Yes',
                        'updated_by' => '1'
                    );
                    $this->db->where('id', $client_id);
                    $this->db->update('client', $data_client_update);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        $message_type = "Welcome";

                        $get_welcome_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='3' and logic_flow='1' and language_id='$lang' LIMIT 1")->result();
                        foreach ($get_welcome_msg as $value) {
                            // // // echo 'Msg #1  queued ';
                            $message = $value->message;
                            $message_type_id = $value->message_type_id;
                            $client_name = ucwords(strtolower($fname)) . " ";

                            $cleaned_msg = str_replace("XXX", $client_name, $message);
                            $created_at = date('Y-m-d H:i:s');
                            $source = '40148';
                            $client_destination = $phone_no;

                            $this->db->trans_start();
                            // // // echo 'Cleaned Msg => ' . $cleaned_msg . '<br>';
                            $data_outgoing = array(
                                'destination' => $client_destination,
                                'source' => $source,
                                'msg' => $cleaned_msg,
                                'status' => 'Not Sent',
                                'message_type_id' => $message_type_id,
                                'responded' => 'No',
                                'clnt_usr_id' => $client_id,
                                'recepient_type' => 'Client',
                                'created_at' => $created_at,
                                'created_by' => '1'
                            );
                            $this->db->insert('outgoing', $data_outgoing);


                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {

                                $get_next_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='3' and logic_flow='2' and language_id='$lang' LIMIT 1")->result();
                                foreach ($get_next_outgoing_msg as $value) {
                                    $message = $value->message;
                                    $message_type_id = $value->message_type_id;
                                    // // // echo 'Message #2 queued ....';
                                    $this->db->trans_start();
                                    $data_outgoing = array(
                                        'destination' => $client_destination,
                                        'source' => $source,
                                        'msg' => $message,
                                        'status' => 'Not Sent',
                                        'message_type_id' => $message_type_id,
                                        'responded' => 'No',
                                        'clnt_usr_id' => $client_id,
                                        'recepient_type' => 'Client',
                                        'created_at' => $created_at,
                                        'created_by' => '1'
                                    );
                                    $this->db->insert('outgoing', $data_outgoing);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {






//                                        $enrollment_date2 = str_replace('/', '-', $enrollment_date2);
//                                        $enrollment_date2 = date("Y-m-d", strtotime($enrollment_date2));

                                        $current_date = date("Y-m-d");
                                        $current_date = date_create($current_date);
                                        $enrollment_date2 = date_create($enrollment_date);
                                        $date_diff = date_diff($enrollment_date2, $current_date);

                                        $diff = $date_diff->format("%R%a days");
                                        // // // echo 'Date Difference => ' . $diff;
                                        $diff = substr($diff, 1, 2);
                                        if ($diff >= 30) {
                                            
                                        } elseif ($diff <= 30) {





                                            // $message_type_id = 3;
                                            // $logic_flow = 3;
                                            //  $target_group = 'All';

                                            $get_welcome_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='3' and language_id='$lang' LIMIT 1")->result();
                                            foreach ($get_welcome_msg as $value) {
                                                // // // echo 'Message #3 queued ....';
                                                $message = $value->message;
                                                $message_type_id = $value->message_type_id;
                                                $client_name = ucwords(strtolower($fname)) . " ";

                                                $cleaned_msg = str_replace("XXX", $client_name, $message);
                                                $created_at = date('Y-m-d H:i:s');
                                                $source = '40148';
                                                $client_destination = $phone_no;
                                                // // // echo $cleaned_msg;
                                                $this->db->trans_start();

                                                $data_outgoing = array(
                                                    'destination' => $client_destination,
                                                    'source' => $source,
                                                    'msg' => $cleaned_msg,
                                                    'status' => 'Not Sent',
                                                    'message_type_id' => $message_type_id,
                                                    'responded' => 'No',
                                                    'clnt_usr_id' => $client_id,
                                                    'recepient_type' => 'Client',
                                                    'created_at' => $created_at,
                                                    'created_by' => '1'
                                                );
                                                $this->db->insert('outgoing', $data_outgoing);


                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    
                }
            }
        } else {
            
        }
    }

    protected function hex2bin($hexdata) {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }

    function decrypt() {

        $code = '6aaf7ed1f615227bd2526106a6324430063363a51ccd166edcc54556ce0e120b71b4c1f0514b67481b4183426209a67c7bbdf37ac78dd319ebdd6d73c37fd206869099f8abb51021a593dc3ab8d3922c';
        $iv = 'fedcba9876543210';
        $key = '0123456789abcdef';


        //$key = $this->hex2bin($key);
        $code = $this->hex2bin($code);
        $iv = $iv;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        // // // echo utf8_encode(trim($decrypted));
    }

    function re_assign_user_access() {
        /*

         * Reassign user access credentials to users in a situation where there is a change in the modules assigned to a specific role access
         * Get all active roles in the  system
         * Get all active modules in that sepcified roles
         * Counter check if the active user permission is the  same with the ones assigned in the  role modules
         * If they are found....ignnore , no changes will be done
         * If a new role exists in the  role module and not in the  user permission table, add it to the  user permission table
         * If a role exists in the  role module and not in the user permission, then add the  role to the  user permission table
         * 
         * Tables to be used : 
         * #1 => tbl_roles
         * #2 => tbl_role_module
         * #3 => tbl_user_permission
         *          */



        $this->db->trans_start();

        //Get roles from the  system 
        $get_roles = $this->db->get_where('role', array('status' => 'Active'))->result();
        foreach ($get_roles as $role_value) {
            $role_name = $role_value->name;
            $role_id = $role_value->id;
            $today = date('Y-m-d H:i:s');
            $get_active_role_modules = $this->db->get_where('role_module', array('status' => 'Active', 'role_id' => $role_id))->result();
            foreach ($get_active_role_modules as $role_module_value) {
                $module_id = $role_module_value->module_id;
                $get_user_permission = $this->db->get_where('user_permission', array('status' => 'Active', 'role_id ' => $role_id));
                foreach ($get_user_permission->result() as $user_permission_value) {
                    $user_permission_module_id = $user_permission_value->module_id;
                    $user_permission_id = $user_permission_value->id;
                    $user_id = $user_permission_value->user_id;
                    if ($module_id == $user_permission_module_id) {
                        //Ignore ...Do Nothing......
                    } else {
                        //It does not exist in the  role module and it should be added to the  user permission tables
                        $data_insert_user_permission = array(
                            'role_id' => $role_id,
                            'module_id' => $module_id,
                            'user_id' => $user_id,
                            'status' => 'Active',
                            'created_at' => $today,
                            'created_by' => '1'
                        );
                        $this->db->insert('user_permission', $data_insert_user_permission);
                    }
                }
            }
        }


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            //Transaction Failed...
        } else {
            //Transaction was successfully .....

            $description = "New User Permission was created in the  system. ";
            $user_id = '1';
            $this->log_action($description, $user_id);



            // // // echo 'New modules were added successfull......<br>';
        }
    }

}
