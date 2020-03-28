<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li class="breadcrumb-item active"><a
                        href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">
                        Clients Extract</a></li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->

        <?php echo $access_level ?>
        <div class="row">
            <form class="form-inline">
                <input type='hidden' id="access_level"
                    value="<?php echo $access_level; ?>" />
                <input type='hidden' id="partner_id"
                    value="<?php echo $partner_id; ?>" />
                <input type='hidden' id="facility_id"
                    value="<?php echo $facility_id; ?>" />

                <?php if ($access_level == 'Admin' || $access_level == 'Donor') {?>

                <select class="form-control filter_partner  input-rounded input-sm select2" name="filter_partner"
                    id="filter_partner">
                    <option value="">Please select Partner</option>
                    <?php
                        foreach ($filtered_partner as $value) {
                            ?>
                    <option
                        value="<?php echo $value->partner_id; ?>">
                        <?php echo $value->partner_name; ?>
                    </option>
                    <?php
                        }
                        ?>
                    <option></option>
                </select>
                <?php } ?>

                <?php if ($access_level == 'Admin' || $access_level == 'Donor' || $access_level == 'Partner') {?>

                <select class="form-control filter_county  input-rounded input-sm select2" name="filter_county"
                    id="filter_county">
                    <option value="">Please select County</option>
                    <?php
                        foreach ($filtered_county as $value) {
                            ?>
                    <option value="<?php echo $value->county_id; ?>">
                        <?php echo $value->county_name; ?>
                    </option>
                    <?php
                        }
                        ?>
                    <option></option>
                </select>

                <span class="filter_sub_county_wait" style="display: none;">Loading , Please Wait ...</span>
                <select class="form-control filter_sub_county input-rounded input-sm select2" name="filter_sub_county"
                    id="filter_sub_county">
                    <option value="">Please Select Sub County : </option>
                </select>

                <span class="filter_facility_wait" style="display: none;">Loading , Please Wait ...</span>

                <select class="form-control filter_facility input-rounded input-sm select2" name="filter_facility"
                    id="filter_facility">
                    <option value="">Please select Facility : </option>
                </select>

                <!-- <?php }?>

                <input type="text" name="date_from" id="date_from"
                    class="form-controL date_from input-rounded input-sm " placeholder="Date From : " />

                <input type="text" name="date_to" id="date_to" class="form-control date_to input-rounded input-sm "
                    placeholder="Date To : " /> -->

                <button class="btn btn-default filter_highcharts_dashboard btn-round  btn-small btn-primary  "
                    type="button" name="filter_highcharts_dashboard" id="filter_highcharts_dashboard"> <i
                        class="fa fa-filter"></i>
                    Filter</button>

            </form>

        </div>

        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey">
                                    <b><?php echo $target_active_clients; ?><br></b>
                                    Target Active Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey">
                                    <b><?php echo $total_clients; ?><br></b>
                                    No. of Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey">
                                    <b><?php echo $percentage_uptake; ?><br></b>
                                    % No. of Active Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey">
                                    <b><?php echo $consented_clients; ?><br></b>
                                    Consented Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey">
                                    <b><?php echo $future_appointments; ?><br></b>
                                    Future Appointments
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey">
                                    <b><?php echo $facilities; ?><br></b>
                                    No. of Facilities
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body row">
                        <div class="col-6" id="map"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PAge Content -->

        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2018 Ushauri - All rights reserved. Powered by <a href="https://mhealthkenya.org">mHealth
            Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->

<script type='text/javascript'>
    var data = '<?php echo json_encode($data); ?>';
    maps(data)
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
                minColor: '#EEEEFF',
                maxColor: '#000022',
                stops: [
                    [0, '#EFEFFF'],
                    [0.67, '#4444FF'],
                    [1, '#000022']
                ]
            },
            series: [{
                data: JSON.parse(data),
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
                    pointFormat: 'County: {point.properties.COUNTY}<br> Clients: {point.Clients} <br>'
                }
            }]
        });
    }

    function fetchJSON(url) {
        return fetch(url)
            .then(function(response) {
                return response.json();
            });
    }
</script>