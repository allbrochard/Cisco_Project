<?php

if(isset($_POST['action']) && function_exists($_POST['action'])) {
    $action = $_POST['action'];
    $var = isset($_POST['name']) ? $_POST['name'] : null;
    $getData = $action($var);
    // do whatever with the result
}
switch($action) {
    case 'changer_nom':
        setEquipmentName($_POST['name']);
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