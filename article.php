<?php

if (!isset($_GET['id']) or !is_numeric($_GET['id']))
    header('location: index.php');
else {
    extract($_GET);
    $id = strip_tags($id);

    require_once('functions.php');
    if (!empty($_POST)){
        extract($_POST);
        $errors = array();

        $author = strip_tags($author);
        $comment = strip_tags($comment);
    }
    if (empty($author)){
        array_push($errors, 'Entrez un pseudo');
    }
    if (empty($comment)){
        array_push($errors, 'Entrez un commentaire');
    }
    if (count($errors) ==0)
    {
        $comment = $addComment($id, $author, $comment);
        $succes = 'Votre commentaire à bien été envoyé';
        unset($author);
        unset($comment);
    }

    $article = getArticle($id);
    $comments = getComment($id);
}
//strip_tags permet de supprimer le html et le code php de la variable
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $article->title ?>></title>
</head>
<body>
<a href="index.php">Retour aux articles</a>
    <h2><?= $article->title ?></h2>
    <time><?= $article->date ?></time>
    <p><?= $article->content ?></p>
    <br>
    <hr>

    <?php
    if (isset($succes)){
        echo $succes;
    }
    if (!empty($errors)) :?>
        <?php foreach ($errors as $error): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-danger"><?= $error ?><</div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php endif;?>
    <div class="row">
        <div class="col-md-6">
            <form action="article.php?id=<?= $article->id ?>" method="post">
                <p><label for="author"> Pseudo</label><br>
                    <input type="text" name="author" id="author" class="form-control" value="<?php if (isset($author)) echo $author ?>"></p>
                <p><label for="comment">Commentaire : </label><br>
                    <textarea name="comment" id="comment" cols="30" rows="5" class="form-control"
                              value="<?php if (isset($comment)) echo $comment ?>"></textarea></p>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>

    <h4>Commentaires : </h4>
<?php foreach ($comments as $com): ?>
    <h4><?= $com->author?></h4>
    <time><?= $com->date?> </time>
    <p><?= $com->comment?></p>
<?php endforeach; ?>
</body>