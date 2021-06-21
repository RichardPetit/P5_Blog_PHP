<?php


namespace Blog\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Entity\Comment;
use Blog\Entity\Contact;
use Blog\Entity\User;
use Blog\Exception\ArticleNotFoundException;
use Blog\Exception\UserNotActiveException;
use Blog\Exception\UserNotFoundException;
use Blog\Model\Articles;
use Blog\Model\Comments;
use Blog\Model\Users;
use Blog\Service\EmailService;


class FrontController extends AbstractController
{
    public function homeAction()
    {
        $articles = Articles::getArticles(10);
        $this->render("front", "home.html.twig", [
            'viewArticle' => $articles,
        ]);
    }

    public function articlesListingAction()
    {
        $this->render("front", "listing.html.twig", [
            'articles' => Articles::getArticles(),
        ]);
    }

    public function detailArticleAction(int $id)
    {
        try {
            $article = Articles::getArticle($id);
            $comments = Comments::getCommentsForArticle($id);
        } catch (ArticleNotFoundException $e) {
            $this->redirectTo('home');
        }
        $this->render("front", "detailArticle.html.twig", [
            'detailArticle' => $article,
            'commentsArticle' => $comments,
        ]);
    }

    public function createUserAction()
    {
        $error = false;
        $msgError = "";
        $msgSuccess = "";
        $addUser = isset($_POST['add']);
        if ($addUser) {
            $msgError = $this->checkFormForCreateUserAction();
            if($msgError === '') {
                $pseudo = $_POST['pseudo'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                try {
                    $user = User::create($pseudo, $email, $password);
                } catch (AssertionFailedException $e) {
                    $error = true;
                    $msgError = "L'erreur suivante s'est produite : " . $e->getMessage();
                }
                if (!$error && Users::add($user)) {
                    $msgSuccess = "Votre compte à bien été créé.";
                }
            }
        }
        $this->render('front', 'register.html.twig', [
            'msgError' => $msgError,
            'msgSuccess' => $msgSuccess,
        ]);
    }

    /**
     * @return string
     */
    private function checkFormForCreateUserAction(): string
    {
        $pseudo = $_POST['pseudo'] ?? '';
        $email = $_POST['email'] ?? '';
        $email2 = $_POST['email2'] ?? '';
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $error = '';
        try {
            Assertion::notEmpty($pseudo, 'Le champ Pseudo ne peut pas être vide.');
            Assertion::notEmpty($email, 'Le champ email doit être rempli.');
            Assertion::email($email, 'Le format de l\'adresse email n\'est pas valide');
            Assertion::eq($email, $email2, 'Les 2 emails doivent être identiques');
            Assertion::notEmpty($password, 'Le champ password doit être rempli.');
            Assertion::eq($password, $password2, 'Les 2 mots de passes doivent être identiques');
            Assertion::minLength($password, 6, 'Le mot de passe doit faire au moins 6 caractères.');
        } catch (AssertionFailedException $e) {
            $error = $e->getMessage();
        }
        return $error;
    }

    public function contactAction()
    {
        $error = false;
        $msgError = "";
        $msgSuccess = "";
        $contactMessage = isset($_POST['add']);
        if ($contactMessage){
            $msgError = $this->checkFormForContactAction();
            if ($msgError === ''){
                $email = $_POST['emailContact'] ?? '';
                $name = $_POST['nameContact'] ?? '';
                $subject = $_POST['subjectContact'] ?? '';
                $contentMessage = $_POST['contentContact'] ?? '';
                try {
                    $contact = Contact::create($email, $name, $subject, $contentMessage);
                    $emailService = new EmailService();
                    $emailService->sendEmail($contact);
                } catch (\Exception $e) {
                    $error = true;
                    $msgError = "L'erreur suivante c'est produite : " . $e->getMessage();
                }
                if (!$error) {
                    $msgSuccess = "Votre message a été envoyé. Vous receverez la réponse par email dans les plus brefs délais.";
                }
            }
        }
        $this->render('front', 'contact.html.twig', [
            'msgError' => $msgError,
            'msgSuccess' => $msgSuccess,
        ]);
    }

    private function checkFormForContactAction()
    {
        $email = $_POST['emailContact'] ?? '';
        $subject = htmlspecialchars($_POST['subjectContact']) ?? '';
        $contentMesssage = htmlspecialchars($_POST['contentContact']) ?? '';
        $error = "";
        try {
            Assertion::notEmpty($email, 'Le champ email doit être rempli.');
            Assertion::email($email, 'Le format de l\'adresse email n\'est pas valide');
            Assertion::notEmpty($subject, 'Le champ sujet doit être rempli.');
            Assertion::minLength($subject, 4, "Le sujet doit faire au minimum 10 caractères");
            Assertion::notEmpty($contentMesssage, 'Le champ message doit être rempli.');
        } catch (AssertionFailedException $e){
            $error = $e->getMessage();
        }
        return $error;
    }

    public function loginAction()
    {
        $msgError = "";
        $userConnection = isset($_POST['submit']);
        if ( $userConnection){
            $msgError = $this->checkFormConnectAction();
            if ($msgError === ''){
                $email = $_POST['emailConnect'] ?? '';
                $password = $_POST['passwordConnect'] ?? '';
                try {
                    $user = Users::getUserByEmail($email);
                    $user->verifyPassword($password);
                    $user->verifyStatus();
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['pseudo'] = $user->getPseudo();
                    $_SESSION['email'] = $user->getEmail();
                    $this->redirectTo('home');
                } catch (UserNotFoundException $e) {
                    $msgError = "Erreur d'identifiant. Pseudo ou mot de passe incorrect.";
                } catch (UserNotActiveException $e){
                    $msgError = "Votre compte est inactif, merci de contacter l'administrateur.";
                }
            }
        }
        $this->render("front", "login.html.twig", [
            'msgError' => $msgError,
        ]);
    }

    private function checkFormConnectAction()
    {
        $email = $_POST['emailConnect'];
        $password = $_POST['passwordConnect'];
        $error = '';
        try {
            Assertion::notEmpty($email, "Le champ Pseudo doit être rempli.");
            Assertion::notEmpty($password, "Le champ mot de passe doit être rempli.");
            Assertion::email($email, "l'email saisi n'est pas valide.");
        } catch (AssertionFailedException $e){
            $error = $e->getMessage();
        }
        return $error;
    }

    public function logoutAction()
    {
        $_SESSION = [];
        session_destroy();
        $this->redirectTo('home');
    }

    public function profileAction()
    {
        $this->redirectToHomeIfNotLoggedIn();
        $userLogged = $this->getUser();
        $this->render("front", "profile.html.twig", [
            'user' => $userLogged,
        ]);
    }


    public function addCommentAction($articleId)
    {
        $this->redirectToHomeIfNotLoggedIn();
        $error = false;
        $msgError = "";
        $commentSubmitted = false;
        $article = Articles::getArticle($articleId);

        $addComment= isset($_POST['add']);

        if ($addComment) {
            $msgError = $this->checkFormCreateCommentAction();
            if ($msgError === ''){
                $author = $this->getUser();
                $title = $_POST['title'] ?? '';
                $content = $_POST['content'] ?? '';
                try {
                    $comment = Comment::create($title, $content, $author, $article );
                } catch (AssertionFailedException $e){
                    $error = true;
                    $msgError = "L'erreur suivante s'est produite : " . $e->getMessage();
                }
                if(!$error && Comments::addComment($comment)) {
                    $commentSubmitted = true;

                }
            }
        }
        $this->render('front', 'detailArticle.html.twig', [
            'msgError' => $msgError,
            'title' => '',
            'content' => '',
            'commentSubmitted' => $commentSubmitted,
            'detailArticle' => $article,
        ]);
    }

    private function checkFormCreateCommentAction()
    {
        $title = $_POST['title'] ?? '';
        $comment = $_POST['content'] ?? '';
        $error = "";

        try {
            Assertion::notEmpty($title, "Le champ titre doit être rempli.");
            Assertion::minLength($title, 5, "Le titre doit faire au minimum 5 caractères.");
            Assertion::notEmpty($comment, "Le champ du contenu doit être rempli.");
           } catch (AssertionFailedException $e){
            $error = $e->getMessage();
        }
        return $error;
    }
}
