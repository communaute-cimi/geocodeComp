<?php

$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamUrlGeocoder = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : "";

try {
    if($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");
    
    $aGeocodeParams = array(
        'singleline' => $sParamAddr . " " . $sParamCpVille,
    );
    
    $uri = $sParamUrlGeocoder  . '?' . http_build_query($aGeocodeParams);
    
    $jsonResponse = file_get_contents($uri);
    $oResponse = json_decode($jsonResponse);
    
    if(count($oResponse->candidates) == 0) {
        throw new Exception("Echec de géocodage", 1);
    }
    
    echo $jsonResponse;
    
}catch(Exception $ex) {
    echo json_encode(
        array(
            'error' => 1, 
            'message' => $ex->getMessage()
        )
    );
}