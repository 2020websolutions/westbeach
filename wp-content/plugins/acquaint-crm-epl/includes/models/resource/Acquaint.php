<?php
/**
 * Acquiant CRM Connector
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

Class Acquaint{
      
  
    
    public function __construct()
    {
        
        //Testing:: shortcode to fire call and get result from web service via browser
        add_shortcode('AcquaintCrmWebservice', array($this, 'getAcquaintHtml'));       
                     
    }
    
 
    public static function log($message){
             
        try{
            
            if(ACQUAINT_CRM_LOG_ON){
                   
                $logFile = ABSPATH ."acquaint.log";

                if (!file_exists($logFile)) {
                    file_put_contents($logFile, '');
                    chmod($logFile, 0644);
                }           

                error_log('['.date('Y:m:d\TH:i:sP').'] '.$message."\r\n", 3, $logFile);
            
            }
        
        }catch(Exception $e){
        }
        
    }    
        
       
    
    /**
     * For Shortcode: [AcquaintCrmWebservice]
     * 
     * @param type $atts
     * @return string
     */
    public function getAcquaintHtml($atts)
    {       
                       
        return 'TO DO!!!!... Some Examples';
        
    }    
    
    
    

}
