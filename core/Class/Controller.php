<?php

namespace Core\Class;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class Controller
{
    public function render($view, $data = []) {
        $reflection = new \ReflectionClass(static::class);

        /* Get Child class path */
        $path = explode('/', $reflection->getFileName());
        array_splice($path, -3);
        $path = implode('/', $path).'/templates';

        /* Get template data */
        if (file_exists($path.'/'.$view.'.twig')) {
            /* Render template */
            $loader = new FilesystemLoader($path);
            $twig = new Environment($loader);

            echo $twig->render($view.'.twig', $data);
        } else {
            core()->error('Template not found');
        }
    }
}