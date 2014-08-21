<?php 
class geocoder {
    
    public $env = null;
    public $name = '';
    public $iconColor = '';
    public $proxyPhp = '';
    public $jsFunction = '';
    public $jsonDatas = null;
    public $urlGeocoder = null;
    
    public function __construct($aParams) {
        try {
            
            if(! (isset($aParams['name'], $aParams['iconColor'], $aParams['proxyPhp'], $aParams['jsFunction']))) throw new Exception("Paramètre manquant dans le contructeur de la classe " . __CLASS__ , 1);
            
            $this->name = $aParams['name'];
            $this->iconColor = $aParams['iconColor'];
            $this->proxyPhp = $aParams['proxyPhp'];
            $this->jsFunction = $aParams['jsFunction'];
            
            $this->urlGeocoder = isset($aParams['urlGeocoder']) ? $aParams['urlGeocoder'] : null;
            
            // Environnement facultative
            $this->env = isset($aParams['env']) ? $aParams['env'] : null;
            
            // Transform params into JS function argument
            $this->jsonDatas = json_encode($aParams);
            
        }catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function getJsFunction() {
        return $this->jsFunction . '(' . $this->jsonDatas . ')';
    }
}