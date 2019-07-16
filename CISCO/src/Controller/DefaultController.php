<?php
/**
 * @return bool
 */
function index(){

    $liste_equipement = array();
    for ($i = 1; $i < 255; $i++){
        $ip = '172.25.200.'.$i;
        $comu = 'cisco';
        $output = shell_exec('ping '.$ip.' -w 2 -c 1');
        if(strpos($output, ' 0%')==true){
            $output = shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.1.0');
            if(strpos($output, 'Cisco')){
                array_push($liste_equipement, array(
                    'nom' => shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.5.0'),
                    'type' => shell_exec('snmpwalk -v 2c -c '.$comu.' '.$ip.' .1.3.6.1.2.1.1.5.0'),
                ));
                return true;
            }
        }
    }

    return $this->render('accueil.html.twig', [
        'controller_name' => 'DefaultController',
    ]);
}
