<?php
/*
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core {
    protected $currentController = 'Home';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();
        $controllerName = 'Home';

        // Look in controllers for first value
        if(isset($url[0])){
            // Convert hyphens to underscores, e.g., about-us -> About_us
            $controllerName = ucwords(str_replace('-', '_', $url[0]));
        }

        if(file_exists('../app/controllers/' . $controllerName . '.php')){
            // If exists, set as controller
            $this->currentController = $controllerName;
            unset($url[0]);
        } elseif (isset($url[0])) {
            // 404 Not Found
            $this->currentController = 'Error404';
        }

        // Require the controller
        require_once '../app/controllers/'. $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if(isset($url[1])){
            $methodName = str_replace('-', '_', $url[1]);
            // Check to see if method exists in controller
            if(method_exists($this->currentController, $methodName)){
                $this->currentMethod = $methodName;
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            // Sanitize URL to prevent basic XSS or malicious characters
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
