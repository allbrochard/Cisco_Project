<?php

switch($_GET['']){
    case 'changer_nom':
        setEquipmentName($_GET['nom']);

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

?>