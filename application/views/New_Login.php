<!DOCTYPE html>
<html lang="en">
    <style>
        @import url(line-icons.css);	
        @import url(http://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic);
        @import "https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css";
        /* Full-width input fields */
        input[type=text], input[type=password] {
            width: 100%;
            padding: 18px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border: 1px solid #007aff;
            color: c0392b;
        }

        input:focus + .fa {
            color: #007aff;
        }

        .input-icons {
            position: relative;
            margin: 5px 0px 5px 0px;
        }

        .input-icons > input {
            text-indent: 17px;
            font-family: "Lato", sans-serif;
        }

        .input-icons > .fa-user {
            position: absolute;
            top: 10px;
            left: 7px;
            font-size: 17px;
            color: #777777;
        }

        .input-icons > .fa-lock {
            position: absolute;
            top: 10px;
            left: 7px;
            font-size: 17px;
            color: #777777;
        }


        a {
            color: blue;
        }	

        /* Set a style for all buttons */
        .btn {
            background-color: #007aff;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: 1px solid #007aff;
            cursor: pointer;
            width: 100%;
            text-transform: uppercase;
            font-weight: 300;
            font-family: 'Lato', sans-serif;
            margin-bottom: 20px;
        }

        .btn:hover {
            opacity: 0.8;
            background-color: #d5d7de;
            border: thin;
            border: 1px solid #007aff;
            color: #007aff;
        }


        /* Center the image and position the close button */
        .imgcontainer {
            text-align: center;
            margin: 10px 0 12px 0;
            position: relative;
        }
        .imgcontainer1 {
            text-align: center;
            margin: 45px 20px 0 0;
            position: relative;
            width: 90%;
            height: 30%;
        }

        img.avatar {
            width: 40%;
            border-radius: 50%;
        }

        .container {
            padding: 16px;
        }

        span.psw {
            float: right;
            padding-top: 16px;
            margin-right: 8px;
        }
        .wel {
            margin-top: 20px;
            font-size: 25px;
            width: 100%;
            font-weight: 500px;
            color: #007aff;
            font-family: 'Lato', sans-serif;
            margin-bottom: 20px;
        }
        .log{


        }
        /* Modal Content/Box */
        .modal-content {
            background-color: #B3BBF3;
            margin: 1% auto 15% auto; /* 2% from the top, 15% from the bottom and centered */
            border: none;
            width: 26%; /* Could be more or less, depending on screen size */
        }

        /* Add Zoom Animation */
        .animate {
            -webkit-animation: animatezoom 0.6s;
            animation: animatezoom 0.6s
        }

        @-webkit-keyframes animatezoom {
            from {-webkit-transform: scale(0)} 
            to {-webkit-transform: scale(1)}
        }

        @keyframes animatezoom {
            from {transform: scale(0)} 
            to {transform: scale(1)}
        }

        /* Change styles for span and cancel button on extra small screens */
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }
            .login-img3-body{
                background: no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }


        </style>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <!-- Meta, title, CSS, favicons, etc. -->
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>T4A </title>

            <!-- Bootstrap -->
            <link href="<?php echo base_url(); ?>assets/login/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Font Awesome -->
            <link href="<?php echo base_url(); ?>assets/login/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
            <!-- NProgress -->
            <link href="<?php echo base_url(); ?>assets/login/vendors/nprogress/nprogress.css" rel="stylesheet">
            <!-- Animate.css -->
            <link href="<?php echo base_url(); ?>assets/login/vendors/animate.css/animate.min.css" rel="stylesheet">

            <!-- Custom Theme Style -->
            <link href="<?php echo base_url(); ?>assets/login/build/css/custom.min.css" rel="stylesheet">


            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

        </head>

        <body class="login">
            <div>
                <a class="hiddenanchor" id="signup"></a>
                <a class="hiddenanchor" id="signin"></a>

                <div class="login_wrapper">
                    <div class="animate form login_form">

                        <section class="login_content">
                            <img src="<?php echo base_url(); ?>assets/login/images/logo.png" width="51%" height="70" >
                            <form class="login_form" id="login_form">
                                <h1>Login Form</h1>
                                <div class="input-icons">
                                    <input type="text" class="form-control" id="username" placeholder="Email/Phone No" name="username" required="" />
                                    <span class="fa fa-user"></span>
                                </div>

                                <div class="input-icons">
                                    <input type="password" class="form-control password" id="password" name="password" placeholder="Password" required="" />
                                    <span class="fa fa-lock"></span>
                                </div>


                                <div>   
                                    <button class="btn" type="submit" href="dashboard.html">Login</button> 
                                    </br>

                                    <label class="psw"> <a href="#">Forgot Password?</a></label>

                                </div>



                                <div class="separator">

                                    <div>
                                        <img src="<?php echo base_url(); ?>assets/login/images/moh.png" width="31%" height="40" >
                                        <img src="<?php echo base_url(); ?>assets/login/images/CDC-LOGO.jpg" width="31%" height="40" >
                                        <img src="<?php echo base_url(); ?>assets/login/images/logo_3.png" width="31%" height="40" >

                                        <br/>
                                        <p>©2016 . Powered by mHEALTH Kenya . Privacy and Terms</p>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>


                </div>
            </div>
            <script>
                // Get the modal
                var modal = document.getElementById('id01');

            </script>



            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script type="text/javascript">

                $(document).ready(function () {
                    $(".forgot_password_link").click(function () {
                        $(".reset_password_div").show();
                        $(".login_form_div").hide();
                    });

                    $(".show_login_page").click(function () {
                        $(".reset_password_div").hide();
                        $(".login_form_div").show();
                    });






                    $('.login_form').submit(function (event) {
                
                        dataString = $(".login_form").serialize();
                        $(".btn").prop('disabled', true);
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>login/check_auth",
                            data: dataString,
                            success: function (data) {

                                if (data == "Login Success") {
                                    $(".btn").prop('disabled', false);
                                    swal({
                                        title: "Login Success!",
                                        text: "You will be redirected to your Home page in a few.",
                                        imageUrl: '<?php echo base_url(); ?>assets/images/thumbs-up.jpg'
                                    });

                                    setTimeout(function () {
                                        window.location.href = "<?php echo base_url(); ?>";

                                    }, 3000);



                                } else if (data == "User does not exist") {
                                $(".btn").prop('disabled', false);
                                    swal("Oops", "User does not exist...", "info");
                                } else if (data == "Wrong Password") {
                                $(".btn").prop('disabled', false);
                                    swal("Error", "Wrong password", "warning");
                                }

                            }

                        });
                        event.preventDefault();
                        return false;
                    });




                });
            </script>



        </body>
    </html>
