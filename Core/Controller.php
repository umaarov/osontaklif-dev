<?php

namespace Core;

abstract class Controller
{
    protected function view($view, $data = [])
    {
        $viewFile = BASE_PATH . '/resources/views/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            require BASE_PATH . '/resources/views/layouts/app.php';
        } else {
            die("View not found: {$viewFile}");
        }
    }
}
