<?php


namespace Blog\Controller;


use Blog\model\Articles;
use Blog\model\Comments;
use Blog\model\Db;
use PDO;

class FrontController extends AbstractController
{
    public function homeAction()
    {
        $article = Articles::getArticles();
        $comment = Comments::getComments();
        $this->render("front" , "home.html.twig" , [
            'viewArticle' => $article,
            'viewComment' => $comment
        ]);
    }

    public function listingAction()
    {
        $this->render("front" , "listing.html.twig" , [
            'articles' => Articles::getArticles()
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
    public  function newArticleAction()
    {
        $this->render("front", "createArticle.html.twig", []);
    }



    private function register()
    {
        $pdo = Db::getDb();
    }

}
