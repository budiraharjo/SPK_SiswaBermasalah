<?php

namespace Core;

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Cek controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            if (file_exists('../app/Controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        $controllerClass = 'App\\Controllers\\' . $this->controller;
        $this->controller = new $controllerClass;

        // Cek method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
