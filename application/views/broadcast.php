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


                    <div class="table_div">

                        <table id="table" class="table table-bordered table-condensed table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Broadcast Name</th>
                                    <th>Description</th>
                                    <th>Message</th>
                                    <th>Scheduled Date</th>
                                    <?php
                                    $access_level = $this->session->userdata('access_level');
                                    if ($access_level == "Facility") {
                                        ?>

                                        <?php
                                    } elseif ($access_level == "Admin") {
                                        ?>
                                        <th>Approve</th>
                                        <?php
                                    }
                                    ?>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($broadcasts as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td>
                                            <?php echo $value->description; ?>
                                        </td>
                                        <td><?php echo $value->message; ?></td>
                                        <td><?php echo $value->broadcast_date; ?></td>

                                        <?php
                                        $access_level = $this->session->userdata('access_level');
                                        if ($access_level == "Facility") {
                                            ?>

                                            <?php
                                        } elseif ($access_level == "Admin") {
                                            ?>
                                            <td>
                                                <input type="hidden" name="broadcast_name" value="<?php echo $value->name; ?>" class="form-control broadcsst_name " id="broadcast_name"/>
                                                <input type="hidden" name="broadcast_id" value="<?php echo $value->id; ?>" class="id"/>
                                                <button class="btn btn-primary approve_btn" id="approve_btn">Approve </button>
                                            </td>
                                            <?php
                                        }
                                        ?>

                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>




















                </div>
            </div>                <div class="panel-footer">
                Get   in touch: support.tech@mhealthkenya.org                             </div>

        </div>        












    </div>



</div>
</div>
<!-- END COMMENT AND NOTIFICATION  SECTION -->

</div>








<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', ".approve_btn", function () {
            $('.loader').show();
            //get data
            var broadcast_id = $(this).closest('tr').find('input[name="broadcast_id"]').val();
            var broadcast_name = $(this).closest('tr').find('input[name="broadcast_name"]').val();
            swal({
                title: "Aprove Broadcast ?",
                text: "Broadcast Details : " + broadcast_name + "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Approve it!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
                    function (isconfirm) {

                        if (isconfirm) {
                            $.ajax({
                                url: "<?php echo base_url(); ?>home/approve_broadcast/" + broadcast_id + "",
                                type: 'POST',
                                dataType: 'JSON',
                                success: function (data) {
                                    console.log(data[0].response);
                                    if (data[0].response === true) {

                                        swal({
                                            title: "Approved!",
                                            text: "Broadcast Approved succesfully ",
                                            type: "success",
                                            confirmButtonText: "Okay!",
                                            closeOnConfirm: true
                                        }, function () {
                                            window.location.reload(1);
                                        });
                                    }

                                }, error: function (error) {
                                    if (error === false) {
                                        swal("Failed!", "Broadcast was not approved succesfully", "error");
                                    }
                                }
                            });
                        } else {
                            swal({
                                title: "Reason!",
                                text: "Please provide reason for cancelling :",
                                type: "input",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                animation: "slide-from-top",
                                inputPlaceholder: "Cancelation Reason"
                            },
                                    function (inputValue) {
                                        if (inputValue === false)
                                            return false;
                                        if (inputValue === "") {
                                            swal.showInputError("You need to write something!");
                                            return false
                                        }


                                        $.ajax({
                                            url: "<?php echo base_url(); ?>home/disapprove_broadcast/" + broadcast_id + "",
                                            type: 'POST',
                                            dataType: 'JSON',
                                            data: {reason: inputValue},
                                            success: function (data) {
                                                console.log(data[0].response);
                                                if (data[0].response === true) {

                                                    swal({
                                                        title: "Dis Approved!",
                                                        text: "Broadcast Dis-Approved succesfully ",
                                                        type: "success",
                                                        confirmButtonText: "Okay!",
                                                        closeOnConfirm: true
                                                    }, function () {
                                                        window.location.reload(1);
                                                    });
                                                }

                                            }, error: function (error) {
                                                if (error === false) {
                                                    swal("Failed!", "Broadcast was not approved succesfully", "error");
                                                }
                                            }
                                        });
                                        //swal("Nice!", "You wrote: " + inputValue, "success");
                                    });
                        }



                    });
        });

    });
</script>




<!--END MAIN WRAPPER -->