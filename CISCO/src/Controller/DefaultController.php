<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/equipement", name="equipement_liste")
     */
    public function index()
    {
        $liste_equipement = $_SESSION['liste_equipement'];
        foreach ($liste_equipement as $equipement){
            $ip = $equipement['ip'];
            $output = shell_exec('ping '.$ip.' -w 1 -c 1');
            dump($output);
            if(strpos($output, ' 0%')==true){
                $equipement['on'] = true;
            }else{
                $equipement['on'] = false;
                dump($equipement);
            }
        }
        dump('DefaultController Début');
        dump($liste_equipement);
        $_SESSION['liste_equipement']=$liste_equipement;
        dump('DefaultController fin');
        dump($liste_equipement);
        return $this->render('accueil.html.twig', [
            'controller_name' => 'DefaultController',
            'liste_equipement' => $liste_equipement
        ]);
    }
    /**
     * @Route("equipement/{ip}", name="equipement")
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
        $domaine = strstr($nom, '.');
        $nom = strstr($nom, '.', true);
        $equipement = array(
            'nom' => $nom,
            'type' => $type,
            'domaine' => $domaine,
            'ip' => $ip,
        );
        return $this->render('supervisionEquipement.html.twig', [
            'controller_name' => 'DefaultController',
            'equipement' => $equipement,
        ]);
    }
}
