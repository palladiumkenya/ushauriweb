<?php

$receiver = $_GET['receiver'];
$sender = $_GET['sender'];
$when = $_GET['when'];
$text = $_GET['text'];
    //connect to mysql db
    $con = mysql_connect("localhost","mlab","Mlab123!@#") or die('Could not connect: ' . mysql_error());
    //connect to the message_logs database
    mysql_select_db("message_logs", $con);

   
//Make sure that it is a GET request.
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') != 0){
    throw new Exception('Request method must be GET!');
}
 


   
    
    //insert into mysql table
    $sql = "INSERT INTO tbl_receiver(receiver, sender, time, text)
    VALUES('$receiver', '$sender', '$when', '$text')";
    if(!mysql_query($sql,$con))
    {
        die('Error : ' . mysql_error());
    }
	
	 //insert into mysql table
    $sql1 = "INSERT INTO msg_logs(receiver, sender, time, text)
    VALUES('$receiver', '$sender', '$when', '$text')";
    if(!mysql_query($sql1,$con))
    {
        die('Error : ' . mysql_error());
    }
?>