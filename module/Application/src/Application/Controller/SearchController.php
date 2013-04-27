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

        $select = "*";
        $from = "flickr.photos.info";
        $where = "photo_id=2439864402 and api_key=\"\"";
        $response = $serviceYQL->executeQuery($select , $from, $where);

        $view->setVariable('response',$response);

        return $view;
    }

}