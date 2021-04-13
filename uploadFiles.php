<!DOCTYPE html>
    <html>
        <body>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="uploaded_file"> <br>
                <input type="submit" name="submit"> <br>
            </form>
        </body>
    </html>


<?php

if (isset($_POST['submit'])) {
    $maxSize = 50000;
    $validExt = array('.jpg', '.jpeg', '.gif', '.png');
    if ($_FILES['uploaded_file']['error'] > 0) {
        echo 'Une erreur est survenue lors du transfert';
        die;
    }
    $fileSize = $_FILES['uploaded_file']['size'];

    if ($fileSize > $maxSize) {
        echo 'Le fichier est trop gros.';
        die;
    }
    $fileName = $_FILES['uploaded_file']['name'];

    $fileExt = "." . strtolower(substr(strrchr($fileName, '.'), 1));
    if (!in_array($fileExt, $validExt)) {
        echo "Le fichier n'est pas au bon format";
        die;
    }
    $tmpName = $_FILES['uploaded_file']['tmp_name'];
    $uniqueName = md5(uniqid(rand(), true));
    $fileName = "upload/" . $uniqueName  . $fileExt;
    $result = move_uploaded_file($tmpName, $fileName);
    if($result)
    {
        echo "l'image a bien été chargée";
    }
}