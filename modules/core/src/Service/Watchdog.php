<?php

namespace Core\Service;

class Watchdog
{
    public function add($type, $error, $errorMessage, $die = false) {
        /* set path and file */
        $file = APP_PATH.'/var/log/watchdog.log';
        $path = dirname($file);
        if (!is_dir($path)) { mkdir($path, 0755, true); }

        /* create watchdog text */
        $timestamp = date('Y-m-d H:i:s');
        $text = '['.$timestamp."] ".$type.": ".$error.' ('.$errorMessage.')'.PHP_EOL;

        /* add watchdog line to file */
        file_put_contents($file, $text, FILE_APPEND);

        if ($die) die();
    }
}