<?php
    // Use an Internet proxy
    
class RequestProxy
{
    public static function getCurl($uri, $host, $port, $user, $pwd) {
        try {
            if(! function_exists('curl_exec')) throw new Exception("Curl ne semble pas avoir été installé");
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $uri);                           
            
            // Use proxy server
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
    
            // Proxy hostname and port --> xx.xxx.xxx.xxx:2128
            curl_setopt($ch, CURLOPT_PROXY, $host.":".$port);
    
            // ID and password
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $user.":".$pwd);
    
            $content = curl_exec($ch);
            
            if($content == false) throw new Exception(curl_error($ch));

            return $content;
            
        }catch(Exception $ex) {
            throw $ex;      
        }             
    }
}