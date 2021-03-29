<?php


namespace Blog\Controller;


use Blog\model\Articles;

class ArticleController extends FrontController
{
    public function __construct()
    {
        if (isset($url) && count($url) < 1) {
            throw new \Exception("Page introuvable.");
        } else {
           $this->article();
        }
    }

    public function article()
    {
        if (isset($_GET['id'])){
            $this->article = new Articles();
            $article = $this->article->getArticles();
            $this->render("front", "createArticle.html.twig",[
            'viewArticle' => $article
            ]);
        }
    }
}