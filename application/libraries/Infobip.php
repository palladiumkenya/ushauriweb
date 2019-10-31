<<<<<<< HEAD
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter Infobip SMS endpoint Class
 *
 * This library will help import retrieve southwell SMS based on your user credentials  
 * 
 * This library treats the first row of a CSV file
 * as a column header row.
 * 
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Dindi Harris
 */
class Infobip {

    public function pull_messages($username, $password, $date_from, $date_to) {


        $URL = 'http://sms.southwell.io/api/v1/smsprintersent?limit=100&page=1&start_date=' . $date_from . '&end_date=' . $date_to . '';

        $httpRequest = curl_init($URL);

        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: '));
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_USERPWD, "$username:$password");
        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);


        return $results;
    }

    public function old_send_message($username, $password, $source, $destination, $msg) {
        error_reporting(E_ERROR | E_PARSE);


        $senderid = $source;

        if ($destination <> '') {
            $from = $senderid;
            $id = $id;
            $text = $msg;
            $notifyUrl = "197.248.10.20/infobip/receiver.php";
            $notifyContentType = "application/json";
            $callbackData = "1";
            $username = $username;
            $password = $password;
            $postUrl = "https://api.infobip.com/sms/1/text/advanced";
            // creating an object for sending SMS
            $destination = array("$id" => $id,
                "to" => $destination);
            $message = array("from" => $from,
                "destinations" => array($destination),
                "text" => $text,
                "notifyUrl" => $notifyUrl,
                "notifyContentType" => $notifyContentType,
                "callbackData" => $callbackData);
            $postData = array("messages" => array($message));
            $postDataJson = json_encode($postData);
            $ch = curl_init();
            $header = array("Content-Type:application/json", "Accept:application/json");
            curl_setopt($ch, CURLOPT_URL, $postUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // response of the POST request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $responseBody = json_decode($response);
            curl_close($ch);
            if ($httpCode >= 200 && $httpCode < 300) {
                $messages = $responseBody->messages;
                return $messages;
            } else {
                return $responseBody->requestError->serviceException->text;
            }
        } else {
            $out_put = " Reason: Phone number is missing";
            return $out_put;
        }
    }

    public function send_message($username, $password, $source, $destination, $msg) {
        error_reporting(E_ERROR | E_PARSE);


        $senderid = $source;

        if ($destination <> '') {




            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_PORT => "3001",
                CURLOPT_URL => "http://197.248.10.20:3001/api/senders/sender",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "destination: $destination",
                    "msg: $msg",
                    "source: $senderid"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $out_put = " Reason: Phone number is missing";
            return $out_put;
        }
    }

}
=======
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter Infobip SMS endpoint Class
 *
 * This library will help import retrieve southwell SMS based on your user credentials  
 * 
 * This library treats the first row of a CSV file
 * as a column header row.
 * 
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Dindi Harris
 */
class Infobip {

    public function pull_messages($username, $password, $date_from, $date_to) {


        $URL = 'http://sms.southwell.io/api/v1/smsprintersent?limit=100&page=1&start_date=' . $date_from . '&end_date=' . $date_to . '';

        $httpRequest = curl_init($URL);

        curl_setopt($httpRequest, CURLOPT_NOBODY, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: '));
        curl_setopt($httpRequest, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($httpRequest, CURLOPT_USERPWD, "$username:$password");
        $results = curl_exec($httpRequest);
        $status_code = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
        curl_close($httpRequest);


        return $results;
    }

    public function old_send_message($username, $password, $source, $destination, $msg) {
        error_reporting(E_ERROR | E_PARSE);


        $senderid = $source;

        if ($destination <> '') {
            $from = $senderid;
            $id = $id;
            $text = $msg;
            $notifyUrl = "197.248.10.20/infobip/receiver.php";
            $notifyContentType = "application/json";
            $callbackData = "1";
            $username = $username;
            $password = $password;
            $postUrl = "https://api.infobip.com/sms/1/text/advanced";
            // creating an object for sending SMS
            $destination = array("$id" => $id,
                "to" => $destination);
            $message = array("from" => $from,
                "destinations" => array($destination),
                "text" => $text,
                "notifyUrl" => $notifyUrl,
                "notifyContentType" => $notifyContentType,
                "callbackData" => $callbackData);
            $postData = array("messages" => array($message));
            $postDataJson = json_encode($postData);
            $ch = curl_init();
            $header = array("Content-Type:application/json", "Accept:application/json");
            curl_setopt($ch, CURLOPT_URL, $postUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // response of the POST request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $responseBody = json_decode($response);
            curl_close($ch);
            if ($httpCode >= 200 && $httpCode < 300) {
                $messages = $responseBody->messages;
                return $messages;
            } else {
                return $responseBody->requestError->serviceException->text;
            }
        } else {
            $out_put = " Reason: Phone number is missing";
            return $out_put;
        }
    }

    public function send_message($username, $password, $source, $destination, $msg) {
        error_reporting(E_ERROR | E_PARSE);


        $senderid = $source;

        if ($destination <> '') {




            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_PORT => "3001",
                CURLOPT_URL => "http://197.248.10.20:3001/api/senders/sender",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "destination: $destination",
                    "msg: $msg",
                    "source: $senderid"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $out_put = " Reason: Phone number is missing";
            return $out_put;
        }
    }

}
>>>>>>> 035cde8761d07a7ac33b6310b0d47983cd9c9298
