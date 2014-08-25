<?php


    define('APPLICATION_PATH', realpath(dirname(__FILE__)));

    require_once(APPLICATION_PATH . '/libs/geocoder.php');
    require_once(APPLICATION_PATH . '/libs/proxy.php');

    try {
        
        $geocoders = array();
        
        if(! file_exists(APPLICATION_PATH . '/conf/conf.json')) throw new Exception("Le fichier de configuration est absent --> /conf/conf.json", 1);
        
        $sJsonConf = file_get_contents(APPLICATION_PATH . '/conf/conf.json'); 
        $oJsonConf = json_decode($sJsonConf, true);
        
        if(is_null($oJsonConf)) throw new Exception("Le fichier de configuration JSON n'a pu être parsé", 1);
        
        foreach ($oJsonConf['geocoders'] as $geocoder) {
            $geocoders[] = new geocoder($geocoder);
        }

    }catch(Exception $ex) {
        throw $ex;
    }