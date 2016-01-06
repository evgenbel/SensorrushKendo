<?php
/**
 * @package SensorrushChartKendo
 * @version 1.0
 */
/*
Plugin Name: SensorrushChartKendo
Plugin URI: https://github.com/evgenbel/SensorrushChartKendo
Description: Plugin for view Kendo Chart with data from http://sensorrush.net/{username}/{apikey}/{sensorname}/{channels}/Read/{limit}/{order}. Example: [sensorrush_kendo_chart username=sensorrush sensorname=MyPiSenseHAT apikey=1234 channels=Humidity limit=10 order=desc] - this parameters is default
Author: Evgeniy Belov
Version: 1.0
Author URI:
*/

class sensorrushChartKendo{
    public function __construct() {
        if (isset($_GET['ajaxurl']))
            add_action('template_redirect', array($this, 'hijackRequests'), -100);
        else
            add_shortcode('sensorrush_kendo_chart', array($this, 'show_chart'));
    }

    public function hijackRequests() {
        $this->writeJsonResponse();
    }

    protected function writeJsonResponse($status = 200) {
        $url = $_GET['ajaxurl'];
        $data = file_get_contents(/*'http://sensorrush.net/sensorrush/1234/MyPiSenseHAT/Humidity/Read/10/desc'*/$url);
        /*$data = json_decode($data);
        foreach($data as $item){

        }*/
        //
        header('content-type: application/json; charset=utf-8', true, $status);
        echo($data);
        exit;
    }

    protected function getUrl($attr){
        return 'http://sensorrush.net/' .
        $attr['username'] . '/' .
        $attr['apikey'] . '/' .
        $attr['sensorname'] . '/' .
        $attr['channels'] . '/Read/' .
        $attr['limit'] . '/' .
        $attr['order'];
    }

    public function show_chart($attr){
        $id = uniqid();
        $url = $this->getUrl($attr);
        $this->addStyles();
        $chart = '<div class="charts" id="chart_' . $id . '" url="' . $url . '" title="' . $attr['channels'] . '"></div> <button onclick="refreshKendoChart(\'#chart_' . $id .'\')">Refresh</button>';
        return  $chart;
    }

    protected function addStyles(){
        wp_register_style( 'kendo-common',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.common.min.css' );
        wp_register_style( 'kendo-rtl',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.rtl.min.css' );
        //wp_register_style( 'default-common',  'http://kendo.cdn.telerik.com/2014.2.716/styles/default.common.min.css' );
        wp_register_style( 'kendo-dataviz',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.dataviz.min.css' );
        wp_register_style( 'kendo-dataviz-default',  'http://kendo.cdn.telerik.com/2014.2.716/styles/kendo.dataviz.default.min.css' );
        wp_enqueue_style( 'kendo-common' );
        wp_enqueue_style( 'kendo-rtl' );
        wp_enqueue_style( 'default-common' );
        wp_enqueue_style( 'kendo-dataviz' );
        wp_enqueue_style( 'kendo-dataviz-default' );

        wp_enqueue_script( 'jquery-min', 'http://kendo.cdn.telerik.com/2014.2.716/js/jquery.min.js');
        wp_enqueue_script( 'kendo-all-min', 'http://kendo.cdn.telerik.com/2014.2.716/js/kendo.all.min.js');
        wp_enqueue_script( 'chart', plugins_url('SensorrushChartKendo/chart.js'));
    }
}

$plugin = new sensorrushChartKendo();