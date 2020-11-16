</div>
<!-- End Wrapper -->
<!-- All Jquery -->
<script src="<?php echo base_url(); ?>/assets/js/lib/jquery/jquery.min.js">
</script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?php echo base_url(); ?>/assets/js/lib/bootstrap/js/popper.min.js">
</script>
<script src="<?php echo base_url(); ?>/assets/js/lib/bootstrap/js/bootstrap.min.js">
</script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?php echo base_url(); ?>/assets/js/jquery.slimscroll.js"></script>
<!--Menu sidebar -->
<script src="<?php echo base_url(); ?>/assets/js/sidebarmenu.js"></script>
<!--stickey kit -->
<script src="<?php echo base_url(); ?>/assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js">
</script>

<script src="<?php echo base_url(); ?>/assets/js/lib/owl-carousel/owl.carousel.min.js">
</script>
<script src="<?php echo base_url(); ?>/assets/js/lib/owl-carousel/owl.carousel-init.js">
</script>

<!--Custom JavaScript -->
<script src="<?php echo base_url(); ?>/assets/js/custom.min.js"></script>

<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

<script type="text/javascript">
    $(document).ready(function() {
        <?php
        $function_name = $this->uri->segment(2);

        if ($function_name == "appointment_diary") {
        ?>
            $(".download_excel").click(function() {
                var href =
                    "<?php echo base_url(); ?>home/download_app_diary";
                window.location.href = href; //causes the browser to refresh and load the requested url
            });
            $(".download_register").click(function() {
                var href =
                    "<?php echo base_url(); ?>home/download_defaulter_register";
                window.location.href = href; //causes the browser to refresh and load the requested url
            });
        <?php
        } else {
        ?>
        <?php
        }
        ?>
        $('.filter_partner').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_county").hide();
            $(".filter_county_wait").show();
            var partner_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_county/" +
                    partner_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".filter_county").empty();
                    var select = "<option value=''> Please Select : </option>";
                    $(".filter_county").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_county_wait").hide();
                        $(".filter_county").show();
                        var sub_county_options = "<option value=" + value
                            .county_id + ">" + value.county_name + "</option>";
                        $(".filter_county").append(sub_county_options);
                    });
                },
                error: function(jqXHR) {}
            })
        });
        $('.filter_county').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_sub_county").hide();
            $(".filter_sub_county_wait").show();
            var county_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_sub_county/" +
                    county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".filter_sub_county").empty();
                    var select = "<option value=''> Please Select : </option>";
                    $(".filter_sub_county").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_sub_county_wait").hide();
                        $(".filter_sub_county").show();
                        var sub_county_options = "<option value=" + value
                            .sub_county_id + ">" + value.sub_county_name +
                            "</option>";
                        $(".filter_sub_county").append(sub_county_options);
                    });
                },
                error: function(jqXHR) {}
            })
        });
        $('.filter_sub_county').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_facility").hide();
            $(".filter_facility_wait").show();
            var sub_county_id = this.value;
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_facilities/" +
                    sub_county_id + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".filter_facility").empty();
                    var select = "<option value=''>Please Select : </option>";
                    $(".filter_facility").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_facility_wait").hide();
                        $(".filter_facility").show();
                        var facility_options = "<option value=" + value.mfl_code +
                            ">" + value.facility_name + "</option>";
                        $(".filter_facility").append(facility_options);
                    });
                },
                error: function(jqXHR) {}
            });
        });
        $('.filter_facility').on('change', function() {
            // Does some stuff and logs the event to the console
            $(".filter_time").hide();
            $(".filter_time_wait").show();
            var mfl_code = this.value;
            // console.log(this.value);
            $.ajax({
                url: "<?php echo base_url(); ?>Reports/filter_time/" +
                    mfl_code + "/",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    // console.log(data);
                    $(".filter_time").empty();
                    var select = "<option value=''>Please Select : </option>";
                    $(".filter_time").append(select);
                    $.each(data, function(i, value) {
                        $(".filter_time_wait").hide();
                        $(".filter_time").show();
                        var time_value = value.time;
                        console.log(time_value);
                        var facility_options = "<option value=" + time_value + ">" +
                            time_value + "</option>";
                        $(".filter_time").append(facility_options);
                    });
                },
                error: function(jqXHR) {}
            });
        });
        window.submit_data = function(controller, submit_function, form_class, success_alert, error_alert) {
            $(".loader").show();
            $("#" + form_class + "").submit(function(event) {
                event.preventDefault();
                $(".btn").prop('disabled', true);
                dataString = $("#" + form_class + "").serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>" +
                        controller + "/" + submit_function + "",
                    data: dataString,
                    success: function(data) {
                        //console.log(data)
                        // console.log(controller);
                        // console.log(submit_function);
                        // console.log(form_class);
                        // console.log(success_alert);
                        // console.log(error_alert);
                        $(".btn").prop('disabled', false);
                        $(".loader").hide();

                        data = JSON.parse(data);
                        //console.log(data)
                        var response = data[0].response;
                        console.log(response)
                        if (data === "Success" || response == true) {
                            swal({
                                title: "Success!",
                                text: "" + success_alert + "",
                                type: "success",
                                confirmButtonText: "Okay!",
                                closeOnConfirm: true
                            }, function() {
                                window.location.reload(1);
                            });
                        } else if (response === 'Taken') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info", "Clinic No already taken ", 'info');
                        } else if (response === 'Phone Taken') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info", "Phone No already used in the System ",
                                'info');
                        } else if (response === 'Email Taken') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info",
                                "Email Address already used in the System ", 'info');
                        } else if (response === 'Phone Email Taken') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info",
                                "Phone No and Email Address already used in the System ",
                                'info');
                        } else if (response === 'Under Age') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info",
                                "Under Age are not allowed in the System ", 'info');
                        } else if (response === 'Enrollment greater than DoB') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info",
                                "Enrollment Date cannot be before than Date of BIrth",
                                'info');
                        } else if (response === 'ART greater than DoB') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info",
                                "ART Date cannot be before  than Date of Birth ",
                                'info');
                        } else if (response === 'ART less than Enrollment') {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Info",
                                "ART Date cannot be after than Enrollment Date ",
                                'info');
                        } else {
                            $(".btn").prop('disabled', false);
                            sweetAlert("Oops...", "" + error_alert + "", "error");
                        }
                    },
                    error: function(data) {
                        $('.loader').hide();
                        $(".btn").prop('disabled', false);
                        // sweetAlert("Oops...", "" + error_alert + "", "error");
                    }
                });
                event.preventDefault();
                return false;
            });
        }
    });
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.2.1/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.css" />

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.2.1/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.js">
</script>
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/data.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/modules/bullet.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>


<style>
    .dataTables_wrapper {
        font-family: tahoma;
        font-size: 13px;
        position: relative;
        clear: both;
        *zoom: 1;
        zoom: 1;
    }
</style>
<!-- HighCharts Here -->

