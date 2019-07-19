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
        $ip = $_SESSION["ip_equipement"];
        $username = $_SESSION['user'];
        $userpswd = $_SESSION['mp_user'];
        $adminpswd = $_SESSION['mp_admin'];
        $portInfo = shell_exec('/script/show_port_info '.$ip.' '.$username.' '.$userpswd.' '.$adminpswd);
        dump($portInfo);
        $name = str_replace('-', '/', $name);
        return $this->render('port.html.twig', [
            'port_name' => $name,
            'vlans' => $vlans,
        ]);
    }

    /**
     * @Route("/port/modif", name="port_modif")
     */
    public function modifPort(Request $request, Fonction_equipement $fonctionEquipement)
    {
        $vlans = $_SESSION["vlanID"];

        $portName = $request->request->get('name');
        $typePort = $request->request->get('typePort');
        $vlan = $request->request->get('vlan');

        if($typePort == 'trunk'){
            $fonctionEquipement->createPortTrunk($portName);
        }elseif($typePort == 'access'){
            $fonctionEquipement->createPortAccess($portName, $vlan);
        }
        return $this->render('port.html.twig', [
            'port_name' => $portName,
            'vlans' => $vlans,
        ]);
    }
}
