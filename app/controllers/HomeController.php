<?php

    namespace Controllers;

    class HomeController
    {

        protected $twig ;//templeting file

        public function __construct()
        {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views') ;
            $this->twig = new \Twig_Environment($loader) ;
        }

        public function get()
        {
                echo $this->twig->render("home.html", array(
                    "title" => "Home | Welcome")) ;
        }
    }
?>