<script type="text/javascript">
    var dTbles = jQuery.noConflict();
    dTbles(document).ready(function() {
        <?php
        if ($function_name == "facility_home") {
        ?>
            dTbles('.today_app_table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                buttons: [
                    dTbles.extend(true, {}, {
                        extend: 'copyHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'excelHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'pdfHtml5'
                    })
                ],
                title: 'Today Appointments'
            });
            dTbles('.missed_table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                buttons: [
                    dTbles.extend(true, {}, {
                        extend: 'copyHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'excelHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'pdfHtml5'
                    })
                ],
                title: 'Missed Appointments'
            });
            dTbles('.defaulted_table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                buttons: [
                    dTbles.extend(true, {}, {
                        extend: 'copyHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'excelHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'pdfHtml5'
                    })
                ],
                title: 'Defaulted Appointments'
            });
            dTbles('.ltfu_table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                buttons: [
                    dTbles.extend(true, {}, {
                        extend: 'copyHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'excelHtml5'
                    }),
                    dTbles.extend(true, {}, {
                        extend: 'pdfHtml5'
                    })
                ],
                title: 'LTFU Appointments'
            });
        <?php
        }
        ?>
        var report_name = $(".report_name").val();
        dTbles('.table').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            buttons: [
                dTbles.extend(true, {}, {
                    extend: 'copyHtml5',
                    title: report_name
                }),
                dTbles.extend(true, {}, {
                    extend: 'excelHtml5',
                    title: report_name
                }),
                dTbles.extend(true, {}, {
                    extend: 'pdfHtml5',
                    title: report_name
                }),
                dTbles.extend(true, {}, {
                    extend: 'csvHtml5',
                    title: report_name
                })
            ]
        });

        function create_audit_trail_results_layout() {
            var value = dTbles(".audit_trail_search_value").val();
            dTbles.ajax({
                type: 'GET',
                url: "<?php echo base_url(); ?>support/search_audit_trail/" +
                    value,
                dataType: 'JSON',
                "dataSrc": "results",
                success: function(results) {
                    var check_data = jQuery.isEmptyObject(results);
                    if (check_data === true) {
                        dTbles(".audit_trail_search_results_div").hide();
                    } else {
                        dTbles(".audit_trail_search_results_div").empty();
                        var table = '<table class=" dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Date Added : </th> <th> Username  </th> <th>Description  :</th>  <th>Phone Number   : </th>   </tr> </thead>  \n\
               <tbody id="audit_trail_results_tbody" class="audit_trail_results_tbody"> </tbody> </table>';
                        dTbles('.audit_trail_search_results_div').append(table);
                        dTbles.each(results, function(i, messsages) {
                            var tr_results = "<tr>\n\
                    <td>" + messsages.created_at + "</td>\n\
                    <td>" + messsages.user_name + "</td>\n\
                    <td>" + messsages.description + "</td>\n\
                    <td>" + messsages.phone_no + "</td>\n\
                    </tr>";
                            dTbles(".audit_trail_results_tbody").append(tr_results);
                            dTbles(".audit_trail_search_results_div").show();
                        });
                        //dTbles('.dataTables_processing').DataTable({});
                        var table = dTbles('.dataTables_processing').DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            "lengthMenu": [
                                [5, 10, 25, 50, -1],
                                [5, 10, 25, 50, "All"]
                            ],
                            buttons: [
                                dTbles.extend(true, {}, {
                                    extend: 'copyHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'excelHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'pdfHtml5'
                                })
                            ]
                        });
                    }
                }
            });
        }
        dTbles(".audit_trail_serach_btn").click(function() {
            if (!dTbles("input[name='audit_trail_search_option']:checked").val()) {
                sweetAlert("Info",
                    "Nothing is checked!, Please select one of the Search Parameters... ", 'info');
            } else {
                create_audit_trail_results_layout();
            }
        });

        function create_incoming_results_layout() {
            var value = dTbles(".incoming_search_value").val();
            dTbles.ajax({
                type: 'GET',
                url: "<?php echo base_url(); ?>support/search_incoming/" +
                    value,
                dataType: 'JSON',
                "dataSrc": "results",
                success: function(results) {
                    var check_data = jQuery.isEmptyObject(results);
                    if (check_data === true) {
                        dTbles(".incoming_search_results_div").hide();
                    } else {
                        dTbles(".incoming_search_results_div").empty();
                        var table = '<table class=" dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>From  No</th> <th>Destination No :</th> <th>Message : </th><th>Date Added : </th> <th>Time Stamp   : </th><th>Processed : </th>   </tr> </thead>  \n\
               <tbody id="incoming_results_tbody" class="incoming_results_tbody"> </tbody> </table>';
                        dTbles('.incoming_search_results_div').append(table);
                        dTbles.each(results, function(i, messsages) {
                            var tr_results = "<tr>\n\
                    <td>" + messsages.source + "</td>\n\
                    <td>" + messsages.destination + "</td>\n\
                    <td>" + messsages.msg + "</td>\n\
                    <td>" + messsages.created_at + "</td>\n\
                    <td>" + messsages.updated_at + "</td>\n\
                    <td>" + messsages.processed + "</td>\n\
                    </tr>";
                            console.log(tr_results);
                            dTbles(".incoming_results_tbody").append(tr_results);
                            dTbles(".incoming_search_results_div").show();
                        });
                        var table = dTbles('.dataTables_processing').DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            "lengthMenu": [
                                [5, 10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            buttons: [
                                dTbles.extend(true, {}, {
                                    extend: 'copyHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'excelHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'pdfHtml5'
                                })
                            ]
                        });
                    }
                }
            });
        }
        dTbles(".incoming_serach_btn").click(function() {
            if (!dTbles("input[name='incoming_search_option']:checked").val()) {
                sweetAlert("Info",
                    "Nothing is checked!, Please select one of the Search Parameters... ", 'info');
            } else {
                create_incoming_results_layout();
            }
        });

        function create_outgoing_results_layout() {
            var value = dTbles(".outgoing_search_value").val();
            dTbles.ajax({
                type: 'GET',
                url: "<?php echo base_url(); ?>support/search_outgoing/" +
                    value,
                dataType: 'JSON',
                "dataSrc": "results",
                success: function(results) {
                    var check_data = jQuery.isEmptyObject(results);
                    if (check_data === true) {
                        dTbles(".outgoing_search_results_div").hide();
                    } else {
                        dTbles(".outgoing_search_results_div").empty();
                        var table = '<table class=" dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>From  No</th> <th>Destination No :</th> <th>Message : </th><th>Date Added : </th> <th>Time Stamp   : </th><th>Processed : </th>   </tr> </thead>  \n\
               <tbody id="outgoing_results_tbody" class="outgoing_results_tbody"> </tbody> </table>';
                        dTbles('.outgoing_search_results_div').append(table);
                        dTbles.each(results, function(i, messsages) {
                            var tr_results = "<tr>\n\
                    <td>" + messsages.source + "</td>\n\
                    <td>" + messsages.destination + "</td>\n\
                    <td>" + messsages.msg + "</td>\n\
                    <td>" + messsages.created_at + "</td>\n\
                    <td>" + messsages.updated_at + "</td>\n\
                    <td>" + messsages.processed + "</td>\n\
                    </tr>";
                            console.log(tr_results);
                            dTbles(".outgoing_results_tbody").append(tr_results);
                            dTbles(".outgoing_search_results_div").show();
                        });
                        var table = dTbles('.dataTables_processing').DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            "lengthMenu": [
                                [5, 10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            buttons: [
                                dTbles.extend(true, {}, {
                                    extend: 'copyHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'excelHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'pdfHtml5'
                                })
                            ]
                        });
                    }
                }
            });
        }
        dTbles(".outgoing_serach_btn").click(function() {
            if (!dTbles("input[name='outgoing_search_option']:checked").val()) {
                sweetAlert("Info",
                    "Nothing is checked!, Please select one of the Search Parameters... ", 'info');
            } else {
                create_outgoing_results_layout();
            }
        });

        function create_results_layout() {
            var value = dTbles(".search_value").val();
            dTbles.ajax({
                type: 'GET',
                url: "<?php echo base_url(); ?>admin/search_facility/" +
                    value,
                dataType: 'JSON',
                "dataSrc": "results",
                success: function(results) {
                    var check_data = jQuery.isEmptyObject(results);
                    if (check_data === true) {
                        dTbles(".search_results_div").hide();
                        sweetAlert("Info",
                            "Search result , returned no value... , Facility does not exist ",
                            'info');
                    } else {
                        dTbles(".search_results_div").empty();
                        dTbles('.facility_listing').DataTable().clear();
                        dTbles('.facility_listing').DataTable().destroy();
                        var table = '  <table class=" display nowrap table table-hover table-striped table-bordered facility_listing " cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th>MFL No :</th> <th>County : </th><th>Sub County : </th> <th>Consituency : </th><th>Owner: </th> <th>Add Facility </th>  </tr> </thead>  \n\
               <tbody id="results_tbody" class="results_tbody"> </tbody> </table> ';
                        dTbles('.search_results_div').append(table);
                        dTbles.each(results, function(i, facilities) {
                            var tr_results = "<tr>\n\
                    <td>" + facilities.facility_name + "</td>\n\
                    <td>" + facilities.mfl_code + "</td>\n\
                    <td>" + facilities.owner + "</td>\n\
                    <td>" + facilities.county_name + "</td>\n\
                    <td>" + facilities.sub_county_name + "</td>\n\
                    <td>" + facilities.consituency_name + "</td>\n\
                    <td><input type='hidden' id='hidden_mfl_code' class=' hidden_mfl_code form-control' name='hidden_mfl_code' value='" +
                                facilities.mfl_code + "'/>\n\
                    <button data-toggle='modal' data-target='#AddFacilityModal' class='btn btn-xs btn-default select_mfl_btn' id='select_mfl_btn' data-original-title='Select Facility'>\n\
                    <i class='fa fa-plus'></i> </button></td>  \n\
                    </tr>";
                            dTbles(".results_tbody").append(tr_results);
                        });
                        dTbles(".search_results_div").show();
                        dTbles('.facility_listing').DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            "lengthMenu": [
                                [5, 10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            buttons: [
                                dTbles.extend(true, {}, {
                                    extend: 'copyHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'excelHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'pdfHtml5'
                                })
                            ]
                        });
                    }
                }
            });
        }
        dTbles(".search_value").keyup(function() {
            create_results_layout();
        });
        window.create_percentage_counties_report = function create_percentage_counties_report(data) {
            dTbles(".percentage_counties_div").empty();
            var table = '<table id="percentage_counties_table" class=" percentage_counties_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="percentage_counties_tbody" class="percentage_counties_tbody"> </tbody> </table>';
            dTbles('.percentage_counties_div').append(table);
            dTbles.each(data, function(i, val) {
                var target_counties = val.target_counties;
                var actual_counties = val.actual_counties;
                var tr_results = "<tr>\n\
                    <td>Target Counties</td>\n\
                    <td>" + target_counties + "</td>\n\
                    </tr><tr>\n\
                     <td>Actual Counties</td>\n\
                    <td>" + actual_counties + "</td>\n\</tr>";
                dTbles(".percentage_counties_tbody").append(tr_results);
            });
            dTbles(".percentage_counties_div").show();
            dTbles('.percentage_counties_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_reg_report = function create_client_reg_report(data) {
            dTbles(".client_reg_report_div").empty();
            var table = '<table id="client_reg_dashboard_table" class=" client_reg_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_reg_results_tbody" class="client_reg_results_tbody"> </tbody> </table>';
            dTbles('.client_reg_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_reg_results_tbody").append(tr_results);
            });
            dTbles(".client_reg_report_div").show();
            dTbles('.client_reg_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_appointment_report = function create_client_appointment_report(data) {
            dTbles(".client_appointment_report_div").empty();
            var table =
                '<table id="client_appointment_dashboard_table" class=" client_appointment_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_appointment_results_tbody" class="client_appointment_results_tbody"> </tbody> </table>';
            dTbles('.client_appointment_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_appointment_results_tbody").append(tr_results);
            });
            dTbles(".client_appointment_report_div").show();
            dTbles('.client_appointment_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_gender_report = function create_gender_report(data) {
            var table = dTbles('#gender_dashboard_table').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'colvis']
            });
            table.buttons().container()
                .appendTo('#table_wrapper .col-sm-6:eq(0)');
            dTbles(".gender_report_div").empty();
            var table = '<table id="gender_dashboard_table" class=" gender_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="gender_results_tbody" class="gender_results_tbody"> </tbody> </table>';
            dTbles('.gender_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.name;
                var value = val.value;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".gender_results_tbody").append(tr_results);
            });
            dTbles(".gender_report_div").show();
            dTbles('.gender_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_message_distribution_report = function create_message_distribution_report(data) {
            dTbles(".message_distribution_report_div").empty();
            var table = dTbles('#message_distributon_dashboard_table').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'colvis']
            });
            table.buttons().container()
                .appendTo('#table_wrapper .col-sm-6:eq(0)');
            var table =
                '<table id="message_distributon_dashboard_table" class=" message_distributon_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="message_distribution_results_tbody" class="message_distribution_results_tbody"> </tbody> </table>';
            dTbles('.message_distribution_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.message_type;
                var value = val.no_messages;
                //console.log('Message Type => ' + name + ' No of Messages => ' + value + ' end ');
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".message_distribution_results_tbody").append(tr_results);
            });
            dTbles(".message_distribution_report_div").show();
            dTbles('.message_distributon_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_language_report = function create_client_language_report(data) {
            dTbles(".language_report_div").empty();
            var table = '<table id="language_dashboard_table" class=" language_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="language_results_tbody" class="language_results_tbody"> </tbody> </table>';
            dTbles('.language_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.NAME;
                var value = val.VALUE;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".language_results_tbody").append(tr_results);
            });
            dTbles(".language_report_div").show();
            dTbles('.language_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_marital_report = function create_client_marital_report(data) {
            dTbles(".marital_report_div").empty();
            var table = '<table id="marital_dashboard_table" class=" marital_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="marital_results_tbody" class="marital_results_tbody"> </tbody> </table>';
            dTbles('.marital_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.NAME;
                var value = val.VALUE;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".marital_results_tbody").append(tr_results);
            });
            dTbles(".marital_report_div").show();
            dTbles('.marital_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_type_report = function create_client_type_report(data) {
            dTbles(".type_report_div").empty();
            var table = '<table id="type_dashboard_table" class=" type_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="type_results_tbody" class="type_results_tbody"> </tbody> </table>';
            dTbles('.type_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".type_results_tbody").append(tr_results);
            });
            dTbles(".type_report_div").show();
            dTbles('.type_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_category_report = function create_client_category_report(data) {
            dTbles(".client_category_report_div").empty();
            var table = '<table id="client_category_dashboard_table" class=" client_category_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_category_results_tbody" class="client_category_results_tbody"> </tbody> </table>';
            dTbles('.client_category_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_category_results_tbody").append(tr_results);
            });
            dTbles(".client_category_report_div").show();
            dTbles('.client_category_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_age_group_report = function create_client_age_group_report(data) {
            dTbles(".client_age_group_report_div").empty();
            var table = '<table id="client_age_group_dashboard_table" class=" client_age_group_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_age_group_results_tbody" class="client_age_group_results_tbody"> </tbody> </table>';
            dTbles('.client_age_group_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_age_group_results_tbody").append(tr_results);
            });
            dTbles(".client_age_group_report_div").show();
            dTbles('.client_age_group_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_condition_report = function create_client_condition_report(data) {
            dTbles(".client_condition_report_div").empty();
            var table = '<table id="client_condition_dashboard_table" class=" client_condition_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_condition_results_tbody" class="client_condition_results_tbody"> </tbody> </table>';
            dTbles('.client_condition_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_condition_results_tbody").append(tr_results);
            });
            dTbles(".client_condition_report_div").show();
            dTbles('.client_condition_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_status_report = function create_client_status_report(data) {
            dTbles(".client_status_report_div").empty();
            var table = '<table id="client_status_dashboard_table" class=" client_status_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_status_results_tbody" class="client_status_results_tbody"> </tbody> </table>';
            dTbles('.client_status_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_status_results_tbody").append(tr_results);
            });
            dTbles(".client_status_report_div").show();
            dTbles('.client_status_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_consented_report = function create_client_consented_report(data) {
            dTbles(".client_consented_report_div").empty();
            var table = '<table id="client_consented_dashboard_table" class=" client_consented_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_consented_results_tbody" class="client_consented_results_tbody"> </tbody> </table>';
            dTbles('.client_consented_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.k;
                var value = val.v;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_consented_results_tbody").append(tr_results);
            });
            dTbles(".client_consented_report_div").show();
            dTbles('.client_consented_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_consented_gender_report = function create_client_consented_gender_report(data) {
            dTbles(".client_consented_gender_report_div").empty();
            var table =
                '<table id="client_consented_gender_dashboard_table" class=" client_consented_gender_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_consented_gender_results_tbody" class="client_consented_gender_results_tbody"> </tbody> </table>';
            dTbles('.client_consented_gender_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.gender;
                var value = val.total_client;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_consented_gender_results_tbody").append(tr_results);
            });
            dTbles(".client_consented_gender_report_div").show();
            dTbles('.client_consented_gender_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_consented_marital_report = function create_client_consented_marital_report(data) {
            dTbles(".client_consented_marital_report_div").empty();
            var table =
                '<table id="client_consented_marital_dashboard_table" class=" client_consented_marital_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_consented_marital_results_tbody" class="client_consented_marital_results_tbody"> </tbody> </table>';
            dTbles('.client_consented_marital_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.marital;
                var value = val.total_client;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_consented_marital_results_tbody").append(tr_results);
            });
            dTbles(".client_consented_marital_report_div").show();
            dTbles('.client_consented_marital_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_consented_category_report = function create_client_consented_category_report(
            data) {
            dTbles(".client_consented_category_report_div").empty();
            var table =
                '<table id="client_consented_category_dashboard_table" class=" client_consented_category_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_consented_category_results_tbody" class="client_consented_category_results_tbody"> </tbody> </table>';
            dTbles('.client_consented_category_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.group_name;
                var value = val.total_client;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_consented_category_results_tbody").append(tr_results);
            });
            dTbles(".client_consented_category_report_div").show();
            dTbles('.client_consented_category_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_appointment_status_report = function create_client_appointment_status_report(
            data) {
            dTbles(".client_appointment_status_report_div").empty();
            var table =
                '<table id="client_appointment_status_dashboard_table" class=" client_appointment_status_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_appointment_status_results_tbody" class="client_appointment_status_results_tbody"> </tbody> </table>';
            dTbles('.client_appointment_status_report_div').append(table);
            dTbles.each(data, function(i, val) {
                var name = val.Missed;
                var value = val.total;
                var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                dTbles(".client_appointment_status_results_tbody").append(tr_results);
            });
            dTbles(".client_appointment_status_report_div").show();
            dTbles('.client_appointment_status_dashboard_table').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "bInfo": false,
                "bPaginate": false,
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':contains("Office")'
                        }
                    },
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
        window.create_client_booked_appointment_status_report =
            function create_client_booked_appointment_status_report(data) {
                dTbles(".client_booked_appointment_status_report_div").empty();
                var table =
                    '<table id="client_booked_appointment_status_dashboard_table" class=" client_booked_appointment_status_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_booked_appointment_status_results_tbody" class="client_booked_appointment_status_results_tbody"> </tbody> </table>';
                dTbles('.client_booked_appointment_status_report_div').append(table);
                dTbles.each(data, function(i, val) {
                    var name = val.group_name;
                    var value = val.total;
                    var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                    dTbles(".client_booked_appointment_status_results_tbody").append(tr_results);
                });
                dTbles(".client_booked_appointment_status_report_div").show();
                dTbles('.client_booked_appointment_status_dashboard_table').DataTable({
                    dom: 'Bfrtip',
                    "searching": false,
                    "bInfo": false,
                    "bPaginate": false,
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: ':contains("Office")'
                            }
                        },
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                });
            }
        window.create_client_notified_appointment_status_report =
            function create_client_notified_appointment_status_report(data) {
                dTbles(".client_notified_appointment_status_report_div").empty();
                var table =
                    '<table id="client_notified_appointment_status_dashboard_table" class=" client_notified_appointment_status_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_notified_appointment_status_results_tbody" class="client_notified_appointment_status_results_tbody"> </tbody> </table>';
                dTbles('.client_notified_appointment_status_report_div').append(table);
                dTbles.each(data, function(i, val) {
                    var name = val.group_name;
                    var value = val.total;
                    var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                    dTbles(".client_notified_appointment_status_results_tbody").append(tr_results);
                });
                dTbles(".client_notified_appointment_status_report_div").show();
                dTbles('.client_notified_appointment_status_dashboard_table').DataTable({
                    dom: 'Bfrtip',
                    "searching": false,
                    "bInfo": false,
                    "bPaginate": false,
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: ':contains("Office")'
                            }
                        },
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                });
            }
        window.create_client_missed_appointment_status_report =
            function create_client_missed_appointment_status_report(data) {
                dTbles(".client_missed_appointment_status_report_div").empty();
                var table =
                    '<table id="client_missed_appointment_status_dashboard_table" class=" client_missed_appointment_status_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_missed_appointment_status_results_tbody" class="client_missed_appointment_status_results_tbody"> </tbody> </table>';
                dTbles('.client_missed_appointment_status_report_div').append(table);
                dTbles.each(data, function(i, val) {
                    var name = val.group_name;
                    var value = val.total;
                    var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                    dTbles(".client_missed_appointment_status_results_tbody").append(tr_results);
                });
                dTbles(".client_missed_appointment_status_report_div").show();
                dTbles('.client_missed_appointment_status_dashboard_table').DataTable({
                    dom: 'Bfrtip',
                    "searching": false,
                    "bInfo": false,
                    "bPaginate": false,
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: ':contains("Office")'
                            }
                        },
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                });
            }
        window.create_client_defaulted_appointment_status_report =
            function create_client_defaulted_appointment_status_report(data) {
                dTbles(".client_defaulted_appointment_status_report_div").empty();
                var table =
                    '<table id="client_defaulted_appointment_status_dashboard_table" class=" client_defaulted_appointment_status_dashboard_table dashboard_report dataTables_processing  table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">\n\
             <thead> <tr> <th>Name</th> <th> No :</th>  </tr> </thead>  \n\
               <tbody id="client_defaulted_appointment_status_results_tbody" class="client_defaulted_appointment_status_results_tbody"> </tbody> </table>';
                dTbles('.client_defaulted_appointment_status_report_div').append(table);
                dTbles.each(data, function(i, val) {
                    var name = val.group_name;
                    var value = val.total;
                    var tr_results = "<tr>\n\
                    <td>" + name + "</td>\n\
                    <td>" + value + "</td>\n\</td>\n\
                    </tr>";
                    dTbles(".client_defaulted_appointment_status_results_tbody").append(tr_results);
                });
                dTbles(".client_defaulted_appointment_status_report_div").show();
                dTbles('.client_defaulted_appointment_status_dashboard_table').DataTable({
                    dom: 'Bfrtip',
                    "searching": false,
                    "bInfo": false,
                    "bPaginate": false,
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: ':contains("Office")'
                            }
                        },
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                });
            }
        dTbles(".filter_monthly_appointment_extract").click(function(e) {
            document.getElementById('loading').style.display = 'block'
            // document.getElementById('loading').modal("show")



            e.preventDefault()
            var access_level = $('#access_level').val();
            var partner_id = $('#partner_id').val();
            var facility_id = $('#facility_id').val();
            var sub_county_id = $('#sub_county_id').val();
            var partner = dTbles(".filter_partner").val();
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            var filter_time = dTbles(".filter_time").val();
            console.log(sub_county_id);
            if (access_level == 'Partner') {
                partner = partner_id;
            }
            if (access_level == 'Facility') {
                facility = facility_id;
            }
            if (access_level == 'Sub County') {
                sub_county = sub_county_id;
            }
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_filter_time = "";
            if (filter_time.length > 0) {
                selected_filter_time = "From : " + dTbles(".filter_time").val() + " - ";
            }
            if (partner != "") {
                selected_partner = "For Partner: " + dTbles(".filter_partner option:selected").text() +
                    "";
            } else {
                if (access_level == 'Partner') {
                    selected_partner = "For Partner: " + access_level + "";
                }
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() + " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var description_two = " " + selected_filter_time + " ";
            var tokenizer = dTbles(".tokenizer").val();
            generate_monthly_appointment_report(county, sub_county, facility, filter_time,
                description_one, description_two, tokenizer, partner);
        });

        function generate_monthly_appointment_report(county, sub_county, facility, filter_time, description_one,
            description_two, tokenizer, partner) {
            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }
            var processed_json = new Array();
            dTbles(".gender_report_div").empty();
            dTbles.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_monthly_appointment_report/",
                dataType: 'JSON',
                data: {
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    filter_time: filter_time,
                    tokenizer: tokenizer,
                    partner: partner
                },
                success: function(data) {
                    dTbles(".appointment_summary_reports_div").empty();
                    dTbles('.appointment_summary_listing').DataTable().clear();
                    dTbles('.appointment_summary_listing').DataTable().destroy();
                    var table = '  <table class=" display nowrap table table-hover table-striped table-bordered appointment_summary_listing " cellspacing="0" width="100%">\n\
             <thead> <tr>  <th>Label  :</th> <th>Partner : </th><th>County  : </th> <th>Sub County  : </th>\n\
            <th>MFL Code : </th> <th>Facility name  </th> <th>Month year : </th><th>0-9 : </th> <th> 10-14 : </th><th>15-19 : </th><th>20-24 : </th> <th>25+ : </th><th>TOTAL : </th><th>Total_Female : </th><th>Total_Male : </th><th>Total_Transgender : </th><th>Total_Not_Provided : </th>\n\
               </tr> </thead>  \n\
               <tbody id="results_tbody" class="results_tbody"> </tbody> </table> ';
                    dTbles('.appointment_summary_reports_div').append(table);
                    dTbles.each(data, function(i, value) {
                        var label = value.label;
                        var partner = value.partner;
                        var county = value.county;
                        var sub_county = value.sub_county;
                        var mfl_code = value.mfl_code;
                        var facility_name = value.facility_name;
                        var time = value.time;
                        var ToNine = value.ToNine;
                        var ToFourteen = value.ToFourteen;
                        var ToNineteen = value.ToNineteen;
                        var ToTwentyFour = value.ToTwentyFour;
                        var PlusTwentyFive = value.PlusTwentyFive;
                        var TOTAL = value.TOTAL;
                        var Total_Female = value.Total_Female;
                        var Total_Male = value.Total_Male;
                        var Total_Transgender = value.Total_Transgender;
                        var Total_Not_Provided = value.Total_Not_Provided;
                        var tr_results = "<tr>\n\
                    <td>" + label + "</td>\n\
                    <td>" + partner + "</td>\n\
                    <td>" + county + "</td>\n\
                    <td>" + sub_county + "</td>\n\
                    <td>" + mfl_code + "</td>\n\
                    <td>" + facility_name + "</td>\n\
                    <td>" + time + "</td>\n\
                    <td>" + ToNine + "</td>\n\
                    <td>" + ToFourteen + "</td>\n\
                    <td>" + ToNineteen + "</td>\n\
                    <td>" + ToTwentyFour + "</td>\n\
                    <td>" + PlusTwentyFive + "</td>\n\
                    <td>" + TOTAL + "</td>\n\
                    <td>" + Total_Female + "</td>\n\
                    <td>" + Total_Male + "</td>\n\
                    <td>" + Total_Transgender + "</td>\n\
                    <td>" + Total_Not_Provided + "</td>\n\
                    </tr>";
                        dTbles(".results_tbody").append(tr_results);
                    });
                    dTbles(".appointment_summary_reports_div").show();
                    dTbles('.appointment_summary_listing').DataTable({
                        dom: 'Bfrtip',
                        responsive: true,
                        "lengthMenu": [
                            [5, 10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        buttons: [
                            dTbles.extend(true, {}, {
                                extend: 'copyHtml5'
                            }),
                            dTbles.extend(true, {}, {
                                extend: 'excelHtml5'
                            }),
                            dTbles.extend(true, {}, {
                                extend: 'pdfHtml5'
                            })
                        ]
                    });
                    document.getElementById('loading').style.display = 'none'
                    //document.getElementById('loading').modal("hide")

                }
            });
        }
        dTbles("#filter_highcharts_dashboard").click(function() {
            var access_level = $('#access_level').val();
            var partner_id = $('#partner_id').val();
            var facility_id = $('#facility_id').val();
            var partner = dTbles(".filter_partner").val();
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            if (access_level == 'Partner') {
                partner = partner_id;
            }
            if (access_level == 'Facility') {
                facility = facility_id;
            }
            var selected_partner = "";
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            if (partner != "") {
                selected_partner = "For Partner: " + dTbles(".filter_partner option:selected").text() +
                    "";
            } else {
                if (access_level == 'Partner') {
                    selected_partner = "For Partner: " + access_level + "";
                }
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() + " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var tokenizer = dTbles(".tokenizer").val();
            filter_highcharts_dashboard(partner, county, sub_county, facility,
                description_one, tokenizer);
        });

        function filter_highcharts_dashboard(partner, county, sub_county, facility,
            description_one, tokenizer) {
            var final_description;
            if (description_one == undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ';
            }
            let url =
                "<?php echo base_url(); ?>Reports/filter_highcharts_dashboard/";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    partner: partner,
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    tokenizer: tokenizer
                },
                success: function(results) {
                    result = JSON.parse(results)
                    $("#targetClients").empty();
                    $("#targetClients").append("  <b> " + result.target_active_clients +
                        "<br></b> Target Active Clients");
                    $("#totalClients").empty();
                    $("#totalClients").append("  <b> " + result.total_clients +
                        "<br></b> No. of Clients");
                    $("#percentageUptake").empty();
                    $("#percentageUptake").append("  <b> " + result.percentage_uptake +
                        "<br></b>   % No. of Active Clients");
                    $("#consentedClients").empty();
                    $("#consentedClients").append("  <b> " + result.consented_clients +
                        "<br></b> Consented Clients");
                    $("#furuteAppointments").empty();
                    $("#furuteAppointments").append("  <b> " + result.future_appointments +
                        "<br></b> Future Appointments");
                    $("#facilities").empty();
                    $("#facilities").append("  <b> " + result.facilities +
                        "<br></b> Facilities");
                    $("#map").empty();
                    $("#column").empty();
                    maps(result.data)
                    columnChart(result.bar_clients_data, result.bar_appointments_data);
                    async function maps(data) {
                        let geojson = await fetchJSON('/kenyan-counties.geojson');
                        // Initiate the chart
                        Highcharts.mapChart('map', {
                            chart: {
                                map: geojson,
                                height: 600
                            },
                            title: {
                                text: 'Uptake by County'
                            },
                            legend: {
                                layout: 'horizontal',
                                borderWidth: 0,
                                backgroundColor: 'rgba(255,255,255,0.85)',
                                floating: true,
                                verticalAlign: 'top',
                                y: 25
                            },
                            exporting: {
                                sourceWidth: 600,
                                sourceHeight: 500
                            },
                            mapNavigation: {
                                enabled: true
                            },
                            colorAxis: {
                                min: 1,
                                type: 'logarithmic',
                                minColor: '#fa520a',
                                maxColor: '#ed3512',
                                stops: [
                                    [0, '#fa520a'],
                                    [0.67, '#ed3512'],
                                    [1, '#ed3512']
                                ]
                            },
                            series: [{
                                data: data,
                                keys: ['Clients'],
                                joinBy: 'county_id',
                                name: 'Results by County',
                                states: {
                                    hover: {
                                        color: '#004D1A'
                                    }
                                },
                                dataLabels: {
                                    enabled: false,
                                    format: '{point.properties.COUNTY}'
                                },
                                tooltip: {
                                    pointFormat: 'County: {point.properties.COUNTY}<br> Clients: {point.Clients} <br> Consented: {point.Consented} <br> Total Target Clients: {point.Target_Clients} <br> Male: {point.Male} <br> Female: {point.Female} <br> TransGender: {point.Trans_Gender} <br> No. of Facilities: {point.mfl_code} <br> % Uptake Per County: {point.Percentage_Uptake}'
                                }
                            }]
                        });
                    }

                    function columnChart(bar_clients_data, bar_appointments_data) {
                        let barData = bar_clients_data.concat(bar_appointments_data)
                        let result = barData.reduce((acc, el) => {
                            var existEl = acc.find(e => e.MONTH == el.MONTH && e
                                .mfl_code ==
                                el.mfl_code);
                            if (existEl) {
                                existEl.appointments = el.appointments;
                            } else {
                                acc.push(el);
                            }
                            return acc;
                        }, []);
                        result = result.map(elem => {
                            if (!elem.clients) elem.clients = 0
                            if (!elem.consented) elem.consented = 0
                            if (!elem.appointments) elem.appointments = 0
                            return elem
                        })
                        let categories = new Set();
                        let series = []
                        for (let i = 0; i < result.length; i++) {
                            categories.add(result[i].MONTH)
                        }
                        let clientsArray = [];
                        let consentedArray = [];
                        let appointmentsArray = [];
                        for (let category of categories) {
                            let categoryDatas = result.filter(function(categoryData) {
                                return categoryData.MONTH == category;
                            });
                            let clientsCount = 0;
                            let consentedCount = 0;
                            let appointmentsCount = 0;
                            for (let j in categoryDatas) {
                                clientsCount = clientsCount + parseInt(categoryDatas[j]
                                    .clients)
                                consentedCount = consentedCount + parseInt(categoryDatas[j]
                                    .consented)
                                appointmentsCount = appointmentsCount + parseInt(
                                    categoryDatas[j]
                                    .appointments)
                            }
                            clientsArray.push(clientsCount)
                            consentedArray.push(consentedCount)
                            appointmentsArray.push(appointmentsCount)
                        }
                        series.push({
                            name: 'Registered Clients',
                            data: clientsArray
                        })
                        series.push({
                            name: 'Consented Clients',
                            data: consentedArray
                        })
                        series.push({
                            name: 'Total Appointments',
                            data: appointmentsArray
                        })
                        // console.log(series)
                        Highcharts.chart('column', {
                            chart: {
                                type: 'column',
                                height: 300
                            },
                            title: {
                                text: 'Monthly Numbers Series'
                            },
                            xAxis: {
                                categories: [...categories],
                                crosshair: true
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Count'
                                }
                            },
                            tooltip: {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                            plotOptions: {
                                column: {
                                    pointPadding: 0.2,
                                    borderWidth: 0
                                }
                            },
                            series: series
                        });
                    }
                }
            });
        }

        dTbles("#filter_highcharts_appointment_dashboard").click(function() {
            var access_level = $('#access_level').val();
            var partner_id = $('#partner_id').val();
            var facility_id = $('#facility_id').val();
            var partner = dTbles(".filter_partner").val();
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            var date_from = dTbles(".date_from").val();
            var date_to = dTbles(".date_to").val();
            // console.log(date_from)
            if (access_level == 'Partner') {
                partner = partner_id;
            }
            if (access_level == 'Facility') {
                facility = facility_id;
            }
            var selected_partner = "";
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_date_from = "";
            var selected_date_to = "";
            if (date_from.length > 0) {
                selected_date_from = "From : " + dTbles(".date_from").val() + " - ";
            }
            if (date_to.length > 0) {
                selected_date_to = "To : " + dTbles(".date_to").val();
            }
            if (partner != "") {
                selected_partner = "For Partner: " + dTbles(".filter_partner option:selected").text() +
                    "";
            } else {
                if (access_level == 'Partner') {
                    selected_partner = "For Partner: " + access_level + "";
                }
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() + " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            var tokenizer = dTbles(".tokenizer").val();
            filter_tablecharts_appointment_dashboard(partner, county, sub_county, facility, date_from, date_to,
                description_one, description_two, tokenizer);
        });

        function filter_tablecharts_appointment_dashboard(partner, county, sub_county, facility, date_from, date_to,
            description_one, description_two, tokenizer) {
            var final_description;
            if (description_one == undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }

            let url =
                "<?php echo base_url(); ?>Reports/filter_tablecharts_appointment_dashboard/";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    partner: partner,
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    date_from: date_from,
                    date_to: date_to,
                    tokenizer: tokenizer
                },
                success: function(results) {
                    result = JSON.parse(results)
                    $("#allApps").empty();
                    $("#allApps").append("  <b> " + result.created_appointments +
                        "<br></b> Created Appointments");
                    $("#keptApps").empty();
                    $("#keptApps").append("  <b> " + result.kept_appointments +
                        "<br></b> Honoured Appointments");
                    $("#defaultedApps").empty();
                    $("#defaultedApps").append("  <b> " + result.defaulted_appointments +
                        "<br></b>  Active Defaulted Appointments");
                    $("#missedApps").empty();
                    $("#missedApps").append("  <b> " + result.missed_appointments +
                        "<br></b> Active Missed Appointments");
                    $("#ltfuApps").empty();
                    $("#ltfuApps").append("  <b> " + result.ltfu_appointments +
                        "<br></b> Active LTFU Appointments");
                    $("#container").empty();
                    $("#marriage").empty();

                    parseMarriage = result.marriage_appointments;
                    parseRecords = result.data;

                    var keptArray = []
                    var defaultedArray = []
                    var missedArray = []
                    var ltfuArray = []


                    const toNineKept = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nine_kept), 0)
                    const toFourteenKept = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_fourteen_kept), 0)
                    const toNineteenKept = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nineteen_kept), 0)
                    const toTwentyfourKept = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_twenty_four_kept), 0)
                    const toTwentyfiveKept = parseRecords.reduce((total, parseR) => total + parseInt(parseR.plus_twenty_five_kept), 0)

                    const toNineDefaulted = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nine_defaulted), 0)
                    const toFourteenDefaulted = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_fourteen_defaulted), 0)
                    const toNineteenDefaulted = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nineteen_defaulted), 0)
                    const toTwentyfourDefaulted = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_twenty_four_defaulted), 0)
                    const toTwentyfiveDefaulted = parseRecords.reduce((total, parseR) => total + parseInt(parseR.plus_twenty_five_defaulted), 0)

                    const toNineMissed = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nine_missed), 0)
                    const toFourteenMissed = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_fourteen_missed), 0)
                    const toNineteenMissed = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nineteen_missed), 0)
                    const toTwentyfourMissed = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_twenty_four_missed), 0)
                    const toTwentyfiveMissed = parseRecords.reduce((total, parseR) => total + parseInt(parseR.plus_twenty_five_missed), 0)

                    const toNineLtfu = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nine_ltfu), 0)
                    const toFourteenLtfu = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_fourteen_ltfu), 0)
                    const toNineteenLtfu = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_nineteen_ltfu), 0)
                    const toTwentyfourLtfu = parseRecords.reduce((total, parseR) => total + parseInt(parseR.to_twenty_four_ltfu), 0)
                    const toTwentyfiveLtfu = parseRecords.reduce((total, parseR) => total + parseInt(parseR.plus_twenty_five_ltfu), 0)

                    keptArray.push(toNineKept)
                    keptArray.push(toFourteenKept)
                    keptArray.push(toNineteenKept)
                    keptArray.push(toTwentyfourKept)
                    keptArray.push(toTwentyfiveKept)

                    defaultedArray.push(toNineDefaulted)
                    defaultedArray.push(toFourteenDefaulted)
                    defaultedArray.push(toNineteenDefaulted)
                    defaultedArray.push(toTwentyfourDefaulted)
                    defaultedArray.push(toTwentyfiveDefaulted)

                    missedArray.push(toNineMissed)
                    missedArray.push(toFourteenMissed)
                    missedArray.push(toNineteenMissed)
                    missedArray.push(toTwentyfourMissed)
                    missedArray.push(toTwentyfiveMissed)

                    ltfuArray.push(toNineLtfu)
                    ltfuArray.push(toFourteenLtfu)
                    ltfuArray.push(toNineteenLtfu)
                    ltfuArray.push(toTwentyfourLtfu)
                    ltfuArray.push(toTwentyfiveLtfu)

                    var marriageKeptArray = []
                    var marriageDefaultedArray = []
                    var marriageMissedArray = []
                    var marriageLtfuArray = []

                    const singleKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.single_kept), 0)
                    const monogamousKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.married_monogamous_kept), 0)
                    const divorcedKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.divorced_kept), 0)
                    const widowedKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.widowed_kept), 0)
                    const cohabitingKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.cohabiting_kept), 0)
                    const unavailableKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unavailable_kept), 0)
                    const polygamousKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.maried_polygamous_kept), 0)
                    const unapplicableKept = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unapplicable_kept), 0)

                    const singleDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.single_defaulted), 0)
                    const monogamousDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.married_monogamous_defaulted), 0)
                    const divorcedDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.divorced_defaulted), 0)
                    const widowedDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.widowed_defaulted), 0)
                    const cohabitingDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.cohabiting_defaulted), 0)
                    const unavailableDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unavailable_defaulted), 0)
                    const polygamousDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.maried_polygamous_defaulted), 0)
                    const unapplicableDefaulted = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unapplicable_defaulted), 0)

                    const singleMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.single_missed), 0)
                    const monogamousMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.married_monogamous_missed), 0)
                    const divorcedMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.divorced_missed), 0)
                    const widowedMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.widowed_missed), 0)
                    const cohabitingMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.cohabiting_missed), 0)
                    const unavailableMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unavailable_missed), 0)
                    const polygamousMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.maried_polygamous_missed), 0)
                    const unapplicableMissed = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unapplicable_missed), 0)

                    const singleLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.single_ltfu), 0)
                    const monogamousLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.married_monogamous_ltfu), 0)
                    const divorcedLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.divorced_ltfu), 0)
                    const widowedLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.widowed_ltfu), 0)
                    const cohabitingLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.cohabiting_ltfu), 0)
                    const unavailableLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unavailable_ltfu), 0)
                    const polygamousLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.maried_polygamous_ltfu), 0)
                    const unapplicableLtfu = parseMarriage.reduce((total, parseM) => total + parseInt(parseM.unapplicable_ltfu), 0)

                    marriageKeptArray.push(singleKept)
                    marriageKeptArray.push(monogamousKept)
                    marriageKeptArray.push(divorcedKept)
                    marriageKeptArray.push(widowedKept)
                    marriageKeptArray.push(cohabitingKept)
                    marriageKeptArray.push(unavailableKept)
                    marriageKeptArray.push(polygamousKept)
                    marriageKeptArray.push(unapplicableKept)

                    marriageDefaultedArray.push(singleDefaulted)
                    marriageDefaultedArray.push(monogamousDefaulted)
                    marriageDefaultedArray.push(divorcedDefaulted)
                    marriageDefaultedArray.push(widowedDefaulted)
                    marriageDefaultedArray.push(cohabitingDefaulted)
                    marriageDefaultedArray.push(unavailableDefaulted)
                    marriageDefaultedArray.push(polygamousDefaulted)
                    marriageDefaultedArray.push(unapplicableDefaulted)

                    marriageMissedArray.push(singleMissed)
                    marriageMissedArray.push(monogamousMissed)
                    marriageMissedArray.push(divorcedMissed)
                    marriageMissedArray.push(widowedMissed)
                    marriageMissedArray.push(cohabitingMissed)
                    marriageMissedArray.push(unavailableMissed)
                    marriageMissedArray.push(polygamousMissed)
                    marriageMissedArray.push(unapplicableMissed)

                    marriageLtfuArray.push(singleLtfu)
                    marriageLtfuArray.push(monogamousLtfu)
                    marriageLtfuArray.push(divorcedLtfu)
                    marriageLtfuArray.push(widowedLtfu)
                    marriageLtfuArray.push(cohabitingLtfu)
                    marriageLtfuArray.push(unavailableLtfu)
                    marriageLtfuArray.push(polygamousLtfu)
                    marriageLtfuArray.push(unapplicableLtfu)

                    Highcharts.chart('container', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Appointments by Age'
                        },
                        xAxis: {
                            categories: ['0-9', '10-14', '15-19', '20-24', '25+']
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Appointments Count'
                            },
                            stackLabels: {
                                enabled: true,
                                style: {
                                    fontWeight: 'bold',
                                    color: ( // theme
                                        Highcharts.defaultOptions.title.style &&
                                        Highcharts.defaultOptions.title.style.color
                                    ) || 'gray'
                                }
                            }
                        },
                        // legend: {
                        //     align: 'right',
                        //     x: -30,
                        //     verticalAlign: 'top',
                        //     y: 25,
                        //     floating: true,
                        //     backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                        //     borderColor: '#CCC',
                        //     borderWidth: 1,
                        //     shadow: false
                        // },
                        tooltip: {
                            formatter: function() {
                                return '<b>' + this.x + '</b><br/>' +
                                    this.series.name + ': ' + this.y + '<br/>' +
                                    'Sum of all appointment categories ' + ': ' + this.point.stackTotal;
                            }
                        },
                        // tooltip: {
                        //     headerFormat: '<b>{point.x}</b><br/>',
                        //     pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                        // },
                        plotOptions: {
                            column: {
                                stacking: 'normal',
                                // dataLabels: {
                                //     enabled: false
                                // }
                            }
                        },
                        series: [{
                                name: 'Honoured Appointments',
                                data: keptArray
                            }, {
                                name: 'Active Defaulters',
                                data: defaultedArray
                            }, {
                                name: 'Active Missed',
                                data: missedArray
                            },
                            {
                                name: 'Active LTFUs',
                                data: ltfuArray
                            }
                        ]
                    });

                    Highcharts.chart('marriage', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Appointments by Marital Status'
                        },
                        xAxis: {
                            categories: ['Single', 'Married Monogamous', 'Divorced', 'Widowed', 'Cohabiting', 'Unavailable', 'Married Polygamous', 'Not Applicable']
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Appointments Count'
                            },
                            stackLabels: {
                                enabled: true,
                                style: {
                                    fontWeight: 'bold',
                                    color: ( // theme
                                        Highcharts.defaultOptions.title.style &&
                                        Highcharts.defaultOptions.title.style.color
                                    ) || 'gray'
                                }
                            }
                        },
                        tooltip: {
                            formatter: function() {
                                return '<b>' + this.x + '</b><br/>' +
                                    this.series.name + ': ' + this.y + '<br/>' +
                                    'Sum of all appointment categories: ' + this.point.stackTotal;
                            }
                        },
                        plotOptions: {
                            column: {
                                stacking: 'normal',
                            }
                        },
                        series: [{
                                name: 'Honoured Appointments',
                                data: marriageKeptArray
                            }, {
                                name: 'Active Defaulters',
                                data: marriageDefaultedArray
                            }, {
                                name: 'Active Missed',
                                data: marriageMissedArray
                            },
                            {
                                name: 'Active LTFUs',
                                data: marriageLtfuArray
                            }
                        ]
                    });

                }

            });
        }

        dTbles("#filter_tablecharts_dashboard").click(function() {
            var access_level = $('#access_level').val();
            var partner_id = $('#partner_id').val();
            var facility_id = $('#facility_id').val();
            var partner = dTbles(".filter_partner").val();
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            if (access_level == 'Partner') {
                partner = partner_id;
            }
            if (access_level == 'Facility') {
                facility = facility_id;
            }
            var selected_partner = "";
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            if (partner != "") {
                selected_partner = "For Partner: " + dTbles(".filter_partner option:selected").text() +
                    "";
            } else {
                if (access_level == 'Partner') {
                    selected_partner = "For Partner: " + access_level + "";
                }
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() + " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var tokenizer = dTbles(".tokenizer").val();
            filter_tablecharts_dashboard(partner, county, sub_county, facility,
                description_one, tokenizer);
        });

        function filter_tablecharts_dashboard(partner, county, sub_county, facility,
            description_one, tokenizer) {
            var final_description;
            if (description_one == undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ';
            }
            let url =
                "<?php echo base_url(); ?>Home/filter_tablecharts_dashboard/";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    partner: partner,
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    tokenizer: tokenizer
                },
                success: function(results) {
                    result = JSON.parse(results)
                    //console.log(result)
                    $("#container").empty();
                    $("#marriage").empty();
                    $("#gender").empty();
                    $("#art").empty();
                    parseCondition = result.condition_records;
                    parseGender = result.gender_records;
                    parseMarriage = result.marriage_records;
                    parseData = result.data;


                    var conditionReg = [];
                    var conditionConsent = [];
                    var conditionPercent = [];

                    const art = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.art), 0)
                    const on_care = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.on_care), 0)
                    const pre_art = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.pre_art), 0)
                    const no_condition = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.no_condition), 0)

                    const art_consent = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.art_consent), 0)
                    const on_care_consent = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.on_care_consent), 0)
                    const pre_art_consent = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.pre_art_consent), 0)
                    const no_condition_consent = parseCondition.reduce((total, parseC_) => total + parseInt(parseC_.not_condition_consent), 0)

                    conditionPercent.push((art_consent / art) * 100);
                    conditionPercent.push((on_care_consent / on_care) * 100);
                    conditionPercent.push((pre_art_consent / pre_art) * 100);
                    conditionPercent.push((no_condition_consent / no_condition) * 100);

                    conditionReg.push(art);
                    conditionReg.push(on_care);
                    conditionReg.push(pre_art);
                    conditionReg.push(no_condition);

                    conditionConsent.push(art_consent);
                    conditionConsent.push(on_care_consent);
                    conditionConsent.push(pre_art_consent);
                    conditionConsent.push(no_condition_consent);


                    var genderReg = [];
                    var genderConsent = [];
                    var genderPercent = [];

                    const female = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.female), 0)
                    const male = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.male), 0)
                    const trans_gender = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.trans_gender), 0)
                    const unspecified = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.not_specified_gender), 0)

                    const female_consent = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.female_consent), 0)
                    const male_consent = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.male_consent), 0)
                    const trans_gender_consent = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.trans_gender_consent), 0)
                    const unspecified_consent = parseGender.reduce((total, parseG_) => total + parseInt(parseG_.not_specified_gender_consent), 0)

                    genderPercent.push((female_consent / female) * 100);
                    genderPercent.push((male_consent / male) * 100);
                    genderPercent.push((trans_gender_consent / trans_gender) * 100);
                    genderPercent.push((unspecified_consent / unspecified) * 100);

                    genderReg.push(female);
                    genderReg.push(male);
                    genderReg.push(trans_gender);
                    genderReg.push(unspecified);

                    genderConsent.push(female_consent);
                    genderConsent.push(male_consent);
                    genderConsent.push(trans_gender_consent);
                    genderConsent.push(unspecified_consent);


                    var marriedConsented = [];
                    var marriedRegistered = [];
                    var marriedPercent = [];

                    const single = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.single), 0)
                    const monogamous = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.married_monogamous), 0)
                    const divorced = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.divorced), 0)
                    const widowed = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.widowed), 0)
                    const cohabiting = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.cohabiting), 0)
                    const unavailable = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.unavailable), 0)
                    const polygamous = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.maried_polygamous), 0)

                    const singleConsent = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.single_consent), 0)
                    const monogamousConsent = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.married_monogamous_consent), 0)
                    const divorcedConsent = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.divorced_consented), 0)
                    const widowedConsent = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.widowed_consented), 0)
                    const cohabitingConsent = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.cohabiting_consented), 0)
                    const unavailableConsent = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.unavailable_consented), 0)
                    const pologamousConsent = parseMarriage.reduce((total, parseM_) => total + parseInt(parseM_.married_polygomous_consented), 0)



                    marriedPercent.push((singleConsent / single) * 100);
                    marriedPercent.push((monogamousConsent / monogamous) * 100);
                    marriedPercent.push((divorcedConsent / divorced) * 100);
                    marriedPercent.push((widowedConsent / widowed) * 100);
                    marriedPercent.push((cohabitingConsent / cohabiting) * 100);
                    marriedPercent.push((unavailableConsent / unavailable) * 100);
                    marriedPercent.push((pologamousConsent / polygamous) * 100);


                    marriedRegistered.push(single);
                    marriedRegistered.push(monogamous);
                    marriedRegistered.push(divorced);
                    marriedRegistered.push(widowed);
                    marriedRegistered.push(cohabiting);
                    marriedRegistered.push(unavailable);
                    marriedRegistered.push(polygamous);

                    marriedConsented.push(singleConsent);
                    marriedConsented.push(monogamousConsent);
                    marriedConsented.push(divorcedConsent);
                    marriedConsented.push(widowedConsent);
                    marriedConsented.push(cohabitingConsent);
                    marriedConsented.push(unavailableConsent);
                    marriedConsented.push(pologamousConsent);

                    var consentedArray = [];
                    var registeredArray = [];
                    var percentageArray = [];

                    //we use the reduce function to sum total registered

                    const toNine = parseData.reduce((total, parse_) => total + parseInt(parse_.ToNineregistered), 0)
                    const toFourteenReg = parseData.reduce((total, parse_) => total + parseInt(parse_.ToFourteenregistered), 0)
                    const toNineteenReg = parseData.reduce((total, parse_) => total + parseInt(parse_.ToNineteenregistered), 0)
                    const toTwenFourReg = parseData.reduce((total, parse_) => total + parseInt(parse_.ToTwentyFourregistered), 0)
                    const toTwenFiveReg = parseData.reduce((total, parse_) => total + parseInt(parse_.Overtwentyfiveregistered), 0)

                    //here we do the same to sum consented values

                    const toNineConsent = parseData.reduce((total, parse_) => total + parseInt(parse_.ToNineconsented), 0)
                    const toFourteenConsent = parseData.reduce((total, parse_) => total + parseInt(parse_.ToFourteenconsented), 0)
                    const toNineteenConsent = parseData.reduce((total, parse_) => total + parseInt(parse_.ToNineteenconsented), 0)
                    const toTwentyFourConsent = parseData.reduce((total, parse_) => total + parseInt(parse_.ToTwentyFourconsented), 0)
                    const toTwentyFiveConsent = parseData.reduce((total, parse_) => total + parseInt(parse_.TwentyFiveconsented), 0)

                    registeredArray.push(toNine);
                    registeredArray.push(toFourteenReg);
                    registeredArray.push(toNineteenReg);
                    registeredArray.push(toTwenFourReg);
                    registeredArray.push(toTwenFiveReg);
                    //push to consent array

                    consentedArray.push(toNineConsent);
                    consentedArray.push(toFourteenConsent);
                    consentedArray.push(toNineteenConsent);
                    consentedArray.push(toTwentyFourConsent);
                    consentedArray.push(toTwentyFiveConsent);

                    percentageArray.push((toNineConsent / toNineReg) * 100)
                    percentageArray.push((toFourteenConsent / toFourteenReg) * 100)
                    percentageArray.push((toNineteenConsent / toNineteenReg) * 100)
                    percentageArray.push((toTwentyFourConsent / toTwenFourReg) * 100)
                    percentageArray.push((toTwentyFiveConsent / toTwenFiveReg) * 100)


                    Highcharts.drawTable = function() {


                        // user options
                        var tableTop = 55,
                            colWidth = 150,
                            tableLeft = 40,
                            rowHeight = 40,
                            cellPadding = 4,
                            valueDecimals = 0;
                        // valueSuffix = ' °C';

                        // internal variables
                        var chart = this,
                            series = chart.series,
                            renderer = chart.renderer,
                            cellLeft = tableLeft;

                        // draw category labels
                        $.each(chart.xAxis[0].categories, function(i, name) {
                            renderer.text(
                                    name,
                                    cellLeft + cellPadding,
                                    tableTop + (i + 2) * rowHeight - cellPadding
                                )
                                .css({
                                    fontWeight: 'bold'
                                })
                                .add();
                        });

                        $.each(series, function(i, serie) {
                            cellLeft += colWidth;

                            // Apply the cell text
                            renderer.text(
                                    serie.name,
                                    cellLeft - cellPadding + colWidth,
                                    tableTop + rowHeight - cellPadding
                                )
                                .attr({
                                    align: 'right'
                                })
                                .css({
                                    fontWeight: 'bold'
                                })
                                .add();

                            $.each(serie.data, function(row, point) {

                                // Apply the cell text
                                renderer.text(
                                        Highcharts.numberFormat(point.y, valueDecimals),
                                        cellLeft + colWidth - cellPadding,
                                        tableTop + (row + 2) * rowHeight - cellPadding
                                    )
                                    .attr({
                                        align: 'right'
                                    })
                                    .add();

                                // horizontal lines
                                if (row == 0) {
                                    Highcharts.tableLine( // top
                                        renderer,
                                        tableLeft,
                                        tableTop + cellPadding,
                                        cellLeft + colWidth,
                                        tableTop + cellPadding
                                    );
                                    Highcharts.tableLine( // bottom
                                        renderer,
                                        tableLeft,
                                        tableTop + (serie.data.length + 1) * rowHeight + cellPadding,
                                        cellLeft + colWidth,
                                        tableTop + (serie.data.length + 1) * rowHeight + cellPadding
                                    );
                                }
                                // horizontal line
                                Highcharts.tableLine(
                                    renderer,
                                    tableLeft,
                                    tableTop + row * rowHeight + rowHeight + cellPadding,
                                    cellLeft + colWidth,
                                    tableTop + row * rowHeight + rowHeight + cellPadding
                                );

                            });

                            // vertical lines        
                            if (i == 0) { // left table border  
                                Highcharts.tableLine(
                                    renderer,
                                    tableLeft,
                                    tableTop + cellPadding,
                                    tableLeft,
                                    tableTop + (serie.data.length + 1) * rowHeight + cellPadding
                                );
                            }

                            Highcharts.tableLine(
                                renderer,
                                cellLeft,
                                tableTop + cellPadding,
                                cellLeft,
                                tableTop + (serie.data.length + 1) * rowHeight + cellPadding
                            );

                            if (i == series.length - 1) { // right table border    

                                Highcharts.tableLine(
                                    renderer,
                                    cellLeft + colWidth,
                                    tableTop + cellPadding,
                                    cellLeft + colWidth,
                                    tableTop + (serie.data.length + 1) * rowHeight + cellPadding
                                );
                            }

                        });


                    };
                    Highcharts.tableLine = function(renderer, x1, y1, x2, y2) {
                        renderer.path(['M', x1, y1, 'L', x2, y2])
                            .attr({
                                'stroke': 'silver',
                                'stroke-width': 1
                            })
                            .add();
                    }
                    window.chart = new Highcharts.Chart({

                        chart: {
                            renderTo: 'container',
                            events: {
                                load: Highcharts.drawTable
                            },
                            borderWidth: 2
                        },

                        title: {
                            text: 'Client Registration by Age Group'
                        },

                        xAxis: {
                            visible: false,
                            categories: ['0-9', '10-14', '15-19', '20-24', '25+']
                        },

                        yAxis: {
                            visible: false
                        },

                        legend: {
                            enabled: false
                        },
                        plotOptions: {
                            series: {
                                visible: false
                            }
                        },

                        series: [{
                            name: 'Registered',
                            data: registeredArray
                        }, {
                            name: 'Consented',
                            data: consentedArray
                        }, {
                            name: 'Percentage',
                            data: percentageArray
                        }]
                    });
                    window.chart = new Highcharts.Chart({

                        chart: {
                            renderTo: 'marriage',
                            events: {
                                load: Highcharts.drawTable
                            },
                            borderWidth: 2
                        },

                        title: {
                            text: 'Client Registration by Marital Status'
                        },

                        xAxis: {
                            visible: false,
                            categories: ['Single', 'Married Monogamous', 'Divorced', 'Widowed', 'Cohabiting', 'Unavailable', 'Married Polygamous']
                        },

                        yAxis: {
                            visible: false
                        },

                        legend: {
                            enabled: false
                        },
                        plotOptions: {
                            series: {
                                visible: false
                            }
                        },

                        series: [{
                            name: 'Registered',
                            data: marriedRegistered
                        }, {
                            name: 'Consented',
                            data: marriedConsented
                        }, {
                            name: 'Percentage',
                            data: marriedPercent
                        }]
                    });
                    window.chart = new Highcharts.Chart({

                        chart: {
                            renderTo: 'gender',
                            events: {
                                load: Highcharts.drawTable
                            },
                            borderWidth: 2
                        },

                        title: {
                            text: 'Client Registration by Gender'
                        },

                        xAxis: {
                            visible: false,
                            categories: ['Female', 'Male', 'Trans Gender', 'Unspecified']
                        },

                        yAxis: {
                            visible: false
                        },

                        legend: {
                            enabled: false
                        },
                        plotOptions: {
                            series: {
                                visible: false
                            }
                        },

                        series: [{
                            name: 'Registered',
                            data: genderReg
                        }, {
                            name: 'Consented',
                            data: genderConsent
                        }, {
                            name: 'Percentage',
                            data: genderPercent
                        }]
                    });
                    window.chart = new Highcharts.Chart({

                        chart: {
                            renderTo: 'art',
                            events: {
                                load: Highcharts.drawTable
                            },
                            borderWidth: 2
                        },

                        title: {
                            text: 'Client Registration by Treatment Category'
                        },

                        xAxis: {
                            visible: false,
                            categories: ['Art', 'On Care', 'Pre-Art', 'No Condition']
                        },

                        yAxis: {
                            visible: false
                        },

                        legend: {
                            enabled: false
                        },
                        plotOptions: {
                            series: {
                                visible: false
                            }
                        },

                        series: [{
                            name: 'Registered',
                            data: conditionReg
                        }, {
                            name: 'Consented',
                            data: conditionConsent
                        }, {
                            name: 'Percentage',
                            data: conditionPercent
                        }]
                    });

                }
            });

        }

        dTbles(".filter_client_extract").click(function() {
            var access_level = $('#access_level').val();
            var partner_id = $('#partner_id').val();
            var facility_id = $('#facility_id').val();
            console.log(partner_id);
            var partner = dTbles(".filter_partner").val();
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            var date_from = dTbles(".date_from").val();
            var date_to = dTbles(".date_to").val();
            if (access_level == 'Partner') {
                partner = partner_id;
            }
            if (access_level == 'Facility') {
                facility = facility_id;
            }
            var selected_partner = "";
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_date_from = "";
            var selected_date_to = "";
            if (date_from.length > 0) {
                selected_date_from = "From : " + dTbles(".date_from").val() + " - ";
            }
            if (date_to.length > 0) {
                selected_date_to = "To : " + dTbles(".date_to").val();
            }
            if (partner != "") {
                selected_partner = "For Partner: " + dTbles(".filter_partner option:selected")
                    .text() +
                    "";
            } else {
                if (access_level == 'Partner') {
                    selected_partner = "For Partner: " + access_level + "";
                }
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() +
                    " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            var tokenizer = dTbles(".tokenizer").val();
            generate_client_report(county, sub_county, facility, date_from, date_to,
                description_one,
                description_two, tokenizer);
        });

        function generate_client_report(county, sub_county, facility, date_from, date_to, description_one,
            description_two, tokenizer) {
            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }
            var processed_json = new Array();
            dTbles(".gender_report_div").empty();
            dTbles.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_client_reports/",
                dataType: 'JSON',
                data: {
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    date_from: date_from,
                    date_to: date_to,
                    tokenizer: tokenizer
                },
                success: function(data) {
                    dTbles(".client_reports_div").empty();
                    dTbles('.client_listing').DataTable().clear();
                    dTbles('.client_listing').DataTable().destroy();
                    var table = '  <table class=" display nowrap table table-hover table-striped table-bordered client_listing " cellspacing="0" width="100%">\n\
            <thead> <tr> <th>CCC No</th> <th>Gender  :</th> <th>Group : </th><th>Marital Status : </th> <th>Create At : </th>\n\
            <th>Month Year: </th> <th>Language </th> <th>Text Time : </th><th>Enrollment Date : </th> <th>ART Date : </th><th>Partner : </th> <th>County  </th>\n\
            <th>Sub County: </th> <th>MFL Code </th> <th>Facility  : </th><th>SMS Enable : </th> <th>Consent Date : </th><th>Wellness Enable : </th>   </tr> </thead>  \n\
            <tbody id="results_tbody" class="results_tbody"> </tbody> </table> ';
                    dTbles('.client_reports_div').append(table);
                    if (data === "Too much data") {
                        dTbles(".client_reports_div").empty();
                        sweetAlert("Info",
                            "The data you're trying to load is too much , we will send it to you through your personal e-mail ",
                            'info');
                    } else {
                        dTbles.each(data, function(i, value) {
                            var clinic_number = value.clinic_number;
                            var gender = value.gender;
                            var group_name = value.group_name;
                            var marital = value.marital;
                            var created_at = value.created_at;
                            var month_year = value.month_year;
                            var LANGUAGE = value.LANGUAGE;
                            var txt_time = value.txt_time;
                            var enrollment_date = value.enrollment_date;
                            var art_date = value.art_date;
                            var partner_id = value.partner_id;
                            var partner_name = value.partner_name;
                            var county_name = value.county_name;
                            var sub_county = value.sub_county;
                            var mfl_code = value.mfl_code;
                            var facility_name = value.facility_name;
                            var smsenable = value.smsenable;
                            var consent_date = value.consent_date;
                            var motivational_enable = value.motivational_enable;
                            var wellness_enable = value.wellness_enable;
                            var tr_results = "<tr>\n\
            <td>" + clinic_number + "</td>\n\
            <td>" + gender + "</td>\n\
            <td>" + group_name + "</td>\n\
            <td>" + marital + "</td>\n\
            <td>" + created_at + "</td>\n\
            <td>" + month_year + "</td>\n\
            <td>" + LANGUAGE + "</td>\n\
            <td>" + txt_time + "</td>\n\
            <td>" + enrollment_date + "</td>\n\
            <td>" + art_date + "</td>\n\
            <td>" + partner_name + "</td>\n\
            <td>" + county_name + "</td>\n\
            <td>" + sub_county + "</td>\n\
            <td>" + mfl_code + "</td>\n\
            <td>" + facility_name + "</td>\n\
            <td>" + smsenable + "</td>\n\
            <td>" + consent_date + "</td>\n\
            <td>" + motivational_enable + "</td>\n\
            <td>" + wellness_enable + "</td>\n\
            </tr>";
                            dTbles(".results_tbody").append(tr_results);
                        });
                        dTbles(".client_reports_div").show();
                        dTbles('.client_listing').DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            "lengthMenu": [
                                [5, 10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            buttons: [
                                dTbles.extend(true, {}, {
                                    extend: 'copyHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'excelHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'pdfHtml5'
                                })
                            ]
                        });
                    }
                }
            });
        }
        //TRACING OUTCOME STARTS HERE ...
        dTbles(".filter_tracing_outcome").click(function() {
            var access_level = $('#access_level').val();
            var partner_id = $('#partner_id').val();
            var facility_id = $('#facility_id').val();
            var sub_county_id = $('#sub_county_id').val();
            var partner = dTbles(".filter_partner").val();
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            var date_from = dTbles(".date_from").val();
            var date_to = dTbles(".date_to").val();
            console.log('data loads');
            if (access_level == 'Partner') {
                partner = partner_id;
            }
            if (access_level == 'Facility') {
                facility = facility_id;
            }
            if (access_level == 'Sub County') {
                sub_county = sub_county_id;
            }
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_date_from = "";
            var selected_date_to = "";
            if (date_from.length > 0) {
                selected_date_from = "From : " + dTbles(".date_from").val() + " - ";
            }
            if (date_to.length > 0) {
                selected_date_to = "To : " + dTbles(".date_to").val();
            }
            if (partner != "") {
                selected_partner = "For Partner: " + dTbles(".filter_partner option:selected")
                    .text() +
                    "";
            } else {
                if (access_level == 'Partner') {
                    selected_partner = "For Partner: " + access_level + "";
                }
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() +
                    " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            var tokenizer = dTbles(".tokenizer").val();
            generate_tracing_outcome(county, sub_county, facility, date_from, date_to,
                description_one,
                description_two, tokenizer);
        });
        //TRACING OUTCOME FUNCTION
        function generate_tracing_outcome(county, sub_county, facility, date_from, date_to, description_one,
            description_two, tokenizer) {
            console.log("in generate");
            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }
            var processed_json = new Array();
            dTbles(".gender_report_div").empty();
            dTbles.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/TracingOutcomefilter/",
                dataType: 'JSON',
                data: {
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    date_from: date_from,
                    date_to: date_to,
                    tokenizer: tokenizer
                },
                success: function(data) {
                    console.log(data);
                    dTbles(".client_reports_div").empty();
                    dTbles('.client_listing').DataTable().clear();
                    dTbles('.client_listing').DataTable().destroy();
                    var table = '  <table class=" display nowrap table table-hover table-striped table-bordered client_listing " cellspacing="0" width="100%">\n\
        <thead> <tr>  <th>Clinic Number  :</th> <th>Age  :</th> <th>Facility  :</th> <th>Gender  :</th> <th>Sub_County  :</th> <th>File No : </th><th>Appointment Date : </th> <th>Date Came : </th> <th>Tracer Name : </th>\n\
        <th>Days Defaulted: </th> <th>ART Start Date: </th> <th>Tracing Date </th> <th>Outcome : </th><th>Final Outcome : </th> <th>Other Outcome : </th> </tr> </thead>  \n\
        <tbody id="results_tbody" class="results_tbody"> </tbody> </table> ';
                    dTbles('.client_reports_div').append(table);
                    // if (data === "Too much data") {
                    //     dTbles(".client_reports_div").empty();
                    //     sweetAlert("Info", "The data you're trying to load is too much , we will send it to you through your personal e-mail ", 'info');
                    // } else {
                    dTbles.each(data, function(i, value) {
                        var clinic_number = value.UPN;
                        var age = value.Age;
                        var facility = value.Facility;
                        var gender = value.Gender;
                        var subcounty = value.Sub_County;
                        var file_no = value.File_Number;
                        var appmnt_date = value.Appointment_Date;
                        var date_attened = value.Date_Came;
                        var tracer = value.Tracer;
                        var days_defaulted = value.Days_Defaulted;
                        var art_start_date = value.Art_Start_Date;
                        var tracing_date = value.Tracing_Date;
                        var outcome = value.Outcome;
                        var final_outcome = value.Final_Outcome;
                        var other_outcome = value.Other_Outcome;
                        var tr_results = "<tr>\n\
        <td>" + clinic_number + "</td>\n\
        <td>" + age + "</td>\n\
        <td>" + facility + "</td>\n\
        <td>" + gender + "</td>\n\
        <td>" + subcounty + "</td>\n\
        <td>" + file_no + "</td>\n\
        <td>" + appmnt_date + "</td>\n\
        <td>" + date_attened + "</td>\n\
        <td>" + tracer + "</td>\n\
        <td>" + days_defaulted + "</td>\n\
        <td>" + art_start_date + "</td>\n\
        <td>" + tracing_date + "</td>\n\
        <td>" + outcome + "</td>\n\
        <td>" + final_outcome + "</td>\n\
        <td>" + other_outcome + "</td>\n\
        </tr>";
                        dTbles(".results_tbody").append(tr_results);
                    });
                    dTbles(".client_reports_div").show();
                    dTbles('.client_listing').DataTable({
                        dom: 'Bfrtip',
                        responsive: true,
                        "lengthMenu": [
                            [5, 10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        buttons: [
                            dTbles.extend(true, {}, {
                                extend: 'copyHtml5'
                            }),
                            dTbles.extend(true, {}, {
                                extend: 'excelHtml5'
                            }),
                            dTbles.extend(true, {}, {
                                extend: 'pdfHtml5'
                            })
                        ]
                    });
                    //}
                }
            });
        }
        dTbles(".filter_appointment_extract").click(function() {
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            var date_from = dTbles(".date_from").val();
            var date_to = dTbles(".date_to").val();
            var partner = dTbles(".filter_partner").val();
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_date_from = "";
            var selected_date_to = "";
            if (date_from.length > 0) {
                selected_date_from = "From : " + dTbles(".date_from").val() + " - ";
            }
            if (date_to.length > 0) {
                selected_date_to = "To : " + dTbles(".date_to").val();
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() +
                    " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            var tokenizer = dTbles(".tokenizer").val();
            generate_appointment_report(partner, county, sub_county, facility, date_from, date_to,
                description_one, description_two, tokenizer);
        });

        function generate_appointment_report(partner, county, sub_county, facility, date_from, date_to,
            description_one, description_two, tokenizer) {
            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }
            var processed_json = new Array();
            dTbles(".gender_report_div").empty();
            dTbles.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_appointment_reports/",
                dataType: 'JSON',
                data: {
                    partner: partner,
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    date_from: date_from,
                    date_to: date_to,
                    tokenizer: tokenizer
                },
                success: function(data) {
                    console.log(data);
                    if (data === "Too much data") {
                        dTbles(".appointment_reports_div").empty();
                        sweetAlert("Info",
                            "The data you're trying to load is too heavy to be viewed on browser , we will send it to you through your personal e-mail ",
                            'info');
                    } else {
                        dTbles(".appointment_reports_div").empty();
                        dTbles('.appointment_listing').DataTable().clear();
                        dTbles('.appointment_listing').DataTable().destroy();
                        var table = '  <table class=" display nowrap table table-hover table-striped table-bordered appointment_listing " cellspacing="0" width="100%">\n\
             <thead> <tr> <th>CCC No</th> <th>Gender  :</th> <th>Group : </th><th>Marital Status : </th> <th>Appointment Type : </th>\n\
            <th>Appointment Month Year: </th> <th>Appointment Status </th> <th>Partner : </th> <th>County  </th>\n\
            <th>Sub County: </th> <th>MFL Code </th> <th>Facility  : </th>   </tr> </thead>  \n\
               <tbody id="results_tbody" class="results_tbody"> </tbody> </table> ';
                        dTbles('.appointment_reports_div').append(table);
                        dTbles.each(data, function(i, value) {
                            var clinic_number = value.clinic_number;
                            var gender = value.gender;
                            var group_name = value.group_name;
                            var marital = value.marital;
                            var created_at = value.created_at;
                            var month_year = value.appointment_month_year;
                            var LANGUAGE = value.app_status;
                            var partner_id = value.partner_id;
                            var partner_name = value.partner_name;
                            var county_name = value.county_name;
                            var sub_county = value.sub_county;
                            var mfl_code = value.mfl_code;
                            var facility_name = value.facility_name;
                            var tr_results = "<tr>\n\
                    <td>" + clinic_number + "</td>\n\
                    <td>" + gender + "</td>\n\
                    <td>" + group_name + "</td>\n\
                    <td>" + marital + "</td>\n\
                    <td>" + created_at + "</td>\n\
                    <td>" + month_year + "</td>\n\
                    <td>" + LANGUAGE + "</td>\n\
                    <td>" + partner_name + "</td>\n\
                    <td>" + county_name + "</td>\n\
                    <td>" + sub_county + "</td>\n\
                    <td>" + mfl_code + "</td>\n\
                    <td>" + facility_name + "</td>\n\
                    </tr>";
                            dTbles(".results_tbody").append(tr_results);
                        });
                        dTbles(".appointment_reports_div").show();
                        var table = dTbles('.appointment_listing').DataTable({
                            dom: 'Bfrtip',
                            responsive: true,
                            "lengthMenu": [
                                [5, 10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            buttons: [
                                dTbles.extend(true, {}, {
                                    extend: 'copyHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'excelHtml5'
                                }),
                                dTbles.extend(true, {}, {
                                    extend: 'pdfHtml5'
                                })
                            ]
                        });
                    }
                }
            });
        }
        dTbles(".filter_message_extract").click(function() {
            var access_level = $('#access_level').val();
            var partner_id = $('#partner_id').val();
            var facility_id = $('#facility_id').val();
            var sub_county_id = $('#sub_county_id').val();
            var partner = dTbles(".filter_partner").val();
            var county = dTbles(".filter_county").val();
            var sub_county = dTbles(".filter_sub_county").val();
            var facility = dTbles(".filter_facility").val();
            var date_from = dTbles(".date_from").val();
            var date_to = dTbles(".date_to").val();
            if (access_level == 'Partner') {
                partner = partner_id;
            }
            if (access_level == 'Facility') {
                facility = facility_id;
            }
            if (access_level == 'Sub County') {
                sub_county = sub_county_id;
            }
            var selected_county = "";
            var selected_sub_county = "";
            var selected_facility = "";
            var selected_date_from = "";
            var selected_date_to = "";
            if (date_from.length > 0) {
                selected_date_from = "From : " + dTbles(".date_from").val() + " - ";
            }
            if (date_to.length > 0) {
                selected_date_to = "To : " + dTbles(".date_to").val();
            }
            if (partner != "") {
                selected_partner = "For Partner: " + dTbles(".filter_partner option:selected")
                    .text() +
                    "";
            } else {
                if (access_level == 'Partner') {
                    selected_partner = "For Partner: " + access_level + "";
                }
            }
            if (county != "") {
                selected_county = "For " + dTbles(".filter_county option:selected").text() +
                    " County ";
            }
            if (sub_county != "") {
                selected_sub_county = ", " + dTbles(".filter_sub_county option:selected").text() +
                    "Sub County ";
            }
            if (facility != "") {
                selected_facility = "," + dTbles(".filter_facility option:selected").text() + " ";
            }
            var description_one = "" + selected_county + " " + selected_sub_county + "  " +
                selected_facility + " ";
            var description_two = " " + selected_date_from + " " + selected_date_to + " ";
            var tokenizer = dTbles(".tokenizer").val();
            generate_message_report(county, sub_county, facility, date_from, date_to,
                description_one,
                description_two, tokenizer);
        });

        function generate_message_report(county, sub_county, facility, date_from, date_to, description_one,
            description_two, tokenizer) {
            var final_description;
            if (description_one == undefined && description_two != undefined) {
                final_description = " " + description_two;
            } else if (description_one != undefined && description_two == undefined) {
                final_description = " " + description_one;
            } else if (description_one == undefined && description_two == undefined) {
                final_description = " ";
            } else {
                final_description = description_one + ' </br> ' + description_two;
            }
            var processed_json = new Array();
            dTbles(".gender_report_div").empty();
            dTbles.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Reports/get_message_reports/",
                dataType: 'JSON',
                data: {
                    county: county,
                    sub_county: sub_county,
                    facility: facility,
                    date_from: date_from,
                    date_to: date_to,
                    tokenizer: tokenizer
                },
                success: function(data) {
                    dTbles(".message_reports_div").empty();
                    dTbles('.message_listing').DataTable().clear();
                    dTbles('.message_listing').DataTable().destroy();
                    var table = '  <table class=" display nowrap table table-hover table-striped table-bordered message_listing " cellspacing="0" width="100%">\n\
             <thead> <tr> <th>CCC No</th> <th>Gender  :</th> <th>Group : </th><th>Marital Status : </th> <th>Text Time : </th> <th>Langauge Type : </th> <th>Mesage Type : </th>\n\
            <th>Message Month Year: </th> <th>Message </th> <th>Partner : </th> <th>County  </th>\n\
            <th>Sub County: </th> <th>MFL Code </th> <th>Facility  : </th>   </tr> </thead>  \n\
               <tbody id="results_tbody" class="results_tbody"> </tbody> </table> ';
                    dTbles('.message_reports_div').append(table);
                    dTbles.each(data, function(i, value) {
                        var clinic_number = value.clinic_number;
                        var gender = value.gender;
                        var group_name = value.group_name;
                        var marital = value.marital;
                        var created_at = value.created_at;
                        var month_year = value.month_year;
                        var LANGUAGE = value.LANGUAGE;
                        var txt_time = value.txt_time;
                        var message_type = value.message_type;
                        var message = value.msg;
                        var partner_name = value.partner_name;
                        var county_name = value.county_name;
                        var sub_county = value.sub_county;
                        var mfl_code = value.mfl_code;
                        var facility_name = value.facility_name;
                        var tr_results = "<tr>\n\
                    <td>" + clinic_number + "</td>\n\
                    <td>" + gender + "</td>\n\
                    <td>" + group_name + "</td>\n\
                    <td>" + marital + "</td>\n\
                    <td>" + txt_time + "</td>\n\
                    <td>" + LANGUAGE + "</td>\n\
                    <td>" + message_type + "</td>\n\
                    <td>" + month_year + "</td>\n\
                    <td>" + message + "</td>\n\
                    <td>" + partner_name + "</td>\n\
                    <td>" + county_name + "</td>\n\
                    <td>" + sub_county + "</td>\n\
                    <td>" + mfl_code + "</td>\n\
                    <td>" + facility_name + "</td>\n\
                    </tr>";
                        dTbles(".results_tbody").append(tr_results);
                    });
                    dTbles(".message_reports_div").show();
                    var table = dTbles('.message_listing').DataTable({
                        dom: 'Bfrtip',
                        responsive: true,
                        "lengthMenu": [
                            [5, 10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        buttons: [
                            dTbles.extend(true, {}, {
                                extend: 'copyHtml5'
                            }),
                            dTbles.extend(true, {}, {
                                extend: 'excelHtml5'
                            }),
                            dTbles.extend(true, {}, {
                                extend: 'pdfHtml5'
                            })
                        ]
                    });
                }
            });
        }
        dTbles(document).on('click', ".select_mfl_btn", function() {
            //get data
            var mfl_code = dTbles(this).closest('tr').find('input[name="hidden_mfl_code"]').val();
            var controller = "admin";
            var get_function = "get_facility_details";
            var success_alert = "Facility added successfully ... :) ";
            var error_alert = "An Error Ocurred";
            dTbles.ajax({
                type: "GET",
                async: true,
                url: "<?php echo base_url(); ?>admin/get_facility_details/" +
                    mfl_code,
                dataType: "JSON",
                success: function(response) {
                    dTbles.each(response, function(i, value) {
                        //dTbles(".add_facilty_div").hide();
                        // dTbles(".table_div").show();
                        dTbles(".facility_name").empty();
                        dTbles(".mfl_code").empty();
                        dTbles(".facility_type").empty();
                        dTbles(".facility_county").empty();
                        console.log("Facility Name : " + value.name +
                            "MFL Code : " + value.code +
                            "Facility Type : " +
                            value.facility_type + "Location : " + value
                            .owner);
                        dTbles(".facility_name").val(value.name);
                        dTbles(".mfl_code").val(value.code);
                        dTbles(".facility_type").val(value.facility_type);
                        dTbles(".facility_location").val(value.keph_level);
                        dTbles(".facility_county").val(value.regulatory_body);
                        dTbles(".add_facilty_div").show();
                        dTbles(".table_div").hide();
                    });
                    //                    $(".edit_div").show();
                    //                    $(".search_results_div").hide();
                    //                    $(".facility_search_div").hide();
                },
                error: function(data) {
                    sweetAlert("Oops...", "" + error_alert + "", "error");
                }
            });
        });
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    var dtp = jQuery.noConflict();
    dtp(document).ready(function() {
        dtp('#123').datepicker({
            format: "dd/mm/yyyy"
        });
    });
    var date_picker = jQuery.noConflict();
    var startD = new Date().setDate(new Date().getDate() + 7)
    console.log(startD)
    date_picker(function() {
        date_picker(".appointment_date").datepicker({
            format: 'dd/mm/yyyy',
            startDate: '-0d',
            autoclose: true
        });
        date_picker(".transfer_appointment_date").datepicker({
            format: 'dd/mm/yyyy',
            startDate: '-0d',
            autoclose: true
        });
        date_picker(".edit_appntmnt_date").datepicker({
            format: 'yyyy-mm-dd',
            minDate: startD,
            autoclose: true
        });
        date_picker(".transfer_date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        date_picker(".dob").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });
        date_picker(".transfer_dob").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });
        date_picker(".date").datepicker({
            format: 'dd/mm/yyyy'
        });
        date_picker(".date_from").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
        date_picker(".date_to").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
    });
