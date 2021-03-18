<?php


namespace Blog\Controller;


use PDO;

class FrontController extends AbstractController
{
    public function homeAction()
    {
        $article = $this->getArticles();
        $this->render("front" , "home.html.twig" , [
            'viewArticle' => $article
        ]);
        $comment = $this->getComments();
        $this->render("front", "home.html.twig", [
            'viewComment' => $comment
        ]);
    }

    public function listingAction()
    {
        $this->render("front" , "listing.html.twig" , []);

    }

    public function contactAction()
    {
        $this->render("front" , "contact.html.twig" , []);
    }



    private function getArticles()
    {
        $pdo = new PDO('mysql:dbname=blog;host=localhost:3309', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // permet d'indiquer qu'on veut des exceptions en cas d'erreur
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); //demande  que ça renvoi sous forme d'objet et non d'un tableau
        $articles = $pdo->query('SELECT * FROM articles ORDER BY id DESC limit 10');
        return $articles;
    }

    private function getComments()
    {
        $pdo = new PDO('mysql:dbname=blog;host=localhost:3309', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $comments = $pdo->query('SELECT * FROM comments ORDER BY id');
        return $comments;
    }
}