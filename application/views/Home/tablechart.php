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

                <div class="card-body row">
                    <div id="container" class="col" style="height: 340px;margin-top:40px;"></div> <br />
                    <div id="marriage" class="col" style="height: 340px;margin-top:40px;"></div>

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
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
<!-- End footer -->
<script type="text/javascript">
    let marriage_records = '<?php echo json_encode($marriage_records); ?>';
    parseMarriage = JSON.parse(marriage_records);
</script>
<script type="text/javascript">
    let data = '<?php echo json_encode($data); ?>';
    parseData = JSON.parse(data);
    console.log(parseMarriage)
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
        var tableTop = 60,
            colWidth = 100,
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
</script>