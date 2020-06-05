<?php
namespace Parser\Wallpapers;
use \simplehtmldom\HtmlDocument as simple_html_dom;

class Requests
{
/*
    private $tax;
    private $price;
    private $shipping;
    private $instPrice;
    
    const ORIGIN_URL = 'http://app.multiscreensite.com/site/447bf3de';
    const PUBLIC_URL = 'https://www.3ddesigncanada.com';// for links
    const BASE_URL   = 'https://wallpaper.3ddesigncanada.com';

    function __construct()
    {
        $params = $this->loadParams();
        $this->tax       = $params['tax'];
        $this->price     = $params['price'];
        $this->shipping  = $params['shipping'];
        $this->instPrice = $params['installation'];
    }
    
    private function loadParams()
    {
        require_once 'settings/params.php';
        return $_PARAMS;
    }

    private function curl_download($url)
    {
        if (!function_exists('curl_init')) {
            die('Sorry cURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, self::PUBLIC_URL);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    public function getCategoriesPage()
    {
        $html = $this->handleRequest('/services');
        $html = $this->removeGarbage($html);
        $html = $this->injectJs($html);
        $gallery = $html->find('.dmPhotoGallery');
        if (count($gallery)) {
            foreach ($gallery[0]->find('.has-link') as $link) {
                $link->setAttribute('onclick',"console.log('stuff')");
            }
        }
        return $html;
    }
*/
    /**
    * Simple function for loading html from files in /html folder
    * @param string $dest html file name
    */
/*    private function htmlLoader($dest)
    {
        try {
            $html = file_get_contents('html/'.$dest.'.html');
        } catch (Exception $e) {
            $html = $e->getMessage();
        }
        return $html;
    }*/
    
    /**
    * Create empty page using error page
    * as template
    */
    private function createEmptyPage() 
    {
        $path = '/payment_declined';
        $html = $this->handleRequest($path);
        $html->find('.dmContent')[0]->outertext = '';
        return $html;
    }
    
/*
    public function handleAction($action, $params)
    {
        $html = $this->createEmptyPage();
        $file = 'includes/' . $action . '.php';
        require $file;
        //$this->htmlLoader();
        return $html;
    }*/

    public function handleItem($path)
    {
        $html = $this->handleRequest($path);
        $html = $this->injectCss($html); // ?
        // load new form
        $newForm = $this->htmlLoader('itemForm');
        // Set the value of the price fields.
        $newForm = str_replace(
            [ '{tax}', '{price}', '{shipping}', '{installation}' ],
            [ $this->tax, $this->price, $this->shipping, $this->instPrice ],
            $newForm
        );
        // Replace old form
        $form = $html->find('form')[0]->outertext = $newForm;
        // Remove bottom gallery
        $html->find('.dmPhotoGallery')[0]->outertext = '';
        // Remove bottom gallery's title
        $html->find('#allWrapper')[0]->find('h3')[1]->outertext = '';
        // Handle thumbnail

        $html = $this->injectJs($html);
        return $html;
    }

    private function createThumb($src)
    {
        $parts     = explode('-', $src);
        $sizeParts = explode('w', $parts[2]);
        $size      = 350;
        $thumb     = $parts[0].'-'.$parts[1]."-".$size.'w.jpg';
        return $thumb;
    }

/*    public function handleRequest($path)
    {
        $html = '';
        $url = self::ORIGIN_URL.$path;
        if ($this->get_http_response_code($url) != "200") {
            Router::notFound();
        } else {
            $strHtml = $this->curl_download($url);
            if ($strHtml) {
                $html = new simple_html_dom();
                $html->load($strHtml);
                // Set new links for gallery menu entry
                foreach ($html->find('.unifiednav__item') as $key=>$link) {
                    $tmpHref = $link->href;
                    if ($key < 4 || (($key > 19) && ($key < 25)))
                        $desiredHref = self::PUBLIC_URL.$tmpHref;
                    else
                        $desiredHref = self::BASE_URL.$tmpHref;
                    $link->href = $desiredHref;
                    $link->setAttribute('onclick',"console.log('stuff')");
               }
            } else {
                Router::notFound();
            }
            return $html;
        }
    }

    private function get_http_response_code($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true); // just headers
        curl_setopt($ch, CURLOPT_NOBODY, true); // no body
        curl_setopt($ch, CURLOPT_REFERER, self::PUBLIC_URL);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpcode;
    }*/

    private function injectCss($html)
    {
        $head  = $html->find('head')[0];
        $style = $html->createElement('link');
        $style->setAttribute('rel','stylesheet');
        $style->setAttribute('href','/css/additional.css');
        $head->appendChild($style);
        return $html;
    }

    private function injectJs($html)
    {
        $head   = $html->find('head')[0];
        $script = $html->createElement('script');
        $script->setAttribute('src','/js/here_we_go.js');
        $head->appendChild($script);
        return $html;
    }

    /**
    * Function for removing tracking and etc. scripts
    * from loaded page
    * @param simple_html_dom $html to operate
    */
    private function removeGarbage($html)
    {
        $head = $html->find('head')[0];
        $body = $html->find('body')[0];
        $bodyScripts = $body->find('script');
        // Remove garbage
        $bodyScripts[3]->outertext = '';
        $bodyScripts[15]->outertext = '';
        $bodyScripts[16]->outertext = '';
        $headScripts = $head->find('script');
        $headScripts[2]->outertext = '';
        // replace jQuery
        $jQ = $html->createElement('script');
        $jQ->setAttribute('src','/js/jquery-2.2.4.min.js');
        $head->appendChild($jQ);
        return $html;
    }
}