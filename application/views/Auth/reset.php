<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>/assets/images/login/images/ushauri_logo.png">
        <title>Ushauri - Getting better one text at a time</title>
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo base_url(); ?>/assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="<?php echo base_url(); ?>/assets/css/helper.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>/assets/css/style.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
        <!--[if lt IE 9]>
        <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body class="fix-header fix-sidebar">
        <!-- Preloader - style you can find in spinners.css -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
        </div>
        <!-- Main wrapper  -->
        <div id="main-wrapper">

            <div class="unix-login">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-4">
                        





                            <div class="login-content card">
                                <div class="login-form">
                                    <img src="<?php echo base_url(); ?>/assets/images/login/images/ushauri_logo.png" style="margin-left: 83px;" width="51%" height="70%" >

                                    <h4>Reset Password</h4>
                                    <form class="reset_form" id="reset_form" method="POST">
                                    <div class="alert alert-info">
                                    Provided password should meet the  following criteria : 
                                    <ul>
                                        <li>1. At least one upper case letter </li>
                                        <li>2. At least one lower case letter</li>
                                        <li>3. At least one special character</li>
                                        <li>4. Should not be less than 8 characters</li>
                                    </ul>
                                </div>

                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" autofocus="" class="form-control password input-rounded input-medium" autocomplete="false" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" placeholder="Type New Password" id="password" placeholder="New Password" name="password" required="" />

                                        </div>
                                        <div class="form-group">
                                            <label>Re-Type Password</label>
                                            <input type="password" class="form-control password input-rounded input-medium" id="password2" autocomplete="false" name="password2" placeholder="Ret Type New Password" required="" />

                                        </div>
                                        <div class="">
                                            <input type="hidden" name="user_id" id="user_id"  class="user_id form-control" value="<?php echo $this->uri->segment(3); ?>">

                                        </div>
                                        <div class="btn_div" id="btn_div" style="display: none;">
                                            <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30 btn-md btn-rounded">Reset Password </button>
                                            </br>
                                        </div>

                                        <label class="psw"> <a href="<?php echo base_url(); ?>">Login?</a></label>


                                        <div class="register-link m-t-15 text-center">
                                            <div>
                                                <img src="<?php echo base_url(); ?>assets/images/login/images/moh.png"  height="40" >
                                                <img src="<?php echo base_url(); ?>assets/images/login/images/PEPFAR_Logo.jpg"  height="40" >
                                                <img src="<?php echo base_url(); ?>assets/images/login/images/logo_3.png" width="31%" height="40" >


                                            </div>
                                        </div> 
                                        <div class="register-link m-t-15 text-center">
                                            <p>&copy;  mHealth Kenya &nbsp;2016 - <?php echo date('Y'); ?> <b> Powered by : <a href="https://mhealthkenya.org/" target="_blank"> mHealth  Kenya </a></b> </p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Wrapper -->
        <!-- All Jquery -->
        <script src="<?php echo base_url(); ?>/assets/js/lib/jquery/jquery.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="<?php echo base_url(); ?>/assets/js/lib/bootstrap/js/popper.min.js"></script>
        <script src="<?php echo base_url(); ?>/assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="<?php echo base_url(); ?>/assets/js/jquery.slimscroll.js"></script>
        <!--Menu sidebar -->
        <script src="<?php echo base_url(); ?>/assets/js/sidebarmenu.js"></script>
        <!--stickey kit -->
        <script src="<?php echo base_url(); ?>/assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <!--Custom JavaScript -->
        <script src="<?php echo base_url(); ?>/assets/js/custom.min.js"></script>


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>



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


                $(".password").keyup(function () {
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




                $('.reset_form').submit(function (event) {
                    dataString = $(".reset_form").serialize();
                    $(".btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>Reset/reset_password",
                        data: dataString,
                        success: function (data) {

                            if (data == "Success") {
                                swal({
                                    title: "Reset Success!",
                                    text: "You will be redirected to your Login page in a few.",
                                    imageUrl: '<?php echo base_url(); ?>assets/images/hand.jpg'
                                });

                                setTimeout(function () {
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