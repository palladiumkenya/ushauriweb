<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Chore extends MY_Controller {

    public $data = '';
    private $iv = 'fedcba9876543210';
    private $key = '0123456789abcdef';

    function __construct() {
        parent::__construct();


        // $this->load->library("Infobip");

        $this->data = new DBCentral();
    }

    function index() {
        
    }

    function not_found() {
        $this->load->view("not_found");
    }

    function sender() {

        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
        $this->config->load('config', TRUE);
        // Retrieve a config item named site_name contained within the blog_settings array
        $shortcode = $this->config->item('shortcode', 'config');



        $query = "    SELECT 
        tbl_clnt_outgoing.id 
      FROM
        tbl_clnt_outgoing 
        INNER JOIN tbl_client 
          ON tbl_client.`id` = tbl_clnt_outgoing.`clnt_usr_id` 
        INNER JOIN tbl_time 
          ON tbl_time.`id` = tbl_client.`txt_time` 
      WHERE 1 
        AND tbl_clnt_outgoing.created_at >= CURDATE() - INTERVAL 1 DAY 
        AND tbl_clnt_outgoing.created_at <= CURDATE() + INTERVAL 1 DAY 
        AND tbl_clnt_outgoing.STATUS = 'Not Sent' 
        AND tbl_time.`name` = HOUR(NOW()) GROUP by tbl_client.id, tbl_clnt_outgoing.id ";

        $no_outgoing_msgs = $this->db->query($query)->num_rows();
        if ($no_outgoing_msgs > 0) {
            $limit = round($no_outgoing_msgs / 2);
            echo 'No of outgoing msgs => ' . $no_outgoing_msgs . ' and our limit is => ' . $limit . '<br>';
            $new_query = "    SELECT 
            tbl_clnt_outgoing.id,
            source,
            destination,
            msg,
            tbl_clnt_outgoing.`status`,
            responded,
            content_id,
            message_type_id,
            clnt_usr_id,
            tbl_clnt_outgoing.`created_at`,
            tbl_clnt_outgoing.`updated_at`,
            tbl_clnt_outgoing.`recepient_type` 
          FROM
            tbl_clnt_outgoing 
            INNER JOIN tbl_client 
              ON tbl_client.`id` = tbl_clnt_outgoing.`clnt_usr_id` 
            INNER JOIN tbl_time 
              ON tbl_time.`id` = tbl_client.`txt_time` 
          WHERE 1 
            AND tbl_clnt_outgoing.created_at >= CURDATE() - INTERVAL 1 DAY 
            AND tbl_clnt_outgoing.created_at <= CURDATE() + INTERVAL 1 DAY 
            AND tbl_clnt_outgoing.STATUS = 'Not Sent' 
            AND tbl_time.`name` = HOUR(NOW())  GROUP by tbl_client.id, tbl_clnt_outgoing.id LIMIT $limit ";

            //get all Client messages due yesterday and today that have not been sent from the  system and limit them to 200 after every 1 minute
            $clnt_outgoing = $this->db->query($new_query)->result();

            foreach ($clnt_outgoing as $value) {
                $clnt_outgoing_id = $value->id;
                $source = $value->source;
                $destination = $value->destination;
                $msg = $value->msg;
                $status = $value->status;
                $responded = $value->responded;
                $content_id = $value->content_id;
                $message_type_id = $value->message_type_id;
                $clnt_usr_id = $value->clnt_usr_id;
                $created_at = $value->created_at;
                $recepient_type = $value->recepient_type;

                $check_welcome = $this->db->query(" Select * from tbl_clnt_outgoing where message_type_id='3' and  clnt_usr_id='$clnt_usr_id' and status='Sent' LIMIT 1 ")->num_rows();
                if ($check_welcome > 0) {
                    //echo "Welcome message has been sent , send other messages....";
                    //Welcome has been sent, send other messages ...
                    if ($status == "Not Sent") {


                        $check_if_similiar_msg_sent_qry = $this->db->query("Select * from tbl_clnt_outgoing where msg LIKE '%$msg%' and destination='$destination' and status='Sent' and created_at >= CURDATE()- INTERVAL 1 DAY AND created_at <= CURDATE() + INTERVAL 1 DAY ")->num_rows();
                        if ($check_if_similiar_msg_sent_qry < 1) {
                            echo 'Message has not been sent , send the current message ...-> ';
                            //Message has not been sent, send the  current message
                            //Number process , Append conutry code prefix on the  phone no if its not appended e.g 0712345678 => 254712345678	
                            $mobile = substr($destination, -9);
                            $len = strlen($mobile);
                            if ($len < 10) {
                                $destination = "+254" . $mobile;
                            }



                            //Password the access credentials for sending message 

                            $send_text = $this->send_message($source, $destination, $msg, $clnt_outgoing_id);
                            echo 'Send status => ' . $send_text . '<br>';
                            $today = date("Y-m-d H:i:s");
                            echo 'Msg #1' . $msg . '</br>Destination  # 1 : ' . $destination . '<br>Source => ' . $source . '</br>';



                            if ($send_text) {
                                echo 'Sent Logs Insert Successfully for id $clnt_outgoing_id </br> ';
                                $this->db->trans_start();

                                $data_update_clnt_outgoing = array(
                                    'status' => 'Sent'
                                );
                                $this->db->where('id', $clnt_outgoing_id);
                                $this->db->update('clnt_outgoing', $data_update_clnt_outgoing);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    echo 'Outgoing Logs Update successfuly </br> ';
                                }
                            } else if (!$send_text) {
                                
                            } else {
                                
                            }
                        } else {
                            echo 'Message has been sent , do not send the  current message';
                        }
                    }
                } else if ($check_welcome <= 0) {
                    echo 'Send welcome first ... for client ID =>' . $clnt_usr_id . '<br>';
                    //Send the welcome first ....
                    $this->check_welcome_sent();
                }
            }
        }


        //USER SENDER STARTS FROM HERE....

        $get_user_msgs = $query = $this->db->get_where('tbl_usr_outgoing', array('status' => "Not Sent"));
        if ($get_user_msgs->num_rows() > 0) {
            //Fetch the found messages
            foreach ($get_user_msgs->result() as $value) {
                $destination = $value->destination;
                $source = $value->source;
                $msg = $value->msg;
                $outgoing_id = $value->id;
                $send_message = $this->send_message($source, $destination, $msg, $outgoing_id);
                if ($send_message) {
                    $this->db->trans_start();
                    $outgoing = array(
                        'status' => 'Sent'
                    );
                    $this->db->where('id', $outgoing_id);
                    $this->db->update('usr_outgoing', $outgoing);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() == FALSE) {
                        
                    } else {
                        
                    }
                } else {
                    
                }
            }
        } else {
            //Do Nothing
        }


        // $this->output->enable_profiler(TRUE);
    }

    function alt_send_msgs() {
        $gateway = $this->load->database('gateway', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
        $get_failed_msgs = $gateway->query("SELECT * FROM `delivery_report` WHERE failureReason != 'undefined' AND date(created_at) = CURRENT_DATE GROUP BY msg_id ORDER BY created_at ASC")->result();
        foreach ($get_failed_msgs as $value) {
            $message_id = $value->msg_id;
            $get_msg_id = $gateway->query("SELECT * FROM sender_status WHERE messageid='$message_id'")->result();
            foreach ($get_msg_id as $value) {
                $message_id = $value->messageid;
                $get_client_outgoing_id = $this->db->query("Select * from tbl_clnt_outgoing where messageId='$message_id'")->result();
                foreach ($get_client_outgoing_id as $value) {
                    $clnt_usr_id = $value->clnt_usr_id;
                    $msg = $value->msg;
                    $outgoing_id = $value->id;
                    $get_phone_no = $this->db->query("Select * from tbl_client where id='$clnt_usr_id'")->result();
                    foreach ($get_phone_no as $value) {
                        $alt_phone_no = $value->alt_phone_no;
                        $trtmnt_buddy_phone_no = $value->buddy_phone_no;
                        $send_message = $this->send_message($source, $alt_phone_no, $msg, $outgoing_id);
                        if ($send_message) {
                            
                        } else {
                            
                        }
                        $send_message = $this->send_message($source, $trtmnt_buddy_phone_no, $msg, $outgoing_id);
                        if ($send_message) {
                            
                        } else {
                            
                        }
                    }
                }
            }
        }
    }

    function broadcast() {


        $broadcast = $this->db->query(" SELECT 
  tbl_sms_queue.id,
  clnt_usr_id,
  recepient_type,
  destination,
  sms_datetime,
  sms_status,
  tbl_sms_queue.created_at,
  tbl_sms_queue.updated_at,
  source,
  tbl_sms_queue.msg,
  tbl_sms_queue.time_id,
  tbl_sms_queue.broadcast_date,
  tbl_time.name,
  clnt_usr_id,
  recepient_type 
FROM
  tbl_sms_queue 
  INNER JOIN tbl_time 
    ON tbl_time.id = tbl_sms_queue.time_id 
  INNER JOIN tbl_broadcast 
    ON tbl_sms_queue.`broadcast_id` = tbl_broadcast.id 
WHERE sms_status = 'Not Sent' 
  AND DATE(tbl_sms_queue.broadcast_date) = CURDATE() 
  AND tbl_broadcast.is_approved = 'Yes' ")->result();
        //print_r($broadcast);

        foreach ($broadcast as $value) {
            $broadcast_id = $value->id;
            $source = $value->source;
            $destination = $value->destination;
            $msg = $value->msg;

            $sms_status = $value->sms_status;
            $time = $value->name;
            $broadcast_date = $value->broadcast_date;
            $broadcast_date_today = date($broadcast_date);
            $broadcast_unix_timestamp = strtotime($broadcast_date);
            $clnt_usr_id = $value->clnt_usr_id;
            $recepient_type = $value->recepient_type;

            $datetime = new DateTime($broadcast_date);
            $broadcast_day = $datetime->format('d');
            $broadcat_time = $time;
            $broadcast_hour = explode(":", $broadcat_time);
            echo 'Broadcast date : ' . $broadcast_day . 'Broadcast time : ' . $broadcast_hour[0] . '</br>';
            $today = date("d");
            $hour = date("H");
            echo 'Today is : ' . $today . " and Hour is : " . $hour . "</br>";

            $today_month = date('m');
            $broadcast_month = $datetime->format('m');
            echo 'Broadcast month : ' . $broadcast_month . 'Todays  month : ' . $today_month . '</br>';

            $today_year = date('Y');
            $broadcast_year = $datetime->format('Y');
            echo 'Broadcast date : ' . $today_year . 'Broadcast time : ' . $broadcast_year . '</br>';

            if ($sms_status == "Sent") {
                echo "Message already sent to User No : => " . $destination . '<br>';
            } else {


                if ($broadcast_year == $today_year) {
                    echo 'Year Found...<br>';
                    if ($today_month == $broadcast_month) {
                        echo 'Month Found...<br>';
                        if ($today == $broadcast_day) {
                            echo ' Day Found...<br>';
                            echo "Broadcast Hour  " . $broadcast_hour[0] . '   Current Hour   ' . $hour . '<br>';
                            if ($hour == $broadcast_hour[0]) {
                                echo 'Hour Found...<br>';
                                //Number process        
                                $mobile = substr($destination, -9);
                                $len = strlen($mobile);
                                if ($len < 10) {
                                    $destination = "254" . $mobile;
                                }

                                echo 'Msg => ' . $msg . '</br>';
                                $send_text = $this->send_message($source, $destination, $msg, $broadcast_id);

                                echo "Sent status => " . $send_text;

                                if ($send_text) {

                                    echo 'Message sent successfully ....';
                                    echo 'Broad cast ID => ' . $broadcast_id;
                                    $this->db->trans_start();
                                    $sms_update = array(
                                        'sms_status' => 'Sent'
                                    );
                                    $this->db->where('id', $broadcast_id);
                                    $this->db->update('sms_queue', $sms_update);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {

                                        $this->db->trans_start();

                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');
                                        $created_at = date("Y-m-d H:i:s");


                                        if ($recepient_type == "User") {
                                            $outgoing = array(
                                                'destination' => $destination,
                                                'msg' => $msg,
                                                'status' => 'Sent',
                                                'message_type_id' => '7',
                                                'source' => $source,
                                                'created_at' => $created_at,
                                                'clnt_usr_id' => $clnt_usr_id,
                                                'recepient_type' => "User",
                                                'updated_by' => '1',
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('usr_outgoing', $outgoing);
                                        } else if ($recepient_type == "Client") {
                                            $outgoing = array(
                                                'destination' => $destination,
                                                'msg' => $msg,
                                                'status' => 'Sent',
                                                'message_type_id' => '7',
                                                'source' => $source,
                                                'created_at' => $created_at,
                                                'clnt_usr_id' => $clnt_usr_id,
                                                'recepient_type' => "Client",
                                                'updated_by' => '1',
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('clnt_outgoing', $outgoing);
                                        }



                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    }
                                } else {
                                    ?>
                                    <div class="alert alert-danger" role="alert">
                                        <b>An error occurred!</b> Reason:
                                        <?php
                                        echo $send_text;
                                        ?>
                                    </div>
                                    <?php
                                }


                                ///END OF INFOBIP API 
                            }
                        }
                    }
                }
            }
        }
    }

    function appointment_scheduler() {
        $this->booked_scheduler();
        $this->notified_scheduler();
        $this->missed_scheduler();
        $this->defaulted_scheduler();
        $this->ltfu_scheduler();
    }

    function booked_scheduler() {
        //Current Date 
        $current_date = date("Y-m-d");

        // Get all appointments who status is booked
        $appointments = $this->db->query("SELECT 
  tbl_appointment.id AS appointment_id,
  f_name,
  m_name,
  l_name,
  dob,
  tbl_client.status,
  phone_no,
  tbl_client.clinic_number,
  tbl_client.created_at AS enrollment_date,
  tbl_client.updated_at,
  tbl_client.id AS client_id,
  tbl_client.language_id AS language_id,
  tbl_client.clinic_number,
  tbl_client.client_status,
  tbl_client.txt_frequency,
  tbl_client.txt_time,
  tbl_client.alt_phone_no,
  tbl_client.shared_no_name,
  tbl_client.smsenable,
     
  tbl_appointment.appntmnt_date,
  tbl_appointment.app_status,
  tbl_appointment.app_msg,
  tbl_appointment.updated_at,
  tbl_appointment.app_type_1,
  tbl_client.group_id,
  
  tbl_appointment.sent_status,
  tbl_appointment.notified
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.client_id 
WHERE tbl_client.status = 'Active' 
  AND active_app = '1'  
  AND tbl_appointment.appntmnt_date > CURDATE() ")->result();

        foreach ($appointments as $value) {

            $f_name = $value->f_name;
            $m_name = $value->m_name;
            $l_name = $value->l_name;
            $phone_no = $value->phone_no;
            $txt_time = $value->txt_time;
            $alt_phone_no = $value->alt_phone_no;
            $smsenable = $value->smsenable;

            $appointment_date = $value->appntmnt_date;
            $appointment_msg = $value->app_msg;
            $app_status = $value->app_status;
            $appointment_id = $value->appointment_id;
            $notified = $value->notified;
            $sent_status = $value->sent_status;
            $language_id = $value->language_id;
            $client_id = $value->client_id;
            $group_id = $value->group_id;
            $client_name = ucwords(strtolower($f_name)) . " ";

            $check_outgoing_msg_existence = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id ='1' and clnt_usr_id='$client_id' and destination='$phone_no' and DATE(created_at) = DATE(NOW()) ")->num_rows();
            if ($check_outgoing_msg_existence > 0) {
                // // // echo 'Out going msg found';
            } else {
                $notification_flow = $this->db->query("Select * from tbl_notification_flow where status='Active' and notification_type ='Booked'")->result();

                foreach ($notification_flow as $value2) {
                    $notification_type = $value2->notification_type;
                    $notification_days = $value2->days;
                    $notification_value = $value2->value;
                    $notification_flow_id = $value2->id;
                    $notification_type = $value2->notification_type;
                    $notification_days = $value2->days;
                    $notification_value = $value2->value;
                    $notification_flow_id = $value2->id;

                    $notification_type = $value2->notification_type;

                    $notification_flow_days = $value2->days;

                    $current_date2 = new DateTime($current_date);

                    $appointment_date2 = new DateTime($appointment_date);
                    $current_month = date("m", strtotime($current_date));

                    $current_year = date("Y", strtotime($current_date));
                    $appointment_year = date("Y", strtotime($appointment_date));

                    $appointment_month = date("m", strtotime($appointment_date));

                    $days_diff = $current_date2->diff($appointment_date2)->format("%a");
                    if ($app_status == $notification_type && $notification_flow_days == 0) {
                        $notification_type = $this->db->query("Select notification_type,days,value,tbl_notification_flow.id as notification_flow_id from tbl_notification_flow 
                    where  notification_type ='$notification_type' ")->result();
                        //print_r($notification_type);
                        foreach ($notification_type as $value) {


                            if ($sent_status == "Booked Sent") {
                                // // // echo 'Booked Sent';
                                //Booked Sent Do Nothing
                            } else {
                                $get_content = $this->db->query("Select * from tbl_content where identifier='$notification_flow_id' and message_type_id='1' and language_id='$language_id' and group_id='$group_id' LIMIT 1")->result();

                                foreach ($get_content as $value) {
                                    $content = $value->content;
                                    $booked = "Booked";
                                    $content_id = $value->id;
                                    $message_type_id = $value->message_type_id;
                                    //Convert encoded character in the  message to clients real name and appointment day XXX => Client Name  YYY=> Appointment Date 

                                    $new_msg = str_replace("XXX", $client_name, $content);
                                    $appointment_date = date("d-m-Y", strtotime($appointment_date));
                                    $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);
                                    $today = date("Y-m-d H:i:s");
                                    $status = "Not Sent";
                                    $responded = "No";

                                    $yes_notified = 'Yes';
                                    $app_status = "Booked";
                                    $Booked_status = "Booked Sent";

                                    $this->db->trans_start();

                                    $update_appointment_array = array(
                                        'app_status' => $app_status,
                                        'app_msg' => $cleaned_msg,
                                        'notified' => $yes_notified,
                                        'sent_status' => $Booked_status,
                                        'updated_by' => '1'
                                    );
                                    $this->db->where('id', $appointment_id);
                                    $this->db->update('appointment', $update_appointment_array);

                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {






                                        $check_if_its_first_appointment = $this->db->query("Select * from tbl_appointment where client_id='$client_id'")->num_rows();
                                        if ($check_if_its_first_appointment < 1) {
                                            //This is the  first appointment , please ignore messaging
                                        } else {
                                            //This is not the  first appointment , schedule the  message
                                        }

                                        $check_if_client_kept_previous_appointment = $this->db->query("SELECT * FROM tbl_appointment WHERE active_app='0' AND client_id='$client_id'  ORDER BY id DESC LIMIT 1")->result();
                                        foreach ($check_if_client_kept_previous_appointment as $value) {
                                            $appointment_kept = $value->appointment_kept;
                                            if ($appointment_kept == 'Yes') {
                                                if ($smsenable == 'Yes') {
                                                    $this->db->trans_start();

                                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                    $this->config->load('config', TRUE);
                                                    // Retrieve a config item named site_name contained within the blog_settings array
                                                    $source = $this->config->item('shortcode', 'config');


                                                    $outgoing = array(
                                                        'destination' => $phone_no,
                                                        'msg' => $cleaned_msg,
                                                        'responded' => $responded,
                                                        'status' => $status,
                                                        'message_type_id' => $message_type_id,
                                                        'source' => $source,
                                                        'created_at' => $today,
                                                        'clnt_usr_id' => $client_id,
                                                        'recepient_type' => "Client",
                                                        'content_id' => $content_id,
                                                        'created_at' => $today,
                                                        'updated_by' => '1'
                                                    );
                                                    $this->db->insert('clnt_outgoing', $outgoing);

                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {
                                                        
                                                    }
                                                } else {
                                                    
                                                }
                                            } else {
                                                
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($current_date < $appointment_date) {
                            //Booked and Notified
                            $check_existence_booked = $this->db->get_where('notification_flow', array('days' => "$days_diff", 'notification_type' => $notification_type))->num_rows();
                            $check_existence_notified = $this->db->get_where('notification_flow', array('days' => "$days_diff", 'notification_type' => $notification_type))->num_rows();
                            if ($check_existence_booked == 1 && $notification_type == "Booked") {


                                if ($sent_status == "Booked Sent") {
                                    
                                } else {
                                    $get_content = $this->db->query("Select * from tbl_content where identifier='$notification_flow_id' and message_type_id='1' and language_id='$language_id' and group_id='$group_id' ")->result();
                                    foreach ($get_content as $value) {
                                        $booked = "Booked";
                                        $content = $value->content;
                                        $content_id = $value->id;
                                        //Convert encoded character in the  message to clients real name and appointment day XXX => Client Name  YYY=> Appointment Date 


                                        $new_msg = str_replace("XXX", $client_name, $content);
                                        $appointment_date = date("d-m-Y", strtotime($appointment_date));
                                        $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);


                                        $status = "Not Sent";
                                        $responded = "No";
                                        $yes_notified = 'Yes';
                                        $app_status = "Booked";
                                        $Booked_status = "Booked Sent";
                                        $this->db->trans_start();
                                        $update_appointment_array = array(
                                            'app_status' => $app_status,
                                            'app_msg' => $cleaned_msg,
                                            'notified' => $yes_notified,
                                            'sent_status' => $Booked_status,
                                            'updated_by' => '1'
                                        );
                                        $this->db->where('id', $appointment_id);
                                        $this->db->update('appointment', $update_appointment_array);

                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            $check_outgoing_msg_existence = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id ='1' and clnt_usr_id='$client_id' and destination='$phone_no' and DATE(created_at) = CURDATE()")->num_rows();
                                            if ($check_outgoing_msg_existence > 0) {
                                                
                                            } else {



                                                $check_if_its_first_appointment = $this->db->query("Select * from tbl_appointment where client_id='$client_id'")->num_rows();
                                                if ($check_if_its_first_appointment < 1) {
                                                    //This is the  first appointment , please ignore messaging
                                                } else {
                                                    //This is not the  first appointment , schedule the  message





                                                    $check_if_client_kept_previous_appointment = $this->db->query("SELECT * FROM tbl_appointment WHERE active_app='0' AND client_id='$client_id'  ORDER BY id DESC LIMIT 1")->result();
                                                    foreach ($check_if_client_kept_previous_appointment as $value) {
                                                        $appointment_kept = $value->appointment_kept;
                                                        if ($appointment_kept == 'Yes') {


                                                            if ($smsenable == 'Yes') {
                                                                $today = date("Y-m-d H:i:s");
                                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                $this->config->load('config', TRUE);
                                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                                $source = $this->config->item('shortcode', 'config');

                                                                $message_type_id = 1;
                                                                $this->db->trans_start();
                                                                $outgoing = array(
                                                                    'destination' => $phone_no,
                                                                    'msg' => $cleaned_msg,
                                                                    'responded' => $responded,
                                                                    'status' => $status,
                                                                    'message_type_id' => $message_type_id,
                                                                    'source' => $source,
                                                                    'clnt_usr_id' => $client_id,
                                                                    'recepient_type' => 'Client',
                                                                    'content_id' => $content_id,
                                                                    'created_at' => $today,
                                                                    'created_by' => '1'
                                                                );
                                                                $this->db->insert('clnt_outgoing', $outgoing);
                                                                $this->db->trans_complete();
                                                                if ($this->db->trans_status() === FALSE) {
                                                                    
                                                                } else {
                                                                    
                                                                }
                                                            } else {
                                                                
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function one_day_scheduler() {
        //Current Date 
        $current_date = date("Y-m-d");
        // Get all appointment dates 
        $appointments = $this->db->query(" SELECT 
  tbl_appointment.id AS appointment_id,
  f_name,
  m_name,
  l_name,
  dob,
  tbl_client.status,
  phone_no,
  tbl_client.clinic_number,
  tbl_client.created_at AS enrollment_date,
  tbl_client.updated_at,
  tbl_client.id AS client_id,
  tbl_client.language_id AS language_id,
  tbl_client.clinic_number,
  tbl_client.client_status,
  tbl_client.txt_frequency,
  tbl_client.txt_time,
  tbl_client.alt_phone_no,
  tbl_client.shared_no_name,
  tbl_client.smsenable,
   
  tbl_appointment.appntmnt_date,
  tbl_appointment.app_status,
  tbl_appointment.app_msg,
  tbl_appointment.updated_at,
     
  tbl_appointment.app_type_1,
   
  
  sent_status,
  tbl_appointment.notified 
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.client_id 
WHERE tbl_client.status = 'Active' 
  AND active_app = '1' 
    AND  DATE(`tbl_appointment`.`appntmnt_date`) = DATE(NOW() + INTERVAL 1 DAY) group by appointment_id ")->result();
        foreach ($appointments as $value) {
            $f_name = $value->f_name;
            $m_name = $value->m_name;
            $l_name = $value->l_name;
            $phone_no = $value->phone_no;
            $txt_time = $value->txt_time;
            $alt_phone_no = $value->alt_phone_no;
            $smsenable = $value->smsenable;

            $appointment_date = $value->appntmnt_date;
            $appointment_msg = $value->app_msg;

            $app_status = $value->app_status;
            $appointment_id = $value->appointment_id;
            $notified = $value->notified;
            $sent_status = $value->sent_status;
            $language_id = $value->language_id;
            $client_id = $value->client_id;
            $client_name = ucwords(strtolower($f_name)) . " ";

            /// // echo 'Client Name => ' . $client_name . 'and client id ' . $client_id . '</br>';



            $check_clnt_outgoing_msg_existence = $this->db->query("SELECT * FROM tbl_clnt_outgoing WHERE message_type_id=1 AND DATE(updated_at) = DATE(NOW()) AND clnt_usr_id=$client_id LIMIT 1")->num_rows();
            if ($check_clnt_outgoing_msg_existence > 0) {
                echo 'Message found ....<br>';
            } else {
                echo 'No message was found....';




                $notification_flow = $this->db->query("Select * from tbl_notification_flow where status='Active' and notification_type ='Notified' LIMIT 1")->result();


                foreach ($notification_flow as $value2) {
                    $notification_type = $value2->notification_type;

                    $notification_flow_days = $value2->days;

                    $current_date2 = new DateTime($current_date);

                    $appointment_date2 = new DateTime($appointment_date);
                    $current_month = date("m", strtotime($current_date));

                    $current_year = date("Y", strtotime($current_date));
                    $appointment_year = date("Y", strtotime($appointment_date));

                    $appointment_month = date("m", strtotime($appointment_date));

                    $days_diff = $current_date2->diff($appointment_date2)->format("%a");
                    echo 'Day difference : => ' . $days_diff . '</br>';

                    if ($current_date < $appointment_date) {
                        echo $client_name . '</br>' . $appointment_date . '</br> end <br>';
                        //Booked and Notified

                        $check_existence_booked = $this->db->get_where('notification_flow', array('days' => $days_diff, 'notification_type' => $notification_type))->num_rows();
                        $check_existence_notified = $this->db->get_where('notification_flow', array('days' => $days_diff, 'notification_type' => $notification_type))->num_rows();
                        if ($check_existence_notified == 1 && $notification_type == "Notified") {
                            // // // // echo 'Check notified found ....<br> ';

                            $target_group = 'All';
                            $message_type_id = 1;
                            // // // // echo 'Notification Flow Days ; ' . $notification_flow_days . ' And Days difference : ' . $days_diff . '</br>';
                            // // // // echo gettype($notification_flow_days);
                            $sevendays = '7';
                            $oneday = '1';
                            if (strcmp($sevendays, $days_diff) == 0) {

                                // // // // echo 'Line No 1076 7 Days to appointment for client name ' . $client_name . ' and day difference ' . $days_diff . ' and days is ' . $sevendays . '</br>';
                                $logic_flow_id = 2;
                            } elseif (strcmp($oneday, $days_diff) == 0) {
                                // // // // echo 'Line No 1079  One day to appointment..... for ' . $client_name . ' and day difference ' . $days_diff . ' and days is ' . $oneday . '</br>';
                                $logic_flow_id = 3;
                            }
                            // // // // echo 'Logic flow ' . $logic_flow_id . '</br>';



                            $check_clnt_outgoing_msg_existence = $this->db->query("SELECT * FROM tbl_clnt_outgoing WHERE message_type_id=1 AND DATE(created_at) = DATE(NOW()) AND clnt_usr_id=$client_id LIMIT 1")->num_rows();
                            if ($check_clnt_outgoing_msg_existence > 0) {
                                // // // // echo 'Message found ....<br>';
                            } else {
                                // // // // echo 'No message was found....';





                                $get_content = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='1' and logic_flow='$logic_flow_id' and language_id='$language_id' LIMIT 1 ")->result();
                                foreach ($get_content as $value) {
                                    $content_id = $value->id;
                                    $content = $value->message;
                                    //Convert encoded character in the  message to clients real name and appointment day XXX => Client Name  YYY=> Appointment Date 


                                    $today = date("Y-m-d H:i:s");

                                    $new_msg = str_replace("XXX", $client_name, $content);
                                    $appointment_date = date("d-m-Y", strtotime($appointment_date));
                                    $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);

                                    echo $cleaned_msg;
                                    $status = "Not Sent";
                                    $responded = "No";
                                    $yes_notified = 'Yes';
                                    $app_status = "Notified";
                                    $Notified_status = "Notified Sent";
                                    $this->db->trans_start();
                                    $update_appointment_array = array(
                                        'app_status' => $app_status,
                                        'app_msg' => $cleaned_msg,
                                        'notified' => $yes_notified,
                                        'sent_status' => $Notified_status,
                                        'updated_by' => '1'
                                    );
                                    $this->db->where('id', $appointment_id);
                                    $this->db->update('appointment', $update_appointment_array);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {



                                        // echo 'Login flow id => ' . $logic_flow_id . 'Cleaned msg => ' . $cleaned_msg . '<br>';
                                        if ($smsenable == 'Yes') {
                                            $this->db->trans_start();
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');


                                            $message_type_id = 1;
                                            $clnt_outgoing = array(
                                                'destination' => $phone_no,
                                                'msg' => $cleaned_msg,
                                                'responded' => $responded,
                                                'status' => $status,
                                                'message_type_id' => $message_type_id,
                                                'source' => $source,
                                                'clnt_usr_id' => $client_id,
                                                'recepient_type' => 'Client',
                                                'content_id' => $content_id,
                                                'created_at' => $today,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        } else {
                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function seven_day_scheduler() {
        //Current Date 
        $current_date = date("Y-m-d");
        // Get all appointment dates 
        $appointments = $this->db->query(" SELECT 
  tbl_appointment.id AS appointment_id,
  f_name,
  m_name,
  l_name,
  dob,
  tbl_client.status,
  phone_no,
  tbl_client.clinic_number,
  tbl_client.created_at AS enrollment_date,
  tbl_client.updated_at,
  tbl_client.id AS client_id,
  tbl_client.language_id AS language_id,
  tbl_client.clinic_number,
  tbl_client.client_status,
  tbl_client.txt_frequency,
  tbl_client.txt_time,
  tbl_client.alt_phone_no,
  tbl_client.shared_no_name,
  tbl_client.smsenable,
   
  tbl_appointment.appntmnt_date,
  tbl_appointment.app_status,
  tbl_appointment.app_msg,
  tbl_appointment.updated_at,
     
  tbl_appointment.app_type_1,
   
  
  sent_status,
  tbl_appointment.notified 
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.client_id 
WHERE tbl_client.status = 'Active' 
  AND active_app = '1' 
    AND  DATE(`tbl_appointment`.`appntmnt_date`) = DATE(NOW() + INTERVAL 7 DAY) group by appointment_id ")->result();
        foreach ($appointments as $value) {
            $f_name = $value->f_name;
            $m_name = $value->m_name;
            $l_name = $value->l_name;
            $phone_no = $value->phone_no;
            $txt_time = $value->txt_time;
            $alt_phone_no = $value->alt_phone_no;
            $smsenable = $value->smsenable;

            $appointment_date = $value->appntmnt_date;
            $appointment_msg = $value->app_msg;

            $app_status = $value->app_status;
            $appointment_id = $value->appointment_id;
            $notified = $value->notified;
            $sent_status = $value->sent_status;
            $language_id = $value->language_id;
            $client_id = $value->client_id;
            $client_name = ucwords(strtolower($f_name)) . " ";

            /// // echo 'Client Name => ' . $client_name . 'and client id ' . $client_id . '</br>';



            $check_clnt_outgoing_msg_existence = $this->db->query("SELECT * FROM tbl_clnt_outgoing WHERE message_type_id=1 AND DATE(created_at) = DATE(NOW()) AND clnt_usr_id=$client_id LIMIT 1")->num_rows();
            if ($check_clnt_outgoing_msg_existence > 0) {
                // // echo 'Message found ....<br>';
            } else {
                // // echo 'No message was found....';




                $notification_flow = $this->db->query("Select * from tbl_notification_flow where status='Active' and notification_type ='Notified' LIMIT 1")->result();


                foreach ($notification_flow as $value2) {
                    $notification_type = $value2->notification_type;

                    $notification_flow_days = $value2->days;

                    $current_date2 = new DateTime($current_date);

                    $appointment_date2 = new DateTime($appointment_date);
                    $current_month = date("m", strtotime($current_date));

                    $current_year = date("Y", strtotime($current_date));
                    $appointment_year = date("Y", strtotime($appointment_date));

                    $appointment_month = date("m", strtotime($appointment_date));

                    $days_diff = $current_date2->diff($appointment_date2)->format("%a");
                    // // // // echo 'Day difference : => ' . $days_diff . '</br>';

                    if ($current_date < $appointment_date) {
                        // // // // echo $client_name . '</br>' . $appointment_date . '</br> end <br>';
                        //Booked and Notified

                        $check_existence_booked = $this->db->get_where('notification_flow', array('days' => $days_diff, 'notification_type' => $notification_type))->num_rows();
                        $check_existence_notified = $this->db->get_where('notification_flow', array('days' => $days_diff, 'notification_type' => $notification_type))->num_rows();
                        if ($check_existence_notified == 1 && $notification_type == "Notified") {
                            // // // // echo 'Check notified found ....<br> ';

                            $target_group = 'All';
                            $message_type_id = 1;
                            // // // // echo 'Notification Flow Days ; ' . $notification_flow_days . ' And Days difference : ' . $days_diff . '</br>';
                            // // // // echo gettype($notification_flow_days);
                            $sevendays = '7';
                            $oneday = '1';
                            if (strcmp($sevendays, $days_diff) == 0) {

                                // // // // echo 'Line No 1076 7 Days to appointment for client name ' . $client_name . ' and day difference ' . $days_diff . ' and days is ' . $sevendays . '</br>';
                                $logic_flow_id = 2;
                            } elseif (strcmp($oneday, $days_diff) == 0) {
                                // // // // echo 'Line No 1079  One day to appointment..... for ' . $client_name . ' and day difference ' . $days_diff . ' and days is ' . $oneday . '</br>';
                                $logic_flow_id = 3;
                            }
                            // // // // echo 'Logic flow ' . $logic_flow_id . '</br>';



                            $check_clnt_outgoing_msg_existence = $this->db->query("SELECT * FROM tbl_clnt_outgoing WHERE message_type_id=1 AND DATE(created_at) = DATE(NOW()) AND clnt_usr_id=$client_id LIMIT 1")->num_rows();
                            if ($check_clnt_outgoing_msg_existence > 0) {
                                // // // // echo 'Message found ....<br>';
                            } else {
                                // // // // echo 'No message was found....';





                                $get_content = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='1' and logic_flow='$logic_flow_id' and language_id='$language_id' LIMIT 1 ")->result();
                                foreach ($get_content as $value) {
                                    $content_id = $value->id;
                                    $content = $value->message;
                                    //Convert encoded character in the  message to clients real name and appointment day XXX => Client Name  YYY=> Appointment Date 


                                    $today = date("Y-m-d H:i:s");

                                    $new_msg = str_replace("XXX", $client_name, $content);
                                    $appointment_date = date("d-m-Y", strtotime($appointment_date));
                                    $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);


                                    $status = "Not Sent";
                                    $responded = "No";
                                    $yes_notified = 'Yes';
                                    $app_status = "Notified";
                                    $Notified_status = "Notified Sent";
                                    $this->db->trans_start();
                                    $update_appointment_array = array(
                                        'app_status' => $app_status,
                                        'app_msg' => $cleaned_msg,
                                        'notified' => $yes_notified,
                                        'sent_status' => $Notified_status,
                                        'updated_by' => '1'
                                    );
                                    $this->db->where('id', $appointment_id);
                                    $this->db->update('appointment', $update_appointment_array);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {



                                        // echo 'Login flow id => ' . $logic_flow_id . 'Cleaned msg => ' . $cleaned_msg . '<br>';
                                        if ($smsenable == 'Yes') {
                                            $this->db->trans_start();
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');


                                            $message_type_id = 1;
                                            $clnt_outgoing = array(
                                                'destination' => $phone_no,
                                                'msg' => $cleaned_msg,
                                                'responded' => $responded,
                                                'status' => $status,
                                                'message_type_id' => $message_type_id,
                                                'source' => $source,
                                                'clnt_usr_id' => $client_id,
                                                'recepient_type' => 'Client',
                                                'content_id' => $content_id,
                                                'created_at' => $today,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        } else {
                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function notified_scheduler() {


        $this->seven_day_scheduler();
        $this->one_day_scheduler();
    }

    function missed_scheduler() {
        //Current Date 
        $current_date = date("Y-m-d");
        // Get all appointment dates 
        $appointments = $this->db->query(" SELECT 
  tbl_appointment.id AS appointment_id,
  tbl_groups.name AS group_name,
  tbl_groups.id AS group_id,
  f_name,
  m_name,
  l_name,
  dob,
  tbl_client.status,
  phone_no,
  tbl_client.clinic_number,
  tbl_client.created_at AS enrollment_date,
  tbl_client.updated_at,
  tbl_client.id AS client_id,
  tbl_client.language_id AS language_id,
  tbl_client.clinic_number,
  tbl_client.client_status,
  tbl_client.txt_frequency,
  tbl_client.txt_time,
  tbl_client.alt_phone_no,
  tbl_client.shared_no_name,
  tbl_client.smsenable,
     
  tbl_appointment.appntmnt_date,
  tbl_appointment.app_status,
  tbl_appointment.app_msg,
  tbl_appointment.updated_at,
  tbl_appointment.app_type_1,
  
  sent_status,
  tbl_appointment.notified
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.client_id 
  INNER JOIN tbl_groups 
    ON tbl_groups.id = tbl_client.group_id 
WHERE tbl_client.status = 'Active' 
  AND tbl_groups.status = 'Active' 
  AND tbl_appointment.appntmnt_date < CURDATE() 
  AND tbl_appointment.active_app = '1' ")->result();

        foreach ($appointments as $value) {
            $group_name = $value->group_name;
            $group_id = $value->group_id;
            $f_name = $value->f_name;
            $m_name = $value->m_name;
            $l_name = $value->l_name;
            $phone_no = $value->phone_no;
            $txt_time = $value->txt_time;
            $alt_phone_no = $value->alt_phone_no;
            $smsenable = $value->smsenable;

            $appointment_date = $value->appntmnt_date;
            $appointment_msg = $value->app_msg;
            $app_status = $value->app_status;
            $appointment_id = $value->appointment_id;
            $notified = $value->notified;
            $sent_status = $value->sent_status;
            $language_id = $value->language_id;
            $client_id = $value->client_id;
            $client_name = ucwords(strtolower($f_name)) . " ";




            $notification_flow = $this->db->query("Select * from tbl_notification_flow where status='Active' and notification_type ='Missed'")->result();


            foreach ($notification_flow as $value2) {

                $notification_type = $value2->notification_type;
                $notification_days = $value2->days;
                $notification_value = $value2->value;
                $notification_flow_id = $value2->id;
                $notification_type = $value2->notification_type;
                $notification_days = $value2->days;
                $notification_value = $value2->value;
                $notification_flow_id = $value2->id;


                $notification_type = $value2->notification_type;

                $notification_flow_days = $value2->days;
                $notification_flow_id = $value2->id;

                $current_date2 = new DateTime($current_date);

                $appointment_date2 = new DateTime($appointment_date);
                $current_month = date("m", strtotime($current_date));

                $current_year = date("Y", strtotime($current_date));
                $appointment_year = date("Y", strtotime($appointment_date));

                $appointment_month = date("m", strtotime($appointment_date));

                $days_diff = $current_date2->diff($appointment_date2)->format("%a");

                echo 'Day Difference => ' . $days_diff . '<br> ';
                if ($current_date > $appointment_date) {
                    //Missed and Defaulted check up
                    echo 'Appointment Date => ' . $appointment_date . '<br> Notification Flow days => ' . $notification_flow_days . ' <br>  Days difference => ' . $days_diff . ' and app status' . $app_status . '</br> .................................<br>     ............................';

                    $check_existence_missed = $this->db->get_where('notification_flow', array('days' => $days_diff, 'notification_type' => "Missed"))->num_rows();
                    if ($check_existence_missed == 1 && $notification_type == "Missed") {
                        // // // echo 'Sent status => ' . $sent_status . '<br>';
                        if ($sent_status == "Missed Sent") {
                            echo 'Missed sent .....<br>';
                        } else {
                            echo 'Missed not sent .....<br> ';

                            $target_group = 'All';
                            $message_type_id = 1;
                            $logic_flow_id = 4;

                            echo 'Notification ID =>    ' . $notification_flow_id . ' Lang ID  => ' . $language_id . 'Group ID => ' . $group_id;

                            $get_content = $this->db->query("Select * from tbl_content where identifier='$notification_flow_id' and message_type_id='1' and language_id='$language_id' and group_id='$group_id' LIMIT 1 ")->result();

                            foreach ($get_content as $value) {
                                $missed = "Missed";
                                $content = $value->content;

                                $content_id = $value->id;
                                $message_type_id = $value->message_type_id;
                                //Convert encoded character in the  message to clients real name and appointment day XXX => Client Name  YYY=> Appointment Date 
                                $today = date("Y-m-d H:i:s");
                                $new_msg = str_replace("XXX", $client_name, $content);
                                $appointment_date = date("d-m-Y", strtotime($appointment_date));
                                $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);

                                echo $cleaned_msg;

                                $status = "Not Sent";
                                $responded = "No";

                                $yes_notified = 'Yes';
                                $app_status = "Missed";
                                $Missed_status = "Missed Sent";
                                $this->db->trans_start();
                                $update_appointment_array = array(
                                    'app_status' => $app_status,
                                    'app_msg' => $cleaned_msg,
                                    'notified' => $yes_notified,
                                    'sent_status' => $Missed_status,
                                    'updated_by' => '1'
                                );
                                $this->db->where('id', $appointment_id);
                                $this->db->update('appointment', $update_appointment_array);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {

                                    $check_outgoing_msg_existence = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id ='1' and clnt_usr_id='$client_id' and destination='$phone_no' and DATE(created_at) = CURDATE()")->num_rows();
                                    if ($check_outgoing_msg_existence > 0) {
                                        
                                    } else {


                                        if ($smsenable == 'Yes') {
                                            $this->db->trans_start();
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');


                                            $outgoing = array(
                                                'destination' => $phone_no,
                                                'msg' => $cleaned_msg,
                                                'responded' => $responded,
                                                'status' => $status,
                                                'message_type_id' => $message_type_id,
                                                'source' => $source,
                                                'clnt_usr_id' => $client_id,
                                                'recepient_type' => 'Client',
                                                'content_id' => $content_id,
                                                'created_at' => $today,
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('clnt_outgoing', $outgoing);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        } else {
                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function defaulted_scheduler() {
        //Current Date 
        $current_date = date("Y-m-d");
        // Get all appointment dates 
        $appointments = $this->db->query("SELECT
        tbl_appointment.appntmnt_date,
        tbl_appointment.id AS appointment_id,
        f_name,
        m_name,
        l_name,
        dob,
        tbl_client.STATUS,
        phone_no,
        tbl_client.clinic_number,
        tbl_client.created_at AS enrollment_date,
        tbl_client.updated_at,
        tbl_client.id AS client_id,
        tbl_client.language_id AS language_id,
        tbl_client.clinic_number,
        tbl_client.client_status,
        tbl_client.txt_frequency,
        tbl_client.txt_time,
        tbl_client.alt_phone_no,
        tbl_client.shared_no_name,
        tbl_client.smsenable,
        tbl_appointment.app_status,
        tbl_appointment.app_msg,
        tbl_appointment.updated_at,
        tbl_appointment.app_type_1,
        tbl_client.group_id,
        sent_status,
        tbl_appointment.notified 
    FROM
        tbl_appointment
        INNER JOIN tbl_client ON tbl_client.id = tbl_appointment.client_id 
    WHERE
        tbl_client.STATUS = 'Active' 
        AND active_app = '1' 
        AND tbl_appointment.appntmnt_date < CURDATE( ) 
        AND datediff( CURDATE( ), date( appntmnt_date ) ) BETWEEN 7 
        AND 90 
    ORDER BY
        appntmnt_date ASC ")->result();

        foreach ($appointments as $value) {
            $f_name = $value->f_name;
            $m_name = $value->m_name;
            $l_name = $value->l_name;
            $phone_no = $value->phone_no;
            $txt_time = $value->txt_time;
            $alt_phone_no = $value->alt_phone_no;
            $smsenable = $value->smsenable;

            $appointment_date = $value->appntmnt_date;
            $appointment_msg = $value->app_msg;
            $app_status = $value->app_status;
            $appointment_id = $value->appointment_id;
            $notified = $value->notified;
            $sent_status = $value->sent_status;
            $language_id = $value->language_id;
            $client_id = $value->client_id;
            $group_id = $value->group_id;
            $client_name = ucwords(strtolower($f_name)) . " ";


            // // // echo 'Client Name : ' . $client_name . '<br>';
            // // // echo 'Out going message not found .....<br> ';
            $notification_flow = $this->db->query("Select * from tbl_notification_flow where status='Active' and notification_type ='Defaulted'")->result();

            foreach ($notification_flow as $value2) {

                $notification_type = $value2->notification_type;
                $notification_days = $value2->days;
                $notification_value = $value2->value;
                $notification_flow_id = $value2->id;
                $notification_type = $value2->notification_type;
                $notification_days = $value2->days;
                $notification_value = $value2->value;
                $notification_flow_id = $value2->id;


                $notification_type = $value2->notification_type;

                $notification_flow_days = $value2->days;

                $current_date2 = new DateTime($current_date);

                $appointment_date2 = new DateTime($appointment_date);
                $current_month = date("m", strtotime($current_date));

                $current_year = date("Y", strtotime($current_date));
                $appointment_year = date("Y", strtotime($appointment_date));

                $appointment_month = date("m", strtotime($appointment_date));

                $days_diff = $current_date2->diff($appointment_date2)->format("%a");

                echo 'Days Diff' . $days_diff . '</br>';


                if ($current_date > $appointment_date) {
                    // Defaulted check up
                    $check_existence_defaulted = $this->db->get_where('notification_flow', array('days' => $days_diff, 'notification_type' => "Defaulted"))->num_rows();
                    if ($check_existence_defaulted == 1 && $notification_type == "Defaulted") {

                        if ($sent_status == "Default Sent") {
                            echo 'Sent Status => ' . $sent_status . '<br>';
                        } else {
                            echo 'Defaulted Not Sent <br>';
                        }
                        $target_group = 'All';
                        $message_type_id = 1;



                        $today = date("Y-m-d H:i:s");

                        $status = "Not Sent";
                        $responded = "No";
                        $yes_notified = 'Yes';
                        $app_status = "Default";
                        $Default_status = "Default Sent";
                        $this->db->trans_start();
                        $update_appointment_array = array(
                            'app_status' => $app_status,
                            'notified' => $yes_notified,
                            'sent_status' => $Default_status,
                            'updated_by' => '1'
                        );
                        $this->db->where('id', $appointment_id);
                        $this->db->update('appointment', $update_appointment_array);
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            
                        }












                        $check_outgoing_msg_existence = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id ='1' and clnt_usr_id='$client_id' and destination='$phone_no' and DATE(created_at) = DATE(NOW()) ")->num_rows();
                        if ($check_outgoing_msg_existence > 0) {
                            echo 'Out going message already exists ...<br> ';
                        } else {
                            // echo $notification_flow_id . '<br>';
                            $get_content = $this->db->query(" Select * from tbl_content where identifier='$notification_flow_id' and message_type_id='1' and language_id='$language_id' and group_id='$group_id' ")->result();
                            print_r($get_content);
                            foreach ($get_content as $value) {
                                $defaulted = "Defaulted";
                                $content = $value->content;
                                $content_id = $value->id;
                                $message_type_id = $value->message_type_id;
                                //Convert encoded character in the  message to clients real name and appointment day XXX => Client Name  YYY=> Appointment Date 

                                $new_msg = str_replace("XXX", $client_name, $content);
                                $appointment_date = date("d-m-Y", strtotime($appointment_date));
                                $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);
                                echo 'Cleaned msg =>' . $cleaned_msg . '</br>';
                                $today = date("Y-m-d H:i:s");

                                $status = "Not Sent";
                                $responded = "No";
                                $yes_notified = 'Yes';
                                $app_status = "Default";
                                $Default_status = "Default Sent";
                                $this->db->trans_start();
                                $update_appointment_array = array(
                                    'app_status' => $app_status,
                                    'app_msg' => $cleaned_msg,
                                    'notified' => $yes_notified,
                                    'sent_status' => $Default_status,
                                    'updated_by' => '1'
                                );
                                $this->db->where('id', $appointment_id);
                                $this->db->update('appointment', $update_appointment_array);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    if ($smsenable == 'Yes') {
                                        $this->db->trans_start();
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');


                                        $outgoing = array(
                                            'destination' => $phone_no,
                                            'msg' => $cleaned_msg,
                                            'responded' => $responded,
                                            'status' => $status,
                                            'message_type_id' => $message_type_id,
                                            'source' => $source,
                                            'clnt_usr_id' => $client_id,
                                            'recepient_type' => 'Client',
                                            'content_id' => $content_id,
                                            'created_at' => $today,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('clnt_outgoing', $outgoing);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    } else {
                                        
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function ltfu_scheduler() {
        //Current Date 
        $current_date = date("Y-m-d");
        // Get all appointment dates 
        $appointments = $this->db->query("SELECT 
  tbl_appointment.id AS appointment_id,
  f_name,
  m_name,
  l_name,
  dob,
  tbl_client.status,
  phone_no,
  tbl_client.clinic_number,
  tbl_client.created_at AS enrollment_date,
  tbl_client.updated_at,
  tbl_client.id AS client_id,
  tbl_client.language_id AS language_id,
  tbl_client.clinic_number,
  tbl_client.client_status,
  tbl_client.txt_frequency,
  tbl_client.txt_time,
  tbl_client.alt_phone_no,
  tbl_client.shared_no_name,
  tbl_client.smsenable,
     
  tbl_appointment.appntmnt_date,
  tbl_appointment.app_status,
  tbl_appointment.app_msg,
  tbl_appointment.updated_at,
  tbl_appointment.app_type_1,
  tbl_client.group_id,
  
  sent_status,
  tbl_appointment.notified
FROM
  tbl_appointment 
  INNER JOIN tbl_client 
    ON tbl_client.id = tbl_appointment.client_id 
WHERE tbl_client.status = 'Active'
  AND active_app = '1' 
  AND tbl_appointment.appntmnt_date < CURDATE() ")->result();

        foreach ($appointments as $value) {
            $f_name = $value->f_name;
            $m_name = $value->m_name;
            $l_name = $value->l_name;
            $phone_no = $value->phone_no;
            $txt_time = $value->txt_time;
            $alt_phone_no = $value->alt_phone_no;
            $smsenable = $value->smsenable;

            $appointment_date = $value->appntmnt_date;
            $appointment_msg = $value->app_msg;
            $app_status = $value->app_status;
            $appointment_id = $value->appointment_id;
            $notified = $value->notified;
            $sent_status = $value->sent_status;
            $language_id = $value->language_id;
            $client_id = $value->client_id;
            $group_id = $value->group_id;
            $client_name = ucwords(strtolower($f_name)) . " ";


            // // // echo 'Client Name : ' . $client_name . '<br>';
            // // // echo 'Out going message not found .....<br> ';
            $notification_flow = $this->db->query("Select * from tbl_notification_flow where status='Active' and notification_type ='LTFU'")->result();

            foreach ($notification_flow as $value2) {

                $notification_type = $value2->notification_type;
                $notification_days = $value2->days;
                $notification_value = $value2->value;
                $notification_flow_id = $value2->id;
                $notification_type = $value2->notification_type;
                $notification_days = $value2->days;
                $notification_value = $value2->value;
                $notification_flow_id = $value2->id;

                $notification_type = $value2->notification_type;

                $notification_flow_days = $value2->days;

                $current_date2 = new DateTime($current_date);

                $appointment_date2 = new DateTime($appointment_date);
                $current_month = date("m", strtotime($current_date));

                $current_year = date("Y", strtotime($current_date));
                $appointment_year = date("Y", strtotime($appointment_date));

                $appointment_month = date("m", strtotime($appointment_date));

                $days_diff = $current_date2->diff($appointment_date2)->format("%a");

                // // // echo 'Days Diff' . $days_diff . '</br>';


                if ($current_date > $appointment_date) {
                    // Defaulted check up
                    $check_existence_defaulted = $this->db->get_where('notification_flow', array('days' => $days_diff, 'notification_type' => "LTFU"))->num_rows();
                    if ($check_existence_defaulted == 1 && $notification_type == "LTFU") {

                        if ($sent_status == "LTFU Sent") {
                            
                        } else {
                            
                        }
                        $target_group = 'All';
                        $message_type_id = 1;
                        // // // echo '';




                        $check_outgoing_msg_existence = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id ='1' and clnt_usr_id='$client_id' and destination='$phone_no' and DATE(created_at) = DATE(NOW()) ")->num_rows();
                        if ($check_outgoing_msg_existence > 0) {
                            // // // echo 'Out going message already exists ...<br> ';
                        } else {

                            $get_content = $this->db->query("Select * from tbl_content where identifier='$notification_flow_id' and message_type_id='1' and language_id='$language_id' and group_id='$group_id' LIMIT 1 ")->result();
                            foreach ($get_content as $value) {
                                $defaulted = "Defaulted";
                                $content = $value->content;
                                $content_id = $value->id;
                                $message_type_id = $value->message_type_id;
                                //Convert encoded character in the  message to clients real name and appointment day XXX => Client Name  YYY=> Appointment Date 

                                $new_msg = str_replace("XXX", $client_name, $content);
                                $appointment_date = date("d-m-Y", strtotime($appointment_date));
                                $cleaned_msg = str_replace("YYY", $appointment_date, $new_msg);
                                // // // echo 'Cleaned msg =>' . $cleaned_msg . '</br>';
                                $today = date("Y-m-d H:i:s");

                                $status = "Not Sent";
                                $responded = "No";
                                $yes_notified = 'Yes';
                                $app_status = "Default";
                                $Default_status = "Default Sent";
                                $this->db->trans_start();
                                $update_appointment_array = array(
                                    'app_status' => $app_status,
                                    'app_msg' => $cleaned_msg,
                                    'notified' => $yes_notified,
                                    'sent_status' => $Default_status,
                                    'updated_by' => '1'
                                );
                                $this->db->where('id', $appointment_id);
                                $this->db->update('appointment', $update_appointment_array);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {



                                    if ($smsenable == 'Yes') {

                                        $this->db->trans_start();
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');


                                        $outgoing = array(
                                            'destination' => $phone_no,
                                            'msg' => $cleaned_msg,
                                            'responded' => $responded,
                                            'status' => $status,
                                            'message_type_id' => $message_type_id,
                                            'source' => $source,
                                            'clnt_usr_id' => $client_id,
                                            'recepient_type' => 'Client',
                                            'content_id' => $content_id,
                                            'created_at' => $today,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('clnt_outgoing', $outgoing);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    } else {
                                        
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function map_client_responses() {
        $response_type = '';
        $get_all_today_responses = $this->db->query(" SELECT 
  * 
FROM
  tbl_responses 
WHERE  recognised = 'UnRecognised' and created_at > NOW() - INTERVAL 48 HOUR   ")->result();
        //print_r($get_all_today_responses);
        //exit();
        foreach ($get_all_today_responses as $value) {

            $source = $value->source;
            $response_id = $value->id;
            $msg = $value->msg;
            $created_at = $value->created_at;
            $updated_at = $value->updated_at;
            $destination = $value->destination;
            $created_by = $value->created_by;
            $msg = ucfirst(strtolower($msg));
            $mobile = substr($source, -9);
            $len = strlen($mobile);
            if ($len < 10) {
                $source = "0" . $mobile;
            }
            //echo $source . '<br>';
            $client_query = $this->db->query("Select * from tbl_client where phone_no ='$source' LIMIT 1 ");
            if ($client_query->num_rows() > 0) {
                //Client Exists marks as recognised

                foreach ($client_query->result() as $value) {
                    $client_id = $value->id;
                    $phone_no = $value->phone_no;

                    $this->db->trans_start();
                    $data_update = array(
                        'recognised' => 'Recognised'
                    );
                    $this->db->where('id', $response_id);
                    $this->db->update('responses', $data_update);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        //Get the notification flow messages for comparison 
                        // echo 'Client Respone found => ' . $msg . ' Phone No ' . $source . ' Client ID => ' . $client_id . '<br>';



                        $get_notification_flow = $this->db->query("SELECT * FROM tbl_notification_flow WHERE VALUE LIKE '%$msg%' LIMIT 1 ");
                        if ($get_notification_flow->num_rows() > 0) {
                            foreach ($get_notification_flow->result() as $notification_value) {
                                $range = $notification_value->value;
                                echo 'Dictionary ' . $range . ' and our client repsonse is => ' . $msg . '<br>';
                                $exploded_range = explode(":", $range);
                                $positive = $exploded_range[0];
                                $negative = $exploded_range[1];


                                if ($positive == $msg) {
                                    echo 'Positive Response and Msg =>' . $msg . '<br>';
                                    $this->db->trans_start();
                                    $data_insert = array(
                                        'msg' => $msg,
                                        'created_at' => $created_at,
                                        'updated_at' => $updated_at,
                                        'status' => 'Active',
                                        'source' => $source,
                                        'destination' => $destination,
                                        'client_id' => $client_id,
                                        'created_by' => $created_by,
                                        'response_id' => $response_id
                                    );
                                    $this->db->insert('ok', $data_insert);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        $response_type .= 'Positive';

                                        $this->db->trans_start();
                                        $data_insert = array(
                                            'msg' => $msg,
                                            'created_at' => $created_at,
                                            'updated_at' => $updated_at,
                                            'status' => 'Active',
                                            'source' => $source,
                                            'destination' => $destination,
                                            'client_id' => $client_id,
                                            'created_by' => $created_by,
                                            'response_id' => $response_id,
                                            'response_type' => 'Positive'
                                        );
                                        $this->db->insert('sms_checkin', $data_insert);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    }
                                } else if ($negative == $msg) {
                                    echo 'Negative Response and Msg => ' . $msg . '<br>';
                                    $this->db->trans_start();
                                    $data_insert = array(
                                        'msg' => $msg,
                                        'created_at' => $created_at,
                                        'updated_at' => $updated_at,
                                        'status' => 'Active',
                                        'source' => $source,
                                        'destination' => $destination,
                                        'client_id' => $client_id,
                                        'created_by' => $created_by,
                                        'response_id' => $response_id
                                    );
                                    $this->db->insert('not_ok', $data_insert);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        $response_type .= 'Negative';

                                        $this->db->trans_start();
                                        $data_insert = array(
                                            'msg' => $msg,
                                            'created_at' => $created_at,
                                            'updated_at' => $updated_at,
                                            'status' => 'Active',
                                            'source' => $source,
                                            'destination' => $destination,
                                            'client_id' => $client_id,
                                            'created_by' => $created_by,
                                            'response_id' => $response_id,
                                            'response_type' => 'Negative'
                                        );
                                        $this->db->insert('sms_checkin', $data_insert);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            $type = 'Negative';
                                            $this->not_ok_folllow_up($client_id, $msg, $type, $response_id);
                                        }
                                    }
                                } else {
                                    
                                }
                            }
                        } else {


                            echo 'Unrecognised Response and Msg => ' . $msg . '<br>';
                            $this->db->trans_start();
                            $data_insert = array(
                                'msg' => $msg,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at,
                                'status' => 'Active',
                                'source' => $source,
                                'destination' => $destination,
                                'client_id' => $client_id,
                                'created_by' => $created_by,
                                'response_id' => $response_id
                            );
                            $this->db->insert('unrecognised', $data_insert);
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                $response_type .= 'Other';



                                $this->db->trans_start();
                                $data_insert = array(
                                    'msg' => $msg,
                                    'created_at' => $created_at,
                                    'updated_at' => $updated_at,
                                    'status' => 'Active',
                                    'source' => $source,
                                    'destination' => $destination,
                                    'client_id' => $client_id,
                                    'created_by' => $created_by,
                                    'response_id' => $response_id,
                                    'response_type' => 'Other'
                                );
                                $this->db->insert('sms_checkin', $data_insert);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    
                                }
                            }
                            $type = 'Other';
                            $this->not_ok_folllow_up($client_id, $msg, $type, $response_id);
                        }
                    }
                }
            } else {
                //Client Does not exist, mark as un recognised




                $this->db->trans_start();
                $data_update = array(
                    'recognised' => 'Recognised'
                );
                $this->db->where('id', $response_id);
                $this->db->update('responses', $data_update);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }

        $this->procees_wellenss();
    }

    function not_ok_folllow_up($client_id, $msg, $type, $response_id) {

        //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
        $this->config->load('config', TRUE);
        // Retrieve a config item named site_name contained within the blog_settings array
        $source = $this->config->item('shortcode', 'config');

        echo 'Our values => ' . $client_id . ' Msg => ' . $msg . ' Type => ' . $type . ' Response ID => ' . $response_id . '<bt> ';

        $get_client_details = $this->db->query("Select * from tbl_client where id='$client_id'")->result();
        foreach ($get_client_details as $client_value) {
            $clinic_number = $client_value->clinic_number;
            $mfl_code = $client_value->mfl_code;
            $fname = $client_value->f_name;
            $get_facility_user = $this->db->query("Select * from tbl_users where facility_id='$mfl_code' and  rcv_app_list='Yes'")->result();
            foreach ($get_facility_user as $user_value) {
                $phone_no = $user_value->phone_no;
                $user_name = $user_value->f_name;

                $outgoing_msg = '';
                $our_msg = '' . $clinic_number . '*' . $fname . '*' . $phone_no . '*' . $msg . '*' . $response_id . ' ';

                if ($type == 'Other') {
                    $outgoing_msg = 'UNR*' . base64_encode($our_msg);
                } else if ($type == 'Negative') {
                    $outgoing_msg = 'NEG*' . base64_encode($our_msg);
                }



                //echo 'Source => ' . $source . '<br> Phone No =: ' . $phone_no . '<br> Msg => ' . $outgoing_msg . '<br>';


                $created_at = date('Y-m-d H:i:s');




                $this->db->trans_start();
                $data_insert = array(
                    'destination' => $phone_no,
                    'source' => $source,
                    'msg' => $outgoing_msg,
                    'status' => 'Sent',
                    'created_at' => $created_at,
                    'message_type_id' => '5',
                    'recepient_type' => 'User'
                );
                $this->db->insert('usr_outgoing', $data_insert);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    echo 'Error => <br> ';
                } else {
                    
                }
            }
        }
    }

    function receiver() {






        $receiver_id = $this->uri->segment(3, 0);



        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
        $this->config->load('config', TRUE);
        // Retrieve a config item named site_name contained within the blog_settings array
        $config = $this->config->item('site_name', 'config');



        $get_response = $this->db->query("
        SELECT
	* 
FROM
	tbl_incoming 
WHERE id ='$receiver_id' 
	
        ")->result();



        foreach ($get_response as $value) {
            $destination = $value->destination;
            $source = $value->source;
            $responded_msg = $value->msg;
            $created_at = $value->created_at;
            $updated_at = $value->updated_at;
            $incoming_id = $value->id;


            $check_response = $this->db->get_where('responses', array('incoming_id' => $incoming_id))->num_rows();
            if ($check_response > 0) {
                echo 'Response Found';
                //If responses exists from the incoming table , ignore inserting the message







                $this->db->trans_start();
                $update_incoming = array(
                    'processed' => 'Processed',
                    'updated_by' => '1'
                );
                $this->db->where('id', $incoming_id);
                $this->db->update('incoming', $update_incoming);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    echo "Response Found .......Transaction was NOT  updated....<br> ";
                } else {
                    echo "Response Found .......Transaction was updated....<br> ";
                }
            } else {
                echo 'Insert Response Not Found...';
                $this->db->trans_start();
                //Insert the  message in the response 
                $response_array = array(
                    'source' => $source,
                    'destination' => $destination,
                    'msg' => $responded_msg,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'incoming_id' => $incoming_id,
                    'recognised' => 'UnRecognised',
                    'created_by' => '1'
                );
                $this->db->insert('responses', $response_array);
                $response_id = $this->db->insert_id();
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {





                    if (strpos($responded_msg, 'BRD') !== false) {

                        $this->process_broadcast($response_id);
                    }
                    if (strpos($responded_msg, 'MSD') !== false) {

                        $this->process_missed_actions($response_id);
                    }

                    if (strpos($responded_msg, 'DF') !== false) {

                        $this->process_defaulted_actions($response_id);
                    }

                    if (strpos($responded_msg, 'LTFU') !== false) {

                        $this->process_LTFU_actions($response_id);
                    }
                    if (strpos($responded_msg, 'Reg') !== false) {
                        echo 'PROCESS REGISTRATION ....';
                        $this->process_register($response_id);
                    }
                    if (strpos($responded_msg, 'APP') !== false) {
                        log_message('info', 'Porcess Appointment Called....');
                        $this->process_appointment($response_id);
                    }
                    if (strpos($responded_msg, 'CON') !== false) {

                        $this->process_consent($response_id);
                    }
                    if (strpos($responded_msg, 'STOP') !== false) {

                        $this->process_stop($response_id);
                    }

                    if (strpos($responded_msg, 'IL_ADT') !== false) {

                        $this->process_il_registration($response_id);
                    }
                    if (strpos($responded_msg, 'IL_SIU') !== false) {

                        $this->process_il_appointment($response_id);
                    }
                    if (strpos($responded_msg, 'IL_ORU') !== false) {

                        $this->process_il_ORU($response_id);
                    }
                    if (strpos($responded_msg, 'ASSTRC') !== false) {

                        $this->proces_assng_trcrs($response_id);
                    }
                    if (strpos($responded_msg, 'TRANSITCLIENT') !== false) {

                        $this->process_transit_appointment($response_id);
                    }
                    if (strpos($responded_msg, 'MOVECLINIC') !== false) {

                        $this->process_move_clinic($response_id);
                    }

                    $this->db->trans_start();
                    $update_incoming = array(
                        'processed' => 'Processed',
                        'updated_by' => '1'
                    );
                    $this->db->where('id', $incoming_id);
                    $this->db->update('incoming', $update_incoming);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }
            }
        }
//        $this->map_responsed();
//        $this->map_client_responses();
//        $this->clean_DOB();
    }

    function process_il_registration($response_id) {
        $query = $this->db->query("Select * from tbl_responses where msg like '%IL_ADT%'  and processed='No' and id='$response_id' ")->result();

        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;
            $response_id = $value->id;


            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;





                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];




                $descrypted_msg = base64_decode($message);
//            echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                $new_msg = $identifier . "*" . $descrypted_msg;
//            echo 'New Message => ' . $new_msg;


                $msg = $new_msg;


                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', TRUE);
                // Retrieve a config item named site_name contained within the blog_settings array
                $source = $this->config->item('shortcode', 'config');
                $mobile = substr($user_source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }
                echo 'Message => ' . $msg . '<br>';
                // // // echo 'Response id => ' . $response_id . ' and Phone Noe : ' . $user_source . '.</br>';

                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility'");
                $user_exists = $get_facility->num_rows();
                if ($user_exists >= 1) {

                    //User exists
                    $get_user_details = $get_facility->result();

                    foreach ($get_user_details as $value) {

                        $facility_id = $value->facility_id;
                        $partner_id = $value->partner_id;
                        $user_id = $value->id;
                        // // // echo 'Incoming  Msg => ' . $msg . '</br>';
                        $exploded_msg = explode("*", $msg);
                        $count_msg = count($exploded_msg);
                        //echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 18

                        if ($count_msg == 20) {
                            //Success Go Ahead 
                            echo 'Success , new application kindly go ahead....<br>';

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
                            $SENDING_APPLICATION = @$exploded_msg[18];
                            $DEATH_INDICATOR = @$exploded_msg[19];

                            $client_id = '';
                            $enrollment_date2 = $enrollment_date;


                            $trans_type_dict = "1:2:3"; //1= > NEW 2 => UPDATE 3=> TRANSFER

                            $exploded_trans_type_dict = explode(":", $trans_type_dict);

                            $new_trans = $exploded_trans_type_dict[0];
                            $update_trans = $exploded_trans_type_dict[1];
                            $transfer_trans = $exploded_trans_type_dict[2];



                            $check_gender = $this->db->get_where('gender', array('id' => $gender))->num_rows();
                            $check_marital_status = $this->db->get_where('marital_status', array('id' => $marital))->num_rows();
                            $check_condition = $this->db->get_where('condition', array('id' => $condition))->num_rows();
                            // $check_grouping = $this->db->get_where('groups', array('id' => $grouping))->num_rows();
                            $check_language = $this->db->get_where('language', array('id' => $language))->num_rows();
                            echo 'Checker =>' . $check_gender . '' . $check_marital_status . '' . $check_condition . '</br>';
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


                            if ($check_gender > 0 and $check_marital_status > 0 and $check_condition >= 0 and $check_language > 0) {
                                echo 'SMS Enable => ' . $sms_enable . '<br>';
                                if ($sms_enable == $yes) {
                                    $sms_lrt = "Yes";
                                } elseif ($sms_enable == $no) {
                                    echo 'SMS Enable => ' . $sms_enable . '</br>';
                                    $sms_lrt = "No";
                                } else {
                                    $outgoing_msg .= " Invalid selection for SMS Alert please try again with 1= > YES 2 => NO   ";
                                }

                                if ($DEATH_INDICATOR == 'Y') {
                                    //Client is Deceased
                                    $client_stts = "Dead";
                                } else if ($DEATH_INDICATOR == 'N') {
                                    //Client is Active
                                    $client_stts = "Active";
                                } else {
                                    //Client is Active
                                    $client_stts = "Active";
                                }




                                if (!empty($sms_lrt) and ! empty($client_stts)) {

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






                                    if ($condition1) {



                                        /* Transaction Types .....
                                         * 
                                         * Transaction #1 =>  Add new client
                                         * Transaction #2 => Update Client
                                         * Transaction #3 => Transfer Client 
                                         */










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





                                        $category = "";
                                        if ($diff >= 3650 and $diff <= 6935) {
                                            //Adolescent
                                            $category .= 2;
                                        } else if ($diff >= 7300) {
                                            //Adult
                                            $category .= 1;
                                        } else {
                                            //Paeds
                                            $category .= 3;
                                        }

                                        echo "Transaction Type => " . $transaction_type . 'and Transaction Dictionary => ' . $new_trans . '<br>';

                                        if ($transaction_type == $new_trans) {
                                            //REGISTER NEW CLIENT GOES IN HERE ...
                                            echo 'Insert Transaction was found ....<br>';









                                            $clinic_number = $upn;

                                            $check_client_existence = $this->db->get_where('client', array('clinic_number' => $clinic_number))->num_rows();

                                            if ($check_client_existence > 0) {

                                                $created_at = date('Y-m-d H:i:s');

                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');



                                                $user_destination = $phone_no;
                                                $this->db->trans_start();
                                                $message = "Client No : $upn already exists in the  system and cannot be registered again, you can either Update client's records or transfer in the  client.  ";
                                                $data_outgoing = array(
                                                    'destination' => $user_source,
                                                    'source' => $source,
                                                    'msg' => $message,
                                                    'status' => 'Sent',
                                                    'message_type_id' => '5',
                                                    'responded' => 'No',
                                                    'clnt_usr_id' => $user_id,
                                                    'recepient_type' => 'User',
                                                    'created_at' => $created_at,
                                                    'created_by' => $user_id
                                                );
                                                $this->db->insert('usr_outgoing', $data_outgoing);







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
                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');
                                                        $destination = $user_source;
                                                        $msg = $message;
                                                        $usr_otgoing_id = "User";
                                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
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
                                                } else {
                                                    //Paeds
                                                    $category .= 3;
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
                                                    'entry_point' => $SENDING_APPLICATION,
                                                    'created_by' => $user_id,
                                                    'client_type' => 'New',
                                                    'txt_time' => $messaging_time,
                                                    'motivational_enable' => $motivation_enable,
                                                    'wellness_enable' => $motivation_enable
                                                );
                                                $this->db->insert('client', $data_insert);
                                                $client_id .= $this->db->insert_id();
                                                echo $client_id;
                                                log_message("INFO", $client_id);
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {



                                                    $this->welcome_msg($client_id);


                                                    $this->db->trans_start();
                                                    $response_update = array(
                                                        'processed' => 'Yes'
                                                    );
                                                    $this->db->where('id', $response_id);
                                                    $this->db->update('responses', $response_update);

                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {


                                                        $this->db->trans_start();
                                                        $message = "Client ID : $upn was succesfully added in the  system ";
                                                        $data_outgoing = array(
                                                            'destination' => $user_source,
                                                            'source' => $source,
                                                            'msg' => $message,
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'clnt_usr_id' => $client_id,
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at,
                                                            'created_by' => $user_id
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);







                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {
                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');
                                                            $destination = $user_source;
                                                            $msg = $message;
                                                            $usr_otgoing_id = "User";

                                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                        }
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
                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');


                                                $user_destination = $phone_no;
                                                $this->db->trans_start();
                                                $message = "Update Client Error = > Client No : $upn does not exist in the  system ";
                                                $data_outgoing = array(
                                                    'destination' => $user_source,
                                                    'source' => $source,
                                                    'msg' => $message,
                                                    'status' => 'Sent',
                                                    'message_type_id' => '5',
                                                    'responded' => 'No',
                                                    'clnt_usr_id' => $user_id,
                                                    'recepient_type' => 'User',
                                                    'created_at' => $created_at,
                                                    'created_by' => $user_id
                                                );
                                                $this->db->insert('usr_outgoing', $data_outgoing);





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


                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');
                                                        $destination = $user_source;
                                                        $msg = $message;
                                                        $usr_otgoing_id = "User";

                                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
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
                                                        'motivational_enable' => $motivation_enable,
                                                        'wellness_enable' => $motivation_enable
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
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'clnt_usr_id' => $client_id,
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at,
                                                            'created_by' => $user_id
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);






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

                                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                $this->config->load('config', TRUE);
                                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                                $source = $this->config->item('shortcode', 'config');
                                                                $destination = $user_source;
                                                                $msg = $message;
                                                                $usr_otgoing_id = "User";

                                                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
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
                                                        'motivational_enable' => $motivation_enable,
                                                        'wellness_enable' => $motivation_enable
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
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'clnt_usr_id' => $client_id,
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at,
                                                            'created_by' => $user_id
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);




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


                                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                $this->config->load('config', TRUE);
                                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                                $source = $this->config->item('shortcode', 'config');
                                                                $destination = $user_source;
                                                                $msg = $message;
                                                                $usr_otgoing_id = "User";

                                                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
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
                                                    'motivational_enable' => $motivation_enable,
                                                    'wellness_enable' => $motivation_enable
                                                );

                                                $this->db->insert('client', $data_insert);
                                                $client_id .= $this->db->insert_id();
                                                echo $client_id;
                                                log_message("INFO", $client_id);

                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {



                                                    $this->welcome_msg($client_id);


                                                    $this->db->trans_start();
                                                    $response_update = array(
                                                        'processed' => 'Yes'
                                                    );
                                                    $this->db->where('id', $response_id);
                                                    $this->db->update('responses', $response_update);

                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {






                                                        $this->db->trans_start();
                                                        $message = "Client ID : $upn was succesfully transfered to your facility  in the system ";
                                                        $data_outgoing = array(
                                                            'destination' => $user_source,
                                                            'source' => $source,
                                                            'msg' => $message,
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'clnt_usr_id' => $client_id,
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at,
                                                            'created_by' => $user_id
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);



                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {


                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');
                                                            $destination = $user_source;
                                                            $msg = $message;
                                                            $usr_otgoing_id = "User";

                                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {


                                        $this->db->trans_start();




                                        //Conditions were not met , queue out going message 
                                        $created_at = date('Y-m-d H:i:s');
                                        // // // echo 'Out going message => ' . $outgoing_msg . '</br> ';
                                        $message = "Error encountered = > " . $outgoing_msg;
                                        $data_outgoing = array(
                                            'destination' => $source,
                                            'source' => $user_destination,
                                            'msg' => $message,
                                            'status' => 'Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'clnt_usr_id' => $user_id,
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at,
                                            'created_by' => $user_id
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);






                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {





                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');
                                            $destination = $user_source;
                                            $msg = $message;
                                            $usr_otgoing_id = "User";

                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);




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


                                $message = "Error encountered = > " . $outgoing_msg;

                                //Conditions were not met , queue out going message 
                                $created_at = date('Y-m-d H:i:s');
                                $data_outgoing = array(
                                    'destination' => $user_source,
                                    'source' => $user_destination,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'clnt_usr_id' => $user_id,
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at,
                                    'created_by' => $user_id
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);





                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {



                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');
                                    $destination = $user_source;
                                    $msg = $message;
                                    $usr_otgoing_id = "User";

                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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

                            $message = "Error encountered = > You need to update your application to the  latest version, kindly contact support for guidance. "
                                    . "T4A :Your Friendly Reminder .";


                            //Conditions were not met , queue out going message 
                            $created_at = date('Y-m-d H:i:s');
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => $message,
                                'status' => 'Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'clnt_usr_id' => $user_id,
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);






                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {





                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $user_source;
                                $msg = $message;
                                $usr_otgoing_id = "User";

                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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

                    echo 'User Not Found...';

                    $created_at = date('Y-m-d H:i:s');


                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');



                    $destination = $mobile;
                    $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                    $this->db->trans_start();

                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'User',
                        'created_at' => $created_at,
                        'created_by' => $user_id,
                        'clnt_usr_id' => '587'
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);





                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {


                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        $this->config->load('config', TRUE);
                        // Retrieve a config item named site_name contained within the blog_settings array
                        $source = $this->config->item('shortcode', 'config');
                        $destination = $user_source;
                        $msg = $message;
                        $usr_otgoing_id = "User";

                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);






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
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;



                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $response_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_il_appointment($response_id) {
        $today = date('Y-m-d H:i:s');
        // sleep for 10 seconds
        sleep(10);
        $query = $this->db->query("Select * from tbl_responses where msg like '%IL_SIU%' and processed='No' and id='$response_id' ")->result();
        //print_r($query);
        //Get All New Repsonse
        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;
            $process_id = $value->id;



            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;




                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];




                $descrypted_msg = base64_decode($message);
                echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                $new_msg = $identifier . "*" . $descrypted_msg;
                echo 'New Message => ' . $new_msg;



                $msg = $new_msg;




                $mobile = substr($user_source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }
                echo 'New From : ' . $user_source . '</br>';
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



                        //SIU  MESSAGE => ILLAPP * CCC NUMBER * APPOINTMENT DATE * APPOINYMENT TYPE * APPOINTMENT LOCATION *ACTION CODE *  APPOINTMENT NOTE *APPOINTMENT HONORED * APPOINTMENT REASON * SENDING_APPLICATION

                        $exploded_msg = explode("*", $msg);
                        $app = @$exploded_msg[0];
                        $upn = @$exploded_msg[1];
                        $app_date = @$exploded_msg[2];
                        $appointment_type = @$exploded_msg[3];
                        $appointment_location = @$exploded_msg[4];
                        $action_code = @$exploded_msg[5];
                        $appointment_note = @$exploded_msg[6];
                        $appointment_honored = @$exploded_msg[7];
                        $appointment_reason = @$exploded_msg[8];
                        $SENDING_APPLICATION = @$exploded_msg[8];

                        $appointment_type_id = '';

                        $get_appointment_types = $this->db->query("Select * from tbl_appointment_types where name ='$appointment_type'");
                        if ($get_appointment_types->num_rows() > 0) {
                            //Get the results
                            foreach ($get_appointment_types->results() as $appointment_type_value) {
                                $appointment_type_id .= $appointment_type_value->id;
                            }
                        } else {
                            //Insert the  value and get the  last insert id
                            $this->db->trans_start();
                            $data_insert = array(
                                'name ' => $appointment_type,
                                'created_at' => $today
                            );

                            $this->db->insert('appointment_types', $data_insert);
                            $appointment_type_id .= $this->db->insert_id();
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                $appointment_type_id .= $this->db->insert_id();
                            }
                        }



                        $appointment_kept_dict = "Y:N";

                        #Explode Appointment Type Dictionary
                        $exploded_app_type = explode(":", $appointment_type_dict);



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
                        echo "New " . $app_date . '<br>';
                        if ($app_date == "1970-01-01") {
                            //Invalid Appointment Date 
                            $created_at = date('Y-m-d H:i:s');
                            $message = "Invalid Appointment Date , DD/MM/YYYY is the  appropriate date format .  ";
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => $message,
                                'status' => 'Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);


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
                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');
                                    $destination = $user_source;
                                    $msg = $message;
                                    $usr_otgoing_id = "User";

                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                }
                            }
                        } else {
                            //Appointment date is correct proceed to appointment processing
                            //Get Client Details from the  Client Number 






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

                                    $get_client = $this->db->query("Select * from tbl_appointment where client_id='$client_id' and active_app='1' and app_type_1='$appointment_type_id' ");
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
                                            $app_type_1 = $appointment_value->app_type_1;


                                            $created_at = $appointment_value->created_at;
                                            $updated_at = $appointment_value->updated_at;
                                            $app_status = $appointment_value->app_status;
                                            $app_msg = $appointment_value->app_msg;



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
                                                    'sent_status' => 'Sent',
                                                    'client_id' => $client_id,
                                                    'created_at' => $today,
                                                    'created_by' => $user_id,
                                                    'app_type_1' => $appointment_type,
                                                    'entry_point' => $SENDING_APPLICATION
                                                );

                                                $this->db->insert('appointment', $appointment_insert);
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {


                                                    $this->db->trans_start();




                                                    //Conditions were not met , queue out going message 
                                                    $created_at = date('Y-m-d H:i:s');
                                                    $message = "Client $clinic_number appointment was succesfully updated in the  system  ";
                                                    $data_outgoing = array(
                                                        'destination' => $user_source,
                                                        'source' => $user_destination,
                                                        'msg' => $message,
                                                        'status' => 'Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $user_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at,
                                                        'created_by' => $user_id
                                                    );
                                                    $this->db->insert('usr_outgoing', $data_outgoing);


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


                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');
                                                            $destination = $user_source;
                                                            $msg = $message;
                                                            $usr_otgoing_id = "User";

                                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
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
                                            'sent_status' => 'Sent',
                                            'client_id' => $client_id,
                                            'created_at' => $today,
                                            'active_app' => '1',
                                            'created_by' => $user_id,
                                            'app_type_1' => $appointment_type,
                                            'entry_point' => $SENDING_APPLICATION
                                        );

                                        $this->db->insert('appointment', $appointment_insert);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {


                                            // // // echo  'Appointment Booked Successfully ...';


                                            $this->db->trans_start();

                                            $created_at = date('Y-m-d H:i:s');
                                            $message = "Client $clinic_number appointment was succesfully updated in the  system  ";
                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $user_destination,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);


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


                                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                    $this->config->load('config', TRUE);
                                                    // Retrieve a config item named site_name contained within the blog_settings array
                                                    $source = $this->config->item('shortcode', 'config');
                                                    $destination = $user_source;
                                                    $msg = $message;
                                                    $usr_otgoing_id = "User";

                                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                // // // echo  'Cllinic No not found...';
                                // // // echo  'Start';

                                $created_at = date('Y-m-d H:i:s');
                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');


                                $destination = '0' . $mobile;
                                $this->db->trans_start();
                                $message = " Appointment was not scheduled in the  system , Clinic No $upn was not found in the system ...";
                                $data_outgoing = array(
                                    'destination' => $destination,
                                    'source' => $source,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);


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


                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');
                                        $destination = $user_source;
                                        $msg = $message;
                                        $usr_otgoing_id = "User";

                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                    }
                                }
                            }
                        }
                    }
                } else {


                    // // // echo  'Not Authorised in the  system ...' . $user_source . '</br>';
                    // // // echo  'Start';

                    $created_at = date('Y-m-d H:i:s');
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');


                    $destination = '0' . $mobile;
                    $this->db->trans_start();
                    $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'User',
                        'created_at' => $created_at,
                        'clnt_usr_id' => '587'
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);
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

                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                            $this->config->load('config', TRUE);
                            // Retrieve a config item named site_name contained within the blog_settings array
                            $source = $this->config->item('shortcode', 'config');
                            $destination = $user_source;
                            $msg = $message;
                            $usr_otgoing_id = "User";

                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                        }
                    }


                    // // // echo  'End';
                }
            } else {
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;



                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $process_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_il_ORU($response_id) {
        
    }

    function map_responsed() {

        $responses = $this->db->query("SELECT id,source FROM tbl_responses WHERE user_type IS NULL");
        $check_existnse = $responses->num_rows();
        if ($check_existnse > 0) {
            $get_responses = $responses->result();
            foreach ($get_responses as $value) {
                $source = $value->source;
                $response_id = $value->id;

                $mobile = substr($source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $source = "0" . $mobile;
                }
                $client_query = $this->db->query("Select id from tbl_client where phone_no='$source'");
                $check_client_existense = $client_query->num_rows();
                if ($check_client_existense > 0) {
                    // // // echo 'Phone No : ' . $source . '</br>';

                    $this->db->trans_start();
                    $update_response = array(
                        'user_type' => 'Client',
                        'updated_by' => '1'
                    );
                    $this->db->where('id', $response_id);
                    $this->db->update('responses', $update_response);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }
            }
        }
    }

    function transfer_client() {

        $created_at = date("Y-m-d H:i:s");



        $query = $this->db->query("Select * from tbl_responses where msg like '%TRANS%' and processed='No' ")->result();

        foreach ($query as $value) {
            $source = $value->source;
            $destination = $value->destination;

            $response_id = $value->id;
            $mobile = substr($source, -9);
            $len = strlen($mobile);

            $encrypted_msg = $value->msg;



            $explode_msg = explode("*", $encrypted_msg);
            $identifier = $explode_msg[0];
            $message = $explode_msg[1];
            $msg = "TRANS*" . $this->decrypt($message);

            echo 'Msg => ' . $msg . '<br>';
            //  exit();




            if ($len = 9) {

                $source = "0" . $mobile;
            }
            echo 'New From : ' . $source;


            $get_facility = $this->db->query("Select * from tbl_users where phone_no='$source' and access_level='Facility'");

            $check_user_exist = $get_facility->num_rows();
            if ($check_user_exist > 0) {
                /* Process the  sent message 
                  Check if the specified number exists in the  system,
                  If it's not found, then send a failed message back to the  user
                  Else process and move the  client to our facility.
                 */

                foreach ($get_facility->result() as $value) {
                    # code...
                    $new_mfl_code = $value->facility_id;


                    $exploded_msg = explode("*", $msg);
                    $message_code = $exploded_msg[0];
                    $ccc_number = $exploded_msg[1];
                    $new_mfl_code = $value->facility_id;
                    echo "Message Code => " . $message_code . '     CCC Number ' . $ccc_number . '<br>';

                    //Check if the  client exists in the  system
                    $geT_client = $this->db->query("Select * from tbl_client where clinic_number='$ccc_number'");
                    $check_client_existence = $geT_client->num_rows();
                    if ($check_client_existence > 0) {
                        echo 'Client Found...';
                        //Get client details
                        foreach ($geT_client->result() as $value) {
                            # code...
                            $old_mfl_code = $value->mfl_code;
                            $client_id = $value->id;

                            $this->db->trans_start();
                            $data_update = array(
                                'prev_clinic' => $old_mfl_code,
                                'mfl_code' => $new_mfl_code,
                                'client_type' => 'Transfer',
                                'f_name' => 'Transfered Client');

                            $this->db->where('id', $client_id);
                            $this->db->update('client', $data_update);
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {



                                $this->db->trans_start();
                                $data_insert = array(
                                    'destination' => $source,
                                    'source' => $destination,
                                    'msg' => "Client CCC No : $ccc_number was successfully transfered in the  system.",
                                    'status' => 'Sent',
                                    'created_at' => $created_at,
                                    'message_type_id' => '5',
                                    'recepient_type' => 'User'
                                );
                                $this->db->insert('usr_outgoing', $data_insert);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    echo 'Error => <br> ';
                                } else {

                                    $phone_no = $source;

                                    //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');
                                    $destination = $destination;
                                    $msg = "Client CCC No : $ccc_number was successfully transfered in the  system.";
                                    echo 'Message => ' . $msg . '<br> and response ID => ' . $response_id . '<br>';
                                    echo $phone_no . '<br>';

                                    $usr_otgoing_id = "User";

                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                    if ($send_text) {
                                        echo 'TRUE';
                                    } else {
                                        echo 'FALSE';
                                    }





                                    echo 'Transaction Insert Success <br>';

                                    $this->db->trans_start();
                                    $update_response = array(
                                        'user_type' => 'User',
                                        'updated_by' => '1',
                                        'processed' => 'Yes'
                                    );
                                    $this->db->where('id', $response_id);
                                    $this->db->update('responses', $update_response);

                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        
                                    }
                                }
                            }
                        }
                    } else {



                        $this->db->trans_start();
                        $data_insert = array(
                            'destination' => $source,
                            'source' => $destination,
                            'msg' => "Client CCC No : $ccc_number was not found in the  system.",
                            'status' => 'Sent',
                            'created_at' => $created_at,
                            'message_type_id' => '5',
                            'recepient_type' => 'User'
                        );
                        $this->db->insert('usr_outgoing', $data_insert);
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            echo 'Error => <br> ';
                        } else {
                            echo 'Transaction Insert Success <br>';



                            //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                            $this->config->load('config', TRUE);
                            // Retrieve a config item named site_name contained within the blog_settings array
                            $source = $this->config->item('shortcode', 'config');
                            $destination = $destination;
                            $msg = "Client CCC No : $ccc_number was not found in the  system.";
                            echo 'Message => ' . $msg . '<br> and response ID => ' . $response_id . '<br>';
                            $usr_otgoing_id = "User";

                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                            if ($send_text) {
                                echo 'TRUE';
                            } else {
                                echo 'FALSE';
                            }




                            $this->db->trans_start();
                            $update_response = array(
                                'user_type' => 'User',
                                'updated_by' => '1',
                                'processed' => 'Yes'
                            );
                            $this->db->where('id', $response_id);
                            $this->db->update('responses', $update_response);

                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {
                                
                            }
                        }
                    }
                }
            } else {
                $this->db->trans_start();
                $data_insert = array(
                    'destination' => $source,
                    'source' => $destination,
                    'msg' => 'Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you ',
                    'status' => 'Sent',
                    'created_at' => $created_at,
                    'message_type_id' => '5',
                    'recepient_type' => 'User',
                    'clnt_usr_id' => '587'
                );
                $this->db->insert('usr_outgoing', $data_insert);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    echo 'Error => <br> ';
                } else {
                    echo 'Transaction Insert Success <br>';



                    //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');
                    $destination = $destination;
                    $msg = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you ";
                    echo 'Message => ' . $msg . '<br> and response ID => ' . $response_id . '<br>';
                    $usr_otgoing_id = "User";

                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                    if ($send_text) {
                        echo 'TRUE';
                    } else {
                        echo 'FALSE';
                    }




                    $this->db->trans_start();
                    $update_response = array(
                        'user_type' => 'User',
                        'updated_by' => '1',
                        'processed' => 'Yes'
                    );
                    $this->db->where('id', $response_id);
                    $this->db->update('responses', $update_response);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }
            }
        }
    }

    function process_consent($response_id) {

        $created_at = date("Y-m-d H:i:s");



        $query = $this->db->query(" 
        SELECT
        * 
    FROM
        tbl_responses 
    WHERE
        msg LIKE '%CON%' and processed='No' and id='$response_id'  
         ")->result();

        foreach ($query as $value) {
            $source = $value->source;
            $user_phone_no = $source;
            $destination = $value->destination;
            $short_code = $destination;
            $response_id = $value->id;
            $mobile = substr($source, -9);
            $len = strlen($mobile);

            $encrypted_msg = $value->msg;




            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;





                echo 'Encrypted Message => ' . $encrypted_msg . '<br>';

                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $msg = "CON*" . $this->decrypt($message);

                echo 'Decrpyted Msg => ' . $msg . '<br>';





                if ($len = 9) {

                    $source = "0" . $mobile;
                }
                echo 'New From : ' . $source;


                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$source' and access_level='Facility'");

                $check_user_exist = $get_facility->num_rows();
                if ($check_user_exist > 0) {
                    /* Process the  sent message 
                      Check if the specified number exists in the  system,
                      If it's not found, then send a failed message back to the  user
                      Else process and move the  client to our facility.
                     */

                    foreach ($get_facility->result() as $value) {
                        # code...
                        $new_mfl_code = $value->facility_id;


                        $exploded_msg = explode("*", $msg);
                        $message_code = @$exploded_msg[0];
                        $ccc_number = @$exploded_msg[1];
                        $consent_date = @$exploded_msg[2];
                        $preferred_time = @$exploded_msg[3];
                        $client_phone_no = @$exploded_msg[4];

                        $preferred_time = substr($preferred_time, 0, 2);


                        $count_msg = count($exploded_msg);
                        if ($count_msg == 5) {
                            //Check if the  client exists in the  system
                            $get_time_id = $this->db->query("Select * from tbl_time where name LIKE '%$preferred_time%' LIMIT 1");
                            $check_time_existence = $get_time_id->num_rows();
                            if ($check_time_existence > 0) {

                                foreach ($get_time_id->result() as $value) {
                                    $time_name = $value->name;
                                    $time_id = $value->id;

                                    $date = str_replace('/', '-', $consent_date);
                                    $consent_date = date('Y-m-d', strtotime($date));




                                    //Check if the  client exists in the  system
                                    $geT_client = $this->db->get_where('tbl_client', array('clinic_number' => $ccc_number));
                                    $check_client_existence = $geT_client->num_rows();
                                    if ($check_client_existence > 0) {
                                        echo 'Client Found...';
                                        $check_client_consent = $this->db->get_where('tbl_client', array('clinic_number' => $ccc_number, 'smsenable' => 'Yes'));
                                        if ($check_client_consent->num_rows() > 0) {
                                            //Client Already consented....

                                            $this->db->trans_start();
                                            $data_insert = array(
                                                'destination' => $user_phone_no,
                                                'source' => $short_code,
                                                'msg' => "Consent Not updated , Client CCC No : $ccc_number has already consnented to receive messages.",
                                                'status' => 'Sent',
                                                'created_at' => $created_at,
                                                'message_type_id' => '5',
                                                'recepient_type' => 'User'
                                            );
                                            $this->db->insert('usr_outgoing', $data_insert);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {



                                                //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');
                                                $destination = $user_phone_no;
                                                $outgoing_msg = "Consent Not updated , Client CCC No : $ccc_number consent was already updated in the system.";
                                                $usr_otgoing_id = "User";

                                                $send_text = $this->send_message($source, $destination, $outgoing_msg, $usr_otgoing_id);
                                                if ($send_text) {
                                                    echo 'TRUE';
                                                } else {
                                                    echo 'FALSE';
                                                }




                                                $this->db->trans_start();
                                                $update_response = array(
                                                    'user_type' => 'User',
                                                    'updated_by' => '1',
                                                    'processed' => 'Yes'
                                                );
                                                $this->db->where('id', $response_id);
                                                $this->db->update('responses', $update_response);

                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {
                                                    
                                                }
                                            }
                                        } else {
                                            //Client Has not Consented....
                                            //Get client details
                                            foreach ($geT_client->result() as $value) {
                                                # code...

                                                $client_id = $value->id;

                                                $this->db->trans_start();
                                                $data_update = array(
                                                    'consent_date' => $consent_date,
                                                    'smsenable' => 'Yes',
                                                    'phone_no' => $client_phone_no,
                                                    'txt_time' => $time_id);

                                                $this->db->where('id', $client_id);
                                                $this->db->update('client', $data_update);
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {



                                                    $this->db->trans_start();
                                                    $data_insert = array(
                                                        'destination' => $source,
                                                        'source' => $destination,
                                                        'msg' => "Client CCC No : $ccc_number Consent Date was successfully updated in the  system.",
                                                        'status' => 'Sent',
                                                        'created_at' => $created_at,
                                                        'message_type_id' => '5',
                                                        'recepient_type' => 'User'
                                                    );
                                                    $this->db->insert('usr_outgoing', $data_insert);
                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        echo 'Error => <br> ';
                                                    } else {

                                                        $phone_no = $source;

                                                        //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');
                                                        $destination = $destination;
                                                        $msg = "Client CCC No : $ccc_number Consent Date was successfully updated in the  system.";

                                                        $usr_otgoing_id = "User";

                                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                        if ($send_text) {
                                                            echo 'TRUE';
                                                        } else {
                                                            echo 'FALSE';
                                                        }



                                                        echo 'Transaction Insert Success <br>';

                                                        $this->db->trans_start();
                                                        $update_response = array(
                                                            'user_type' => 'User',
                                                            'updated_by' => '1',
                                                            'processed' => 'Yes'
                                                        );
                                                        $this->db->where('id', $response_id);
                                                        $this->db->update('responses', $update_response);

                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {
                                                            
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {



                                        $this->db->trans_start();
                                        $data_insert = array(
                                            'destination' => $source,
                                            'source' => $destination,
                                            'msg' => "Consent Not updated , Client CCC No : $ccc_number was not found in the  system.",
                                            'status' => 'Sent',
                                            'created_at' => $created_at,
                                            'message_type_id' => '5',
                                            'recepient_type' => 'User'
                                        );
                                        $this->db->insert('usr_outgoing', $data_insert);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            echo 'Error => <br> ';
                                        } else {
                                            echo 'Transaction Insert Success <br>';



                                            //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $short_code = $this->config->item('shortcode', 'config');
                                            $destination = $user_phone_no;
                                            $outgoing_msg = "Consent Not updated , Client CCC No : $ccc_number was not found in the  system.";
                                            $usr_otgoing_id = "User";

                                            $send_text = $this->send_message($short_code, $user_phone_no, $outgoing_msg, $usr_otgoing_id);
                                            if ($send_text) {
                                                echo 'TRUE';
                                            } else {
                                                echo 'FALSE';
                                            }




                                            $this->db->trans_start();
                                            $update_response = array(
                                                'user_type' => 'User',
                                                'updated_by' => '1',
                                                'processed' => 'Yes'
                                            );
                                            $this->db->where('id', $response_id);
                                            $this->db->update('responses', $update_response);

                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        }
                                    }
                                }
                            } else {
                                //Time ID was not found in the system


                                $this->db->trans_start();
                                $data_insert = array(
                                    'destination' => $source,
                                    'source' => $destination,
                                    'msg' => "Consent Not updated , The specified Time  : $preferred_time was not found in the  system.",
                                    'status' => 'Sent',
                                    'created_at' => $created_at,
                                    'message_type_id' => '5',
                                    'recepient_type' => 'User'
                                );
                                $this->db->insert('usr_outgoing', $data_insert);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    echo 'Error => <br> ';
                                } else {
                                    echo 'Transaction Insert Success <br>';



                                    //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $short_code = $this->config->item('shortcode', 'config');
                                    $destination = $phone_no;
                                    $outgoing_msg = "Consent Not updated , The specified Time  : $preferred_time was not found in the  system.";
                                    $usr_otgoing_id = "User";

                                    $send_text = $this->send_message($short_code, $destination, $outgoing_msg, $usr_otgoing_id);
                                    if ($send_text) {
                                        echo 'TRUE';
                                    } else {
                                        echo 'FALSE';
                                    }




                                    $this->db->trans_start();
                                    $update_response = array(
                                        'user_type' => 'User',
                                        'updated_by' => '1',
                                        'processed' => 'Yes'
                                    );
                                    $this->db->where('id', $response_id);
                                    $this->db->update('responses', $update_response);

                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        
                                    }
                                }
                            }
                        } else {



                            $this->db->trans_start();
                            $data_insert = array(
                                'destination' => $source,
                                'source' => $destination,
                                'msg' => 'You need to update your application to the latest version, kindly contact support for guidance. Ushauri : Getting Better one text at a time. ',
                                'status' => 'Sent',
                                'created_at' => $created_at,
                                'message_type_id' => '5',
                                'recepient_type' => 'User',
                                'clnt_usr_id' => '587'
                            );
                            $this->db->insert('usr_outgoing', $data_insert);
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                echo 'Error => <br> ';
                            } else {
                                echo 'Transaction Insert Success <br>';



                                //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $short_code = $this->config->item('shortcode', 'config');
                                $destination = $destination;
                                $msg = " You need to update your application to the latest version, kindly contact support for guidance. Ushauri : Getting Better one text at a time. ";
                                $usr_otgoing_id = "User";

                                $send_text = $this->send_message($short_code, $destination, $msg, $usr_otgoing_id);
                                if ($send_text) {
                                    echo 'TRUE';
                                } else {
                                    echo 'FALSE';
                                }




                                $this->db->trans_start();
                                $update_response = array(
                                    'user_type' => 'User',
                                    'updated_by' => '1',
                                    'processed' => 'Yes'
                                );
                                $this->db->where('id', $response_id);
                                $this->db->update('responses', $update_response);

                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    
                                }
                            }
                        }
                    }
                } else {
                    $this->db->trans_start();
                    $data_insert = array(
                        'destination' => $source,
                        'source' => $destination,
                        'msg' => 'Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you',
                        'status' => 'Sent',
                        'created_at' => $created_at,
                        'message_type_id' => '5',
                        'recepient_type' => 'User',
                        'clnt_usr_id' => '587'
                    );
                    $this->db->insert('usr_outgoing', $data_insert);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        echo 'Error => <br> ';
                    } else {
                        echo 'Transaction Insert Success <br>';



                        //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        $this->config->load('config', TRUE);
                        // Retrieve a config item named site_name contained within the blog_settings array
                        $short_code = $this->config->item('shortcode', 'config');
                        $destination = $destination;
                        $msg = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you ";
                        $usr_otgoing_id = "User";

                        $send_text = $this->send_message($short_code, $destination, $msg, $usr_otgoing_id);
                        if ($send_text) {
                            echo 'TRUE';
                        } else {
                            echo 'FALSE';
                        }




                        $this->db->trans_start();
                        $update_response = array(
                            'user_type' => 'User',
                            'updated_by' => '1',
                            'processed' => 'Yes'
                        );
                        $this->db->where('id', $response_id);
                        $this->db->update('responses', $update_response);

                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            
                        }
                    }
                }
            } else {
                //Old Non Encrypted Message


                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $response_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_broadcast($response_id) {
        //get current date time 
        $today = date("Y-m-d H:i:s");
        $query = $this->db->query("Select * from tbl_responses where msg like '%BRD%' and processed='No' and id='$response_id' ")->result();

        foreach ($query as $value) {



            echo $value->msg . '<br>';
            $encrypted_msg = $value->msg;

            $response_id = $value->id;
            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo "New Encrypted Message" . $count_special;



                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $msg = $this->decrypt($message);





                $source = $value->source;
                $destination = $value->destination;

                echo 'Msg => ' . $msg . '<br>';


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


                if ($user_exists > 0) {
                    echo "Check user status" + $user_exists . " for Source : " . $source . "<br> ";


                    $get_user_details = $get_facility->result();
                    foreach ($get_user_details as $value) {
                        $partner_id = $value->partner_id;
                        $user_id = $value->user_id;
                        $phone_no = $value->phone_no;
                        $mfl_code = $value->mfl_code;
                        $facility_id = $value->facility_id;
                        $county_id = $value->county_id;
                        $sub_county_id = $value->sub_county_id;

//                    echo 'Message => ' . $msg . '</br>';

                        $exploded_msg = explode("*", $msg);



                        $count_msg = count($exploded_msg);
                        echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 18

                        if ($count_msg == 7) {

                            echo 'Condition Met...';




                            $code = $exploded_msg[0];
                            $broadcast_name = $exploded_msg[1];
                            $target_client = $exploded_msg[2];
                            $target_group = $exploded_msg[3];
                            $broadcast_date = $exploded_msg[4];
                            $broadcast_time = $exploded_msg[5];
                            $broadcast_message = $exploded_msg[6];
                            $user_source = $source;
                            $user_destination = $destination;


                            $trgt_clnt_dictionary = "1:2:3";   //1= > YES 2 => NO
                            $trgt_group_dictionary = "1:2";  // 1 => Active 2 => Disabled 3 => Dead
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





                                        echo 'Name => ' . $broadcast_name . '<br> Taeget Client => ' . $target_client . '<br> Target group => ' . $target_group . ''
                                        . '<br> Broadcast date =. ' . $broadcast_date . '<br> Broadcast Time => ' . $broadcast_time . '<br> Message => ' . $broadcast_message . '<br>';



                                        $broadcast_date = str_replace('/', '-', $broadcast_date);
                                        $broadcast_date = date("Y-m-d", strtotime($broadcast_date));
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
                                            'broadcast_date' => $broadcast_date,
                                            'created_by' => $user_id,
                                            'partner_id' => $partner_id
                                        );
                                        $this->db->insert('tbl_broadcast', $data_insert);
                                        $broadcast_id = $this->db->insert_id();


                                        $msg = $broadcast_message;
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

                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');


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
                                            $clients = $this->get_all_active_clients_appointments($target_group, $mfl_code);

                                            foreach ($clients as $value) {
                                                $client_name = $value->f_name . " " . $value->m_name . " " . $value->l_name;
                                                echo $client_name . '<br>';
                                                $phone_no = $value->phone_no;
                                                $alt_phone_no = $value->alt_phone_no;
                                                $client_id = $value->id;
                                                $client_mfl_code = $value->mfl_code;
                                                if (empty($phone_no)) {
                                                    $destination = $alt_phone_no;
                                                } else {
                                                    $destination = $phone_no;
                                                }

                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');

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
                                                    'mfl_code' => $client_mfl_code
                                                );
                                                $this->db->insert('sms_queue', $post_data);
                                            }
                                        }












                                        $this->db->trans_start();



                                        $message = "Broadcast $broadcast_name  Added Successfully!! ";
                                        //Conditions were not met , queue out going message 
                                        $created_at = date('Y-m-d H:i:s');
                                        $data_outgoing = array(
                                            'destination' => $user_source,
                                            'source' => $user_destination,
                                            'msg' => $message,
                                            'status' => 'Not Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'clnt_usr_id' => $user_id,
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);






                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {


                                            //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');
                                            $destination = $destination;
                                            $msg = $message;
                                            echo 'Message => ' . $msg . '<br> and response ID => ' . $response_id . '<br>';
                                            $usr_otgoing_id = "User";

                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                                            echo $response_id;

                                            $this->db->trans_start();
                                            $response_update = array(
                                                'processed' => 'Yes',
                                                'updated_by' => '1'
                                            );
                                            $this->db->where('id', $response_id);
                                            $this->db->update('responses', $response_update);

                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        }
                                    } else {
                                        $outgoing_msg .= " Invalid selection for Broadcast time.  ";

                                        $this->db->trans_start();

                                        $message = "Error encountered = > " . $outgoing_msg;
                                        //Conditions were not met , queue out going message 
                                        $created_at = date('Y-m-d H:i:s');
                                        $data_outgoing = array(
                                            'destination' => $user_source,
                                            'source' => $user_destination,
                                            'msg' => $message,
                                            'status' => 'Not Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'clnt_usr_id' => $user_id,
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at,
                                            'created_by' => '1'
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);


                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {


                                            //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');
                                            $destination = $destination;
                                            $msg = $message;
                                            echo 'Message => ' . $msg . '<br>';
                                            $usr_otgoing_id = "User";

                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                                            echo $response_id;

                                            $this->db->trans_start();
                                            $response_update = array(
                                                'processed' => 'Yes',
                                                'updated_by' => '1'
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
                                    $outgoing_msg .= " Invalid selection for Target Group please try again with 1 => All Clients  2 => All Active Appointments   ";

                                    $this->db->trans_start();

                                    $message = "Error encountered = > " . $outgoing_msg;
                                    //Conditions were not met , queue out going message 
                                    $created_at = date('Y-m-d H:i:s');
                                    $data_outgoing = array(
                                        'destination' => $user_source,
                                        'source' => $user_destination,
                                        'msg' => $message,
                                        'status' => 'Not Sent',
                                        'message_type_id' => '5',
                                        'responded' => 'No',
                                        'clnt_usr_id' => $user_id,
                                        'recepient_type' => 'User',
                                        'created_at' => $created_at,
                                        'created_by' => '1'
                                    );
                                    $this->db->insert('usr_outgoing', $data_outgoing);


                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {


                                        //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');
                                        $destination = $destination;
                                        $msg = $message;
                                        echo 'Message => ' . $msg . '<br>';
                                        $usr_otgoing_id = "User";

                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                                        echo $response_id;

                                        $this->db->trans_start();
                                        $response_update = array(
                                            'processed' => 'Yes',
                                            'updated_by' => '1'
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
                                $outgoing_msg .= " Invalid selection for Target Client  please try again with 1 => All  2 => Adults 3 => Adolescents  ";



                                $this->db->trans_start();

                                $message = "Error encountered = > " . $outgoing_msg;
                                //Conditions were not met , queue out going message 
                                $created_at = date('Y-m-d H:i:s');
                                $data_outgoing = array(
                                    'destination' => $user_source,
                                    'source' => $user_destination,
                                    'msg' => $message,
                                    'status' => 'Not Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'clnt_usr_id' => $user_id,
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at,
                                    'created_by' => '1'
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);


                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {


                                    //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');
                                    $destination = $destination;
                                    $msg = $message;
                                    echo 'Message => ' . $msg . '<br>';
                                    $usr_otgoing_id = "User";

                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                                    echo $response_id;

                                    $this->db->trans_start();
                                    $response_update = array(
                                        'processed' => 'Yes',
                                        'updated_by' => '1'
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

                            echo 'Condition Not met ....';

                            $this->db->trans_start();



                            $message = "Error encountered = > The message does not meet the standardised structure... Please try again";
                            echo 'Message => ' . $message . '<br>';
                            //Conditions were not met , queue out going message 
                            $created_at = date('Y-m-d H:i:s');
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => $message,
                                'status' => 'Not Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'clnt_usr_id' => $user_id,
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => '1'
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);



                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {


                                //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $destination;
                                $msg = $message;
                                $usr_otgoing_id = "User";

                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                                echo $response_id;

                                $this->db->trans_start();
                                $response_update = array(
                                    'processed' => 'Yes',
                                    'updated_by' => '1'
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
                    echo "User not found ...<br> ";



                    $created_at = date('Y-m-d H:i:s');

                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');


                    $destination = $mobile;

                    $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'User',
                        'created_at' => $created_at,
                        'created_by' => '1',
                        'clnt_usr_id' => '587'
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);
                    echo $response_id;
                    $response_update = array(
                        'processed' => 'Yes',
                        'updated_by' => '1'
                    );
                    $this->db->where('id', $response_id);
                    $this->db->update('responses', $response_update);


                    $this->db->trans_start();
                }
            } else {
                //Old Non Encrypted Message
                echo "Old Non Encrypted Message" . $count_special;



                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $response_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
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

    function process_stop($response_id) {
        //get current date time 
        $today = date("Y-m-d H:i:s");
        //Get the content for STOP , by filtering based on the message type ID 6 which caters for the  STOP massages and for the  different languages in the system .
        $get_stop_msg_type = $this->db->query("  SELECT * FROM tbl_content WHERE message_type_id='6' AND identifier='16' and logic_flow='1' ");
        $check_existence = $get_stop_msg_type->num_rows();

        //Check if there's something returned from the  system 
        if ($check_existence >= 1) {

            //If returned get the  result row of the  above $get_stop_msg_type query
            $get_stop_msg = $get_stop_msg_type->result();
            foreach ($get_stop_msg as $value) {
                //Loop through the  specific message and get the  content from the  query e.g STOP 
                $stop_msg = $value->content;
                //Get returl from the  incoming table based on the  STOP message and that was found from the table content and it has nt been processed yet
                $query = $this->db->query("Select * from tbl_responses where msg LIKE '%$stop_msg%' and processed ='No' and id='$response_id'");
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
                                'smsenable' => 'No',
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
                                        $get_outgoing_msg = $this->db->query(" SELECT 
  * 
FROM
  tbl_content 
WHERE message_type_id = '6' 
  AND identifier = '16' 
  AND logic_flow = '2' 
  AND language_id = '$language_id' ")->result();
                                        foreach ($get_outgoing_msg as $value) {

                                            $message = $value->message;
                                            // // // echo 'Outgoing Message => ' . $message . '</br>';
                                            // // // echo 'Client ID => ' . $client_id . '</br>';
                                            $created_at = date('Y-m-d H:i:s');
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');


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
                                            $this->db->insert('clnt_outgoing', $data_outgoing);


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

    function process_transit() {
        $query = $this->db->query("Select * from tbl_responses where msg like '%TRANSFER%' and processed = 'No' LIMIT 1 ")->result();

        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;


            $response_id = $value->id;
            echo $response_id;

            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {

                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;

                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $descrypted_msg = $this->decrypt($message);
                echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                $new_msg = $identifier . "*" . $descrypted_msg;
                echo 'New Message => ' . $new_msg;


                $msg = $new_msg;

                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', TRUE);
                // Retrieve a config item named site_name contained within the blog_settings array
                $source = $this->config->item('shortcode', 'config');
                $mobile = substr($user_source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }
                echo '<br>  Our Message => ' . $msg . '<br>';
                echo 'Response id => ' . $response_id . ' and Phone Noe : ' . $user_source . '.</br>';

                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility' LIMIT 1 ");
                $user_exists = $get_facility->num_rows();
                if ($user_exists > 0) {
                    echo 'User Found...';
                    //User exists
                    $get_user_details = $get_facility->result();

                    foreach ($get_user_details as $value) {

                        $facility_id = $value->facility_id;
                        $partner_id = $value->partner_id;
                        $user_id = $value->id;
                        echo 'Incoming  Msg => ' . $msg . '</br>';
                        $exploded_msg = explode("*", $msg);
                        $count_msg = count($exploded_msg);
                        echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 2


                        if ($count_msg == 2) {
                            $key_code = @$exploded_msg[0]; //CODE = TRANSFER CLIENT 1
                            $upn = @$exploded_msg[1]; //UPN/CCC NO 2

                            $get_client_id = $this->db->get_where("client", array('clinic_number' => $upn));
                            if ($get_client_id->num_rows() > 0) {
                                foreach ($get_client_id->result() as $value) {
                                    $client_id = $value->id;
                                    $mfl_code = $value->mfl_code;
                                    $clinic_id = $value->clinic_id;

                                    if ($mfl_code === $facility_id) {
                                        //Cannot transfer within the  same facility





                                        $message = "Client ID : $upn already exists in the  Facility : $mfl_code and cannot be transfered within the same facility . ";

                                        echo $message . '<br>';
                                        $this->db->trans_start();



                                        //Conditions were not met , queue out going message 
                                        $created_at = date('Y-m-d H:i:s');
                                        $data_outgoing = array(
                                            'destination' => $user_source,
                                            'source' => $user_destination,
                                            'msg' => $message,
                                            'status' => 'Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'clnt_usr_id' => $user_id,
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at,
                                            'created_by' => $user_id
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);






                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {





                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');
                                            $destination = $user_source;
                                            $msg = $message;
                                            $usr_otgoing_id = "User";

                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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
                                        //Different facilities , proceeed with the transfer


                                        echo 'MFL COde => ' . $mfl_code . 'Clinic ID ' . $clinic_id . 'Clinic Number /CCC Number : ' . $upn;
                                        $today = date('Y-m-d H:i:s');



                                        $this->db->trans_start();
                                        $data_array = array(
                                            'mfl_code' => $facility_id,
                                            'prev_clinic' => $mfl_code,
                                            'client_type' => 'Self Transfer',
                                            'transfer_date' => $today
                                        );
                                        $this->db->where('id', $client_id);
                                        $this->db->update('client', $data_array);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            //Update Failed....
                                        } else {
                                            //Update was succesfull...


                                            $message = " Client ID : $upn  was successfully transfered from Clinic $mfl_code to Clinic $facility_id ";

                                            echo $message . '<br>';
                                            $this->db->trans_start();



                                            //Conditions were not met , queue out going message 
                                            $created_at = date('Y-m-d H:i:s');
                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $user_destination,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at,
                                                'created_by' => $user_id
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);


                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {


                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');
                                                $destination = $user_source;
                                                $msg = $message;
                                                $usr_otgoing_id = "User";

                                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);



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




                                                    $get_home_facility_phone_no = $this->db->get_where('users', array('facility_id' => $mfl_code, 'clinic_id' => $clinic_id));
                                                    if ($get_home_facility_phone_no->num_rows() > 0) {
                                                        foreach ($get_home_facility_phone_no->result() as $value) {
                                                            $facility_phone_no = $value->phone_no;

                                                            echo 'Facility phone no' . $facility_phone_no . '<br>';

                                                            $user_name = $value->f_name . ' ' . $value->m_name;


                                                            $this->db->trans_start();

                                                            $user_msg = "Hi $user_name , Clients : $upn  has been transfered by user : $user_source to facility $facility_id kindly follow up for more details with the  following number $user_source ";

                                                            echo 'User msg => ' . $user_msg . '<br>';

                                                            //Conditions were not met , queue out going message 
                                                            $created_at = date('Y-m-d H:i:s');
                                                            $data_outgoing = array(
                                                                'destination' => $user_source,
                                                                'source' => $user_destination,
                                                                'msg' => $user_msg,
                                                                'status' => 'Sent',
                                                                'message_type_id' => '5',
                                                                'responded' => 'No',
                                                                'clnt_usr_id' => $user_id,
                                                                'recepient_type' => 'User',
                                                                'created_at' => $created_at,
                                                                'created_by' => $user_id
                                                            );
                                                            $this->db->insert('usr_outgoing', $data_outgoing);



                                                            $this->db->trans_complete();
                                                            if ($this->db->trans_status() === FALSE) {
                                                                
                                                            } else {

                                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                $this->config->load('config', TRUE);
                                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                                $source = $this->config->item('shortcode', 'config');
                                                                $destination = $user_source;
                                                                $msg = $message;
                                                                $usr_otgoing_id = "User";

                                                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                            }
                                                        }
                                                    } else {
                                                        
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {


                                $this->db->trans_start();

                                $message = "Error encountered = > Clinic No $upn was not found in the  system... "
                                        . "Ushauri : Getting Better one text at a time.";


                                //Conditions were not met , queue out going message 
                                $created_at = date('Y-m-d H:i:s');
                                $data_outgoing = array(
                                    'destination' => $user_source,
                                    'source' => $user_destination,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'clnt_usr_id' => $user_id,
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at,
                                    'created_by' => $user_id
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);






                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {





                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');
                                    $destination = $user_source;
                                    $msg = $message;
                                    $usr_otgoing_id = "User";

                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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

                            $message = "Error encountered = > You need to update your application to the  latest version, kindly contact support for guidance. "
                                    . "Ushauri : Getting Better one text at a time.";


                            //Conditions were not met , queue out going message 
                            $created_at = date('Y-m-d H:i:s');
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => $message,
                                'status' => 'Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'clnt_usr_id' => $user_id,
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);






                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {





                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $user_source;
                                $msg = $message;
                                $usr_otgoing_id = "User";

                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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
                    echo 'User not found ..';
                    $msg = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";

                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');
                    $destination = $user_source;
                    $msg = $message;
                    $usr_otgoing_id = "User";

                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;


                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $response_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_transit_appointment($response_id) {
        $query = $this->db->query("Select * from tbl_responses where msg like '%TRANSITCLIENT%' and processed = 'No' and id=''$response_id'  ")->result();

        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;


            $response_id = $value->id;
            echo $response_id;

            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {

                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;

                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $descrypted_msg = $this->decrypt($message);
                echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                $new_msg = $identifier . "*" . $descrypted_msg;
                echo 'New Message => ' . $new_msg;


                $msg = $new_msg;

                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', TRUE);
                // Retrieve a config item named site_name contained within the blog_settings array
                $source = $this->config->item('shortcode', 'config');
                $mobile = substr($user_source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }
                echo '<br>  Our Message => ' . $msg . '<br>';
                echo 'Response id => ' . $response_id . ' and Phone Noe : ' . $user_source . '.</br>';

                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility' LIMIT 1 ");
                $user_exists = $get_facility->num_rows();
                if ($user_exists > 0) {
                    echo 'User Found...';
                    //User exists
                    $get_user_details = $get_facility->result();

                    foreach ($get_user_details as $value) {

                        $facility_id = $value->facility_id;
                        $partner_id = $value->partner_id;
                        $user_id = $value->id;
                        echo 'Incoming  Msg => ' . $msg . '</br>';
                        $exploded_msg = explode("*", $msg);
                        $count_msg = count($exploded_msg);
                        echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 2


                        if ($count_msg == 4) {
                            $key_code = @$exploded_msg[0]; //CODE = TRANSIT CLIENT 1
                            $upn = @$exploded_msg[1]; //UPN/CCC NO 2
                            $national_id = @$exploded_msg[3]; //NATIONAL/PASSPORT NO 2
                            $appointment_type_id = @$exploded_msg[2]; //Appointment Type ID  3

                            $get_client_id = $this->db->get_where("client", array('clinic_number' => $upn));
                            if ($get_client_id->num_rows() > 0) {
                                foreach ($get_client_id->result() as $value) {
                                    $client_id = $value->id;

                                    $mfl_code = $value->mfl_code;
                                    $clinic_id = $value->clinic_id;

                                    echo 'MFL COde => ' . $mfl_code . 'Clinic ID ' . $clinic_id . 'Clinic Number /CCC Number : ' . $upn;
                                    $attendance_dae = date('Y-m-d H:i:s');



                                    $this->db->trans_start();
                                    $data_array = array(
                                        'client_id' => $client_id,
                                        'attendance_date' => $attendance_dae,
                                        'appointment_type_id' => $appointment_type_id,
                                        'created_by' => $user_id,
                                        'updated_by' => $user_id,
                                        'processed' => 'No'
                                    );
                                    $this->db->insert('transit_app', $data_array);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        //Update Failed....
                                    } else {
                                        //Update was succesfull...


                                        $message = "Transit Client ID : $upn appointment was successfully updated ";

                                        echo $message . '<br>';
                                        $this->db->trans_start();



                                        //Conditions were not met , queue out going message 
                                        $created_at = date('Y-m-d H:i:s');
                                        $data_outgoing = array(
                                            'destination' => $user_source,
                                            'source' => $user_destination,
                                            'msg' => $message,
                                            'status' => 'Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'clnt_usr_id' => $user_id,
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at,
                                            'created_by' => $user_id
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);


                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {


                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');
                                            $destination = $user_source;
                                            $msg = $message;
                                            $usr_otgoing_id = "User";
                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);



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




                                                $get_home_facility_phone_no = $this->db->get_where('users', array('facility_id' => $mfl_code, 'clinic_id' => $clinic_id));
                                                if ($get_home_facility_phone_no->num_rows() > 0) {
                                                    
                                                } else {
                                                    
                                                }
                                                foreach ($get_home_facility_phone_no->result() as $value) {
                                                    $facility_phone_no = $value->phone_no;

                                                    echo 'Facility phone no' . $facility_phone_no . '<br>';

                                                    $user_name = $value->f_name . ' ' . $value->m_name;


                                                    $this->db->trans_start();

                                                    $user_msg = "Hi $user_name , Clients : $upn appointment has been updated by user : $user_source kindly follow up for more details. ";

                                                    echo 'User msg => ' . $user_msg . '<br>';

                                                    //Conditions were not met , queue out going message 
                                                    $created_at = date('Y-m-d H:i:s');
                                                    $data_outgoing = array(
                                                        'destination' => $user_source,
                                                        'source' => $user_destination,
                                                        'msg' => $user_msg,
                                                        'status' => 'Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $user_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at,
                                                        'created_by' => $user_id
                                                    );
                                                    $this->db->insert('usr_outgoing', $data_outgoing);



                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {

                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');
                                                        $destination = $user_source;
                                                        $msg = $message;
                                                        $usr_otgoing_id = "User";
                                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {


                                $this->db->trans_start();

                                $message = "Error encountered = > Clinic No $upn was not found in the  system... "
                                        . "Ushauri : Getting Better one text at a time.";


                                //Conditions were not met , queue out going message 
                                $created_at = date('Y-m-d H:i:s');
                                $data_outgoing = array(
                                    'destination' => $user_source,
                                    'source' => $user_destination,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'clnt_usr_id' => $user_id,
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at,
                                    'created_by' => $user_id
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);






                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {





                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');
                                    $destination = $user_source;
                                    $msg = $message;
                                    $usr_otgoing_id = "User";
                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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

                            $message = "Error encountered = > You need to update your application to the  latest version, kindly contact support for guidance. "
                                    . "Ushauri : Getting Better one text at a time.";


                            //Conditions were not met , queue out going message 
                            $created_at = date('Y-m-d H:i:s');
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => $message,
                                'status' => 'Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'clnt_usr_id' => $user_id,
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);






                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {





                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $user_source;
                                $msg = $message;
                                $usr_otgoing_id = "User";
                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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
                    echo 'User not found ..';
                    $msg = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";

                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');
                    $destination = $user_source;
                    $msg = $message;
                    $usr_otgoing_id = "User";
                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;


                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $response_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_move_clinic($response_id) {
        $query = $this->db->query("Select * from tbl_responses where  processed='No' and id='$response_id'  ")->result();

        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;


            $response_id = $value->id;

            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {

                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;

                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $descrypted_msg = $this->decrypt($message);
                echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                $new_msg = $identifier . "*" . $descrypted_msg;
                echo 'New Message => ' . $new_msg;


                $msg = $new_msg;

                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', TRUE);
                // Retrieve a config item named site_name contained within the blog_settings array
                $source = $this->config->item('shortcode', 'config');
                $mobile = substr($user_source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }
                echo 'Message => ' . $msg . '<br>';
                echo 'Response id => ' . $response_id . ' and Phone Noe : ' . $user_source . '.</br>';

                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility' limit 1 ");
                $user_exists = $get_facility->num_rows();
                if ($user_exists > 0) {
                    echo 'User Found...<br>';
                    //User exists
                    $get_user_details = $get_facility->result();

                    foreach ($get_user_details as $value) {

                        $facility_id = $value->facility_id;
                        $partner_id = $value->partner_id;
                        $user_id = $value->id;
                        $phone_no = $value->phone_no;
                        // // // echo 'Incoming  Msg => ' . $msg . '</br>';
                        $exploded_msg = explode("*", $msg);
                        $count_msg = count($exploded_msg);
                        echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 2


                        if ($count_msg == 3) {
                            $move_code = @$exploded_msg[0]; //CODE = MOVECLINIC 1
                            $upn = @$exploded_msg[1]; //UPN/CCC NO 2
                            $clinic_id = @$exploded_msg[2]; //CLINIC ID  3
                            echo 'our clinic id => ' . $clinic_id;
                            $limit = 1;
                            $get_client_id = $this->db->get_where("client", array('clinic_number' => $upn), $limit);
                            $no_clients = $get_client_id->num_rows();
                            echo "no of clients found .... => " . $no_clients;
                            if ($get_client_id->num_rows() > 0) {
                                foreach ($get_client_id->result() as $value) {
                                    $client_id = $value->id;
                                    $old_clinic_id = $value->clinic_id;


                                    if ($old_clinic_id === $clinic_id) {
                                        $get_clinic = $this->db->get_where('clinic', array('id' => $clinic_id), $limit)->result();
                                        foreach ($get_clinic as $value) {
                                            $clinic_name = $value->name;
                                            $message = "Client ID : $upn already exists in the  Clinic : $clinic_name and cannot be moved . ";

                                            echo $message . '<br>';
                                            $this->db->trans_start();



                                            //Conditions were not met , queue out going message 
                                            $created_at = date('Y-m-d H:i:s');
                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $user_destination,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at,
                                                'created_by' => $user_id
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);






                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {





                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');
                                                $destination = $user_source;
                                                $msg = $message;
                                                $usr_otgoing_id = "User";
                                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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




                                        $clinic_id_dict = "";


                                        $clinic_id_dict = "1:2:3"; //1 => Active 2 => Disabled 3 => Dead
                                        $exploded_status_dict = explode(":", $clinic_id_dict);

                                        $psc = @$exploded_status_dict[0];
                                        $pmtct = @$exploded_status_dict[1];
                                        $emtct = @$exploded_status_dict[2];
                                        $tb = @$exploded_status_dict[3];

                                        $this->db->trans_start();
                                        $data_update = array(
                                            'clinic_id' => $clinic_id
                                        );
                                        $this->db->where('id', $client_id);
                                        $this->db->update('client', $data_update);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            //Update Failed....
                                        } else {
                                            //Update was succesfull...


                                            $message = "Client ID : $upn was successfully moved to new Clinic : ";

                                            echo $message . '<br>';
                                            $this->db->trans_start();



                                            //Conditions were not met , queue out going message 
                                            $created_at = date('Y-m-d H:i:s');
                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $user_destination,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at,
                                                'created_by' => $user_id
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);






                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {





                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');
                                                $destination = $user_source;
                                                $msg = $message;
                                                $usr_otgoing_id = "User";
                                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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
                            } else {



                                //Failed, please try again ....
                                // echo 'Old application';

                                $this->db->trans_start();

                                $message = " Move Client Clinic failed , Clinic No $upn was not found in the  system "
                                        . "Ushauri : Getting Better one text at a time.";


                                echo '' . $message;
                                //Conditions were not met , queue out going message 
                                $created_at = date('Y-m-d H:i:s');
                                $data_outgoing = array(
                                    'destination' => $user_source,
                                    'source' => $user_destination,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'clnt_usr_id' => $user_id,
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at,
                                    'created_by' => $user_id
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);






                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {





                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');
                                    $destination = $user_source;
                                    $msg = $message;
                                    $usr_otgoing_id = "User";
                                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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

                            $message = "Error encountered = > You need to update your application to the  latest version, kindly contact support for guidance. "
                                    . "Ushauri : Getting Better one text at a time.";
                            echo '' . $message;

                            //Conditions were not met , queue out going message 
                            $created_at = date('Y-m-d H:i:s');
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => $message,
                                'status' => 'Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'clnt_usr_id' => $user_id,
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);






                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {





                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $user_source;
                                $msg = $message;
                                $usr_otgoing_id = "User";
                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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
                    //Failed, please try again ....
                    // echo 'Old application';

                    $this->db->trans_start();

                    $message = "Hi , your phone number is not in the  system, kindly contact your partner focal prson so that it can be added."
                            . "Thank you ! "
                            . " "
                            . "Ushauri : Getting Better one text at a time.";


                    //Conditions were not met , queue out going message 
                    $created_at = date('Y-m-d H:i:s');
                    $data_outgoing = array(
                        'destination' => $user_source,
                        'source' => $user_destination,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'clnt_usr_id' => "587",
                        'recepient_type' => 'User',
                        'created_at' => $created_at,
                        'created_by' => "587"
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);






                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {





                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        $this->config->load('config', TRUE);
                        // Retrieve a config item named site_name contained within the blog_settings array
                        $source = $this->config->item('shortcode', 'config');
                        $destination = $user_source;
                        $msg = $message;
                        $usr_otgoing_id = "User";
                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





                        $this->db->trans_start();
                        $response_update = array(
                            'processed' => 'Yes',
                            'updated_by' => 587
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
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;


                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $response_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_register($response_id) {
        $query = $this->db->query("Select * from tbl_responses where msg like '%REG%' and processed='No' and id='$response_id'  ");
        if ($query->num_rows() > 0) {


            foreach ($query->result() as $value) {
                $user_source = $value->source;
                $user_destination = $value->destination;
                $encrypted_msg = $value->msg;


                $response_id = $value->id;
                echo $response_id . '<br>';

                try {

                    $count_special = substr_count($encrypted_msg, "*");
                    if ($count_special < 2) {
                        //New Encrypted Message
                        echo " New Encrypted Message => " . $count_special;



                        $explode_msg = explode("*", $encrypted_msg);
                        $identifier = $explode_msg[0];
                        $message = $explode_msg[1];
                        $descrypted_msg = $this->decrypt($message);
                        echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                        $new_msg = $identifier . "*" . $descrypted_msg;
                        echo 'New Message => ' . $new_msg;



                        $msg = $new_msg;

                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        $this->config->load('config', TRUE);
                        // Retrieve a config item named site_name contained within the blog_settings array
                        $short_code = $this->config->item('shortcode', 'config');
                        $mobile = substr($user_source, -9);
                        $len = strlen($mobile);

                        if ($len = 9) {

                            $user_source = "0" . $mobile;
                        }
                        echo 'Message => ' . $msg . '<br>';
                        // // // echo 'Response id => ' . $response_id . ' and Phone Noe : ' . $user_source . '.</br>';

                        $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility'");
                        $user_exists = $get_facility->num_rows();
                        if ($user_exists > 0) {
                            echo 'User Found...';
                            //User exists
                            $get_user_details = $get_facility->result();

                            foreach ($get_user_details as $value) {

                                $facility_id = $value->facility_id;
                                $partner_id = $value->partner_id;
                                $user_id = $value->id;
                                $clinic_id = $value->clinic_id;
                                // // // echo 'Incoming  Msg => ' . $msg . '</br>';
                                $exploded_msg = explode("*", $msg);
                                $count_msg = count($exploded_msg);
                                echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 20



                                if ($count_msg == 22) {
                                    //Success Go Ahead 
                                    //  echo 'Success , new application kindly go ahead....';

                                    $reg = @$exploded_msg[0]; //CODE = REG => REGISTRATION 1
                                    $upn = @$exploded_msg[1]; //UPN/CCC NO 2
                                    $serial_no = @$exploded_msg[2]; //SERIAL NO 3

                                    $f_name = @$exploded_msg[3]; //FIRST NAME 4
                                    $m_name = @$exploded_msg[4]; //MIDDLE NAME 5
                                    $l_name = @$exploded_msg[5]; //LAST NAME 6
                                    $dob = @$exploded_msg[6]; //DATE OF BIRTH 7
                                    $national_id = @$exploded_msg[7]; //NATIONAL ID OR PASSOPRT NO 8
                                    $gender = @$exploded_msg[8]; //GENDER 9
                                    $marital = @$exploded_msg[9]; //MARITAL STATUS 10 
                                    $condition = @$exploded_msg[10]; //CONDITION 11
                                    $enrollment_date = @$exploded_msg[11]; //ENROLLMENT DATE 12
                                    $art_start_date = @$exploded_msg[12]; //ART START DATE 13
                                    $primary_phone_no = @$exploded_msg[13]; //PHONE NUMBER 14
                                    $alt_phone_no = @$exploded_msg[14]; //PHONE NUMBER 14
                                    $trtmnt_buddy_phone_no = @$exploded_msg[15]; //PHONE NUMBER 14
                                    $language = @$exploded_msg[16]; //LANGUAGE 16
                                    $sms_enable = @$exploded_msg[17]; //SMS ENABLE 15
                                    $motivation_enable = @$exploded_msg[18]; //MOTIVATIONAL ALERTS ENABLE 18
                                    $messaging_time = @$exploded_msg[19]; //MESSAGING TIME 17
                                    $client_status = @$exploded_msg[20]; //CLIENT STATUS 19
                                    $transaction_type = @$exploded_msg[21]; //TRANSACTION TYPE 20 
                                    //$regiment = $exploded_msg[16];
                                    $client_id = '';
                                    // // // echo 'Enrollment date = > #2 =>  ' . $enrollment_date . '</br>';
                                    $enrollment_date2 = $enrollment_date;


                                    $trans_type_dict = "1:2:3"; //1= > NEW 2 => UPDATE 3=> TRANSFER

                                    $exploded_trans_type_dict = explode(":", $trans_type_dict);

                                    $new_trans = $exploded_trans_type_dict[0];
                                    $update_trans = $exploded_trans_type_dict[1];
                                    $transfer_trans = $exploded_trans_type_dict[2];


//                            echo "Our Outpout => <br> UPN " . $upn . " <br> SerialNo " . $serial_no . "<br> F Name= " . $f_name . "<br> M Name " . $m_name . " L Name " . $l_name . "<br>DoB => " . $dob . "<br> National ID =>" . $national_id . "<br> Gender =>" . $gender . "<br> Marital => " . $marital . "<br> Condition => " . $condition . "<br> Enrollment date => " . $enrollment_date .
//                            "<br> ART Date => " . $art_start_date . "<br> Phone No => " . $phone_no . "<br>SMS Enable => " . $sms_enable . "<br> Language => " . $language . "<br> Msg time => " . $messaging_time . "<br> Motivation " . $motivation_enable . "<br> Client status => " . $client_status . "<br> tran type => " . $transaction_type . "<br>";





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

                                    echo "Language ID => " . $language . "<br>";


                                    /* Transaction Types .....
                                     * 
                                     * Transaction #1 =>  Add new client
                                     * Transaction #2 => Update Client
                                     * Transaction #3 => Transfer Client 
                                     */

                                    if ($transaction_type == $new_trans or $transaction_type == $transfer_trans) {

                                        if ($gender < 0) {
                                            $gender = "0";
                                        } else {
                                            $check_gender = $this->db->get_where('gender', array('id' => $gender))->num_rows();
                                        }

                                        if ($marital < 0) {
                                            $marital = "0";
                                        } else {
                                            $check_marital_status = $this->db->get_where('marital_status', array('id' => $marital))->num_rows();
                                        }
                                        if ($condition < 0) {
                                            $condition = "0";
                                        } else {
                                            $check_condition = $this->db->get_where('condition', array('id' => $condition))->num_rows();
                                        }
                                        // $check_grouping = $this->db->get_where('groups', array('id' => $grouping))->num_rows();
                                        if ($language < 0) {
                                            echo 'Language ID Negative => ' . $language . '<br>';
                                            $language = "5";
                                            $check_language = $this->db->get_where('language', array('id' => $language))->num_rows();
                                        } else {
                                            echo 'Language ID Positive => ' . $language . '<br>';
                                            $check_language = $this->db->get_where('language', array('id' => $language))->num_rows();
                                        }


                                        if ($check_gender > 0 and $check_marital_status > 0 and $check_condition > 0 and $check_language > 0) {

                                            echo "Our Lang => " . $check_language;
                                            echo $sms_enable;

                                            if ($sms_enable == $yes) {
                                                $sms_lrt = "Yes";
                                            } elseif ($sms_enable == $no or $sms_enable === "-1") {

                                                $sms_lrt = "No";
                                            } else {
                                                $outgoing_msg .= " Invalid selection for SMS Alert please try again with 1= > YES 2 => NO   ";
                                            }

                                            if ($motivation_enable == $yes) {
                                                $motivation_enable = "Yes";
                                            } elseif ($motivation_enable == $no or $motivation_enable === "-1") {

                                                $motivation_enable = "No";
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

                                                $outgoing_msg .= " Invalid selection for Client Status please try again with 1 => Active 2 => Disabled 3 => Dead  ";
                                            }


                                            echo $sms_lrt . "<br>";
                                            echo $client_stts . "<br>";

                                            if (!empty($sms_lrt) and ! empty($client_stts)) {
                                                echo $outgoing_msg;
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

                                                    $dob = str_replace('/', '-', $dob);
                                                    $dob = date("Y-m-d", strtotime($dob));

                                                    $current_date = date("Y-m-d");
                                                    $current_date = date_create($current_date);
                                                    $new_dob = date_create($dob);
                                                    $date_diff = date_diff($new_dob, $current_date);
                                                    $diff = $date_diff->format("%R%a days");

                                                    $diff = substr($diff, 0);
                                                    $diff = (int) $diff;





                                                    $category = "";
                                                    if ($diff >= 3650 and $diff <= 6935) {
                                                        //Adolescent
                                                        $category .= 2;
                                                    } else if ($diff >= 7300) {
                                                        //Adult
                                                        $category .= 1;
                                                    } else {
                                                        //Paeds
                                                        $category .= 3;
                                                    }



                                                    if ($transaction_type == $new_trans) {
                                                        //REGISTER NEW CLIENT GOES IN HERE ...
                                                        // echo 'Insert Transaction was found ....<br>';









                                                        $clinic_number = $upn;
                                                        // // // echo '<br>' . $clinic_number . '<br>';
                                                        $check_client_existence = $this->db->get_where('client', array('clinic_number' => $clinic_number))->num_rows();
                                                        if ($check_client_existence > 0) {
                                                            echo 'Clinic number already exists ... <br> ';
                                                            $created_at = date('Y-m-d H:i:s');

                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');




                                                            $this->db->trans_start();
                                                            $message = "Client No : $upn already exists in the  system and cannot be registered again, you can either Update client's records or transfer in the  client.  ";
                                                            $data_outgoing = array(
                                                                'destination' => $user_source,
                                                                'source' => $source,
                                                                'msg' => $message,
                                                                'status' => 'Sent',
                                                                'message_type_id' => '5',
                                                                'responded' => 'No',
                                                                'clnt_usr_id' => $user_id,
                                                                'recepient_type' => 'User',
                                                                'created_at' => $created_at,
                                                                'created_by' => $user_id
                                                            );
                                                            $this->db->insert('usr_outgoing', $data_outgoing);







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
                                                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                    $this->config->load('config', TRUE);
                                                                    // Retrieve a config item named site_name contained within the blog_settings array
                                                                    $source = $this->config->item('shortcode', 'config');
                                                                    $destination = $user_source;
                                                                    $msg = $message;

                                                                    $usr_otgoing_id = "User";
                                                                    $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                                    $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                                    log_message('info', $log_msg);
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
                                                            } else {
                                                                //Paeds
                                                                $category .= 3;
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
                                                                'phone_no' => $primary_phone_no,
                                                                'alt_phone_no' => $alt_phone_no,
                                                                'buddy_phone_no' => $trtmnt_buddy_phone_no,
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
                                                                'motivational_enable' => $motivation_enable,
                                                                'wellness_enable' => $motivation_enable,
                                                                'national_id' => $national_id,
                                                                'file_no' => $serial_no,
                                                                'clinic_id' => $clinic_id
                                                            );
                                                            $this->db->insert('client', $data_insert);
                                                            $client_id .= $this->db->insert_id();
                                                            echo $client_id;
                                                            log_message("INFO", $client_id);

                                                            $this->db->trans_complete();
                                                            if ($this->db->trans_status() === FALSE) {
                                                                
                                                            } else {



                                                                $this->welcome_msg($client_id);


                                                                $this->db->trans_start();
                                                                $response_update = array(
                                                                    'processed' => 'Yes'
                                                                );
                                                                $this->db->where('id', $response_id);
                                                                $this->db->update('responses', $response_update);

                                                                $this->db->trans_complete();
                                                                if ($this->db->trans_status() === FALSE) {
                                                                    
                                                                } else {


                                                                    $this->db->trans_start();
                                                                    $message = "Client ID : $upn was succesfully added in the  system ";
                                                                    $data_outgoing = array(
                                                                        'destination' => $user_source,
                                                                        'source' => $source,
                                                                        'msg' => $message,
                                                                        'status' => 'Sent',
                                                                        'message_type_id' => '5',
                                                                        'responded' => 'No',
                                                                        'clnt_usr_id' => $client_id,
                                                                        'recepient_type' => 'User',
                                                                        'created_at' => $created_at,
                                                                        'created_by' => $user_id
                                                                    );
                                                                    $this->db->insert('usr_outgoing', $data_outgoing);







                                                                    $this->db->trans_complete();
                                                                    if ($this->db->trans_status() === FALSE) {
                                                                        
                                                                    } else {
                                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                        $this->config->load('config', TRUE);
                                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                                        $source = $this->config->item('shortcode', 'config');
                                                                        $destination = $user_source;
                                                                        $msg = $message;

                                                                        $usr_otgoing_id = "User";
                                                                        $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                                        $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                                        log_message('info', $log_msg);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }



                                                    if ($transaction_type == $transfer_trans) {
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
                                                                } else {
                                                                    //Paeds
                                                                    $category .= 3;
                                                                }



                                                                $this->db->trans_start();


                                                                $created_at = date('Y-m-d H:i:s');




                                                                $this->db->query("UPDATE IGNORE  tbl_client set clinic_number = '$upn' , facility_id ='$facility_id', mfl_code='$facility_id',f_name='$f_name',m_name='$m_name',l_name='$l_name',dob='$dob' ,  "
                                                                        . " gender ='$gender' , marital='$marital' , client_status='$condition' , enrollment_date ='$enrollment_date' , group_id = '$category' , phone_no ='$primary_phone_no' , "
                                                                        . " alt_phone_no = '$alt_phone_no' , buddy_phone_no = '$trtmnt_buddy_phone_no' , language_id = '$language' , smsenable ='$sms_enable' , partner_id = '$partner_id' , status = '$client_stts' , "
                                                                        . " art_date = '$art_start_date'  , entry_point = 'Mobile' , updated_by = '$user_id' , prev_clinic = '$previous_facility' , client_type = 'Transfer' , "
                                                                        . " txt_time = '$messaging_time' , motivational_enable = '$motivation_enable' , wellness_enable = '$motivation_enable' , national_id = '$national_id' , file_no ='$serial_no' , clinic_id = '$clinic_id' "
                                                                        . " where id = '$trans_3_client_id' ");

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
                                                                        'status' => 'Sent',
                                                                        'message_type_id' => '5',
                                                                        'responded' => 'No',
                                                                        'clnt_usr_id' => $user_id,
                                                                        'recepient_type' => 'User',
                                                                        'created_at' => $created_at,
                                                                        'created_by' => $user_id
                                                                    );
                                                                    $this->db->insert('usr_outgoing', $data_outgoing);




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


                                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                            $this->config->load('config', TRUE);
                                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                                            $source = $this->config->item('shortcode', 'config');
                                                                            $destination = $user_source;
                                                                            $msg = $message;

                                                                            $usr_otgoing_id = "User";
                                                                            $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                                            $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                                            log_message('info', $log_msg);
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
                                                            } else {
                                                                //Paeds
                                                                $category .= 3;
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
                                                                'phone_no' => $primary_phone_no,
                                                                'alt_phone_no' => $alt_phone_no,
                                                                'buddy_phone_no' => $trtmnt_buddy_phone_no,
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
                                                                'motivational_enable' => $motivation_enable,
                                                                'wellness_enable' => $motivation_enable,
                                                                'national_id' => $national_id,
                                                                'file_no' => $serial_no,
                                                                'clinic_id' => $clinic_id
                                                            );

                                                            $this->db->insert('client', $data_insert);
                                                            $client_id .= $this->db->insert_id();
                                                            echo $client_id;
                                                            log_message("INFO", $client_id);

                                                            $this->db->trans_complete();
                                                            if ($this->db->trans_status() === FALSE) {
                                                                
                                                            } else {


                                                                $this->welcome_msg($client_id);


                                                                $this->db->trans_start();
                                                                $response_update = array(
                                                                    'processed' => 'Yes'
                                                                );
                                                                $this->db->where('id', $response_id);
                                                                $this->db->update('responses', $response_update);

                                                                $this->db->trans_complete();
                                                                if ($this->db->trans_status() === FALSE) {
                                                                    
                                                                } else {






                                                                    $this->db->trans_start();
                                                                    $message = "Client ID : $upn was succesfully transfered to your facility  in the system ";
                                                                    $data_outgoing = array(
                                                                        'destination' => $user_source,
                                                                        'source' => $source,
                                                                        'msg' => $message,
                                                                        'status' => 'Sent',
                                                                        'message_type_id' => '5',
                                                                        'responded' => 'No',
                                                                        'clnt_usr_id' => $client_id,
                                                                        'recepient_type' => 'User',
                                                                        'created_at' => $created_at,
                                                                        'created_by' => $user_id
                                                                    );
                                                                    $this->db->insert('usr_outgoing', $data_outgoing);



                                                                    $this->db->trans_complete();
                                                                    if ($this->db->trans_status() === FALSE) {
                                                                        
                                                                    } else {


                                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                        $this->config->load('config', TRUE);
                                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                                        $source = $this->config->item('shortcode', 'config');
                                                                        $destination = $user_source;
                                                                        $msg = $message;
                                                                        $usr_otgoing_id = "User";
                                                                        $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                                        $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                                        log_message('info', $log_msg);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {


                                                    $this->db->trans_start();




                                                    //Conditions were not met , queue out going message 
                                                    $created_at = date('Y-m-d H:i:s');
                                                    // // // echo 'Out going message => ' . $outgoing_msg . '</br> ';
                                                    $message = "Error encountered = > " . $outgoing_msg;
                                                    $data_outgoing = array(
                                                        'destination' => $source,
                                                        'source' => $user_destination,
                                                        'msg' => $message,
                                                        'status' => 'Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $user_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at,
                                                        'created_by' => $user_id
                                                    );
                                                    $this->db->insert('usr_outgoing', $data_outgoing);






                                                    $this->db->trans_complete();
                                                    if ($this->db->trans_status() === FALSE) {
                                                        
                                                    } else {





                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');
                                                        $destination = $user_source;
                                                        $msg = $message;
                                                        $usr_otgoing_id = "User";
                                                        $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                        $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                        log_message('info', $log_msg);

                                                        log_message('debug', $source, $user_source, $msg, $usr_otgoing_id);


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
                                        } else if ($check_gender <= 0 or $check_marital_status <= 0 or $check_condition <= 0) {


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





                                            $this->db->trans_start();


                                            $message = "Error encountered = > " . $outgoing_msg;

                                            //Conditions were not met , queue out going message 
                                            $created_at = date('Y-m-d H:i:s');
                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $user_destination,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at,
                                                'created_by' => $user_id
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);





                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {



                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');
                                                $destination = $user_source;
                                                $msg = $message;
                                                $usr_otgoing_id = "User";
                                                $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                log_message('info', $log_msg);

                                                log_message('debug', $source, $user_source, $msg, $usr_otgoing_id);



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





                                    if ($transaction_type == $update_trans) {
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
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');


                                            $user_destination = $phone_no;
                                            $this->db->trans_start();
                                            $message = "Update Client Error = > Client No : $upn does not exist in the  system ";
                                            $data_outgoing = array(
                                                'destination' => $user_source,
                                                'source' => $source,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => 'User',
                                                'created_at' => $created_at,
                                                'created_by' => $user_id
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);





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


                                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                    $this->config->load('config', TRUE);
                                                    // Retrieve a config item named site_name contained within the blog_settings array
                                                    $source = $this->config->item('shortcode', 'config');
                                                    $destination = $user_source;
                                                    $msg = $message;
                                                    $usr_otgoing_id = "User";
                                                    $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                    $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                    log_message('info', $log_msg);
                                                }
                                            }
                                        } else {






                                            $enrollment_date = str_replace('/', '-', $enrollment_date);
                                            $enrollment_date = date("Y-m-d", strtotime($enrollment_date));




                                            $art_start_date = str_replace('/', '-', $art_start_date);
                                            $art_start_date = date("Y-m-d", strtotime($art_start_date));



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
                                            } else {
                                                //Paeds
                                                $category .= 3;
                                            }

                                            foreach ($client_query->result() as $value) {


                                                $trans_2_client_id = $value->id;


                                                $this->db->trans_start();


                                                $created_at = date('Y-m-d H:i:s');

                                                $query = "UPDATE IGNORE tbl_client SET ";






                                                if ($serial_no < 0) {
                                                    
                                                } else {

                                                    $query .= " file_no = '$serial_no' ";
                                                }
                                                if ($f_name < 0) {
                                                    
                                                } else {

                                                    $query .= ", f_name = '$f_name' ";
                                                }
                                                if ($m_name < 0) {
                                                    
                                                } else {
                                                    $query .= " ,m_name = '$m_name' ";
                                                }
                                                if ($l_name < 0) {
                                                    
                                                } else {
                                                    $query .= ", l_name = '$l_name' ";
                                                }
                                                if ($dob < 0) {
                                                    
                                                } else {
                                                    $query .= " ,dob = '$dob' ";
                                                }
                                                if ($national_id < 0) {
                                                    
                                                } else {
                                                    $query .= " ,national_id = '$national_id' ";
                                                }
                                                if ($gender < 0) {
                                                    
                                                } else {
                                                    $query .= " ,gender = '$gender' ";
                                                }
                                                if ($marital < 0) {
                                                    
                                                } else {
                                                    $query .= ", marital = '$marital' ";
                                                }
                                                if ($condition < 0) {
                                                    
                                                } else {
                                                    $query .= ", client_status = '$condition' ";
                                                }

                                                if ($enrollment_date < 0) {
                                                    
                                                } else {
                                                    $query .= " ,enrollment_date = '$enrollment_date' ";
                                                }
                                                if ($art_start_date < 0) {
                                                    
                                                } else {
                                                    $query .= ", art_date = '$art_start_date' ";
                                                }
                                                if ($primary_phone_no < 0) {
                                                    
                                                } else {
                                                    $query .= ", phone_no = '$primary_phone_no' ";
                                                }
                                                if ($alt_phone_no < 0) {
                                                    
                                                } else {
                                                    $query .= ", alt_phone_no = '$alt_phone_no' ";
                                                }
                                                if ($trtmnt_buddy_phone_no < 0) {
                                                    
                                                } else {
                                                    $query .= ", buddy_phone_no = '$trtmnt_buddy_phone_no' ";
                                                }
                                                if ($sms_enable < 0) {
                                                    
                                                } else {
                                                    $query .= ", smsenable = '$sms_enable' ";
                                                }
                                                if ($language < 0) {
                                                    
                                                } else {
                                                    $query .= ", language_id = '$language' ";
                                                }
                                                if ($messaging_time < 0) {
                                                    
                                                } else {
                                                    $query .= ", txt_time = '$messaging_time' ";
                                                }
                                                if ($motivation_enable < 0) {
                                                    
                                                } else {
                                                    $query .= ", motivational_enable = '$motivation_enable' ";
                                                }
                                                if ($client_status < 0) {
                                                    
                                                } else {
                                                    $query .= " ,client_status = '$client_status' ";
                                                }


                                                $query .= " WHERE id = '$trans_2_client_id' ";



                                                $this->db->query($query);

                                                $client_id .= $this->db->insert_id();
                                                echo $client_id;
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {





                                                    $this->db->trans_start();
                                                    $message = "Client ID : $upn was succesfully updated in the  system ";
                                                    $data_outgoing = array(
                                                        'destination' => $user_source,
                                                        'source' => $source,
                                                        'msg' => $message,
                                                        'status' => 'Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $client_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at,
                                                        'created_by' => $user_id
                                                    );
                                                    $this->db->insert('usr_outgoing', $data_outgoing);






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

                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');
                                                            $destination = $user_source;
                                                            $msg = $message;
                                                            $usr_otgoing_id = "User";
                                                            $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                                            $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                                            log_message('info', $log_msg);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    //Failed, please try again ....
                                    // echo 'Old application';

                                    $this->db->trans_start();

                                    $message = "Error encountered = > You need to update your application to the  latest version, kindly contact support for guidance. "
                                            . "Ushauri : Getting Better one text at a time.";


                                    //Conditions were not met , queue out going message 
                                    $created_at = date('Y-m-d H:i:s');
                                    $data_outgoing = array(
                                        'destination' => $user_source,
                                        'source' => $user_destination,
                                        'msg' => $message,
                                        'status' => 'Sent',
                                        'message_type_id' => '5',
                                        'responded' => 'No',
                                        'clnt_usr_id' => $user_id,
                                        'recepient_type' => 'User',
                                        'created_at' => $created_at,
                                        'created_by' => $user_id
                                    );
                                    $this->db->insert('usr_outgoing', $data_outgoing);






                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {





                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');
                                        $destination = $user_source;
                                        $msg = $message;
                                        $usr_otgoing_id = "User";
                                        $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                        $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                        log_message('info', $log_msg);





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


                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                            $this->config->load('config', TRUE);
                            // Retrieve a config item named site_name contained within the blog_settings array
                            $source = $this->config->item('shortcode', 'config');



                            $destination = $mobile;
                            $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                            $this->db->trans_start();


                            $data_outgoing = array(
                                'destination' => $destination,
                                'source' => $source,
                                'msg' => $message,
                                'status' => 'Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => "587",
                                'clnt_usr_id' => '587'
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);





                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {


                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $user_source;
                                $msg = $message;
                                $usr_otgoing_id = "User";
                                $send_text = $this->send_message($short_code, $user_source, $msg, $usr_otgoing_id);
                                $log_msg = "our short code is => " . $short_code . " user source => " . $user_source . " msg => " . $msg . "<br>";
                                log_message('info', $log_msg);






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
                        //Old Non Encrypted Message
                        echo " Old Non Encrypted Message => " . $count_special;


                        $this->db->trans_start();

                        $this->db->delete('responses', array('id' => $response_id));

                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            
                        }
                    }
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();

                    $this->db->trans_start();
                    $response_update = array(
                        'processed' => 'Yes',
                        'updated_by' => '1'
                    );
                    $this->db->where('id', $response_id);
                    $this->db->update('responses', $response_update);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                }
            }


            $this->clean_DOB();
        } else {
            
        }
    }

    function get_clnt_outgoing_sms($language_id, $group_id, $message_type, $identifier) {
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

    function process_appointment($response_id) {
        // sleep for 10 seconds
        sleep(5);
        $query = $this->db->query(" select * from tbl_responses where  id='$response_id' ")->result();
        //Get All New Repsonse
        foreach ($query as $value) {
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;


            $process_id = $value->id;

            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;






                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $descrypted_msg = $this->decrypt($message);
                echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                $new_msg = $identifier . "*" . $descrypted_msg;
                echo 'Cleaned Message => ' . $new_msg;


                $msg = $new_msg;


                $mobile = substr($user_source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }
                echo 'New From : ' . $user_source . '</br>';
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
                        $app = @$exploded_msg[0];
                        $upn = @$exploded_msg[1];
                        $app_date = @$exploded_msg[2];
                        $appointment_type = @$exploded_msg[3];
                        $appointment_other = @$explode_msg[4];
                        $appointment_kept = @$exploded_msg[5];
                        $old_appointment_id = @$exploded_msg[6];
                        $appointment_type_dict = "1:2:3:4:5";
                        $appointment_kept_dict = "1:2";


                        $count_msg = count($exploded_msg);
                        echo 'Count ....' . count($exploded_msg) . '<br>'; // Output of 20



                        if ($count_msg == 7) {
                            #Explode Appointment Type Dictionary
                            $exploded_app_type = explode(":", $appointment_type_dict);

                            $re_fill_code = $exploded_app_type[0];
                            $clinical_review_code = $exploded_app_type[1];
                            $enhance_adherence_code = $exploded_app_type[2];
                            $lab_investigation_code = $exploded_app_type[3];

                            if ($re_fill_code == $appointment_type) {
                                //Re Fill will assigned from here...
                                $appntmnt_type = "Re-Fill";
                            } elseif ($clinical_review_code == $appointment_type) {
                                //Clinical Review will be assigned from here ...
                                $appntmnt_type = "Clinical Review";
                            } elseif ($enhance_adherence_code == $appointment_type) {
                                //Enhance Adherence will be assigned from here ...
                                $appntmnt_type = "Enhanced Adherence";
                            } elseif ($lab_investigation_code == $appointment_type) {
                                //Enhance Adherence will be assigned from here ...
                                $appntmnt_type = "Lab Investigation";
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
                            echo "New " . $app_date . '<br>';
                            if ($app_date == "1970-01-01") {
                                //Invalid Appointment Date 
                                $created_at = date('Y-m-d H:i:s');
                                $message = "Invalid Appointment Date , DD/MM/YYYY is the  appropriate date format .  ";
                                $this->db->trans_start();
                                $data_outgoing = array(
                                    'destination' => $user_source,
                                    'source' => $user_destination,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at,
                                    'created_by' => $user_id
                                );
//                            $this->db->insert('usr_outgoing', $data_outgoing);


                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {


                                    $this->db->trans_start();
                                    $response_update = array(
                                        'processed' => 'Yes',
                                        'updated_by' => $user_id
                                    );
                                    $this->db->where('id', $process_id);
//                                $this->db->update('responses', $response_update);

                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        // End Process Here ....
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');
                                        $destination = $user_source;
                                        $msg = $message;
                                        $usr_otgoing_id = "User";
//                                    $send_text = $this->send_message($source, $user_source, $msg, $usr_otgoing_id);
                                    }
                                }
                            } else {
                                //Appointment date is correct proceed to appointment processing
                                //Get Client Details from the  Client Number 
                                echo 'Clinic Name' . $upn . '</br>';





                                $clinic_number = $upn;
                                $app_status = "Booked";
                                $language_id = '';
                                $group_id = '';


                                $client_data = $this->db->query("Select * from tbl_client where clinic_number='$clinic_number' ");

                                $check_client_existence = $client_data->num_rows();
                                if ($check_client_existence > 0) {
                                    echo 'Client Was Found....';
                                    //Client Was Found ...
                                    foreach ($client_data->result() as $client_value) {
                                        $client_id = $client_value->id;

                                        $group_id = $client_value->group_id;
                                        $language_id = $client_value->language_id;

                                        $client_name = " " . $client_value->f_name . " ";

                                        $client_name = ucwords(strtolower($client_name)) . " ";
                                        echo 'Client Name ' . $client_name . '</br>';
                                        //Get Previous Appointment  if it exists
                                        echo 'Appointment Type => ' . $appntmnt_type . '<br>';
                                        echo 'Old Appointment ID ' . $old_appointment_id . '<br>';

                                        if ($old_appointment_id > 0) {
                                            //Old Appointment needs to be updated ....

                                            echo 'Old Appointment needs to be updated ....';

                                            $get_client = $this->db->query("Select * from tbl_appointment where  id='$old_appointment_id' ");
                                            $get_client_row = $get_client->num_rows();
                                            echo $get_client_row . '<br>';

                                            if ($get_client_row > 0) {
                                                //Old Appointment
                                                echo 'Old Appointment';
                                                $get_client_result = $get_client->result();
                                                foreach ($get_client_result as $appointment_value) {
                                                    //Archive previous appointments and Update the  new appointments

                                                    $id = $appointment_value->id;
                                                    $client_id = $appointment_value->client_id;
                                                    $appntmnt_date = $appointment_value->appntmnt_date;
                                                    $app_type_1 = $appointment_value->app_type_1;
                                                    $app_type_2 = $appointment_value->app_type_2;
                                                    $expln_app = $appointment_value->expln_app;
                                                    $custom_txt = $appointment_value->custom_txt;
                                                    $created_at = $appointment_value->created_at;
                                                    $updated_at = $appointment_value->updated_at;
                                                    $app_status = $appointment_value->app_status;
                                                    $app_msg = $appointment_value->app_msg;
                                                    $appntmnt_kept = $appointment_value->appointment_kept;



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
                                                        echo 'Appointment not updated....<br>';
                                                    } else {
                                                        echo 'Appointment Updated ...<br>';








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
                                                            'sent_status' => 'Sent',
                                                            'client_id' => $client_id,
                                                            'created_at' => $today,
                                                            'created_by' => $user_id,
                                                            'app_type_1' => $appointment_type,
                                                            'entry_point' => 'Mobile'
                                                        );

                                                        $this->db->insert('appointment', $appointment_insert);
                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {

                                                            if ($appointment_type === 5) {
                                                                $this->db->trans_start();
                                                                $appointment_other_insert = array(
                                                                    'name' => $appointment_other,
                                                                    'created_by' => $user_id,
                                                                    'updated_by' => $user_id
                                                                );
                                                                $this->db->insert('other_appointment_types', $appointment_other_insert);
                                                                $this->db->trans_complete();
                                                                if ($this->db->trans_status() === FALSE) {
                                                                    
                                                                } else {
                                                                    
                                                                }
                                                            } else {
                                                                
                                                            }
                                                            $this->db->trans_start();




                                                            //Conditions were not met , queue out going message 
                                                            $created_at = date('Y-m-d H:i:s');
                                                            $message = "Client $clinic_number appointment was succesfully updated in the  system  ";
                                                            $data_outgoing = array(
                                                                'destination' => $user_source,
                                                                'source' => $user_destination,
                                                                'msg' => $message,
                                                                'status' => 'Sent',
                                                                'message_type_id' => '5',
                                                                'responded' => 'No',
                                                                'clnt_usr_id' => $user_id,
                                                                'recepient_type' => 'User',
                                                                'created_at' => $created_at,
                                                                'created_by' => $user_id
                                                            );
                                                            $this->db->insert('usr_outgoing', $data_outgoing);


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


                                                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                                    $this->config->load('config', TRUE);
                                                                    // Retrieve a config item named site_name contained within the blog_settings array
                                                                    $source = $this->config->item('shortcode', 'config');
                                                                    $destination = $user_source;
                                                                    $msg = $message;
                                                                    $usr_otgoing_id = "User";
                                                                    $send_text = $this->send_message($source, $user_source, $msg, $usr_otgoing_id);
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
                                                    'sent_status' => 'Sent',
                                                    'client_id' => $client_id,
                                                    'created_at' => $today,
                                                    'active_app' => '1',
                                                    'created_by' => $user_id,
                                                    'app_type_1' => $appointment_type,
                                                    'entry_point' => 'Mobile'
                                                );

                                                $this->db->insert('appointment', $appointment_insert);
                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {


                                                    // // // echo  'Appointment Booked Successfully ...';


                                                    $this->db->trans_start();

                                                    $created_at = date('Y-m-d H:i:s');
                                                    $message = "Client $clinic_number appointment was succesfully updated in the  system  ";
                                                    $data_outgoing = array(
                                                        'destination' => $user_source,
                                                        'source' => $user_destination,
                                                        'msg' => $message,
                                                        'status' => 'Sent',
                                                        'message_type_id' => '5',
                                                        'responded' => 'No',
                                                        'clnt_usr_id' => $user_id,
                                                        'recepient_type' => 'User',
                                                        'created_at' => $created_at
                                                    );
                                                    $this->db->insert('usr_outgoing', $data_outgoing);


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


                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');
                                                            $destination = $user_source;
                                                            $msg = $message;
                                                            $usr_otgoing_id = "User";
                                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                        }
                                                    }
                                                }
                                            }
                                        } else {

                                            //New Appointment to schedueled in the  system ....
                                            // // // echo  'New Appointment';
                                            //Insert Into Table Apptointment ang get outgoing message

                                            echo 'New Appointment to schedueled in the  system ....';






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
                                                'sent_status' => 'Sent',
                                                'client_id' => $client_id,
                                                'created_at' => $today,
                                                'active_app' => '1',
                                                'created_by' => $user_id,
                                                'app_type_1' => $appointment_type,
                                                'entry_point' => 'Mobile'
                                            );

                                            $this->db->insert('appointment', $appointment_insert);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {


                                                // // // echo  'Appointment Booked Successfully ...';


                                                $this->db->trans_start();

                                                $created_at = date('Y-m-d H:i:s');
                                                $message = "Client $clinic_number appointment was succesfully updated in the  system  ";
                                                $data_outgoing = array(
                                                    'destination' => $user_source,
                                                    'source' => $user_destination,
                                                    'msg' => $message,
                                                    'status' => 'Sent',
                                                    'message_type_id' => '5',
                                                    'responded' => 'No',
                                                    'clnt_usr_id' => $user_id,
                                                    'recepient_type' => 'User',
                                                    'created_at' => $created_at
                                                );
                                                $this->db->insert('usr_outgoing', $data_outgoing);


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


                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');
                                                        $destination = $user_source;
                                                        $msg = $message;
                                                        $usr_otgoing_id = "User";
                                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    // // // echo  'Cllinic No not found...';
                                    // // // echo  'Start';

                                    $created_at = date('Y-m-d H:i:s');
                                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                    $this->config->load('config', TRUE);
                                    // Retrieve a config item named site_name contained within the blog_settings array
                                    $source = $this->config->item('shortcode', 'config');


                                    $destination = '0' . $mobile;
                                    $this->db->trans_start();
                                    $message = " Appointment was not scheduled in the  system , Clinic No $upn was not found in the system or is not active in the  system  ...";
                                    $data_outgoing = array(
                                        'destination' => $destination,
                                        'source' => $source,
                                        'msg' => $message,
                                        'status' => 'Sent',
                                        'message_type_id' => '5',
                                        'responded' => 'No',
                                        'recepient_type' => 'Client',
                                        'created_at' => $created_at
                                    );
                                    $this->db->insert('usr_outgoing', $data_outgoing);


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


                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');
                                            $destination = $user_source;
                                            $msg = $message;
                                            $usr_otgoing_id = "User";
                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                        }
                                    }
                                }
                            }
                        } else {
                            //Failed, please try again ....
                            // echo 'Old application';

                            $this->db->trans_start();

                            $message = "Error encountered = > You need to update your application to the  latest version, kindly contact support for guidance. "
                                    . "Ushauri : Getting Better one text at a time.";

                            echo $message;
                            //Conditions were not met , queue out going message 
                            $created_at = date('Y-m-d H:i:s');
                            $data_outgoing = array(
                                'destination' => $user_source,
                                'source' => $user_destination,
                                'msg' => $message,
                                'status' => 'Sent',
                                'message_type_id' => '5',
                                'responded' => 'No',
                                'clnt_usr_id' => $user_id,
                                'recepient_type' => 'User',
                                'created_at' => $created_at,
                                'created_by' => $user_id
                            );
                            $this->db->insert('usr_outgoing', $data_outgoing);






                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                
                            } else {





                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $user_source;
                                $msg = $message;
                                $usr_otgoing_id = "User";
                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);





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




                    $created_at = date('Y-m-d H:i:s');
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');


                    $destination = '0' . $mobile;
                    $this->db->trans_start();
                    $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'Client',
                        'created_at' => $created_at,
                        'clnt_usr_id' => '587'
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);
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

                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                            $this->config->load('config', TRUE);
                            // Retrieve a config item named site_name contained within the blog_settings array
                            $source = $this->config->item('shortcode', 'config');
                            $destination = $user_source;
                            $msg = $message;
                            $usr_otgoing_id = "User";
//                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                        }
                    }
                }
            } else {
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;



                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $process_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
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
                . "tbl_client.smsenable AS ENABLE_SMS    ,tbl_appointment.appntmnt_date AS APPOINTMENT_DATE,"
                . "tbl_appointment.app_status AS APPOINTMENT_STATUS,tbl_appointment.app_msg AS APPOINTMENT_MESSAGE,tbl_appointment.updated_at APPOINTMENT_TIME_STAMP "
                . "from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id inner join tbl_groups on tbl_groups.id = tbl_client.group_id inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " where tbl_client.status='Active'  group by tbl_client.id  ";
        $result = $this->db->query($query);
        $filename = "appointments.csv";
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        write_file(FCPATH . '/documents/sys_reports/appointments.csv', $data);
        if (!write_file(FCPATH . '/documents/sys_reports/appointments.csv', $data)) {
            
        } else {
            
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
            $output = 'Email Successfully Send !';
        } else {
            $output = '<p class="error_msg">Invalid Gmail Account or Password !</p>';
        }
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
        $this->email->attach('C:\xampp\htdocs\ushauri\documents\sys_reports\appointments.csv');

        // Message in email
        $this->email->message('Messae... going...out.....');

        if ($this->email->send(FALSE)) {

            $output = 'Email Successfully Send !';
        } else {
            $output = '<p class="error_msg">Invalid Gmail Account or Password !</p>';
        }

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

    function client_grouping_calculator() {

        $sql = $this->db->query("SELECT * FROM tbl_client WHERE group_id='0' or group_id IS NULL ")->result();
        foreach ($sql as $value) {
            $client_id = $value->id;
            $dob = $value->dob;
            $current_grouping = $value->group_id;
            $dob = str_replace('/', '-', $dob);
            $dob = date("Y-m-d", strtotime($dob));

            $current_date = date("Y-m-d");
            $current_date = date_create($current_date);
            $new_dob = date_create($dob);
            $date_diff = date_diff($new_dob, $current_date);
            $diff = $date_diff->format("%R%a days");
            $diff = substr($diff, 0);
            $diff = (int) $diff;
            $category = "";
            if ($diff >= 3650 and $diff <= 7299) {
                $category .= 2;
            } else if ($diff >= 7300) {
                $category .= 1;
            } else if ($diff <= 3649) {
                $category .= 3;
            }
            $current_grouping = (int) $current_grouping;
            $category = (int) $category;
            if (strcmp($category, $current_grouping) === 0) {
                
            } else {

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

    function welcome_msg($client_id) {
        echo $client_id;
        $get_clients = $this->db->query("SELECT
	* 
FROM
	tbl_client 
WHERE
	id='$client_id' and smsenable='Yes' ")->result();
        foreach ($get_clients as $value) {
            $phone_no = $value->phone_no;
            $client_id = $value->id;
            $language_id = $value->language_id;
            $fname = $value->f_name;
            $lang = $language_id;
            $enrollment_date = $value->enrollment_date;
            $check_welcome_existence = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id='3' and  clnt_usr_id='$client_id' LIMIT 1");
            $num_rows = $check_welcome_existence->num_rows();

            if ($num_rows > 0) {
                //Welcome added
                echo "welcome added";
            } else {
                echo $language_id;
                $message_type = "Welcome";
                //Get first welcome msg
                echo 'send welcome first';
                $get_welcome_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='3' and logic_flow='1' and language_id='$lang' LIMIT 1")->result();
                foreach ($get_welcome_msg as $value) {
                    echo 'Msg #1  queued ';
                    $message_1 = $value->message;
                    $message_type_id = $value->message_type_id;
                    $client_name = ucwords(strtolower($fname)) . " ";

                    $cleaned_msg = str_replace("XXX", $client_name, $message_1);
                    $created_at = date('Y-m-d H:i:s');
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');


                    $client_destination = $phone_no;

                    $this->db->trans_start();
                    echo 'Cleaned Msg => ' . $cleaned_msg . '<br>';
                    $data_clnt_outgoing = array(
                        'destination' => $client_destination,
                        'source' => $source,
                        'msg' => $cleaned_msg,
                        'status' => 'Sent',
                        'message_type_id' => $message_type_id,
                        'responded' => 'No',
                        'clnt_usr_id' => $client_id,
                        'recepient_type' => 'Client',
                        'created_at' => $created_at,
                        'created_by' => '1'
                    );
                    $this->db->insert('clnt_outgoing', $data_clnt_outgoing);
                    $clnt_outgoing_id_1 = $this->db->insert_id();

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        $source = $source;
                        $destination = $client_destination;
                        $msg = $cleaned_msg;
                        $send_msg = $this->send_message($source, $destination, $msg, $clnt_outgoing_id_1);
                        if ($send_msg) {
                            echo 'Message sent success';
                        } else {
                            echo 'Message sending fialure';
                            $update_clnt_outgoing = array(
                                'status' => 'Not Sent'
                            );
                            $this->db->where('id', $clnt_outgoing_id_1);
                            $this->db->update('clnt_outgoing', $update_clnt_outgoing);
                        }
                    }
                }



                //Get the explanatory msg
                $get_next_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='3' and logic_flow='2' and language_id='$lang' LIMIT 1")->result();
                foreach ($get_next_clnt_outgoing_msg as $value) {
                    $message_2 = $value->message;
                    $message_type_id = $value->message_type_id;
                    echo 'Message #2 queued ....' . $message_2 . '</br>';
                    $this->db->trans_start();
                    $data_clnt_outgoing = array(
                        'destination' => $client_destination,
                        'source' => $source,
                        'msg' => $message_2,
                        'status' => 'Not Sent',
                        'message_type_id' => $message_type_id,
                        'responded' => 'No',
                        'clnt_usr_id' => $client_id,
                        'recepient_type' => 'Client',
                        'created_at' => $created_at,
                        'created_by' => '1'
                    );
                    $this->db->insert('clnt_outgoing', $data_clnt_outgoing);
                    $clnt_outgoing_id_2 = $this->db->insert_id();
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        $source = $source;
                        $destination = $client_destination;
                        $msg = $message_2;
                        $send_msg_2 = $this->send_message($source, $destination, $msg, $clnt_outgoing_id_2);
                        if ($send_msg_2) {
                            echo 'Message sent success';
                        } else {
                            echo 'Message sending fialure';
                            $update_clnt_outgoing = array(
                                'status' => 'Not Sent'
                            );
                            $this->db->where('id', $clnt_outgoing_id_2);
                            $this->db->update('clnt_outgoing', $update_clnt_outgoing);
                        }
                    }
                }





                //Check based on enrollment date if client meets the  condition of the  thid message ....

                $current_date = date("Y-m-d");
                $current_date = date_create($current_date);
                $enrollment_date2 = date_create($enrollment_date);
                $date_diff = date_diff($enrollment_date2, $current_date);

                $diff = $date_diff->format("%R%a days");
                // // // // echo 'Date Difference => ' . $diff;
                $diff = substr($diff, 1, 2);
                if ($diff >= 30) {
                    
                } elseif ($diff <= 30) {





                    // $message_type_id = 3;
                    // $logic_flow = 3;
                    //  $target_group = 'All';

                    $get_welcome_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='3' and language_id='$lang' LIMIT 1")->result();
                    foreach ($get_welcome_msg as $value) {
                        echo 'Message #3 queued ....';
                        $message_3 = $value->message;
                        $message_type_id = $value->message_type_id;
                        $client_name = ucwords(strtolower($fname)) . " ";

                        $cleaned_msg = str_replace("XXX", $client_name, $message_3);
                        $created_at = date('Y-m-d H:i:s');
                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        $this->config->load('config', TRUE);
                        // Retrieve a config item named site_name contained within the blog_settings array
                        $source = $this->config->item('shortcode', 'config');


                        $client_destination = $phone_no;
                        echo $cleaned_msg . '<br>';
                        $this->db->trans_start();

                        $data_clnt_outgoing = array(
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
                        $this->db->insert('clnt_outgoing', $data_clnt_outgoing);

                        $clnt_outgoing_id_3 = $this->db->insert_id();
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            $source = $source;
                            $destination = $client_destination;
                            $msg = $cleaned_msg;
                            $send_msg_3 = $this->send_message($source, $destination, $msg, $clnt_outgoing_id_3);
                            if ($send_msg_3) {
                                echo 'Message sent success';
                            } else {
                                echo 'Message sending fialure';
                                $update_clnt_outgoing = array(
                                    'status' => 'Not Sent'
                                );
                                $this->db->where('id', $clnt_outgoing_id_3);
                                $this->db->update('clnt_outgoing', $update_clnt_outgoing);
                            }
                        }
                    }
                }
            }
        }
    }

    function check_welcome_sent() {
        sleep(10);
        $get_clients = $this->db->query("SELECT
	* 
FROM
	tbl_client 
WHERE
	id NOT IN ( SELECT clnt_usr_id FROM tbl_clnt_outgoing WHERE message_type_id = '3' ) 
	AND tbl_client.smsenable = 'Yes' 
ORDER BY
	tbl_client.id DESC ")->result();
        foreach ($get_clients as $value) {
            $phone_no = $value->phone_no;
            $client_id = $value->id;
            $language_id = $value->language_id;
            $fname = $value->f_name;
            $lang = $language_id;
            $enrollment_date = $value->enrollment_date;
            $check_welcome_existence = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id='3' and  clnt_usr_id='$client_id' LIMIT 1");
            $num_rows = $check_welcome_existence->num_rows();

            if ($num_rows > 0) {
                //Welcome added
            } else {
                echo $language_id;
                $message_type = "Welcome";
                //Get first welcome msg

                $get_welcome_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='3' and logic_flow='1' and language_id='$lang' LIMIT 1")->result();
                print_r($get_welcome_msg);
                foreach ($get_welcome_msg as $value) {
                    echo 'Msg #1  queued ';
                    $message = $value->message;
                    $message_type_id = $value->message_type_id;
                    $client_name = ucwords(strtolower($fname)) . " ";

                    $cleaned_msg = str_replace("XXX", $client_name, $message);
                    $created_at = date('Y-m-d H:i:s');
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');


                    $client_destination = $phone_no;

                    $this->db->trans_start();
                    echo 'Cleaned Msg => ' . $cleaned_msg . '<br>';
                    $data_clnt_outgoing = array(
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
                    $this->db->insert('clnt_outgoing', $data_clnt_outgoing);
                    $clnt_outgoing_id = $this->db->insert_id();

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        $source = $source;
                        $destination = $client_destination;
                        $msg = $cleaned_msg;
                        $send_msg = $this->send_message($source, $destination, $msg, $clnt_outgoing_id);
                        if ($send_msg) {
                            echo 'Message sent success';
                        } else {
                            echo 'Message sending fialure';
                        }
                    }
                }



                //Get the explanatory msg
                $get_next_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='3' and logic_flow='2' and language_id='$lang' LIMIT 1")->result();
                foreach ($get_next_clnt_outgoing_msg as $value) {
                    $message = $value->message;
                    $message_type_id = $value->message_type_id;
                    echo 'Message #2 queued ....' . $message . '</br>';
                    $this->db->trans_start();
                    $data_clnt_outgoing = array(
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
                    $this->db->insert('clnt_outgoing', $data_clnt_outgoing);
                    $clnt_outgoing_id = $this->db->insert_id();
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        $source = $source;
                        $destination = $client_destination;
                        $msg = $cleaned_msg;
                        $send_msg = $this->send_message($source, $destination, $msg, $clnt_outgoing_id);
                        if ($send_msg) {
                            echo 'Message sent success';
                        } else {
                            echo 'Message sending fialure';
                        }
                    }
                }





                //Check based on enrollment date if client meets the  condition of the  thid message ....

                $current_date = date("Y-m-d");
                $current_date = date_create($current_date);
                $enrollment_date2 = date_create($enrollment_date);
                $date_diff = date_diff($enrollment_date2, $current_date);

                $diff = $date_diff->format("%R%a days");
                // // // // echo 'Date Difference => ' . $diff;
                $diff = substr($diff, 1, 2);
                if ($diff >= 30) {
                    
                } elseif ($diff <= 30) {





                    // $message_type_id = 3;
                    // $logic_flow = 3;
                    //  $target_group = 'All';

                    $get_welcome_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='3' and language_id='$lang' LIMIT 1")->result();
                    foreach ($get_welcome_msg as $value) {
                        echo 'Message #3 queued ....';
                        $message = $value->message;
                        $message_type_id = $value->message_type_id;
                        $client_name = ucwords(strtolower($fname)) . " ";

                        $cleaned_msg = str_replace("XXX", $client_name, $message);
                        $created_at = date('Y-m-d H:i:s');
                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        $this->config->load('config', TRUE);
                        // Retrieve a config item named site_name contained within the blog_settings array
                        $source = $this->config->item('shortcode', 'config');


                        $client_destination = $phone_no;
                        echo $cleaned_msg . '<br>';
                        $this->db->trans_start();

                        $data_clnt_outgoing = array(
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
                        $this->db->insert('clnt_outgoing', $data_clnt_outgoing);

                        $clnt_outgoing_id = $this->db->insert_id();
                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            $source = $source;
                            $destination = $client_destination;
                            $msg = $cleaned_msg;
                            $send_msg = $this->send_message($source, $destination, $msg, $clnt_outgoing_id);
                            if ($send_msg) {
                                echo 'Message sent success';
                            } else {
                                echo 'Message sending fialure';
                            }
                        }
                    }
                }
            }
        }
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
                        //Ignore ...Do Nothing...... Role still mapped to the  right user in the system
                    } else {
                        //Role and Module does not exist in the user permissions table and it should be added to the  user permission tables
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
        }
    }

    function remove_unauth_modules() {





        /*
         * 
         * Option Two
         * Check if there are any modules in the  user permission that are not supposed to be there ....
         * This modules should be marked as in active
         */

        //Get user permission
        $get_user_permission = $this->db->query("SELECT * FROM tbl_user_permission WHERE STATUS='Active' LIMIT 200");

        $check_permission_existence = $get_user_permission->num_rows();
        if ($check_permission_existence > 0) {
            foreach ($get_user_permission->result() as $user_permission_value) {
                $user_permission_id = $user_permission_value->id;
                $user_permission_module_id = $user_permission_value->module_id;
                $user_permission_role_id = $user_permission_value->role_id;
                $check_role = $this->db->get_where('role_module', array('role_id' => $user_permission_role_id, 'module_id' => $user_permission_module_id, 'status' => 'Active'))->num_rows();
                if ($check_role > 0) {
                    //Ignore do nothing
                } else {
                    //Mark the  specific role as in active 
                    $this->db->trans_start();
                    $data_user_permission_update = array(
                        'status' => 'In Active',
                        'updated_by' => '1'
                    );
                    $this->db->where('id', $user_permission_id);
                    $this->db->update('user_permission', $data_user_permission_update);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        //Trnasaction Success

                        $description = "Un necessaryy modules were removed from User permission. User permission $user_permission_id was updated to In ACTIVE";
                        $user_id = '1';
                        $this->log_action($description, $user_id);
                    }
                }
            }
        }
    }

    function log_action($description, $user_id) {
        $this->db->trans_start();
        $today = date("Y-m-d H:i:s");


        $post_data = array(
            'user_id' => $user_id,
            'description' => $description,
            'created_at' => $today
        );
        $this->db->insert('sys_logs', $post_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {

            return TRUE;
        }
    }

    function clnt_to_upper() {
        $get_clients = $this->db->query("SELECT * FROM tbl_client  ")->result();
        foreach ($get_clients as $value) {
            $client_id = $value->id;
            $f_name = ucfirst(strtolower($value->f_name));
            $m_name = ucfirst(strtolower($value->m_name));
            $l_name = ucfirst(strtolower($value->l_name));

            $this->db->trans_start();

            $data_update = array(
                'f_name' => $f_name,
                'm_name' => $m_name,
                'l_name' => $l_name
            );
            $this->db->where('id', $client_id);
            $this->db->update('client', $data_update);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                
            } else {
                
            }
        }
    }

    function text_time_mapping() {
        $geT_clients = $this->db->query("Select * from tbl_client where id between 2857 and 3087")->result();
        foreach ($geT_clients as $value) {

            $client_id = $value->id;
            $data_update = array(
                'txt_time' => '17'
            );
            $this->db->where('id', $client_id);
            $this->db->update('client', $data_update);
        }
    }

    function date_converter() {
        //$query = $this->db->get_where('client',array());
        $query = $this->db->query("Select * from tbl_client where  entry_point IS NULL ");

        foreach ($query->result() as $row) {
            $dob = $row->dob;
            $client_id = $row->id;

            $date = str_replace('/', '-', $dob);
            $new_date = date('Y-m-d', strtotime($date));
            if ($new_date == "1970-01-01") {
                
            } else {
                $data_update = array(
                    'dob' => $new_date
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $data_update);
            }
        }
    }

    function send_user_notification() {
        #1 => GET USERS AT THE  FACILITY LEVEL
        #2 => GET APPOINTMENTS BASED ON THE  USER ACCESS LEVELS
        #3 => GENERATE THE  OUT GOING MESSAGE AND QUEUE THE MESSAGE TO GO OUT EVERY MORNING.
        $get_users_facilities = $this->db->query("Select * from tbl_users where access_level='Facility' ")->result();
        foreach ($get_users_facilities as $value) {
            $user_id = $value->id;
            $user_phone_no = $value->phone_no;
            $user_name = ' ' . $value->f_name . ' ' . $value->m_name . ' ' . $value->l_name . ' ';
            $mfl_code = $value->facility_id;
            $all_appointments = $this->count_all_appointments($mfl_code);
            $current_appointments = $this->count_current_appointments($mfl_code);
            $missed_appointments = $this->count_missed_appointments($mfl_code);
            $defaulted_appiointments = $this->count_defaulted_appointments($mfl_code);
            $ltfu_appointments = $this->count_LTFU_appointments($mfl_code);
            $todays_appointments = $this->count_Today_appointments($mfl_code);

            $msg = "Good Morning $user_name, "
                    . "Thank you for using T4A , Below is information of your facility performance ,  "
                    . "No of Appointments : $all_appointments , "
                    . "No of Current Appointments : $current_appointments ,"
                    . "No of missed Appointments : $missed_appointments , "
                    . "No of Defaulted Appointments : $defaulted_appiointments ,"
                    . "No of LTFU Appointments : $ltfu_appointments ,"
                    . "No of Today Appointments : $todays_appointments ,"
                    . "Thank you for using T4A : Your Friendly Reminder . ";

            $today = date('Y-m-d H:i:s');


            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
            $this->config->load('config', TRUE);
            // Retrieve a config item named site_name contained within the blog_settings array
            $source = $this->config->item('shortcode', 'config');


            $outgoing = array(
                'destination' => $user_phone_no,
                'msg' => $msg,
                'responded' => 'No',
                'status' => 'Not Sent',
                'message_type_id' => '5',
                'source' => $source,
                'created_at' => $today,
                'clnt_usr_id' => $user_id,
                'recepient_type' => "User",
                'updated_by' => '1'
            );
            $this->db->insert('usr_outgoing', $outgoing);
        }
    }

    function count_all_appointments($mfl_code) {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment "
                        . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id inner join "
                        . " tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                        . " where active_app is NOT NULL AND tbl_partner_facility.mfl_code='$mfl_code'")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    function count_current_appointments($mfl_code) {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
                        . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id inner join"
                        . " tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                        . " where active_app='1' and appntmnt_date > CURDATE() AND tbl_partner_facility.mfl_code='$mfl_code'")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    function count_missed_appointments($mfl_code) {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
                        . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
                        . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                        . " where active_app='1' and appntmnt_date < CURDATE() and app_status='Missed' AND tbl_partner_facility.mfl_code='$mfl_code'")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    function count_defaulted_appointments($mfl_code) {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment  "
                        . "inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
                        . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                        . " where active_app='1' and appntmnt_date < CURDATE() and app_status='Defaulted' AND tbl_partner_facility.mfl_code='$mfl_code'")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    function count_LTFU_appointments($mfl_code) {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment"
                        . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
                        . "inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code"
                        . " where active_app='1' and appntmnt_date < CURDATE() and app_status='LTFU' AND tbl_partner_facility.mfl_code='$mfl_code' ")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    function count_Today_appointments($mfl_code) {
        $request_type = $this->uri->segment(3);
        $query = $this->db->query("Select count(tbl_appointment.id)as num from tbl_appointment "
                        . " inner join tbl_client on tbl_client.id = tbl_appointment.client_id "
                        . " inner join tbl_partner_facility on tbl_partner_facility.mfl_code = tbl_client.mfl_code "
                        . " where active_app='1' and appntmnt_date = CURDATE() AND tbl_partner_facility.mfl_code='$mfl_code' ")->result();

        foreach ($query as $value) {
            $num = $value->num;
            if ($request_type === 'JSON') {
                echo json_encode($num);
            } else {
                return $num;
            }
        }
    }

    function today_appointments() {
        /*

         * Get user facility users from the  system
         * Get Appointments based on the  facility user
         * Send them to the  facility user phone
         *          */

        $created_at = date('Y-m-d H:i:s');

        $get_facility_users = $this->db->query(" SELECT
	facility_id,
	phone_no,
	tbl_users.id ,tbl_users.clinic_id
FROM
	tbl_users
	INNER JOIN tbl_role ON tbl_role.id = tbl_users.role_id 
WHERE
	tbl_users.access_level = 'Facility' 
	AND rcv_app_list = 'Yes' 
	AND tbl_role.`name` NOT LIKE '%Tracer%'  ")->result();
        foreach ($get_facility_users as $result) {
            $facility_id = $result->facility_id;
            $phone_no = $result->phone_no;
            $mfl_code = $result->facility_id;
            $user_id = $result->id;
            $clinic_id = $result->clinic_id;


            $get_today_appointments = $this->db->query("
            SELECT tbl_appointment.id as appointment_id,
            tbl_client.`clinic_number` AS CCC,
            CONCAT(
              tbl_client.f_name,
              ' ',
              tbl_client.l_name
            ) AS client_name,
            tbl_client.phone_no,
            tbl_appointment_types.`name` AS appointment_type,
            tbl_appointment.`appntmnt_date`,
            tbl_appointment.`created_at` ,tbl_client.file_no,tbl_client.buddy_phone_no
          FROM
            tbl_client 
            INNER JOIN tbl_appointment 
              ON tbl_appointment.`client_id` = tbl_client.`id` 
            INNER JOIN tbl_appointment_types 
              ON tbl_appointment_types.`id` = tbl_appointment.`app_type_1` 
            INNER JOIN tbl_users 
              ON tbl_client.`mfl_code` = tbl_users.`facility_id` 
          WHERE DATE(
              tbl_appointment.`appntmnt_date`
            ) = CURDATE() 
            AND tbl_appointment.`active_app` = '1' 
            AND tbl_users.`access_level` = 'Facility'  
  AND tbl_client.mfl_code = '$facility_id' AND tbl_client.clinic_id='$clinic_id'
GROUP BY tbl_appointment.`id`   ")->result();
            foreach ($get_today_appointments as $value) {
                $appointment_id = $value->appointment_id;
                $CCC = $value->CCC;
                $client_name = $value->client_name;
                $client_phone_no = $value->phone_no;
                $appointment_type = $value->appointment_type;
                $appointment_date = $value->appntmnt_date;
                $file_no = $value->file_no;
                $buddy_phone_no = $value->buddy_phone_no;
                echo $buddy_phone_no . '<br>';
                if ($buddy_phone_no == "" or empty($buddy_phone_no)) {
                    $trmnt_buddy_phone_no = '-1';
                } else {
                    $trmnt_buddy_phone_no = $buddy_phone_no;
                }
                echo 'Cleaned one => ' . $trmnt_buddy_phone_no . "<br>";
                $outgoing_msg = $CCC . "*" . $client_name . "*" . $client_phone_no . "*" . $appointment_type . "*" . $appointment_id . "*" . $file_no . "*" . $trmnt_buddy_phone_no;
                echo "Outgoing Msg => " . $outgoing_msg . " And Clinic ID => " . $clinic_id . "<br>";
                $encrypted_msg = "TOAPP*" . $this->encrypt($outgoing_msg);
                // echo $encrypted_msg . '<br>';
                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', TRUE);
                // Retrieve a config item named site_name contained within the blog_settings array
                $source = $this->config->item('shortcode', 'config');
                //$destination = $phone_no;
                $destination_no = $phone_no;


                echo "Phone No => " . $phone_no . "<br>";



                $check_existence = $this->db->get_where('usr_outgoing', array('msg' => $encrypted_msg, 'destination' => $destination_no))->num_rows();
                if ($check_existence > 0) {
                    echo "check existednce " . $check_existence . '<br>';
                } else {

                    echo "check existednce " . $check_existence . '<br>';

                    $useroutgoing = "User";
                    echo "Encrypted msg => " . $encrypted_msg . '<br>';

                    $send_text = $this->send_message($source, $destination_no, $encrypted_msg, $useroutgoing);
                    if ($send_text) {

                        echo ' Outgoing Msg => ' . $outgoing_msg . ' Destination phone no => ' . $destination_no . '<br>';




                        $this->db->trans_start();

                        $data_outgoing = array(
                            'destination' => $destination_no,
                            'source' => $source,
                            'msg' => $encrypted_msg,
                            'status' => 'Sent',
                            'message_type_id' => '5',
                            'responded' => 'No',
                            'clnt_usr_id' => $user_id,
                            'recepient_type' => 'User',
                            'created_at' => $created_at,
                            'created_by' => '1'
                        );
                        $this->db->insert('usr_outgoing', $data_outgoing);


                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            
                        }
                    } else {
                        
                    }
                }
            }
        }

        $this->ushauri_msgs();
    }

    function encrypt($decoded_msg) {
        $encoded_str = base64_encode($decoded_msg);

        return $encoded_str;
    }

    function decrypt($encoded_msg) {
        $decoded_str = base64_decode($encoded_msg);

        return $decoded_str;
    }

    protected function hex2bin($hexdata) {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }

    function trigger() {
        $this->wellness_trigger();
    }

    function wellness_trigger() {
        //Get All Clients who have consented to receive SMS


        $get_day_of_week = date('N') + 1;
        if ($get_day_of_week > 7) {
            $day_of_week = $get_day_of_week - 1;
        } else {
            $day_of_week = $get_day_of_week;
        }
        $get_clients = $this->db->query("  SELECT 
  tbl_groups.name AS group_name,
  tbl_groups.id AS group_id,
  tbl_language.name AS language_name,
  tbl_language.id AS language_id,
  f_name,
  m_name,
  l_name,
  dob,
  tbl_client.status,
  phone_no,
  tbl_client.created_at AS registration_date,
  tbl_client.txt_time,
  tbl_client.txt_frequency,
  tbl_client.updated_at,
  tbl_client.id AS client_id,
  tbl_time.name AS daytime,
  wellness_enable,
  motivational_enable 
FROM
  tbl_client 
  INNER JOIN tbl_language 
    ON tbl_language.id = tbl_client.language_id 
  INNER JOIN tbl_groups 
    ON tbl_groups.id = tbl_client.group_id 
  INNER JOIN tbl_time 
    ON tbl_time.id = tbl_client.txt_time 
WHERE tbl_client.status = 'Active' 
  AND tbl_client.smsenable = 'Yes' 
  AND tbl_client.wellness_enable = 'YES'  
  AND DAYOFWEEK(tbl_client.`created_at`) = $day_of_week
GROUP BY tbl_client.id   ")->result();

        print_r($get_clients);
        foreach ($get_clients as $value) {
            $client_id = $value->client_id;
            $language = $value->language_name;
            $group_id = $value->group_id;
            $language_id = $value->language_id;
            $registration_date = $value->registration_date;
            $time = $value->txt_time;
            $daytime = $value->daytime;
            $frequency = $value->txt_frequency;
            $phone_no = $value->phone_no;
            $wellness_enable = $value->wellness_enable;
            $motivational_enable = $value->motivational_enable;
            //Create the  Number of Hours that we should check on the  client
            $new_frequency = "+" . $frequency . " Hours";

            //Check if the  phone number has country code appended , if not appended , please append the  country code . e.g 07123456 => 254712345678
            $mobile = substr($phone_no, -9);
            $len = strlen($mobile);
            if ($len < 10) {
                $phone_no = "254" . $mobile;
            }



            // Chceck if Wellness check is enabled , If enabled , go through successfully.
            if ($wellness_enable == 'Yes') {

                //Check if previous clnt_outgoing wellness message under client phone number exists in the  sent logs and it was successful , this helps with controlling of spamming. 
                $get_clnt_outgoing_arch = $this->db->query("Select * from tbl_clnt_outgoing where message_type_id='4' and destination='$phone_no' AND created_at = (SELECT MAX(`created_at`) FROM tbl_clnt_outgoing AS b WHERE b.`destination`='$phone_no') GROUP BY destination LIMIT 1");
                $check_wellness_existense = $get_clnt_outgoing_arch->num_rows();

                if ($check_wellness_existense == 1) {

                    //Get the  previous clnt_outgoing message date time
                    // and compare it with the  current out going message time
                    //  from the system, this helps with controlling of spamming
                    //   in the system. If the current date and message date match ,
                    //    then this message was sent today and no otehr message should
                    //     go out today again, since this will be spamming the clients
                    $get_clnt_outgoing_arch_message = $get_clnt_outgoing_arch->result();

                    foreach ($get_clnt_outgoing_arch_message as $clnt_outgoing_arch_value) {
                        $row_id = $clnt_outgoing_arch_value->id;

                        $created_at = $clnt_outgoing_arch_value->created_at;
                        $message_type_id = $clnt_outgoing_arch_value->message_type_id;
                        $current_date = date("Y-m-d");

                        $last_msg_Timestamp = strtotime($created_at);
                        $last_message_date = date("Y-m-d", $last_msg_Timestamp);
                        //If last message date and current date are the same , do not send message 
                        //echo 'Client ID' . $client_id . '</br>';
                        if ($last_message_date == $current_date) {
                            echo 'Last message date and current date are matching so no clnt_outgoing message for phone No ' . $phone_no . '</br>';
                            //If the  date time in the  sent are the  same , do nothing
                        } else {
                            echo 'Last message date and current date do not match for Phone No : => ' . $phone_no . '</br>';
                            //From the previous out going message , Get the  next message day  
                            $MsgUnixTimestamp = strtotime($new_frequency, strtotime($created_at));
                            //Get the  Message day of the week using PHP's date function.
                            $message_day = date("N", $MsgUnixTimestamp);


                            // From PHP function , get the  current day of the  week from the PHP function 
                            // Get the current day of the week using PHP's date function.
                            $current_datetime = date("Y-m-d");
                            $current_unix_timestamp = strtotime($current_datetime);
                            $current_day = date("N", $current_unix_timestamp);

                            //From the client's preference for messages , get the  Hour the  client prefers to get the 
                            // message e.g 08:00 will give 08 

                            $message_hour = substr($daytime, 0, 2);
                            //Get the current hour the  message should be going outside
                            $current_hour = date("H");
                            echo 'Message Day => ' . $message_day . '<br> Current Day => ' . $current_day . '<br> Current Hour =.>' . $current_hour . ' <br> Message Hour => ' . $message_hour . '<br>';
                            //Check the  condition if the  current day is the  same as the  message day and
                            // current hour is the  same as message hour , it's the  same then get clnt_outgoing message

                            if ($current_hour == $message_hour) {
                                //Get out going message ... based on client's preferences



                                $check_msg_existence = $this->db->query("SELECT * FROM tbl_clnt_outgoing WHERE destination='$phone_no' AND DATE(created_at) = CURDATE()")->num_rows();
                                if ($check_msg_existence > 0) {
                                    
                                } else {


                                    $content_options = array(
                                        'table' => 'content',
                                        'where' => array('language_id' => $language_id, 'group_id' => $group_id, 'message_type_id' => $message_type_id)
                                    );
                                    $get_content = $this->data->commonGet($content_options);

                                    foreach ($get_content as $value) {
                                        $content = $value->content;
                                        echo 'Content => ' . $content;
                                        $content_id = $value->id;
                                        $status = "Not Sent";
                                        $responded = "No";
                                        $today = date("Y-m-d H:i:s");
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');
                                        $message_type_id = 2;
                                        $this->db->trans_start();
                                        $clnt_outgoing = array(
                                            'destination' => $phone_no,
                                            'msg' => $content,
                                            'responded' => $responded,
                                            'status' => $status,
                                            'message_type_id' => $message_type_id,
                                            'source' => $source,
                                            'clnt_usr_id' => $client_id,
                                            'recepient_type' => 'Client',
                                            'content_id' => $content_id,
                                            'created_at' => $today
                                        );
                                        $this->db->insert('clnt_outgoing', $clnt_outgoing);
                                        $this->db->trans_complete();
                                        if ($this->db->trans_status() === FALSE) {
                                            
                                        } else {
                                            
                                        }
                                    }
                                }
                            } else {
                                //Do Nothing ...
                            }
                        }
                    }
                } else {


                    #SCENARIO 2 = > This works in a situation where the client is still new the system and no Adherence message has ever been sent out to him/her 
                    //Assumption , we use the  registration date in the  System to determine the  next message that will be going out. 
                    //Current date 
                    $current_date = date("Y-m-d");

                    //Convert the  registration date time to registration date i.e from Y-m-d H:i:s 
                    //To Y-m-d

                    $registration_timestamp = strtotime($registration_date);
                    $registration_check_date = date("Y-m-d", $registration_timestamp);


//                    echo 'Registration Date => '.$registration_check_date.'<br>  Current Date => '.$current_date.'<br> ';
                    //Compare registration date with the  current date. 
                    if ($registration_check_date == $current_date) {
                        //If the  same , no message should go out , ignore sending message
                        echo 'No messages going out ...';
                    } else {

                        //Get the  next message and schedule it for out going
                        //Convert the  registration date to unixtimestamp and from unixtiemstamp get the day of week

                        $MsgUnixTimestamp = strtotime($new_frequency, strtotime($registration_date));

                        //Get the  Message day of the week using PHP's date function.
                        $message_day = date("N", $MsgUnixTimestamp);


                        //Get the current day of the week using PHP's date function.
                        $current_datetime = date("Y-m-d");
                        $current_unix_timestamp = strtotime($current_datetime);
                        $current_day = date("N", $current_unix_timestamp);

                        //Get the  current hour the  client wants to receive the  message
                        $message_hour = substr($daytime, 0, 2);
                        //Get the current hour
                        $current_hour = date("H");




                        if ($current_hour == $message_hour) {
                            //Check the  condition if the  current day is the  same as the  message day and
                            // current hour is the  same as message hour , it's the  same then get clnt_outgoing message




                            echo 'START <br> ';
                            echo $client_id . '   Phone No => ' . $phone_no . '   Wellness =>   ' . $wellness_enable . '     Motivational =>   ' . $motivational_enable . '</br>';

                            echo "Current Day => " . $current_day . " AND Message Day " . $message_day . '<br>';
                            echo "Current Hour => " . $current_hour . " AND Message Hour " . $message_hour . '</br>';
                            echo 'END <BR>';


                            $message_type_id = 4;




                            $content_options = array(
                                'table' => 'content',
                                'where' => array('language_id' => $language_id, 'group_id' => $group_id, 'message_type_id' => $message_type_id)
                            );
                            $get_content = $this->data->commonGet($content_options);

                            foreach ($get_content as $value) {
                                echo 'Phone No ' . $phone_no . '</br> Mesage Day ' . $message_day . '</br> Current Day : ' . $current_day . '</br>';

                                $content = $value->content;
                                echo 'Content => ' . $content;
                                $content_id = $value->id;
                                $status = "Not Sent";
                                $responded = "No";
                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $message_type_id = 2;
                                $today = date("Y-m-d H:i:s");
                                $check_msg_existence = $this->db->query("SELECT * FROM tbl_clnt_outgoing WHERE destination='$phone_no' AND DATE(created_at) = CURDATE()")->num_rows();
                                if ($check_msg_existence > 0) {
                                    
                                } else {
                                    $this->db->trans_start();
                                    $clnt_outgoing = array(
                                        'destination' => $phone_no,
                                        'msg' => $content,
                                        'responded' => $responded,
                                        'status' => $status,
                                        'message_type_id' => $message_type_id,
                                        'source' => $source,
                                        'clnt_usr_id' => $client_id,
                                        'recepient_type' => 'Client',
                                        'content_id' => $content_id,
                                        'created_at' => $today
                                    );
                                    $this->db->insert('clnt_outgoing', $clnt_outgoing);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() === FALSE) {
                                        
                                    } else {
                                        
                                    }
                                }
                            }
                        } else {
                            //Do Nothing ...
                        }
                    }
                }
            } else {
                //Do Nothing
            }
        }


        //
    }

    function process_missed_actions($response_id) {
        /*

         * Process all missed appointments Actions for Personal Call
         * Treatment Supporter Called
         * Physical Tracing 
         *          */


        $today = date("Y-m-d H:i:s");
        //Get all the unprocessed missed actions from the  user phone. 
        $sql = "SELECT 
  * 
FROM
  tbl_responses 
WHERE msg LIKE '%MSD%' 
  AND processed = 'No' and id ='$response_id' GROUP BY msg ";
        $query1 = $this->db->query($sql)->result();

        foreach ($query1 as $value) {


            $process_id = $value->id;
            $user_source = $value->source;
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;


            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;






                //Explode the  incoming message into two chunks usning the  * identifier
                $explode_msg = explode("*", $encrypted_msg);
                $identifier = @$explode_msg[0];
                $resp_message = @$explode_msg[1];
                //Decrypt the  incoming message and pre-append it back to the  orignial message template. 


                $descrypted_msg = $this->decrypt($resp_message);

                $new_msg = $identifier . "*" . $descrypted_msg;
                //echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                //echo 'Msg => '.$message.'AND Identifier => '.$identifier.'</br>';


                $msg = $new_msg;


                // echo 'New Message => ' . $new_msg . '<br>';



                $mobile = substr($user_source, -9);
                $len = strlen($mobile);


                $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                $this->db->query($sql);





                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }
                // // // echo  'New From : ' . $user_source . '</br>';
                //Check if User is authoriesed to perform transactions in the  system. 
                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility' GROUP BY phone_no ");
                $user_exists = $get_facility->num_rows();

                if ($user_exists >= 1) {

                    //If user exists, proceed with processing the decrypted message. 
                    $user_details = $get_facility->result();

                    foreach ($user_details as $value) {
                        $user_id = $value->id;

                        /*
                          Explode the message using the * keyword , get the  first strip of the  message
                          Using the  first strip , to check the type of message if it's a call action or  a home visit action.
                         */
                        $split_message = explode('*', $msg);
                        $code = $split_message[0];
                        if (strpos($code, 'C') !== false) {

                            /**
                             * Break down the message to the  values 
                             * The message should contain the  following values : 
                             * Clinic Number , Old Appointment Type , New Appointment Type, Call Date , Outcome,  Appointment Date,  Tracer Name , Final Outcome
                             */
                            $clinic_number = @$split_message[1];
                            $old_appointment_type = @$split_message[2];
                            $new_appointment_type = @$split_message[3];
                            $call_date = @$split_message[4];
                            $outcome = @$split_message[5];
                            $app_date = @$split_message[6];
                            $tracer_name = @$split_message[7];
                            $final_outcome = @$split_message[8];




                            /*                             * *
                             * Check if the  client exists in the  system , if not found , then end the  transaction and send back mesage of Client not Found in the  system
                             */
                            $query2 = $this->db->query("Select * from tbl_client where clinic_number = '$clinic_number' LIMIT 1");
                            $check_num_rows = $query2->num_rows();
                            if ($check_num_rows > 0) {

                                foreach ($query2->result() as $value) {
                                    $client_id = $value->id;
                                    $language_id = $value->language_id;
                                    $client_name = $value->f_name . ' ' . $value->m_name . ' ' . $value->l_name;

                                    /**
                                     * Get the  appointment from the system based on the Active Appointment (this is determined by the  active_app = 1 and appointment date should be less than today. )
                                     * The  appointment type should be the  same as what is recorded in the  DBase
                                     * If Appointment is found , then proceed to adding/ updating the  appointment outcome in the  tbl_clnt_outcome 
                                     */
                                    $get_current_appointment = "Select * from tbl_appointment where client_id='$client_id' and app_type_1='$old_appointment_type' and active_app='1' and `appntmnt_date` < CURDATE() LIMIT 1";



                                    $query3 = $this->db->query($get_current_appointment);
                                    $check_num_rows = $query3->num_rows();
                                    if ($check_num_rows > 0) {
                                        foreach ($query3->result() as $value) {
                                            $client_id = $value->client_id;
                                            $appointment_id = $value->id;
                                            //Get the  curent actual outcome   
                                            $app_status = $value->app_status;
                                            $no_calls = $value->no_calls;
                                            $no_msgs = $value->no_msgs;
                                            $home_visits = $value->home_visits;
                                            $this->db->trans_start();

                                            $insert_outcome = array(
                                                'client_id' => $client_id,
                                                'appointment_id' => $appointment_id,
                                                'outcome' => $outcome,
                                                'tracer_name' => $tracer_name,
                                                'created_by' => $user_id,
                                                'tracing_type' => '1',
                                                'app_status' => $app_status,
                                                'tracing_date' => $call_date
                                            );
                                            $this->db->insert('clnt_outcome', $insert_outcome);



                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {


                                                /**
                                                 * Update Appointment row with the  tracing type of client called .
                                                 * Based on the number of updated made .....
                                                 * 
                                                 */
                                                $this->db->trans_start();


                                                $no_calls = $no_calls + 1;
                                                $update_appointment = array(
                                                    'no_calls' => $no_calls
                                                );
                                                $this->db->where('id', $appointment_id);
                                                $this->db->update('appointment', $update_appointment);





                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {




                                                    if ($final_outcome == "NULL" or empty($final_outcome)) {


                                                        $created_at = date('Y-m-d H:i:s');
                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');

                                                        $destination = '0' . $mobile;

                                                        $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                        $data_outgoing = array(
                                                            'destination' => $destination,
                                                            'source' => $source,
                                                            'msg' => $message,
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                                        $send_text = $this->send_message($source, $destination, $message);

                                                        if ($send_text) {
                                                            echo 'SUCCESS';

                                                            $sql = "UPDATE tbl_responses
                                                     SET processed = 'Yes' WHERE id = '$process_id'";
                                                            $this->db->query($sql);
                                                        } else {
                                                            echo 'FALSE';
                                                        }
                                                    } else {

                                                        if ($final_outcome == 1) {
                                                            //Returned to care , close the  appointment and book a new appointment
                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);


                                                            $appointment_insert = array(
                                                                'app_status' => 'Booked',
                                                                'appntmnt_date' => $app_date,
                                                                'status' => 'Active',
                                                                'sent_status' => 'Sent',
                                                                'client_id' => $client_id,
                                                                'created_at' => $today,
                                                                'created_by' => $user_id,
                                                                'app_type_1' => $new_appointment_type,
                                                                'entry_point' => 'Mobile',
                                                                'visit_type' => 'Scheduled',
                                                                'active_app' => '1'
                                                            );

                                                            $this->db->insert('appointment', $appointment_insert);
                                                        } elseif ($final_outcome == 2) {
                                                            //Self Transfer , close appointment and mark clients as self transfer

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Self Transfer'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 3) {
                                                            //Dead , close appointment and mark clients as Dead


                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Dead'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 4) {
                                                            //Challenging Client , leave appointment as open and mark clients as challenging 
                                                        } elseif ($final_outcome == 5) {
                                                            //Too Sick to attend , leave appointment as open and follow up later with the  client.

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);
                                                        }
                                                        //Update Appointment with the Final Outcome

                                                        $this->db->trans_start();
                                                        $update_appointment = array(
                                                            'fnl_trcing_outocme' => $final_outcome,
                                                            'visit_type' => 'Scheduled',
                                                            'fnl_outcome_dte' => $today
                                                        );
                                                        $this->db->where('id', $appointment_id);
                                                        $this->db->update('appointment', $update_appointment);
                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {


                                                            $created_at = date('Y-m-d H:i:s');
                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');

                                                            $destination = '0' . $mobile;

                                                            $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                            $data_outgoing = array(
                                                                'destination' => $destination,
                                                                'source' => $source,
                                                                'msg' => $message,
                                                                'status' => 'Sent',
                                                                'message_type_id' => '5',
                                                                'responded' => 'No',
                                                                'recepient_type' => 'User',
                                                                'created_at' => $created_at
                                                            );
                                                            $this->db->insert('usr_outgoing', $data_outgoing);

                                                            $send_text = $this->send_message($source, $destination, $message);

                                                            if ($send_text) {
                                                                echo 'SUCCESS';

                                                                $sql = "UPDATE tbl_responses
                                                        SET processed = 'Yes' WHERE id = '$process_id'";
                                                                $this->db->query($sql);
                                                            } else {
                                                                echo 'FALSE';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $created_at = date('Y-m-d H:i:s');
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');

                                        $destination = '0' . $mobile;

                                        $message = "The speified appointment for Clinic number: $clinic_number  does not exist in the System  ";
                                        $data_outgoing = array(
                                            'destination' => $destination,
                                            'source' => $source,
                                            'msg' => $message,
                                            'status' => 'Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                        $send_text = $this->send_message($source, $destination, $message);

                                        if ($send_text) {
                                            echo 'SUCCESS';

                                            $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                            $this->db->query($sql);
                                        } else {
                                            echo 'FALSE';
                                        }
                                    }
                                }
                            } else {



                                $created_at = date('Y-m-d H:i:s');
                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');

                                $destination = '0' . $mobile;

                                $message = "Clinic number: $clinic_number does not exist in the System  ";
                                $data_outgoing = array(
                                    'destination' => $destination,
                                    'source' => $source,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);

                                $send_text = $this->send_message($source, $destination, $message);

                                if ($send_text) {
                                    echo 'SUCCESS';


                                    $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                    $this->db->query($sql);
                                } else {
                                    echo 'FALSE';
                                }
                            }
                        } else if (strpos($code, 'V') !== false) {



                            $clinic_number = @$split_message[1];
                            $old_appointment_type = @$split_message[2];
                            $new_appointment_type = @$split_message[3];
                            $call_date = @$split_message[4];
                            $outcome = @$split_message[5];
                            $app_date = @$split_message[6];
                            $tracer_name = @$split_message[7];
                            $final_outcome = @$split_message[8];




                            $query2 = $this->db->query("Select * from tbl_client where clinic_number = '$clinic_number' LIMIT 1");
                            $check_num_rows = $query2->num_rows();
                            if ($check_num_rows > 0) {
                                echo 'Clinic Number was Found...';
                                foreach ($query2->result() as $value) {
                                    $client_id = $value->id;
                                    $language_id = $value->language_id;
                                    $client_name = $value->f_name . ' ' . $value->m_name . ' ' . $value->l_name;
                                    $get_current_appointment = "Select * from tbl_appointment where client_id='$client_id' and app_type_1='$old_appointment_type' and active_app='1' and `appntmnt_date` < CURDATE() LIMIT 1";



                                    $query3 = $this->db->query($get_current_appointment);
                                    $check_num_rows = $query3->num_rows();
                                    if ($check_num_rows > 0) {
                                        foreach ($query3->result() as $value) {
                                            $client_id = $value->client_id;
                                            $appointment_id = $value->id;
                                            //Get the  curent actual outcome   
                                            $app_status = $value->app_status;

                                            $this->db->trans_start();

                                            $insert_outcome = array(
                                                'client_id' => $client_id,
                                                'appointment_id' => $appointment_id,
                                                'outcome' => $outcome,
                                                'tracer_name' => $tracer_name,
                                                'created_by' => $user_id,
                                                'tracing_type' => '3',
                                                'app_status' => $app_status,
                                                'tracing_date' => $call_date
                                            );
                                            $this->db->insert('clnt_outcome', $insert_outcome);

                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {

                                                /**
                                                 * Update Appointment row with the  tracing type of client called .
                                                 * Based on the number of updated made .....
                                                 * 
                                                 */
                                                $this->db->trans_start();


                                                $no_calls = $no_calls + 1;
                                                $update_appointment = array(
                                                    'no_calls' => $no_calls
                                                );
                                                $this->db->where('id', $appointment_id);
                                                $this->db->update('appointment', $update_appointment);





                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {



                                                    if ($final_outcome == "NULL" or empty($final_outcome)) {


                                                        $created_at = date('Y-m-d H:i:s');
                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');

                                                        $destination = '0' . $mobile;

                                                        $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                        $data_outgoing = array(
                                                            'destination' => $destination,
                                                            'source' => $source,
                                                            'msg' => $message,
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                                        $send_text = $this->send_message($source, $destination, $message);

                                                        if ($send_text) {
                                                            echo 'SUCCESS';

                                                            $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                                            $this->db->query($sql);
                                                        } else {
                                                            echo 'FALSE';
                                                        }
                                                    } else {

                                                        if ($final_outcome == 1) {
                                                            //Returned to care , close the  appointment and book a new appointment
                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);


                                                            $appointment_insert = array(
                                                                'app_status' => 'Booked',
                                                                'appntmnt_date' => $app_date,
                                                                'status' => 'Active',
                                                                'sent_status' => 'Sent',
                                                                'client_id' => $client_id,
                                                                'created_at' => $today,
                                                                'created_by' => $user_id,
                                                                'app_type_1' => $new_appointment_type,
                                                                'entry_point' => 'Mobile',
                                                                'visit_type' => 'Scheduled',
                                                                'active_app' => '1'
                                                            );

                                                            $this->db->insert('appointment', $appointment_insert);
                                                        } elseif ($final_outcome == 2) {
                                                            //Self Transfer , close appointment and mark clients as self transfer

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Self Transfer'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 3) {
                                                            //Dead , close appointment and mark clients as Dead


                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Dead'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 4) {
                                                            //Challenging Client , leave appointment as open and mark clients as challenging 
                                                        } elseif ($final_outcome == 5) {
                                                            //Too Sick to attend , leave appointment as open and follow up later with the  client.

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);
                                                        }
                                                        //Update Appointment with the Final Outcome

                                                        $this->db->trans_start();
                                                        $update_appointment = array(
                                                            'fnl_trcing_outocme' => $final_outcome,
                                                            'visit_type' => 'Scheduled',
                                                            'fnl_outcome_dte' => $today
                                                        );
                                                        $this->db->where('id', $appointment_id);
                                                        $this->db->update('appointment', $update_appointment);
                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {


                                                            $created_at = date('Y-m-d H:i:s');
                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');

                                                            $destination = '0' . $mobile;

                                                            $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                            $data_outgoing = array(
                                                                'destination' => $destination,
                                                                'source' => $source,
                                                                'msg' => $message,
                                                                'status' => 'Sent',
                                                                'message_type_id' => '5',
                                                                'responded' => 'No',
                                                                'recepient_type' => 'User',
                                                                'created_at' => $created_at
                                                            );
                                                            $this->db->insert('usr_outgoing', $data_outgoing);

                                                            $send_text = $this->send_message($source, $destination, $message);

                                                            if ($send_text) {
                                                                echo 'SUCCESS';

                                                                $sql = "UPDATE tbl_responses
                                                        SET processed = 'Yes' WHERE id = '$process_id'";
                                                                $this->db->query($sql);
                                                            } else {
                                                                echo 'FALSE';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $created_at = date('Y-m-d H:i:s');
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');

                                        $destination = '0' . $mobile;

                                        $message = "The specified appointment for Clinic number: $clinic_number  does not exist in the System  ";
                                        $data_outgoing = array(
                                            'destination' => $destination,
                                            'source' => $source,
                                            'msg' => $message,
                                            'status' => 'Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                        $send_text = $this->send_message($source, $destination, $message);

                                        if ($send_text) {
                                            echo 'SUCCESS';

                                            $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                            $this->db->query($sql);
                                        } else {
                                            echo 'FALSE';
                                        }
                                    }
                                }
                            } else {



                                $created_at = date('Y-m-d H:i:s');
                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');

                                $destination = '0' . $mobile;

                                $message = "Clinic number: $clinic_number does not exist in the System  ";
                                $data_outgoing = array(
                                    'destination' => $destination,
                                    'source' => $source,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);

                                $send_text = $this->send_message($source, $destination, $message);

                                if ($send_text) {
                                    echo 'SUCCESS';


                                    $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                    $this->db->query($sql);
                                } else {
                                    echo 'FALSE';
                                }
                            }
                        }
                    }
                } else {
                    echo 'User Not Found ....';
                    $created_at = date('Y-m-d H:i:s');
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');

                    $destination = '0' . $mobile;
                    $this->db->trans_start();
                    $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'User',
                        'created_at' => $created_at
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);

                    $usr_otgoing_id = "User";
                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                    if ($send_text) {
                        echo 'SUCCESS';
                    } else {
                        echo 'FALSE';
                    }

                    $response_update = array(
                        'processed' => 'Yes'
                    );
                    $this->db->where('id', $process_id);
                    $this->db->update('responses', $response_update);
                }
            } else {
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;



                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $process_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_defaulted_actions($response_id) {
        /*

         * Process all defaulted appointments Actions for Personal Call
         * Treatment Supporter Called
         * Physical Tracing 
         *          */


        $today = date("Y-m-d H:i:s");
        //Get all the unprocessed missed actions from the  user phone. 
        $sql = "SELECT 
  * 
FROM
  tbl_responses 
WHERE msg LIKE '%MSD%' 
  AND processed = 'No' and id='$response_id' ";
        $query1 = $this->db->query($sql)->result();

        foreach ($query1 as $value) {


            $process_id = $value->id;
            $user_source = $value->source;
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;




            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {



                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;

                //Explode the  incoming message into two chunks usning the  * identifier
                $explode_msg = explode("*", $encrypted_msg);
                $identifier = @$explode_msg[0];
                $message = @$explode_msg[1];
                //Decrypt the  incoming message and pre-append it back to the  orignial message template. 


                $descrypted_msg = $this->decrypt($message);

                $new_msg = $identifier . "*" . $descrypted_msg;
                //echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                //echo 'Msg => '.$message.'AND Identifier => '.$identifier.'</br>';


                $msg = $new_msg;


                // echo 'New Message => ' . $new_msg . '<br>';



                $mobile = substr($user_source, -9);
                $len = strlen($mobile);

                if ($len = 9) {

                    $user_source = "0" . $mobile;
                }


                //Check if User is authoriesed to perform transactions in the  system. 
                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$user_source' and access_level='Facility'");
                $user_exists = $get_facility->num_rows();

                if ($user_exists >= 1) {

                    //If user exists, proceed with processing the decrypted message. 
                    $user_details = $get_facility->result();

                    foreach ($user_details as $value) {
                        $user_id = $value->id;

                        /*
                          Explode the message using the * keyword , get the  first strip of the  message
                          Using the  first strip , to check the type of message if it's a call action or  a home visit action.
                         */
                        $split_message = explode('*', $msg);
                        $code = $split_message[0];
                        if (strpos($code, 'C') !== false) {

                            /**
                             * Break down the message to the  values 
                             * The message should contain the  following values : 
                             * Clinic Number , Old Appointment Type , New Appointment Type, Call Date , Outcome,  Appointment Date,  Tracer Name , Final Outcome
                             */
                            $clinic_number = @$split_message[1];
                            $old_appointment_type = @$split_message[2];
                            $new_appointment_type = @$split_message[3];
                            $call_date = @$split_message[4];
                            $outcome = @$split_message[5];
                            $app_date = @$split_message[6];
                            $tracer_name = @$split_message[7];
                            $final_outcome = @$split_message[8];




                            /*                             * *
                             * Check if the  client exists in the  system , if not found , then end the  transaction and send back mesage of Client not Found in the  system
                             */
                            $query2 = $this->db->query("Select * from tbl_client where clinic_number = '$clinic_number' LIMIT 1");
                            $check_num_rows = $query2->num_rows();
                            if ($check_num_rows > 0) {

                                foreach ($query2->result() as $value) {
                                    $client_id = $value->id;
                                    $language_id = $value->language_id;
                                    $client_name = $value->f_name . ' ' . $value->m_name . ' ' . $value->l_name;

                                    /**
                                     * Get the  appointment from the system based on the Active Appointment (this is determined by the  active_app = 1 and appointment date should be less than today. )
                                     * The  appointment type should be the  same as what is recorded in the  DBase
                                     * If Appointment is found , then proceed to adding/ updating the  appointment outcome in the  tbl_clnt_outcome 
                                     */
                                    $get_current_appointment = "Select * from tbl_appointment where client_id='$client_id' and app_type_1='$old_appointment_type' and active_app='1' and `appntmnt_date` < CURDATE() LIMIT 1";



                                    $query3 = $this->db->query($get_current_appointment);
                                    $check_num_rows = $query3->num_rows();
                                    if ($check_num_rows > 0) {
                                        foreach ($query3->result() as $value) {
                                            $client_id = $value->client_id;
                                            $appointment_id = $value->id;
                                            //Get the  curent actual outcome   
                                            $app_status = $value->app_status;
                                            $no_calls = $value->no_calls;
                                            $no_msgs = $value->no_msgs;
                                            $home_visits = $value->home_visits;
                                            $this->db->trans_start();

                                            $insert_outcome = array(
                                                'client_id' => $client_id,
                                                'appointment_id' => $appointment_id,
                                                'outcome' => $outcome,
                                                'tracer_name' => $tracer_name,
                                                'created_by' => $user_id,
                                                'tracing_type' => '1',
                                                'app_status' => $app_status,
                                                'tracing_date' => $call_date
                                            );
                                            $this->db->insert('clnt_outcome', $insert_outcome);



                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {


                                                /**
                                                 * Update Appointment row with the  tracing type of client called .
                                                 * Based on the number of updated made .....
                                                 * 
                                                 */
                                                $this->db->trans_start();


                                                $no_calls = $no_calls + 1;
                                                $update_appointment = array(
                                                    'no_calls' => $no_calls
                                                );
                                                $this->db->where('id', $appointment_id);
                                                $this->db->update('appointment', $update_appointment);





                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {




                                                    if ($final_outcome == "NULL" or empty($final_outcome)) {


                                                        $created_at = date('Y-m-d H:i:s');
                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');

                                                        $destination = '0' . $mobile;

                                                        $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                        $data_outgoing = array(
                                                            'destination' => $destination,
                                                            'source' => $source,
                                                            'msg' => $message,
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                                        $send_text = $this->send_message($source, $destination, $message);

                                                        if ($send_text) {
                                                            echo 'SUCCESS';

                                                            $sql = "UPDATE tbl_responses
                                                     SET processed = 'Yes' WHERE id = '$process_id'";
                                                            $this->db->query($sql);
                                                        } else {
                                                            echo 'FALSE';
                                                        }
                                                    } else {

                                                        if ($final_outcome == 1) {
                                                            //Returned to care , close the  appointment and book a new appointment
                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);


                                                            $appointment_insert = array(
                                                                'app_status' => 'Booked',
                                                                'appntmnt_date' => $app_date,
                                                                'status' => 'Active',
                                                                'sent_status' => 'Sent',
                                                                'client_id' => $client_id,
                                                                'created_at' => $today,
                                                                'created_by' => $user_id,
                                                                'app_type_1' => $new_appointment_type,
                                                                'entry_point' => 'Mobile',
                                                                'visit_type' => 'Scheduled',
                                                                'active_app' => '1'
                                                            );

                                                            $this->db->insert('appointment', $appointment_insert);
                                                        } elseif ($final_outcome == 2) {
                                                            //Self Transfer , close appointment and mark clients as self transfer

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Self Transfer'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 3) {
                                                            //Dead , close appointment and mark clients as Dead


                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Dead'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 4) {
                                                            //Challenging Client , leave appointment as open and mark clients as challenging 
                                                        } elseif ($final_outcome == 5) {
                                                            //Too Sick to attend , leave appointment as open and follow up later with the  client.

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);
                                                        }
                                                        //Update Appointment with the Final Outcome

                                                        $this->db->trans_start();
                                                        $update_appointment = array(
                                                            'fnl_trcing_outocme' => $final_outcome,
                                                            'visit_type' => 'Scheduled',
                                                            'fnl_outcome_dte' => $today
                                                        );
                                                        $this->db->where('id', $appointment_id);
                                                        $this->db->update('appointment', $update_appointment);
                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {


                                                            $created_at = date('Y-m-d H:i:s');
                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');

                                                            $destination = '0' . $mobile;

                                                            $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                            $data_outgoing = array(
                                                                'destination' => $destination,
                                                                'source' => $source,
                                                                'msg' => $message,
                                                                'status' => 'Sent',
                                                                'message_type_id' => '5',
                                                                'responded' => 'No',
                                                                'recepient_type' => 'User',
                                                                'created_at' => $created_at
                                                            );
                                                            $this->db->insert('usr_outgoing', $data_outgoing);

                                                            $send_text = $this->send_message($source, $destination, $message);

                                                            if ($send_text) {
                                                                echo 'SUCCESS';

                                                                $sql = "UPDATE tbl_responses
                                                        SET processed = 'Yes' WHERE id = '$process_id'";
                                                                $this->db->query($sql);
                                                            } else {
                                                                echo 'FALSE';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $created_at = date('Y-m-d H:i:s');
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');

                                        $destination = '0' . $mobile;

                                        $message = "The speified appointment for Clinic number: $clinic_number  does not exist in the System  ";
                                        $data_outgoing = array(
                                            'destination' => $destination,
                                            'source' => $source,
                                            'msg' => $message,
                                            'status' => 'Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                        $send_text = $this->send_message($source, $destination, $message);

                                        if ($send_text) {
                                            echo 'SUCCESS';

                                            $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                            $this->db->query($sql);
                                        } else {
                                            echo 'FALSE';
                                        }
                                    }
                                }
                            } else {



                                $created_at = date('Y-m-d H:i:s');
                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');

                                $destination = '0' . $mobile;

                                $message = "Clinic number: $clinic_number does not exist in the System  ";
                                $data_outgoing = array(
                                    'destination' => $destination,
                                    'source' => $source,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);

                                $send_text = $this->send_message($source, $destination, $message);

                                if ($send_text) {
                                    echo 'SUCCESS';


                                    $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                    $this->db->query($sql);
                                } else {
                                    echo 'FALSE';
                                }
                            }
                        } else if (strpos($code, 'V') !== false) {



                            $clinic_number = @$split_message[1];
                            $old_appointment_type = @$split_message[2];
                            $new_appointment_type = @$split_message[3];
                            $call_date = @$split_message[4];
                            $outcome = @$split_message[5];
                            $app_date = @$split_message[6];
                            $tracer_name = @$split_message[7];
                            $final_outcome = @$split_message[8];




                            $query2 = $this->db->query("Select * from tbl_client where clinic_number = '$clinic_number' LIMIT 1");
                            $check_num_rows = $query2->num_rows();
                            if ($check_num_rows > 0) {
                                echo 'Clinic Number was Found...';
                                foreach ($query2->result() as $value) {
                                    $client_id = $value->id;
                                    $language_id = $value->language_id;
                                    $client_name = $value->f_name . ' ' . $value->m_name . ' ' . $value->l_name;
                                    $get_current_appointment = "Select * from tbl_appointment where client_id='$client_id' and app_type_1='$old_appointment_type' and active_app='1' and `appntmnt_date` < CURDATE() LIMIT 1";



                                    $query3 = $this->db->query($get_current_appointment);
                                    $check_num_rows = $query3->num_rows();
                                    if ($check_num_rows > 0) {
                                        foreach ($query3->result() as $value) {
                                            $client_id = $value->client_id;
                                            $appointment_id = $value->id;
                                            //Get the  curent actual outcome   
                                            $app_status = $value->app_status;

                                            $this->db->trans_start();

                                            $insert_outcome = array(
                                                'client_id' => $client_id,
                                                'appointment_id' => $appointment_id,
                                                'outcome' => $outcome,
                                                'tracer_name' => $tracer_name,
                                                'created_by' => $user_id,
                                                'tracing_type' => '3',
                                                'app_status' => $app_status,
                                                'tracing_date' => $call_date
                                            );
                                            $this->db->insert('clnt_outcome', $insert_outcome);

                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {

                                                /**
                                                 * Update Appointment row with the  tracing type of client called .
                                                 * Based on the number of updated made .....
                                                 * 
                                                 */
                                                $this->db->trans_start();


                                                $no_calls = $no_calls + 1;
                                                $update_appointment = array(
                                                    'no_calls' => $no_calls
                                                );
                                                $this->db->where('id', $appointment_id);
                                                $this->db->update('appointment', $update_appointment);





                                                $this->db->trans_complete();
                                                if ($this->db->trans_status() === FALSE) {
                                                    
                                                } else {



                                                    if ($final_outcome == "NULL" or empty($final_outcome)) {


                                                        $created_at = date('Y-m-d H:i:s');
                                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                        $this->config->load('config', TRUE);
                                                        // Retrieve a config item named site_name contained within the blog_settings array
                                                        $source = $this->config->item('shortcode', 'config');

                                                        $destination = '0' . $mobile;

                                                        $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                        $data_outgoing = array(
                                                            'destination' => $destination,
                                                            'source' => $source,
                                                            'msg' => $message,
                                                            'status' => 'Sent',
                                                            'message_type_id' => '5',
                                                            'responded' => 'No',
                                                            'recepient_type' => 'User',
                                                            'created_at' => $created_at
                                                        );
                                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                                        $send_text = $this->send_message($source, $destination, $message);

                                                        if ($send_text) {
                                                            echo 'SUCCESS';

                                                            $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                                            $this->db->query($sql);
                                                        } else {
                                                            echo 'FALSE';
                                                        }
                                                    } else {

                                                        if ($final_outcome == 1) {
                                                            //Returned to care , close the  appointment and book a new appointment
                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);


                                                            $appointment_insert = array(
                                                                'app_status' => 'Booked',
                                                                'appntmnt_date' => $app_date,
                                                                'status' => 'Active',
                                                                'sent_status' => 'Sent',
                                                                'client_id' => $client_id,
                                                                'created_at' => $today,
                                                                'created_by' => $user_id,
                                                                'app_type_1' => $new_appointment_type,
                                                                'entry_point' => 'Mobile',
                                                                'visit_type' => 'Scheduled',
                                                                'active_app' => '1'
                                                            );

                                                            $this->db->insert('appointment', $appointment_insert);
                                                        } elseif ($final_outcome == 2) {
                                                            //Self Transfer , close appointment and mark clients as self transfer

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Self Transfer'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 3) {
                                                            //Dead , close appointment and mark clients as Dead


                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);

                                                            $client_update = array(
                                                                'status' => 'Dead'
                                                            );
                                                            $this->db->where('id', $client_id);
                                                            $this->db->update('client', $client_update);
                                                        } elseif ($final_outcome == 4) {
                                                            //Challenging Client , leave appointment as open and mark clients as challenging 
                                                        } elseif ($final_outcome == 5) {
                                                            //Too Sick to attend , leave appointment as open and follow up later with the  client.

                                                            $appointment_update = array(
                                                                'active_app' => '0',
                                                                'fnl_trcing_outocme' => $final_outcome,
                                                                'fnl_outcome_dte' => $created_at,
                                                                'date_attended' => $today
                                                            );
                                                            $this->db->where('id', $appointment_id);
                                                            $this->db->update('appointment', $appointment_update);
                                                        }
                                                        //Update Appointment with the Final Outcome

                                                        $this->db->trans_start();
                                                        $update_appointment = array(
                                                            'fnl_trcing_outocme' => $final_outcome,
                                                            'visit_type' => 'Scheduled',
                                                            'fnl_outcome_dte' => $today
                                                        );
                                                        $this->db->where('id', $appointment_id);
                                                        $this->db->update('appointment', $update_appointment);
                                                        $this->db->trans_complete();
                                                        if ($this->db->trans_status() === FALSE) {
                                                            
                                                        } else {


                                                            $created_at = date('Y-m-d H:i:s');
                                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                            $this->config->load('config', TRUE);
                                                            // Retrieve a config item named site_name contained within the blog_settings array
                                                            $source = $this->config->item('shortcode', 'config');

                                                            $destination = '0' . $mobile;

                                                            $message = "Outcome for  Clinic number: $clinic_number was successfully updated in the   System  ";
                                                            $data_outgoing = array(
                                                                'destination' => $destination,
                                                                'source' => $source,
                                                                'msg' => $message,
                                                                'status' => 'Sent',
                                                                'message_type_id' => '5',
                                                                'responded' => 'No',
                                                                'recepient_type' => 'User',
                                                                'created_at' => $created_at
                                                            );
                                                            $this->db->insert('usr_outgoing', $data_outgoing);

                                                            $send_text = $this->send_message($source, $destination, $message);

                                                            if ($send_text) {
                                                                echo 'SUCCESS';

                                                                $sql = "UPDATE tbl_responses
                                                        SET processed = 'Yes' WHERE id = '$process_id'";
                                                                $this->db->query($sql);
                                                            } else {
                                                                echo 'FALSE';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $created_at = date('Y-m-d H:i:s');
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');

                                        $destination = '0' . $mobile;

                                        $message = "The speified appointment for Clinic number: $clinic_number  does not exist in the System  ";
                                        $data_outgoing = array(
                                            'destination' => $destination,
                                            'source' => $source,
                                            'msg' => $message,
                                            'status' => 'Sent',
                                            'message_type_id' => '5',
                                            'responded' => 'No',
                                            'recepient_type' => 'User',
                                            'created_at' => $created_at
                                        );
                                        $this->db->insert('usr_outgoing', $data_outgoing);

                                        $send_text = $this->send_message($source, $destination, $message);

                                        if ($send_text) {
                                            echo 'SUCCESS';

                                            $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                            $this->db->query($sql);
                                        } else {
                                            echo 'FALSE';
                                        }
                                    }
                                }
                            } else {



                                $created_at = date('Y-m-d H:i:s');
                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');

                                $destination = '0' . $mobile;

                                $message = "Clinic number: $clinic_number does not exist in the System  ";
                                $data_outgoing = array(
                                    'destination' => $destination,
                                    'source' => $source,
                                    'msg' => $message,
                                    'status' => 'Sent',
                                    'message_type_id' => '5',
                                    'responded' => 'No',
                                    'recepient_type' => 'User',
                                    'created_at' => $created_at
                                );
                                $this->db->insert('usr_outgoing', $data_outgoing);

                                $send_text = $this->send_message($source, $destination, $message);

                                if ($send_text) {
                                    echo 'SUCCESS';


                                    $sql = "UPDATE tbl_responses
                                    SET processed = 'Yes' WHERE id = '$process_id'";
                                    $this->db->query($sql);
                                } else {
                                    echo 'FALSE';
                                }
                            }
                        }
                    }
                } else {
                    echo 'User Not Found ....';
                    $created_at = date('Y-m-d H:i:s');
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');

                    $destination = '0' . $mobile;
                    $this->db->trans_start();
                    $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'User',
                        'created_at' => $created_at
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);

                    $usr_otgoing_id = "User";
                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                    if ($send_text) {
                        echo 'SUCCESS';
                    } else {
                        echo 'FALSE';
                    }

                    $response_update = array(
                        'processed' => 'Yes'
                    );
                    $this->db->where('id', $process_id);
                    $this->db->update('responses', $response_update);
                }
            } else {
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;



                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $process_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function process_LTFU_actions($response_id) {
        $sql = "SELECT * FROM tbl_incoming WHERE DATE(created_at) = CURDATE() AND msg LIKE '%LTFU%' and id='$response_id' ";

        $query1 = $this->db->query($sql)->result();
        foreach ($query1 as $value) {
            $user_source = $value->source;

            $process_id = $value->id;
            $user_source = $value->source;
            $user_destination = $value->destination;
            $encrypted_msg = $value->msg;



            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;








                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $descrypted_msg = $this->decrypt($message);
                echo 'Decrypted Msg => ' . $descrypted_msg . '<br>';
                $new_msg = $identifier . "*" . $descrypted_msg;
                echo 'New Message => ' . $new_msg;


                //$msg = $new_msg;
                $msg = $value->msg;


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


                    $user_details = $get_facility->result();

                    foreach ($user_details as $value) {
                        $user_id = $value->id;




                        $split_message = explode('*', $msg);
                        $code = $split_message[0];

                        $clinic_number = $split_message[1];
                        $appointment_type = $split_message[2];
                        $no_of_calls = $split_message[3];
                        $outcome = $split_message[4];
                        $app_date = $split_message[5];
                        $tracer_name = $split_message[6];


                        $check_clinic_no_sql = "Select * from tbl_client where clinic_number = '$clinic_number' LIMIT 1";

                        $query2 = $this->db->query($check_clinic_no_sql);
                        $check_num_rows = $query2->num_rows();
                        if ($check_num_rows > 0) {

                            foreach ($query2->result() as $value) {
                                $client_id = $value->id;
                                $language_id = $value->language_id;
                                $client_name = $value->f_name . ' ' . $value->m_name . ' ' . $value->l_name;
                                $get_current_appointment = "Select * from tbl_appointment where client_id='$client_id' and app_type_1='$appointment_type' and active_app='1' LIMIT 1";



                                $query3 = $this->db->query($get_current_appointment);
                                $check_num_rows = $query3->num_rows();
                                if ($check_num_rows > 0) {
                                    foreach ($query3->result() as $value) {

                                        $appointment_id = $value->id;

                                        $no_calls = $value->no_calls;
                                        $no_calls = $no_calls + $no_of_calls;
                                        echo 'No of calls => #1 ' . $no_of_calls . '<br>';

                                        if ($app_date == 'Null') {

                                            $data_update = array(
                                                'no_calls' => $no_calls,
                                                'fnl_trcing_outocme' => $outcome,
                                                'tracer_name' => $tracer_name,
                                                'visit_type' => 'Scheduled',
                                                'active_app' => '1'
                                            );

                                            $this->db->where('id', $appointment_id);
                                            $this->db->update('appointment', $data_update);




                                            $created_at = date('Y-m-d H:i:s');
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');

                                            $destination = '0' . $mobile;
                                            $this->db->trans_start();
                                            $message = "Client NO : $clinic_number Appointment was updated to LTFU Successfully ";
                                            $data_outgoing = array(
                                                'destination' => $destination,
                                                'source' => $source,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'recepient_type' => 'Client',
                                                'created_at' => $created_at
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);

                                            $usr_otgoing_id = "User";
                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                                            if ($send_text) {
                                                echo 'SUCCESS';
                                            } else {
                                                echo 'FALSE';
                                            }

                                            $response_update = array(
                                                'processed' => 'Yes'
                                            );
                                            $this->db->where('id', $process_id);
                                            $this->db->update('responses', $response_update);
                                        } else {

                                            $data_update = array(
                                                'no_calls' => $no_calls,
                                                'fnl_trcing_outocme' => $outcome,
                                                'tracer_name' => $tracer_name,
                                                'visit_type' => 'Scheduled',
                                                'active_app' => '0'
                                            );

                                            $this->db->where('id', $appointment_id);
                                            $this->db->update('appointment', $data_update);

                                            //Create a new apponitment 





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
                                                'sent_status' => 'Sent',
                                                'client_id' => $client_id,
                                                'created_at' => $today,
                                                'created_by' => $user_id,
                                                'app_type_1' => $appointment_type,
                                                'entry_point' => 'Mobile',
                                                'visit_type' => 'Scheduled',
                                                'active_app' => '1'
                                            );

                                            $this->db->insert('appointment', $appointment_insert);





                                            $created_at = date('Y-m-d H:i:s');
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');

                                            $destination = '0' . $mobile;
                                            $this->db->trans_start();
                                            $message = "Client NO : $clinic_number Appointment was updated to LTFU Successfully ";
                                            $data_outgoing = array(
                                                'destination' => $destination,
                                                'source' => $source,
                                                'msg' => $message,
                                                'status' => 'Sent',
                                                'message_type_id' => '5',
                                                'responded' => 'No',
                                                'recepient_type' => 'Client',
                                                'created_at' => $created_at
                                            );
                                            $this->db->insert('usr_outgoing', $data_outgoing);

                                            $usr_otgoing_id = "User";
                                            $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                                            if ($send_text) {
                                                echo 'SUCCESS';
                                            } else {
                                                echo 'FALSE';
                                            }


                                            $response_update = array(
                                                'processed' => 'Yes'
                                            );
                                            $this->db->where('id', $process_id);
                                            $this->db->update('responses', $response_update);
                                        }
                                    }
                                }
                            }
                        } else {
                            
                        }
                    }
                } else {

                    $created_at = date('Y-m-d H:i:s');
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');

                    $destination = '0' . $mobile;
                    $this->db->trans_start();
                    $message = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you";
                    $data_outgoing = array(
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $message,
                        'status' => 'Sent',
                        'message_type_id' => '5',
                        'responded' => 'No',
                        'recepient_type' => 'Client',
                        'created_at' => $created_at,
                        'clnt_usr_id' => '587'
                    );
                    $this->db->insert('usr_outgoing', $data_outgoing);

                    $usr_otgoing_id = "User";
                    $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);

                    if ($send_text) {
                        echo 'SUCCESS';
                    } else {
                        echo 'FALSE';
                    }



                    $response_update = array(
                        'processed' => 'Yes'
                    );
                    $this->db->where('id', $process_id);
                    $this->db->update('responses', $response_update);
                }
            } else {
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;



                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $process_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function stable_client() {
        $start_id = "1";
        $end_id = "1000";
        $date_today = date("Y-m-d H:i:s");
        $get_client = $this->db->query("Select * from tbl_client ")->result();
        foreach ($get_client as $value) {
            $art_Start_date = $value->art_date;
            $dob = $value->dob;
            $client_id = $value->id;

            # procedural
            $art_date_diff = date_diff(date_create($art_Start_date), date_create('today'))->format('%m');
            $differenceFormat = '%a';
            $date_difference = $this->dateDifference($art_Start_date, $date_today, $differenceFormat);
            $age_difference = $this->dateDifference($dob, $date_today, $differenceFormat);



            if ($age_difference < '7300') {
                $this->db->trans_start();
                $stable_array = array(
                    'stable' => 'No'
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $stable_array);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            } else {
                $this->db->trans_start();
                $stable_array = array(
                    'stable' => 'Yes'
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $stable_array);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
            if ($date_difference < '182') {
                $this->db->trans_start();
                $stable_array = array(
                    'stable' => 'No'
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $stable_array);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            } else {
                $this->db->trans_start();
                $stable_array = array(
                    'stable' => 'Yes'
                );
                $this->db->where('id', $client_id);
                $this->db->update('client', $stable_array);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
            $start_id + 1000;
            $end_id + 2000;
        }
    }

    function dateDifference($date_1, $date_2, $differenceFormat) {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

    function add_super_admin() {
        $f_name = "Admin";
        $m_name = "Super";
        $l_name = "Admin";
        $dob = "25/04/1990";
        $e_mail = "support.tech@mhealthkenya.org";
        $status = 'Active';
        $partner_id = "";
        $donor_id = "";
        $facility_id = "";
        $today = date("Y-m-d H:i:s");
        $phone_no = "0732577551";
        $access_level = "Admin";
        $daily_report = "Yes";
        $weekly_report = "Yes";
        $monthly_report = "Yes";
        $month3_report = "Yes";
        $month6_report = "Yes";
        $yearly_report = "Yes";
        $role_names = "1";
        $view_bio_data = "No";
        $rcv_app_list = "No";
        $county_id = "";
        $sub_county_id = "";
        $check_email = $this->check_email($e_mail)->num_rows();
        $check_phoneno = $this->check_phoneno($phone_no)->num_rows();

        $check_dob = str_replace('/', '-', $dob);
        $check_dob = date("Y-m-d", strtotime($check_dob));
        $current_year = date('Y');
        $unix_dob = strtotime($check_dob);
        $year_dob = date('Y', $unix_dob);
        $current_age = $current_year - $year_dob;
        $created_by = "1";

        if ($current_age >= 18) {
            if ($check_email or $check_phoneno) {
                if (!empty($check_email) and ! empty($check_phoneno) and $check_email > 0 and $check_phoneno > 0) {
                    $msg = "Phone Email Taken";
                } elseif (!empty($check_email) and $check_email > 0) {
                    $msg = "Email Taken";
                } elseif (!empty($check_phoneno) and $check_phoneno > 0) {
                    $msg = "Phone Taken";
                }
                return $msg;
            } else {
                $this->db->trans_start();
                $password = $this->cryptPass($phone_no);
                $first_access = "Yes";

                $post_data = array(
                    'f_name' => $f_name,
                    'm_name' => $m_name,
                    'l_name' => $l_name,
                    'dob' => $check_dob,
                    'e_mail' => $e_mail,
                    'status' => $status,
                    'partner_id' => $partner_id,
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
                    'role_id' => $role_names
                );


                $this->db->insert('users', $post_data);

                $last_insert_id = $this->db->insert_id();

                $get_role_modules = array(
                    'table' => 'role_module',
                    'where' => array('role_id' => $role_names, 'status' => 'Active'));

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
                if ($this->db->trans_status() === FALSE) {
                    return FALSE;
                } else {
                    $description = "Added a user : $f_name   $m_name   $l_name successfully ";
                    $this->log_action($description);
                    return TRUE;
                }
            }
        } else {
            $msg = "Under Age";
            return $msg;
        }
    }

    function cryptPass($input, $rounds = 9) {
        $salt = "";
        $saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        for ($i = 0; $i < 22; $i++):
            $salt .= $saltChars[array_rand($saltChars)];
        endfor;
        return crypt($input, sprintf("$2y$%02d$", $rounds) . $salt);
    }

    function initiate_user() {
        $output = $this->add_super_admin();
        echo 'Output => ' . $output;
    }

    function procees_wellenss() {
        $sql = "SELECT * FROM tbl_responses where updated_at >= CURDATE()- INTERVAL 1 DAY AND updated_at <= CURDATE() + INTERVAL 1 DAY AND recognised ='UnRecognised' ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $value) {
                $response_id = $value->id;
                $source = $value->source;
                $destination = $value->destination;
                $msg = $value->msg;
                $created_at = $value->created_at;
                $updated_at = $value->updated_at;




                //Number process , Append conutry code prefix on the  phone no if its not appended e.g 0712345678 => 254712345678	
                $mobile = substr($source, -9);
                $len = strlen($mobile);

                if ($len > 10) {
                    $source = "0" . $mobile;
                } else {
                    $source = "0" . $mobile;
                }

                $get_client = $this->db->query("Select * from tbl_client where phone_no='$source'");
                if ($get_client->num_rows() > 0) {
                    foreach ($get_client->result() as $client_details) {

                        $client_id = $client_details->id;

                        $check_response_type_sql = "Select * from tbl_notification_flow where value like '%$msg%'";
                        $check_response_type_query = $this->db->query($check_response_type_sql);
                        if ($check_response_type_query->num_rows() > 0) {
                            foreach ($check_response_type_query->result() as $notification_value) {
                                $response_value = $notification_value->value;
                                $exploded_value = explode(":", $response_value);
                                $positive_value = @$exploded_value[0];
                                $negative_value = @$exploded_value[1];

                                if ($msg == $positive_value) {
                                    //Postive outcome , record in the  SMS Checkin 
                                    $data_insert = array(
                                        'client_id' => $client_id,
                                        'msg' => $msg,
                                        'source' => $source,
                                        'destination' => $destination,
                                        'response_type' => 'Positive',
                                        'created_by' => '1'
                                    );
                                }

                                if ($msg == $negative_value) {
                                    //Negative outcome , record in the  SMS Checkin 
                                    $data_insert = array(
                                        'client_id' => $client_id,
                                        'msg' => $msg,
                                        'source' => $source,
                                        'destination' => $destination,
                                        'response_type' => 'Negative',
                                        'created_by' => '1'
                                    );
                                }

                                $this->db->trans_start();
                                $this->db->insert('sms_checkin', $data_insert);
                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {


                                    //Update the response to recognised
                                    $data_update = array(
                                        'recognised' => 'Recognised'
                                    );
                                    $this->db->where('id', $response_id);
                                    $this->db->update('responses', $data_update);
                                }
                            }
                        } else {
                            
                        }
                    }
                } else {
                    
                }
            }
        } else {
            
        }
    }

    function get_facility_results() {




        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "3001",
            CURLOPT_URL => "http://api.mhealthkenya.org:3001/api/results?filter[where][mfl_code]=13050",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 86ca831e-4ee8-9b32-1a4b-f86ca28917fb"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $results = json_decode($response);


            foreach ($results as $jsons) { // This will search in the 2 jsons
                $source = $jsons->source;
                $mfl_code = $jsons->mfl_code;
                $result_id = $jsons->result_id;
                $result_type = $jsons->result_type;
                $request_id = $jsons->request_id;
                $client_id = $jsons->client_id;
                $age = $jsons->age;
                $gender = $jsons->gender;
                $result_content = $jsons->result_content;
                $units = $jsons->units;
                $mfl_code = $jsons->mfl_code;
                $lab_id = $jsons->lab_id;
                $date_collected = $jsons->date_collected;
                $cst = $jsons->cst;
                $cj = $jsons->cj;
                $csr = $jsons->csr;
                $lab_order_date = $jsons->lab_order_date;
                $id = $jsons->id;
                echo $mfl_code;
            }
        }
    }

    function map_incoming_id() {
        $query = $this->db->query("SELECT * FROM tbl_responses WHERE incoming_id IS NULL")->result();
        foreach ($query as $value) {
            $source = $value->source;
            $msg = $value->msg;
            $response_id = $value->id;
            $get_incoming_id = $this->db->query("Select * from tbl_incoming where source='$source' and msg LIKE '%$msg%'")->result();
            foreach ($get_incoming_id as $value2) {
                $incoming_id = $value2->id;
                $this->db->trans_start();
                $data_update = array(
                    'incoming_id' => $incoming_id
                );
                $this->db->where('id', $response_id);
                $this->db->update('responses', $data_update);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function clean_up_responses() {
        $get_incoming_id = $this->db->query("Select * from tbl_incoming")->result();
        foreach ($get_incoming_id as $value) {
            $id = $value->id;
            $source = $value->source;
            $detination = $value->destination;
            $msg = $value->msg;
            $created_at = $value->created_at;
            $time_Stamp = $value->updated_at;
            $status = $value->status;
            $senttime = $value->senttime;
            $receivedtime = $value->receivedtime;
            $reference = $value->reference;
            $processed = $value->processed;
            $created_by = $value->created_by;
            $updated_by = $value->updated_by;

            $this->db->trans_start();
            $data_insert = array(
                'source' => $source,
                'destination' => $detination,
                'msg' => $msg,
                'created_at' => $created_at,
                'updated_at' => $time_Stamp,
                'incoming_id' => $id,
                'created_by' => $created_by,
                'updated_by' => $updated_by,
                'recognised' => 'UnRecognised',
                'processed' => 'Yes'
            );
            $this->db->insert('responses', $data_insert);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                
            } else {
                
            }
        }
    }

    function send_facility_tracers() {
        $get_facility_users = $this->db->query(" SELECT
	* 
FROM
	tbl_users
	INNER JOIN tbl_role ON tbl_role.id = tbl_users.role_id 
WHERE
	tbl_users.access_level = 'Facility' 
	AND tbl_users.rcv_app_list = 'Yes' and tbl_role.name LIKE '%Tracer%' ")->result();
        foreach ($get_facility_users as $value) {
            $mfl_code = $value->facility_id;
            $fclty_phone_no = $value->phone_no;
            $clnt_usr_id = $value->id;

            $get_tracers_list = $this->db->query(" SELECT
	f_name,m_name,phone_no,tbl_users.id
FROM
	tbl_users
	INNER JOIN tbl_role ON tbl_role.id = tbl_users.role_id 
WHERE
	tbl_users.access_level = 'Facility' and tbl_role.name LIKE '%Tracer%' and facility_id ='$mfl_code' ")->result();
            foreach ($get_tracers_list as $tracers_value) {
                $f_name = $tracers_value->f_name;
                $m_name = $tracers_value->m_name;
                $trcrs_phone_no = $tracers_value->phone_no;
                $tracers_user_id = $tracers_value->id;

                $trcrs_msg = "" . $f_name . "*" . $m_name . "*" . $trcrs_phone_no . "*" . $tracers_user_id . " ";
                echo $trcrs_msg;
                $msg = "TRC*" . base64_encode($trcrs_msg);


                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                $this->config->load('config', TRUE);
                // Retrieve a config item named site_name contained within the blog_settings array
                $shortcode = $this->config->item('shortcode', 'config');

                $source = $shortcode;
                $destination = $fclty_phone_no;



                $usr_otgoing_id = "User";
                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                echo 'Send status => ' . $send_text . '<br>';
                $created_at = date("Y-m-d H:i:s");
                echo 'Msg #1' . $msg . '</br>Destination  # 1 : ' . $destination . '<br>Source => ' . $source . '</br>';



                if ($send_text) {
                    $this->db->trans_start();
                    $outgoing = array(
                        'destination' => $destination,
                        'msg' => $msg,
                        'status' => 'Sent',
                        'message_type_id' => '7',
                        'source' => $source,
                        'created_at' => $created_at,
                        'clnt_usr_id' => $clnt_usr_id,
                        'recepient_type' => "User",
                        'updated_by' => '1',
                        'created_by' => '1'
                    );
                    $this->db->insert('usr_outgoing', $outgoing);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        
                    } else {
                        
                    }
                } else if (!$send_text) {
                    echo 'Msg was not sent , please try again.....';
                } else {
                    
                }
            }
        }
    }

    function proces_assng_trcrs($response_id) {



        $created_at = date("Y-m-d H:i:s");



        $query = $this->db->query(" 
        SELECT
        * 
    FROM
        tbl_responses 
    WHERE
     id='$response_id'  
         ")->result();

        foreach ($query as $value) {
            $source = $value->source;
            $destination = $value->destination;

            $response_id = $value->id;
            $mobile = substr($source, -9);
            $len = strlen($mobile);

            $encrypted_msg = $value->msg;






            $count_special = substr_count($encrypted_msg, "*");
            if ($count_special < 2) {
                //New Encrypted Message
                echo " New Encrypted Message => " . $count_special;







                echo 'Encrypted Message => ' . $encrypted_msg . '<br>';

                $explode_msg = explode("*", $encrypted_msg);
                $identifier = $explode_msg[0];
                $message = $explode_msg[1];
                $msg = "ASSTRC*" . base64_decode($message);

                echo 'Decrpyted Msg => ' . $msg . '<br>';





                if ($len = 9) {

                    $source = "0" . $mobile;
                }
                echo 'New From : ' . $source;


                $get_facility = $this->db->query("Select * from tbl_users where phone_no='$source' and access_level='Facility'");

                $check_user_exist = $get_facility->num_rows();
                if ($check_user_exist > 0) {
                    /* Process the  sent message 
                      Check if the specified number exists in the  system,
                      If it's not found, then send a failed message back to the  user
                      Else process and move the  client to our facility.
                     */

                    foreach ($get_facility->result() as $user_value) {
                        # code...
                        $new_mfl_code = $user_value->facility_id;


                        $exploded_msg = explode("*", $msg);
                        $message_code = @$exploded_msg[0];
                        $appointment_id = @$exploded_msg[1];
                        $user_id = @$exploded_msg[2];
                        $user_phone_no = $source;
                        $get_tracers_details = $this->db->query("Select phone_no from tbl_users where id = $user_id ");
                        if ($get_tracers_details->num_rows() > 0) {

                            foreach ($get_tracers_details->result() as $value) {
                                $tracers_phone_no = $value->phone_no;
                                $get_client_details = $this->db->query("  SELECT
	cl.clinic_number,
	concat( cl.f_name, '', cl.m_name, '', cl.l_name ) AS client_name,
	cl.phone_no,
	app.appntmnt_date as appointment_date,
	app.app_status,
	app_type.NAME AS appointment_type 
FROM
	tbl_client cl
	INNER JOIN tbl_appointment app ON app.client_id = cl.id
	INNER JOIN tbl_appointment_types app_type ON app_type.id = app.app_type_1
        where app.id='$appointment_id'  ");
                                if ($get_client_details->num_rows() > 0) {
                                    foreach ($get_client_details->result() as $client_details_value) {
                                        $clinic_number = $client_details_value->clinic_number;
                                        $client_name = $client_details_value->client_name;
                                        $client_phone_no = $client_details_value->phone_no;
                                        $appointment_date = $client_details_value->appointment_date;
                                        $app_status = $client_details_value->app_status;
                                        $appointment_type = $client_details_value->appointment_type;
                                        $outgoing_msg = $clinic_number . "*" . $client_name . "*" . $client_phone_no . "*" . $appointment_type . "*" . $appointment_date . "*" . $app_status . " ";
                                        $keyword = " ";
                                        if ($app_status == "Missed") {
                                            $keyword .= "MSDAPP";
                                        } elseif ($app_status == "Defaulted") {
                                            $keyword .= "DEFAPP";
                                        }
                                        $encrypted_msg = $keyword . "*" . base64_encode($outgoing_msg);

                                        $msg = $encrypted_msg;

                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $shortcode = $this->config->item('shortcode', 'config');

                                        $source = $shortcode;
                                        $destination = $tracers_phone_no;


                                        $usr_otgoing_id = "User";
                                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                        echo 'Send status => ' . $send_text . '<br>';
                                        $created_at = date("Y-m-d H:i:s");
                                        echo 'Msg #1' . $msg . '</br>Destination  # 1 : ' . $destination . '<br>Source => ' . $source . '</br>';



                                        if ($send_text) {
                                            $this->db->trans_start();
                                            $outgoing = array(
                                                'destination' => $destination,
                                                'msg' => $msg,
                                                'status' => 'Sent',
                                                'message_type_id' => '7',
                                                'source' => $source,
                                                'created_at' => $created_at,
                                                'clnt_usr_id' => $user_id,
                                                'recepient_type' => "User",
                                                'updated_by' => '1',
                                                'created_by' => '1'
                                            );
                                            $this->db->insert('usr_outgoing', $outgoing);
                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }


                                            $this->db->trans_start();
                                            $update_response = array(
                                                'user_type' => 'User',
                                                'updated_by' => '1',
                                                'processed' => 'Yes'
                                            );
                                            $this->db->where('id', $response_id);
                                            $this->db->update('responses', $update_response);

                                            $this->db->trans_complete();
                                            if ($this->db->trans_status() === FALSE) {
                                                
                                            } else {
                                                
                                            }
                                        } else if (!$send_text) {
                                            echo 'Msg was not sent , please try again.....';
                                        } else {
                                            
                                        }
                                    }
                                } else {
                                    
                                }
                            }
                        } else {
                            //TRACER DOES NOT EXIST ...


                            $this->db->trans_start();
                            $data_insert = array(
                                'destination' => $source,
                                'source' => $destination,
                                'msg' => 'The Assigned Tracer does not exist or is not active in the system ...',
                                'status' => 'Sent',
                                'created_at' => $created_at,
                                'message_type_id' => '5',
                                'recepient_type' => 'User',
                                'clnt_usr_id' => '587'
                            );
                            $this->db->insert('usr_outgoing', $data_insert);
                            $this->db->trans_complete();
                            if ($this->db->trans_status() === FALSE) {
                                echo 'Error => <br> ';
                            } else {
                                echo 'Transaction Insert Success <br>';



                                //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                $this->config->load('config', TRUE);
                                // Retrieve a config item named site_name contained within the blog_settings array
                                $source = $this->config->item('shortcode', 'config');
                                $destination = $destination;
                                $msg = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you ";
                                echo 'Message => ' . $msg . '<br> and response ID => ' . $response_id . '<br>';
                                $usr_otgoing_id = "User";
                                $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                                if ($send_text) {
                                    echo 'TRUE';
                                } else {
                                    echo 'FALSE';
                                }




                                $this->db->trans_start();
                                $update_response = array(
                                    'user_type' => 'User',
                                    'updated_by' => '1',
                                    'processed' => 'Yes'
                                );
                                $this->db->where('id', $response_id);
                                $this->db->update('responses', $update_response);

                                $this->db->trans_complete();
                                if ($this->db->trans_status() === FALSE) {
                                    
                                } else {
                                    
                                }
                            }
                        }
                    }
                } else {
                    $this->db->trans_start();
                    $data_insert = array(
                        'destination' => $source,
                        'source' => $destination,
                        'msg' => 'Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you',
                        'status' => 'Sent',
                        'created_at' => $created_at,
                        'message_type_id' => '5',
                        'recepient_type' => 'User',
                        'clnt_usr_id' => '587'
                    );
                    $this->db->insert('usr_outgoing', $data_insert);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        echo 'Error => <br> ';
                    } else {
                        echo 'Transaction Insert Success <br>';



                        //Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                        $this->config->load('config', TRUE);
                        // Retrieve a config item named site_name contained within the blog_settings array
                        $source = $this->config->item('shortcode', 'config');
                        $destination = $destination;
                        $msg = "Hi , your phone number is not is the system,kindly contact your partner focal person so that it can be added, thank you ";
                        echo 'Message => ' . $msg . '<br> and response ID => ' . $response_id . '<br>';
                        $usr_otgoing_id = "User";
                        $send_text = $this->send_message($source, $destination, $msg, $usr_otgoing_id);
                        if ($send_text) {
                            echo 'TRUE';
                        } else {
                            echo 'FALSE';
                        }




                        $this->db->trans_start();
                        $update_response = array(
                            'user_type' => 'User',
                            'updated_by' => '1',
                            'processed' => 'Yes'
                        );
                        $this->db->where('id', $response_id);
                        $this->db->update('responses', $update_response);

                        $this->db->trans_complete();
                        if ($this->db->trans_status() === FALSE) {
                            
                        } else {
                            
                        }
                    }
                }
            } else {
                //Old Non Encrypted Message
                echo " Old Non Encrypted Message => " . $count_special;





                $this->db->trans_start();

                $this->db->delete('responses', array('id' => $response_id));

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    
                } else {
                    
                }
            }
        }
    }

    function ushauri_msgs() {

        //Get the  frequency of messages that should be going out, 
        //from the  frequency schedule all the messages that should be going out to the clients 
        //Be checking on a dialy basis if the message meets the  criteria and then send it to the  client. 
        //Get the  frequency of message flowing 
        //Get the current week , check the last ougoing message per client level
        // and based on the conditions (if the  conditions were met) , if they were met , schedule the  next message
        //Tables required : => tbl_messages , tbl_clnt_ushauri 
        //1=> Get Clients based on the  date added on the  system 

        $today = date("Y-m-d H:i:s");
        $dayofweek = date('w', strtotime($today));

        echo 'Day of week => ' . $dayofweek . '<br>';


        $ddate = $today;
        $date = new DateTime($ddate);
        $week = $date->format("W");
        $year = date('Y');

        $get_client_day = $this->db->query(" 
SELECT
	tbl_client.phone_no,
	tbl_client.id,
	tbl_client.language_id,
	tbl_client.gender,
	tbl_client.group_id,
	tbl_client.f_name,
	MAX( DATE( tbl_clnt_ushauri.`updated_at` ) ) as max_date,
	DAYOFWEEK( CURDATE( ) ) as day_of_week
FROM
	tbl_client
	INNER JOIN tbl_clnt_ushauri ON tbl_clnt_ushauri.`client_id` = tbl_client.`id` 
WHERE
	DAYOFWEEK( `tbl_client`.`created_at` ) = $dayofweek
	AND smsenable = 'Yes' 
	AND motivational_enable = 'Yes' 
GROUP BY
	tbl_client.id 
ORDER BY
	tbl_clnt_ushauri.updated_at ASC  ");
        $check_existnce = $get_client_day->num_rows();
        if ($check_existnce > 0) {
            echo 'Our Check Existence => ' . $check_existnce . ' end ...<br>';

            echo 'Clients wako !!!';
            $get_client_details = $get_client_day->result();

            foreach ($get_client_details as $value) {
                $phone_no = $value->phone_no;
                $client_id = $value->id;
                $language_id = $value->language_id;
                $gender_id = $value->gender;
                $group_id = $value->group_id;
                $client_name = $value->f_name;
                $max_sent_date = $value->max_date;
                $day_of_week = $value->day_of_week;
                $today = date("Y-m-d");
                if ($max_sent_date == $today) {
                    echo 'Max Date and User should not be equal<br>';
                } else {
                    echo $phone_no . '<br>';
                    $get_clnt_ushauri = $this->db->query(" Select * from tbl_clnt_ushauri where client_id=$client_id and DATE(`created_at`) = (SELECT MAX(DATE(created_at)) FROM tbl_clnt_ushauri AS b WHERE b.`client_id`='$client_id') GROUP BY client_id LIMIT 1  ");
                    $check_get_clnt_ushauri = $get_clnt_ushauri->num_rows();
                    echo $check_get_clnt_ushauri . '<br>';
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
                            echo 'Year Value ' . $year_value . '   and Current Year ' . $year . '</br>';
                            echo 'Week value ' . $week_value . ' and Current Week  ' . $week . '</br>';

                            if ($year == $year_value and $week_value == $week) {
                                echo 'No out going msg, year and week are the same... ';
                            } else {
                                echo "Outgoing msg should be sent ... and our login id value is : $logic_flow_id_value <br> ";
                                if ($logic_flow_id_value == 26) {
                                    echo 'Logic flow 26 attained <br>';
                                    $new_logic_flow = 1;
                                } else {
                                    $new_logic_flow = $logic_flow_id_value + 1;

                                    if ($new_logic_flow == 13) {
                                        $new_logic_flow = $new_logic_flow + 1;

                                        echo 'New Logic Flow ID : => for Flow ID 13 => ' . $new_logic_flow . '</br>';
                                    } else {

                                        echo 'New Logic Flow ID not equals to 13  : => ' . $new_logic_flow . '</br>';

                                        if ($new_logic_flow == 26) {
                                            if ($gender_id == 1) {
                                                
                                            } else if ($gender_id == 2) {
                                                $new_logic_flow = 1;
                                            }
                                        } else {
                                            
                                        }
                                    }
                                }



                                if ($new_logic_flow == 3) {
                                    $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Adult' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id' and status='Active'")->result();


                                    foreach ($get_clnt_outgoing_msg as $value) {
                                        $message = $value->message;
                                        $message_type_id = $value->message_type_id;
                                        $message_id = $value->id;
                                        $logic_flow_id = $value->logic_flow;
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');

                                        $client_name = ucwords(strtolower($client_name)) . " ";

                                        $message = str_replace("XXX", $client_name, $message);

                                        echo "Outgoing Mesage 1 => " . $message . '<br>';
                                        $this->db->trans_start();
                                        $clnt_outgoing = array(
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
                                        $this->db->insert('clnt_outgoing', $clnt_outgoing);
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

                                    //Check if client is an Adolescent or Adult
                                    //If client is adolescent , queue the message
                                    //If client is adult , ignore and append +1 to the loagic flow id and pick the  next message
                                    //Key => 1 : Adult 2:Adolescent
                                    $get_clnt_outgoing_msg = '';
                                    if ($group_id == 1) { //Adult
                                        echo 'Adult' . $new_logic_flow . ' and language ID => ' . $language_id . '<br>';
                                        $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='6' and language_id='$language_id'")->result();


                                        foreach ($get_clnt_outgoing_msg as $value) {
                                            $message = $value->message;
                                            $message_type_id = $value->message_type_id;
                                            $message_id = $value->id;
                                            $logic_flow_id = $value->logic_flow;
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');

                                            $client_name = ucwords(strtolower($client_name)) . " ";

                                            $message = str_replace("XXX", $client_name, $message);

                                            echo "Outgoing Mesage 1 => " . $message . '<br>';
                                            $this->db->trans_start();
                                            $clnt_outgoing = array(
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
                                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
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
                                        echo 'Adolescent Grouping ' . $new_logic_flow . '<br>';
                                        if ($new_logic_flow == 4) {

                                            $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Adolescent' and message_type_id='4' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                            foreach ($get_clnt_outgoing_msg as $value) {
                                                $message = $value->message;
                                                $message_type_id = $value->message_type_id;
                                                $message_id = $value->id;
                                                $logic_flow_id = $value->logic_flow;
                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');
                                                $client_name = ucwords(strtolower($client_name)) . " ";

                                                $message = str_replace("XXX", $client_name, $message);

                                                echo "Outgoing Mesage 1 => " . $message . '<br>';
                                                $this->db->trans_start();
                                                $clnt_outgoing = array(
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
                                                $this->db->insert('clnt_outgoing', $clnt_outgoing);
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
                                            $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Adolescent' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                            foreach ($get_clnt_outgoing_msg as $value) {
                                                $message = $value->message;
                                                $message_type_id = $value->message_type_id;
                                                $message_id = $value->id;
                                                $logic_flow_id = $value->logic_flow;
                                                // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                                $this->config->load('config', TRUE);
                                                // Retrieve a config item named site_name contained within the blog_settings array
                                                $source = $this->config->item('shortcode', 'config');


                                                $client_name = ucwords(strtolower($client_name)) . " ";

                                                $message = str_replace("XXX", $client_name, $message);

                                                echo "Outgoing Mesage 1 => " . $message . '<br>';
                                                $this->db->trans_start();
                                                $clnt_outgoing = array(
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
                                                    'created_by' => '1');
                                                $this->db->insert('clnt_outgoing', $clnt_outgoing);
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

                                if ($group_id == 2) {

                                    if ($new_logic_flow == 9 or $new_logic_flow == 11) {



                                        echo 'Logic ID is equal to 9 and language is => ' + $language_id + ' <br>';
                                        $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Adolescent' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                        foreach ($get_clnt_outgoing_msg as $value) {
                                            $message = $value->message;
                                            $message_type_id = $value->message_type_id;
                                            $message_id = $value->id;
                                            $logic_flow_id = $value->logic_flow;
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');

                                            $client_name = ucwords(strtolower($client_name)) . " ";

                                            $message = str_replace("XXX", $client_name, $message);




                                            echo "Outgoing Mesage 1 => " . $message . '<br>';
                                            $this->db->trans_start();
                                            $clnt_outgoing = array(
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
                                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
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
                                } else {


                                    if ($new_logic_flow == 9 or $new_logic_flow == 11) {

                                        $new_logic_flow_id = $new_logic_flow + 1;

                                        echo 'Logic ID is equal to 9 but we jumped to 9+1 = 10 <br>';
                                        $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where  message_type_id='2' and logic_flow='$new_logic_flow_id' and language_id='$language_id'")->result();

                                        foreach ($get_clnt_outgoing_msg as $value) {
                                            $message = $value->message;
                                            $message_type_id = $value->message_type_id;
                                            $message_id = $value->id;
                                            $logic_flow_id = $value->logic_flow;
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');

                                            $client_name = ucwords(strtolower($client_name)) . " ";

                                            $message = str_replace("XXX", $client_name, $message);



                                            echo "Outgoing Mesage 1 => " . $message . '<br>';
                                            $this->db->trans_start();
                                            $clnt_outgoing = array(
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
                                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
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







                                echo "Gender ID => " . $gender_id . '<br>';
                                echo"Qwerty";
                                if ($gender_id == 1) {

                                    //Get only cilents who are Female 
                                    if ($new_logic_flow == 7 or $new_logic_flow == 8 or $new_logic_flow == 24 or $new_logic_flow == 25 or $new_logic_flow == 26) {

                                        echo 'Logic ID is equal to 7 or 8 or 26 <br>';
                                        $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Female' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                        foreach ($get_clnt_outgoing_msg as $value) {
                                            $message = $value->message;
                                            $message_type_id = $value->message_type_id;
                                            $message_id = $value->id;
                                            $logic_flow_id = $value->logic_flow;
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');

                                            $client_name = ucwords(strtolower($client_name)) . " ";

                                            $cleaned_msg = str_replace("XXX", $client_name, $message);




                                            echo "Outgoing Mesage 1 => " . $message . '<br>';
                                            $this->db->trans_start();
                                            $clnt_outgoing = array(
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
                                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
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

                                    if ($new_logic_flow == 21 or $new_logic_flow == 23) {
                                        $new_logic_flow = $new_logic_flow + 1;
                                    }
                                } else if ($gender_id == 2) {

                                    if ($new_logic_flow == 7 or $new_logic_flow == 8 or $new_logic_flow == 24 or $new_logic_flow == 25 or $new_logic_flow == 26) {
                                        $new_logic_flow = $new_logic_flow + 1;
                                    }



                                    if ($new_logic_flow == 23 or $new_logic_flow == 21) {
                                        echo 'Logic ID is equal to 23 or 21 <br>';
                                        $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='Male' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();

                                        foreach ($get_clnt_outgoing_msg as $value) {
                                            $message = $value->message;
                                            $message_type_id = $value->message_type_id;
                                            $message_id = $value->id;
                                            $logic_flow_id = $value->logic_flow;
                                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                            $this->config->load('config', TRUE);
                                            // Retrieve a config item named site_name contained within the blog_settings array
                                            $source = $this->config->item('shortcode', 'config');

                                            $client_name = ucwords(strtolower($client_name)) . " ";

                                            $message = str_replace("XXX", $client_name, $message);

                                            echo "Outgoing Mesage 1 => " . $message . '<br>';
                                            $this->db->trans_start();
                                            $clnt_outgoing = array(
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
                                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
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

                                        if ($new_logic_flow == 26) {
                                            
                                        } else {
                                            
                                        }
                                        echo 'New Logic Flow ID => ' . $new_logic_flow . '<br>';
                                        echo 'This condition has been mettttttt.......';


                                        echo 'Logic ID is equal to other value.... <br>';
                                    }
                                }

                                if ($new_logic_flow != 3 or $new_logic_flow != 4 or $new_logic_flow != 5 or $new_logic_flow != 7 or $new_logic_flow != 8 or $new_logic_flow != 23 or $new_logic_flow != 26) {

                                    echo 'Logic ID is not equal to 3 or 4 or 5 or 7 or 8 or 23 or 26 <br> Our new logic id is => ' . $new_logic_flow . ' and language ID is =>   ' . $language_id . '<br>';

                                    $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();
                                    print_r($get_clnt_outgoing_msg);
                                    foreach ($get_clnt_outgoing_msg as $value) {
                                        $message = $value->message;
                                        $message_type_id = $value->message_type_id;
                                        $message_id = $value->id;
                                        $logic_flow_id = $value->logic_flow;
                                        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                                        $this->config->load('config', TRUE);
                                        // Retrieve a config item named site_name contained within the blog_settings array
                                        $source = $this->config->item('shortcode', 'config');

                                        $client_name = ucwords(strtolower($client_name)) . " ";

                                        $message = str_replace("XXX", $client_name, $message);

                                        echo "Outgoing Mesage 1 => " . $message . '<br>';
                                        $this->db->trans_start();
                                        $clnt_outgoing = array(
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
                                        $this->db->insert('clnt_outgoing', $clnt_outgoing);
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
                        $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where message_type_id=2 and logic_flow=1 and language_id='$language_id' ")->result();
                        foreach ($get_clnt_outgoing_msg as $value) {
                            $message = $value->message;
                            $message_type_id = $value->message_type_id;
                            $message_id = $value->id;
                            $logic_flow_id = $value->logic_flow;
                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                            $this->config->load('config', TRUE);
                            // Retrieve a config item named site_name contained within the blog_settings array
                            $source = $this->config->item('shortcode', 'config');


                            $this->db->trans_start();
                            echo "First Outgoing Message from the  System  => " . $message . '<br>';
                            $clnt_outgoing = array(
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
                            $this->db->insert('clnt_outgoing', $clnt_outgoing);
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
            $get_first_outoging_msg = $this->db->query("	
				SELECT
	tbl_client.phone_no,
	tbl_client.id,
	tbl_client.language_id,
	tbl_client.gender,
	tbl_client.group_id,
	tbl_client.f_name,
	DAYOFWEEK( CURDATE( ) ) as day_of_week
FROM
	tbl_client 
WHERE
	DAYOFWEEK( `tbl_client`.`created_at` ) = $dayofweek
	AND smsenable = 'Yes' 
	AND motivational_enable = 'Yes' 
GROUP BY
	tbl_client.id ")->result();
            foreach ($get_first_outoging_msg as $value) {
                $phone_no = $value->phone_no;
                $client_id = $value->id;
                $language_id = $value->language_id;
                $gender_id = $value->gender;
                $group_id = $value->group_id;
                $client_name = $value->f_name;

                $new_logic_flow = 1;

                $get_clnt_outgoing_msg = $this->db->query("Select * from tbl_messages where target_group='All' and message_type_id='2' and logic_flow='$new_logic_flow' and language_id='$language_id'")->result();


                foreach ($get_clnt_outgoing_msg as $value) {
                    $message = $value->message;
                    $message_type_id = $value->message_type_id;
                    $message_id = $value->id;
                    $logic_flow_id = $value->logic_flow;
                    // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                    $this->config->load('config', TRUE);
                    // Retrieve a config item named site_name contained within the blog_settings array
                    $source = $this->config->item('shortcode', 'config');

                    $client_name = ucwords(strtolower($client_name)) . " ";

                    $message = str_replace("XXX", $client_name, $message);

                    echo "Outgoing Mesage 1 => " . $message . '<br>';
                    $this->db->trans_start();
                    $clnt_outgoing = array(
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
                    $this->db->insert('clnt_outgoing', $clnt_outgoing);
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

    function clean_DOB() {


        $get_client_result = $this->db->query('Select * from tbl_client where clnd_dob IS NULL  ')->result();
        foreach ($get_client_result as $value) {
            $id = $value->id;
            $dob = $value->dob;

            if (!empty($dob)) {
                $dob2 = str_replace('/', '-', $dob);
                $cleaned_dob = date("Y-m-d", strtotime($dob2));
            }
            echo $cleaned_dob . '<br>';

            $data_update = array(
                'clnd_dob' => $cleaned_dob
            );
            $this->db->where('id', $id);
            $this->db->update('tbl_client', $data_update);
        }
    }

    function special_char() {
        $encrypted_msg = "Reg*MTIzNDUtMTI0NTEqTW9zZXMqQSpBdGVtYmEqMTIvMTAvMjAwMCoyKjIqMSoxLzgvMjAxOCoxLzgvMjAxOCowNzM1MjM1NzU1KjIqMSoyKjEwKjEqMQ==";
        $count_special = substr_count($encrypted_msg, "*");
        if ($count_special < 2) {
            //New Encrypted Message
            echo " New Encrypted Message => " . $count_special;
        } else {
            //Old Non Encrypted Message
            echo " Old Non Encrypted Message => " . $count_special;
        }
    }

    function affiliation() {
        $query = $this->db->query("select id, name from tbl_partner where status='Active'")->result();


        echo json_encode(["affiliations" => $query]);
    }

    function server() {

        $server_address = $_SERVER['SERVER_ADDR'];
        echo $server_address;




        // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
        $this->config->load('config', TRUE);
        // Retrieve a config item named site_name contained within the blog_settings array
        $source = $this->config->item('shortcode', 'config');
        $destination = "0714339521";
        $msg = "Our outgoing message to harris samuel dindid";
        $usr_otgoing_id = "User";
        $send_text = $this->AT_SMS($source, $destination, $msg, $usr_otgoing_id);
    }

    function map_clinic() {
        $get_clinic_id = $this->db->query("Select tbl_users.clinic_id, tbl_client.id as client_id from tbl_users inner join tbl_client on tbl_client.created_by = tbl_users.id")->result();
        foreach ($get_clinic_id as $value) {
            $clinic_id = $value->clinic_id;
            $client_id = $value->client_id;
            $data_array = array(
                'clinic_id' => $clinic_id
            );
            $this->db->where('id', $client_id);
            $this->db->update('client', $data_array);
        }
    }

    function cleanup() {
        /*
         * Select the  client records that meet the specified condition 
         * strip off the  first 5 characters from string position one 
         * strip off the  last 5 characters from  string postion last 
         */


        $get_client = $this->db->query(" SELECT 
    *
FROM
    ushauri_new.tbl_client where INSTR(clinic_number, '  ') > 0 ")->result();
        foreach ($get_client as $value) {
            $clinic_number = $value->clinic_number;
            $mfl_code = $value->mfl_code;
            $f_name = $value->f_name;
            $m_name = $value->m_name;
            $id = $value->id;
            echo $clinic_number . '<br>';

            $explode = explode("  ", $clinic_number);
            $mfl_code = @$explode[0];
            $ccc_no = @$explode[1];

            $new_clinic_number = $mfl_code . $ccc_no;
            $check_clinic_number_existence = $this->db->get_where('tbl_client', array('clinic_number' => $new_clinic_number,
                        'mfl_code' => $mfl_code,
                        'f_name' => $f_name))->num_rows();
            if ($check_clinic_number_existence > 0) {
                echo "clinic number =>  " . $new_clinic_number . ' already exists in the system  => ' . $check_clinic_number_existence . '<br>';
                $this->db->trans_start();
                $this->db->delete('tbl_client', array('id' => $id));
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    echo "Failed Clinic number => $clinic_number was not deleted...<br>";
                } else {
                    echo "Success Clinic number => $clinic_number was successfully deleted...<br>";
                }
            } else {
                echo "clinic number =>  " . $new_clinic_number . ' update was => ';
                $this->db->trans_start();
                $data_update = array(
                    'clinic_number' => $new_clinic_number
                );
                $this->db->where('id', $id);
                $this->db->update('tbl_client', $data_update);
                $this->db->trans_complete();
                if ($this->db->trans_status() === TRUE) {
                    echo 'Success ...<br>';
                } else {
                    echo 'Failed...<br>';
                }
            }
        }
    }

    function cleanup2() {
        /*
         * Select the  client records that meet the specified condition 
         * strip off the  first 5 characters from string position one 
         * strip off the  last 5 characters from  string postion last 
         */


        $get_client = $this->db->query(" SELECT 
    *
FROM
    ushauri_new.tbl_client where INSTR(clinic_number, '_') > 0 ")->result();
        foreach ($get_client as $value) {
            $clinic_number = $value->clinic_number;
            $id = $value->id;
            echo $clinic_number . '<br>';

            $explode = explode("_", $clinic_number);
            $mfl_code = @$explode[0];
            $ccc_no = @$explode[1];

            $new_clinic_number = $mfl_code . $ccc_no;
            $check_clinic_number_existence = $this->db->get_where('tbl_client', array('clinic_number' => $new_clinic_number))->num_rows();
            if ($check_clinic_number_existence > 0) {
                echo "clinic number =>  " . $new_clinic_number . ' already exists in the system  => ' . $check_clinic_number_existence . '<br>';
            } else {
                echo "clinic number =>  " . $new_clinic_number . ' update was => ';
                $this->db->trans_start();
                $data_update = array(
                    'clinic_number' => $new_clinic_number
                );
                $this->db->where('id', $id);
                $this->db->update('tbl_client', $data_update);
                $this->db->trans_complete();
                if ($this->db->trans_status() === TRUE) {
                    echo 'Success ...<br>';
                } else {
                    echo 'Failed...<br>';
                }
            }
        }
    }

    function cleanup3() {
        /*
         * Select the  client records that meet the specified condition 
         * strip off the  first 5 characters from string position one 
         * strip off the  last 5 characters from  string postion last 
         */



        $get_client = $this->db->query(" SELECT 
    *
FROM
    ushauri_new.tbl_client where INSTR(clinic_number, ' ') > 0  ")->result();
        foreach ($get_client as $value) {
            $clinic_number = $value->clinic_number;
            $id = $value->id;
            echo $clinic_number . '<br>';

            $explode = explode(" ", $clinic_number);
            $mfl_code = @$explode[0];
            $ccc_no = @$explode[1];

            $new_clinic_number = $mfl_code . $ccc_no;
            echo $new_clinic_number;
            $check_clinic_number_existence = $this->db->get_where('tbl_client', array('clinic_number' => $new_clinic_number))->num_rows();
            if ($check_clinic_number_existence > 0) {
                echo "clinic number =>  " . $new_clinic_number . ' already exists in the system  => ' . $check_clinic_number_existence . '<br>';
            } else {
                echo "clinic number =>  " . $new_clinic_number . ' update was => ';
                $this->db->trans_start();



                $this->db->query(" UPDATE IGNORE `tbl_client` SET `clinic_number` = REPLACE(`clinic_number`, '  ', '') ");


                $this->db->trans_complete();
                if ($this->db->trans_status() === TRUE) {
                    echo 'Success ...<br>';
                } else {
                    echo 'Failed...<br>';
                }
            }
        }
    }

    function cleanup4() {
        /*
         * Select the  client records that meet the specified condition 
         * strip off the  first 5 characters from string position one 
         * strip off the  last 5 characters from  string postion last 
         */



        $get_client = $this->db->query(" SELECT * FROM tbl_client  ")->result();
        foreach ($get_client as $value) {
            $clinic_number = $value->clinic_number;
            $id = $value->id;


            $check_ccc_no = strpos($clinic_number, "'") !== false;
            if ($check_ccc_no) {
                echo "TRUE";
                echo $clinic_number . '<br>';



                $explode = explode(" ", $clinic_number);
                $mfl_code = @$explode[0];
                $ccc_no = @$explode[1];

                $new_clinic_number = str_replace("'", "", $clinic_number);
                echo $new_clinic_number;
                $check_clinic_number_existence = $this->db->get_where('tbl_client', array('clinic_number' => $new_clinic_number))->num_rows();
                if ($check_clinic_number_existence > 0) {
                    echo "clinic number =>  " . $new_clinic_number . ' already exists in the system  => ' . $check_clinic_number_existence . '<br>';
                } else {
                    echo "clinic number =>  " . $new_clinic_number . ' update was => ';
                    $this->db->trans_start();



                    $this->db->query(" UPDATE IGNORE `tbl_client` SET `clinic_number` = $new_clinic_number WHERE id = $id ");


                    $this->db->trans_complete();
                    if ($this->db->trans_status() === TRUE) {
                        echo 'Success ...<br>';
                    } else {
                        echo 'Failed...<br>';
                    }
                }
            } else {

                echo "FALSE";
                echo $clinic_number . '<br>';
            }
        }
    }

    function send_sms() {
        $source = '40146';
        $destination = '0714339521';
        $msg = 'This is trial ';
        $outgoing_id = 'Hello ....';
        $this->send_message($source, $destination, $msg, $outgoing_id);
    }

}
