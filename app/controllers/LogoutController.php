<?php

    namespace Controllers;

    class LogoutController
    {

        protected $twig ;//templeting file

        public function __construct()
        {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views') ;
            $this->twig = new \Twig_Environment($loader) ;
        }

        public function get()
        {
            session_destroy();
            header("Location: /login");
        }
    }
?>