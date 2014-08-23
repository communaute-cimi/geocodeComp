<?php

//$url_photon = 'http://france.photon.fluv.io/api/';
$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamProxy = isset($_REQUEST['proxy']) ? $_REQUEST['proxy'] : false;
$sParamUrlGeocoder = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : False;

try {

    $aGeocodeParams = array(
        'q' => $sParamAddr . " " . $sParamCpVille,
        'limit' => 1
    );
    
    $uri = $sParamUrlGeocoder  . '?' . http_build_query($aGeocodeParams);
    
    if($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");

    $jsonResponse = file_get_contents($uri);
    
    echo $jsonResponse;

}catch(Exception $ex) {
    echo json_encode(
        array(
            'error' => 1, 
            'message' => $ex->getMessage()
        )
    );
}
