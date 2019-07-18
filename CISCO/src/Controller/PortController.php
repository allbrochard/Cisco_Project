<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Fonction_equipement;

class PortController extends AbstractController
{
    /**
     * @Route("/port/{name}", name="port")
     */
    public function index($name)
    {
        $vlans = $_SESSION["vlanID"];
        dump($vlans);
        $name = str_replace('-', '/', $name);
        return $this->render('port.html.twig', [
            'port_name' => $name,
            'vlans' => $vlans,
        ]);
    }

    /**
     * @Route("/port/modif/{name}", name="port_modif")
     */
    public function modifPort(Request $request, Fonction_equipement $fonctionEquipement)
    {
        
    }
}
