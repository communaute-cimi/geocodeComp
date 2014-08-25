<?php

require_once('../config.inc.php');

//$url_photon = 'http://france.photon.fluv.io/api/';
$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamProxy = isset($_REQUEST['proxy']) ? $_REQUEST['proxy'] : FALSE;
$sParamUrlGeocoder = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : FALSE;

try {
        
    if($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");        

    $aGeocodeParams = array(
        'q' => $sParamAddr . " " . $sParamCpVille,
        'limit' => 1
    );
    
    $uri = $sParamUrlGeocoder  . '?' . http_build_query($aGeocodeParams);

    if($sParamProxy == true) {
        $jsonResponse = RequestProxy::getCurl($uri, $oJsonConf['proxy']['host'], $oJsonConf['proxy']['port'], $oJsonConf['proxy']['user'], $oJsonConf['proxy']['pwd']);
    }else {
        $jsonResponse = file_get_contents($uri);
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
