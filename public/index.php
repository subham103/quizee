<?php

require_once __DIR__ . "/../vendor/autoload.php";
session_start();
Toro::serve(array(
    "/" => "Controllers\\HomeController" ,
    "/signup" => "Controllers\\SignupController" ,
    "/login" => "Controllers\\LoginController" ,
    "/home" => "Controllers\\UserController"
));
?>