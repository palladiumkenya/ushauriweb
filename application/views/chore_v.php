<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Calling a Function Repeatedly with setInterval() Method</title>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script type="text/javascript">
            var myVar;
            function showTime() {
                var d = new Date();
                var t = d.toLocaleTimeString();
                console.log(t);
                $("#clock").html("Start Time : " + t); // display time on the page
            }
            function stopFunction() {
                clearInterval(myVar); // stop the timer
            }
            $(document).ready(function () {
                setInterval(function () {
                    sender();
                    trigger();
                    receiver();
                    send_mail();
                    broadcast();
                    scheduler();

                }, 30000);
                setInterval(function () {
                    window.location.reload(1);

                }, 300000);




                setInterval('showTime()', 1000);


                function sender() {
                    $(".sender_p").empty();
                    $.ajax({
                        url: "<?php echo base_url(); ?>chore/sender",
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {
                            $(".sender_p").append("Sender is running");
                        }, error: function (errorThrown) {
                            $(".sender_p").append("Sender is not running");
                        }
                    });
                }

                function scheduler() {
                    $(".scheduler_p").empty();
                    $.ajax({
                        url: "<?php echo base_url(); ?>chore/scheduler",
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {
                            $(".scheduler_p").append("Scheduler is running");
                        }, error: function (errorThrown) {
                            $(".scheduler_p").append("Scheduler is not running");
                        }
                    });


                }

                function receiver() {
                    $(".receiver_p").empty();
                    $.ajax({
                        url: "<?php echo base_url(); ?>chore/receiver",
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {
                            $(".receiver_p").append("Receiver is running");
                        }, error: function (errorThrown) {
                            $(".receiver_p").append("Receiver is not running");
                        }
                    });

                }


                function trigger() {
                    $(".trigger_p").empty();
                    $.ajax({
                        url: "<?php echo base_url(); ?>chore/trigger",
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {
                            $(".trigger_p").append("Trigger is running");
                        }, error: function (errorThrown) {
                            $(".trigger_p").append("Trigger is not running");
                        }
                    });


                }

                function send_mail() {
                    $(".send_mail_p").empty();
                    $.ajax({
                        url: "<?php echo base_url(); ?>chore/send_mail",
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {
                            $(".send_mail_p").append("Send mail is running");
                        }, error: function (errorThrown) {
                            $(".send_mail_p").append("Send mail is not running");
                        }
                    });

                }

                function broadcast() {
                    $(".broadcast_p").empty();
                    $.ajax({
                        url: "<?php echo base_url(); ?>chore/broadcast",
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {
                            $(".broadcast_p").append("Broadcast is running");
                        }, error: function (errorThrown) {
                            $(".broadcast_p").append("Broadcast is not running");
                        }
                    });

                }

            });


        </script>
    </head>
    <body>
        <p id="clock"></p>
        <p class="sender_p">Sender is Running</p>
        <p class="trigger_p">Trigger is Running</p>
        <p class="scheduler_p">Scheduler is Running</p>
        <p class="broadcast_p">Broadcast is Running</p>
        <p class="send_email_p">Send Email is Running</p>
        <p class="receiver_p">Receiver is Running </p>

    </body>
</html>                                		