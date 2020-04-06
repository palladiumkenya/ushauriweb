<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">
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
                <input type='hidden' id="access_level" value="<?php echo $access_level; ?>" />
                <input type='hidden' id="partner_id" value="<?php echo $partner_id; ?>" />
                <input type='hidden' id="facility_id" value="<?php echo $facility_id; ?>" />

                <?php if ($access_level == 'Admin' || $access_level == 'Donor') { ?>

                    <select class="form-control filter_partner  input-rounded input-sm select2" name="filter_partner" id="filter_partner">
                        <option value="">Please select Partner</option>
                        <?php
                        foreach ($filtered_partner as $value) {
                        ?>
                            <option value="<?php echo $value->partner_id; ?>">
                                <?php echo $value->partner_name; ?>
                            </option>
                        <?php
                        }
                        ?>
                        <option></option>
                    </select>
                <?php } ?>

                <?php if ($access_level == 'Admin' || $access_level == 'Donor' || $access_level == 'Partner') { ?>

                    <select class="form-control filter_county  input-rounded input-sm select2" name="filter_county" id="filter_county">
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
                    <select class="form-control filter_sub_county input-rounded input-sm select2" name="filter_sub_county" id="filter_sub_county">
                        <option value="">Please Select Sub County : </option>
                    </select>

                    <span class="filter_facility_wait" style="display: none;">Loading , Please Wait ...</span>

                    <select class="form-control filter_facility input-rounded input-sm select2" name="filter_facility" id="filter_facility">
                        <option value="">Please select Facility : </option>
                    </select>

                    <!-- <?php } ?>

                <input type="text" name="date_from" id="date_from"
                    class="form-controL date_from input-rounded input-sm " placeholder="Date From : " />

                <input type="text" name="date_to" id="date_to" class="form-control date_to input-rounded input-sm "
                    placeholder="Date To : " /> -->

                    <button class="btn btn-default filter_highcharts_dashboard btn-round  btn-small btn-primary  " type="button" name="filter_highcharts_dashboard" id="filter_highcharts_dashboard"> <i class="fa fa-filter"></i>
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
                                <div class="card-body grey" id="targetClients">
                                    <b><?php echo $target_active_clients; ?><br></b>
                                    Target Active Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="totalClients">
                                    <b><?php echo $total_clients; ?><br></b>
                                    No. of Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="percentageUptake">
                                    <b><?php echo $percentage_uptake; ?><br></b>
                                    % No. of Active Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="consentedClients">
                                    <b><?php echo $consented_clients; ?><br></b>
                                    Consented Clients
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="furuteAppointments">
                                    <b><?php echo $future_appointments; ?><br></b>
                                    Future Appointments
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="facilities" <b><?php echo $facilities; ?><br></b>
                                    No. of Facilities
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row" id="column"></div>
                        <div class="row" id="map"></div>
                    </div>

                    <!-- <div class="card-body row">
                        <figure class="highcharts-figure">
                            <div class="col-6" id="container"></div>
                        </figure>
                    </div> -->


                </div>
            </div>
        </div>
        <!-- End PAge Content -->

        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
    <footer class="footer"> © 2020 Ushauri - All rights reserved. Powered by <a href="https://mhealthkenya.org">mHealth
            Kenya Ltd</a></footer>
    <!-- End footer -->
</div>
<!-- End Page wrapper  -->

<script type='text/javascript'>
    $(document).ready(function() {
        var data = '<?php echo json_encode($data); ?>';
        var bar_clients_data =
            '<?php echo json_encode($bar_clients_data); ?>';
        var bar_appointments_data =
            '<?php echo json_encode($bar_appointments_data); ?>';
        maps(data)
        columnChart(bar_clients_data, bar_appointments_data);
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
                        pointFormat: 'County: {point.properties.COUNTY}<br> Clients: {point.Clients} <br> Consented: {point.Consented} <br> Total Target Clients: {point.Target_Clients} <br> Male: {point.Male} <br> Female: {point.Female} <br> TransGender: {point.Trans_Gender} <br> No. of Facilities: {point.mfl_code} <br> % Uptake Per County: {point.Percentage_Uptake}'
                    }
                }]
            });
        }

        function columnChart(bar_clients_data, bar_appointments_data) {
            bar_clients_data = JSON.parse(bar_clients_data);
            bar_appointments_data = JSON.parse(bar_appointments_data);
            let barData = bar_clients_data.concat(bar_appointments_data)
            let result = barData.reduce((acc, el) => {
                var existEl = acc.find(e => e.MONTH == el.MONTH && e.mfl_code == el.mfl_code);
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
                    clientsCount = clientsCount + parseInt(categoryDatas[j].clients)
                    consentedCount = consentedCount + parseInt(categoryDatas[j].consented)
                    appointmentsCount = appointmentsCount + parseInt(categoryDatas[j].appointments)
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
        // function fetchJSON(url) {
        //     return fetch(url)
        //         .then(function(response) {
        //             return response.json();
        //         });
        // }
    });
</script>