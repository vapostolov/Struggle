<?php
    function Struggle($request) {
        $controller = "";
        $action = "index"; /* default controller's action */
        
        $segments = explode("/", $request);
        if (isset($segments[0]) && strlen($segments[0]) > 0) $controller = ucfirst(strtolower($segments[0]));
        if (isset($segments[1]) && strlen($segments[1]) > 0) $action = strtolower($segments[1]);
        
        /* Get controller's file */
        $path = CONTROLLERS_DIRECTORY . "/" . $controller . ".php";
        if (!file_exists($path)) {
            die("Missing controller.");
        }
        require_once($path);
        
        /* Verify if action exists */
        if (!method_exists($controller, $action)) {
            die("Missing action.");
        }
        
        /* Create controller's instance and call method */
        $obj = new $controller;
        die(call_user_func_array(array($obj, $action), array_slice($segments, 2)));
    }
?>