<?php
// We are working in WallpaperParser scope

// Loading shipping form
$form = $this->htmlLoader('select-payment');
$html->find('#allWrapper')[0]->innertext = $form;
$html = $this->injectJs($html);
$html = $this->injectCss($html);
