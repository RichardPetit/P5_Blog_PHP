<?php


namespace Blog\Model;



use Blog\Entity\Comment;
use Blog\Model\Connector\PDO;
use function var_dump;

class Comments
{
    public static function getComments()
    {
        $pdo = PDO::getInstance();
        try {
            $commentsPDO = $pdo->query("SELECT * FROM comments WHERE articles_id = ?");
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
        $commentEntities = [];
        foreach ($commentsPDO as $commentPDO){
            $commentEntities[] = self::hydrateEntity($commentPDO);
        }
        return$commentEntities;
    }

    public static function add(Comment $comment)
    {
        $pdo = PDO::getInstance();
        try {
            $articleId = $comment->getArticle()->getId();
            $userId = $comment->getAuthor()->getId();
            $title = $comment->getTitle();
            $content = $comment->getContent();
            $sql = "INSERT INTO comments (title, content, users_id, articles_id, date) VALUES (?, ?, ?, ?, NOW())";
            $pdo->prepare($sql)->execute([$title, $content, $articleId, $userId]);
        } catch (\Exception $e){
            var_dump($e->getMessage());
            exit();
        }
        $comment->setId($pdo->lastInsertId());
        return $comment;
    }

    private static function hydrateEntity($commentFromDb): Comment
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