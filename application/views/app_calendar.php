
<!--END BLOCK SECTION -->
<hr />
<!-- COMMENT AND NOTIFICATION  SECTION -->
<div class="row" id="data">

    <div class="col-lg-12">


        <div class="panel panel-primary" id="main_clinician">

            <div class="panel-heading"> 
                All clients in the  system
            </div>   
            <div >


                <div class="panel-body"> 





                    <div id='calendar'></div>





                    <div id="eventContent" title="Event Details" style="display:none;">
                        Start: <span id="startTime"></span><br>
                        End: <span id="endTime"></span><br><br>
                        <p id="eventInfo"></p>
                        <p><strong><a id="eventLink" href="" target="_blank">Read More</a></strong></p>
                    </div>




                </div>
            </div>                <div class="panel-footer">
                Get   in touch: support.tech@mhealthkenya.org                             </div>

        </div>        








        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.0/fullcalendar.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.0/fullcalendar.min.css" />


        <script type="text/javascript">
            var cal = jQuery.noConflict();


            cal(document).ready(function () {

                function get_data() {
                    cal.ajax({
                        url: "<?php echo base_url(); ?>Reports/get_count_apointments/",
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {

                            var appointment_info = data;
                            draw_calendar(appointment_info);
                        }, error: function (jqXHR) {
                            console.log(jqXHR);
                        }
                    });
                }
                get_data();

                function draw_calendar(appointment_info) {


                    cal('#calendar').fullCalendar({
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        editable: true,
                        events: appointment_info
                    });




                }



            });














        </script>



    </div>



</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>









<!--END MAIN WRAPPER -->