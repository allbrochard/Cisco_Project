<?php

namespace App\Controller;

use App\Service\Fonction_equipement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InterfaceController extends AbstractController
{
    /**
     * @Route("/interface/{name}", name="interface")
     */
    public function index(Request $request,Fonction_equipement $fonction_equipement,$name)
    {
        $comu = 'cisco';
        $name = str_replace(' ', '',str_replace('-', '/', $name));

        $response = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' 1.3.6.1.2.1.2.2.1.2 | grep \''.$name.'"\'');
        $num = strstr(str_replace('iso.3.6.1.2.1.2.2.1.2.', '', $response), ' =', true);
        $response = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' 1.3.6.1.2.1.4.20.1.2 | grep "'.$num.'>"');
        dump('réponse ip :  '.$response);
        $ip = strstr(str_replace('iso.3.6.1.2.1.4.20.1.2.', '', $response), ' =', true);
        $mask = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' iso.3.6.1.2.1.4.20.1.3.'.$ip.' -Ov -Oq');
        if ($request->request->get('type_form')=='interface_ajout') {
            $fonction_equipement->createSousInterface(
                $request->request->get('nom'),
                $request->request->get('ip'),
                $request->request->get('mask'),
                $request->request->get('vlan')
            );
            return $this->redirectToRoute('equipement');
        }
        return $this->render('interface.html.twig', array(
            'interface_name' => $name,
            'ip' => $ip,
            'mask' => $mask
        ));
    }

    /**
     * @Route("/interface/ajout/{name}/{ip}/{mask]", name="ajout_interface")
     */
    public function ajoutInterface(Request $request,Fonction_equipement $fonction_equipement, $name, $ip, $mask){
        if (isset($request->request->get('type_form')) && $request->request->get('type_form')=='interface_ajout') {
            $fonction_equipement->createSousInterface(
                $request->request->get('nom'),
                $request->request->get('ip'),
                $request->request->get('mask'),
                $request->request->get('vlan')
            );
            return $this->redirectToRoute('equipement');
        }
        return $this->render('interface_ajout.html.twig', array(
            'interface_name' => $name,
            'interface_ip' => $ip,
            'interface_mask' => $mask 
        ));
    }
}
