<?php


namespace Blog\Controller;

use Blog\Entity\Article;
use Blog\Model\Articles;
use function var_dump;

class AdminController extends AbstractController
{

    public function createArticleAction()
    {
        $addArticle = isset($_POST['add']);
        $success = true;
        $errorMessage = '';

        if ($addArticle) {

            //1) Je récupère les paramètres
            //On récupère ici l'Entité User depuis la méthode getUser();
            $author = $this->getUser();

            //On récupère également le titre,content et summary depuis le $_POST
            $title = $_POST['title'] ?? 'Titre de test';
            $content = $_POST['content'] ?? 'Contenu de test';
            $summary = $_POST['summary'] ?? 'Summary de test';

            //2) Je crée l'entité Article avec les paramètres récupérés
            try {
                $article = Article::create($title, $content, $summary, $author);
            }catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                $success = false;
            }

            if($success) {
                //3) Je fais appel au model Articles pour créer une entrée en base de données depuis l'entité Article (2)
                Articles::add($article);
                $this->redirectTo('?p=home');
            }
        }
    }



}
