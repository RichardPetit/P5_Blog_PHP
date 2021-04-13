<?php


namespace Blog\Controller;


use Blog\Entity\User;
use Blog\Model\Users;

class AbstractController
{
    protected function render(string $folder, string $viewName, array $content)
    {
        $path = dirname(__FILE__) . "/../view/$folder";
        $loader = new \Twig\Loader\FilesystemLoader($path);
        $twig = new \Twig\Environment($loader);

        echo $twig->render($viewName, $content);
    }

    protected function getUser() : User
    {
        return Users::getUser(1);
    }

}
