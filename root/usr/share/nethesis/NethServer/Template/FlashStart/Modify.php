<?php

$headerTemplate = $T('profile_create_header');

echo $view->header('name')->setAttribute('template', $headerTemplate);

echo $view->panel()
    ->insert($view->selector('Group', $view::SELECTOR_DROPDOWN))
    ->insert($view->textInput('Description'));

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL);

