<?php


namespace Blog\Route;

class Router
{

    public array $routes = [
        'home' => ['controller' => 'FrontController', 'action' => 'homeAction', 'url' => '/home'],
        'articlesListing' => ['controller' => 'FrontController', 'action' => 'articlesListingAction', 'url' => '/listing'],
        'createArticle' => ['controller' => 'AdminController', 'action' => 'createArticleAction', 'url' => '/articles/add'],
        'detailArticle' => ['controller' => 'FrontController', 'action' => 'detailArticleAction', 'url' => '/articles/show'],
        'register' => ['controller' => 'FrontController', 'action' => 'createUserAction', 'url' => '/register'],
        'contact' => ['controller' => 'FrontController', 'action' => 'contactAction', 'url' => '/contact'],
        'login' => ['controller' => 'FrontController', 'action' => 'loginAction', 'url' => '/login'],
        'logout' => ['controller' => 'FrontController', 'action' => 'logoutAction', 'url' => '/logout'],
        'dashboard' => ['controller' => 'AdminController', 'action' => 'dashboardAction', 'url' => '/admin'],
        'usersAdmin' => ['controller' => 'AdminController', 'action' => 'usersAdminAction', 'url' => '/admin_users'],
        'profile' => ['controller' => 'FrontController', 'action' => 'profileAction', 'url' => '/profile'],
    ];

    public function run()
    {
        $route = $this->getRouteFromUri($_SERVER['REQUEST_URI']);
        if ($route) {
            $controllerName = 'Blog\\Controller\\'.$route['controller'];
            $actionName = $route['action'];
            $controller = new $controllerName($this);
            $controller->$actionName();
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
            if ($route['url'] === $uri) {
                return $route;
            }
        }
        return null;
    }

}
