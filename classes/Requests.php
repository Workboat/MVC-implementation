<?php
use \simplehtmldom\HtmlDocument as simple_html_dom;

class Requests
{
    /**
    * @var string  the public url of the origin website
    */
    private $publicUrl;
    
    function __construct($publicUrl)
    {
        $this->publicUrl = $publicUrl;
    }
    
    /** 
    * Get the response headers
    * @param string $url the website address
    */
    private function get_http_response_code($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true); // just headers
        curl_setopt($ch, CURLOPT_NOBODY, true); // no body
        curl_setopt($ch, CURLOPT_REFERER, $this->publicUrl);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpcode;
    }
    
    /** 
    * Get a response body
    * @param string $url the website address
    */
    private function curl_download($url)
    {
        if (!function_exists('curl_init')) {
            die('Sorry cURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $this->publicUrl);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    /** 
    * Get the data from URL
    * @param string $path a relative path in the website origin
    */
    public function doRequest($url)
    {
        $html = '';
        if ($this->get_http_response_code($url) != "200") {
            Router::notFound();
        } else {
            $strHtml = $this->curl_download($url);
            if ($strHtml) {
                $html = new simple_html_dom();
                $html->load($strHtml);
            } else {
                Router::notFound();
            }
            return $html;
        }
    }
}