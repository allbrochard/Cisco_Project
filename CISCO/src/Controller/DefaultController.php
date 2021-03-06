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
            $_SESSION['user'] = $username;
            $_SESSION['mp_user'] = $userpswd;
            $_SESSION['mp_admin'] = $adminpswd;
            $_SESSION['type'] = $type;
        }
        else{
            $username = $_SESSION['user'];
            $userpswd = $_SESSION['mp_user'];
            $adminpswd = $_SESSION['mp_admin'];
        }
        if ($request->request->get('type_form')=='equipement'){
            $response = $fonction_equipement->setEquipmentName($request->request->get('nameInput'));
        }

        $comu = 'cisco';
        if(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.9.1.3.30'), 'Switched')&& !isset($type)){
            $type='Switch';
        }elseif(strpos(shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1'), 'ISR')&& !isset($type)){
            $type='Router';
        }elseif(!isset($type)){
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
        $vlan = shell_exec('/script/show_vlan '.$ip.' '.$username.' '.$userpswd.' '.$adminpswd);
        $tok = strtok($vlan, "\n\r");
        $arrayVlan = array();
        while($tok !== false)
        {
            $tok = strtok("\n\r");
            array_push($arrayVlan, $tok);
        }
        $tabV = array();
        $tabVlan = array();
        foreach($arrayVlan as $vlans)
        {
            $vlan = substr($vlans, 0, strpos($vlans, " "));
            array_push($tabV, $vlan);
        }
        foreach($tabV as $vlan)
        {
            if (strlen($vlan) > 4 ){
                unset($vlan);
            }elseif ($vlan == "VLAN" || $vlan== "----")
            {
                unset($vlan);
            }elseif(strlen($vlan) == 0){
                unset($vlan);
            }else{
                array_push($tabVlan, $vlan);
            }
            
        }
        dump($tabVlan);
        $interfacesNames = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.2 -Ov');
        $interfacesStatusAdmin = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.7 -Ov');
        $interfacesStatusLinks = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' 1.3.6.1.2.1.2.2.1.8 -Ov');
        $tabNames = Array(explode("STRING:", $interfacesNames));
        $tabStatusAdmin = Array(explode("INTEGER:", $interfacesStatusAdmin));
        $tabStatusLinks = Array(explode("INTEGER:", $interfacesStatusLinks));
        $tabFinal = array();

        for($i = 1; $i <= count($tabNames[0])-1 ; $i++){
            $statutAdmin = 0;
            if (strpos($tabStatusAdmin[0][$i],"1")) {
                $statutAdmin = 1;
            }
            elseif(strpos($tabStatusLinks[0][$i],"2")) {
                $statutAdmin = 2;
            }
            $statutLink = 0;
            if (strpos($tabStatusLinks[0][$i],"1")) {
                $statutLink = 1;
            }
            elseif(strpos($tabStatusLinks[0][$i],"2")) {
                $statutLink = 2;
            }
            $name =  str_replace("/", "-", str_replace("\n","", str_replace('"', '',$tabNames[0][$i])));
            $originalName = str_replace("\n","", str_replace('"', '',$tabNames[0][$i]));
            if(strpos($tabNames[0][$i], 'Vlan') && $_SESSION['type']=='Switch'){ 
                
            }elseif(strpos( $tabNames[0][$i], 'Vlan')||strpos( $tabNames[0][$i], 'Null')){
                
            }else{
                $tab = array(
                    "NomInterface" => $name,
                    "StatutAdmin" => $statutAdmin,
                    "StatutLink" => $statutLink,
                    'originalName' => $originalName,
                );
                array_push($tabFinal, $tab);
            }
        }
        $_SESSION['tabFinal'] = $tabFinal;
        $_SESSION["vlanID"] = $tabVlan;
        $equipement = array(
            'nom' => $nom,
            'type' => $type,
            'domaine' => $domaine,
            'ip' => $ip,
            'info' => $tabFinal,
        );
        $_SESSION['equipement'] = $equipement;
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
        $_SESSION["ip_equipement"] = $ip;
        return $this->render('connexion.html.twig', array(
            'type' => $type
        ));
    }
}
