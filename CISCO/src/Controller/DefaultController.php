<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
        foreach ($liste_equipement as $key => $equipement){
            $ip = $equipement['ip'];
            $output = shell_exec('ping '.$ip.' -w 1 -c 1');
            if(strpos($output, ' 0%')==true){
                $comu = 'cisco';
                $nom = str_replace('"', '', shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.5.0 -Ov -Oq'));
                if(strpos($nom, '.')){
                    $domaine = strstr($nom, '.');
                    $nom = strstr($nom, '.', true);
                }
                $equipement['on'] = true;
            }else{
                $equipement['on'] = false;
            }
            $liste_equipement[$key] = $equipement;
        }
        $_SESSION['liste_equipement']=$liste_equipement;
        return $this->render('accueil.html.twig', [
            'controller_name' => 'DefaultController',
            'liste_equipement' => $liste_equipement
        ]);
    }

    /**
     * @Route("/equipement_supervision", name="equipement")
     */
    public function getSupervisionEquipement(Request $request)
    {
        $ip = $_SESSION['ip_equipement'];

        $username = $request->request->get('user');
        $userpswd = $request->request->get('userpswd');
        $adminpswd = $request->request->get('adminpswd');

        $_SESSION['user'] = $username;
        $_SESSION['mp_user'] = $userpswd;
        $_SESSION['mp_admin'] = $adminpswd;

        $comu = 'cisco';
        if(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.9.1.3.30'), 'Switched')){
            $type='Switch';
        }elseif(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1'), 'ISR')){
            $type='Router';
        }else{
            $type = 'Autre équipement Cisco';
        }
        $nom = str_replace('"', '', shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.5.0 -Ov -Oq'));
        if(strpos($nom, '.')){
            $domaine = strstr($nom, '.');
            $nom = strstr($nom, '.', true);
        }
        $interfacesNames = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.2 -Ov');
        $interfacesStatusAdmin = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.7');
        $interfacesStatusLinks = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.8');
        $tab = Array(str_split($interfacesNames, strpos($interfacesNames, "\n")));
        dump($interfacesNames);
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

    /**
     * @Route("equipement/connexion/{ip}", name="equipement_connexion")
     */
    public function connexionSupervision($ip)
    {
        $_SESSION["ip_equipement"] = $ip;
        return $this->render('connexion.html.twig');
    }
}
