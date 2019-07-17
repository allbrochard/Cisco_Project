<?php

if(isset($_GET['action']) && function_exists($_GET['action'])) {
    $action = $_GET['action'];
    $var = isset($_GET['name']) ? $_GET['name'] : null;
    $getData = $action($var);
    // do whatever with the result
}
switch($action) {
    case 'changer_nom':
        setEquipmentName($_GET['name']);
    default:
        die('Access denied for this function!');
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
echo $action($var);
?>