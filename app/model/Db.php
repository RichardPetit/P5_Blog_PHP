<?php


namespace Blog\model;


class Db
{
    const DATABASE_HOST_DEFAULT= "localhost:3306";
    const DATABASE_NAME_DEFAULT= "blog";
    const DATABASE_USER_DEFAULT= "root";
    const DATABASE_PWD_DEFAULT= "root:";

    protected static $db = null;

    public static function getDb()
    {
        if (is_null(self::$db)) {
            $host= $_ENV['DATABASE_HOST'] ?? self::DATABASE_HOST_DEFAULT;
            $dbName=$_ENV['DATABASE_NAME'] ?? self::DATABASE_NAME_DEFAULT;
            $dbUser=$_ENV['DATABASE_USER'] ?? self::DATABASE_USER_DEFAULT;
            $dbPwd=$_ENV['DATABASE_PWD'] ?? self::DATABASE_PWD_DEFAULT;
            $pdo= new \PDO("mysql:dbname=$dbName;host=$host", $dbUser, $dbPwd);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // permet d'indiquer qu'on veut des exceptions en cas d'erreur
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ); //demande  que ça renvoi sous forme d'objet et non d'un tableau
            self:: $db = $pdo;
        }
        return self::$db;
    }

    protected function getAll($table, $obj)
    {
        $this->getDb();
        $var = [];
        $req = self::$db->prepare('SELECT * FROM ' .$table .' ORDER BY id DESC');
        $req->execute();
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)){
            $var[] = new $obj($data);
        }
    }

}