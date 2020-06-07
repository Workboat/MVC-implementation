<?php
use Models\Model;

class FormController extends Controller
{
    /**
    * Show shipping form
    */
    public function shippingAction()
    {
        $summ     = $this->params['summ'];
        $tax      = $this->params['tax'];
        $taxSumm  = round($summ * $tax, 2);
        $shipping = $this->params['shipping'];
        $total    = $taxSumm + $shipping;
        $params   = array_merge($_POST, ['total' => $total,
                                         'taxes-summ' => $taxSumm]);
        $html     = $this->loadView($params);
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