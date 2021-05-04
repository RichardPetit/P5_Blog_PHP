<?php


namespace Blog\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Entity\Article;
use Blog\Entity\Contact;
use Blog\Entity\User;
use Blog\Exception\ArticleNotFoundException;
use Blog\Exception\UserNotFoundException;
use Blog\Model\Articles;
use Blog\Model\Users;
use Blog\Service\EmailService;
use PHPMailer\PHPMailer\Exception;
use Blog\Model\Connector\PDO;


class FrontController extends AbstractController
{
    public function homeAction()
    {
        $articles = Articles::getArticles();
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

    public function detailArticleAction()
    {
        $id = $_GET['id'] ?? null;
        if (!$id || $id === null) {
            $this->redirectTo('?p=home');
        }
        try {
            $article = Articles::getArticle((int)$id);
        } catch (ArticleNotFoundException $e) {
            $this->redirectTo('?p=home');
        }
        $this->render("front", "detailArticle.html.twig", [
            'detailArticle' => $article,
        ]);
    }

    public function createUserAction()
    {
        $error = false;
        $msgError = "";
        $msgSuccess = "";
        $addUser = isset($_POST['add']); //true si le form est posté / false si on arrive sur la page (donc form non posté)
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
        //Si on arrive là c'est qu'on est pas dans le if($addUser) donc que le form est pas posté
        //Donc là on affiche le form

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
                $subject = $_POST['subjectContact'] ?? '';
                $contentMesssage = $_POST['contentContact'] ?? '';
                try {
                    $contact = Contact::create($email, $subject, $contentMesssage);
                    try {
                        $emailService = new EmailService();
                        $emailService->sendEmail($contact);
                        echo 'Le message a été envoyé';
                    } catch (Exception $e){
                        echo "Le message n'a pas été envoyé";
                    }
                } catch (AssertionFailedException $e) {
                    $error = true;
                    $msgError = "L'erreur suivante c'est produite : " . $e->getMessage();
                }
                // ajouter l'envoie d'email après la création du service après le !$error
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
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['pseudo'] = $user->getPseudo();
                    $_SESSION['email'] = $user->getEmail();
                    $this->redirectTo('?p=home');
                } catch (UserNotFoundException $e) {
                    $msgError = "Erreur d'identifiant. Pseudo ou mot de passe incorrect.";
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
        $this->redirectTo('?p=home');
    }

}
