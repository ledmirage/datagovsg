<?php

namespace Ledmirage\Datagovsg;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Exception;

class Nea
{

    private $api_key;
    private $base_url;
    private $http_client;

    public function __construct()
    {
        // constructor body
        // get the nea key from config
        $this->api_key = config('datagovsg.nea-key');
        $this->http_client = new \GuzzleHttp\Client();

        $this->base_url = array();
        // Nowcast
        $this->base_url['nowcast'] = "http://www.nea.gov.sg/api/WebAPI?dataset=nowcast&keyref=";

        // 12 hrs Forecast
        $this->base_url['12hrs_forecast'] = "http://www.nea.gov.sg/api/WebAPI?dataset=12hrs_forecast&keyref=";

        // 3 days Outlook
        $this->base_url['3days_outlook'] = "http://www.nea.gov.sg/api/WebAPI?dataset=3days_outlook&keyref=";

        // Heavy Rain Warning
        $this->base_url['heavy_rain_warning'] = "http://www.nea.gov.sg/api/WebAPI?dataset=heavy_rain_warning&keyref=";

        // Ultraviolet Index (UVI)
        $this->base_url['uvi'] = "http://www.nea.gov.sg/api/WebAPI?dataset=uvi&keyref=";

        // Earthquake Advisory
        $this->base_url['earthquake'] = "http://www.nea.gov.sg/api/WebAPI?dataset=earthquake&keyref=";

        // PSI Update
        $this->base_url['psi_update'] = "http://www.nea.gov.sg/api/WebAPI?dataset=psi_update&keyref=";

        // PM 2.5 Update
        $this->base_url['pm2.5_update'] = "http://www.nea.gov.sg/api/WebAPI?dataset=pm2.5_update&keyref=";

    }

    /**
     *
     * @param string $base_url
     * @param string $key
     *
     * @return PSI data as Guzzle respone object
     */
    private function Fetch($api_type)
    {
        try {
            if (empty($this->base_url[$api_type])) {
                throw new Exception("Unknown api type - {$api_type}");
            }

            $query_url = $this->base_url[$api_type] . $this->api_key;
            $response = $this->http_client->get($query_url);
            $http_status = $response->getStatusCode();

            //only return response object if returned status is 200
            //else throw exception
            if ($http_status == "200") {
                return $response->getBody();
            } else {
                throw new Exception("Error Getting PSI data - HTTP {$http_status}");
            }

        } catch (Exception $e) {
            echo $e->getMessage(), "\n";
        }
        return null;
    }

    /**
     *
     * @param none
     *
     * @return PSI data as Guzzle respone object
     */
    public function psiFetch()
    {
        return $this->Fetch('psi_update');
    }

    /**
     *
     * @param none
     *
     * @return PSI data in XML object
     */

    public function psiFetchXml()
    {
        $simpleXml = simplexml_load_string($this->psiFetch()->getContents());
        return ($simpleXml);
    }

    /**
     *
     * @param none
     *
     * @return PSI data in Json string
     */

    public function psiFetchJson()
    {
        $json = json_encode($this->psiFetchXml());
        return ($json);
    }
}
