<?php
/**
 * Acquaint CRM To Easy Property Listings: Cron Jobs
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

Class AcquaintEplCronJob extends AcquiantWebServiceCall{
    

    
    
    public function acquaintPropertyImport(){
        
        try{
        
            if(!$this->hasNewLastUploadTime()){
                //return;//Comment out for testing to run update on all properties
                //Acquaint::log('FORCE CALL TO FILE>>>>>'); 
            }           
                          
            $this->processPropertyUpdates();
    
        }catch(Exception $e){
            Acquaint::log('Exception: '.$e->getMessage());
        }
        
    }
    
    
    /**
     * 
     */
    public function acquaintPropertyDelete(){
            
        
        try{
            
            
            $xml = $this->readProperties();

            $obj = simplexml_load_string($xml);  
            
            $properties = array();
            
            foreach($obj as $property){
                $properties[] = (string)$property->id;
            }         
           
            global $wpdb;
            $posts = $wpdb->get_results( 
                    "
                    SELECT ID, post_title
                    FROM $wpdb->posts 
                    WHERE post_type = 'rental' 
                    OR post_type = 'property'
                    OR post_type = 'rural'
                    OR post_type = 'business'
                    OR post_type = 'commercial'
                    OR post_type = 'commercial_land'
                    OR post_type = 'contact'
                    OR post_type = 'land'
                    "  
            );
                                
            foreach($posts as $post){
                if(!in_array($post->post_title, $properties)){
                    $this->deleteWordpressPost($post->ID);
                }
                Acquaint::log('POST ID ['. $post->ID. '] DELETE > POST RETAINED '); 
            }
            
    
        }catch(Exception $e){
            Acquaint::log('Exception: '.$e->getMessage());
        }
        
    }    
    
    
    
    private function hasNewLastUploadTime(){
        
        $time_stored = get_option( 'acquaint_last_upload');                 
        $time_new    = $this->readLastUploadDateTime();   

        Acquaint::log('Cron Job: acquaint_last_upload STORED:['.$time_stored .'] NEW:['.$time_new .']');

        if($time_stored == $time_new){   
            
            return false;
            
        }else{
            
            //store the new last update time
            //------------------

            update_option( 'acquaint_last_upload', $time_new);     
            
            return true;
            
        }                
        
    }
    
    
    
    /**
     * readProperties: create and update properties
     */
    private function processPropertyUpdates(){
                
        
        $xml = $this->readProperties();

        $obj = simplexml_load_string($xml);                   

        $processor = new AcquaintToEpl();

        foreach($obj->property as $property){                   
            
            $xml = $this->readProperty((string)$property->id);                             

            $processor->processAcquaintProperty($xml);

        }           
        
    }
    
    
    private function deleteWordpressPost($post_ID){
        
        $media = get_attached_media( 'image', $post_ID);
        
        foreach($media as $attachment){ 
            
            $result =  wp_delete_attachment( $attachment->ID, $force_delete = true);

            if (!$result) {
                    Acquaint::log('POST ID ['.  $post_ID. '] MEDIA ERROR:  FAILED DELETE IMAGE ATTACHEMENT ID: '.$attachment->ID); 
            } else {
                    Acquaint::log('POST ID ['.  $post_ID. '] DELETED ATTACHEMENT: '.$attachment->ID. ' '.$attachment->guid); 
            }        
            
        }   
        
        $result =  wp_delete_post( $post_ID, $force_delete = true );
         
        if (!$result) {
                Acquaint::log('POST ID ['. $post_ID. '] FAILED TO DELETE POST'); 
        } else {
                Acquaint::log('POST ID ['. $post_ID. '] DELETED '); 
        }           
         
        
    }
    
}