<?php


namespace Blog\Controller;


use Blog\model\Users;

class UserController extends AbstractController
{


    public function users()
    {
//        if (isset($_GET['id'])){
            $this->users = new Users();
            $users = $this->users->getUsers();
            $this->render("front", "usersList.html.twig",[
                'viewUsers' => $users
            ]);
//        }
    }
}

