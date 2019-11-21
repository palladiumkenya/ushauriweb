<!-- MENU SECTION -->
<div id="left" >
    <div class="media user-media well-small">         
        <div class="media-body">                   
        </div>
        <br />
    </div>

    <ul id="menu" class="collapse">

        <li class="panel active"><a href="index.php" >
                <i class="icon-dashboard"></i> Dashboard </a>                   
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardprojects","master_admin");'>
                <i class="icon-user"> </i> Partners
                <span class="pull-right">                	 
                </span>
                &nbsp; <span class="label label-default">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardhospitals","master_admin");'>
                <i class="icon-user"> </i> Hospitals
                <span class="pull-right">
                </span>
                &nbsp; <span class="label label-default">

                </span> &nbsp;
            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardusers","master_admin");'>
                <i class="icon-user"> </i> Users
                <span class="pull-right">
                </span>
                &nbsp; <span class="label label-default">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardclients","master_admin");'>
                <i class="icon-user"> </i> Clients
                <span class="pull-right">
                </span>
                &nbsp; <span class="label label-default">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardclients","master_admin");'>
                <i class="icon-user"> </i> Deactivated
                <span class="pull-right">
                </span>
                &nbsp; <span class="label label-default">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardout","master_admin");'>
                <i class="icon-user"> </i> Appointments
                <span class="pull-right">
                </span>						
                &nbsp; <span class="label label-default">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardcounties","master_admin");'>
                <i class="icon-user"> </i> Counties
                <span class="pull-right">             	 
                </span>						
                &nbsp; <span class="label label-default">

                </span> &nbsp;
            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("profile","search","main_clinician");'>
                <i class="icon-user"> </i> Appointments
                <span class="pull-right">
                </span>					
                &nbsp; <span class="label label-default">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardactive","main_clinician");'>
                <i class="icon-signin"> </i> Notified
                <span class="pull-right">                
                </span>						
                &nbsp; <span class="label btn-metis-2">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardbooked","main_clinician");'>
                <i class="icon-time"></i> Booked	   
                <span class="pull-right">                         
                </span>
                &nbsp; <span class="label btn-primary">

                </span>&nbsp;
            </a>
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardmissed","main_clinician");'>
                <i class="icon-calendar-empty"></i> Missed	   
                <span class="pull-right">                       
                </span>
                &nbsp; <span class="label label-danger">

                </span>&nbsp;
            </a>
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboarddefault","main_clinician");'>
                <i class="icon-bell"></i> Defaulted	   
                <span class="pull-right">                      
                </span>
                &nbsp; <span class="label label-warning">


                </span>&nbsp;
            </a>
        </li>

        <li class="panel ">
            <a href='javascript:taskInterpreter("dose","dashboardgroups","main_clinician");' data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-groups">

                <i class="icon-tasks"> </i> Groups 	   
                <span class="pull-right">
                    <i class="icon-angle-left"></i>
                </span>
                &nbsp; <span class="label label-default">

                </span>&nbsp;
            </a>
            <ul class="collapse" id="component-groups"> 
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardyoung","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Adolescents	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>                    
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardpmtct","main_clinician");'>
                        <i class="icon-calendar-empty"></i> PMTCT	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-2">
                        </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardisco","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Discordants	   
                        <span class="pull-right"></span>&nbsp; <span class="label label-warning">
                        </span>&nbsp;
                    </a>
                </li>

            </ul>
        </li>

        <li class="panel ">
            <a href='javascript:taskInterpreter("dose","dashboardcheckins","main_clinician");' data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-checkins">

                <i class="icon-tasks"> </i> Weekly Checkins 	   
                <span class="pull-right">
                    <i class="icon-angle-left"></i>
                </span>
                &nbsp; <span class="label label-default">

                </span>&nbsp;
            </a>
            <ul class="collapse" id="component-checkins"> 
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardresponded","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Responded	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>                    
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardpending","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Pending	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-2">
                             </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardlate","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Late	   
                        <span class="pull-right"></span>&nbsp; <span class="label label-warning">
                        </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardunspecified","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Unrecognised	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>
            </ul>
        </li>

        <li class="panel ">
            <a href='javascript:taskInterpreter("profile","lab","main_clinician");' data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-lab">

                <i class="icon-tasks"> </i> Lab Results   
                <span class="pull-right">
                    <i class="icon-angle-left"></i>
                </span>
                &nbsp; <span class="label label-default">

                </span>&nbsp;
            </a>
            <ul class="collapse" id="component-lab"> 
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardsuppressed","main_clinician");'>
                        <i class="icon-calendar-empty"></i> VL-Suppressed	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>                    
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardunsuppressed","main_clinician");'>
                        <i class="icon-calendar-empty"></i> VL-Unsuppressed	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-2">
                        </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardeid","main_clinician");'>
                        <i class="icon-calendar-empty"></i> 	EID Results   
                        <span class="pull-right"></span>&nbsp; <span class="label label-warning">
                        </span>&nbsp;
                    </a>
                </li>                          
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardinvalid","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Invalid samples	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>
            </ul>
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardoutreach","main_clinician");'>
                <i class="icon-warning-sign"></i> Outreach	   
                <span class="pull-right">                        
                </span>
                &nbsp; <span class="label btn-metis-4"> 

                </span>&nbsp;
            </a>
        </li> 

        <li class="panel"><a href='#' onclick='javascript:window.open("report.php");'>
                <i class="icon-bar-chart"></i> Service Reports</a>                   
        </li>










        <!--                        ADMIN MENU STARTS HERE-->


        <li class="panel"> <a href='javascript:taskInterpreter("profile","search","main_clinician");'>
                <i class="icon-user"> </i> Appointments
                <span class="pull-right">
                </span>					
                &nbsp; <span class="label label-default">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardactive","main_clinician");'>
                <i class="icon-signin"> </i> Notified
                <span class="pull-right">                
                </span>						
                &nbsp; <span class="label btn-metis-2">

                </span> &nbsp;

            </a>             
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardbooked","main_clinician");'>
                <i class="icon-time"></i> Booked	   
                <span class="pull-right">                         
                </span>
                &nbsp; <span class="label btn-primary">

                </span>&nbsp;
            </a>
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardmissed","main_clinician");'>
                <i class="icon-calendar-empty"></i> Missed	   
                <span class="pull-right">                       
                </span>
                &nbsp; <span class="label label-danger">

                </span>&nbsp;
            </a>
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboarddefault","main_clinician");'>
                <i class="icon-bell"></i> Defaulted	   
                <span class="pull-right">                      
                </span>
                &nbsp; <span class="label label-warning">


                </span>&nbsp;
            </a>
        </li>

        <li class="panel ">
            <a href='javascript:taskInterpreter("dose","dashboardgroups","main_clinician");' data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-groups">

                <i class="icon-tasks"> </i> Groups 	   
                <span class="pull-right">
                    <i class="icon-angle-left"></i>
                </span>
                &nbsp; <span class="label label-default">

                </span>&nbsp;
            </a>
            <ul class="collapse" id="component-groups"> 
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardyoung","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Adolescents	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>                    
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardpmtct","main_clinician");'>
                        <i class="icon-calendar-empty"></i> PMTCT	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-2">
                        </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardisco","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Discordants	   
                        <span class="pull-right"></span>&nbsp; <span class="label label-warning">
                        </span>&nbsp;
                    </a>
                </li>

            </ul>
        </li>

        <li class="panel ">
            <a href='javascript:taskInterpreter("dose","dashboardcheckins","main_clinician");' data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-checkins">

                <i class="icon-tasks"> </i> Weekly Checkins 	   
                <span class="pull-right">
                    <i class="icon-angle-left"></i>
                </span>
                &nbsp; <span class="label label-default">

                </span>&nbsp;
            </a>
            <ul class="collapse" id="component-checkins"> 
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardresponded","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Responded	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>                    
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardpending","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Pending	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-2">
                        </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardlate","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Late	   
                        <span class="pull-right"></span>&nbsp; <span class="label label-warning">
                        </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardunspecified","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Unrecognised	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>
            </ul>
        </li>

        <li class="panel ">
            <a href='javascript:taskInterpreter("profile","lab","main_clinician");' data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-lab">

                <i class="icon-tasks"> </i> Lab Results   
                <span class="pull-right">
                    <i class="icon-angle-left"></i>
                </span>
                &nbsp; <span class="label label-default">

                </span>&nbsp;
            </a>
            <ul class="collapse" id="component-lab"> 
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardsuppressed","main_clinician");'>
                        <i class="icon-calendar-empty"></i> VL-Suppressed	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>                    
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardunsuppressed","main_clinician");'>
                        <i class="icon-calendar-empty"></i> VL-Unsuppressed	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-2">
                        </span>&nbsp;
                    </a>
                </li>
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardeid","main_clinician");'>
                        <i class="icon-calendar-empty"></i> 	EID Results   
                        <span class="pull-right"></span>&nbsp; <span class="label label-warning">
                        </span>&nbsp;
                    </a>
                </li>                          
                <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardinvalid","main_clinician");'>
                        <i class="icon-calendar-empty"></i> Invalid samples	   
                        <span class="pull-right"></span>&nbsp; <span class="label btn-metis-6">
                        </span>&nbsp;
                    </a>
                </li>
            </ul>
        </li>

        <li class="panel"> <a href='javascript:taskInterpreter("dose","dashboardoutreach","main_clinician");'>
                <i class="icon-warning-sign"></i> Outreach	   
                <span class="pull-right">                        
                </span>
                &nbsp; <span class="label btn-metis-4"> 

                </span>&nbsp;
            </a>
        </li> 

        <li class="panel"><a href='#' onclick='javascript:window.open("report.php");'>
                <i class="icon-bar-chart"></i> Service Reports</a>                   
        </li>


        <!--                        ADMIN STOPS HERE-->





















        <li class="panel ">
            <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-nav">
                <i class="icon-tasks"> </i> JOBS MONITOR     

                <span class="pull-right">
                    <i class="icon-angle-left"></i>
                </span>
                &nbsp; <span class="label label-default">4</span>&nbsp;
            </a>
            <ul class="collapse" id="component-nav">


            </ul>
        </li>
    </ul>

