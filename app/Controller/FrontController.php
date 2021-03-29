<?php


namespace Blog\Controller;


use Blog\model\Articles;
use Blog\model\Comments;
use Blog\model\Db;

class FrontController extends AbstractController
{
    public function homeAction()
    {
        $articles = Articles::getArticles();
        $this->render("front" , "home.html.twig" , [
            'viewArticle' => $articles,
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

    private function register()
    {
        $pdo = Db::getDb();
    }

}
