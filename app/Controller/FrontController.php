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

    public function createArticleAction()
    {
        //Si on a pas de requête (pas de formulaire soumis) => afficher la vue twig avec le formulaire
        $author = $this->getUser();
        $title = $_POST['title'] ?? 'Titre de test';
        $content = $_POST['title'] ?? 'Contenu de test';
        $summary = $_POST['title'] ?? 'Summary de test';

        //Si on aune requête elle doit traiter la requête => créer l'article
    }
}
