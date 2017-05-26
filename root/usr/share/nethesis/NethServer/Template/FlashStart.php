<?php

echo $view->header('flashstart')->setAttribute('template', $T('FlashStart_header'));

echo "<div class='ui-state-warning ui-state-highlight' style='padding: 10px; font-size: 120%'><i class='fa'></i>";
echo $T("Flashstart_Registration");
echo "</div>";

echo "<div style='margin: 20px; padding: 10px; font-size: 120%'>";
echo $T("FlashStartSite_label").": <a href='".$view['FlashStartSite']."' target='_blank'>".$view['FlashStartSite']."</a>";
echo "</div>";

echo $view->fieldsetSwitch('status', 'disabled')
    ->setAttribute('label', $T('Status_disabled_label'));

echo $view->fieldsetSwitch('status', 'enabled')
    ->setAttribute('label', $T('Status_enabled_label'))
    ->insert($view->textInput('Username'))
    ->insert($view->textInput('Password'))
    ->insert($view->selector('Roles', $view::SELECTOR_MULTIPLE))
    ->insert($view->textArea('Bypass', $view::LABEL_ABOVE)->setAttribute('dimensions', '10x50'));

echo $view->buttonList($view::BUTTON_SUBMIT);

