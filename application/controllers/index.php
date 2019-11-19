<?php
session_start();
error_reporting(E_ERROR | E_PARSE);     //ERROR SUPPRESSION
include("conn.php");
$con = getcon();
?>
<html>
    <head> 
        <title> Mhealth - Router </title>
        <META HTTP-EQUIV="REFRESH" CONTENT="10"></meta>
        <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body id="loggerdiv">
        <?php print "InfoBip router is on"; ?>
    <marquee  loop="infinite" behavior="slide"
              direction="down" height="100%" scrollamount="1">
                  <?php
// xxxxxxxxxxxxxxxxxxxxxxx  Receiving Messages xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                  include_once("DbInterface.php");
                  $dbi = new DbInterface();

                  $qry = "select * from tbl_receiver where status ='not processed'";
                  $rs = mysql_query($qry, $con) or die("Unable to get incoming messages " . mysql_error());
     
        while ($row = mysql_fetch_array($rs)) {
            $id = $row['id'];
            $shortCode = $row['receiver'];
            $MSISDN = $row['sender'];
            $message = $row['text'];
            $msgDateCreated = $row['timestamp'];
            $createdOn = $row['timestamp'];
            $status = $row['status'];



            if ($shortCode == 40145) { //mpep shortcode
                $mpep = mpep();

                $insert = 'insert into inbox (shortCode,MSISDN,message,msgDateCreated,createdOn,message_id)
		values ("' . $shortCode . '","' . $MSISDN . '","' . $message . '","' . $msgDateCreated . '","' . $createdOn . '","' . $id . '")';
                mysql_query($insert, $mpep) or die("Unable to receive message to mpep DB " . mysql_error());
                print "Successfully inserted into mpep db";
            } else if ($shortCode == 40146) {   //daily stock shortcode
                $t4a = t4a();

                $insert = 'insert into ozekimessagein (receiver,sender,msg,senttime,receivedtime,reference)
		values ("' . $shortCode . '","' . $MSISDN . '","' . $message . '","' . $msgDateCreated . '","' . $createdOn . '","' . $id . '")';
                mysql_query($insert, $t4a) or die("Unable to receive message to T4A DB " . mysql_error());
                print "Successfully inserted into t4a db";
            } else if ($shortCode == 40147) {   //daily stock shortcode
                $mlab = mlab();

                $insert = 'insert into inbox (shortCode,MSISDN,message,msgDateCreated,createdOn,message_id)
		values ("' . $shortCode . '","' . $MSISDN . '","' . $message . '","' . $msgDateCreated . '","' . $createdOn . '","' . $id . '")';
                mysql_query($insert, $mlab) or die("Unable to receive message mlab DB " . mysql_error());
                print "Successfully inserted into t4l db";
            } else if ($shortCode == 40148) {   //daily stock shortcode
                $t4l = t4l();

                $insert = 'insert into inbox (shortCode,MSISDN,message,msgDateCreated,createdOn,message_id)
		values ("' . $shortCode . '","' . $MSISDN . '","' . $message . '","' . $msgDateCreated . '","' . $createdOn . '","' . $id . '")';
                mysql_query($insert, $t4l) or die("Unable to receive message T4L DB " . mysql_error());
                print "Successfully inserted into t4l db";
            } else if ($shortCode == 40149) {   //daily stock shortcode
                $kemsa = kemsa();

                $insert = 'insert into inbox (shortCode,MSISDN,message,msgDateCreated,createdOn,message_id)
		values ("' . $shortCode . '","' . $MSISDN . '","' . $message . '","' . $msgDateCreated . '","' . $createdOn . '","' . $id . '")';
                mysql_query($insert, $kemsa) or die("Unable to receive message KEMSA DB " . mysql_error());
                print "Successfully inserted into kemsa db";
            }
            //--optimization
            /*  BEST ==>remove from results table 
              $delete='delete from results';
              mysql_query($delete,$con) or die("Unable to move record ".mysql_error());
              //print "Processing Incoming Messages"; */

// update results - incoming processed results --optimization
//Successfully inserted into mpep dbUnable to update record Table 'mpep_dev.results' doesn't exist
            $infobip = infobip();
            $update = "update  tbl_receiver set status = 'processed'";
            mysql_query($update, $infobip) or die("Unable to update record on Infobip DB " . mysql_error());
            //print "Processing Incoming Messages";	 	
        }

        function URLopen($url) {
            // Fake the browser type
            ini_set('user_agent', 'MSIE 4\.0b2;');

            $dh = fopen("$url", 'r');
            $result = fread($dh, 8192);
            return $result;
        }

// xxxxxxxxxxxxxxxxxxxxxxxxxxxxx End Receiving Broadcast SMS Messages xxxxxxxxxxxxxxxxxx
        print '</marquee>';
        ?>


    </body>
</html>