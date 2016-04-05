<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 10/7/2015
 * Time: 4:27 PM
 */
class termResults_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        //$this->mongo_db->set_database('database');

        $this->load->model('companies_model');
        $this->load->model('reporting_periods_model', 'rperiods');
        $this->load->model('api_model', 'api');

    }

    public function load_companies_api($companies_id = array(), $kpis = array(), $period = array()) {

        $this->benchmark->mark('termResults_model-load_companies_api_start');

        $entityID = array_to_string($companies_id);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($period);

        $result = $this->api->get_termResults_data($entityID, $termID, $FYFQ);

        $all_companies = array();

        if(empty($result) || !is_array($result))
            return $all_companies;

        $c1 = array();
        foreach ($companies_id as $_company) {

            $c1["entityID"] = $_company;
            $obj = $this->companies_model->get_by_id($_company);
            $c1['company_name'] = $obj->company_name;
            $c1['drilldown'] = array();

            foreach ($kpis as $kpi) {
                $found = false;
                foreach ($result as $r) {
                    if(empty($r['entityID']) && !empty($r['entityId']))
                        $r['entityID'] = $r['entityId'];
                    if(empty($r['termID']) && !empty($r['termId']))
                        $r['termID'] = $r['termId'];
                    if(empty($r['value']) && !empty($r['amount']))
                        $r['value'] = $r['amount'];
                    if (!empty($r['entityID']) && !empty($r['termID']) && $_company == $r['entityID'] && $kpi == $r['termID']) {
                        if(!empty($r['error']))
                            $c1['error'] = $r['error'];

                        if(!empty($r['value']))
                            $c1[(string)$r['termID']] = $r['value'];
                        else
                            $c1[(string)$r['termID']] = '';

                        //echo 'company: '.$_company.'/'.$r['entityID'].' - kpi: '.$kpi.'/'.$r['termID'].' - value: '.$c1[$r['termID']].' <br/>';

                        $info = array();

                        if ((isset($r['dimensionalFacts']) && count($r['dimensionalFacts']) > 0) ||
                            (isset($r['dimData']) && $r['dimData'] == 'true')) {    // dimData is only provided by curl - php provides the dimensionalFacts array
                            $c1['drilldown'][] = (string)$r['termID'];
                        }

                        $info['entity_id'] = $obj->entity_id;
                        $info['cik'] = $obj->cik;
                        $info['company_name'] = $obj->company_name;
                        $info['industry'] = $obj->industry;
                        $info['sector'] = $obj->sector;
                        $info['sic'] = $obj->sic;
                        $info['sic_code'] = $obj->sic_code;
                        $info['state'] = $obj->state;
                        $info['stock_symbol'] = $obj->stock_symbol;
                        $c1['info'] = $info;
                        $found = true;

                        break;
                    }
                }
                if (!$found) {
                    $info = array();
                    $info['entity_id'] = $obj->entity_id;
                    $info['cik'] = $obj->cik;
                    $info['company_name'] = $obj->company_name;
                    $info['industry'] = $obj->industry;
                    $info['sector'] = $obj->sector;
                    $info['sic'] = $obj->sic;
                    $info['sic_code'] = $obj->sic_code;
                    $info['state'] = $obj->state;
                    $info['stock_symbol'] = $obj->stock_symbol;
                    $c1['info'] = $info;
                    $c1[$kpi] = '';
                }
            }
            $all_companies[] = $c1;
            //$all_companies['data'] = $c1;
        }

        $this->benchmark->mark('termResults_model-load_companies_api_end');

        log_message('debug', 'termResults_model-load_companies_api: ' . $this->benchmark->elapsed_time('termResults_model-load_companies_api_start', 'termResults_model-load_companies_api_end'));

        //$dataSources = $this->datasources($result);

        //$all_companies['datasources'] = $dataSources;

        //var_dump($all_companies[1]);

        //if (ENVIRONMENT != "production") {
        //    $data['url_api'] = $this->api->url;
        //}

        return $all_companies;
    }

    public function get_companies_kpis_values($companies_id = array(), $kpis = array(), $period = array()) {

        $this->benchmark->mark('termResults_model-get_companies_kpis_values_start');

        $entityID = array_to_string($companies_id);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($period);

        $result = $this->api->get_termResults_data($entityID, $termID, $FYFQ);

        $all_companies = array();
        $c1 = array();
        foreach ($companies_id as $_company) {
            $c1["entityID"] = $_company;
            $obj = $this->companies_model->get_by_id($_company);
            $c1['company_name'] = $obj->company_name;

            foreach ($kpis as $kpi) {
                $found = false;
                foreach ($result as $r) {
                    if(empty($r['entityID']) && !empty($r['entityId']))
                        $r['entityID'] = $r['entityId'];
                    if(empty($r['termID']) && !empty($r['termId']))
                        $r['termID'] = $r['termId'];
                    if(empty($r['value']) && !empty($r['amount']))
                        $r['value'] = $r['amount'];
                    if (!empty($r['entityID']) && !empty($r['termID']) && $_company == $r['entityID'] && $kpi == $r['termID']) {
                        if(!empty($r['error']))
                            $c1['error'] = $r['error'];

                        if(!empty($r['value']))
                            $c1[(string)$r['termID']] = $r['value'];
                        else
                            $c1[(string)$r['termID']] = '';

                        $info = array();
                        $info['entity_id'] = $obj->entity_id;
                        $info['cik'] = $obj->cik;
                        $info['company_name'] = $obj->company_name;
                        $info['industry'] = $obj->industry;
                        $info['sector'] = $obj->sector;
                        $info['sic'] = $obj->sic;
                        $info['sic_code'] = $obj->sic_code;
                        $info['state'] = $obj->state;
                        $info['stock_symbol'] = $obj->stock_symbol;
                        $c1['info'] = $info;
                        $found = true;

                        break;
                    }
                }
                if (!$found) {
                    $info = array();
                    $info['entity_id'] = $obj->entity_id;
                    $info['cik'] = $obj->cik;
                    $info['company_name'] = $obj->company_name;
                    $info['industry'] = $obj->industry;
                    $info['sector'] = $obj->sector;
                    $info['sic'] = $obj->sic;
                    $info['sic_code'] = $obj->sic_code;
                    $info['state'] = $obj->state;
                    $info['stock_symbol'] = $obj->stock_symbol;
                    $c1['info'] = $info;
                    $c1[$kpi] = '';
                }
            }
            $all_companies[] = $c1;
        }

        $this->benchmark->mark('termResults-get_companies_kpis_values_end');

        log_message('debug', 'termResults-get_companies_kpis_values: ' . $this->benchmark->elapsed_time('termResults-get_companies_kpis_values_start', 'termResults-get_companies_kpis_values_end'));

        return $all_companies;
    }

    public function get_companies_kpis_values_all_periods($companies_id = array(), $kpis = array(), $segments = 'year') {

        $this->benchmark->mark('termResults_model-get_companies_kpis_values_all_periods_start');

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


        $entityID = array_to_string($companies_id);
        $termID   = array_to_string($kpis);
        $FYFQ     = array_to_string($periods);

        $result = $this->api->get_termResults_data($entityID, $termID, $FYFQ);

        $all_companies = array();
        $c1 = array();
        foreach ($companies_id as $_company) {
            $c1["entityID"] = $_company;
            $obj = $this->companies_model->get_by_id($_company);
            $c1['company_name'] = $obj->company_name;
            foreach ($periods as $period)
            {
                foreach ($kpis as $kpi) {
                    $found = false;
                    $key = '';
                    $c2 = array();
                    foreach ($result as $k => $r) {
                        if(empty($r['entityID']) && !empty($r['entityId']))
                            $r['entityID'] = $r['entityId'];
                        if(empty($r['termID']) && !empty($r['termId']))
                            $r['termID'] = $r['termId'];
                        if(empty($r['value']) && !empty($r['amount']))
                            $r['value'] = $r['amount'];

                        if (empty($r['FYFQ'])) {
                            if ($r['FQ'] !== 'FY') {
                                $r['FYFQ'] = $r['FY'] . $r['FQ'];
                            }
                            else {
                                $r['FYFQ'] = $r['FY'];
                            }
                        }
                        if (!empty($r['entityID']) && !empty($r['termID']) && $_company == $r['entityID'] && $kpi == $r['termID'] && ($period == $r['FYFQ'] || $period == $r['FY'])) {
                            if(!empty($r['error']))
                                $c2['error'] = $r['error'];

                            if(!empty($r['value']))
                                $c2[(string)$r['termID']] = floatval($r['value']);
                            else
                                $c2[(string)$r['termID']] = '';

                            $found = true;
                            $key = $k;

                            break;
                        }
                    }
                    if (!$found) {
                        $c2[$kpi] = '';
                    }
                    foreach ($c2 as $k => $v) {
                        $c1[$period][$k] = $v;
                    }
                    if(!empty($key))
                    {
                        unset($result[$key]);
                        $result = array_values($result);
                    }
                }
            }
            $all_companies[] = $c1;
        }

        $this->benchmark->mark('termResults_model-get_companies_kpis_values_all_periods_end');

        log_message('debug', 'get_companies_kpis_values_all_periods: ' . $this->benchmark->elapsed_time('termResults_model-get_companies_kpis_values_all_periods_start', 'termResults_model-get_companies_kpis_values_all_periods_end'));

        return $all_companies;
    }

    public function get_company_kpis_values($company, $kpis = array(), $segments = 'year') {

        $this->benchmark->mark('termResults_model-get_company_kpis_values_start');

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

        $result = $this->api->get_termResults_data($entityID, $termID, $FYFQ, TRUE);

        $return_result = array();

        $return_result["entityID"] = $company;
        $obj = $this->companies_model->get_by_id($company);
        $return_result['company_name'] = $obj->company_name;
        $data = array();

        foreach ($kpis as $kpi) {
            $k = array();
            $k['kpi'] = $kpi;
            $k['vals'] = array();
            foreach ($periods as $period) {
                $found = false;
                $key = '';
                $c2 = '';
                foreach ($result as $e => $r) {
                    if(empty($r['entityID']) && !empty($r['entityId']))
                        $r['entityID'] = $r['entityId'];
                    if(empty($r['termID']) && !empty($r['termId']))
                        $r['termID'] = $r['termId'];
                    if(empty($r['value']) && !empty($r['amount']))
                        $r['value'] = $r['amount'];

                    if (empty($r['FYFQ'])) {
                        if ($r['FQ'] !== 'FY') {
                            $r['FYFQ'] = $r['FY'] . $r['FQ'];
                        }
                        else {
                            $r['FYFQ'] = $r['FY'];
                        }
                    }

                    if (!empty($r['entityID']) && !empty($r['termID']) && $company == $r['entityID'] && $kpi == $r['termID'] && ($period == $r['FYFQ'] || $period == $r['FY'])) {
                        if(!empty($r['value']))
                            $c2 = floatval($r['value']);
                        else
                            $c2 = '';

                        $found = true;
                        $key = $e;

                        break;
                    }
                }
                if (!$found) {
                    $c2 = '';
                }
                if(!empty($key)) {
                    unset($result[$key]);
                    $result = array_values($result);
                }
                $k['vals'][] = $c2;
            }
            $data[] = $k;
        }
        $return_result['data'] = $data;

        $this->benchmark->mark('termResults_model-get_company_kpis_values_end');

        log_message('debug', 'get_company_kpis_values: ' . $this->benchmark->elapsed_time('Api_model-get_company_kpis_values_start', 'Api_model-get_company_kpis_values_end'));

        return $return_result;
    }

}