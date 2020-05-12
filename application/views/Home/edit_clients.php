<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>"> Clients</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->




        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!--                        <h4 class="card-title">A lis st</h4>
                                                <h6 class="card-subtitle">A list of Partners in the  system </h6>-->
                        <div class="table-responsive m-t-40">





                            <div class="table_div">

                                <?php
                                $access_level = $this->session->userdata('access_level');
                                if ($access_level == "Partner" || $access_level == "Facility") {
                                ?>
                                    <table id="table" class="table table-bordered table-condensed table-hover">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>UPN</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Type</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($edit_apps as $value) {
                                            ?>
                                                <tr>
                                                    <td class="a-center"><?php echo $i; ?></td>


                                                    <td>
                                                        <button onclick='editAppointment( <?php echo  json_encode($value);  ?>)' class="btn btn-default btn-small edit_btn" id="edit_btn">
                                                            <?php echo $value['clinic_number']; ?></td>
                                                    <td><?php echo $value['appntmnt_date']; ?></td>
                                                    <td><?php echo $value['appointment_type']; ?></td>

                                                </tr>
                                            <?php
                                                $i++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>



                                <?php
                                }
                                ?>





                            </div>











                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="edit_div" style="display: none;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Edit Appointment Detail</h4>
                        </div>
                        <div class="card-body">
                            <form class="edit_form" id="edit_form">
                                <div class="row">
                                    <div class="col-sm-6 ">

                                        <h2> Client Appointment Details... </h2>
                                        <input class='form-control' type='text' disabled name='edit_upn' id='edit_upn'>

                                        <label class='control-label'> Appointment Date: </label>
                                        <input class='form-control edit_appntmnt_date date input-rounded input-sm' name='edit_appntmnt_date' type="text" id='edit_appntmnt_date'>

                                        <label for="type">Appointment Type</label>
                                        <select class='form-control appntmnt_type input-rounded input-sm' name='edit_appntmnt_type' id='edit_appntmnt_type'>
                                            <option value=''>Please select </option>
                                            <?php foreach ($appointment_types as $val) {
                                            ?>
                                                <option value="<?php echo $val->name; ?>"><?php echo $val->name; ?></option>
                                            <?php }
                                            ?>
                                        </select>

                                        <label for="comment">Reason For Editing</label>
                                        <input class='form-control input-rounded input-sm' placeholder="Please Enter A Reason" type='text' name='comment' id='comment' required="true">

                                        <input type="hidden" id="appointment_id" name="appointment_id" class="appointment_id form-control" />

                                        <hr>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" id="save_edited_app" class="btn btn-primary">Save changes</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Button trigger modal -->
        <!-- Button trigger modal -->

        <!-- End PAge Content -->

        <!-- END COMMENT AND NOTIFICATION  SECTION -->

    </div>






    <!--END MAIN WRAPPER -->


</div>





<!-- End PAge Content -->
</div>
<!-- End Container fluid  -->
<!-- footer -->
<footer class="footer"> © 2018 Ushauri - All rights reserved. Powered by <a href="https://mhealthkenya.org">mHealth Kenya Ltd</a></footer>
<!-- End footer -->
</div>
<!-- End Page wrapper  -->
<script type="text/javascript">
    //$('#edit_btn').click(edit_appointments)

    // function edit_appointments() {
    //     $(".edit_div").show();
    //     //console.log('i am clicked')
    // }

    function editAppointment(app) {
        $(".edit_div").show();
        $('#edit_upn').val(app.clinic_number);
        $('#edit_appntmnt_date').val(app.appntmnt_date);
        $('#edit_appntmnt_type').val(app.appointment_type);
        $('#appointment_id').val(app.id);
        console.log(app)


    }
    var btn = document.getElementById('save_edited_app')
    btn.addEventListener('click', nextItem)

    function nextItem() {
        console.log('clicked')
        let new_upn = $('#edit_upn').val();
        let app_date = $('#edit_appntmnt_date').val();
        let app_type = $('#edit_appntmnt_type').val();
        let comment = $('#comment').val();
        let appointment_id = $('#appointment_id').val();

        var controller = "Reports";
        var get_function = "update_appointment";
        $.ajax({
            data: {
                upn: new_upn,
                date: app_date,
                type: app_type,
                comment: comment,
                appointment_id: appointment_id
            },
            method: 'POST',
            url: "<?php echo base_url(); ?>" + controller + "/" + get_function,
            dataType: 'JSON',
            success: function(response) {
                location.reload()
                console.log(response)
            }


        })

    }
</script>





<!--END BLOCK SECTION -->




<!--END MAIN WRAPPER -->




<!--END MAIN WRAPPER -->