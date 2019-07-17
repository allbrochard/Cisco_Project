<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        if(!isset($_SESSION['liste_equipement'])){
            session_start();
        }
        $liste_equipement = array();
        $minPing = 245;
        for ($i = 255; $i > $minPing; $i--){
            $ip = '172.25.200.'.$i;
            $comu = 'cisco';
            $output = shell_exec('ping '.$ip.' -w 1 -c 1');
            if(strpos($output, ' 0%')==true){
                $output = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.1.0');
                if(strpos($output, 'Cisco')){
                    $type = strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.9.1.3.30'), 'Switched');
                    if($type){
                        $type='Switch';
                    }elseif($type==null){
                        $type='non connue';
                    }else{
                        $type='Router';
                    }
                    $nom = str_replace('"', '', shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.5.0 -Ov -Oq'));
                    if(strpos($nom, '.')){
                        $domaine = strstr($nom, '.');
                        $nom = strstr($nom, '.', true);
                    }
                    array_push($liste_equipement, array(
                        'nom' => $nom,
                        'domaine' => $domaine,
                        'type' => $type,
                        'ip' => $ip,
                        'on' => true,
                    ));
                }
            }
        }
        dump('indexController');
        dump($liste_equipement);
        $_SESSION['liste_equipement']=$liste_equipement;
        return $this->redirectToRoute('equipement_liste');
    }
}
