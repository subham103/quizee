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

		public static function getData($username){
			#code for getUserPoints
			$db_userpoints = self::getDB();
			$user_userpoints = $db_userpoints->prepare("SELECT sum(points_given) FROM `score-board` WHERE username=:username;");
			$user_userpoints->execute(array(
				"username" => "$username"
			));
			$data_userpoints = $user_userpoints->fetchAll();
			// return $data;

			#code for isAdmin
			$db_isAdmin = self::getDB();
			$user_isAdmin = $db_isAdmin->prepare("SELECT * FROM user_info WHERE username=:username;");
			$user_isAdmin->execute(array(
				"username" => "$username"
			));
			$data_isAdmin = $user_isAdmin->fetchAll();
			// return $data;

			#code for printQuestions
			$db_printQuestions = self::getDB();
			$user_printQuestions = $db_printQuestions->prepare("SELECT * FROM questions;");
			$user_printQuestions->execute();
			$data_printQuestions = $user_printQuestions->fetchAll();
			// return $data;

			#leaderBoard
			$db_leaderBoard = self::getDB();
			$user_leaderBoard = $db_leaderBoard->prepare("SELECT username,sum(points_given) FROM `score-board` WHERE username != :admin GROUP BY username ORDER BY sum(points_given) DESC");
			$user_leaderBoard->execute(array(
				"admin" => "admin"
			));
			$data_leaderBoard = $user_leaderBoard->fetchAll();
			// return $data;
			$data = array(
				"getUserPoints" => $data_userpoints ,
				"isAdmin" => $data_isAdmin ,
				"printQuestions" => $data_printQuestions ,
				"leaderBoard" => $data_leaderBoard
            );
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
		}
		public static function submitQuestions($answer, $i){
			$db = self::getDB();
			$user = self::getData($_SESSION['username']);
			$user = $user['isAdmin'];
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
			
			$user_dl = $db->prepare("SELECT question_id FROM `score-board` WHERE username=:username;");
			$user_dl->execute(array(
				"username" => "$userLogedIn"
			));
			$data_dl = $user_dl->fetchAll();
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
	}
 ?>
