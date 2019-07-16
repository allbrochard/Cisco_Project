<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        $liste_equipement = array();
        $minPing = 245;
        for ($i = 255; $i > $minPing; $i--){
            $ip = '172.25.200.'.$i;
            $comu = 'cisco';
            $output = shell_exec('ping '.$ip.' -w 1 -c 1');
            if(strpos($output, ' 0%')==true){
                $output = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.1.0');
                if(strpos($output, 'Cisco')){
                    if(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.9.1.3.30'), 'Switched')){
                        $type='Switch';
                    }else{
                        $type='Rooter';
                    }
                    $nom = str_replace('"', '', shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.5.0 -Ov -Oq'));
                    array_push($liste_equipement, array(
                        'nom' => $nom,
                        'type' => $type,
                        'ip' => $ip,
                     ));
                }
            }
        }
//        dump($liste_equipement);
        return $this->render('accueil.html.twig', [
            'controller_name' => 'DefaultController',
            'liste_equipement' => $liste_equipement
        ]);
    }
    /**
     * @Route("/equipement/{ip}", name="equipement")
     */
    public function getSupervisionEquipement($ip)
    {
        $comu = 'cisco';
        if(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.9.1.3.30'), 'Switched')){
            $type='Switch';
        }else{
            $type='Rooter';
        }
        $nom = str_replace('"', '', shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.5.0 -Ov -Oq'));
        $equipement = array(
            'nom' => $nom,
            'type' => $type,
            'ip' => $ip,
        );
        return $this->render('supervisionEquipement.html.twig', [
            'controller_name' => 'DefaultController',
            'equipement' => $equipement,
        ]);
    }
}
