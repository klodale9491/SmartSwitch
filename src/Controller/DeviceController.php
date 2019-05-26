<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\DeviceDriver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class DeviceController extends AbstractController
{

    /**
     * Add device to controller
     * @Route("/admin/driver/{driver_id}/add", name="add_device_to_driver")
     */
    public function addDeviceToDriver(Request $request, $driver_id)
    {
        // just setup a fresh $task object (remove the dummy data)
        $device = new Device();
        $form = $this->createFormBuilder($device)
            ->add('name', TextType::class)
            ->add('type', TextType::class)
            ->add('relay', IntegerType::class)
            ->add('status', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();
        $form->handleRequest($request);

        //  If form is submitted persist new data
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $device = $form->getData();
            $driver = $entityManager->find(DeviceDriver::class, $driver_id);
            if($driver){
                $driver->addDevice($device);
                $entityManager->merge($driver);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_home');
        }

        //  render form to insert data
        return $this->render('device/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Edit an existing device
     * @Route("/admin/device/edit/{device_id}", name="edit_device")
     */
    public function editDeviceDriver(Request $request, $device_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $device = $entityManager->find( Device::class, $device_id);
        if($device){
            $form = $this->createFormBuilder($device)
                ->add('name', TextType::class)
                ->add('type', TextType::class)
                ->add('relay', IntegerType::class)
                ->add('status', IntegerType::class)
                ->add('save', SubmitType::class, ['label' => 'Edit'])
                ->getForm();
            $form->setData($device);
            $form->handleRequest($request);

            //  If form is submitted persist new data
            if ($form->isSubmitted() && $form->isValid()) {
                $device = $form->getData();
                $entityManager->merge($device);
                $entityManager->flush();
                return $this->redirectToRoute('app_home');
            }

            //  render form to insert data
            return $this->render('device_driver/form.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }


    /**
     * Remove device
     * @Route("/admin/device/{device_id}/del", name="remove_device")
     */
    public function removeDeviceFromController($device_id){
        $entityManager = $this->getDoctrine()->getManager();
        $device = $entityManager->find(Device::class, $device_id);
        if($device){
            $entityManager->remove($device);
            $entityManager->flush();
        }
        //  return to home
        return $this->redirectToRoute('app_home');
    }


    /**
     * Update status device
     * @Route("/admin/device/{device_id}/status/{status_val}", name="update_device")
     */
    public function updateDeviceStaus($device_id, $status_val){
        $entityManager = $this->getDoctrine()->getManager();
        $device = $entityManager->find(Device::class, $device_id);
        if($device){
            $url = "https://" . $device->getDriver()->getIp() . "relay/" . $device->getRelay() . "$status_val";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $head = curl_exec($ch);
            return $this->redirectToRoute('app_home');
        }
    }

}
