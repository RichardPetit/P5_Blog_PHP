<?php


namespace Blog\Controller;

use Blog\model\Articles;

class AdminController extends AbstractController
{
    public function addArticleAction()
    {
        $articleTitle = $_POST['title'] ?? '';
        $articleContent = $_POST['content'] ?? '';
        $articleSummary = $_POST['summary'] ?? '';
        $articleSubmitted = false;

        if (isset($_POST['add'])) {
            //@TODO : La validation des donnees sera faite ici, je t'expliquerai comment faire prochaine session
            $article = Articles::addArticle($articleTitle, $articleContent, $articleSummary);
            if($article) {
                $articleSubmitted = true;
            }
        }

        $this->render("front", "createArticle.html.twig", [
            'articleTitle' => $articleTitle,
            'articleContent' => $articleContent,
            'articleSummary' => $articleSummary,
            'articleSubmitted' => $articleSubmitted,
        ]);
    }

}
