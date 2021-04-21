<?php


namespace Blog\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Entity\Article;
use Blog\Entity\User;
use Blog\Exception\ArticleNotFoundException;
use Blog\Model\Articles;
use Blog\Model\Users;

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
        //Récuperer l'ID de l'article à afficher
        $id = $_GET['id'] ?? null;

        if (!$id || $id === null) {
            $this->redirectTo('?p=home');
        }

        //Faire appel au modèle Articles avec une méthode getArticle($id)
        //Le modèle doit nous renvoyer l'Entité Article configurée depuis l'enregistrement DB
        try {
            $article = Articles::getArticle((int)$id);
        } catch (ArticleNotFoundException $e) {
            $this->redirectTo('?p=home');
        }

        //Renvoyer l'entité Article à la vue pour afficher le détail
        $this->render("front", "article.html.twig", [
            'detailArticle' => $article,
        ]);
    }

    public function createArticleAction()
    {
        //Pour le moment on force l'ajout, par la suite bien sûr on ajoutera que lorsqu'on soumet un formulaire
        $addArticle = true;
        if ($addArticle) {
            //On récupère ici l'Entité User depuis la méthode getUser();
            $author = $this->getUser();
            //On récupère également le titre,content et summary depuis le $_POST
            $title = $_POST['title'] ?? 'Titre de test';
            $content = $_POST['content'] ?? 'Contenu de test';
            $summary = $_POST['summary'] ?? 'Summary de test';
            //On crée ici l'Entité Article avec les paramètres récupérés
            $article = Article::create($title, $content, $summary, $author);
            //On passe l'entité $article crée au Model Articles afin d'ajouter l'article en DB
            if (Articles::add($article)) {
                //Si ça a fonctionné on redirige vers la home (à voir si nécessaire)
                $this->redirectTo('/');
            }
        }

    }

    public function createUserAction()
    {
        /**
         * Ici, ce qu'il faut comprendre c'est que cette action a 2 raisons d'être :
         *
         * Si on poste le paramètre add depuis le formulaire => on soumet donc le formulaire et on récupère les params
         * Sinon on affiche le formulaire.
         *
         * Donc on teste juste en dessous : $addUser = isset($_POST['add']);
         * Le isset renverra false si jamais le formulaire n'est pas posté et il renverra true si le form est posté
         *
         */
        $error = false;
        $msgError = "";
        $msgSuccess = "";
        $addUser = isset($_POST['add']); //true si le form est posté / false si on arrive sur la page (donc form non posté)
        if ($addUser) {
            //Donc là c'est le cas où le form est posté
            //Fais bien attention à ce que tes input (leur name précisément) corresponde bien aux $_POST ci-dessous
            //Donc si ton front a pour input name="pseudoRegister" alors il faudra récupérer $_POST['pseudoRegister']
            //Je te laisse faire la modif ;)
            $msgError = $this->checkFormForCreateUserAction();
            if($msgError === '') {
                $pseudo = $_POST['pseudo'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                try {
                    $user = User::create($pseudo, $email, $password);
                } catch (AssertionFailedException $e) {
                    $error = true;
                    $msgError = "L'erreur suivante s'est produite: " . $e->getMessage();
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
            Assertion::notEmpty($pseudo, 'Le champs Pseudo ne peut pas être vide.');
            Assertion::notEmpty($email, 'Le champs email doit être rempli.');
            Assertion::email($email, 'Le format de l\'adresse email n\'est pas valide');
            Assertion::eq($email, $email2, 'Les 2 emails doivent être identiques');
            Assertion::notEmpty($password, 'Le champs password doit être rempli.');
            Assertion::eq($password, $password2, 'Les 2 mots de passes doivent être identiques');
            Assertion::minLength($password, 6, 'Le mot de passe doit faire au moins 6 caractères.');


        } catch (AssertionFailedException $e) {
            $error = $e->getMessage();
        }

        return $error;
    }
}