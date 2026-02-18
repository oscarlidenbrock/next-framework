<?php

namespace Core\Class;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class Controller
{
    private $layout = "default";
    public function layout($layout) {
        $this->layout = $layout;

        return $this;
    }
    public function render($view, $data = []) {
        $render = ['html' => ''];
        $reflection = new \ReflectionClass(static::class);

        /* Render layout */

        /* Get View path */
        $path = explode('/', $reflection->getFileName());
        array_splice($path, -3);
        $path = implode('/', $path).'/templates';

        /* Render view */
        if (file_exists($path.'/'.$view.'.twig')) {
            $loader = new FilesystemLoader($path);
            $twig = new Environment($loader);

            $render['page'] = $twig->render($view.'.twig', $data);
        } else {
            core()->error('Template not found');
        }

        return $this;
    }
}