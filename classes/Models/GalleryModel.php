<?php
use Models\Model;

class GalleryModel extends Model
{
    /**
    * Set initial params
    */
    function __construct()
    {
        $params          = $this->loadParams();
        $this->tax       = $params['tax'];
        $this->price     = $params['price'];
        $this->shipping  = $params['shipping'];
        $this->instPrice = $params['installation'];
    }
    
    /**
    * Load initial params
    * @return array
    */
    protected function loadParams()
    {
        require_once 'settings/params.php';
        return $_PARAMS;
    }
    
    /** 
    * A wrapper for the handleRequest function
    * @param string $path a relative path in the website origin
    * @return HtmlDocument
    */
    public function getPage($path)
    {
        $html = $this->handleRequest($path);
        $html = $this->replaceLinks($html);
        return $html;
    }
    
    /** 
    * Replace the form from the origin website with the new one
    * @param string $path path to an item in the website origin
    * @return HtmlDocument
    */
    public function getItem($path)
    {
        $html = $this->handleRequest($path);
        $html = $this->injectCss($html); // ?
        // load the new form
        $newForm = $this->htmlLoader('itemForm');
        // Set the value of the price fields.
        $newForm = str_replace(
            [ '{tax}', '{price}', '{shipping}', '{installation}' ],
            [ $this->tax, $this->price, $this->shipping, $this->instPrice ],
            $newForm
        );
        // Replace the old form
        $form = $html->find('form')[0]->outertext = $newForm;
        // Remove the bottom gallery
        $html->find('.dmPhotoGallery')[0]->outertext = '';
        // Remove the bottom gallery's title
        $html->find('#allWrapper')[0]->find('h3')[1]->outertext = '';
        // Handle thumbnail
        // !!!!!!!!!!!!!!!
        //remove tracking
        $html = $this->injectJs($html);
        return $html;
    }
    
    /** 
    * Set new links for galleries and menus items
    * @param HtmlDocument $html DOM object
    */
    private function replaceLinks($html)
    {
        foreach ($html->find('.unifiednav__item') as $key => $link) {
            $tmpHref = $link->href;
            if ($key < 4 || (($key > 19) && ($key < 25))) $desiredHref = $this->publicUrl . $tmpHref;
            else $desiredHref = $this->baseUrl . $tmpHref;
            $link->href = $desiredHref;
            $link->setAttribute('onclick',"return true;");
        }            
        $gallery = $html->find('.dmPhotoGallery');
        if (count($gallery))
            foreach ($gallery[0]->find('.has-link') as $link)
                $link->setAttribute('onclick',"return true;");
        return $html;
    }
    
    /**
    * Inject the custom css to the parsed html
    * @param  HtmlDocument
    * @return HtmlDocument
    */
    private function injectCss($html)
    {
        $head  = $html->find('head')[0];
        $style = $html->createElement('link');
        $style->setAttribute('rel','stylesheet');
        $style->setAttribute('href','/css/additional.css');
        $head->appendChild($style);
        return $html;
    }
    
    /**
    * Inject the custom javascript script to the parsed html
    * @param  HtmlDocument
    * @return HtmlDocument
    */
    private function injectJs($html)
    {
        $head   = $html->find('head')[0];
        $script = $html->createElement('script');
        $script->setAttribute('src','/js/gallery.js');
        $head->appendChild($script);
        return $html;
    }

    /**
    * The function for removing tracking and etc. scripts
    * from a loaded page
    * @param  HtmlDocument
    * @return HtmlDocument
    */
    private function removeGarbage($html)
    {
        $head = $html->find('head')[0];
        $body = $html->find('body')[0];
        // Find garbage
        $bodyScripts = $body->find('script');
        $headScripts = $head->find('script');
        // Remove garbage
        $headScripts[2]->outertext  = '';
        $bodyScripts[3]->outertext  = '';
        $bodyScripts[15]->outertext = '';
        $bodyScripts[16]->outertext = '';
        // replace jQuery
        $jQ = $html->createElement('script');
        $jQ->setAttribute('src','/js/jquery-2.2.4.min.js');
        $head->appendChild($jQ);
        return $html;
    }
}