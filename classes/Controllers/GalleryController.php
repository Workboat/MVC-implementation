<?php
class GalleryController extends Controller {
    
    /**
    * Get and process page data
    * @param array arguments from query string
    */
    public function pageAction($query)
    {
        if (!empty($query['page'])) {
            $page   = $query['page'];
            $path   = '/' . $page;
            $html   = $this->model->getPage($path);
            $this->getResult($html);
        } else {
            // Handle empty or wrong request
            echo "No page selected!";
        }
    }
    
    /**
    * Get and process specific gallery item data
    * @param array arguments from query string
    */
    public function itemAction($query)
    {
        if (!empty($query['page']) && !empty($query['item'])) {
            $page = $query['page'];
            $item = $query['item'];
            $path = '/'.$page.'/'.$item;
            $html = $this->model->getItem($path);
            $this->getResult($html);
        } else {
            // Handle empty or wrong request
            echo "No page or item selected!";
        }
    }
    
    /**
    * Print result
    * @param html
    */
    protected function getResult($result = null)
    {
        echo $result;
    }
}