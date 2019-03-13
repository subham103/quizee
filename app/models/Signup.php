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
			$db = self::getDB();
			$user = $db->prepare("INSERT INTO user_info (firstname, lastname, username, email, password, signup_date) VALUES (:firstname, :lastname, :username, :email, :password, :signup_date)");
			$user->execute(array(
				"firstname" => $firstname,
				"lastname" => $lastname,
				"username" => $username,
				"email" => $email,
				"password" => $password,
				"signup_date" => $signup_date
			));
		}//sql injection byparsing
		
		public static function ValidateEmail($email){
			$db = self::getDB();
			$user = $db->prepare("SELECT email FROM user_info WHERE email=:email");
			$user->execute(array(
				"email" => $email
			));

			$data = $user->fetchAll();
			 // var_dump($data);
			 // die();
            //Count the number of rows returned

            return $data;
		}

		public static function ValidateUsername($uname){
			$db = self::getDB();
			$user = $db->prepare("SELECT username FROM user_info WHERE username=:username");
			$user->execute(array(
				"username" => $uname
			));
			$data = $user->fetchAll();
			// var_dump($data);
			// die();
			
            //Count the number of rows returned
            //$num_rows = mysqli_num_rows($user);

            return $data;
		}
	}
 ?>