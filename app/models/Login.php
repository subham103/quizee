<?php 

	namespace Models;

	class Login{
		public static function getDB(){
			include __DIR__."/../../configs/credential.php";

			return new \PDO("mysql:dbname=".
			$db_connect['db_name'].";host=".
			$db_connect['server'] ,
			$db_connect['username'] ,
			$db_connect['password']);
		}

		public static function FindUser($email, $password){
			$db = self::getDB();
			$user = $db->prepare("SELECT * FROM user_info WHERE email=:email AND password=:password");
			$user->execute(array(
				"email" => $email,
				"password" => $password

			));
			
			$data = $user->fetchAll();
			// var_dump($data);
   //              die();
			return $data;
		}
		
	}
 ?>
