<?php


namespace Blog\Model;

use Blog\Entity\Comment;
use Blog\Model\Connector\PDO;


class Comments
{

    public static function getAllComments()
    {
        $pdo = PDO::getInstance();
        try {
            $commentsPDO = $pdo->query('SELECT * FROM comments');
        } catch (\Exception $e) {
            echo "Erreur de connexion à la base de données pour les commentaires. Exception reçue : " . $e->getMessage();
        }
        $commentEntities = [];
        foreach ($commentsPDO as $commentPDO) {
            $commentEntities[] = self::hydrateEntity($commentPDO);
        }

    }

    public static function getCommentsForArticle($id, $moderateOnly = true)
    {
        $pdo = PDO::getInstance();
        try {
            $query = 'SELECT * FROM comments WHERE  articles_id = ?';
            if ($moderateOnly){
                $query .= " AND is_valid = 1";
            }

            $req = $pdo->prepare($query);
            $req->execute([$id]);
        }
        catch (\Exception $e) {
            echo "Erreur de connexion à la base de données pour les commentaires. Exception reçue : " . $e->getMessage();
        }
        $commentsPDO = $req->fetchAll();


        $commentEntities = [];
        foreach ($commentsPDO as $comment) {
            $commentEntities[] = self::hydrateEntity($comment);
        }

        return $commentEntities;
    }

    public  static function addComment($articleId, $title, $comment)
    {
        $pdo = PDO::getInstance();
        $insertNewComment = $pdo->prepare('INSERT INTO comments (articles_id, title, content, users_id, date) VALUES (?, ?, ?, 1, NOW())');
        $insertNewComment->execute(array($articleId, $title, $comment));
        $insertNewComment->closeCursor();
    }

    public static function edit(Comment $comment)
    {
        $pdo = PDO::getInstance();
        try {
            $commentId = $comment->getId();
            $userId = $comment->getAuthor()->getId();
            $articleId = $comment->getArticle()->getId();
            $content = $comment->getContent();
            $title = $comment->getTitle();
            $sql = "UPDATE comments SET (title, content, users_id, articles_id, date ) VALUES (?, ?, ?, ?, NOW()) WHERE id = ? ";
            $pdo->prepare($sql)->execute([$title, $content, $userId, $articleId]);
        } catch (\Exception $e) {
            echo "Une erreur c'est produite, le commentaire n'a pas pu être modifié." . $e->getMessage();
        }
        return $comment;
    }

    public static function delete(Comment $comment)
    {
        $pdo = PDO::getInstance();
        try {
            $commentId = $comment->getId();
            $userId = $comment->getAuthor()->getId();
            $articleId = $comment->getArticle()->getId();
            $content = $comment->getContent();
            $title = $comment->getTitle();
            $sql = "DELETE FROM comments WHERE id = ? ";
        } catch (\Exception $e) {
            echo "Une erreur c'est produite, le commentaire n'a pas pu être supprimé." . $e->getMessage();
        }
        header("Location: /admin");
        exit;
    }


    private static function hydrateEntity($commentFromDb) : Comment
    {
        $commentEntity = new Comment();
        $commentEntity->setId($commentFromDb->id);
        $commentEntity->setTitle($commentFromDb->title);
        $commentEntity->setContent($commentFromDb->content);
        $commentEntity->setCreatedAt(new \DateTime($commentFromDb->date));
        $author = Users::getUser($commentFromDb->users_id);
        $commentEntity->setAuthor($author);
        $article = Articles::getArticle($commentFromDb->articles_id);
        $commentEntity->setArticle($article);

        return $commentEntity;
    }
}