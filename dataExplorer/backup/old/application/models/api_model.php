<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$folder = dirname(dirname(__FILE__));
require_once $folder . "/helpers/curl.php";
//require_once 'abstract_mongo_model.php';

class Api_model extends CI_Model {

    public $url;
    
    public function __construct(){
        parent::__construct();
    }

    public function get_termResults_data($entityID, $termID, $FYFQ, $specialFormat = FALSE) {

        return $this->get_termResults_data_rest($entityID, $termID, $FYFQ);
    }

    protected function clean_str($dataIn){
        $ds = str_replace('"', '', $dataIn);
        $ds = str_replace(' ', '', $ds);

        return $ds;
    }

    protected function get_termResults_data_rest($entityID, $termID, $FYFQ) {

        $fyfq = $this->clean_str($FYFQ, '"');
        $entityIDs = $this->clean_str($entityID);
        $termIDs = $this->clean_str($termID);

        $this->url = REST_API_TERM_RESULTS_BY_ENTITY_ID;
        $this->url = str_replace('{0}', IDACITI_TOKEN, $this->url);
        $this->url = str_replace('{1}', $entityIDs, $this->url);
        $this->url = str_replace('{2}', $termIDs, $this->url);
        $this->url = str_replace('{3}', $fyfq, $this->url);

        log_message('debug', 'Api_model-get_termResults_data_rest url: ' . $this->url);

        if ($this->config->item('use_cache') === TRUE) {
            log_message('debug', 'Api_model-get_termResults_data_rest: using cached copy');
            $result = $this->session->userdata($this->url);
        }

        if (!isset($result) || empty($result)) {
            $this->benchmark->mark('Api_model-get_termResults_data_rest_start');

            $_mycurl = new  mycurl($this->url);
            $_mycurl->useAuth(false);
            $_mycurl->createCurl();
            $result = (string)$_mycurl;

            if ($this->config->item('use_cache') === TRUE ) {
                $this->session->set_userdata($this->url, $result);
            }

            $this->benchmark->mark('Api_model-get_termResults_data_rest_end');
            log_message('debug', 'Api_model-get_termResults_data_rest: ' . $this->benchmark->elapsed_time('Api_model-get_termResults_data_rest_start', 'Api_model-get_termResults_data_rest_end'));
        }

        $result = json_decode($result, true);

        return $result;
    }

    public function get_termResults_drilldown($entityID, $termID, $FYFQ) {

        $entityIDs = $this->clean_str($entityID);

        $this->url = REST_API_TERM_RESULTS_DRILLDOWN;
        $this->url = str_replace('{0}', IDACITI_TOKEN, $this->url);
        $this->url = str_replace('{1}', $entityIDs, $this->url);
        $this->url = str_replace('{2}', $termID, $this->url);
        $this->url = str_replace('{3}', $FYFQ, $this->url);

        log_message('debug', 'Api_model-drilldown url: ' . $this->url);

        // no cache on drilldown

        $this->benchmark->mark('Api_model-drilldown_start');

        $_mycurl = new  mycurl($this->url);
        $_mycurl->useAuth(false);
        $_mycurl->createCurl();
        $result = (string)$_mycurl;

        $result = json_decode($result, true);

        $this->benchmark->mark('Api_model-drilldown_end');

        log_message('debug', 'Api_model-drilldown: ' . $this->benchmark->elapsed_time('Api_model-drilldown_start', 'Api_model-drilldown_end'));
        //log_message('debug', 'Api_model-php: json_decode: ' . print_r($result, true));

        /* no cache on drilldown
        if ($this->config->item('use_cache') === TRUE && !empty($result)) {
            $this->session->set_userdata($cacheKey, $result);
        }
    */

        return $result;
    }

    public function get_trending_benchmark_data($entityID, $peerEntities, $term, $calc_type, $FYFQ)
    {
        $this->benchmark->mark('Api_model-get_trending_benchmark_data_start');

        //$this->url = 'http://data.idaciti.com:81/api/benchmark/trending/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&peerEntities='.$peerEntities.'&term='.$term.'&calcType='.$calc_type.'&periods='.$FYFQ;

        $this->url = REST_API_BENCHMARK_TRENDING;
        $this->url = str_replace('{0}', IDACITI_TOKEN, $this->url);
        $this->url = str_replace('{1}', $entityID, $this->url);
        $this->url = str_replace('{2}', $peerEntities, $this->url);
        $this->url = str_replace('{3}', $term, $this->url);
        $this->url = str_replace('{4}', $calc_type, $this->url);
        $this->url = str_replace('{5}', $FYFQ, $this->url);

        $_mycurl = new  mycurl($this->url);
        $_mycurl->useAuth(false);
        $_mycurl->createCurl();
        $result = (string)$_mycurl;

        $result = json_decode($result, true);

        $this->benchmark->mark('Api_model-get_trending_benchmark_data_end');

        return $result;
    }

