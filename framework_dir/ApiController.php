<?php

    namespace Struggle\Core;

    abstract class ApiController {
        protected $method = null;
        protected $allowedMethods = array("DELETE", "POST", "GET", "PUT");
        protected $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error'
        );

        public function __construct() {
            header("Access-Control-Allow-Orgin: *");
            header("Access-Control-Allow-Methods: *");

            $this->method = $this->requestMethod();
            if (!$this->isRequestedMethodAllowed()) {
                $this->response("Requested method not allowed.", 405);
                die();
            };
        }

        public function requestMethod($server = null) {
            if ($server === null) $server = $_SERVER;
            $method = $server['REQUEST_METHOD'];
            if ($method === 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $server)) {
                if ($server['HTTP_X_HTTP_METHOD'] === 'DELETE') {
                    $method = 'DELETE';
                } else if ($server['HTTP_X_HTTP_METHOD'] === 'PUT') {
                    $method = 'PUT';
                } else {
                    throw new \Exception("Unexpected header.");
                }
            }

            return $method;
        }

        public function isRequestedMethodAllowed() {
            return in_array($this->method, $this->allowedMethods);
        }

        private function requestStatus($code) {
            return array_key_exists($code, $this->status) ? $this->status[$code] : $this->status[500];
        }

        protected function response($data, $status = "200") {
            header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
            header("Content-Type: application/json");
            print json_encode($data);
        }
    }
?>