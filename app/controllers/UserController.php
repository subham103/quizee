<?php 
	namespace Controllers;
	use Models\User;
    use Models\Login;

	class UserController {
		protected $twig ;//templeting file

        public function __construct() {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views') ;
            $this->twig = new \Twig_Environment($loader) ;
        }
        public function get() {
            if (isset($_SESSION['username'])) {
                $userLoggedIn = $_SESSION['username'];
                $user_id = $_SESSION['user_id'];
                $rows = User::getData($userLoggedIn);
                echo $this->twig->render("user.html", array(
                "username" => $_SESSION['username'] ,
                "points" => $rows['getUserPoints'][0]['sum(points_given)'] ,
                "questions" => $rows['printQuestions'] ,
                "leader" => $rows['leaderBoard']
                )) ;
            }
            else{
                header("Location: /login");
            }
        }
        public function post() {
            if(isset($_POST['post'])){
                $question = $_POST['post_text'];
                $option_a = $_POST['post_a'];
                $option_b = $_POST['post_b'];
                $option_c = $_POST['post_c'];
                $option_d = $_POST['post_d'];
                $correct_ans = $_POST['post_correct'];
                $points = $_POST['points'];
                User::addQuestions($question, $option_a, $option_b, $option_c, $option_d, $correct_ans, $points);
                header("Location: /home");
            }
            $rows_questions = User::getData($_SESSION['username']);
            // var_dump($rows_questions['printQuestions']);
            // die();
            for ($i=1; $i <= sizeof($rows_questions['printQuestions']) ; $i++) { 
                // var_dump($_POST['answer' . $i]);
                // die();
                if(isset($_POST['post_ans' . $i])){
                    $answer = $_POST['answer' . $i];
                    // var_dump($_POST['answer' . $i]);
                    // die();
                    User::submitQuestions($answer, $i);
                    header("Location: /home");
                    break;
                }
            }
        }
	}

 ?>
