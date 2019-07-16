<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="default")
     */
    public function index()
    {
        return $this->render('accueil.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    /**
     * @Route("/equipement", name="equipement")
     */
    public function getSupervisionEquipement()
    {
        return $this->render('supervisionEquipement.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
     /**
     * @Route("/confEquipement", name="confEquipement")
     */
    public function getConfEquipement()
    {
        return $this->render('recup_conf_equipement.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
