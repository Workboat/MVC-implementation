<?php 
class View
{
    /**
    * The recursive function which setting up view variables
    * passed from a controller
    * @param string $html  
    * @param array $params
    * @param string $padding if an array found in $params
    */
    static function setViewArgs($html, $params, $padding = null)
    {
        // if recursive call
        if (!is_null($padding)) {
            $inner = '';
            foreach ($params as $key => $value) {
                $inner .= $value;
            }
            $html = str_replace($padding, $inner, $html);
        } else {
            $keyArr = [];
            $valArr = [];
            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    $html = $this->setViewArgs($html, $value, $key);
                } else {
                    $keyArr[] = '{' . $key . '}';
                    $valArr[] = $value;
                }
                echo "key = $key   |  value = $value </br>";
            }
            $html = str_replace($keyArr, $valArr, $html);
        }
        return $html;
    }
    
    /**
    * Pass parameters from the pervious form
    * @param array
    */
    /*static function createHiddenFields($fields = is_null){
        echo "Static from abstarct class";
        die();
    }*/
}