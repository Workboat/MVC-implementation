<?php
class Router {
    
    function __construct()
    {
        $query = $_GET;
        if (!empty($query['controller']) && !empty($query['action'])) {
            $controller = $query['controller'];
            $action     = $query['action'];
            unset($query['controller'], $query['action']);
            $this->useController($controller, $action);
       }
    }
    
    /**
    * Route to controllers action with params
    * @param string $controller name of controllers
    * @param string $action name of action`
    * @param array $params $_GET arguments
    */
    private function useController($controller, $action)
    {
        $controller     = ucfirst($controller);
        $controllerName = $controller . 'Controller';
        $controllerObj  = new $controllerName($controller, $action);
    }

}
