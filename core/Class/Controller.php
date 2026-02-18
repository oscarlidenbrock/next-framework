<?php

namespace Core\Class;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class Controller
{
    private $layout = "html";
    public function layout($layout = "html") {
        $this->layout = $layout;

        return $this;
    }
    public function render($view, $data = []) {
        $render = ['html' => ''];
        $reflection = new \ReflectionClass(static::class);

        /* TODO: Change theme variable */
        $theme = "next";

        /* Get layout path */
        if (file_exists(THEMES_PATH.'/'.$theme.'/layout/'.$this->layout.'.twig')) {
            /* Render layout in custom theme */
            $loader = new FilesystemLoader(THEMES_PATH.'/'.$theme.'/layout');
            $twig = new Environment($loader);

            $render['html'] = $twig->render($this->layout.'.twig', $data);
        } elseif (file_exists(THEMES_PATH.'/default/layout/'.$this->layout.'.twig')) {
            /* Render layout in default theme */
            $loader = new FilesystemLoader(THEMES_PATH.'/default/layout');
            $twig = new Environment($loader);

            $render['html'] = $twig->render($this->layout.'.twig', $data);
        }

        /* Get View path */
        $viewPath = explode('/', $reflection->getFileName());
        array_splice($viewPath, -3);
        $viewPath = implode('/', $viewPath).'/templates';

        /* Render view */
        if (file_exists($viewPath.'/'.$view.'.twig')) {
            $loader = new FilesystemLoader($viewPath);
            $twig = new Environment($loader);

            $render['page'] = $twig->render($view.'.twig', $data);
        } else {
            core()->error('Template not found');
        }

        pre($render);

        return $this;
    }
}