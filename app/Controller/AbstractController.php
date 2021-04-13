<?php


namespace Blog\Controller;


use Blog\Entity\User;
use Blog\Model\Users;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AbstractController
{
    /**
     * @param string $folder
     * @param string $viewName
     * @param array $content
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function render(string $folder, string $viewName, array $content)
    {
        $path = dirname(__FILE__) . "/../view/$folder";
        $loader = new \Twig\Loader\FilesystemLoader($path);
        $twig = new \Twig\Environment($loader);

        echo $twig->render($viewName, $content);
    }

    /**
     * Returns the logged user
     * @return User
     */
    protected function getUser() : User
    {
        //On fait appel au modèle Users pour récupérer en dur pour le moment l'User avec l'ID 1
        return Users::getUser(1);
    }

    /**
     * Redirects to a given path
     * @param string $path
     */
    protected function redirectTo(string $path)
    {
        header('Location: '.$path);
        exit;
    }

}
