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

                                    <h4>Login</h4>
                                    <form class="login_form" id="login_form" >

                                        <?php
                                        $csrf = array(
                                            'name' => $this->security->get_csrf_token_name(),
                                            'hash' => $this->security->get_csrf_hash()
                                        );
                                        ?>

                                        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />


                                        <div class="form-group">
                                            <label>Email address / Phone No</label>
                                            <input type="text" class="form-control input-rounded input-medium" name="username" required="" placeholder="Email/Phone No">
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control input-rounded input-medium" name="password" required="" id="password" placeholder="Password">
                                        </div>
                                        <div class="checkbox">

                                            <label class="pull-left">
                                                <a href="javascript::void(0)" class="security_tips_link" id="security_tips_link">Security Tips ? </a>
                                            </label>
                                            <label class="pull-right">
                                                <a href="javascript::void(0)" class="forgot_password_link" id="forgot_password_link">Forgotten Password?</a>
                                            </label>

                                        </div>
                                        <button type="submit" class="btn btn-primary btn-flat btn-small m-b-15 m-t-15 btn-rounded login_btn">Sign in</button>
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





        <!-- The Modal -->
        <div class="modal" id="security_tips_modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Security Tips</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">



                        <p>
                            Keep your computer up-to-date with Anti-virus software, operating system patches, firewalls etc. and ensure your browser is set to the highest level of security.
                        </p>
                        <hr>
                        <p class="mb-0">Be wary of unsolicited emails or phone calls asking you for PINs or passwords - mHealth Kenya Ltd never makes such requests over the phone.</p>
                        <hr>
                        <p>Never click any link sent to your email address asking you to login to the Self Service Portal. Below are the ways to access the online platform: </p>
                        <hr>

                        <ul style="list-style-type:disc">
                            <?php
                            // Loads a config file named sys_config.php and assigns it to an index named "sys_config"
                            $this->config->load('config', TRUE);
                            // Retrieve a config item named site_name contained within the blog_settings array
                            $system_url = $this->config->item('system_url', 'config');
                            echo 'System URL => ' . $system_url . '<br>';
                            ?>
                            <li>Type in your browser the full Equity Diaspora Self Service Portal URL as follows: <b><a href="<?php echo $system_url ?>" target="_blank"><?php echo $system_url ?></a></b></li>
                            <li>Visit <b><a href="https://mhealthkenya.org/projects/interventions-for-clients/">https://mhealthkenya.org/</a></b> and click on .Products and Services and select the  second option in the  drop down "Intervention for Clients" .</li>

                        </ul>






                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-stop-circle-o"></i>Close</button>
                    </div>




                </div>
            </div>
        </div>








        <!-- The Modal -->
       

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
                    swal({
                        title: "Not Allowed!",
                        text: "Kindly contact the  Support / Help Desk to reset your password."
                    });
                });
                $(".forgot_password_link").click(function () {
                    $(".reset_password_div").show();
                    $(".login_form_div").hide();
                });

                $(".show_login_page").click(function () {
                    $(".reset_password_div").hide();
                    $(".login_form_div").show();
                });



                $(".security_tips_link").click(function () {

                    $('#security_tips_modal').modal('show');
                });



                $('.login_form').submit(function (event) {

                $(".loader").show();
                dataString = $(".login_form").serialize();
                $(".login_btn").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>login/check_auth",
                    data: dataString,
                    success: function (data) {

                    data =data.trim();
                    console.log(data);                  

                    if (data == "Login Success") {  
                    
                        swal({
                            title: "Login Success!",
                            text: "You will be redirected to your Home page in a few.",
                            imageUrl: '<?php echo base_url(); ?>assets/images/hand.jpg'
                        });         

                        setTimeout(function () {
                            window.location.href = "<?php echo base_url(); ?>";                     
                        }, 3000);
                        }
                       if (data === "Wrong Password") {
                            $(".login_btn").prop('disabled', false);
                            swal("Error", "Wrong password", "warning");
                        }
                        else if (data == "User does not exist") {
                            $(".login_btn").prop('disabled', false);
                            swal("Oops", "User does not exist...", "info");
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
