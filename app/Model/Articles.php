<?php


namespace Blog\Model;

use Blog\Entity\Article;
use Blog\Model\Connector\PDO;

class Articles
{
    //Fonction qui récupère les articles en BDD
    public static function getArticles()
    {
        $pdo = PDO::getInstance();
        try {
            $articles = $pdo->query('SELECT * FROM articles ORDER BY id DESC limit 10');
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
        $articleEntities = [];
        foreach ($articles as $article) {
            $articleEntities[] = self::hydrateEntity($article);
        }

        return $articleEntities;
    }


    public static function hydrateEntity($articleFromDb): Article
    {
        $articleEntity = new Article();
        $articleEntity->setId($articleFromDb->id);
        $articleEntity->setTitle($articleFromDb->title);
        $articleEntity->setContent($articleFromDb->content);
        $articleEntity->setSummary($articleFromDb->summary);
        $articleEntity->setCreatedAt(new \DateTime($articleFromDb->date));
        $author = Users::getUser($articleFromDb->users_id);
        $authorEntity = Users::hydrateEntity($author);
        $articleEntity->setAuthor($authorEntity);

        return $articleEntity;
    }


}
