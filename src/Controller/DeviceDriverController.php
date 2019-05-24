<?php

namespace App\Controller;

use App\Entity\DeviceDriver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class DeviceDriverController extends AbstractController
{

    /**
     * @Route("/device/driver/new", name="device_driver_new")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $deviceDriver = new DeviceDriver();
        $form = $this->createFormBuilder($deviceDriver)
            ->add('type', TextType::class)
            ->add('mac', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Device-Driver'])
            ->getForm();
        $form->handleRequest($request);

        //  If form is submitted persist new data
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $deviceDriver = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($deviceDriver);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        //  render form to insert data
        return $this->render('device_driver/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
