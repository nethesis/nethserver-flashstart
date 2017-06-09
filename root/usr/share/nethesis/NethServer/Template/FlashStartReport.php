<?php

echo $view->header('flashstart')->setAttribute('template', $T('FlashStart_header'));

foreach ($view['report'] as $key => $props) {
    echo "<div>";
    echo "<div>".$props['title']."</div>";
    echo "<img src='".$props['url']."' alt='".$props['title']."'/>";
    echo "</div>";
}
