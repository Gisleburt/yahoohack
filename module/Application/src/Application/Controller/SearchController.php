<?php
/**
 * User: Zdenek
 * Date: 27/04/13
 * Time: 15:58
 */

namespace Application\Controller;


use Application\Model\Search;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class SearchController extends AbstractActionController {

    public function searchAction(){

        $view = new JsonModel();


        $module = $this->params()->fromQuery('m');

        if(!$module){
            $view->setVariable('error','missing parameter module');
            return $view;
        }

        $search = new Search($this->getServiceLocator());

        switch($module){
            case 'location':
                $query = $this->params()->fromQuery('q');
                if(!$query){
                    $view->setVariable('error','missing parameter q');
                    return $view;
                }

                if($resultArray = $search->searchLocation($query)){
                        $view->setVariable('result',$resultArray);
                    }
                else{
                    $view->setVariable('error','no data found');
                }
            break;
            case 'flickr':
                $lat = $this->params()->fromQuery('lat');
                $lon = $this->params()->fromQuery('lon');
                if(!$lat || !$lon){
                    $view->setVariable('error','missing parameter lat or lon');
                    return $view;
                }

                if($resultArray = $search->searchFlickr($lat, $lon)){
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