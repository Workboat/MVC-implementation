<?php
// We are working in WallpaperParser scope

// Loading shipping form
$form  = $this->htmlLoader('shipping');
$summ  = $_GET['summ'];
$taxes = $this->tax * $summ;
var_dump($taxes);
$form = str_replace(
    [ '{tax}', '{price}', '{shipping}', '{installation}', '{summ}' ],
    [ $this->tax, $this->price, $this->shipping, $this->instPrice, $summ ],
    $form
);
$html->find('#allWrapper')[0]->innertext = $form;
$html = $this->injectJs($html);
$html = $this->injectCss($html);
