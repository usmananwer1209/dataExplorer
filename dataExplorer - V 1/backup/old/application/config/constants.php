<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('PAGE_CONTET_COUNT',24);
define('PRIVATE_COMPANY_INDICATOR','C');
define('IDACITI_TOKEN', 'oepsy3b6');


if (defined('ENVIRONMENT'))
{
    switch (ENVIRONMENT)
    {
        case 'production':
            define('REST_API_URL','http://data.idaciti.com/api/');
            break;

        default:
            define('REST_API_URL','http://data.idaciti.com:81/api/');
    }
}

define('REST_API_TERM_RESULTS_BY_ENTITY_ID', REST_API_URL . 'termResult/json?token={0}&entities={1}&terms={2}&periods={3}');
define('REST_API_TERM_RESULTS_DRILLDOWN', REST_API_URL . 'termResult/drilldown/json?token={0}&entities={1}&term={2}&period={3}');
define('REST_API_ENTITY', REST_API_URL . 'entity/json?token={0}');
define('REST_API_TERM_RULE', REST_API_URL . 'termRule/json?token={0}');
define('REST_API_BENCHMARK_TRENDING', REST_API_URL . 'benchmark/trending/json?token={0}&benchmarkEntity={1}&peerEntities={2}&term={3}&calcType={4}&periods={5}');
define('REST_API_BENCHMARK_COMMONSIZE', REST_API_URL . 'benchmark/commonSize/json?token={0}&benchmarkEntity={1}&peerEntities={2}&reportType={3}&calcType={4}&period={5}');
define('REST_API_BENCHMARK_SCATTERPLOT_INCLUDEALLPEERS', REST_API_URL . 'benchmark/scatterPlot/json?token={0}&benchmarkEntity={1}&includeAllPeers={2}&xAxisTermId={3}&yAxisTermId={4}&period={5}');
define('REST_API_BENCHMARK_SCATTERPLOT_PEERLIST', REST_API_URL . 'benchmark/scatterPlot/json?token={0}&benchmarkEntity={1}&peerEntities={2}&xAxisTermId={3}&yAxisTermId={4}&period={5}');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
