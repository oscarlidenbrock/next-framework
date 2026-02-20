<?php

namespace Core\Class\Extend;

use Twig\TwigFunction;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    private $layout = "html";
    public function layout($layout = "html") {
        $this->layout = $layout;

        return $this;
    }
    public function render($view, $data = []) {
        $html = '';
        $render = [];
        $reflection = new \ReflectionClass(static::class);

        /* TODO: Change theme variable */
        $theme = "next";

        /* Get View path */
        $viewPath = explode('/', $reflection->getFileName());
        array_splice($viewPath, -3);
        $viewPath = implode('/', $viewPath).'/templates';

        /* Initialize Twig for theme path */
        $loader = new FilesystemLoader($viewPath);
        $twig = new Environment($loader);

        /* Render view */
        if (file_exists($viewPath.'/'.$view.'.twig')) {
            $render['page'] = $twig->render($view.'.twig', $data);
        } else {
            core()->error('Template not found');
        }

        /* Set layout in custom or default theme */
        if (file_exists(THEMES_PATH.'/'.$theme.'/layout/'.$this->layout.'.twig')) {
            $loader = new FilesystemLoader(THEMES_PATH.'/'.$theme.'/layout');
        } elseif (file_exists(THEMES_PATH.'/default/layout/'.$this->layout.'.twig')) {
            $loader = new FilesystemLoader(THEMES_PATH.'/default/layout');
        }

        /* Initialize Twig for layout path  */
        $twig = new Environment($loader);

        $twig->addFunction(new TwigFunction('render', function ($variable) use ($render) {;
            return $render[$variable];
        }, ['is_safe' => ['html']]));

        $html = $twig->render($this->layout.'.twig', $data);

        /* print $html */
        print($html);

        return $this;
    }
}