</div>
<!--END MENU SECTION -->
<!--PAGE CONTENT -->
<div id="content">             
    <div class="inner" style="min-height: 1200px;">
        <div class="row">
            <div class="col-lg-12">

                <!-- there is usergroup and username    -->
            </div>
        </div>
        <hr />

        <!--BLOCK SECTION -->
        <div class="row">
            <div class="col-lg-12">
                <div style="text-align: center;">

                    <a class="quick-btn" href='javascript:taskInterpreter("signin","p_regform","master_admin")'>
                        <i class="icon-sitemap icon-2x"></i>
                        <span>Partners</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("signin","f_regform","master_admin")'>
                        <i class="icon-h-sign icon-2x"></i>
                        <span>Hospital</span>
                        <span class="label btn-metis-4"> 

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("signin","user_regform","master_admin")'>
                        <i class="icon-user icon-2x"></i>
                        <span>Users</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","messagedef","master_admin")'>
                        <i class="icon-hospital icon-2x"></i>
                        <span>Messages</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","responsedef","master_admin")'>
                        <i class="icon-envelope icon-2x"></i>
                        <span>Responses</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","search","main_clinician")'>
                        <i class=" icon-save icon-2x"></i>
                        <span>Backup</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","messagedef","master_admin")'>
                        <i class="icon-hospital icon-2x"></i>
                        <span>Messages</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    







                    <a class="quick-btn" href='javascript:taskInterpreter("signin","f_regform","main_clinician")'>
                        <i class="icon-h-sign icon-2x"></i>
                        <span>Hospital</span>
                        <span class="label btn-metis-4"> 

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("signin","user_regform","main_clinician")'>
                        <i class="icon-user icon-2x"></i>
                        <span>Users</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","messagedef","main_clinician")'>
                        <i class="icon-hospital icon-2x"></i>
                        <span>Messages</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","responsedef","main_clinician")'>
                        <i class="icon-envelope icon-2x"></i>
                        <span>Responses</span>
                        <span class="label btn-metis-4">

                        </span>
                    </a>    
















                    <a class="quick-btn" href='javascript:taskInterpreter("profile","view","main_clinician")'>
                        <i class="icon-user-md icon-2x"></i>
                        <span>Clients</span>
                        <span class="label btn-metis-6">

                        </span>
                    </a> 

                    <a class="quick-btn" href='javascript:taskInterpreter("signin","signin","main_clinician")'>
                        <i class="icon-user icon-2x"></i>
                        <span> Add Client</span>
                       <!--  <i class="icon-search icon-2x"></i><span class="label label-warning">2</span>  -->
                    </a>

                    <a class="quick-btn" href='javascript:taskInterpreter("dose","dashboardgood","main_clinician")'>
                        <i class="icon-ok-circle icon-2x"></i>
                        <span>Ok</span>
                        <span class="label btn-metis-2">

                        </span>
                    </a> 

                    <a class="quick-btn" href='javascript:taskInterpreter("dose","dashboardbad","main_clinician")'>
                        <i class="icon-remove-sign icon-2x"></i>
                        <span>Not Ok</span>
                        <span class="label btn-metis-1">

                        </span>
                    </a> 

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","ListPatients","main_clinician")'>
                        <i class="icon-edit icon-2x"></i>
                        <span>Message</span>
                        <span class="label label-warning">+2</span>
                    </a>

                    <a class="quick-btn" href='javascript:taskInterpreter("profile","setbroadcast","main_clinician")'>
                        <i class="icon-external-link icon-2x"></i>
                        <span>Broadcast</span>
                        <span class="label btn-metis-2"> 
                        </span>
                    </a>

                    <a class="quick-btn" href='javascript:taskInterpreter("signin","backup","main_clinician")'>
                        <i class="icon-folder-close icon-2x"></i>
                        <span>Backup</span>
                        <span class="label label-default">20</span>
                    </a>



                </div>

            </div>

        </div>
        <!--END BLOCK SECTION -->
        <!--END BLOCK SECTION -->
        <hr />
        <!-- COMMENT AND NOTIFICATION  SECTION -->
        <div class="row" id="data">

            <div class="col-lg-12">


                <div class="panel panel-primary" id="main_clinician">

                    <div class="panel-heading"> 
                    </div>   
                    <div >


                        <div class="panel-body"> 

                        </div>
                    </div>                <div class="panel-footer">
                        Get   in touch: support.tech@mhealthkenya.org                             </div>

                </div>        

                <div class="panel panel-primary" id="main_clinician">                
                    <div class="panel-heading">

                    </div>
                    <div class="panel-body"> 

                    </div>
                    <div class="panel-footer">
                        Get   in touch: admin@mhealthKenya.com                            </div>

                </div>      


                <div class="panel panel-primary" id="master_admin">                
                    <div class="panel-heading">
                        Panel 
                    </div>
                    <div class="panel-body">

                    </div>
                    <div class="panel-footer">
                        Get   in touch: admin@mhealthkenya.org                            </div>

                </div>                 


                <div class="panel panel-primary">                
                    <div class="panel-heading">
                        Panel  
                    </div>
                    <div class="panel-body">
                        We Develop, Implement and Sustain
                    </div>
                    <div class="panel-footer">
                        Get   in touch: admin@mhealthkenya.org                            </div>

                </div>   




            </div>



        </div>
    </div>
    <!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>

<!--END MAIN WRAPPER -->