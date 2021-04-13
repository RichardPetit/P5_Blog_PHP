<?php


namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Exception\ArticleNotFoundException;
use Blog\Model\Articles;
use Blog\Model\Connector\PDO;
use function var_dump;

class FrontController extends AbstractController
{
    public function homeAction()
    {
        $articles = Articles::getArticles();
        $this->render("front", "home.html.twig", [
            'viewArticle' => $articles,
        ]);
    }

    public function articlesListingAction()
    {
        $this->render("front", "listing.html.twig", [
            'articles' => Articles::getArticles(),
        ]);
    }

    public function detailArticleAction()
    {
        //Récuperer l'ID de l'article à afficher
        $id = $_GET['id'] ?? null;

        if(!$id || $id === null) {
            $this->redirectTo('?p=home');
        }

        //Faire appel au modèle Articles avec une méthode getArticle($id)
        //Le modèle doit nous renvoyer l'Entité Article configurée depuis l'enregistrement DB
        try {
            $article = Articles::getArticle((int)$id);
        }catch (ArticleNotFoundException $e) {
            $this->redirectTo('?p=home');
        }

        //Renvoyer l'entité Article à la vue pour afficher le détail
        $this->render("front", "article.html.twig", [
            'detailArticle' => $article
        ]);
    }

    public function createArticleAction()
    {
        //Pour le moment on force l'ajout, par la suite bien sûr on ajoutera que lorsqu'on soumet un formulaire
        $addArticle = true;
        if ($addArticle) {
            //On récupère ici l'Entité User depuis la méthode getUser();
            $author = $this->getUser();
            //On récupère également le titre,content et summary depuis le $_POST
            $title = $_POST['title'] ?? 'Titre de test';
            $content = $_POST['content'] ?? 'Contenu de test';
            $summary = $_POST['summary'] ?? 'Summary de test';
            //On crée ici l'Entité Article avec les paramètres récupérés
            $article = Article::create($title, $content, $summary, $author);
            //On passe l'entité $article crée au Model Articles afin d'ajouter l'article en DB
            if (Articles::add($article)) {
                //Si ça a fonctionné on redirige vers la home (à voir si nécessaire)
                $this->redirectTo('/');
            }
        }

    }
}
