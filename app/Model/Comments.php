<?php

namespace Blog\Model;

use Blog\Entity\Comment;
use Blog\Exception\CommentNotFoundException;
use Blog\Model\Connector\PDO;

class Comments
{
    /**
     * @param int $id
     * @return Comment
     * @throws CommentNotFoundException
     * @throws \Blog\Exception\ArticleNotFoundException
     * @throws \Blog\Exception\UserNotFoundException
     */
    public static function getComment(int $id)
    {
        $pdo = PDO::getInstance();
        try {
            $req = $pdo->prepare("SELECT * FROM comments WHERE id = ? ");
            $req->execute([$id]);
            $comment = $req->fetch();
        } catch (\Exception $e) {
            echo "Une erreur c'est produite. L'article n'a pas été trouvé ou n'existe pas." . $e->getMessage();
        }
        if(!$comment) {
            throw new CommentNotFoundException('Comment not found');
        }
        return self::hydrateEntity($comment);
    }

    /**
     * @throws \Blog\Exception\ArticleNotFoundException
     * @throws \Blog\Exception\UserNotFoundException
     */
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

    /**
     * @param int $id
     * @param bool $moderateOnly
     * @return array
     * @throws \Blog\Exception\ArticleNotFoundException
     * @throws \Blog\Exception\UserNotFoundException
     */
    public static function getCommentsForArticle(int $id, bool $moderateOnly = true)
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

    /**
     * @param int $id
     * @return array
     * @throws \Blog\Exception\ArticleNotFoundException
     * @throws \Blog\Exception\UserNotFoundException
     */
    public static function getCommentsForArticleForAdmin(int $id)
    {
        return self::getCommentsForArticle($id, false);
    }

    /**
     * @param int $id
     * @param bool $validate
     */
    public static function changeValidationStatusForComment(int $id, bool $validate = true)
    {
        $pdo = PDO::getInstance();
        try {
            $validate = (int)$validate;
            $query = 'UPDATE comments SET is_valid = ? WHERE id = ?';
            $req = $pdo->prepare($query);
            $req->execute([$validate, $id]);
        }
        catch (\Exception $e) {
            echo "Erreur de connexion à la base de données pour les commentaires. Exception reçue : " . $e->getMessage();
        }
    }

    /**
     * @param int $id
     */
    public static function validateComment(int $id)
    {
        self::changeValidationStatusForComment($id);
    }

    /**
     * @param int $id
     */
    public static function invalidateComment(int $id)
    {
        self::changeValidationStatusForComment($id, false);
    }

    /**
     * @param Comment $comment
     * @return Comment
     */
    public  static function addComment(Comment $comment)
    {
        $pdo = PDO::getInstance();
        try {
            $userId = $comment->getAuthor()->getId();
            $articleId = $comment->getArticle()->getId();
            $content = $comment->getContent();
            $title = $comment->getTitle();
            $now = date("Y-m-d H:i:s");
            $sql = "INSERT INTO comments (articles_id, title, content, users_id, is_valid, date ) VALUES (?, ?, ?, ?, 0, ?)";
            $pdo->prepare($sql)->execute([$articleId, $title, $content, $userId, $now]);
        } catch (\Exception $e) {
            echo "Une erreur c'est produite, le commentaire n'a pas pu être ajouté." . $e->getMessage();
        }
        $comment->setId($pdo->lastInsertId());
        return $comment;
    }

    /**
     * @param Comment $comment
     * @return Comment
     */
    public static function edit(Comment $comment)
    {
        $pdo = PDO::getInstance();
        try {
            $commentId = $comment->getId();
            $userId = $comment->getAuthor()->getId();
            $articleId = $comment->getArticle()->getId();
            $content = $comment->getContent();
            $title = $comment->getTitle();
            $now = date("Y-m-d H:i:s");
            $sql = "UPDATE comments SET (title, content, users_id, articles_id, date ) VALUES (?, ?, ?, ?, ? ) WHERE id = $commentId ";
            $pdo->prepare($sql)->execute([$title, $content, $userId, $articleId, $now]);
        } catch (\Exception $e) {
            echo "Une erreur c'est produite, le commentaire n'a pas pu être modifié." . $e->getMessage();
        }
        return $comment;
    }

    /**
     * @param Comment $comment
     */
    public static function delete(Comment $comment)
    {
        $pdo = PDO::getInstance();
        try {
            $commentId = $comment->getId();
            $sql = "DELETE FROM comments WHERE id = ? ";
            $pdo->prepare($sql)->execute([$commentId]);
        } catch (\Exception $e) {
            echo "Une erreur c'est produite, le commentaire n'a pas pu être supprimé." . $e->getMessage();
        }
        header("Location: /admin");
    }

    /**
     * @param object $commentFromDb
     * @return Comment
     * @throws \Blog\Exception\ArticleNotFoundException
     * @throws \Blog\Exception\UserNotFoundException
     */
    private static function hydrateEntity(object $commentFromDb) : Comment
    {
        $commentEntity = new Comment();
        $commentEntity->setId($commentFromDb->id);
        $commentEntity->setTitle($commentFromDb->title);
        $commentEntity->setContent($commentFromDb->content);
        $commentEntity->setCreatedAt(new \DateTime($commentFromDb->date));
        $commentEntity->setIsValid((bool)$commentFromDb->is_valid);
        $author = Users::getUser($commentFromDb->users_id);
        $commentEntity->setAuthor($author);
        $article = Articles::getArticle($commentFromDb->articles_id);
        $commentEntity->setArticle($article);

        return $commentEntity;
    }
}
