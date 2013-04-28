<?php
/**
 * User: Zdenek
 * Date: 28/04/13
 * Time: 12:03
 */

namespace Application\Model;


use Zend\Http\Client;

class SearchExpedia {

    private $serviceManager;

    public function __construct($serviceManager){

        $this->serviceManager = $serviceManager;
    }

    public function search($query){

        $config  = $this->serviceManager->get('Configuration');
        $key = $config['mmyql']['expedia_key'];

        $client = new Client('http://api.ean.com/ean-services/rs/hotel/v3/list');
        $client->setParameterGet(array(
            'destinationString'  => $query,
            'apiKey'   => $key
        ));

        $response = $client->send();
        $responseArray = json_decode($response->getBody());

        if ($response->isSuccess()) {
            if(isset($responseArray->HotelListResponse->HotelList->HotelSummary))
                return $responseArray->HotelListResponse->HotelList->HotelSummary;
            else
                return false;

        }
        else{
            throw new \Exception('Error - Response code: '.$response->getStatusCode());
        }

    }
}