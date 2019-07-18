<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InterfaceController extends AbstractController
{
    /**
     * @Route("/interface/{name}", name="interface")
     */
    public function index($name)
    {
        $comu = 'cisco';
        $name = str_replace(' ', '',str_replace('-', '/', $name));

        $response = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' 1.3.6.1.2.1.2.2.1.2 | grep \''.$name.'"\'');
        dump('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' 1.3.6.1.2.1.2.2.1.2 | grep \''.$name.'"\'');
        $num = strstr(str_replace('iso.3.6.1.2.1.2.2.1.2.', '', $response), ' =', true);
        dump('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' 1.3.6.1.2.1.4.20.1.2 | grep "'.$num.'>"');
        $response = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' 1.3.6.1.2.1.4.20.1.2 | grep "'.$num.'>"');
        $ip = strstr(str_replace('iso.3.6.1.2.1.4.20.1.2.', '', $response), ' =', true);
        dump($ip);
        $mask = shell_exec('snmpwalk -c '.$comu.' -v 2c '.$_SESSION['ip_equipement'].' iso.3.6.1.2.1.4.20.1.3.'.$ip.' -Ov -Oq');
        dump($mask);
        return $this->render('interface.html.twig', array(
            'interface_name' => $name,
            'ip' => $ip,
            'mask' => $mask
        ));
    }

    /**
     * @Route("/interface/ajout/", name="ajout_interface")
     */
    public function ajoutInterface(){

        return $this->render('interface_ajout.html.twig', array(
            'interface_name' => '',
        ));
    }
}
