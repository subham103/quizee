<?php 
	namespace Controllers;
	use Models\Login;

	class LoginController {
		protected $twig ;//templeting file

        public function __construct() {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views') ;
            $this->twig = new \Twig_Environment($loader) ;
        }
        public function get() {
            echo $this->twig->render("login.html", array(
            "email" => $_SESSION['login_email']
            )) ;
        }
        public function post() {
            if(isset($_POST['login_btn'])) {
                $login_email = $_POST["log_email"];
                $login_password = $_POST["log_password"];
                $error_array = array();

                $_SESSION['login_email'] = $login_email;
                $password = md5($login_password);
                $rows = Login::FindUser($login_email, $password);
                // var_dump($rows);
                // die();
                if(sizeof($rows) == 1) {
                    $fullname = $rows[0]['username'];
                    $_SESSION['username'] = $fullname;
                    $_SESSION['email'] = $rows[0]['email'];
                    $_SESSION['user_id'] = $rows[0]['id'];
                    // var_dump($_SESSION['user_id']);
                    // die();
                    header("Location: /home");
                }
                else {
                    array_push($error_array, "Email or password was incorrect<br>");
                } 
                if (empty($error_array)) {
                    $_SESSION['login_email'] = "";
                }
                
            }
        }
	}

 ?>