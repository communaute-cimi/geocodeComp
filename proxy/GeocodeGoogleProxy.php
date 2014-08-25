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
        $jsonResponse = RequestProxy::getCurl($uri, $oJsonConf['proxy']['host'], $oJsonConf['proxy']['port'], $oJsonConf['proxy']['user'], $oJsonConf['proxy']['pwd']);
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