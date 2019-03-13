<?php 
	namespace Controllers;
	use Models\Signup;

	class SignupController {
        private $error_array= array() ;
		protected $twig ;//templeting file

        public function __construct() {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views') ;
            $this->twig = new \Twig_Environment($loader) ;
        }
        public function get() {
            
            echo $this->twig->render("signup.html", array(
            "title" => "Signup" ,
            "firstname" => $_SESSION['registration_firstname'] ,
            "lastname" => $_SESSION['registration_lastname'] ,
            "username" => $_SESSION['registration_username'] ,
            "user_invalid" => $_SESSION['user_invalid'] ,
            "user_exist" => $_SESSION['user_exist'] ,
            "email" => $_SESSION['registration_email'] ,
            "email_used" => $_SESSION['email_used'] ,
            "email_invalid" => $_SESSION['email_invalid'] ,
            "password_mismatch" => $_SESSION['password_mismatch'] ,
            "password_invalid" => $_SESSION['password_invalid'] ,
            "password_short" => $_SESSION['password_short']
            )) ;
            $_SESSION['user_invalid'] = "";
            $_SESSION['user_exist'] = "";
            $_SESSION['email_used'] = "";
            $_SESSION['email_invalid'] = "";
            $_SESSION['password_mismatch'] = "";
            $_SESSION['password_invalid'] = "";
            $_SESSION['password_short'] = "";       }
        public function post() {
            if(isset($_POST['signupbtn'])) {
                //$this->error_array = [];
                $registration_firstname = $_POST["reg_fname"];
                $registration_lastname = $_POST["reg_lname"];
                $registration_username = $_POST["reg_uname"];
                $registration_email = $_POST["reg_email"];
                $registration_password = $_POST["reg_password"];
                $registration_password_confirm = $_POST["reg_password2"];
                $date = date("Y-m-d H:i:s");

                $_SESSION['registration_firstname'] = $registration_firstname;
                $_SESSION['registration_lastname'] = $registration_lastname;
                $_SESSION['registration_username'] = $registration_username;
                $_SESSION['registration_email'] = $registration_email;

                // $uname = $registration_username;
                if(filter_var($registration_email, FILTER_VALIDATE_EMAIL)) {

                    $email = filter_var($registration_email, FILTER_VALIDATE_EMAIL);
                    $no_rows = Signup::Validate($email, $registration_username);

                    if(sizeof($no_rows['Email']) > 0 ){
                        array_push($this->error_array, "Email already in use <br>");
                    }

                }else {
                    array_push($this->error_array, "Invalid email format <br>");
                }

                if($registration_password != $registration_password_confirm) {
                    array_push($this->error_array,  "Your passwords do not match <br>");
                }
                else {
                    if(preg_match('/[^A-Za-z0-9]/', $registration_password)) {
                        array_push($this->error_array, "Your password can only contain english characters or numbers <br>");
                    }
                }

                if((strlen($registration_password) > 30) || (strlen($registration_password) < 5)) {
                    array_push($this->error_array, "Your password must be betwen 5 and 30 characters <br>");
                }

                if(preg_match('/[^A-Za-z0-9]/', $registration_username)) {
                    array_push($this->error_array, "Your username can only contain english characters or numbers <br>");
                }else{
                    // //Check if uname already exists
                    $no_rows = Signup::Validate($email, $registration_username);
                    
                    if(sizeof($no_rows['Username']) > 0) {
                        array_push($this->error_array, "username already in use <br>");
                    }
                }
                #section for asigning values to error variables
                if(in_array("Email already in use <br>", $this->error_array)) {
                    $_SESSION['email_used'] = "Email already in use <br>";
                }else{
                    $_SESSION['email_used'] = "";
                }
                if(in_array("Invalid email format <br>", $this->error_array)) {
                    $_SESSION['email_invalid'] = "Invalid email format <br>";
                }else{
                    $_SESSION['email_invalid'] = "";
                }
                if(in_array("Your passwords do not match <br>", $this->error_array)) {
                    $_SESSION['password_mismatch'] = "Your passwords do not match <br>";
                }else{
                    $_SESSION['password_mismatch'] = "";
                }
                if(in_array("Your password can only contain english characters or numbers <br>",$this->error_array)){
                    $_SESSION['password_invalid'] = "Your password can only contain english characters or numbers <br>";
                }else{
                    $_SESSION['password_invalid'] = "";
                }
                if(in_array("Your password must be betwen 5 and 30 characters <br>", $this->error_array)) {
                    $_SESSION['password_short'] = "Your password must be betwen 5 and 30 characters <br>";
                }else{
                    $_SESSION['password_short'] = "";
                }
                if(in_array("Your username can only contain english characters or numbers <br>", $this->error_array)) {
                    $_SESSION['user_invalid'] = "Your username can only contain english characters or numbers <br>";
                }else{
                    $_SESSION['user_invalid'] = "";
                }
                if(in_array("username already in use <br>", $this->error_array)) {
                    $_SESSION['user_exist'] = "username already in use <br>";
                }else{
                    $_SESSION['user_exist'] = "";
                }
                if(empty($this->error_array)) {
                    $password = md5($registration_password); //Encrypt password before sending to database
                    Signup::AddUser($registration_firstname, $registration_lastname, $registration_username, $registration_email, $password, $date);
                    // array_push($error_array, "<span style='color: #14C800;'>You're all set! Goahead and login!</span><br>");

                    //Clear session variables 
                    $_SESSION['registration_firstname'] = "";
                    $_SESSION['registration_lastname'] = "";
                    $_SESSION['registration_username'] = "";
                    $_SESSION['registration_email'] = "";

                }
                header("Location: /signup");
            }
        }
	}

 ?>
 