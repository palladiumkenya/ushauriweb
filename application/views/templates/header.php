<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ushauri -: Getting Better One Text at a Time">
    <meta name="author" content="mHealth Kenya Ltd">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>/assets/images/login/images/ushauri_logo.png">
    <title>Ushauri - Getting better one text at a time</title>


    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/helper.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/assets/css/style.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>/assets/css/lib/chartist/chartist.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/lib/html5-editor/bootstrap-wysihtml5.css" />


    <link href="<?php echo base_url(); ?>/assets/css/helper.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/assets/css/style.css" rel="stylesheet">


    <script src="<?php echo base_url(); ?>/assets/js/lib/jquery/jquery.min.js"></script>




    <script src="https://public.tableau.com/javascripts/api/tableau-2.min.js"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
        <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>




    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo base_url(); ?>">
                        <!-- Logo icon -->
                        <b><img src="<?php echo base_url(); ?>/assets/images/login/images/ushauri_logo.png" alt="homepage" class="dark-logo" /></b>
                        <!--End Logo icon -->

                    </a>
                </div>

                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>




                        <!-- Messages -->
                        <li class="nav-item dropdown mega-dropdown"> <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-th-large"></i></a>
                            <div class="dropdown-menu animated zoomIn">
                                <ul class="mega-dropdown-menu row">


                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">User Information</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)">Name : <i class="fa fa-user text-success"></i> <?php
                                                                                                                            $name = $this->session->userdata('Fullname');
                                                                                                                            echo $name;
                                                                                                                            ?></a></li>
                                            <li><a href="javascript:void(0)">E-Mail : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                $email = $this->session->userdata('email');
                                                                                                                                echo $email;
                                                                                                                                ?></a></li>
                                            <li><a href="javascript:void(0)">Phone No : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                $phone_no = $this->session->userdata('phone_no');
                                                                                                                                echo $phone_no;
                                                                                                                                ?></a></li>


                                        </ul>
                                    </li>
                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">User Information</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)">Access Level : <i class="fa fa-bulle text-success"></i> <?php
                                                                                                                                        $access_level = $this->session->userdata('access_level');
                                                                                                                                        echo $access_level;
                                                                                                                                        ?></a></li>
                                            <?php
                                            $access_level = $this->session->userdata('access_level');


                                            if ($access_level === "Admin") {
                                            ?>
                                                <li><a href="javascript:void(0)"> : <i class="fa fa-user text-success"></i> Administrator</a></li>
                                            <?php
                                            } else if ($access_level === "Donor") {
                                            ?>
                                                <li><a href="javascript:void(0)">Donor : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                    $donor_name = $this->session->userdata('donor_name');
                                                                                                                                    echo $donor_name;
                                                                                                                                    ?></a></li>
                                            <?php
                                            } else if ($access_level === "Partner") {
                                            ?>
                                                <li><a href="javascript:void(0)">Partner : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                    $partner_name = $this->session->userdata('partner_name');
                                                                                                                                    echo $partner_name;
                                                                                                                                    ?></a></li>
                                            <?php
                                            } else if ($access_level === "County") {
                                            ?>
                                                <li><a href="javascript:void(0)">County : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                    $county_name = $this->session->userdata('county_name');
                                                                                                                                    echo $county_name;
                                                                                                                                    ?></a></li>
                                            <?php
                                            } else if ($access_level === "Sub County") {
                                            ?>
                                                <li><a href="javascript:void(0)"> Sub County : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                        $sub_county = $this->session->userdata('sub_county_name');
                                                                                                                                        echo $sub_county;
                                                                                                                                        ?></a></li>
                                            <?php
                                            } else if ($access_level === "Facility") {
                                            ?>
                                                <li><a href="javascript:void(0)">Facility : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                    $facility_name = $this->session->userdata('facility_name');
                                                                                                                                    echo $facility_name;
                                                                                                                                    ?></a></li>
                                                <li><a href="javascript:void(0)">Clinic : <i class="fa fa-user text-success"></i> <?php
                                                                                                                                    $clinic_name = $this->session->userdata('clinic_name');
                                                                                                                                    echo $clinic_name;
                                                                                                                                    ?></a></li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>

                                </ul>
                                </>
                        </li>
                        <!-- End Messages -->
                    </ul>



                    <div class=" navbar-nav mr-auto mt-md-0 ">
                        <!-- Logo icon -->
                        <b><img src="<?php echo base_url(); ?>/assets/images/coat_of_arms.png" alt="homepage" class="dark-logo" /></b>
                        <!--End Logo icon -->
                    </div>


                    <div class="navbar-nav mr-auto mt-md-0 ">
                        <!-- Logo icon -->
                        <b><img src="<?php echo base_url(); ?>/assets/images/NASCOP_Logo.png" alt="homepage" class="dark-logo" /></b>
                        <!--End Logo icon -->
                    </div>









                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">

                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo base_url(); ?>assets/images/male_icon.jpg" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="<?php echo base_url(); ?>/home/user_profile"><i class="ti-user"></i> Profile</a></li>
                                    <li><a href="<?php echo base_url(); ?>/support/documentation"><i class="ti-settings"></i> Documentation</a></li>
                                    <li><a href="http://ushauriwiki.mhealthkenya.co.ke" target="_blank"><i class="ti-settings"></i> WIKI</a></li>
                                    <li><a href="http://ushauriapi.mhealthkenya.co.ke" target="_blank"><i class="ti-settings"></i> APIs</a></li>
                                    <li><a href="<?php echo base_url(); ?>/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>




            </nav>
        </div>
        <!-- End header header -->





        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboards </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="<?php echo base_url(); ?>Reports/dashboard">Summary </a></li>
                                <li><a href="<?php echo base_url(); ?>Reports/clients_dashboard"> Clients </a></li>
                                <li><a href="<?php echo base_url(); ?>Reports/appointments_dashboard"> Appointments </a></li>
                                <li><a href="<?php echo base_url(); ?>Reports/messages_dashboard"> Messages </a></li>
                            </ul>
                        </li>








                        <li class="nav-label">Clients</li>

                        <?php
                        $array = $side_functions;
                        $number = array_column($array, 'level');
                        $found_key = array_search('2', $number);

                        $level2 = '2';
                        $newArray = array_filter($array, function ($var) use ($level2) {
                            return ($var['level'] == $level2);
                        });



                        $names = array_column($newArray, 'id');
                        $level2 = array_filter($names);

                        if (!empty($level2)) {
                        ?>
                            <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Clients</span></a>
                                <ul aria-expanded="false" class="collapse">



                                    <?php
                                    foreach ($side_functions as $value) {
                                        $level2 = $value['level'];
                                        if ($level2 == 2) {
                                    ?>

                                            <li>
                                                <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                    <span>
                                                        <?php echo $value['module']; ?>
                                                    </span>

                                                </a>
                                            </li>

                                    <?php
                                        }
                                    }
                                    ?>


                                </ul>
                            </li>

                        <?php
                        }
                        ?>

                        <?php
                        $array = $side_functions;
                        $number = array_column($array, 'level');
                        $found_key = array_search('2', $number);

                        $level3 = '3';
                        $newArray = array_filter($array, function ($var) use ($level3) {
                            return ($var['level'] == $level3);
                        });



                        $names = array_column($newArray, 'id');
                        $level3 = array_filter($names);

                        if (!empty($level3)) {
                        ?>

                            <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-briefcase"></i><span class="hide-menu">Appointments</span></a>
                                <ul aria-expanded="false" class="collapse">

                                    <li> <a class="has-arrow" href="#" aria-expanded="false">Appointment Diary</a>
                                        <ul aria-expanded="false" class="collapse">

                                            <?php
                                            foreach ($side_functions as $value) {
                                                $level3 = $value['level'];
                                                if ($level3 == 3) {
                                            ?>
                                                    <?php
                                                    $function = $value['function'];

                                                    if ($function == 'today_appointments' or $function == 'notified_clients' or $function == 'create_appointment') {
                                                    ?>
                                                        <li>
                                                            <a href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>

                                                                <?php echo $value['module']; ?>


                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>

                                            <?php
                                                }
                                            }
                                            ?>

                                        </ul>
                                    </li>
                                    <li> <a class="has-arrow" href="#" aria-expanded="false">Defaulter Diary</a>
                                        <ul aria-expanded="false" class="collapse">

                                            <?php
                                            foreach ($side_functions as $value) {
                                                $level3 = $value['level'];
                                                if ($level3 == 3) {
                                            ?>
                                                    <?php
                                                    $function = $value['function'];

                                                    if ($function == 'missed_clients' or $function == 'defaulted_clients' or $function == 'ltfu_clients') {
                                                    ?>
                                                        <li>
                                                            <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                                <span>
                                                                    <?php echo $value['module']; ?>
                                                                </span>

                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>

                                            <?php
                                                }
                                            }
                                            ?>

                                        </ul>
                                    </li>
                                    <li>
                                        <a class="" href='<?php echo base_url(); ?>/Reports/edit_appointments'>
                                            <span>
                                                Edit Appointments
                                            </span>

                                        </a>
                                    </li>






                                    <?php
                                    foreach ($side_functions as $value) {
                                        $level3 = $value['level'];
                                        if ($level3 == 3) {
                                    ?>
                                            <?php
                                            $function = $value['function'];

                                            if ($function == 'show_calendar' or $function == 'appointment_diary' or $function == 'appointments') {
                                            ?>
                                                <li>
                                                    <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                        <span>
                                                            <?php echo $value['module']; ?>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php
                                            }
                                            ?>

                                    <?php
                                        }
                                    }
                                    ?>






                                </ul>
                            </li>





                        <?php
                        }
                        ?>





                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-heart"></i><span class="hide-menu">Wellness </span></a>
                            <ul aria-expanded="false" class="collapse">


                                <?php
                                foreach ($side_functions as $value) {
                                    $level4 = $value['level'];
                                    if ($level4 == 4) {
                                ?>

                                        <li>
                                            <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                <span>
                                                    <?php echo $value['module']; ?>
                                                </span>

                                            </a>
                                        </li>

                                <?php
                                    }
                                }
                                ?>



                            </ul>
                        </li>


                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-users"></i><span class="hide-menu">Groups </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <?php
                                foreach ($side_functions as $value) {
                                    $level5 = $value['level'];
                                    if ($level5 == 5) {
                                ?>

                                        <li>
                                            <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                <span>
                                                    <?php echo $value['module']; ?>
                                                </span>

                                            </a>
                                        </li>

                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>

                        <li class="nav-label">Administration</li>


                        <?php
                        $array = $side_functions;
                        $number = array_column($array, 'level');
                        $found_key = array_search('2', $number);

                        $level6 = '6';
                        $newArray = array_filter($array, function ($var) use ($level6) {
                            return ($var['level'] == $level6);
                        });



                        $names = array_column($newArray, 'id');
                        $level6 = array_filter($names);

                        if (!empty($level6)) {
                        ?>
                            <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-gear"></i><span class="hide-menu">Administration Tools</span></a>
                                <ul aria-expanded="false" class="collapse">



                                    <?php
                                    foreach ($side_functions as $value) {
                                        $level6 = $value['level'];
                                        if ($level6 == 6) {
                                    ?>

                                            <li>
                                                <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                    <span>
                                                        <?php echo $value['module']; ?>
                                                    </span>

                                                </a>
                                            </li>

                                    <?php
                                        }
                                    }
                                    ?>




                                </ul>
                            </li>



                        <?php } ?>

                        <li class="nav-label">Report</li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-wpforms"></i><span class="hide-menu">Reports</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <?php
                                foreach ($side_functions as $value) {
                                    $level7 = $value['level'];
                                    if ($level7 == 7) {
                                ?>

                                        <li>
                                            <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                <span>
                                                    <?php echo $value['module']; ?>
                                                </span>

                                            </a>
                                        </li>

                                <?php

                                    }
                                }
                                ?>
                                <li>
                                    <a class="" href='<?php echo base_url(); ?>/Reports/monthly_appointment_report'>
                                        <span>
                                            Monthly Reports
                                        </span>

                                    </a>
                                </li>
                                <li>
                                    <a class="" href='<?php echo base_url(); ?>/Reports/TracingOutcome'>
                                        <span>
                                            Tracing Outcome RawData
                                        </span>

                                    </a>
                                </li>
                                <li>
                                    <a class="" href='<?php echo base_url(); ?>Reports/summaryappoitmentfilter'>
                                        <span>
                                            Summary Appoitment
                                        </span>

                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-label">Support</li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-support"></i><span class="hide-menu">Support Links</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <?php
                                foreach ($side_functions as $value) {
                                    $level8 = $value['level'];
                                    if ($level8 == 8) {
                                ?>

                                        <li>
                                            <a class="" href='<?php echo base_url() ?><?php echo $value['controller'] . '/' . $value['function']; ?>'>
                                                <span>
                                                    <?php echo $value['module']; ?>
                                                </span>

                                            </a>
                                        </li>

                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li>
                            <a class="" target="_blank" href="https://ushauriapi.mhealthkenya.co.ke/"> <i class="fa fa-code"></i><span class="hide-menu">API's</span>
                                <i class="icon_datareport"></i>

                            </a>
                        </li>

                        <li>
                            <a class="" target="_blank" href="https://ushauriwiki.mhealthkenya.co.ke"> <i class="fa fa-book"></i><span class="hide-menu">WIKI's</span>
                                <i class="icon_datareport"></i>

                            </a>
                        </li>


                        <li>
                            <a class="" href="<?php echo base_url(); ?>/Logout"> <i class="fa fa-power-off"></i><span class="hide-menu">Log Out</span>
                                <i class="icon_key_alt"></i>

                            </a>
                        </li>





                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        <!-- End Left Sidebar  -->