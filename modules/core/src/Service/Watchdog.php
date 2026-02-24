<?php

namespace Core\Service;

class Watchdog
{
    public function add($type, $error, $errorMessage, $die) {
        $file = APP_PATH.'/var/log/watchdog.log';
        $timestamp = date('Y-m-d H:i:s');
        $text = '['.$timestamp."] ".$type.": ".$error.' ('.$errorMessage.')'.PHP_EOL;

        file_put_contents($file, $text, FILE_APPEND);
    }
}