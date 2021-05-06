<?php


namespace Blog\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Entity\Article;
use Blog\Model\Articles;
use Blog\Model\Users;

class AdminController extends AbstractController
{

    public function createArticleAction()
    {
        $this->throwExceptionIfNotAdmin();

        $error = false;
        $msgError = "";
        $msgSuccess = "";


        $addArticle = isset($_POST['add']);

        if ($addArticle) {
            $msgError = $this->checkFormCreateArticleAction();
            if ($msgError === ''){
                $author = $this->getUser();
                $title = $_POST['title'] ?? '';
                $content = $_POST['content'] ?? '';
                $summary = $_POST['summary'] ?? '';
                try {
                    $article = Article::create($title, $content, $summary,$author);
                } catch (AssertionFailedException $e){
                    $error = true;
                    $msgError = "L'erreur suivante s'est produite : " . $e->getMessage();
                }
                if(!$error && Articles::add($article)) {
                    $msgSuccess = "Votre article à bien été créé.";
                }
            }
        }
        $this->render('front', 'createArticle.html.twig', [
            'msgError' => $msgError,
            'msgSuccess' => $msgSuccess,
        ]);
    }

    private function checkFormCreateArticleAction()
    {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $error = "";

        try {
            Assertion::notEmpty($title, "Le champ titre doit être rempli.");
            Assertion::minLength($title, 5, "Le titre doit faire au minimum 5 caractères.");
            Assertion::notEmpty($content, "Le champ du contenu doit être rempli.");
            Assertion::maxLength($summary, 250, "Le résumé doit faire au maximum 250 caractères.");

        } catch (AssertionFailedException $e){
            $error = $e->getMessage();
        }
        return $error;
    }


    public function editArticleAction()
    {
        $this->throwExceptionIfNotAdmin();

        $error = false;
        $msgError = "";
        $msgSuccess = "";


        $editArticle = isset($_POST['edit']);

        if ($editArticle) {
            $msgError = $this->checkFormEditArticleAction();
            if ($msgError === ''){
                $author = $this->getUser();
                $title = $_POST['title'] ?? '';
                $content = $_POST['content'] ?? '';
                $summary = $_POST['summary'] ?? '';
                try {
                    $article = Article::edit($title, $content, $summary,$author);
                } catch (AssertionFailedException $e){
                    $error = true;
                    $msgError = "L'erreur suivante s'est produite : " . $e->getMessage();
                }
                if(!$error && Articles::edit($article)) {
                    $msgSuccess = "Votre article à bien été créé.";
                }
            }
        }
        $this->render('front', 'createArticle.html.twig', [
            'msgError' => $msgError,
            'msgSuccess' => $msgSuccess,
        ]);
    }

    private function checkFormEditArticleAction()
    {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $error = "";
        try {
            Assertion::notEmpty($title, "Le champ titre doit être rempli.");
            Assertion::minLength($title, 5, "Le titre doit faire au minimum 5 caractères.");
            Assertion::notEmpty($content, "Le champ du contenu doit être rempli.");
            Assertion::maxLength($summary, 250, "Le résumé doit faire au maximum 250 caractères.");
        } catch (AssertionFailedException $e){
            $error = $e->getMessage();
        }
        return $error;
    }


    public function dashbordAction()
    {
//        $this->throwExceptionIfNotAdmin();

        $this->render('front', 'dashbordAdmin.html.twig', [
            'articles' => Articles::getArticles(),
        ]);
    }

    public function usersAdminAction()
    {
        $this->render('front', 'usersAdmin.html.twig', [
            'users' => Users::getAllUsers(),
        ]);
    }


}
