<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">Appointment Calendar</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">


        <!-- Start Page Content -->
        <div class="row">



            <div id='calendar' class="calendar"></div>



            <div id="eventContent" title="Event Details" style="display:none;">
                Start: <span id="startTime"></span><br>
                End: <span id="endTime"></span><br><br>
                <p id="eventInfo"></p>
                <p><strong><a id="eventLink" href="" target="_blank">Read More</a></strong></p>
            </div>




        </div>
        <!-- /# row -->
        <!-- End PAge Content -->






    </div>






    <!--END MAIN WRAPPER -->


</div>





<!-- End PAge Content -->
</div>
<!-- End Container fluid  -->
<!-- footer -->
<footer class="footer"> © 2018 Ushauri -  All rights reserved. Powered  by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
<!-- End footer -->
</div>
<!-- End Page wrapper  -->






<!--END MAIN WRAPPER -->





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.0/fullcalendar.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.0/fullcalendar.min.css" />


<script type="text/javascript">
    var cal = jQuery.noConflict();


    cal(document).ready(function () {





        function draw_calendar() {



            cal('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                windowResize: true,
                eventSources: [
                    {
                        url: '<?php echo base_url(); ?>Reports/get_count_appointments',
                        color: '#A3FF33',
                        textColor: 'black'
                    }, {
                        url: '<?php echo base_url(); ?>Reports/get_count_enhanced_adherence',
                        color: '#33FFE5',
                        textColor: 'black'
                    }, {
                        url: '<?php echo base_url(); ?>Reports/get_count_re_fill',
                        color: '#33FFE5',
                        textColor: 'black'
                    }, 
                    {
                        url: '<?php echo base_url(); ?>Reports/get_count_vl',
                        color: '#33FFE5',
                        textColor: 'black'
                    },
                    {
                        url: '<?php echo base_url(); ?>Reports/get_unscheduled_appointments',
                        color: '#FF33FB',
                        textColor: 'black'
                    },{
                        url: '<?php echo base_url(); ?>Reports/get_count_clinical',
                        color: '#FF33FB',
                        textColor: 'black'
                    }, {
                        url: '<?php echo base_url(); ?>Reports/get_count_other',
                        color: '#AB99FB',
                        textColor: 'black'
                    },
                    {
                        url: '<?php echo base_url(); ?>Reports/get_count_confirmed',
                        color: '#AB99FB',
                        textColor: 'black'
                    }
                   
                ]
            });




        }

        draw_calendar();

    });














</script>



