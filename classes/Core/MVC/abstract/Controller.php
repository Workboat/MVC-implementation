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
    
    function __construct($curName, $action)
    {
        $this->selfName   = $curName;
        $this->actionName = $action;
        $curModelName     = $curName . "Model";
        $this->model      = new $curModelName;
        $tmp              = array_merge($_GET, $_POST);
        $this->params     = $this->sanitizer($tmp);
        $this->doAction($action);
    }
    
    /**
    * Handle specific method
    * @param string $action name of file that will be loaded into
    * empty page
    * @param array $query query string params except action
    */
    private function doAction($action) {
        $action .= 'Action';
        $this->$action();
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