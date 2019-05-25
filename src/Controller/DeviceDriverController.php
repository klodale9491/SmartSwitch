<?php

namespace App\Controller;

use App\Entity\DeviceDriver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;


class DeviceDriverController extends AbstractController
{

    /**
     * Add a new device driver
     * @Route("/admin/device_driver/new", name="add_device_driver")
     */
    public function addDeviceDriver(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $deviceDriver = new DeviceDriver();
        $form = $this->createFormBuilder($deviceDriver)
            ->add('name', TextType::class)
            ->add('type', TextType::class)
            ->add('mac', TextType::class)
            ->add('ip', TextType::class)
            ->add('port', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();
        $form->handleRequest($request);

        //  If form is submitted persist new data
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $deviceDriver = $form->getData();
            $entityManager->persist($deviceDriver);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        //  render form to insert data
        return $this->render('device_driver/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Delete an existing device driver
     * @Route("/admin/device_driver/{driver_id}/del", name="delete_device_driver")
     * */
    public function delete($driver_id){
        $entityManager = $this->getDoctrine()->getManager();
        $driver = $entityManager->find(DeviceDriver::class, $driver_id);
        if($driver){
            $entityManager->remove($driver);
            $entityManager->flush();
        }
        //  return to home
        return $this->redirectToRoute('app_home');
    }
}
