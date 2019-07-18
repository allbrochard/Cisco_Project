<?php
/**
 * Created by PhpStorm.
 * User: allan
 * Date: 15/07/2019
 * Time: 13:38
 */
namespace App\Service;

//use Symfony\Component\Validator\Constraints\Date;

class Fonction_equipement
{
//    public function indexFonction($action, $name){
//
//        if(isset($action) && function_exists($action)) {
//            $action = $action;
//            $var = isset($name) ? $name : null;
//            $getData = $action($var);
//            // do whatever with the result
//        }
//        switch($action) {
//            case('changer_nom'):
//                $response = $this->setEquipmentName($name);
//                break;
//            case 'get_conf':
//                $response = $this->getConf();
//                break;
//            default:
//                die('Access denied for this function!');
//        }
//        return $response;
//    }

    //fonctions utilisable peut importe le type de matériel
    function setEquipmentName($name){
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/change_hostname '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$name);
        return $response;
    }

    function getConf(){
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $conf = shell_exec('/script/show_run '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin);
        return $conf;
    }

    function activerInterface($interfaceName)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/up_interface '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName );
        return $response;
    }

    function desactiverInterface($interfaceName)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/down_interface '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName );
        return $response;
    }

    //fonction utilisable uniquement pour les switch
    function createPortTrunk($interfaceName)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/create_port_switch_trunk '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName );
        return $response;
    }

    function createPortAccess($interfaceName, $vlanId)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/create_port_switch_access '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName.' '.$vlanId );
        return $response;
    }

    function createVlan($vlanId, $vlanName)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/create_vlan '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$vlanId.' '.$vlanName );
        return $response;
    }

    function deletePort($interfaceName)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/delete_port_switch '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName );
        return $response;
    }

    function deleteVlan($vlanId)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/delete_vlan '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$vlanId );
        return $response;
    }

    //fonction utilisable uniquement pour les routeurs
    function createInterface($interfaceName, $ipInterface, $mask)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ipEquipement = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/create_interface '.$ipEquipement.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName.' '.$ipInterface.' '.$mask );
        return $response;
    }

    function createSousInterface($interfaceName, $ipInterface, $mask, $vlanId)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ipEquipement = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/create_sous_interface '.$ipEquipement.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName.' '.$ipInterface.' '.$mask.' '.$vlanId );
        return $response;
    }

    function deleteInterface($interfaceName)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/delete_interface '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName );
        return $response;
    }

    function deleteSousInterface($interfaceName)
    {
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/delete_sous_interface '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$interfaceName );
        return $response;
    }
}