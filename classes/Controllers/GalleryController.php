<?php
class GalleryController extends Controller {
    /**
    * Get and process page data
    * @param array arguments from query string
    */
    public function pageAction()
    {
        if (!empty($this->params['page'])) {
            $page   = $this->params['page'];
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
    */
    public function itemAction()
    {
        if (!empty($this->params['page']) && !empty($this->params['item'])) {
            $page = $this->params['page'];
            $item = $this->params['item'];
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