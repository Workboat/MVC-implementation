<?php
/**
 * @abstract controller
 * @author hard.worker.man 
 */
abstract class Controller
{
    abstract protected function getResult();
    
    /**
    * Sanitized parameters
    * @var array
    */
    protected $params;
    
    /**
    * Controllers model
    * @var Model
    */
    protected $model;
    
    /**
    * Current controler name
    * @var string
    */
    protected $selfName;
    
    /**
    * Current action name
    * @var string
    */
    protected $actionName;
    
    function __construct($curName, $action, $query)
    {
        $this->selfName   = $curName;
        $this->actionName = $action;
        $curModelName     = $curName . "Model";
        $this->model      = new $curModelName;
        $this->params     = $this->sanitizer($query);
        $this->doAction($action, $query);
    }
    
    /**
    * Handle specific method
    * @param string $action name of file that will be loaded into
    * empty page
    * @param array $query query string params except action
    */
    private function doAction($action, $query) {
        $action .= 'Action';
        $this->$action($query);
    }
    
    /**
    * Check if ajax request
    * @return boolean
    */
    protected function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
    
    /**
    * XSS prevention
    * @param array $elements array like $_GET or $_POST
    */
    private function sanitizer($elements)
    {
        $elements = array_map('htmlspecialchars', $elements);
        return $elements;
    }
}