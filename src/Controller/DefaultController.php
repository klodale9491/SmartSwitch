<?php
namespace App\Controller;

//must add these namespaces
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class DefaultController extends AbstractController
{

    /**
     * @Route("/home", name="app_home")
     */
    public function login(){
        return $this->render('home.html.twig');
    }


}
