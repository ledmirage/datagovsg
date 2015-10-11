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
    private $methods;

    public function __construct()
    {
        // constructor body
        // get the nea key from config
        $this->api_key     = config('datagovsg.nea-key');
        $this->http_client = new \GuzzleHttp\Client();
        $this->base_url    = array();
        $this->methods     = array();

        // Nowcast
        $this->base_url['nowcast']            = "https://www.nea.gov.sg/api/WebAPI?dataset=nowcast&keyref=";

        // 12 hrs Forecast
        $this->base_url['12hrs_forecast']     = "https://www.nea.gov.sg/api/WebAPI?dataset=12hrs_forecast&keyref=";

        // 3 days Outlook
        $this->base_url['3days_outlook']      = "https://www.nea.gov.sg/api/WebAPI?dataset=3days_outlook&keyref=";

        // Heavy Rain Warning
        $this->base_url['heavy_rain_warning'] = "https://www.nea.gov.sg/api/WebAPI?dataset=heavy_rain_warning&keyref=";

        // Ultraviolet Index (UVI)
        $this->base_url['uvi']                = "https://www.nea.gov.sg/api/WebAPI?dataset=uvi&keyref=";

        // Earthquake Advisory
        $this->base_url['earthquake']         = "https://www.nea.gov.sg/api/WebAPI?dataset=earthquake&keyref=";

        // PSI Update
        $this->base_url['psi_update']         = "https://www.nea.gov.sg/api/WebAPI?dataset=psi_update&keyref=";

        // PM 2.5 Update
        $this->base_url['pm2.5_update']       = "https://www.nea.gov.sg/api/WebAPI?dataset=pm2.5_update&keyref=";


        // the following codes will create these functions

        // these return guzzle response object
        // nowcastFetch()
        // forecast12HrsFetch()
        // outlook3DaysFetch()
        // heaveyRainwarningFetch()
        // uviFetch()
        // earthquakeFetch()
        // psiFetch()
        // pm25UpdateFetch()

        // these return simplexml object
        // nowcastFetchXml()
        // forecast12HrsFetchXml()
        // outlook3DaysFetchXml()
        // heaveyRainwarningFetchXml()
        // uviFetchXml()
        // earthquakeFetchXml()
        // psiFetchXml()
        // pm25UpdateFetchXml()

        // these return json string
        // nowcastFetchJson()
        // forecast12HrsFetchJson()
        // outlook3DaysFetchJson()
        // heaveyRainwarningFetchJson()
        // uviFetchJson()
        // earthquakeFetchJson()
        // psiFetchJson()
        // pm25UpdateFetchJson()

        $api_type_to_function_mapping['nowcast']            = "nowcast";
        $api_type_to_function_mapping['12hrs_forecast']     = "forecast12Hrs";
        $api_type_to_function_mapping['3days_outlook']      = "outlook3Days";
        $api_type_to_function_mapping['heavy_rain_warning'] = "heavyRainWarning";
        $api_type_to_function_mapping['uvi']                = "uvi";
        $api_type_to_function_mapping['earthquake']         = "earthquake";
        $api_type_to_function_mapping['psi_update']         = "psi";
        $api_type_to_function_mapping['pm2.5_update']       = "pm25Update";

        foreach ($api_type_to_function_mapping as $type => $function_name) {
            $function_name = $function_name . "Fetch";
            $this->methods[$function_name] = \Closure::bind(function () use ($type) {
                return $this->Fetch($type);
            }, $this, get_class());

            $function_name_xml = $function_name . "Xml";
            $this->methods[$function_name_xml] = \Closure::bind(function () use ($type) {
                $simpleXml = simplexml_load_string($this->Fetch($type)->getContents());
                return ($simpleXml);
            }, $this, get_class());

            $function_name_json = $function_name . "Json";
            $this->methods[$function_name_json] = \Closure::bind(function () use ($type) {
                $json = json_encode(simplexml_load_string($this->Fetch($type)->getContents()));
                return ($json);
            }, $this, get_class());

        }
    }

    /**
     *
     * @param string $method
     * @param array $args
     *
     * @return depends on which method is called
     */

    function __call($method, $args)
    {
        if (is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $args);
        }
    }

    /**
     *
     * @param string $base_url
     * @param string $key
     *
     * @return Psr\Http\Message\StreamInterface $bodyObject
     */
    private function Fetch($api_type)
    {
        try {
            if (empty($this->base_url[$api_type])) {
                throw new Exception("Unknown api type - {$api_type}");
            }

            $query_url   = $this->base_url[$api_type] . $this->api_key;
            $response    = $this->http_client->get($query_url);
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
}
