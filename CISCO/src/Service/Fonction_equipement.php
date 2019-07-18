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
    public function indexFonction($action, $name){

        if(isset($action) && function_exists($action)) {
            $action = $action;
            $var = isset($name) ? $name : null;
            $getData = $action($var);
            // do whatever with the result
        }
        switch($action) {
            case('changer_nom'):
                $response = $this->setEquipmentName($name);
                break;
            case 'get_conf':
                $response = $this->getConf();
                break;
            default:
                die('Access denied for this function!');
        }
        return $response;
    }
    function setEtatEquipement($bool){

    }

    function setEquipmentName($name){
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/change_hostname '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$name);
        return $response;
    }

    function getConf(){
        $conf = shell_exec('');
        return $conf;
    }
}