<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\DeviceDriver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class DeviceController extends AbstractController
{


    /**
     * @Route("/device/new", name="device_new")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $device = new Device();
        $form = $this->createFormBuilder($device)
            ->add('name', TextType::class)
            ->add('type', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();
        $form->handleRequest($request);

        //  If form is submitted persist new data
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $device = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($device);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        //  render form to insert data
        return $this->render('device/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/device/bind", name="device_bind")
     */
    public function bind(Request $request)
    {
        // bind device to controller
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryDriver = $this->getDoctrine()->getRepository(DeviceDriver::class);
        $repositoryDevice = $this->getDoctrine()->getRepository(Device::class);

        $deviceDrivers = $repositoryDriver->findAll();
        $controllerNames = [];
        foreach($deviceDrivers as $controller){
            $controllerNames[] = $controller->getName();
        }
        $devices = $repositoryDevice->findAll();
        $deviceNames = [];
        foreach($devices as $device){
            $deviceNames[] = $device->getName();
        }
        $form = $this->createFormBuilder()
            ->add('driver', ChoiceType::class, $controllerNames)
            ->add('device', ChoiceType::class, $deviceNames)
            ->add('save', SubmitType::class, ['label' => 'Bind'])
            ->getForm();
        $form->handleRequest($request);


        //  If form is submitted persist new data
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $deviceDriver = $repositoryDriver->findOneBy([
                "name" => $formData["driver"]
            ]);
            $device = $repositoryDevice->findOneBy([
                "name" => $formData["device"]
            ]);
            if($deviceDriver && $device){
                $deviceDriver->addDevice($device);
                $entityManager->merge($deviceDriver);
                $entityManager->flush();
                return $this->redirectToRoute('app_home');
            }
        }

        //  render form to insert data
        return $this->render('device/bind_device.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
