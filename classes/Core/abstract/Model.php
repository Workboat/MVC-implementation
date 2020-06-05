<?php
namespace Models;

abstract class Model
{
    abstract protected function loadParams();
    
    /**
    * @var string public url of source website
    */
    protected $publicUrl = 'https://www.3ddesigncanada.com';
    
    /**
    * @var string service url of source website
    */
    protected $originUrl = 'http://app.multiscreensite.com/site/447bf3de';
    
    /**
    * @var string url of current website
    */
    protected $baseUrl   = 'https://wallpaper.3ddesigncanada.com';
    
    /** 
    * Get a data from an URL
    * @param string $path is the relative path in the website origin
    */
    public function handleRequest($path)
    {
        $html    = '';
        $url     = $this->originUrl . $path;
        $request = new \Requests($this->publicUrl);
        $html    = $request->doRequest($url);
        return $html;
    }
    
    /**
    * Create a page with the content from
    * the error page which used as a template
    * @param string $content
    * @return HtmlDocument
    */
    public function createTemplatePage($content) 
    {
        $path = '/payment_declined';
        $html = $this->handleRequest($path);
        $html->find('.dmContent')[0]->outertext = $content;
        return $html;
    }
    
    /**
    * The Simple function for loading an html 
    * from files in /html folder
    * @param string $dest html file name
    * @return string $html
    */
    public function htmlLoader($dest)
    {
        try {
            $html = file_get_contents('html/'.$dest.'.html');
        } catch (Exception $e) {
            $html = $e->getMessage();
        }
        return $html;
    }
} 