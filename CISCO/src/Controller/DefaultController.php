<?php

namespace App\Controller;

use App\Service\Fonction_equipement;
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
                }else{
                    $domaine='';
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
    public function getSupervisionEquipement(Request $request, Fonction_equipement $fonction_equipement)
    {
        $ip = $_SESSION['ip_equipement'];
        if($request->request->get('type_form')=='connexion') {
            $username = $request->request->get('user');
            $userpswd = $request->request->get('userpswd');
            $adminpswd = $request->request->get('adminpswd');
            $type = $request->request->get('type');
            dump($type);
            $_SESSION['user'] = $username;
            $_SESSION['mp_user'] = $userpswd;
            $_SESSION['mp_admin'] = $adminpswd;
            $_SESSION['type'] = $type;
        }
        if ($request->request->get('type_form')=='equipement'){
            $response = $fonction_equipement->setEquipmentName($request->request->get('nameInput'));
        }

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
        }else{
            $domaine='';
        }
        $domaine=str_replace('.', '', $domaine);
        $interfacesNames = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.2 -Ov');
        $interfacesStatusAdmin = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.7 -Ov');
        $interfacesStatusLinks = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.8 -Ov');
        $tabNames = Array(explode("STRING:", $interfacesNames));
        $tabStatusAdmin = Array(explode("INTEGER:", $interfacesStatusAdmin));
        $tabStatusLinks = Array(explode("INTEGER:", $interfacesStatusLinks));
        $tabFinal = array();
        $tabVlan = array();

        for($i = 1; $i < count($tabNames[0])-1 ; $i++){
            $statutAdmin = 0;
            if (strpos($tabStatusAdmin[0][$i],"1")) {
                $statutAdmin = 1;
            }
            elseif(strpos($tabStatusAdmin[0][$i],"2")) {
                $statutAdmin = 2;
            }
            if(strpos($tabNames[0][$i], 'Vlan') && $_SESSION['type']=='Switch'){ 
                $tabV = array(
                    "NomInterface" => $tabNames[0][$i],
                    "StatutAdmin" => $statutAdmin,
                    "StatutLink" => $tabStatusLinks[0][$i]
                );
                array_push($tabVlan, $tabV);
            }elseif(strpos( $tabNames[0][$i], 'Vlan')||strpos( $tabNames[0][$i], 'Null')){
                
            }else{
                $tab = array(
                    "NomInterface" => $tabNames[0][$i],
                    "StatutAdmin" => $statutAdmin,
                    "StatutLink" => $tabStatusLinks[0][$i]
                );
                array_push($tabFinal, $tab);
            }
        }

        $equipement = array(
            'nom' => $nom,
            'type' => $type,
            'domaine' => $domaine,
            'ip' => $ip,
            'info' => $tabFinal,
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
        $comu = 'cisco';
        if(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.9.1.3.30'), 'Switched')){
            $type='Switch';
        }elseif(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1'), 'ISR')){
            $type='Router';
        }else{
            $type = 'Autre équipement Cisco';
        }
        dump($type);
        $_SESSION["ip_equipement"] = $ip;
        return $this->render('connexion.html.twig', array(
            'type' => $type
        ));
    }
}
