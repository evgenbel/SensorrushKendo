/**
 * Created by Evgeniy Belov on 05.01.2016.
 */

function refreshKendoChart(chart_element){
    var chart = $(chart_element).data("kendoChart");
    chart.dataSource.read();
}

function createChart(chart) {
    var series = {
        field: $(chart).attr('yAxis'),
        name: $(chart).attr('title')
    };
    if ($(chart).attr('type')=='scatter'){
        series = {
                xField: $(chart).attr('xAxis'),
                yField: $(chart).attr('yAxis')
        };
    }
    $(chart).kendoChart({
        dataSource: {
            transport: {
                read: {
                    url: location.href,
                    data: {ajaxurl: $(chart).attr('url'), type: $(chart).attr('type')},
                    dataType: "json"
                }
            }
        },
        autoBind: true,
        transitions: true,
        legend: {
            position: "top"
        },
        seriesDefaults: {
            type: $(chart).attr('type')
        },
        series: [series],
        categoryAxis: {
            field: $(chart).attr('xAxis'),
			title: {
      			text:  $(chart).attr('xAxis')
            },
            labels: {
                rotation: -90
            },
            crosshair: {
                visible: true
            },
            baseUnit: "fit",
			reverse:true
        },
        valueAxis: {
            labels: {
                format: "N0"
            },
			title: {
      			text:  $(chart).attr('yAxis')
            }
        },
        tooltip: {
            visible: true,
            shared: true,
            format: "N2"
        }
    });

    setInterval(function(){refreshKendoChart(chart)}, 20000);
}

$(document).ready(function(){
    $.each($(".charts"), function(){
        createChart(this);
    });
});