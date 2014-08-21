<?php

$sParamAddr = isset($_REQUEST['addr']) ? $_REQUEST['addr'] : "";
$sParamCpVille = isset($_REQUEST['cpVille']) ? $_REQUEST['cpVille'] : "";
$sParamUrlGeocoder = isset($_REQUEST['urlGeocoder']) ? $_REQUEST['urlGeocoder'] : "";

$oSoapClient = new SoapClient($sParamUrlGeocoder,array("trace" =>1));

try {

    if($sParamCpVille == "") throw new Exception("Paramètre [cpVille] absent");

    $aAdresse = array(
        "",
        "",
        "",
        $sParamAddr,
        "",
        $sParamCpVille
    );
    
    $aResultGeocode = array();
    
    $requestValide = array(
        "application"   => 1,   
        "operation"     => 1,
        "donnees"       => $aAdresse
    );
    
    $oSoapValideResponse = $oSoapClient->valide($requestValide);

    if($oSoapValideResponse->return->codeRetour === 0) {
        throw new Exception($oSoapValideResponse->return->erreurs->message . ', code=' . $oSoapValideResponse->return->erreurs->code, 1);
        exit;
    }
    
    $aResultGeocode['total'] = count($oSoapValideResponse->return->propositions);
    

    if(count($aResultGeocode['total'])>0) {
        
        if($aResultGeocode['total'] == 1) {
            $aResultGeocode['valide'] = $oSoapValideResponse->return->propositions;
        }else {
            
            $bValidDone = false;
            $aScores = array();
            
            // Ne garder que les propositions > à 180
            
            foreach ($oSoapValideResponse->return->propositions as $proposition) {
                if((integer) $proposition->note > 180) {
                    if($bValidDone==false) $aResultGeocode['valide'] = $proposition;
                    $bValidDone = true;
                }
                $aScores[] = array((integer) $proposition->note, $proposition->service);
            }
                       
            $aResultGeocode['valide_scores'] = json_encode($aScores);
        }
        
        
        $oSoapGeocodeResponse = geocodeAfterValid($oSoapClient, $aResultGeocode['valide']->donnees);
        
        if($oSoapGeocodeResponse->return->codeRetour === 0) {
            throw new Exception($oSoapGeocodeResponse->return->erreurs->message . ', code=' . $oSoapGeocodeResponse->return->erreurs->code . ', service=' . $oSoapGeocodeResponse->return->erreurs->service, 1);
            exit;
        }
        
        $aResultGeocode['geocode'] = $oSoapGeocodeResponse->return;
        echo json_encode($aResultGeocode);
    } else {
        throw new Exception("Erreur de validation", 1);
    }
    
}catch(Exception $ex) {
    echo json_encode(
        array(
            'error' => 1, 
            'message' => $ex->getMessage()
        )
    );
}



function geocodeAfterValid($oSoapClient, $aAdresse) {
    
    try {

        // Virer la ligne PAYS le cas échéant
        if(count($aAdresse) == 7) array_pop($aAdresse);
        
        $requestGeocode = array(
            "application"   => 1,  
            "operation"     => 2,
            "services"      => 3, 
            "donnees"       => $aAdresse
        ); 

        $oSoapGeocodeResponse = $oSoapClient->geocode($requestGeocode);
        
        return $oSoapGeocodeResponse;
        
    }catch(Exception $Ex) {
        throw new Exception("Erreur de géocodage après validation", 1);
    }   
}
