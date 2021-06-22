<?php

namespace Blog\Route;

use function str_replace;

class Router
{
    /**
     * @var array|\string[][]
     */
    public array $routes = [
        'home' => ['controller' => 'FrontController', 'action' => 'homeAction', 'url' => '/home'],
        'articlesListing' => ['controller' => 'FrontController', 'action' => 'articlesListingAction', 'url' => '/articles'],
        'createArticle' => ['controller' => 'AdminController', 'action' => 'createArticleAction', 'url' => '/articles/add'],
        'editArticle' => ['controller' => 'AdminController', 'action' => 'editArticleAction', 'url' => '/edit-article/:id'],
        'detailArticle' => ['controller' => 'FrontController', 'action' => 'detailArticleAction', 'url' => '/articles/:id'],
        'deleteArticle' => ['controller' => 'AdminController', 'action' => 'deleteArticleAction', 'url' => '/delete-article/:id'],
        'register' => ['controller' => 'FrontController', 'action' => 'createUserAction', 'url' => '/register'],
        'contact' => ['controller' => 'FrontController', 'action' => 'contactAction', 'url' => '/contact'],
        'login' => ['controller' => 'FrontController', 'action' => 'loginAction', 'url' => '/login'],
        'logout' => ['controller' => 'FrontController', 'action' => 'logoutAction', 'url' => '/logout'],
        'admin' => ['controller' => 'AdminController', 'action' => 'dashboardAction', 'url' => '/admin'],
        'usersAdmin' => ['controller' => 'AdminController', 'action' => 'usersAdminAction', 'url' => '/admin_users'],
        'profile' => ['controller' => 'FrontController', 'action' => 'profileAction', 'url' => '/profile'],
        'comments' => ['controller' => 'AdminController', 'action' => 'commentsListingAction', 'url' => '/comments/:id'],
        'addComment' => ['controller' => 'FrontController', 'action' => 'addCommentAction', 'url' => '/add-comment/:id'],
        'validateComment' => ['controller' => 'AdminController', 'action' => 'validateCommentAction', 'url' => '/comments-validate/:id'],
        'invalidateComment' => ['controller' => 'AdminController', 'action' => 'invalidateCommentAction', 'url' => '/comments-invalidate/:id'],
        'activateUser' => ['controller' => 'AdminController', 'action' => 'changeUserStatusActiveAction', 'url' => '/active-user/:id'],
        'deactivateUser' => ['controller' => 'AdminController', 'action' => 'changeUserStatusInactiveAction', 'url' => '/deactivate-user/:id'],
        'userToAdmin' => ['controller' => 'AdminController', 'action' => 'changeUserToAdminAction', 'url' => '/pass-admin/:id'],
        'adminToUser' => ['controller' => 'AdminController', 'action' => 'changeAdminToUserAction', 'url' => '/pass-user/:id'],
    ];


    public function run()
    {
        $route = $this->getRouteFromUri($_SERVER['REQUEST_URI']);
        if ($route) {
            $controllerName = 'Blog\\Controller\\'.$route['route']['controller'];
            $actionName = $route['route']['action'];
            $controller = new $controllerName($this);
            if($route['params']) {
                $controller->$actionName(...$route['params']);
            }else{
                $controller->$actionName();
            }
        } else {
            header('Location: /home');
        }
    }

    /**
     * @param string $routeName
     * @return string|null
     */
    public function getUrlFromRouteName(string $routeName): ?string
    {
        if (isset($this->routes[$routeName])) {
            return $this->routes[$routeName]['url'];
        }
        return null;
    }

    /**
     * @param string $uri
     * @return array|null
     */
    private function getRouteFromUri(string $uri) : ?array
    {
        foreach($this->routes as $route) {
            $regex = $this->getRegex($route['url']);
            if(preg_match($regex, $uri, $matches)) {
                unset($matches[0]);
                return [
                  'route' => $route,
                  'params' => count($matches) > 0 ? $matches : null
                ];
            }
        }
        return null;
    }

    /**
     * @param string $uri
     * @return string
     */
    private function getRegex(string $uri): string
    {
        return "#".str_replace(':id','([0-9]+)', $uri)."$#";
    }
}
