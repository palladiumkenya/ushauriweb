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

                    <button class="btn btn-default filter_tablecharts_dashboard btn-round  btn-small btn-primary  " type="button" name="filter_tablecharts_dashboard" id="filter_tablecharts_dashboard"> <i class="fa fa-filter"></i>
                        Filter</button>

            </form>

        </div>

        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">

                <div class="card-body row">
                    <div id="container" class="col" style="height: 450px;margin-top:40px;"></div> <br />
                    <div id="marriage" class="col" style="height: 450px;margin-top:40px;"></div>
                </div>
                <div class="card-body row">
                    <div id="art" class="col" style="height: 450px;margin-top:40px;"></div>
                    <div id="gender" class="col" style="height: 450px;margin-top:40px;"></div>
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
<script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<!-- End footer -->
<script type="text/javascript">
    let condition_records = '<?php echo json_encode($condition_records); ?>';
    parseCondition = JSON.parse(condition_records);

    let art = 0;
    let on_care = 0;
    let pre_art = 0;
    let no_condition = 0;

    let art_consent = 0;
    let on_care_consent = 0;
    let pre_art_consent = 0;
    let no_condition_consent = 0;


    var conditionReg = [];
    var conditionConsent = [];
    var conditionPercent = [];

    for (let i = 0; i < parseCondition.length; i++) {
        art = art + parseInt(parseCondition[i].art);
        on_care = on_care + parseInt(parseCondition[i].on_care);
        pre_art = pre_art + parseInt(parseCondition[i].pre_art);
        no_condition = no_condition + parseInt(parseCondition[i].no_condition);
        art_consent = art_consent + parseInt(parseCondition[i].art_consent);
        on_care_consent = on_care_consent + parseInt(parseCondition[i].on_care_consent);
        pre_art_consent = pre_art_consent + parseInt(parseCondition[i].pre_art_consent);
        no_condition_consent = no_condition_consent + parseInt(parseCondition[i].not_condition_consent);

    }
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
</script>
<script type="text/javascript">
    let gender_records = '<?php echo json_encode($gender_records); ?>';
    parseGender = JSON.parse(gender_records);

    let female = 0;
    let male = 0;
    let trans_gender = 0;
    let unspecified = 0;

    let female_consent = 0;
    let male_consent = 0;
    let trans_gender_consent = 0;
    let unspecified_consent = 0;

    var genderReg = [];
    var genderConsent = [];
    var genderPercent = [];

    for (let i = 0; i < parseGender.length; i++) {
        female = female + parseInt(parseGender[i].female);
        male = male + parseInt(parseGender[i].male);
        trans_gender = trans_gender + parseInt(parseGender[i].trans_gender);
        unspecified = unspecified + parseInt(parseGender[i].not_specified_gender);
        female_consent = female_consent + parseInt(parseGender[i].female_consent);
        male_consent = male_consent + parseInt(parseGender[i].male_consent);
        trans_gender_consent = trans_gender_consent + parseInt(parseGender[i].trans_gender_consent);
        unspecified_consent = unspecified_consent + parseInt(parseGender[i].not_specified_gender_consent);

    }
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
</script>
<script type="text/javascript">
    let marriage_records = '<?php echo json_encode($marriage_records); ?>';
    parseMarriage = JSON.parse(marriage_records);

    let single = 0;
    let monogamous = 0;
    let divorced = 0;
    let widowed = 0;
    let cohabiting = 0;
    let unavailable = 0;
    let polygamous = 0;

    let singleConsent = 0;
    let monogamousConsent = 0;
    let divorcedConsent = 0;
    let widowedConsent = 0;
    let cohabitingConsent = 0;
    let unavailableConsent = 0;
    let pologamousConsent = 0;

    var marriedConsented = [];
    var marriedRegistered = [];
    var marriedPercent = [];

    for (let i = 0; i < parseMarriage.length; i++) {
        single = single + parseInt(parseMarriage[i].single);
        monogamous = monogamous + parseInt(parseMarriage[i].married_monogamous);
        divorced = divorced + parseInt(parseMarriage[i].divorced);
        widowed = widowed + parseInt(parseMarriage[i].widowed);
        cohabiting = cohabiting + parseInt(parseMarriage[i].cohabiting);
        unavailable = unavailable + parseInt(parseMarriage[i].unavailable);
        polygamous = polygamous + parseInt(parseMarriage[i].maried_polygamous);
        singleConsent = singleConsent + parseInt(parseMarriage[i].single_consent);
        monogamousConsent = monogamousConsent + parseInt(parseMarriage[i].married_monogamous_consent);
        divorcedConsent = divorcedConsent + parseInt(parseMarriage[i].divorced_consented);
        widowedConsent = widowedConsent + parseInt(parseMarriage[i].widowed_consented);
        cohabitingConsent = cohabitingConsent + parseInt(parseMarriage[i].cohabiting_consented);
        unavailableConsent = unavailableConsent + parseInt(parseMarriage[i].unavailable_consented);
        pologamousConsent = pologamousConsent + parseInt(parseMarriage[i].married_polygomous_consented);

    }
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
</script>
<script type="text/javascript">
    let data = '<?php echo json_encode($data); ?>';
    parseData = JSON.parse(data);
    let toNineReg = 0;
    let toFourteenReg = 0;
    let toNineteenReg = 0;
    let toTwenFourReg = 0;
    let toTwenFiveReg = 0;

    let toNineConsent = 0;
    let toFourteenConsent = 0;
    let toNineteenConsent = 0;
    let toTwentyFourConsent = 0;
    let toTwentyFiveConsent = 0;
    var consentedArray = [];
    var registeredArray = [];
    var percentageArray = [];
    for (let i = 0; i < parseData.length; i++) {
        toNineReg = toNineReg + parseInt(parseData[i].ToNineregistered);
        toFourteenReg = toFourteenReg + parseInt(parseData[i].ToFourteenregistered);
        toNineteenReg = toNineteenReg + parseInt(parseData[i].ToNineteenregistered);
        toTwenFourReg = toTwenFourReg + parseInt(parseData[i].ToTwentyFourregistered);
        toTwenFiveReg = toTwenFiveReg + parseInt(parseData[i].Overtwentyfiveregistered);
        toNineConsent = toNineConsent + parseInt(parseData[i].ToNineconsented);
        toFourteenConsent = toFourteenConsent + parseInt(parseData[i].ToFourteenconsented);
        toNineteenConsent = toNineteenConsent + parseInt(parseData[i].ToNineteenconsented);
        toTwentyFourConsent = toTwentyFourConsent + parseInt(parseData[i].ToTwentyFourconsented);
        toTwentyFiveConsent = toTwentyFiveConsent + parseInt(parseData[i].TwentyFiveconsented);

    }
    //console.log(toNineReg)
    percentageArray.push((toNineConsent / toNineReg) * 100)
    percentageArray.push((toFourteenConsent / toFourteenReg) * 100)
    percentageArray.push((toNineteenConsent / toNineteenReg) * 100)
    percentageArray.push((toTwentyFourConsent / toTwenFourReg) * 100)
    percentageArray.push((toTwentyFiveConsent / toTwenFiveReg) * 100)

    registeredArray.push(toNineReg);
    registeredArray.push(toFourteenReg);
    registeredArray.push(toNineteenReg);
    registeredArray.push(toTwenFourReg);
    registeredArray.push(toTwenFiveReg);
    consentedArray.push(toNineConsent);
    consentedArray.push(toFourteenConsent);
    consentedArray.push(toNineteenConsent);
    consentedArray.push(toTwentyFourConsent);
    consentedArray.push(toTwentyFiveConsent);



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
</script>