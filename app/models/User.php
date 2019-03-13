<?php 

	namespace Models;

	class User{
		public static function getDB(){
			include __DIR__."/../../configs/credential.php";

			return new \PDO("mysql:dbname=".
			$db_connect['db_name'].";host=".
			$db_connect['server'] ,
			$db_connect['username'] ,
			$db_connect['password']);
		}

		public static function getUserPoints($username){
			$db = self::getDB();
			$user = $db->prepare("SELECT sum(points_given) FROM `score-board` WHERE username=:username;");
			$user->execute(array(
				"username" => "$username"
			));
			
			$data = $user->fetchAll();
			// var_dump($data);
			// die();
			return $data;
		}
		public static function isAdmin($username){
			$db = self::getDB();
			$user = $db->prepare("SELECT * FROM user_info WHERE username=:username;");
			$user->execute(array(
				"username" => "$username"
			));
			$data = $user->fetchAll();
			return $data;
		}
		public static function printQuestions(){
			$db = self::getDB();
			$user = $db->prepare("SELECT * FROM questions;");
			$user->execute();
			$data = $user->fetchAll();
			return $data;
		}
		public static function addQuestions($question, $option_a, $option_b, $option_c, $option_d, $correct_ans, $points){
			$db = self::getDB();
			$user = $db->prepare("INSERT INTO `questions` (`question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_ans`,`total_points`) VALUES (:question, :option_a, :option_b, :option_c, :option_d, :correct_ans, :points);");
			$user->execute(array(
				"question" => "$question" ,
				"option_a" => "$option_a" ,
				"option_b" => "$option_b" ,
				"option_c" => "$option_c" ,
				"option_d" => "$option_d" ,
				"correct_ans" => "$correct_ans" ,
				"points" => $points 
			));
			
			// $data = $user->fetchAll();
			// return $data;
		}
		public static function submitQuestions($answer, $i){
			$db = self::getDB();
			$user = self::isAdmin($_SESSION['username']);
			$user_id = $user[0]['id'];
			$userLogedIn = $_SESSION['username'];
			$user_ml = $db->prepare("SELECT correct_ans,total_points FROM questions WHERE id=:id;");
			$user_ml->execute(array(
				"id" => $i 
			));
			$data_ml = $user_ml->fetchAll();
			$correct_ans = $data_ml[0]['correct_ans'];
			if($answer == $correct_ans){
				$points = $data_ml[0]['total_points'];
				
			}else{
				$points = 0;
			}
			// var_dump($points . " " . $correct_ans . " " . $answer);
			// die();
			$user_dl = $db->prepare("SELECT question_id FROM `score-board` WHERE username=:username;");
			$user_dl->execute(array(
				"username" => "$userLogedIn"
			));
			$data_dl = $user_dl->fetchAll();
			// foreach ($data_dl as $key => $value) {
			
			// var_dump($value[$key] != "$i");
			// die();
			// }
			$temp = 0;
			foreach ($data_dl as $key => $value) {
				# code...
				if ($value[$key] != "$i") {
					# code...
					$temp++;
				}
			}
			if(sizeof($data_dl) == $temp){
				$user = $db->prepare("INSERT INTO `score-board` (`user_id`, `question_id`, `username`, `answer`, `points_given`) VALUES (:user_id, :question_id, :username, :answer, :points_given);");
				$user->execute(array(
					"user_id" => $user_id ,
					"question_id" => $i ,
					"username" => "$userLogedIn" ,
					"answer" => "$answer" ,
					"points_given" => $points
				));
			}
			
			
		}
		public static function leaderBoard(){
			$db = self::getDB();
			$user = $db->prepare("SELECT username,sum(points_given) FROM `score-board` WHERE username != :admin GROUP BY username ORDER BY sum(points_given) DESC");
			$user->execute(array(
				"admin" => "admin"
			));
			$data = $user->fetchAll();
			// var_dump($data);
   //              die();
			return $data;
		}

	}
 ?>
