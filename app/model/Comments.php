<?php


namespace Blog\model;


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

        $comments->execute(array($id));
        $data = $comments->fetchAll();
        return $data;
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