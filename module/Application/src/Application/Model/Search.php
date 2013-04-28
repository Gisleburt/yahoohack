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

        $select = "*";
        $from = "flickr.photos.search";
        $where = "lat=\"{$lat}\" and lon=\"{$lon}\" AND api_key=\"{$api_key}\" limit 10";

        //to get titles and IDs
        $response = $this->serviceYQL->executeQuery($select, $from, $where);
        $responseArray = json_decode($response);

        if(isset($responseArray->query->results->photo)){
            $photos = $result = $responseArray->query->results->photo;

            $idString = '';
			/** @var SearchResult[] $results */
			$results = array();
            foreach($photos as $photo){
                $results[$photo->id] = new SearchResult(array(
					'name' => $photo->title,
				));
				$idString .= '"'.$photo->id.'",';
            }

            $idString = substr($idString, 0, -1);

            //second select for thumbnails
            $select = "source";
            $from = "flickr.photos.sizes";
            $where = "photo_id in ({$idString}) AND api_key=\"{$api_key}\"  AND label=\"Thumbnail\" ";

            $response = $this->serviceYQL->executeQuery($select, $from, $where);
            $responseArray = json_decode($response);

            if(isset($responseArray->query->results)){

                foreach($responseArray->query->results->size as $photo){
					foreach($results as $id => &$result) {
						if(strpos($photo->source, (string)$id) !== false) {
							//var_dump($photo);
							$result->setValues(array(
								'thumbnail' => $photo->source,
							));
						}
					}
                }

                //third select for lat, lon, url
                $select = "id,location,urls";
                $from = "flickr.photos.info";
                $where = "photo_id in ({$idString}) AND api_key=\"{$api_key}\" limit 10";

                $response = $this->serviceYQL->executeQuery($select, $from, $where);
                $responseArray = json_decode($response);

                if(isset($responseArray->query->results)){

                    foreach($responseArray->query->results->photo as $photo){
                        if(isset($results[$photo->id])){
                            $results[$photo->id]->setValues(array(
                                'latitude' => $photo->location->latitude,
                                'longitude' => $photo->location->longitude,
                                'url' => (isset($photo->urls->url) ? $photo->urls->url->content : ''),
                            ));
                        }
                    }
                }

                return $results;
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