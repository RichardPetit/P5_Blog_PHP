<?php


namespace Blog\Controller;


use Blog\model\Users;

class UserController extends AbstractController
{


    public function users()
    {
        $this->users = new Users();
        $users = $this->users->getUsers();
        $this->render("front", "usersList.html.twig",[
            'viewUsers' => $users
        ]);
    }
}

