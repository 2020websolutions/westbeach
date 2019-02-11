<?php
/**
 * Acquiant CRM Connector
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

Class AcquiantWebServiceCall extends AcquaintWebservice{
        
    
    public function __construct() {
        parent::__construct();
    }
    
        
    
    /**
     * Call to ReadProperties defaults to ALL
     * 
     * @return type
     */
    public function readProperties($params = array()){
                        
        if(!$params){
            $params = $this->getAllPropertiesParameters();
        }
        
        $this->setService(self::ReadProperties);
        $this->setParams($params);       
        
        $xml = $this->call();  
        
        return $xml;
        
    }    
    
    
    /**
     * Call to ReadProperty by ID
     * 
     * @param type $id
     * @return type
     */
    public function readProperty($id){
               
       $params = array(                                 
            'intPropertyID'     => $id,
        );            
                                       
        $this->setService(self::ReadProperty);
        $this->setParams($params);
        
        $xml = $this->call();  
        
        return $xml;
        
    }      
    
    
    /**
     * Calls the ReadLastUploadDateTime endpoint
     * 
     * @return type
     */
    public function readLastUploadDateTime(){                               
               
        $this->setService(self::ReadLastUploadDateTime);
       
        $string = $this->call();  
        
        return $string;
        
    }      
    
    
    
    /**
     * Calls the ReadPictureBaseURL endpoint
     * 
     * @return type
     */
    public function readPictureBaseURL(){                               
               
        $this->setService(self::ReadPictureBaseURL);
       
        $string = $this->call();  
        
        return $string;
        
    }          
    
    
    /**
     * Call to ReadDevelopments
     * 
     * @param type $params
     * @return type
     */
    public function readDevelopments($params = array()){
                                      
        $this->setService(self::ReadDevelopments);
        $this->setParams($params);
        
        $xml = $this->call();  
        
        return $xml;
        
    }       
    
    
    /**
     * Call to ReadPropertyAreas
     * 
     * @param type $params
     * @return type
     */
    public function readReadPropertyAreas($params = array()){
                                      
        $this->setService(self::ReadPropertyAreas);
        $this->setParams($params);
        
        $xml = $this->call();  
        
        return $xml;
        
    }       
    
    
    /**
     * Gets all properties
     * 
     * @return type
     */
    public function readAllProperties(){
        
        $params = getAllPropertiesParameters();
                
        return $this->readProperties($params);       
        
    }
    
    
    
    /**
     * Return required parameters to get ALL properties
     * 
     * @return type
     */
    public function getAllPropertiesParameters(){
        
       $params = array(                                 
           'intCategory'                => -1,  //-1 All
           'intUsage'                   => -1,  //-1 All
           'intPropertyAge'             => -1,  //-1 All
           'bytBedrooms'                => 0,   //0 All
           'decMinPrice'                => 0,   //0 All
           'decMaxPrice'                => 0,   //0 All
           'strAreas'                   => '',  //Empty for All           
           //'strTypes'                 => '',  //Type of property (Optional)
           'intPropertyDevelopmentID'   => 0,   //0 All Properties
           'bytSortOrder'               => 0    //0 Price Asc 1 Price Desc
        );    
                             
        return $params;
               
    }    
    
    
    
}