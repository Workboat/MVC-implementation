<?php
use Models\Model;

class FormController extends Controller
{
    /**
    * Show shipping form
    */
    public function shippingAction()
    {
        $html = $this->loadView($_POST);
        $this->getResult($html);
    }
    
    /**
    * Pass variables to the view
    * @param array
    */
    private function loadView($params)
    {
        $html = $this->model->htmlLoader($this->actionName);
        $html = View::setViewArgs($html, $params);
        $html = $this->model->createTemplatePage($html);
        return $html;
    }
    
    /**
    * Draw the result html
    * @param string 
    */
    protected function getResult($res = null) {
        echo $res;
    }
}