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
                                    <th>Facility Name</th>
                                    <th>Level</th>
                                    <th>Code</th>
                                    <th>Owner</th>
                                    <th>Facility Type</th>
                                    <th>County </th>
                                    <th>Sub County </th>
                                    <th>Approve</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;

                                foreach ($facilities as $value) {
                                    ?>
                                    <tr>
                                        <td class="a-center"><?php echo $i; ?></td>
                                        <td><?php echo $value->name; ?></td>
                                        <td><?php echo $value->keph_level; ?></td>
                                        <td><?php echo $value->code; ?></td>
                                        <td><?php echo $value->owner; ?></td>
                                        <td><?php echo $value->facility_type; ?></td>   
                                        <td><?php echo $value->county; ?></td>
                                        <td><?php echo $value->sub_county; ?></td>
                                        <?php
                                        $is_approved = $value->is_approved;
                                        if ($is_approved == "Yes") {

                                            $access_level = $this->session->userdata('access_level');
                                            if ($access_level == "Facility") {
                                                ?>
                                                <td><?php echo $value->is_approved; ?></td>
                                                <?php
                                            } else {
                                                ?>
                                                <td><?php echo $value->is_approved; ?></td>
                                                <?php
                                            }
                                        } else {


                                            $access_level = $this->session->userdata('access_level');
                                            if ($access_level == "Facility") {
                                                ?>
                                                <td><?php echo $value->is_approved; ?></td>
                                                <?php
                                            } elseif ($access_level == "Admin" or $access_level == "Partner") {
                                                ?>
                                                <td>
                                                    <input type="hidden" name="facility_name" value="<?php echo $value->name; ?>" class="form-control facility_name " id="broadcast_name"/>
                                                    <input type="hidden" name="mfl_code" value="<?php echo $value->code; ?>" class="mfl_code"/>
                                                    <button class="btn btn-primary approve_btn" id="approve_btn">Approve </button>
                                                </td>
                                                <?php
                                            }
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
            </div>              



        </div>        





        <?php
        $csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        ?>

        <input type="hidden" class="tokenizer" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />








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
            var facility_id = $(this).closest('tr').find('input[name="mfl_code"]').val();
            var facility_name = $(this).closest('tr').find('input[name="facility_name"]').val();
            swal({
                title: "Aprove Facility ?",
                text: "Facility Details : " + facility_name + "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Approve it!",
                cancelButtonText: "No!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
                    function (isconfirm) {
                        var tokenizer = $(".tokenizer").val();



                        if (isconfirm) {
                            $.ajax({
                                url: "<?php echo base_url(); ?>admin/approve_facility/" + facility_id + "",
                                type: 'POST',
                                dataType: 'JSON',
                                data: {tokenizer: tokenizer},
                                success: function (data) {
                                    console.log(data[0].response);
                                    if (data[0].response === true) {

                                        swal({
                                            title: "Approved!",
                                            text: "Facility Addition Approved succesfully ",
                                            type: "success",
                                            confirmButtonText: "Okay!",
                                            closeOnConfirm: true
                                        }, function () {
                                            window.location.reload(1);
                                        });
                                    }

                                }, error: function (error) {
                                    if (error === false) {
                                        swal("Failed!", "Facility Addition was not approved ", "error");
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
                                            url: "<?php echo base_url(); ?>admin/disapprove_facility/" + facility_id + "",
                                            type: 'POST',
                                            dataType: 'JSON',
                                            data: {reason: inputValue, tokenizer: tokenizer},
                                            success: function (data) {
                                                console.log(data[0].response);
                                                if (data[0].response === true) {

                                                    swal({
                                                        title: "Dis Approved!",
                                                        text: "Facilty Addition Dis-Approved succesfully ",
                                                        type: "success",
                                                        confirmButtonText: "Okay!",
                                                        closeOnConfirm: true
                                                    }, function () {
                                                        window.location.reload(1);
                                                    });
                                                }

                                            }, error: function (error) {
                                                if (error === false) {
                                                    swal("Failed!", "Facility addition was not approved succesfully", "error");
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