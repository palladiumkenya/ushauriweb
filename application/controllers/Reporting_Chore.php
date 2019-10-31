<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reporting_Chore extends MY_Controller {

    public $data = '';

    function __construct() {
        parent::__construct();


        //$this->load->library("infobip");

        $this->data = new DBCentral();
    }

    function index() {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";

        $query = "    SELECT 
  tbl_gender.`name` AS `Gender/month`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Jan 17' 
      THEN tbl_client.id 
    END
  ) `Jan 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Feb 17' 
      THEN tbl_client.id 
    END
  ) `Feb 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Mar 17' 
      THEN tbl_client.id 
    END
  ) `Mar 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Apr 17' 
      THEN tbl_client.id 
    END
  ) `Apr 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'May 17' 
      THEN tbl_client.id 
    END
  ) `May 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Jun 17' 
      THEN tbl_client.id 
    END
  ) `Jun 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Jul 17' 
      THEN tbl_client.id 
    END
  ) `Jul 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Aug 17' 
      THEN tbl_client.id 
    END
  ) `Aug 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Sep 17' 
      THEN tbl_client.id 
    END
  ) `Sep 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Oct 17' 
      THEN tbl_client.id 
    END
  ) `Oct 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Nov 17' 
      THEN tbl_client.id 
    END
  ) `Nov 17`,
  COUNT(
    CASE
      WHEN DATE_FORMAT(tbl_client.created_at, '%b %y') = 'Dev 17' 
      THEN tbl_client.id 
    END
  ) `Dev 17` 
FROM
  tbl_client 
  INNER JOIN tbl_gender 
    ON tbl_gender.`id` = tbl_client.`gender` 
GROUP BY tbl_gender.`id`           ";
        $result = $this->db->query($query);
        $filename = "reports.csv";
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        write_file(FCPATH . '/documents/sys_reports/reports.csv', $data);
        if (!write_file(FCPATH . '/documents/sys_reports/reports.csv', $data)) {
            echo 'Unable to write the file';
        } else {

            echo 'Report saved succesfully!';
        }


        file_put_contents($filename, $data);
        //force_download($filename, $data);
        //return $result;
    }

    function clnt_age_report_generator() {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";

        $query = " SELECT 
  tbl_groups.`name` AS `Group/month`,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'January' 
      THEN tbl_client.id 
    END
  ) January,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'February' 
      THEN tbl_client.id 
    END
  ) February,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'March' 
      THEN tbl_client.id 
    END
  ) March,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'April' 
      THEN tbl_client.id 
    END
  ) April,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'May' 
      THEN tbl_client.id 
    END
  ) May,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'June' 
      THEN tbl_client.id 
    END
  ) June,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'July' 
      THEN tbl_client.id 
    END
  ) July,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'August' 
      THEN tbl_client.id 
    END
  ) August,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'September' 
      THEN tbl_client.id 
    END
  ) September,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'October' 
      THEN tbl_client.id 
    END
  ) October,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'November' 
      THEN tbl_client.id 
    END
  ) November,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.created_at) = 'December' 
      THEN tbl_client.id 
    END
  ) December 
FROM
  tbl_client 
  INNER JOIN tbl_groups 
    ON tbl_groups.`id` = tbl_client.`group_id` 
WHERE tbl_groups.`status` = 'Active' 
GROUP BY tbl_groups.`id`   ";
        $result = $this->db->query($query);
        $filename = "client_age_distribution_report.csv";
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        write_file(FCPATH . '/documents/sys_reports/client_age_distribution_report.csv', $data);
        if (!write_file(FCPATH . '/documents/sys_reports/client_age_distribution_report.csv', $data)) {
            echo 'Unable to write the file';
        } else {

            echo 'Report saved succesfully!';
        }


        file_put_contents($filename, $data);
        //force_download($filename, $data);
        //return $result;
    }

    function consented_clnt_distribution_report_generator() {

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";

        $query = " SELECT 
  tbl_groups.`name` AS `Group/month`,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'January' 
      THEN tbl_client.id 
    END
  ) January,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'February' 
      THEN tbl_client.id 
    END
  ) February,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'March' 
      THEN tbl_client.id 
    END
  ) March,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'April' 
      THEN tbl_client.id 
    END
  ) April,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'May' 
      THEN tbl_client.id 
    END
  ) May,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'June' 
      THEN tbl_client.id 
    END
  ) June,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'July' 
      THEN tbl_client.id 
    END
  ) July,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'August' 
      THEN tbl_client.id 
    END
  ) August,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'September' 
      THEN tbl_client.id 
    END
  ) September,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'October' 
      THEN tbl_client.id 
    END
  ) October,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'November' 
      THEN tbl_client.id 
    END
  ) November,
  COUNT(
    CASE
      WHEN MONTHNAME(tbl_client.`consent_date`) = 'December' 
      THEN tbl_client.id 
    END
  ) December 
