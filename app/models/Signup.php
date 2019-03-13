<?php 

	namespace Models;

	class Signup{
		public static function getDB(){
			include __DIR__."/../../configs/credential.php";

			return new \PDO("mysql:dbname=".
			$db_connect['db_name'].";host=".
			$db_connect['server'] ,
			$db_connect['username'] ,
			$db_connect['password']);
		}

		public static function AddUser($firstname, $lastname, $username, $email, $password, $signup_date){
			$db_adduser = self::getDB();
			$user_adduser = $db_adduser->prepare("INSERT INTO user_info (firstname, lastname, username, email, password, signup_date) VALUES (:firstname, :lastname, :username, :email, :password, :signup_date)");
			$user_adduser->execute(array(
				"firstname" => $firstname,
				"lastname" => $lastname,
				"username" => $username,
				"email" => $email,
				"password" => $password,
				"signup_date" => $signup_date
			));
		}//sql injection byparsing
		public static function Validate($email, $uname){
			#ValidateEmail
			$db_validateEmail = self::getDB();
			$user_validateEmail = $db_validateEmail->prepare("SELECT email FROM user_info WHERE email=:email");
			$user_validateEmail->execute(array(
				"email" => $email
			));

			$data_validateEmail = $user_validateEmail->fetchAll();
            // return $data;

            #ValidateUsername
            $db_validateUsername = self::getDB();
			$user_validateUsername = $db_validateUsername->prepare("SELECT username FROM user_info WHERE username=:username");
			$user_validateUsername->execute(array(
				"username" => $uname
			));
			$data_validateUsername = $user_validateUsername->fetchAll();
            // return $data;
            $data = array(
				"Email" => $data_validateEmail ,
				"Username" => $data_validateUsername
            );
            return $data;

		}
	}
 ?>