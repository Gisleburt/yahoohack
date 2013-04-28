<?php
/**
 * User: Zdenek
 * Date: 27/04/13
 * Time: 15:58
 */

namespace Application\Controller;


use Application\Model\Search;
use Application\Model\SearchExpedia;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class SearchController extends AbstractActionController {

    public function searchAction(){

        $view = new JsonModel();

        $module = $this->params()->fromQuery('m');
        $query = $this->params()->fromQuery('q');

        if(!$module){
            $view->setVariable('error','missing parameter module');
            return $view;
        }

        $search = new Search($this->getServiceLocator());

        switch($module){
            case 'location':

                if($resultArray = $search->searchLocation($query)){
                        $view->setVariable('result',$resultArray);
                    }
                else{
                    $view->setVariable('error','no data found');
                }
            break;
            case 'flickr':

                if($resultArray = $search->searchFlickr($query)){
                    $view->setVariable('result',$resultArray);
                }
                else{
                    $view->setVariable('error','no data found');
                }

            break;
            case 'expedia_hotels':
                $expediaSearch = new SearchExpedia($this->getServiceLocator());

                if($resultArray = $expediaSearch->search($query)){
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