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
        dump($name);
        dump($_SESSION['tabFinal']);
        foreach ($_SESSION['tabFinal'] as $tab){
            if($tab['NomInterface'] == $name){
                $statutAdmin = $tab['StatutAdmin'];
            }
        }

        $portInfo = shell_exec('/script/show_port_info '.$ip.' '.$username.' '.$userpswd.' '.$adminpswd);
        $tok = strtok($portInfo, "\n\r");
        $arrayPort = array();
        while($tok !== false)
        {
            $tok = strtok("\n\r");
            array_push($arrayPort, $tok);
        }
        $name = str_replace('-', '/', $name);
        return $this->render('port.html.twig', [
            'port_name' => $name,
            'vlans' => $vlans,
            'status_admin' => $statutAdmin,
        ]);
    }

    /**
     * @Route("/modif/port", name="port_modif")
     */
    public function modifPort(Request $request, Fonction_equipement $fonctionEquipement)
    {
        $vlans = $_SESSION["vlanID"];

        $portName = $request->request->get('name');
        $typePort = $request->request->get('typePort');
        $vlan = $request->request->get('vlan');

        $equipement = $_SESSION['equipement'];
        if($typePort == 'trunk'){
            $response = $fonctionEquipement->createPortTrunk($portName);
        }elseif($typePort == 'access'){
            $response = $fonctionEquipement->createPortAccess($portName, $vlan);
        }

        if($request->request->get('etat') == '1'){
            $response = $fonctionEquipement->activerInterface($portName);
        }else{
            $response = $fonctionEquipement->desactiverInterface($portName);
        }



        return $this->render('supervisionEquipement.html.twig', [
            'equipement' => $equipement,
        ]);
    }
}
