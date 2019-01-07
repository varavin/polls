<?php

namespace Polls;

class View
{
    const VIEWS_DIR = __DIR__ . '/../views/';
    const JS_COMPONENTS_DIR = __DIR__ .'/../public/js/components/';

    private $jsComponents = [];

    public function render($view, $variables = array())
    {
        $viewFile = self::VIEWS_DIR . $view . '.php';
        $output = null;
        if(file_exists($viewFile)){
            extract($variables);
            ob_start();
            require_once($viewFile);
            $output = ob_get_clean();
        }
        return $output;
    }

    public function addJsComponent($name, $initCode)
    {
        if (!in_array($name, $this->jsComponents) && is_file(self::JS_COMPONENTS_DIR . $name . '.js')) {
            $this->jsComponents[] = compact('name', 'initCode');
        }
    }

    public function getJsComponents()
    {
        return $this->jsComponents;
    }


}