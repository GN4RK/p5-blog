<?php
require_once("src/controller/Controller.php");
// loading classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');
require_once('src/model/UserManager.php');
require_once('src/model/View.php');

class FrontendController extends Controller
{
    static function home($mailStatus = null) {        
        View::renderFront('home.twig', ['mailStatus' => $mailStatus]);        
    }

    static function blog($id = null) {
        if ($id != null) {
            self::post($id);
        } else {
            self::listPosts();
        }
    }
    
    static function listPosts() {
        $postManager = new PostManager();
        $posts = $postManager->getPosts();
        View::renderFront('listPostsView.twig', ['posts' => $posts]);
    }
    
    static function post($id) {
        $postManager = new PostManager();
        $commentManager = new CommentManager();
        $post = $postManager->getPost($id);
        $comments = $commentManager->getComments($id);
    
        // if id not found, display error
        if (!$post) {
            self::error404();
        } else {
            View::renderFront('postView.twig', [
                'post' => $post, 
                'comments' => $comments
            ]);
        }
    }

    static function register($feedback = null) {
        View::renderFront('register.twig', ["feedback" => $feedback, 'post' => $_POST]);
    }

    static function registerCheck() {

        $userManager = new UserManager();

        if (!isset($_POST['email']) || !isset($_POST['pass']) || !isset($_POST['pass2'])) {
            return "missing information";
        }

        if (!$userManager->emailAvailable($_POST['email'])) return "email already used";
        if ($_POST['pass'] != $_POST['pass2']) return "password verification failed";
        if (strlen($_POST['pass']) < 6) return "password too short";

        $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $key = FrontendController::generateRandomString();
        $userManager->addUser("", "", "", $_POST['email'], "pending@$key", $hash);
        return "user created";

    }

    static function sendVerificationMail($email) {
        $userManager = new UserManager();
        $user = $userManager->getUserByEmail($email);
        $key = $user['status'];
        $url = BASEURL. "validation?key=" .$key. "&email=$email";

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $subject = "Validation de l'adresse mail";
        $message = "Cliquez sur le lien pour valider l'inscription : <a href=\"$url\">Lien</a>";
        return mail($email, $subject, $message, implode("\r\n", $headers));
    }

    static function login() {
        if (isset($_POST['email']) || isset($_POST['pass'])) {
            View::renderFront('login.twig', [
                'loginFailed' => true
            ]);
        } else {
            View::renderFront('login.twig');
        }
    }

    static function loginCheck() {
        $userManager = new UserManager();

        $user = $userManager->checkUser($_POST['email'], $_POST['pass']);
        if ($user) {
            $_SESSION['user'] = $user;
            return true;
        } else {
            $_SESSION['user'] = null;
            return false;
        }
   
    }

    static function logout() {
        $_SESSION['user'] = null;
    }

    static function legal() {
        View::renderFront('legal.twig');
    }

    static function error404() {
        View::renderFront('error404.twig');
    }

    static function sendMail() {
        // return mail("yoann.leonard@gmail.com", "contact", "message");
        return true;
    }

    static function validation() {
        $feedback = "";
        $key = $_GET["key"];
        $email = $_GET["email"];
        $userManager = new UserManager();
        $user = $userManager->getUserByEmail($email);
        if ($user) {
            if ($user['status'] == $key) {
                $userManager->accountValidation($email);
                $feedback = "account validated";
            } else {
                $feedback = "account already validated";
            }
        } else {
            $feedback = "user not found";
        }
        
        View::renderFront('validation.twig', ["feedback" => $feedback]);
    }

    static function profile() {

        $feedback = array();

        if (isset($_SESSION['user']) && isset($_POST)) {

            $userManager = new UserManager();

            if (isset($_POST['name'])) {

                if (!empty($_POST['name'])) {
                    if ($_POST['name'] != $_SESSION['user']['name']) {
                        $userManager->setName($_SESSION['user']['id'], $_POST['name']);
                        $feedback[] = "name edited";
                    }
                }

                if (!empty($_POST['first_name'])) {
                    if ($_POST['first_name'] != $_SESSION['user']['first_name']) {
                        $userManager->setFirstName($_SESSION['user']['id'], $_POST['first_name']);
                        $feedback[] = "first_name edited";
                    }
                }

                if (!empty($_POST['email'])) {
                    if ($_POST['email'] != $_SESSION['user']['email']) {
                        if ($userManager->emailAvailable($_POST['email'])) {
                            $userManager->setEmail($_SESSION['user']['id'], $_POST['email']);
                            $feedback[] = "email edited";
                        } else {
                            $feedback[] = "email already registered";
                        }
                    }
                }

                if (!empty($_POST['pass']) && !empty($_POST['pass2'])) {
                    if ($_POST['pass'] == $_POST['pass2']) {
                        if (strlen($_POST['pass']) > 5) {
                            $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                            $userManager->setPassword($_SESSION['user']['id'], $hash);
                            $feedback[] = "password edited";
                        } else {
                            $feedback[] = "password too short";
                        }
                    } else {
                        $feedback[] = "pass and pass2 are different";
                    }
                }
            }
        }

        if (!empty($feedback)) {
            $userManager->loadInfo($_SESSION['user']['id']);
        }

        View::renderFront('profile.twig', ["session" => $_SESSION, "feedback" => $feedback]);

    }

    static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}