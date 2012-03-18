<?php
    class HTTPRequest extends ApplicationComponent
    {
        public function addGetVar($key, $value)
        {
            $_GET[$key] = $value;
        }
        
        public function cookieData($key)
        {
            return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        }
        
        public function cookieExists($key)
        {
            return isset($_COOKIE[$key]);
        }
        
        public function getData($key)
        {
            return isset($_GET[$key]) ? $_GET[$key] : null;
        }
        
        public function getExists($key)
        {
            return isset($_GET[$key]);
        }
        
        public function fileData($key)
        {
            return isset($_FILES[$key]) ? $_FILES[$key] : null;
        }
        
        public function fileExists($key)
        {
            return !empty($_FILES[$key]['name']);
        }
        
        public function postData($key)
        {
            return isset($_POST[$key]) ? $_POST[$key] : null;
        }
        
        public function postExists($key)
        {
            return isset($_POST[$key]);
        }
        
        public function requestURI()
        {
            return $_SERVER['REQUEST_URI'];
        }
    } 
?>
