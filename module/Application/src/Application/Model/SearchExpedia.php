<?php
/**
 * User: Zdenek
 * Date: 28/04/13
 * Time: 12:03
 */

namespace Application\Model;


use Zend\Http\Client;
use Zend\Stdlib\Hydrator;

class SearchExpedia {

    private $serviceManager;

    public function __construct($serviceManager){

        $this->serviceManager = $serviceManager;
    }

    public function search($query, $limit = 10){

        $config  = $this->serviceManager->get('Configuration');
        $key = $config['mmyql']['expedia_key'];

        $client = new Client('http://api.ean.com/ean-services/rs/hotel/v3/list');
        $client->setParameterGet(array(
            'destinationString'  => $query,
            'apiKey'   => $key,
            'numberOfResults' => $limit,
        ));

        $response = $client->send();

        if ($response->isSuccess()) {
            $responseArray = json_decode($response->getBody());

            if(isset($responseArray->HotelListResponse->HotelList->HotelSummary)){
                $results =  $responseArray->HotelListResponse->HotelList->HotelSummary;
                $resultArray = array();

                $i = 0;
                foreach($results as $result){

                    $resultObject = new SearchResult();
                    $resultObject->name = $result->name;
                    $resultObject->latitude = $result->latitude;
                    $resultObject->longitude = $result->longitude;
                    $resultObject->thumbnail = 'http://images.travelnow.com'.$result->thumbNailUrl;
                    $resultObject->url = $result->deepLink;

                    $resultArray[] = $resultObject;
                    $i++;

                    if($i == 10) return $resultArray;;
                }


            }
            return false;
        }
        else{
            throw new \Exception('Error - Response code: '.$response->getStatusCode());
        }

    }
}