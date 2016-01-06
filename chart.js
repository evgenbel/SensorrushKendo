/**
 * Created by Evgeniy Belov on 05.01.2016.
 */

function refreshKendoChart(chart_element){
    var chart = $(chart_element).data("kendoChart");
    chart.dataSource.read();
}

function createChart(chart) {
    $(chart).kendoChart({
        dataSource: {
            transport: {
                read: {
                    url: location.href,
                    data: {ajaxurl: $(chart).attr('url')},
                    dataType: "json"
                }
            }
        },
        autoBind: true,
        transitions: true,
        title: {
            text: "Humidity"
        },
        legend: {
            position: "top"
        },
        seriesDefaults: {
            type: "line"
        },
        series: [{
            field: "Humidity",
            name: "Humidity"
        }],
        categoryAxis: {
            field: "ts",
            labels: {
                rotation: -90
            },
            crosshair: {
                visible: true
            },
            baseUnit: "fit"
        },
        valueAxis: {
            labels: {
                format: "N0"
            },
            majorUnit: 20
        },
        tooltip: {
            visible: true,
            shared: true,
            format: "N0"
        }
    });

    setInterval(function(){refreshKendoChart(chart)}, 20000);
}

$(document).ready(function(){
    $.each($(".charts"), function(){
        createChart(this);
    });
});