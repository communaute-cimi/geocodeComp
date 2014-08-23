<?php
require_once('../config.inc.php');

$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamProxy = isset($_REQUEST['proxy']) ? $_REQUEST['proxy'] : false;
$sParamUrl = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : FALSE;


try {
    if ($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");
    if ($sParamUrl === FALSE) throw new Exception("Paramètre [url] absent");

    $aGeocodeParams = array('sensor' => 'false', 'address' => $sParamAddr . ' ' . $sParamCpVille);

    $uri = $sParamUrl . '?' . http_build_query($aGeocodeParams);

    if($sParamProxy == true) {
        $jsonResponse = getCurl($uri, $oJsonConf['proxy']['host'], $oJsonConf['proxy']['port'], $oJsonConf['proxy']['user'], $oJsonConf['proxy']['pwd']);
    }else {
        $jsonResponse = file_get_contents($uri);
    }
    
    $oResponse = json_decode($jsonResponse);

    if (count($oResponse -> results) == 0) {
        throw new Exception("Echec de géocodage", 1);
    }

    echo $jsonResponse;

} catch(Exception $ex) {
    echo json_encode(array('error' => 1, 'message' => $ex -> getMessage()));
}

function getCurl($uri, $host, $port, $user, $pwd) {
    try {
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $uri);
    
        if ($sParamProxy) {
            // Activation de l'utilisation d'un serveur proxy
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
    
            // Définition de l'adresse du proxy
            curl_setopt($ch, CURLOPT_PROXY, $host.":".$port);
    
            // Définition des identifiants si le proxy requiert une identification
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $user.":".$pwd);
        }
    
        $content = curl_exec($ch);
        
        return $content;
    }catch(Exception $ex) {
        throw $ex;
    }

}
