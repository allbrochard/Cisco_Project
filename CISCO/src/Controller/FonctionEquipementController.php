<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FonctionEquipementController extends AbstractController
{
    /**
     * @Route("/fonction/equipement/{action}/{name}", name="fonction_equipement")
     */
    public function index($action, $name)
    {

        switch($action) {
            case 'changer_nom':
                $this->setEquipmentName($name);
            default:
                die('Access denied for this function!');
        }

    }

    public function setEtatEquipement($bool){

    }

    public function setEquipmentName($name){
        $user = $_SESSION['user'];
        $mpUser = $_SESSION['mp_user'];
        $mpAdmin = $_SESSION['mp_admin'];
        $ip = $_SESSION['ip_equipement'];

        $response = shell_exec('/script/change_hostname '.$ip.' '.$user.' '.$mpUser.' '.$mpAdmin.' '.$name);
        return $response;
    }

    public function getConf(){
        $conf = shell_exec('');
        return $conf;
    }
}
