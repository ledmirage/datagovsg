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
        $this->base_url_psi_update = "http://www.nea.gov.sg/api/WebAPI?dataset=psi_update&keyref=";
        $this->http_client = new \GuzzleHttp\Client();
    }

    /**
     *
     * @param none
     *
     * @return PSI data as Guzzle respone object
     */
    public function psiFetch()
    {
        $query_url = $this->base_url_psi_update . $this->api_key;

        try {
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
