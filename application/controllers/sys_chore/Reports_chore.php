<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reports_chore extends MY_Controller {

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
  tbl_gender.`name` AS `Gender`,
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
        $result = $this->db->query($query)->result();


        $this->load->library('Excel');


        $objPHPexcel = PHPExcel_IOFactory::load(APPPATH . "report_template.xlsx");
        $objWorksheet = $objPHPexcel->getActiveSheet();

        foreach ($result as $value) {
            $objWorksheet->getCell('B30')->setValue($value->Gender);
            $objWorksheet->getCell('B31')->setValue($value->Gender);
        }



        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Reports');
        //prepare download
        $filename = mt_rand(1, 100000) . '.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!













        $save_file = $objWriter->save(APPPATH . "final_report.xlsx");
        //write_file(FCPATH . '/documents/sys_reports/reports.csv', $objWriter);
        if (!$save_file) {
            echo 'Unable to write the file';
        } else {

            echo 'Report saved succesfully!';
        }


        //file_put_contents($filename, $data);
        //force_download($filename, $data);
        //return $result;
    }

    function trial() {



//include PHPExcel library
        $this->load->library('Excel');

//load Excel template file
        $objTpl = PHPExcel_IOFactory::load(APPPATH . "report_template.xlsx");
        $objTpl->setActiveSheetIndex(0);  //set first sheet as active

        $objTpl->getActiveSheet()->setCellValue('C2', date('Y-m-d'));  //set C1 to current date
        $objTpl->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //C1 is right-justified

        $objTpl->getActiveSheet()->setCellValue('C3', stripslashes('DINDI HARRIS SAMUEL'));
        $objTpl->getActiveSheet()->setCellValue('C4', stripslashes('THIS IS TRIAL MESSAGE ....'));

        $objTpl->getActiveSheet()->getStyle('C4')->getAlignment()->setWrapText(true);  //set wrapped for some long text message

        $objTpl->getActiveSheet()->getColumnDimension('C')->setWidth(40);  //set column C width
        $objTpl->getActiveSheet()->getRowDimension('4')->setRowHeight(120);  //set row 4 height
        $objTpl->getActiveSheet()->getStyle('A4:C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); //A4 until C4 is vertically top-aligned
//prepare download
        $filename = mt_rand(1, 100000) . '.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

        exit; //done.. exiting!
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
            $description = 'Unable to generate Client Age Distribution. ';
            $user_id = '1';
            $this->log_action($description, $user_id);
        } else {

            $description = 'Report Client Age Distribution was generated successfully.';
            $user_id = '1';
            $this->log_action($description, $user_id);
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
            $description = 'Unable to generate Client Age Distribution. ';
            $user_id = '1';
            $this->log_action($description, $user_id);
        } else {

            $description = 'Report Client Age Distribution was generated successfully.';
            $user_id = '1';
            $this->log_action($description, $user_id);
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
            echo 'Output => ' . $out_put;
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
                . "tbl_client.smsenable AS ENABLE_SMS ,tbl_appointment.appntmnt_date AS APPOINTMENT_DATE,"
                . "tbl_appointment.app_status AS APPOINTMENT_STATUS,tbl_appointment.app_msg AS APPOINTMENT_MESSAGE,tbl_appointment.updated_at APPOINTMENT_TIME_STAMP "
                . "from tbl_client inner join tbl_language on tbl_language.id = tbl_client.language_id inner join tbl_groups on tbl_groups.id = tbl_client.group_id inner join tbl_appointment on tbl_appointment.client_id = tbl_client.id"
                . " where tbl_client.status='Active'  group by tbl_client.id  ";
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

    function sendmail() {
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
        $this->email->to('hdindi@mhealthkenya.org');
        // Subject of email
        $this->email->subject('Ushauri System Generated Report');
        // Message in email
        $this->email->message('Please find attached automated report from the  Ushauri system....');

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
    
    function daily_notiifcations() {
        
    }

}
