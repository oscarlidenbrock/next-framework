<?php

namespace Core\Service;

use Symfony\Component\Yaml\Yaml;

class Cache
{
    private $index = [];
    function __construct() {
        /* read index */
        if (file_exists(APP_PATH.'/var/cache/index.cache')) {
            $this->index = json_decode(file_get_contents(APP_PATH.'/var/cache/index.cache'), true);
        }
    }
    public function get($folder, $key) {


        return null;
    }

    public function set($folder, $key, $value, $expires = 0) {
        /* get the cache folder/file if exist */
        $file = null;

        if (isset($this->index[$folder][$key])) {
            if ($this->index[$folder][$key]['e'] == 0 || $this->index[$folder][$key]['e'] > time()) {
                if (file_exists(APP_PATH.'/var/cache/'.$folder.'/'.$this->index[$folder][$key]['f'])) {
                    $file = $this->index[$folder][$key]['f'];
                }
            }
        }

        if (!$file) {
            /* else create it a new file */
            $found = false;
            unset($this->index[$folder][$key]);

            while (!$found) {
                $file = date('Y-m').'/'.token(16).'.cache';
                if (!file_exists(APP_PATH.'/var/cache/'.$folder.'/'.$file)) $found = true;
            }
        }

        /* set the expiration date */
        $e = 0;

        if (isset($this->index[$folder][$key]['e'])) {
            /* if cache index exist, check expiration date*/
            if ($this->index[$folder][$key]['e'] > 0) {
                /* set the same expiration date because cache file exist */
                $e = $this->index[$folder][$key]['e'];
            }
        } else {
            /* else, set the expiration date if expire is set, otherwise is 0 (unlimited) */
            if ($expires > 0) $e = time() + $expires;
        }

        $this->index[$folder][$key] = [
            'f' => $file,
            'e' => $e
        ];

        /* check directories */
        $file = APP_PATH.'/var/cache/'.$folder.'/'.$file;
        $folder= dirname($file);

        if (!is_dir($folder)) mkdir($folder, 0777, true);

        /* save the cache file */
        file_put_contents($file, json_encode($value));

        /* save the index */
        file_put_contents(APP_PATH.'/var/cache/index.cache', json_encode($this->index));
    }
}