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
                        Appointments</a></li>
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

                <?php } ?>

                <input type="text" name="date_from" id="date_from" class="form-controL date_from input-rounded input-sm " placeholder="Date From : " />

                <input type="text" name="date_to" id="date_to" class="form-control date_to input-rounded input-sm " placeholder="Date To : " />

                <button class="btn btn-default filter_highcharts_appointment_dashboard btn-round  btn-small btn-primary  " type="button" name="filter_highcharts_appointment_dashboard" id="filter_highcharts_appointment_dashboard"> <i class="fa fa-filter"></i>
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
                                <div class="card-body grey" id="allApps">
                                    <b><?php echo $created_appointments; ?><br></b>
                                    Created Appointments
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="keptApps">
                                    <b><?php echo $kept_appointments; ?><br></b>
                                    Honoured Appointments
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="defaultedApps">
                                    <b><?php echo $defaulted_appointments; ?><br></b>
                                    Active Defaulted Appointments
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="missedApps">
                                    <b><?php echo $missed_appointments; ?><br></b>
                                    Active Missed Appointments
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="card">
                                <div class="card-body grey" id="ltfuApps">
                                    <b><?php echo $ltfu_appointments; ?><br></b>
                                    Active LTFU Appointments
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="card-body row">
                                <div id="container" class="col" style="height: 450px;margin-top:40px;"></div> <br />
                                <div id="marriage" class="col" style="height: 450px;margin-top:40px;"></div>
                            </div>
                            <div class="card-body row">
                                <div id="trend" class="col" style="height: 450px;margin-top:40px;"></div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- End PAge Content -->

        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
    <!-- footer -->
</div>
<footer class="footer"> © 2020 Ushauri - All rights reserved. Powered by <a href="https://mhealthkenya.org">mHealth
        Kenya Ltd</a></footer>
<!-- End footer -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/themes/high-contrast-light.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<!-- End Page wrapper  -->
<script type="text/javascript">
    let data = '<?php echo json_encode($data); ?>';
    parseRecords = JSON.parse(data)

    let marriage_appointments = '<?php echo json_encode($marriage_appointments); ?>';
    parseMarriage = JSON.parse(marriage_appointments)

    let trend_appointments = '<?php echo json_encode($trend_appointments); ?>';
    parseTrend = JSON.parse(trend_appointments)
    //console.log(parseTrend)
    //const testMap = parseTrend.map(trend => `${trend.month_year} ${trend.Total_Appointments}`).filter(trend.month_year == 'October, 2018');
    //.filter(trend => trend.month_year == 'October, 2018')



    var trendSet = new Set()
    parseTrend.forEach((trend) => {
        monthYear = moment(trend.month_year).format('YYYY-MM')
        trendSet.add(monthYear)

    });
    trendSet = [...trendSet].sort();
    //console.log(trendSet)


    var keptArray = []
    var defaultedArray = []
    var missedArray = []
    var ltfuArray = []

    var totalKept = []
    var totalMissed = []
    var totalDefaulted = []
    var totalLTFU = []

    const totalD = parseTrend.reduce((total, parseT) => total + parseInt(parseT.Defaulted_Appointments), 0)
    const totalK = parseTrend.reduce((total, parseT) => total + parseInt(parseT.Kept_Appointments), 0)
    const totalL = parseTrend.reduce((total, parseT) => total + parseInt(parseT.LTFU_Appointments), 0)
    const totalM = parseTrend.reduce((total, parseT) => total + parseInt(parseT.Missed_Appointments), 0)

    totalDefaulted.push(totalD)
    totalKept.push(totalK)
    totalMissed.push(totalM)
    totalLTFU.push(totalL)
    //console.log(totalDefaulted)






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

    var colors = Highcharts.getOptions().colors;

    // Highcharts.chart('trend', {
    //     chart: {
    //         type: 'spline'
    //     },

    //     legend: {
    //         symbolWidth: 40
    //     },

    //     title: {
    //         text: 'Most common desktop screen readers'
    //     },

    //     subtitle: {
    //         text: 'Source: WebAIM. Click on points to visit official screen reader website'
    //     },

    //     yAxis: {
    //         title: {
    //             text: 'Percentage usage'
    //         }
    //     },

    //     xAxis: {
    //         title: {
    //             text: 'Time'
    //         },
    //         accessibility: {
    //             description: 'Time from December 2010 to September 2019'
    //         },
    //         categories: trendSet
    //     },

    //     tooltip: {
    //         valueSuffix: '%'
    //     },

    //     plotOptions: {
    //         series: {
    //             point: {
    //                 events: {
    //                     click: function() {
    //                         window.location.href = this.series.options.website;
    //                     }
    //                 }
    //             },
    //             cursor: 'pointer'
    //         }
    //     },

    //     series: [{
    //         name: 'NVDA',
    //         data: [34.8, 43.0, 51.2, 41.4, 64.9, 72.4],
    //         website: 'https://www.nvaccess.org',
    //         color: colors[2],
    //         accessibility: {
    //             description: 'This is the most used screen reader in 2019'
    //         }
    //     }, {
    //         name: 'JAWS',
    //         data: [69.6, 63.7, 63.9, 43.7, 66.0, 61.7],
    //         website: 'https://www.freedomscientific.com/Products/Blindness/JAWS',
    //         dashStyle: 'ShortDashDot',
    //         color: colors[0]
    //     }, {
    //         name: 'VoiceOver',
    //         data: [20.2, 30.7, 36.8, 30.9, 39.6, 47.1],
    //         website: 'http://www.apple.com/accessibility/osx/voiceover',
    //         dashStyle: 'ShortDot',
    //         color: colors[1]
    //     }, {
    //         name: 'Narrator',
    //         data: [null, null, null, null, 21.4, 30.3],
    //         website: 'https://support.microsoft.com/en-us/help/22798/windows-10-complete-guide-to-narrator',
    //         dashStyle: 'Dash',
    //         color: colors[9]
    //     }, {
    //         name: 'ZoomText/Fusion',
    //         data: [6.1, 6.8, 5.3, 27.5, 6.0, 5.5],
    //         website: 'http://www.zoomtext.com/products/zoomtext-magnifierreader',
    //         dashStyle: 'ShortDot',
    //         color: colors[5]
    //     }, {
    //         name: 'Other',
    //         data: [42.6, 51.5, 54.2, 45.8, 20.2, 15.4],
    //         website: 'http://www.disabled-world.com/assistivedevices/computer/screen-readers.php',
    //         dashStyle: 'ShortDash',
    //         color: colors[3]
    //     }],

    //     responsive: {
    //         rules: [{
    //             condition: {
    //                 maxWidth: 550
    //             },
    //             chartOptions: {
    //                 legend: {
    //                     itemWidth: 150
    //                 },
    //                 xAxis: {
    //                     categories: trendSet
    //                 },
    //                 yAxis: {
    //                     title: {
    //                         enabled: false
    //                     },
    //                     labels: {
    //                         format: '{value}%'
    //                     }
    //                 }
    //             }
    //         }]
    //     }
    // });
</script>