</script>

<script type="text/javascript">
    var myVar;

    function showTime() {
        var d = new Date();
        var t = d.toLocaleTimeString();
        $("#clock").html("Current Time : " + d); // display time on the page
        $(".current_time").html(" " + d); // display time on the page
    }

    function stopFunction() {
        clearInterval(myVar); // stop the timer
    }
    $(document).ready(function() {
        setInterval(function() {}, 300000);
        setInterval(function() {
            window.location.reload(1);
        }, 3000000);
        setInterval('showTime()', 1000);

        function sender() {
            $(".sender_p").empty();
            $.ajax({
                url: "<?php echo base_url(); ?>chore/sender",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".sender_p").append("Sender is running");
                },
                error: function(errorThrown) {
                    $(".sender_p").append("Sender is not running");
                }
            });
        }

        function scheduler() {
            $(".scheduler_p").empty();
            $.ajax({
                url: "<?php echo base_url(); ?>chore/scheduler",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".scheduler_p").append("Scheduler is running");
                },
                error: function(errorThrown) {
                    $(".scheduler_p").append("Scheduler is not running");
                }
            });
        }

        function receiver() {
            $(".receiver_p").empty();
            $.ajax({
                url: "<?php echo base_url(); ?>chore/receiver",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".receiver_p").append("Receiver is running");
                },
                error: function(errorThrown) {
                    $(".receiver_p").append("Receiver is not running");
                }
            });
        }

        function trigger() {
            $(".trigger_p").empty();
            $.ajax({
                url: "<?php echo base_url(); ?>chore/trigger",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".trigger_p").append("Trigger is running");
                },
                error: function(errorThrown) {
                    $(".trigger_p").append("Trigger is not running");
                }
            });
        }

        function send_mail() {
            $(".send_mail_p").empty();
            $.ajax({
                url: "<?php echo base_url(); ?>chore/send_mail",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".send_mail_p").append("Send mail is running");
                },
                error: function(errorThrown) {
                    $(".send_mail_p").append("Send mail is not running");
                }
            });
        }

        function broadcast() {
            $(".broadcast_p").empty();
            $.ajax({
                url: "<?php echo base_url(); ?>chore/broadcast",
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $(".broadcast_p").append("Broadcast is running");
                },
                error: function(errorThrown) {
                    $(".broadcast_p").append("Broadcast is not running");
                }
            });
        }
    });
</script>

<div class="loading" id="loading">
    <div class="panel-bod">
        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>

    <!-- Place at bottom of page -->
</div>
<style type="text/css">
    /* Start by setting display:none to make this hidden.
Then we position it in relation to the viewport window
with position:fixed. Width, height, top and left speak
for themselves. Background we set to 80% white with
our animation centered, and no-repeating */
    .loader {
        display: inline;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;

        background: rgba(255, 255, 255, .8) 50% 50% no-repeat;
    }
</style>

<script type='text/javascript'>
    (function(d, t) {
        var bh = d.createElement(t),
            s = d.getElementsByTagName(t)[0];
        bh.type = 'text/javascript';
        bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=3epwqunezmhjfnd2iq0cea';
        s.parentNode.insertBefore(bh, s);
    })(document, 'script');

    function fetchJSON(url) {
        return fetch(url)
            .then(function(response) {
                return response.json();
            });
    }
</script>

</bodfy>

</html>