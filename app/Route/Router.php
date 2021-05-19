<?php


namespace Blog\Route;

use function str_replace;

class Router
{

    public array $routes = [
        'home' => ['controller' => 'FrontController', 'action' => 'homeAction', 'url' => '/home'],
        'articlesListing' => ['controller' => 'FrontController', 'action' => 'articlesListingAction', 'url' => '/articles'],
        'createArticle' => ['controller' => 'AdminController', 'action' => 'createArticleAction', 'url' => '/articles/add'],
        'detailArticle' => ['controller' => 'FrontController', 'action' => 'detailArticleAction', 'url' => '/articles/:id'],
        'register' => ['controller' => 'FrontController', 'action' => 'createUserAction', 'url' => '/register'],
        'contact' => ['controller' => 'FrontController', 'action' => 'contactAction', 'url' => '/contact'],
        'login' => ['controller' => 'FrontController', 'action' => 'loginAction', 'url' => '/login'],
        'logout' => ['controller' => 'FrontController', 'action' => 'logoutAction', 'url' => '/logout'],
        'dashboard' => ['controller' => 'AdminController', 'action' => 'dashboardAction', 'url' => '/admin'],
        'usersAdmin' => ['controller' => 'AdminController', 'action' => 'usersAdminAction', 'url' => '/admin_users'],
        'profile' => ['controller' => 'FrontController', 'action' => 'profileAction', 'url' => '/profile/:id'],
        'comments' => ['controller' => 'AdminController', 'action' => 'commentsListingAction', 'url' => '/comments/:id'],
        'validateComment' => ['controller' => 'AdminController', 'action' => 'validateCommentAction', 'url' => '/comments-validate/:id'],
        'invalidateComment' => ['controller' => 'AdminController', 'action' => 'invalidateCommentAction', 'url' => '/comments-invalidate/:id'],
        'activateUser' => ['controller' => 'AdminController', 'action' => 'changeUserStatusActiveAction', 'url' => '/profile/:id'],
        'deactivateUser' => ['controller' => 'AdminController', 'action' => 'changeUserStatusInactiveAction', 'url' => '/profile/:id'],
        'userToAdmin' => ['controller' => 'AdminController', 'action' => 'changeUserToAdminAction', 'url' => '/profile/:id'],
        'adminToUser' => ['controller' => 'AdminController', 'action' => 'changeAdminToUserAction', 'url' => '/profile/:id'],
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
            exit;
        }
    }

    public function getUrlFromRouteName(string $routeName): ?string
    {
        if (isset($this->routes[$routeName])) {
            return $this->routes[$routeName]['url'];
        }
        return null;
    }

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

    private function getRegex(string $uri): string
    {
        return "#".str_replace(':id','([0-9]+)', $uri)."$#";
    }

}
