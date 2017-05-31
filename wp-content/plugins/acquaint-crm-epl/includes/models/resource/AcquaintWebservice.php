<?php
/**
 * Acquiant CRM Connector
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

Class AcquaintWebservice extends Acquaint{
    
    const Websiteservice            = 'http://webservices.acquaintcrm.co.uk/websiteservices.asmx?wsdl';
    const SoapVersion               = 'SOAP_1_2';  
    const ReadDevelopments          = 'ReadDevelopments';
    const ReadFeaturedProperties    = 'ReadFeaturedProperties';
    const ReadLastUploadDateTime    = 'ReadLastUploadDateTime';
    const ReadNews                  = 'ReadNews';
    const ReadPictureBaseURL        = 'ReadPictureBaseURL';
    const ReadProperties            = 'ReadProperties';
    const ReadProperty              = 'ReadProperty';
    const ReadPropertyAreas         = 'ReadPropertyAreas';
    
    
    protected $_strPassword     = '';
    protected $_strSitePrefix   = '';
    protected $_intSiteID       = '';
    protected $_soapClient      = '';
    protected $_params          = array();
    
    
    public function __construct()
    {        
        
        $options = get_option( 'acquaint_settings' );
        
        $this->_strSitePrefix   = $options['acquaint_site_prefix'];
        $this->_intSiteID       = $options['acquaint_site_id'];
        $this->_strPassword     = $options['acquaint_xml_service'];

        
        $params = array(
            'soap_version'      => self::SoapVersion,   
            'trace'             => true,
            'exceptions'        => true,
        );        
                
        $this->_soapClient = new SoapClient(self::Websiteservice, $params);
       
    }    
    
    
    
    private function getCredentials(){
        
        $credentials = array(
            'strPassword'       => $this->_strPassword,
            'strSitePrefix'     => $this->_strSitePrefix,   
            'intSiteID'         => $this->_intSiteID,  
        );   
        
        return $credentials;
        
    }
        
    
    /**
     * Set Service to call
     * 
     * @param type $service
     * @return \AcquaintApi
     */
    public function setService($service){
        
        $this->_service = $service;        
        
        return $this;
        
    }
    
    /**
     * Set call parameters
     * 
     * @param type $params
     * @return \AcquaintApi
     */
    public function setParams($params = null){                                
        
        if($params !== null){
            $this->_params = array_merge($params, $this->getCredentials());   
        }else{
            $this->_params = $this->getCredentials(); 
        }
          
        return $this;
    }    
    
    
    public function setFormat($format = 'xml'){
        
        switch($format){
            
            case 'xml':
                $this->_format = 'xml';
                break;     
            case 'array':
                $this->_format = 'array';
                break;                       
            
        }
        
        return $this;
        
    }
    
 
    
    /**
     * Call api and return resonse
     * @return type
     */
    public function call(){
        
        $service   = (string)$this->_service;
        
        if(!$this->_params){
            $this->setParams();
        }
        
        $response  = $this->_soapClient->$service($this->_params); 
        
        $serviceResult  = $service.'Result';
        
        $xml = $response->$serviceResult;         
            
        return $xml;

    }
    
    
    /**
     * simpleXML2Array with CDATA support
     * @param type $xml
     * @return type
     */
    public function simpleXML2Array($xml){

        $array = (array)$xml;

        if (count($array) == 0) {
            $array = (string)$xml;  
        }

        if (is_array($array)) {
            //recursive Parser
            foreach ($array as $key => $value){
                if (is_object($value)) {
                    if(strpos(get_class($value),"SimpleXML")!==false){
                            $array[$key] = $this->simpleXML2Array($value);
                    }
                } else {
                    $array[$key] = $this->simpleXML2Array($value);
                }
            }
        }

    return $array;
    
    }    

    
    
}


