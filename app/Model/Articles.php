<?php


namespace Blog\Model;

use Blog\Entity\Article;
use Blog\Exception\ArticleNotFoundException;
use Blog\Model\Connector\PDO;
use function var_dump;

class Articles
{
    //Fonction qui récupère les articles en BDD
    public static function getArticles()
    {
        $pdo = PDO::getInstance();
        try {
            $articlesPDO = $pdo->query('SELECT * FROM articles ORDER BY id DESC limit 10');
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
        $articleEntities = [];
        foreach ($articlesPDO as $articlePDO) {
            $articleEntities[] = self::hydrateEntity($articlePDO);
        }

        return $articleEntities;
    }

    public static function getArticle(int $id)
    {
        $pdo = PDO::getInstance();
        try {
            $req = $pdo->prepare("SELECT * FROM articles WHERE id = ? ");
            $req->execute([$id]);
            $showArticle = $req->fetch();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }

        if(!$showArticle) {
            throw new ArticleNotFoundException('Article not found');
        }

        return self::hydrateEntity($showArticle);
    }

    public static function add(Article $article)
    {
        $pdo = PDO::getInstance();
        try {
            $userId = $article->getAuthor()->getId();
            $content = $article->getContent();
            $summary = $article->getSummary();
            $title = $article->getTitle();
            $sql = "INSERT INTO articles (title, content, summary, users_id, date ) VALUES (?, ?, ?, ?, NOW()) ";
            $pdo->prepare($sql)->execute([$title, $content, $summary, $userId]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
        $article->setId($pdo->lastInsertId());
        return $article;
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
        $articleEntity->setAuthor($author);

        return $articleEntity;
    }




}
