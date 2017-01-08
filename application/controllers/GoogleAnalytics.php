<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// load google api client
require_once APPPATH . 'libraries/google-api-php-client/src/Google/autoload.php';
        
class GoogleAnalytics extends CI_Controller {

    /**
    * Index Page for this controller.
    *
    * Maps to the following URL
    * 		http://example.com/index.php/welcome
    *	- or -
    * 		http://example.com/index.php/welcome/index
    *	- or -
    * Since this controller is set as the default controller in
    * config/routes.php, it's displayed at http://example.com/
    *
    * So any other public methods not prefixed with an underscore will
    * map to /index.php/welcome/<method_name>
    * @see http://codeigniter.com/user_guide/general/urls.html
    */

    private $_analytics;
    private $_client;

	public function main()
	{
		// 初期処理
		$this->_initialize();
		// 処理開始
		$this->_run();
	}

	private function _initialize()
    {
        // load key
        //$key = file_get_contents( APPPATH . 'libraries/google-api-php-client/samurai-mall-analytics-project-04b9806ab180.p12');
        $key = file_get_contents( APPPATH . 'libraries/google-api-php-client/samurai-analytics-4c231140db65.p12');

        // crate google client
        $this->_client = new Google_Client();
        $this->_analytics = new Google_Service_Analytics($this->_client);

        // create credentioal
        $credentioal = new Google_Auth_AssertionCredentials(
            $this->config->item('ga_service_account_email'),
            [ Google_Service_Analytics::ANALYTICS_READONLY, ],
            $key
        );
        $this->_client->setAssertionCredentials($credentioal);
        if ($this->_client->getAuth()->isAccessTokenExpired()) {
            $this->_client->getAuth()->refreshTokenWithAssertion($credentioal);
        }

	}

	private function _run()
    {
        $result = $this->_analytics->data_ga->get(
            'ga:' . $this->config->item('ga_profile_id'),
            '2016-11-01',
            '2017-01-03',
            'ga:sessions, ga:pageviews, ga:bounces',
            [ 
                'dimensions'    => 'ga:pagePath, ga:pageTitle', 
                'max-results'   => 10000, 
            ]
     
        );
        
        var_dump($result);
	}
}
