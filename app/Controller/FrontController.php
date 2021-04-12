<?php


namespace Blog\Controller;

use Blog\Model\Articles;

class FrontController extends AbstractController
{
    public function homeAction()
    {
        $articles = Articles::getArticles();
        $this->render("front" , "home.html.twig" , [
            'viewArticle' => $articles,
        ]);
    }

    public function articlesListingAction()
    {
        $this->render("front" , "listing.html.twig" , [
            'articles' => Articles::getArticles()
        ]);
    }
}
