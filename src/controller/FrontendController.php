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

class FrontendController {
    
    public static function home(string $mailStatus = null): void {
        $view = new View();
        $view->renderFront('home.twig', ['title' => 'Accueil', 'mailStatus' => $mailStatus]);        
    }

    public static function blog(int $id = null): void {
        if ($id != null) {
            self::post($id);
            return;
        }
        self::listPosts();
    }
    
    public static function listPosts(): void {
        $view = new View();
        $postManager = new PostManager();
        $posts = $postManager->getPosts();
        $view->renderFront('listPosts.twig', ['title' => 'Blog', 'posts' => $posts]);
    }
    
    public static function post(int $id): void {
        $view = new View();
        $PSG = new PostSG();
        $session = new Session();
        $postManager = new PostManager();
        $commentManager = new CommentManager();
        $userManager = new UserManager();

        $post = $postManager->getPost($id);

        // if id not found, display error
        if (!$post) {
            self::error404();
            return;
        }

        $author = $userManager->getUserById((int)$post['id_user']);
        $feedback = "";

        if (!empty($PSG->get('comment'))) {
            $commentManager->postComment($id, (int)$session->get('user')['id'], $PSG->get('comment'));
            $feedback = "comment added";
        }

        $comments = $commentManager->getComments($id);

        $view->renderFront('post.twig', [
            'title' => 'Blog - '. $post['title'], 
            'author' => $author, 
            'post' => $post, 
            'comments' => $comments,
            'feedback' => $feedback
        ]);
    }

    public static function register(string $feedback = null): void {
        $view = new View();
        $PSG = new PostSG();
        $view->renderFront('register.twig', ['title' => 'Enregistrement', "feedback" => $feedback, 'post' => $PSG->getAll()]);
    }

    public static function registerCheck(): string {

        $PSG = new PostSG();
        $userManager = new UserManager();

        if (empty($PSG->get('email')) || empty($PSG->get('pass')) || empty($PSG->get('pass2'))) {
            return "missing information";
        }

        if (!$userManager->emailAvailable($PSG->get('email'))) return "email already used";
        if ($PSG->get('pass') != $PSG->get('pass2')) return "password verification failed";
        if (strlen($PSG->get('pass')) < 6) return "password too short";

        $hash = password_hash($PSG->get('pass'), PASSWORD_DEFAULT);
        $key = self::generateRandomString();
        $userManager->addUser("", "", "", $PSG->get('email'), "pending@$key", $hash);
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
        $view = new View();
        $PSG = new PostSG();
        if (!empty($PSG->get('email')) || !empty($PSG->get('pass'))) {
            $view->renderFront('login.twig', [
                'title' => 'Connexion / Enregistrement',
                'loginFailed' => true
            ]);
        } else {
            $view->renderFront('login.twig', ['title' => 'Connexion / Enregistrement']);
        }
    }

    public static function loginCheck(): bool {
        $userManager = new UserManager();
        $PSG = new PostSG();
        $session = new Session();
        $user = $userManager->checkUser($PSG->get('email'), $PSG->get('pass'));
        if ($user) {
            $session->set('user', $user);
            return true;
        } else {
            $session->forget('user');
            return false;
        }
   
    }

    public static function logout(): void {
        $session = new Session();
        $session->set('user', null);
    }

    public static function legal(): void {
        $view = new View();
        $view->renderFront('legal.twig', ['title' => 'Mention légales']);
    }

    public static function error404(): void {
        $view = new View();
        $view->renderFront('error404.twig', ['title' => 'Erreur 404']);
    }

    public static function sendMail(): bool {
        $PSG = new PostSG();
        $message = "Message de ". $PSG->get("name") ."\n". $PSG->get("email"). "\n". $PSG->get("message");
        return mail("yoann.leonard@gmail.com", "contact", $message);
    }

    public static function validation(): void {
        $view = new View();
        $GSG = new GetSG();
        $feedback = "";
        $key = !empty($GSG->get("key")) ? $GSG->get("key") : 0;
        $email = !empty($GSG->get("email")) ? $GSG->get("email") : 0;
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
        
        $view->renderFront('validation.twig', ['title' => 'Validation', "feedback" => $feedback]);
    }

    public static function profile(): void {

        $view = new View();
        $PSG = new PostSG();
        $session = new Session();
        $feedback = array();

        if (!empty($session->get('user')) && !empty($PSG->getAll())) {
            self::editEntry('name', $feedback);
            self::editEntry('first_name', $feedback);
            self::editEmail($feedback);
            self::editPassword($feedback);
        }

        if (!empty($feedback)) {
            $userManager = new UserManager();
            $userManager->loadInfo((int)$session->get('user')['id']);
        }

        $view->renderFront('profile.twig', ["title" => "Profil", "session" => $session->getAll(), "feedback" => $feedback]);

    }

    private static function editEntry(string $entry, array &$feedback): void {

        $PSG = new PostSG();
        $session = new Session();
        if (!empty($PSG->get($entry))) {

            if ($entry == "name") $entryC = "Name";
            if ($entry == "first_name") $entryC = "FirstName";
            $userManager = new UserManager();

            if ($PSG->get($entry) != $session->get('user')[$entry]) {
                $userManager->{"set". $entryC}((int)$session->get('user')['id'], $PSG->get($entry));
                $feedback[] = "$entry edited";
            }
        }
    }

    private static function editEmail(array &$feedback): void {

        $PSG = new PostSG();
        $session = new Session();
        if (!empty($PSG->get('email'))) {
            $userManager = new UserManager();
            if ($PSG->get('email') != $session->get('user')['email']) {
                if ($userManager->emailAvailable($PSG->get('email'))) {
                    $userManager->setEmail((int)$session->get('user')['id'], $PSG->get('email'));
                    $feedback[] = "email edited";
                } else {
                    $feedback[] = "email already registered";
                }
            }
        }
    }

    private static function editPassword(array &$feedback): void {
        
        $PSG = new PostSG();
        $session = new Session();
        if (!empty($PSG->get('pass')) && !empty($PSG->get('pass2'))) {
            $userManager = new UserManager();
            if ($PSG->get('pass') == $PSG->get('pass2')) {
                if (strlen($PSG->get('pass')) > 5) {
                    $hash = password_hash($PSG->get('pass'), PASSWORD_DEFAULT);
                    $userManager->setPassword((int)$session->get('user')['id'], $hash);
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