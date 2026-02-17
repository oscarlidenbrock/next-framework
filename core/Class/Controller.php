<?php

namespace Core\Class;

class Controller
{
    public function render($view, $data = []) {
        $reflection = new \ReflectionClass(static::class);

        /* Get Child class path */
        $filename = explode('/', $reflection->getFileName());
        array_splice($filename, -3);
        $filename = implode('/', $filename);

        /* Get template data */
        $filename .= '/templates/'.$view.'.twig';

        if (file_exists($filename)) {
            $data = file_get_contents($filename);
        } else {
            core()->error('Template not found');
        }

        pre($data);

        print "función render";
    }
}