<?php

namespace Blog\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Entity\Article;
use Blog\Exception\ArticleNotFoundException;
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
                    $this->redirectTo('admin');
                }
            }
        }
        $this->render('admin', 'createArticle.html.twig', [
            'msgError' => $msgError,
            'title' => '',
            'content' => '',
            'summary' => ''
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


    public function editArticleAction(int $id)
    {
        $msgError = "";

        try {
            $article = Articles::getArticle($id);
        } catch (ArticleNotFoundException $e) {
            $this->redirectTo('admin');
        }
        $author = $this->getUser();
        $title = $_POST['title'] ?? $article->getTitle();
        $content = $_POST['content'] ?? $article->getContent();
        $summary = $_POST['summary'] ?? $article->getSummary();

        $editArticle = isset($_POST['add']);

        if ($editArticle) {
            $msgError = $this->checkFormEditArticleAction();
            if ($msgError === ''){
                $article = Article::edit($title, $content, $summary, $author, $article);
                if(Articles::edit($article)) {
                    $this->redirectTo('admin');
                }
            }
        }
        $this->render('admin', 'createArticle.html.twig', [
            'msgError' => $msgError,
            'title' => $title,
            'content' => $content,
            'summary' => $summary
        ]);
    }

    private function checkFormEditArticleAction()
    {
        return $this->checkFormCreateArticleAction();
    }

    public function deleteArticleAction(int $id)
    {
        try {
            $article = Articles::getArticle($id);
            Articles::delete($article);
            $this->redirectTo('admin');
        } catch (ArticleNotFoundException $e) {
            $this->redirectTo('home');
        }
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
        $this->redirectToPath('/admin_users');
    }

    public function changeUserStatusInactiveAction(int $id)
    {
        try {
            $user = Users::getUser($id);
            Users::changeUserOnInactive($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $this->redirectToPath('/admin_users');
    }

    public function changeUserToAdminAction(int $id)
    {
        try {
            $user = Users::getUser($id);
            Users::changeUserToAdmin($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $this->redirectToPath('/admin_users');
    }

    public function changeAdminToUserAction(int $id)
    {
        try {
            $user = Users::getUser($id);
            Users::changeAdminToUser($id);
        }catch (\Exception $e) {
            $this->redirectTo('home');
        }
        $this->redirectToPath('/admin_users');
    }
}

