<?php


namespace Blog\model;


use Blog\Entity\Comment;
use Blog\Entity\Article;

class Comments
{
    private $_id;
    private $_title;
    private $_content;
    private $_date;

    //Fonction qui ajoute un commentaire en BDD
    public  static function addComment($articleId, $title, $comment)
    {
        $pdo = Db::getDb();
        $insertNewComment = $pdo->prepare('INSERT INTO comments (articles_id, title, content, users_id, date) VALUES (?, ?, ?, 1, NOW())');
        $insertNewComment->execute(array($articleId, $title, $comment));
        $insertNewComment->closeCursor();
    }
//Fonction qui récupère les commentaires d'un article
    public static function getComments($id)
    {
        $pdo = Db::getDb();
        try {
            $comments = $pdo->prepare('SELECT * FROM comments WHERE  articles_id = ?');

        }
        catch (\Exception $e) {
            echo "Erreur de connexion à la base de données pour les commentaires. Exception reçue : " . $e->getMessage();
        }
//        $commentEntities = [];
//        foreach ($comments as $comment) {
//            $commentEntities[] = self::getEntity($comment);
//        }
        $comments->execute(array($id));
        $data = $comments->fetchAll();
        return $data;
    }

    private static function getEntity($commentFromDb) : Comment
    {
        $commentEntity = new Comment();
        $commentEntity->setId($commentFromDb->id);
        $commentEntity->setTitle($commentFromDb->title);
        $commentEntity->setContent($commentFromDb->content);
        $commentEntity->setCreatedAt($commentFromDb->date);
        $author = Users::getProfile($commentFromDb->users_id);
        $authorEntity = Users::getEntity($author);
        $commentEntity->setAuthor($authorEntity);
        $article = Articles::getOne($commentFromDb->articles_id);
        $articleEntity = Articles::getEntity($article);
        return $commentEntity;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $id = (int)$id;
        if ($id > 0)
            $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        if (is_string($title))
            $this->_title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        if (is_string($content))
            $this->_content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->_date;
    }
}