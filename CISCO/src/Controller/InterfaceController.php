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
        $response = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' 1.3.6.1.2.1.4.20.1.2 | grep "'.$num.'\>"');
        $ip = strstr(str_replace('iso.3.6.1.2.1.4.20.1.2.', '', $response), ' =', true);
        $mask = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' iso.3.6.1.2.1.4.20.1.3.'.$ip.' -Ov -Oq');
        if(strpos($name, '.')){
            if ($request->request->get('type_form')=='interface_modification') {
                $Vlan = str_replace('.', '',strstr($name, '.'));
                $response = $fonction_equipement->createSousInterface(
                    $request->request->get('nom'),
                    $request->request->get('ip'),
                    $request->request->get('mask'),
                    $Vlan
                );
                return $this->redirectToRoute('equipement');
            }

        }else{
            if ($request->request->get('type_form')=='interface_modification') {
                $response =$fonction_equipement->createInterface(
                    $request->request->get('nom'),
                    $request->request->get('ip'),
                    $request->request->get('mask')
                );
                return $this->redirectToRoute('equipement');
            }
        }
        $nameUrl =  str_replace("/", "-", $name);
        return $this->render('interface.html.twig', array(
            'interface_name' => $name,
            'ip' => $ip,
            'mask' => $mask,
            'nameUrl' => $nameUrl
        ));
    }

    /**
     * @Route("ajout/interface", name="ajout_interface")
     */
    public function ajoutInterface(Request $request,Fonction_equipement $fonction_equipement)
    {
        if ($request->request->get('type_form') !== null && $request->request->get('type_form') == 'interface_ajout') {
            $response = $fonction_equipement->createSousInterface(
                $request->request->get('nom'),
                $request->request->get('ip'),
                $request->request->get('mask'),
                $request->request->get('vlan')
            );
            dump($response);
            die;
            return $this->redirectToRoute('equipement');
        }
        $interface_liste = array();
        foreach ($_SESSION['interface_liste'][0] as $interface_name){
            if(!strpos($interface_name, '.')&&$interface_name != ''&&!strpos($interface_name, 'Vlan')&&!strpos($interface_name, 'Null')){
                array_push($interface_liste,  str_replace("\n","", str_replace('"', '',$interface_name)));
            }
        }
        return $this->render('interface_ajout.html.twig', array(
            'interface_names' => $interface_liste
        ));
    }
}