FROM
  tbl_client 
  INNER JOIN tbl_groups 
    ON tbl_groups.`id` = tbl_client.`group_id` 
WHERE tbl_client.`smsenable` = 'Yes' 
  AND tbl_groups.`status` = 'Active' 
GROUP BY tbl_groups.`id`  ";
        $result = $this->db->query($query);
        $filename = "consented_client_distribution_report.csv";
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        write_file(FCPATH . '/documents/sys_reports/consented_client_distribution_report.csv', $data);
        if (!write_file(FCPATH . '/documents/sys_reports/consented_client_distribution_report.csv', $data)) {
            echo 'Unable to write the file';
        } else {

            echo 'Report saved succesfully!';
        }


        file_put_contents($filename, $data);
        //force_download($filename, $data);
        //return $result;
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
                . "tbl_client.smsenable AS ENABLE_SMS  ,tbl_appointment.appntmnt_date AS APPOINTMENT_DATE,"
                . "tbl_appointment.app_status AS APPOINTMENT_STATUS,tbl_appointment.app_msg AS APPOINTMENT_MESSAGE,tbl_appointment.updated_at APPOINTMENT_TIME_STAMP "
                . "from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id inner join tbl_groups on tbl_groups.id = tbl_client.group_id inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " where tbl_client.status='Active' group by tbl_client.id  ";
        $result = $this->db->query($query);
        $filename = "appointments.csv";
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        write_file(FCPATH . '/documents/sys_reports/appointments.csv', $data);
        if (!write_file(FCPATH . '/documents/sys_reports/appointments.csv', $data)) {
            echo 'Unable to write the file';
        } else {

            echo 'Report saved succesfully!';
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
        $this->email->message('Messae... going...out.....');

        if ($this->email->send(FALSE)) {
            // // // echo $this->email->print_debugger();
            $output = 'Email Successfully Send !';
        } else {
            // // // echo $this->email->print_debugger();
            $output = '<p class="error_msg">Invalid Gmail Account or Password !</p>';
        }
        // // // echo $output;
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

    function remove_unauth_modules() {



        set_time_limit(900);



        /*
         * 
         * Option Two
         * Check if there are any modules in the  user permission that are not supposed to be there ....
         * This modules should be marked as in active
         */

        //Get user permission
        $get_user_permission = $this->db->query("SELECT * FROM tbl_user_permission WHERE STATUS='Active' LIMIT 1000");

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
                    // // // echo 'Mark as in active ....permission id => ' . $user_permission_id . ' and .....';
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
                        // // // echo 'Un necessarry modules were removed .... <br>';
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

    function user_broadcast_scheduler() {
        $get_users = $this->db->query('Select * from tbl_users')->result();
        foreach ($get_users as $value) {
            $user_id = $value->id;
            $phone_no = $value->phone_no;
            $user_name = $value->f_name;
            $text = "Dear XXX, 
                
This is to inform you that the Ushauri System has been upgraded to a new version. As part of the transition, you are required to update your Mobile Application before tomorrow morning 8:00Hrs. 
To access the new version of the mobile application, kindly go to Google Play Store and search for Ushauri. And install the application. 
In case of assistance, kindly contact: +254727422282, +254714339521, +254707049575
Thank you

For USHAURI: Getting better one text at a time. 
";

            $cleaned_msg = str_replace("XXX", $user_name, $text);


            $created_at = date('Y-m-d H:i:s');
            $source = '40148';
            $client_destination = $phone_no;
            // // echo $cleaned_msg;
            exit();
            $this->db->trans_start();

            $data_outgoing = array(
                'destination' => $client_destination,
                'source' => $source,
                'msg' => $cleaned_msg,
                'status' => 'Not Sent',
                'message_type_id' => '5',
                'responded' => 'No',
                'clnt_usr_id' => $user_id,
                'recepient_type' => 'User',
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

    function clnt_to_upper() {
        $get_clients = $this->db->query("SELECT * FROM tbl_client WHERE id BETWEEN 1700 AND 1800 ")->result();
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
                // // echo 'Failed...';
            } else {
                // // echo $f_name . ' ' . $m_name . ' ' . $l_name . '<br>';
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

    function remove_msg_dup() {
        $get_clients = $this->db->query("Select * from tbl_outgoing  inner join tbl_client on tbl_client.id = tbl_outgoing.clnt_usr_id where recepient_type='Client'  and message_type_id='3' and is_deleted='0' group by destination LIMIT 1000 ");

//        $num_rows = $get_clients->num_rows();
//        if ($num_rows > 1) {
//            $get_rows = $get_clients->result();
//            foreach ($get_rows as $value) {
//                $msg = $value->msg;
//                echo $msg;
//            }
//        }

        foreach ($get_clients->result() as $value) {
//            $client_id = $value->id;
//            $phone_no = $value->phone_no;
//
//
//            $mobile = substr($phone_no, -9);
//            $len = strlen($mobile);
//            if ($len < 10) {
//                $destination = "254" . $mobile;
//            }
//            
            $clnt_usr_id = $value->clnt_usr_id;
            $destination = $value->destination;
            echo $destination;

            $sql = $this->db->query("Select * from tbl_outgoing where message_type_id='3' and destination='$destination' and msg LIKE '%Ushauri is an electronic SMS Reminder System developed to enhance drug adherence, client retention in care and broadcast health messages %' and is_deleted='0' LIMIT 1000");
            $num_rows = $sql->num_rows();

            if ($num_rows > 1) {
                echo 'Num Rows => ' . $num_rows . '</br>';
                $get_rows = $sql->result();
                foreach ($get_rows as $value) {
                    $id = $value->id;
                    $clnt_usr_id = $value->clnt_usr_id;
                    echo 'Out going ID => ' . $id . '<br>';
                    $update_row = array(
                        'is_deleted' => '1'
                    );
                    $this->db->where('id', $id);
                    $this->db->where('clnt_usr_id', $clnt_usr_id);
                    $this->db->update('outgoing', $update_row);
                    /// echo $msg;
                }
            }
        }
    }

    function remove_msg_dup2() {
        $get_clients = $this->db->query("Select * from tbl_outgoing  inner join tbl_client on tbl_client.id = tbl_outgoing.clnt_usr_id where recepient_type='Client' and language_id='2' and message_type_id='3' and is_deleted='0' group by destination LIMIT 1000 ");

//        $num_rows = $get_clients->num_rows();
//        if ($num_rows > 1) {
//            $get_rows = $get_clients->result();
//            foreach ($get_rows as $value) {
//                $msg = $value->msg;
//                echo $msg;
//            }
//        }

        foreach ($get_clients->result() as $value) {
//            $client_id = $value->id;
//            $phone_no = $value->phone_no;
//
//
//            $mobile = substr($phone_no, -9);
//            $len = strlen($mobile);
//            if ($len < 10) {
//                $destination = "254" . $mobile;
//            }
//            
            $clnt_usr_id = $value->clnt_usr_id;
            $destination = $value->destination;
            //echo $destination;

            $sql = $this->db->query("Select * from tbl_outgoing where message_type_id='3' and destination='$destination' and msg LIKE '%Thank you for enrolling to Ushauri Sms platform %' and is_deleted='0' LIMIT 1000 ");
            $num_rows = $sql->num_rows();

            if ($num_rows > 1) {
                //echo 'Num Rows => ' . $num_rows . '</br>';
                $get_rows = $sql->result();
                foreach ($get_rows as $value) {
                    $id = $value->id;
                    $clnt_usr_id = $value->clnt_usr_id;
                    //echo 'Out going ID => ' . $id . '<br>';
                    $update_row = array(
                        'is_deleted' => '1'
                    );
                    $this->db->where('id', $id);
                    $this->db->where('clnt_usr_id', $clnt_usr_id);
                    $this->db->update('outgoing', $update_row);
                    //echo $msg;
                }
            }
        }
    }

    function remove_msg_dup3() {
        $get_clients = $this->db->query("Select * from tbl_outgoing  inner join tbl_client on tbl_client.id = tbl_outgoing.clnt_usr_id where recepient_type='Client' and language_id='1' and message_type_id='3' and is_deleted='0' group by destination LIMIT 1000 ");

//        $num_rows = $get_clients->num_rows();
//        if ($num_rows > 1) {
//            $get_rows = $get_clients->result();
//            foreach ($get_rows as $value) {
//                $msg = $value->msg;
//                echo $msg;
//            }
//        }

        foreach ($get_clients->result() as $value) {
//            $client_id = $value->id;
//            $phone_no = $value->phone_no;
//
//
//            $mobile = substr($phone_no, -9);
//            $len = strlen($mobile);
//            if ($len < 10) {
//                $destination = "254" . $mobile;
//            }
//            
            $clnt_usr_id = $value->clnt_usr_id;
            $destination = $value->destination;
            //echo $destination;

            $sql = $this->db->query("Select * from tbl_outgoing where message_type_id='3' and destination='$destination' and msg LIKE '%Shukrani kwa kujisajili katika Ushauri %' and is_deleted='0' LIMIT 1000");
            $num_rows = $sql->num_rows();

            if ($num_rows > 1) {
                //echo 'Num Rows => ' . $num_rows . '</br>';
                $get_rows = $sql->result();
                foreach ($get_rows as $value) {
                    $id = $value->id;
                    $clnt_usr_id = $value->clnt_usr_id;
                    //echo 'Out going ID => ' . $id . '<br>';
                    $update_row = array(
                        'is_deleted' => '1'
                    );
                    $this->db->where('id', $id);
                    $this->db->where('clnt_usr_id', $clnt_usr_id);
                    $this->db->update('outgoing', $update_row);
                    //echo $msg;
                }
            }
        }
    }

}
