<?php


namespace Blog\model;


use mysql_xdevapi\Exception;

class Articles extends Db
{
    private $_id;
    private $_title;
    private $_content;
    private $_summary;
    private $_picture;
    private $_date;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    public function hydrate()
    {
        foreach ($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        }
    }


    //Fonction qui récupère les articles en BDD
    public static function getArticles()
    {
        $pdo = Db::getDb();
        try {
            $articles = $pdo->query('SELECT * FROM articles ORDER BY id DESC limit 10');

        }
        catch (\Exception $e ){
            var_dump($e->getMessage());
            exit();
        }
        return $articles;
    }

    //Fonction qui récupère un article en BDD
    public function getArticle($id)
    {
        return $this->getOne('articles', 'Article', $id);
    }

    public static function getOne($id)
    {
        $pdo = Db::getDb();
        $req = $pdo->prepare("SELECT id, title, content, summary, DATE_FORMAT(date, '%d/%m/%Y à %Hh%imin%ss') 
        AS date FROM articles WHERE id = ?");
        $req->execute(array($id));
        $data = $req->fetch(\PDO::FETCH_ASSOC);
        return $data;
    }

    //Fonction qui ajoute un article en BDD
    public static function addArticle($title, $content, $summary)
    {
        //Puisqu'on s'occupe de la validation des donnees dans le controlleur (ou dans l'entité, on verra),
        //pas besoin de tester les paramètres ici, on se contente d'executer la requête et de récupérer le résultat de l'insert
        $pdo = Db::getDb();
        $insertNewArticle = $pdo->prepare("INSERT INTO articles(title, content, summary,users_id, date ) VALUES (?, ?, ?, 1, NOW())");
        return $insertNewArticle->execute([$title, $content, $summary]);
    }


    public function setId($id)
    {
        $id = (int)$id;
        if ($id > 0){
            $this->_id = $id;
        }
    }
    public function getId()
    {
        return $this->_id;
    }

    public function setTitle($title)
    {
        if (is_string($title)){
            $this->_title = $title;
        }
    }
    public function getTitle()
    {
        return $this->_title;
    }

    public function setContent($content)
    {
        if (is_string($content)){
            $this->_content = $content;
        }
    }
    public function getContent()
    {
        return $this->_content;
    }

    public function setSummary($summary)
    {
        if (is_string($summary)){
            $this->_summary = $summary;
        }
    }
    public function getSummary()
    {
        return $this->_summary;
    }

    public function setPicture($picture)
    {
        if (is_string($picture)){
            $this->_picture = $picture;
        }
    }
    public function getPicture()
    {
        return $this->_picture;
    }

    public function setDate($date)
    {
        $this->_date = $date;
    }
    public function getDate()
    {
        return $this->_date;
    }


}
