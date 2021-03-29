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

    public function getArticle($id)
    {
        return $this->getOne('articles', 'Article', $id);
    }

    protected function getOne($table, $obj, $id)
    {
        $pdo = Db::getDb();
        $var = [];
        $req = self::$pdo->prepare("SELECT id, title, content, summary, DATE_FORMAT(date, '%d/%m/%Y à %Hh%imin%ss') 
        AS date FROM " .$table. " WHERE id = ?");
        $req->execute(array($id));
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)){
            $var[] = new $obj($data);
        }
        return $var;
    }

    public function createArticle($title, $content, $date)
    {
        if (isset($message)){
            echo $message;
        }
        $pdo = Db::getDb();
        if(isset($_POST['formArticle'])) {
            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);

            if (!empty($_POST['title']) and !empty($_POST['content'])) {
                $titleLength = strlen($title);
                if ($titleLength <= 100) {
                    $insertNewArticle = $pdo->prepare("INSERT INTO users(name, forname, email, password) VALUES (?, ?, ?)");
                    $insertNewArticle->execute(array($title, $content));
                    $message = "Votre compte à bien été créé.";
                    header('Location: index.php');
                }else{
                    $message = "Le titre est trop long";
                }
            }else {
                $message = "Tous les champs doivent être complétés";
            }
        }
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