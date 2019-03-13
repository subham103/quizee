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
                // var_dump($_SESSION['username']);
                // die();
                $userLoggedIn = $_SESSION['username'];
                $user_id = $_SESSION['user_id'];
                
                $form_notAdmin = "<span>Latest Problems</span><hr><br>";
                $rows_admin = User::isAdmin($userLoggedIn);
                $rows_questions = User::printQuestions();
                if(sizeof($rows_questions) > 0) {
                    for ($i=1; $i <= sizeof($rows_questions) ; $i++) { 
                        $j = $i - 1;
                        $question = $rows_questions[$j]['question'];
                        $option_a = $rows_questions[$j]['option_a'];
                        $option_b = $rows_questions[$j]['option_b'];
                        $option_c = $rows_questions[$j]['option_c'];
                        $option_d = $rows_questions[$j]['option_d'];
                        $form_notAdmin .= "$i )&nbsp; $question <br>a) &nbsp; $option_a &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp; $option_b <br> c) &nbsp; $option_c &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d) &nbsp; $option_d <br><form action='/home' class='post_form1' method='POST'><input type='text' name='answer" . $i . "'class='ans' required><br><input type='submit' name='post_ans" . $i . "'id='post_btn'><br></form><hr>";
                     }
                }
                // var_dump($form_notAdmin);
                // die();
                
                if($rows_admin[0]['username'] == "admin"){
                    $form = "<form action=\"/home\" class=\"post_form\" method=\"POST\">
                                <textarea name=\"post_text\" id=\"post_text\" placeholder=\"Wanna post new questions?\"></textarea>
                                <br><br>
                                <input type=\"text\" name=\"post_a\" placeholder=\"Option A\" required>
                                <input type=\"text\" name=\"post_b\" placeholder=\"Option B\" required>
                                <br><br>
                                <input type=\"text\" name=\"post_c\" placeholder=\"Option C\" required>
                                <input type=\"text\" name=\"post_d\" placeholder=\"Option D\" required>
                                <br><br>
                                <input type=\"text\" name=\"post_correct\" placeholder=\"Correct Answer\" required>
                                <input type=\"text\" name=\"points\" placeholder=\"Points\" required>
                                <br><br>
                                <input type=\"submit\" name=\"post\" id=\"post_btn\">
                                <hr>
                            </form>";
                }else{
                    $form = $form_notAdmin; 
                }
                $leader = User::leaderBoard();
                $user_echo = User::getUserPoints($userLoggedIn);
                // var_dump($user_echo[0]['sum(points_given)']);
                // die();
                echo $this->twig->render("user.html", array(
                "username" => $_SESSION['username'] ,
                "points" => $user_echo[0]['sum(points_given)'] ,
                "form" => $form ,
                "leader" => $leader
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
                // var_dump($points);
                // die();
                User::addQuestions($question, $option_a, $option_b, $option_c, $option_d, $correct_ans, $points);
                header("Location: /home");
            }
            $rows_questions = User::printQuestions();
            for ($i=1; $i <= sizeof($rows_questions) ; $i++) {    
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
