<?php

namespace Core\Service;

class Watchdog
{
    public function log($type, $message, $object = null) {
        /* set path and file */
        $file = APP_PATH.'/var/log/watchdog.log';
        $path = dirname($file);
        if (!is_dir($path)) { mkdir($path, 0755, true); }

        /* create watchdog text */
        $timestamp = time();
        $date = date('Y-m-d H:i:s', $timestamp);
        $text = '['.$date."] ".$type.': '.$message;

        /* if object is set, add object info */
        if ($object) {
            $token = token(16);
            $text .= ' (dump: '.$timestamp.'_'.$token.'.dump)';
            if (is_array($object)) $object = json_encode($object);
            if (!is_dir($path.'/watchdog')) { mkdir($path.'/watchdog', 0755, true); }
            file_put_contents($path.'/watchdog/'.$timestamp.'_'.$token.'.dump', $object);
        }

        /* add watchdog line to file */
        file_put_contents($file, $text.PHP_EOL, FILE_APPEND);
    }

    public function error($type, $error, $errorMessage, $object = null, $die = false) {
        /* set path and file */
        $file = APP_PATH.'/var/log/errors.log';
        $path = dirname($file);
        if (!is_dir($path)) { mkdir($path, 0755, true); }

        /* create watchdog text */
        $timestamp = time();
        $date = date('Y-m-d H:i:s', $timestamp);
        $text = '['.$date."] ".$type.": (".$error.') '.$errorMessage;

        /* if object is set, add object info */
        if ($object) {
            $token = token(16);
            $text .= ' (dump: '.$timestamp.'_'.$token.'.dump)';
            if (is_array($object)) $object = json_encode($object);
            if (!is_dir($path.'/errors')) { mkdir($path.'/errors', 0755, true); }
            file_put_contents($path.'/errors/'.$timestamp.'_'.$token.'.dump', $object);
        }

        /* add watchdog line to file */
        file_put_contents($file, $text.PHP_EOL, FILE_APPEND);

        if ($die) die();
    }
}