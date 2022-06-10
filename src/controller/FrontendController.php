<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\PostManager;
use App\Model\CommentManager;
use App\Model\GetSG;
use App\Model\UserManager;
use App\Model\View;
use App\Model\Session;
use App\Model\PostSG;

class FrontendController extends Controller
{
    public static function home(string $mailStatus = null): void {
        View::renderFront('home.twig', ['title' => 'Accueil', 'mailStatus' => $mailStatus]);        
    }

    public static function blog(int $id = null): void {
        if ($id != null) {
            self::post($id);
        } else {
            self::listPosts();
        }
    }
    
    public static function listPosts(): void {
        $postManager = new PostManager();
        $posts = $postManager->getPosts();
        View::renderFront('listPosts.twig', ['title' => 'Blog', 'posts' => $posts]);
    }
    
    public static function post(int $id): void {
        $postManager = new PostManager();
        $commentManager = new CommentManager();
        $userManager = new UserManager();

        $post = $postManager->getPost($id);
        $author = $userManager->getUserById((int)$post['id_user']);

    
        // if id not found, display error
        if (!$post) {
            self::error404();
        } else {

            $feedback = "";

            if (!empty(PostSG::get('comment'))) {
                $commentManager->postComment($id, (int)Session::get('user')['id'], PostSG::get('comment'));
                $feedback = "comment added";
            }

            $comments = $commentManager->getComments($id);

            View::renderFront('post.twig', [
                'title' => 'Blog - '. $post['title'], 
                'author' => $author, 
                'post' => $post, 
                'comments' => $comments,
                'feedback' => $feedback
            ]);
        }
    }

    public static function register(string $feedback = null): void {
        View::renderFront('register.twig', ['title' => 'Enregistrement', "feedback" => $feedback, 'post' => PostSG::getAll()]);
    }

    public static function registerCheck(): string {

        $userManager = new UserManager();

        if (empty(PostSG::get('email')) || empty(PostSG::get('pass')) || empty(PostSG::get('pass2'))) {
            return "missing information";
        }

        if (!$userManager->emailAvailable(PostSG::get('email'))) return "email already used";
        if (PostSG::get('pass') != PostSG::get('pass2')) return "password verification failed";
        if (strlen(PostSG::get('pass')) < 6) return "password too short";

        $hash = password_hash(PostSG::get('pass'), PASSWORD_DEFAULT);
        $key = FrontendController::generateRandomString();
        $userManager->addUser("", "", "", stripslashes(PostSG::get('email')), "pending@$key", $hash);
        return "user created";

    }

    public static function sendVerificationMail(string $email): bool {
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

    public static function login(): void {
        if (!empty(PostSG::get('email')) || !empty(PostSG::get('pass'))) {
            View::renderFront('login.twig', [
                'title' => 'Connexion / Enregistrement',
                'loginFailed' => true
            ]);
        } else {
            View::renderFront('login.twig', ['title' => 'Connexion / Enregistrement']);
        }
    }

    public static function loginCheck(): bool {
        $userManager = new UserManager();

        $user = $userManager->checkUser(PostSG::get('email'), PostSG::get('pass'));
        if ($user) {
            Session::set('user', $user);
            return true;
        } else {
            Session::forget('user');
            return false;
        }
   
    }

    public static function logout(): void {
        Session::set('user', null);
    }

    public static function legal(): void {
        View::renderFront('legal.twig', ['title' => 'Mention légales']);
    }

    public static function error404(): void {
        View::renderFront('error404.twig', ['title' => 'Erreur 404']);
    }

    public static function sendMail(): bool {
        $message = "Message de ". PostSG::get("name") ."\n". PostSG::get("email"). "\n". PostSG::get("message");
        return mail("yoann.leonard@gmail.com", "contact", $message);
    }

    public static function validation(): void {
        $feedback = "";
        $key = !empty(GetSG::get("key")) ? GetSG::get("key") : 0;
        $email = !empty(GetSG::get("email")) ? GetSG::get("email") : 0;
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
        
        View::renderFront('validation.twig', ['title' => 'Validation', "feedback" => $feedback]);
    }

    public static function profile(): void {

        $feedback = array();

        if (!empty(Session::get('user')) && !empty(PostSG::getAll())) {
            FrontendController::editEntry('name', $feedback);
            FrontendController::editEntry('first_name', $feedback);
            FrontendController::editEmail($feedback);
            FrontendController::editPassword($feedback);
        }

        if (!empty($feedback)) {
            $userManager = new UserManager();
            $userManager->loadInfo((int)Session::get('user')['id']);
        }

        View::renderFront('profile.twig', ["title" => "Profil", "session" => Session::getAll(), "feedback" => $feedback]);

    }

    private static function editEntry(string $entry, array &$feedback): void {

        if (!empty(PostSG::get($entry))) {

            if ($entry == "name") $entryC = "Name";
            if ($entry == "first_name") $entryC = "FirstName";
            $userManager = new UserManager();

            if (PostSG::get($entry) != Session::get('user')[$entry]) {
                $userManager->{"set". $entryC}((int)Session::get('user')['id'], PostSG::get($entry));
                $feedback[] = "$entry edited";
            }
        }
    }

    private static function editEmail(array &$feedback): void {

        if (!empty(PostSG::get('email'))) {
            $userManager = new UserManager();
            if (PostSG::get('email') != Session::get('user')['email']) {
                if ($userManager->emailAvailable(PostSG::get('email'))) {
                    $userManager->setEmail((int)Session::get('user')['id'], PostSG::get('email'));
                    $feedback[] = "email edited";
                } else {
                    $feedback[] = "email already registered";
                }
            }
        }
    }

    private static function editPassword(array &$feedback): void {
        
        if (!empty(PostSG::get('pass')) && !empty(PostSG::get('pass2'))) {
            $userManager = new UserManager();
            if (PostSG::get('pass') == PostSG::get('pass2')) {
                if (strlen(PostSG::get('pass')) > 5) {
                    $hash = password_hash(PostSG::get('pass'), PASSWORD_DEFAULT);
                    $userManager->setPassword((int)Session::get('user')['id'], $hash);
                    $feedback[] = "password edited";
                } else {
                    $feedback[] = "password too short";
                }
            } else {
                $feedback[] = "pass and pass2 are different";
            }
        }
    }

    private static function generateRandomString(int $length = 10): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}