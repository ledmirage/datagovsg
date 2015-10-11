<?php

namespace Ledmirage\Datagovsg;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Exception;

class Lta
{

    private $api_key;
    private $base_url;
    private $http_client;
    private $methods;

    public function __construct()
    {
        // constructor body
        // get the nea key from config
        $this->api_key     = config('datagovsg.lta-account-key');
        $this->api_user_id = config('datagovsg.lta-unique-user-id');
        $this->http_client = new \GuzzleHttp\Client();
        $this->base_url    = array();
        $this->methods     = array();

        $this->base_url['bus_arrival']            = "http://datamall2.mytransport.sg/ltaodataservice/BusArrival";
        $this->base_url['bus_routes_sbs']         = "http://datamall.mytransport.sg/ltaodataservice.svc/SBSTRouteSet";
        $this->base_url['bus_routes_smrt']        = "http://datamall.mytransport.sg/ltaodataservice.svc/SMRTRouteSet";
        $this->base_url['bus_info_sbst']          = "http://datamall.mytransport.sg/ltaodataservice.svc/SBSTInfoSet";
        $this->base_url['bus_info_sbst']          = "http://datamall.mytransport.sg/ltaodataservice.svc/SMRTInfoSet";
        $this->base_url['bus_stops']              = "http://datamall.mytransport.sg/ltaodataservice.svc/BusStopCodeSet";
        $this->base_url['taxi_availability']      = "http://datamall2.mytransport.sg/ltaodataservice/TaxiAvailability";
        $this->base_url['carpark_availability']   = "http://datamall.mytransport.sg/ltaodataservice.svc/CarParkSet";
        $this->base_url['erp_rates']              = "http://datamall.mytransport.sg/ltaodataservice.svc/ERPRateSet";
        $this->base_url['estimated_travel_times'] = "http://datamall.mytransport.sg/ltaodataservice.svc/TravelTimeSet";
        $this->base_url['road_openings']          = "http://datamall.mytransport.sg/ltaodataservice.svc/PlannedRoadOpeningSet";
        $this->base_url['road_works']             = "http://datamall.mytransport.sg/ltaodataservice.svc/RoadWorkSet";
        $this->base_url['traffic_images']         = "http://datamall.mytransport.sg/ltaodataservice.svc/CameraImageSet";
        $this->base_url['traffic_incidents']      = "http://datamall.mytransport.sg/ltaodataservice.svc/IncidentSet";
        $this->base_url['traffic_speed_bands']    = "http://datamall.mytransport.sg/ltaodataservice.svc/TrafficSpeedBandSet";
        $this->base_url['vms_emas']               = "http://datamall.mytransport.sg/ltaodataservice.svc/VMSSet";
    }

    /**
     *
     * @param string $service
     * @param array $parameters
     * @param string $type
     *
     * @return Guzzle request object $request
     */

    private function prepare_request($service, $parameters = '', $type = 'json')
    {
        // exception if service is not defined
        if (empty($this->base_url[$service])) {
            throw new Exception("LTA - no such service: {$service}", 1);
        }

        //force to json if it's not xml
        if ($type!='xml') {
            $accept_type = 'application/json';
        } else {
            $accept_type = 'application/atom+xml';
        }

        // prepare credential and response type headers
        $headers = [
            'AccountKey'   => $this->api_key,
            'UniqueUserID' => $this->api_user_id,
            'accept'       => $accept_type,
        ];

        $query_string = http_build_query($parameters);
        $request = new Request('GET', $this->base_url[$service] . '?' . $query_string, $headers, '');

        return $request;
    }

    /**
     * API that returns Bus Arrival information for Bus Services at a queried Bus Stop,
     * including: Estimated Time of Arrival, Load info (i.e. how crowded the bus is), and
     * if the bus is wheel-chair accessible (WAB).
     * @param  string $bus_stop_id Bus stop reference code
     * @param  string $service_no  option bus service number
     * @param  string $type        response format, json or xml
     * @return Psr\Http\Message\StreamInterface $bodyObject
     */
    public function busArrival($bus_stop_id, $service_no = "", $type='json')
    {
        $request = $this->prepare_request(
            'bus_arrival',
            [
                'BusStopID'=>$bus_stop_id,
                'ServiceNo'=>$service_no,
            ], $type);

        $response = $this->http_client->send ($request);
        return $response->getBody();
    }

    /**
     * return busArrival in Json format
     * @param  string $bus_stop_id Bus stop reference code
     * @param  string $service_no  option bus service number
     * @return json encoded $object
     */
    public function busArrivalJson($bus_stop_id, $service_no = "")
    {
        return $this->busArrival ($bus_stop_id, $service_no, "json")->getContents();
    }

    /**
     * return busArrival in XML format
     * @param  string $bus_stop_id Bus stop reference code
     * @param  string $service_no  option bus service number
     * @return simpleXML $object
     */
    public function busArrivalXml($bus_stop_id, $service_no = "")
    {
        return simplexml_load_string(
            $this->busArrival ($bus_stop_id, $service_no, "xml")->getContents()
        );
    }
}
