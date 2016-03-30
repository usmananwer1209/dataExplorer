<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 11/18/2015
 * Time: 4:18 PM
 */
class benchmarkChart_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('api_model', 'api');
    }

    public function get_company_kpis_values($term, $calc_type, $reportType, $period, $includePeer, $active_xaxes,$active_yaxes,$chat_type, $company,$companies, $kpis = array(), $segments = 'year') {
        $this->benchmark->mark('benchmark_model-get_company_kpis_values_benchmark_start');
        $all_periods = $this->rperiods->list_records();
        $quarters = array();
        $years = array();
        foreach ($all_periods as $k => $p) {
            if(strpos($p->reporting_period, 'Q') === false)
                $years[] = $p->reporting_period;
            else
                $quarters[] = $p->reporting_period;
        }
        if($segments == 'quarter') {
            $periods = $quarters;
        }
        else
            $periods = $years;
        $entityID = sprintf("%06d", $company);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($periods);
        $companies     = array_to_string($companies);

        $result = $this->get_data($term, $calc_type, $reportType, $period, $includePeer, $active_xaxes,$active_yaxes,$chat_type, $entityID,$companies ,$termID, $FYFQ, TRUE);

        $this->benchmark->mark('benchmark_model-get_company_kpis_values_benchmark_end');
        log_message('debug', 'get_company_kpis_values_benchmark: ' . $this->benchmark->elapsed_time('benchmark_model-get_company_kpis_values_benchmark_start', 'benchmark_model-get_company_kpis_values_benchmark_end'));
        return $result;
    }

    protected function get_data($term, $calc_type, $reportType, $period, $includePeer, $active_xaxes,$active_yaxes,$chat_type, $entityID,$companies, $termID, $FYFQ, $specialFormat = FALSE) {
        $this->load->model('kpis_model', 'kpis');
        //$active_xaxes = 697697;
        //$active_yaxes = 346210;
        $xaxes_symbel = '';
        $yaxes_symbel = '';
        $com_record = $this->companies_model->get_by_id($entityID);
        if(count($com_record)==0){
            $com_record = $this->private_company_model->get_by_id_search($entityID);
        }
        if(count($com_record)>0){
            $company_name = $com_record->company_name;
        }else{
            $company_name = '';
        }
        $kpi_record = $this->kpis->get_by_id($term);
        $kpi_name =	$kpi_record->name;

        if($active_xaxes != 'none'){
            $kpi_record = $this->kpis->get_by_id($active_xaxes);
            $xaxes_name =	$kpi_record->name;
        }
        else{
            $xaxes_name =	'';
        }
        if($active_yaxes != 'none'){
            $kpi_record = $this->kpis->get_by_id($active_yaxes);
            $yaxes_name =	$kpi_record->name;
        }
        else{
            $yaxes_name =	'';
        }

        $FYFQ = str_replace('"','', $FYFQ);
        $FYFQ = str_replace(' ','', $FYFQ);

        $peerEntities = str_replace('"'.$entityID.'",','', $companies);
        $peerEntities = str_replace(', "'.$entityID.'"','', $peerEntities);
        $peerEntities = str_replace('"','', $peerEntities);
        $peerEntities = str_replace(' ','', $peerEntities);
        $count_peer = count(explode(",",$peerEntities));

        if ($chat_type == 'trend' || $chat_type == 'range') {
            $result = $this->api->get_trending_benchmark_data($entityID, $peerEntities, $term, $calc_type, $FYFQ);
        }
        else if($chat_type == 'common') {

            $result = $this->api->get_commonSize_benchmark_data($entityID, $peerEntities, $reportType, $calc_type, $period );
        }
        else if ($chat_type == 'scatter'){

            $result = $this->api->get_scatterPlot_benchmark_data($entityID, $includePeer, $peerEntities, $active_xaxes, $active_yaxes, $period );
        }
        else {
            log_message('ERROR', 'Unknown benchmark type: '.$chat_type );
            return;
        }
            /*
            $this->benchmark->mark('Api_model-curl_start');

            $url = 'http://data.idaciti.com:81/api/benchmark/trending/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&peerEntities='.$peerEntities.'&term='.$term.'&calcType='.$calc_type.'&periods='.$FYFQ;
            $this->api->get_trending_benchmark_data($entityID, $peerEntities, $term, $calc_type, $FYFQ );
            if($chat_type == 'common'){
                $url ='http://data.idaciti.com:81/api/benchmark/commonSize/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&peerEntities='.$peerEntities.'&reportType='.$reportType.'&calcType='.$calc_type.'&period='.$period;
                $this->api->get_commonSize_benchmark_data($entityID, $peerEntities, $reportType, $calc_type, $period );
            }
            if($chat_type == 'scatter'){
                //if($includePeer == 'true'){
                    $url ='http://data.idaciti.com:81/api/benchmark/scatterPlot/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&includeAllPeers=true&xAxisTermId='.$active_xaxes.'&yAxisTermId='.$active_yaxes.'&period='.$period;
                    $this->api->get_scatterPlot_benchmark_data($entityID, $includePeer, $peerEntities, $active_xaxes, $active_yaxes, $period );
                //}else{
                //    $url ='http://data.idaciti.com:81/api/benchmark/scatterPlot/json?token=oepsy3b6&benchmarkEntity='.$entityID.'&peerEntities='.$peerEntities.'&xAxisTermId='.$active_xaxes.'&yAxisTermId='.$active_yaxes.'&period='.$period;
                //}
            }
            $_mycurl = new  mycurl($url);
            $_mycurl->createCurl();
            $result = (string)$_mycurl;
            $result = json_decode($result, true);


            $this->benchmark->mark('Api_model-curl_end');
            */

        $categories =array();
        $i = 0;
        $data_peer = array();
        $data_primary = array();
        $series = array();
        if(count($result)>0){
            if($chat_type != 'scatter'){
                foreach($result as $res){
                    if($chat_type == 'trend'){
                        $categories[$i] = $res['period'];
                        $data_peer[$i] = $res['peerGroupValue'];
                        $data_primary[$i] = $res['primaryValue'];
                    }elseif($chat_type == 'range'){
                        $categories[$i] = $res['period'];
                        $data_peer[$i] = array($res['period'],$res['peerGroupValueLow'],$res['peerGroupValueHigh']);
                        $data_primary[$i] = array($res['period'],$res['primaryValue']);
                    }
                    elseif($chat_type == 'common'){
                        $categories[$i] = $res['termName'];
                        $data_peer[$i] = $res['peerGroupValue']*100;
                        $data_primary[$i] = $res['primaryValue']*100;
                    }
                    $i++;
                }

            }elseif($chat_type == 'scatter'){
                $value_of_x = 0;
                $value_of_y = 0;
                foreach($result['primaryPoints'] as $res){

                    $com_record = $this->companies_model->get_by_id($res['entityId']);
                    if(count($com_record)==0){
                        $com_record = $this->private_company_model->get_by_id_search($res['entityId']);
                    }
                    if(count($com_record)>0){
                        $com_name = $com_record->company_name;
                    }else{
                        $com_name = '';
                    }
                    $data_primary[$i] = array(
                        'name'=> $com_name,
                        'x'=> $res['xPoint'],
                        'y'=> $res['yPoint']
                    );
                    $value_of_x = $res['xPoint'];
                    $value_of_y = $res['yPoint'];
                }
                foreach($result['peerGroupPoints'] as $res){

                    $com_record = $this->companies_model->get_by_id($res['entityId']);
                    if(count($com_record)==0){
                        $com_record = $this->private_company_model->get_by_id_search($res['entityId']);
                    }
                    if(count($com_record)>0){
                        $com_name = $com_record->company_name;
                    }else{
                        $com_name = '';
                    }
                    $data_peer[$i] = array(
                        'name'=> $com_name,
                        'x'=> $res['xPoint'],
                        'y'=> $res['yPoint']
                    );
                    $i++;
                }
                if($active_xaxes != 'none'){
                    $xaxes_name = $xaxes_name.$this->getcurrencyStrength($value_of_x);
                    $xaxes_symbel = $this->getcurrencyStrength($value_of_x);
                }
                if($active_yaxes != 'none'){
                    $yaxes_name = $yaxes_name.$this->getcurrencyStrength($value_of_y);
                    $yaxes_symbel = $this->getcurrencyStrength($value_of_y);
                }
            }
            if($chat_type == 'trend'){
                $series[0] = array(
                    'name' => 'Peer Group ('.$count_peer.' Companies) -- '.$calc_type,
                    'type' => 'column',
                    'color'=> 'rgba(165,170,217,1)',
                    'data'=>  $data_peer
                );
                $series[1] = array(
                    'name'	=> $company_name.' (Primary Company)',
                    'type'	=> 'column',
                    'color'	=> 'rgba(126,86,134,.9)',
                    'data'	=>  $data_primary,
                    'pointPadding'=> 0.3
                );
                $series[2] = array(
                    'name'	=> $company_name.' Trend',
                    'type'	=> 'line',
                    'linkedTo' => ':previous',
                    'color'	=> 'rgba(126,86,134,.9)',
                    'data'	=>  $data_primary,
                    'pointPadding'=> 0.3
                );
            }elseif($chat_type == 'range'){

                $series[0] = array(
                    'name'	=> $company_name.' (Primary Company)',
                    'data'	=>  $data_primary,
                    'zIndex' => 1,
                    'marker' => array(
                        'fillColor' => 'white',
                        'lineWidth'=> 4,
                        'lineColor' => 'rgba(126,86,134,.9)'
                    ),
                    'color' => 'rgba(126,86,134,.9)'
                );

                $series[1] = array(
                    'name' => 'Peer Group ('.$count_peer.' Companies)',
                    'data' =>  $data_peer,
                    'type' => 'arearange',
                    'lineWidth' => 0,
                    'color' => 'rgba(165,170,217,1)',
                    'fillOpacity' => 0.8,
                    'zIndex' => 0

                );
            }
            elseif($chat_type == 'common'){

                $series[0] = array(
                    'name' => 'Peer Group ('.$count_peer.' Companies)',
                    'color' => 'rgba(165,170,217,1)',
                    'data' =>  $data_peer
                );

                $series[1] = array(
                    'name'	=> $company_name.' (Primary Company)',
                    'color' => 'rgba(126,86,134,.9)',
                    'data'	=>  $data_primary,
                    'pointPadding' => 0.3,
                    'dataLabels' => array(
                        'enabled' => true,
                        'rotation'=> -90,
                        'color' => '#000',
                        'align' => 'right',
                        'format' => '{point.y:.2f}%',
                        'y' => -50,
                        'x' => 4
                    )

                );
            }
            elseif($chat_type == 'scatter'){
				$series[0] = array(
                    'name' => 'Peer Group',
                    'color' => 'rgba(165,170,217,1)',
                    'data' =>  $data_peer,
                    'turboThreshold' => 0
                );
                $series[1] = array(
                    'name'	=> 'Primary Company',
                    'color' => 'rgba(126,86,134,.9)',
					'marker'=> array(
                				'radius'=> 10
            				),
                    'data'	=>  $data_primary,
                    'turboThreshold' => 0
                );
            }
        }
        $data['series']  = json_encode($series);
        $data['categories']  = json_encode($categories);
        $data['kpi_name'] = $kpi_name;
        $data['xaxes_name'] = $xaxes_name;
        $data['yaxes_name'] = $yaxes_name;
        $data['xaxes_symbel'] = $xaxes_symbel;
        $data['yaxes_symbel'] = $yaxes_symbel;

        if (ENVIRONMENT != "production") {
            $data['url_api'] = $this->api->url;
        }

        return $data;
    }

    private function getcurrencyStrength($c){
        if($c >= pow(10, 12)){
            return '($T)';
        }elseif ($c < pow(10, 12) && $c >= pow(10, 9)){
            return '($B)';
        }elseif ($c < pow(10, 9) && $c >= pow(10, 6)){
            return '($M)';
        }elseif ($c < pow(10, 6) && $c >= pow(10, 3)){
            return '($K)';
        }else{
            return '';
        }
    }
}