    public function get_commonSize_benchmark_data($entityID, $peerEntities, $reportType, $calc_type, $period )
    {
        $this->benchmark->mark('Api_model-get_commonSize_benchmark_data_start');

        //$this->url ='http://data.idaciti.com:81/api/benchmark/commonSize/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&peerEntities='.$peerEntities.'&reportType='.$reportType.'&calcType='.$calc_type.'&period='.$period;

        $this->url = REST_API_BENCHMARK_COMMONSIZE;
        $this->url = str_replace('{0}', IDACITI_TOKEN, $this->url);
        $this->url = str_replace('{1}', $entityID, $this->url);
        $this->url = str_replace('{2}', $peerEntities, $this->url);
        $this->url = str_replace('{3}', $reportType, $this->url);
        $this->url = str_replace('{4}', $calc_type, $this->url);
        $this->url = str_replace('{5}', $period, $this->url);

        $_mycurl = new  mycurl($this->url);
        $_mycurl->useAuth(false);
        $_mycurl->createCurl();
        $result = (string)$_mycurl;

        $result = json_decode($result, true);

        $this->benchmark->mark('Api_model-get_commonSize_benchmark_data_end');

        return $result;
    }

    public function get_scatterPlot_benchmark_data($entityID, $includePeer, $peerEntities, $active_xaxes, $active_yaxes, $period )
    {
        $this->benchmark->mark('Api_model-get_scatterPlot_benchmark_data_start');

        if ($includePeer === true) {
            //$this->url ='http://data.idaciti.com:81/api/benchmark/scatterPlot/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&includeAllPeers=true&xAxisTermId='.$active_xaxes.'&yAxisTermId='.$active_yaxes.'&period='.$period;
            $this->url = REST_API_BENCHMARK_SCATTERPLOT_INCLUDEALLPEERS;
            $this->url = str_replace('{2}', 'true', $this->url);

        }
        else {
            //$this->url ='http://data.idaciti.com:81/api/benchmark/scatterPlot/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&peerEntities='.$peerEntities.'&xAxisTermId='.$active_xaxes.'&yAxisTermId='.$active_yaxes.'&period='.$period;
            $this->url = REST_API_BENCHMARK_SCATTERPLOT_PEERLIST;
            $this->url = str_replace('{2}', $peerEntities, $this->url);
        }

        $this->url = str_replace('{0}', IDACITI_TOKEN, $this->url);
        $this->url = str_replace('{1}', $entityID, $this->url);
        // 2 taken care of above
        $this->url = str_replace('{3}', $active_xaxes, $this->url);
        $this->url = str_replace('{4}', $active_yaxes, $this->url);
        $this->url = str_replace('{5}', $period, $this->url);

        $_mycurl = new  mycurl($this->url);
        $_mycurl->useAuth(false);
        $_mycurl->createCurl();
        $result = (string)$_mycurl;

        $result = json_decode($result, true);

        $this->benchmark->mark('Api_model-get_scatterPlot_benchmark_data_end');

        return $result;
    }

    public function get_entity_data()
    {
        $this->url = REST_API_ENTITY;
        $this->url = str_replace('{0}', IDACITI_TOKEN, $this->url);

        log_message('debug', 'Api_model-get_companies_data url: ' . $this->url);

        $this->benchmark->mark('Api_model-get_companies_data_start');

        $_mycurl = new  mycurl($this->url);
        $_mycurl->useAuth(false);
        $_mycurl->createCurl();
        $result = (string)$_mycurl;

        $this->benchmark->mark('Api_model-get_companies_data_end');

        log_message('debug', 'Api_model-get_companies_data: ' . $this->benchmark->elapsed_time('Api_model-get_companies_data_start', 'Api_model-get_companies_data_end'));

        $result = json_decode($result, true);

        return $result;
    }

    public function get_kpi_data()
    {
        $this->url = REST_API_TERM_RULE;
        $this->url = str_replace('{0}', IDACITI_TOKEN, $this->url);

        log_message('debug', 'Api_model-get_kpi_data url: ' . $this->url);

        $this->benchmark->mark('Api_model-get_kpi_data_start');

        $_mycurl = new  mycurl($this->url);
        $_mycurl->useAuth(false);
        $_mycurl->createCurl();
        $result = (string)$_mycurl;

        $this->benchmark->mark('Api_model-get_kpi_data_end');

        log_message('debug', 'Api_model-get_kpi_data: ' . $this->benchmark->elapsed_time('Api_model-get_kpi_data_start', 'Api_model-get_kpi_data_end'));

        $result = json_decode($result, true);

        return $result;

    }
}

