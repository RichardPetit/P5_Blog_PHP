<?php


namespace Blog\Model;

use Blog\Entity\Article;
use Blog\Exception\ArticleNotFoundException;
use Blog\Model\Connector\PDO;

class Articles
{
    //Fonction qui récupère les articles en BDD
    public static function getArticles()
    {
        $pdo = PDO::getInstance();
        try {
            $articlesPDO = $pdo->query('SELECT * FROM articles ORDER BY id DESC limit 10');
        } catch (\Exception $e) {
            echo "Une erreur c'est produite." . $e->getMessage();
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
            echo "Une erreur c'est produite. L'article n'a pas été trouvé ou n'existe pas." . $e->getMessage();
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
           echo "Une erreur c'est produite, l'article n'a pas pu être ajouté." . $e->getMessage();
        }
        $article->setId($pdo->lastInsertId());
        return $article;
    }


    public static function edit(Article $article)
    {
        $pdo = PDO::getInstance();
        try {
            $articleId = $article->getId();
            $userId = $article->getAuthor()->getId();
            $content = $article->getContent();
            $summary = $article->getSummary();
            $title = $article->getTitle();
            $sql = "UPDATE articles SET (title, content, summary, users_id, date ) VALUES (?, ?, ?, ?, NOW()) WHERE id = ? ";
            $pdo->prepare($sql)->execute([$title, $content, $summary, $userId]);
        } catch (\Exception $e) {
            echo "Une erreur c'est produite, l'article n'a pas pu être modifié." . $e->getMessage();
        }
        return $article;
    }

    public static function delete(Article $article)
    {
        $pdo = PDO::getInstance();
        try {
            $articleId = $article->getId();
            $userId = $article->getAuthor()->getId();
            $content = $article->getContent();
            $summary = $article->getSummary();
            $title = $article->getTitle();
            $sql = "DELETE FROM articles WHERE id = ? ";
        } catch (\Exception $e) {
            echo "Une erreur c'est produite, l'article n'a pas pu être supprimé." . $e->getMessage();
        }
        header("Location: /admin");
        exit;
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
