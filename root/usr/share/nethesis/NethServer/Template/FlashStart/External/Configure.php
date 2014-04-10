<?php

$view->requireFlag($view::INSET_DIALOG);

echo $view->header()->setAttribute('template', $T('configure_header'));

echo "<div class='external_page'>".$T('external_page');
echo "<div class='external_page'><a target='_blank' class='flashstart_button' href=".$view['url'].">".$T('flashstart_page_title')."</a></div>";
echo "</div>";

echo $view->buttonList()
    ->insert($view->button('Close', $view::BUTTON_CANCEL))
;

$view->includeCss("
    .external_page {
        text-align: center;
        padding: 5px;
    }
");

$view->includeJavascript("
(function ( $ ) {

    $('.flashstart_button').button();

} ( jQuery ));
");

