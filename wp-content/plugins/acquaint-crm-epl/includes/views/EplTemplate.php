<?php
/**
 * Acquaint CRM To Easy Property Listings: Template functions
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

class EplTemplate{
    
    

    
    
    /**
     * Return the Acquaint images array
     * @return type
     */
    public static function acquaint_crm_epl_get_media() {    
        
        $main_image = get_post_meta(get_the_ID(), 'property_picture1');
        
        $attachments = get_attached_media( 'image', get_the_ID()); 
                           
        $media = array();
                      
        foreach( $attachments as $attachment ) {
                            
                    if($main_image[0] != ''){
                        if(stristr($attachment->guid, $main_image[0])){
                            $media['featured'] = $attachment->ID;
                        }
                        if(stristr($attachment->post_title, 'picture') && !stristr($attachment->guid, $main_image[0])){
                                $media['pictures'][] = $attachment->ID;
                        }  
                    }
                    if(stristr($attachment->post_title, 'floorplan')){
                        $media['floorplan'] = $attachment->ID;
                    }
                    if(stristr($attachment->post_title, 'eer chart')){
                        $media['eer-chart'] = $attachment->ID;
                    }        
                    if(stristr($attachment->post_title, 'eir chart')){
                        $media['eir-chart'] = $attachment->ID;
                    }            
                    if(stristr($attachment->post_title, 'brochure')){
                        $media['brochure'] = $attachment->ID;
                    }      
                    if(stristr($attachment->post_title, 'qr code')){
                        $media['qr-code'] = $attachment->ID;
                    }                                     
                }      
                
                
                return $media;

    }
    
    
    
}