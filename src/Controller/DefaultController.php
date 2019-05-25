<?php
namespace App\Controller;

//must add these namespaces
use App\Entity\Device;
use App\Entity\DeviceDriver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class DefaultController extends AbstractController
{

    /**
     * @Route("/admin/home", name="app_home")
     */
    public function index(){
        // Read all device controller and binded devices
        $repositoryDriver = $this->getDoctrine()->getRepository(DeviceDriver::class);
        $drivers = $repositoryDriver->findAll();
        return $this->render('home.html.twig', [
            "drivers" => $drivers
        ]);
    }

}
