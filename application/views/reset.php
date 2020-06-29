<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        @import url(line-icons.css);
        @import url(http://fonts.googleapis.com/css?family=Lato:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic);
        @import "https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css";

        /* Full-width input fields */
        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 18px, 20px;
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

        input:focus+.fa {
            color: #007aff;
        }

        .input-icons {
            position: relative;
            margin: 5px 0px 5px 0px;
        }

        .input-icons>input {
            text-indent: 17px;
            font-family: "Lato", sans-serif;
        }

        .input-icons>.fa-user {
            position: absolute;
            top: 10px;
            left: 7px;
            font-size: 17px;
            color: #777777;
        }

        .input-icons>.fa-lock {
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



        /* Modal Content/Box */
        .modal-content {
            background-color: #B3BBF3;
            margin: 1% auto 15% auto;
            /* 2% from the top, 15% from the bottom and centered */
            border: none;
            width: 26%;
            /* Could be more or less, depending on screen size */
        }

        /* Add Zoom Animation */
        .animate {
            -webkit-animation: animatezoom 0.6s;
            animation: animatezoom 0.6s
        }

        @-webkit-keyframes animatezoom {
            from {
                -webkit-transform: scale(0)
            }

            to {
                -webkit-transform: scale(1)
            }
        }

        @keyframes animatezoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
            }
        }

        /* Change styles for span and cancel button on extra small screens */
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }

            .login-img3-body {
                background: no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
        }
    </style>

</head>


<body class="login">
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">

                <section class="login_content">
                    <img src="<?php echo base_url(); ?>assets/login/images/nascop_logo.png" width="51%" height="70">
                    <form class="reset_form" id="reset_form" method="POST">

                        <?php
                        $csrf = array(
                            'name' => $this->security->get_csrf_token_name(),
                            'hash' => $this->security->get_csrf_hash()
                        );
                        ?>

                        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />

                        <div class="alert alert-info">
                            Password should meet the following conditions :
                            <ul>
                                <li>At least one upper case </li>
                                <li>Lower case</li>
                                <li>Special character</li>
                                <li>Should not be less than 8 characters</li>
                            </ul>
                        </div>
                        <h1>Login Form</h1>
                        <div class="input-icons">
                            <input type="password" autofocus="" class="form-control password" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" placeholder="Re-type Password" id="password" placeholder="New Password" name="password" required="" />
                            <span class="fa fa-user"></span>
                        </div>

                        <div class="input-icons">
                            <input type="password" class="form-control password" id="password2" name="password2" placeholder="Password" required="" />
                            <span class="fa fa-lock"></span>
                        </div>

                        <input type="hidden" name="user_id" id="user_id" class="user_id form-control" value="<?php echo $this->uri->segment(3); ?>">











                        <div class="btn_div" id="btn_div" style="display: none;">
                            <button class="btn" type="submit">Reset</button>
                            </br>

                            <label class="psw"> <a href="<?php echo base_url(); ?>">Login?</a></label>

                        </div>



                        <div class="separator">

                            <div>
                                <img src="<?php echo base_url(); ?>assets/login/images/moh.png" width="31%" height="40">
                                <img src="<?php echo base_url(); ?>assets/login/images/logo_3.png" width="31%" height="40">

                                <br />
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



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".forgot_password_link").click(function() {
                $(".reset_password_div").show();
                $(".login_form_div").hide();
            });

            $(".show_login_page").click(function() {
                $(".reset_password_div").hide();
                $(".login_form_div").show();
            });


            $(".password").keyup(function() {
                var password = $("#password").val();
                var password2 = $("#password2").val();
                if (password == password2) {
                    if (password.length > 0) {
                        $(".btn_div").show();
                    } else {

                    }

                } else {
                    $(".btn_div").hide();
                }
            });




            $('.reset_form').submit(function(event) {
                dataString = $(".reset_form").serialize();
                $(".btn").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>Reset/reset_password",
                    data: dataString,
                    success: function(data) {

                        if (data == "Success") {
                            swal({
                                title: "Reset Success!",
                                text: "You will be redirected to your Login page in a few.",
                                imageUrl: '<?php echo base_url(); ?>assets/images/thumbs-up.jpg'
                            });

                            setTimeout(function() {
                                window.location.href = "<?php echo base_url(); ?>";

                            }, 3000);









                        } else if (data == "Error") {
                            $(".btn").prop('disabled', false);
                            swal("Oops", "Something went wrong somewhere...", "error");
                            // alert("User does not exist...");
                        } else if (data == "Password Mismatch") {
                            $(".btn").prop('disabled', false);
                            swal("Error!", "Password mismatch, try again...", "error");
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