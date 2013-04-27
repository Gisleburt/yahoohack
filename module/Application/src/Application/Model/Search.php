<?php

namespace Application\Model;
/**
 * User: Zdenek
 * Date: 27/04/13
 * Time: 20:08
 */

class Search {

    private $serviceManager;
    private $serviceYQL;

    public function __construct($serviceManager){

        $this->serviceManager = $serviceManager;
        $this->serviceYQL = $this->serviceManager->get('serviceYQL');

    }

    public function searchLocation($query){

        $select = "latitude, longitude, radius";
        $from = "geo.placefinder";
        $where = "text=\"{$query}\"";

        $response = $this->serviceYQL->executeQuery($select, $from, $where);
        $responseArray = json_decode($response);
        if(isset($responseArray->query->results->Result)){
            $result = $responseArray->query->results->Result;
            $resultArray = array('coordinates' => array('latitude' => $result->latitude, 'longitude' => $result->longitude, 'radius' => $result->radius));
            return $resultArray;
        }
        else{
            return false;
        }

    }

    public function searchFlickr($lat, $lon){

        $config  = $this->serviceManager->get('Configuration');
        $api_key =  $config['mmyql']['flickr_key'];

        if(!$api_key) throw new \Exception('missing api key');

        $lat = round($lat, 4);
        $lon = round($lon, 4);

        $select = "id,title";
        $from = "flickr.photos.search";
        $where = "lat=\"{$lat}\" and lon=\"{$lon}\" AND api_key=\"{$api_key}\" limit 10";

        //to get titles and IDs
        $response = $this->serviceYQL->executeQuery($select, $from, $where);
        $responseArray = json_decode($response);

        if(isset($responseArray->query->results->photo)){
            $photos = $result = $responseArray->query->results->photo;

            $idString = '';
            $photosArray = array();
            foreach($photos as $photo){
                $idString .= '"'.$photo->id.'",';
            }
            $idString = substr($idString, 0, -1);

            //second select for thumbnails
            $select = "*";
            $from = "flickr.photos.sizes";
            $where = "photo_id in ({$idString}) AND api_key=\"{$api_key}\"  AND label=\"Thumbnail\" limit 10";

            $response = $this->serviceYQL->executeQuery($select, $from, $where);
            $responseArray = json_decode($response);

            if(isset($responseArray->query->results->size)){
                $sizes = $result = $responseArray->query->results->size;
               $i = 0;
                foreach($photos as $photo){
                    $sizes[$i]->title = $photo->title;
                    $sizes[$i]->id = $photo->id;
                }

                return $sizes;
            }
            else{
                return false;
            }

        }
        else{
            return false;
        }

    }
}