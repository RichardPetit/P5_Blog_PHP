<?php

namespace Blog\Controller;

use Blog\Entity\User;
use Blog\Exception\UserNotFoundException;
use Blog\Model\Users;
use Blog\Service\Router;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AbstractController
{
    private Router $router;

    /**
     * AbstractController constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }


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
        $path = dirname(__FILE__)."/../view/$folder";
        $loader = new \Twig\Loader\FilesystemLoader($path);
        $twig = new \Twig\Environment($loader);

        $content['router'] = new Router();
        $content['isLoggedIn'] = $this->isLoggedIn();
        $content['userLogged'] = $this->getUser();

        echo $twig->render($viewName, $content);
    }

    /**
     * Returns the logged user
     * @return User
     * @throws UserNotFoundException
     */
    protected function getUser(): ?User
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        $userId = $_SESSION['id'];

        //On fait appel au modèle Users pour récupérer en dur pour le moment l'User avec l'ID 1
        return Users::getUser($userId);
    }

    /**
     * Redirects to a given route
     * @param string $routeName
     */
    protected function redirectTo(string $routeName)
    {
        $path = $this->router->getUrlFromRouteName($routeName);
        header('Location: '.$path);
        exit;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['id']);
    }

    protected function isAdmin(): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        return $this->getUser()->isAdmin();
    }

    protected function throwExceptionIfNotLoggedIn()
    {
        if (!$this->isLoggedIn()) {
            throw new Exception();
        }
    }

    protected function throwExceptionIfNotAdmin()
    {
        if (!$this->isAdmin()) {
            throw new Exception();
        }
    }

}
