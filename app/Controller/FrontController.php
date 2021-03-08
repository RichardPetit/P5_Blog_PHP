<?php


namespace Blog\Controller;


class FrontController extends AbstractController
{
    public function homeAction()
    {
        $this->render("front" , "home.html.twig" , []);
    }
    public function listingAction()
    {
        $this->render("front" , "listing.html.twig" , []);

    }
}