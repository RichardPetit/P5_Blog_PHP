<?php


namespace Blog\Controller;


use Blog\model\Articles;
use Blog\model\Comments;
use Blog\model\Db;

class FrontController extends AbstractController
{
    public function homeAction()
    {
        $article = Articles::getArticles();
        $this->render("front" , "home.html.twig" , [
            'viewArticle' => $article,
        ]);
    }

    public function listingAction()
    {
        $this->render("front" , "listing.html.twig" , [
            'articles' => Articles::getArticles()
        ]);
    }

    public function detailsAction()
    {
        $id = $_GET['id'];
        $authorName= $_POST['author'] ?? '';
        $commentContent=$_POST['comment'] ?? '';
        $commentTitle=$_POST['title'] ?? '';
        $commentSubmitted = false;
        $comments = Comments::getComments($id);

        if (isset($_POST['add'])){
            Comments::addComment($id, $commentTitle, $commentContent);
            $commentSubmitted = true;
        }

        $this->render("front" , "detailsArticle.html.twig" , [
            'article' => Articles::getOne($id),
            'authorName' => $authorName,
            'commentContent' => $commentContent,
            'commentTitle' => $commentTitle,
            'commentSubmitted' => $commentSubmitted,
            'comments' => $comments
        ]);
    }

    public function contactAction()
    {
        $this->render("front" , "contact.html.twig" , []);
    }

    public function registerAction()
    {
        $this->render("front", "register.html.twig", []);
    }
    public function connectAction()
    {
        $this->render("front", "connect.html.twig", []);
    }
    public  function addArticleAction()
    {
        $articleTitle= $_POST['title'] ?? '';
        $articleContent= $_POST['content'] ?? '';
        $articleSummary= $_POST['summary'] ?? '';
        $articleSubmitted = false;
        $article = Articles::getArticles();

        if (isset($_POST['add'])){
            Articles::addArticle($articleTitle, $articleContent, $articleSummary);
            $articleSubmitted = true;
        }

        $this->render("front", "createArticle.html.twig", [
           'newArticle' => Articles::addArticle($articleTitle, $articleContent, $articleSummary),
            'titleArticle' => $articleTitle,
            'contentArticle' => $articleContent,
            'summaryArticle' => $articleSummary,
            'article' => $article
        ]);
    }



    private function register()
    {
        $pdo = Db::getDb();
    }

}
