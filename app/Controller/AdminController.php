<?php


namespace Blog\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Entity\Article;
use Blog\Model\Articles;
use Blog\Model\Comments;
use Blog\Model\Users;
use Blog\Route\Router;

class AdminController extends AbstractController
{

    public function __construct(Router $router)
    {
        parent::__construct($router);
        $this->redirectToHomeIfNotAdmin();

    }

    public function createArticleAction()
    {
        $error = false;
        $msgError = "";


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
                    $this->redirectTo('dashboard');
                }
            }
        }
        $this->render('admin', 'createArticle.html.twig', [
            'msgError' => $msgError,
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
            Assertion::notEmpty($summary, "Le champ du résumé doit être rempli.");
        } catch (AssertionFailedException $e){
            $error = $e->getMessage();
        }
        return $error;
    }


    public function editArticleAction()
    {
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
                    $msgSuccess = "Votre article à bien été modifié.";
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

    public function deleteArticleAction()
    {
        $deleteArticle = isset($_POST['delete']);


        if ($deleteArticle) {
            try {
                $article = Article::create($title, $content, $summary, $author);
            } catch (AssertionFailedException $e) {
                $error = true;
                $msgError = "L'erreur suivante s'est produite : " . $e->getMessage();
            }
            if (!$error && Articles::add($article)) {
                $msgSuccess = "Votre article à bien été créé.";
            }
        }
        $this->render('front', 'createArticle.html.twig', [
            'msgError' => $msgError,
            'msgSuccess' => $msgSuccess,
        ]);
    }



    public function dashboardAction()
    {
        $this->render('admin', 'dashboardAdmin.html.twig', [
            'articles' => Articles::getArticles(),
        ]);
    }

    public function usersAdminAction()
    {
        $this->render('admin', 'usersAdmin.html.twig', [
            'users' => Users::getAllUsers(),
        ]);
    }

    public function commentsListingAction($id)
    {
        $this->render("admin", "commentsAdmin.html.twig", [
            'comments' => Comments::getCommentsForArticleForAdmin($id),
            'article' => Articles::getArticle($id),
        ]);
    }

    public function validateCommentAction(int $id)
    {
        try {
            $comment = Comments::getComment($id);
            Comments::validateComment($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $articleId = $comment->getArticle()->getId();
        $this->redirectToPath('/comments/'.$articleId);
    }

    public function invalidateCommentAction(int $id)
    {
        try {
            $comment = Comments::getComment($id);
            Comments::invalidateComment($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $articleId = $comment->getArticle()->getId();
        $this->redirectToPath('/comments/'.$articleId);
    }

    public function changeUserStatusActiveAction(int $id)
    {
        try {
            $user = Users::getUser($id);
            Users::changeUserOnActive($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $userId= $user->getId();
        $this->redirectToPath('/profile/'.$userId);
    }

    public function changeUserStatusInactiveAction(int $id)
    {
        try {
            $user = Users::getUser($id);
            Users::changeUserOnInactive($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $userId = $user->getId();
        $this->redirectToPath('/profile/'.$userId);
    }

    public function changeUserToAdminAction(int $id)
    {
        try {
            $user = Users::getUser($id);
            Users::changeUserToAdmin($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $userId= $user->getId();
        $this->redirectToPath('/profile/'.$userId);
    }

    public function changeAdminToUserAction(int $id)
    {
        try {
            $user = Users::getUser($id);
            Users::changeAdminToUser($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $userId = $user->getId();
        $this->redirectToPath('/profile/'.$userId);
    }

}
