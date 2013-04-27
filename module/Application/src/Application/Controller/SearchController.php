<?php
/**
 * User: Zdenek
 * Date: 27/04/13
 * Time: 15:58
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class SearchController extends AbstractActionController {

    public function searchAction(){

        $view = new JsonModel();
        $serviceYQL = $this->getServiceLocator()->get('serviceYQL');


        $query = $this->params()->fromQuery('q');
        $module = $this->params()->fromQuery('m');

        if(!$query || !$module){
            $view->setVariable('error','missing parameter');
            return $view;
        }

        switch($module){
            case 'location':
                $select = "latitude, longitude, radius";
                $from = "geo.placefinder";
                $where = "text=\"{$query}\"";

                $response = $serviceYQL->executeQuery($select, $from, $where);
                $responseArray = json_decode($response);
                if(isset($responseArray->query->results->Result)){
                    $result = $responseArray->query->results->Result;

                    $resultArray = array('coordinates' => array('latitude' => $result->latitude, 'longitude' => $result->longitude, 'radius' => $result->radius));
                    $view->setVariable('result',$resultArray);
                    }
                else{
                    $view->setVariable('error','no data found');
                }
            break;
            default:
                $view->setVariable('error','invalid module');
                return $view;
            break;
        }

        return $view;
    }

}