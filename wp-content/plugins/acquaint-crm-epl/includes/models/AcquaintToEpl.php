<?php
/**
 * Acquaint CRM To Easy Property Listings: Import processing
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

Class AcquaintToEpl extends AcquaintWebservice{
    
    const PictureBaseUrl   = 'http://www.brightlogic-estateagents.co.uk/';

    
    
    
    
    /**
     * Convert Acquaint Proprety XML to Wordpress EPL Property Post
     * 
     * @param type $xml
     */
    public function processAcquaintProperty($xml){
        
        try{                                   
            
            $obj         = simplexml_load_string($xml);                 
            $postArray   = $this->getPropertyPostArray($obj);         
            $post        = get_page_by_title($postArray['post_title'], 'OBJECT', $this->getPropertyPostTypeArray());  
            
            if ($post == null){  

                Acquaint::log('PROPERTY ID ['.$postArray['post_title'].'] NEW PROPERTY >>>>>'); 

                $postArray['ID'] = $this->postInsert($postArray); 

                if(!$postArray['ID']){
                    Acquaint::log('PROPERTY ID ['.$postArray['post_title'].'] INSERT ERROR'); 
                    return;
                }
                
                //Acquaint::log('POST ID ['.$postArray['ID'].'] INSERTED');   
                
                
                $this->postSaveTerms($obj, $postArray);

                //Acquaint::log('POST ID ['.$postArray['ID'].'] TERMS ADDED');                  
               
                
                $this->postInsertImages($postArray);

                //Acquaint::log('POST ID ['.$postArray['ID'].'] IMAGES INSERTED');

                //exit('TEST END!');

            }else{
                
                
                if(!$this->propertyHasUpdate($obj, $post->ID)){ 
                    return;//Comment out to run update on all properties [TESTING
                    //Acquaint::log('FORCE UPDATE OF PROPERT>>>>>'); 
                }


                $postArray['ID'] = $post->ID;

                $this->postUpdate($postArray);

                Acquaint::log('POST ID ['.$postArray['ID'].'] UPDATE TO PROCESS >>>>>');  

                $this->postSaveTerms($obj, $postArray);

                //Acquaint::log('POST ID ['.$postArray['ID'].'] TERMS UPDATED');

                $this->postDeleteImages($postArray);

                //Acquaint::log('POST ID ['.$postArray['ID'].'] IMAGES DELETED');

                $this->postInsertImages($postArray);

                //Acquaint::log('POST ID ['.$postArray['ID'].'] IMAGES INSERTED');
                
                //exit('TEST END!');

            }              
        
        }catch(Exception $e){
             Acquaint::log('Exception: '.$e->getMessage());
        }
        
    }
    
    
    public function getPropertyPostTypeArray(){
        
        return array(
            'rental',
            'property',
            'rural',
            'business',
            'commercial',
            'commercial_land',
            'contact',
            'land',                      
        );
        
    }
    
    
    /**
     * Inserts post returns post id or error
     * @param type $postArray
     * @return type
     */
    private function postInsert($postArray){        
                      
        $result = wp_insert_post($postArray, $wp_error = true ); 
            
        if ( is_wp_error( $result ) ) {
                Acquaint::log('INSERT POST ERROR ['.$result->get_error_message().']'); 
                return false;
        } else {
               Acquaint::log('POST ID['.$result.'] NEW PROPERTY INSERTED'); 
               return $result;
        }              
                              
    }
    
    
    /**
     * Updates post returns post id or error
     * @param type $postArray
     * @return type
     */
    private function postUpdate($postArray){
        
        $result   = wp_update_post($postArray, $wp_error = true);

        if ( is_wp_error( $result ) ) {
                Acquaint::log('POST ID['.$postArray['ID'].']  ERROR ['.$result->get_error_message().']'); 
        } else {
               return $result;
        }                     
                          
    }
    
    
    private function propertyHasUpdate($obj, $post_ID){
              
            $ac_update_date = (string)$obj->property->updateddate;
            $wp_update_date = get_post_meta($post_ID, 'property_date_updated', true);            
            
            if($wp_update_date != $ac_update_date){     
                Acquaint::log('POST ID ['.$post_ID.'] UPDATE AT ['.$ac_update_date.'] WP ['.$wp_update_date.'] >>>>>> '); 
                return true;
            }else{
                Acquaint::log('POST ID ['.$post_ID.'] UPDATE AT ['.$ac_update_date.'] WP ['.$wp_update_date.'] NO CHANGE'); 
                return false;
            }
                      
    }
    
    
    private function postSaveTerms($obj, $postArray){
                
        $areas      = $this->getPropertyPostTerms($obj);//Area             
        
        $success    = wp_set_object_terms($postArray['ID'], $areas, 'location', $append = false );         
        
        if ( is_wp_error( $success ) ) {
               Acquaint::log('POST ID ['.$postArray['ID'].'] RESULT ['.$success->get_error_message().']'); 
        } else {
               Acquaint::log('POST ID ['.$postArray['ID'].'] POST TERMS INSERTED'); 
        }          
        
        
    }
    
    
    public function getImageBaseUrl(){
    
        return self::PictureBaseUrl.$this->_strSitePrefix.'/upload/'; 
        
    }
    
    
  
    
    
    private function getPropertyImageArrayKeys(){
        
        return array(    
            'property_picture',
            'property_brochure',
            'property_floorplan',
            'property_qrcode',
            'property_eerchart',
            'property_eirchart'
                       
        );      
        
        
    }
    
    
    private function postInsertImages($postArray){
               
        foreach($postArray['meta_input'] as $key => $value){                     
                     
            if(stristr($key, 'property_picture') || in_array($key, $this->getPropertyImageArrayKeys())){
           
                if(!$value){ continue;}                            
                                       
                    $this->postInsertImage($value, $postArray, $key);
                    
                    if($key == 'property_picture1'){
                        $this->postSetFeaturedImage($postArray, $value);
                        
                    }                                                      
            }
        }            
        
    }
    
    
    private function postDeleteImages($postArray){
        
        $media = get_attached_media( 'image', $postArray['ID'] );
        
        foreach($media as $attachment){          
            $this->postDeleteAttachement($postArray, $attachment);                      
        }                      
        
    }    
    
    
    
    
    private function postDeleteAttachement($postArray, $attachment){
        
        $result =  wp_delete_attachment( $attachment->ID, $force_delete = true);
        
        if (!$result) {
                Acquaint::log('POST ID ['.  $postArray['ID']. '] MEDIA ERROR:  FAILED DELETE IMAGE ATTACHEMENT ID: '.$attachment->ID); 
        } else {
                Acquaint::log('POST ID ['.  $postArray['ID']. '] DELETED ATTACHEMENT: '.$attachment->ID. ' '.$attachment->guid); 
        }           
        
    }
    
    
    private function postSetFeaturedImage($postArray, $file_name){
        
        $media      = get_attached_media( 'image', $postArray['ID'] );
        $result     = false;
        
        foreach($media as $attachment){
            if(stristr($attachment->guid, $file_name)){
                $result = set_post_thumbnail( $postArray['ID'], $attachment->ID);
                break;
            }
        }
                             
        if (!$result) {
                Acquaint::log('POST ID ['.  $postArray['ID']. '] MEDIA ERROR:  FAILED TO SET FEATURED IMAGE ATTACHEMENT ID: '.$attachment->ID); 
        } else {
                Acquaint::log('POST ID ['.  $postArray['ID']. '] FEATURED IMAGE SET ATTACHEMENT ID: '.$attachment->ID); 
        }           
 
    }
    
    
    
    private function postInsertImage($file_name, $postArray, $key){       
        
        $description = $postArray['meta_input']['property_heading'].' '.$this->getImageAlt($key);
        
        //Upload / resize / crop image (to WP images folder)
        $success = media_sideload_image($this->getImageBaseUrl().$file_name, $postArray['ID'], $description); 

        if ( is_wp_error( $success ) ) {
                Acquaint::log('POST ID ['.  $postArray['ID']. '] MEDIA ERROR:  RESULT ['.$success->get_error_message().']'); 
        } else {
                Acquaint::log('POST ID ['.  $postArray['ID']. '] MEDIA INSERTED: ['.$success.']'); 
        }          
        
    }
    
    
    
    private function getImageAlt($key){
        
        switch($key){
            case 'property_eerchart' :
                $img = 'EER Chart';
                break;            
            case 'property_eerchart' :
                $img = 'EER Chart';
                break;
            case 'property_eirchart' :
                $img = 'EIR Chart';
                break;
            case 'property_qrcode' :
                $img = 'QR Code';
                break;  
            case 'property_floorplan' :
                $img = 'Floor Plan';
                break;    
            case 'property_brochure' :
                $img = 'Brochure';
                break;             
            default:
                $img = ucwords(str_replace('_', ' ', $key));
        }
        
        return $img;
               
    }
    
    
    public function getPostByMetaValue($key,$value,$post_type){
        
        $args = array(
            'meta_key' => $key,
            'meta_value' => $value,
            'post_type' => $post_type,

        );
        return get_posts($args);        
        
    }
    
    
    /**
     * Creates 'Readproperty' array ready for Create / Update Wordpress EPL post
     * 
     * @param type $obj
     * @return type
     */
    private function getPropertyPostArray($obj){
                       
        $post['post_type']      = $this->getPostType($obj);//Category: For Sale / To Let
        $post['post_title']     = (string)$obj->property->id;
        $post['post_author']    = 1;        
        $post['post_content']   = (string)$obj->property->descriptionfull;
        $post['post_excerpt']   = (string)$obj->property->descriptionbrief;
        $post['post_status']    = 'publish';       
        $post['comment_status'] = 'closed';
        $post['ping_status']    = 'closed';
        $post['meta_input']     = $this->getPropertyPostMeta($obj);
        
        //Values with Defaults or not required
        //$post['post_date']            = '';//Default is the current time.
        //$post['post_password']        = '';//Default empty.
        //$post['post_name']            = '';//Default is the sanitized post title when creating a new post.
        //$post['post_ping']            = '';//Default empty.
        //$post['pinged']               = '';//Default empty.
        //$post['post_modified']        = '';//Default is the current time.
        //$post['post_modified_gmt']    = '';//Default is the current time.
        //$post['post_parent']          = '';//Default 0.
        //$post['menu_order']           = '';//Default 0.
        //$post['post_mime_type']       = '';//Default empty.
        //$post['guid']                 = '';//Default empty.
        //$post['tax_input']            = '';//Default empty.
                     
    
        return $post;
        
        
    }
    
    
    /**
     * Maps Acquaint Property Category to EPL Post Type
     * 
     * propertycategory
     * 
     * rental
     * property
     * 
     * added filter to use other post types if needed
     * 
     * @param type $obj
     * @return string
     */
    private function getPostType($obj){
        
        //propertycategory
        $id = (string)$obj->property->category;             
        
        switch($id){

            case 0 :
                $post_type = 'property';
                break;   
            case 1 :
                $post_type = 'rental';
                break;
         
        }
        
        $post_type = apply_filters('aquaint_crm_epl_get_post_type',$post_type, $obj);
        
        return $post_type;
        
    }
    
   
    
    /**
     * Maps Acquiant property values to EPL post Meta
     * 
     * NB Developments call is excluded
     * 
     * @param type $obj
     * @return type
     */
    private function getPropertyPostMeta($obj){
  
        
        //propertytype
        $meta['property_category']              = $this->getHouseCategory($obj);
        
        //propertytenures
        $meta['property_tenure']                = $this->getPropertyTenure($obj); 
        
        //propertysalesstatus
        $meta['property_status']                = $this->getPropertyStatus($obj);        
               
        //propertypriceprefix
        $meta['property_price_prefix']          = $this->getPropertyPricePrefix($obj);          
        
        //countrycode
        $meta['property_address_country']       = $this->getPropertyCountry($obj);
        
        //------------------------------
        
        //Listing Details
        $meta['property_heading']               = (string)$obj->property->displayaddress;
        $meta['property_agent']                 = get_bloginfo('name');
        $meta['property_authority']             = '';//not specified
        $meta['property_list_date']             = (string)$obj->property->addeddate;
        $meta['property_unique_id']             = (string)$obj->property->id;
        
        $meta['property_date_updated']          = (string)$obj->property->updateddate;//custom
        $meta['property_statusdescription']     = (string)$obj->property->statusdescription;
        
        //Address
        $meta['property_address_name']          = (string)$obj->property->propertyname;
        $meta['property_address_sub_number']    = '';//Unit - Not Specified
        $meta['property_address_street_number'] = (string)$obj->property->housenumber;//Street Number
        $meta['property_address_street']        = (string)$obj->property->streetname;//Street Name
        $meta['property_address_suburb']        = (string)$obj->property->locality;//Area
        $meta['property_address_city']          = (string)$obj->property->town;//City
        $meta['property_address_state']         = (string)$obj->property->region;//County
        $meta['property_address_postal_code']   = (string)$obj->property->postcode;
        
        //Coordinates
        //$meta['property_address_coordinates']   = '';//not provided by acquaint, only needed for 'advanced epl map'
        
        //Map
        $meta['property_address_hide_map_yes']  = '';//always show
        
        //Area
        //After Post Updated or saved locaclity and city is saved to wordpress tags
        
        Acquaint::log('PROPERTY CATEGORY ['.(string)$obj->property->category. ']'); 
        
        //Price
        if((string)$obj->property->category === '1'){//Rental
            
            Acquaint::log('PROPERTY Pricing>>>>>>>>'); 
            
            //propertyrentalterm
            $meta['property_rental_terms']          = $this->getPropertyRentalTerm($obj);

            //propertyrentalfurnish
            $meta['property_rental_furnish']        = $this->getPropertyRentalFurnished($obj);            
            
            $meta['property_rent']               = (string)$obj->property->price;
            $meta['property_rent_view']          = (string)$obj->property->displayprice;
            $meta['property_rent_period']        = $this->getPriceFrequency($obj);
            $meta['property_rent_display']       = 'yes';
            $meta['property_bond']               = '';//not specified 
            $meta['property_date_available']     = (string)$obj->property->rentalavailabledate;
        }
        if((string)$obj->property->category === '0'){//For Sale
            
            $meta['property_price']              = (string)$obj->property->price;
            $meta['property_price_view']         = (string)$obj->property->displayprice;
            $meta['property_auction']            = '';//not specified 
            $meta['property_price_display']      = 'yes';
            if($meta['property_status'] == 1){
                $meta['property_under_offer']    = 'yes';
            }else{
                $meta['property_under_offer']    = '';
            }
            
            $meta['property_sold_price']         = '';//not specified 
            $meta['property_sold_date']         = '';//not specified 
        }
        
        //House Features       
        $meta['property_bedrooms']         = (string)$obj->property->bedrooms;
        $meta['property_bathrooms']        = (string)$obj->property->bathrooms;
        $meta['property_receptions']       = (string)$obj->property->receptions;
        $meta['property_building_area']    = (string)$obj->property->floorarea ? (string)$obj->property->floorarea : '';//remove 0
        $meta['property_land_area']        = (string)$obj->property->landarea ? (string)$obj->property->landarea : '';//remove 0
        
        //Checkboxes
        $meta['property_garage_yes']            = (string)$obj->property->garage == 'Yes' ? 'Yes' : '';
        $meta['property_parkingoffstreet_yes']  = (string)$obj->property->parkingoffstreet == 'Yes' ? 'Yes' : '';
        $meta['property_doubleglazing_yes']     = (string)$obj->property->doubleglazing == 'Yes' ? 'Yes' : '';
        $meta['property_centralheating_yes']    = (string)$obj->property->centralheating == 'Yes' ? 'Yes' : '';
        $meta['property_retirement_yes']        = (string)$obj->property->retirement == 'Yes' ? 'Yes' : '';
        $meta['property_featured_yes']          = (string)$obj->property->featured == 'Yes' ? 'Yes' : '';
        $meta['property_exclusive_yes']         = (string)$obj->property->exclusive == 'Yes' ? 'Yes' : '';
        $meta['property_students_yes']          = (string)$obj->property->students == 'Yes' ? 'Yes' : '';
        $meta['property_benefit_yes']           = (string)$obj->property->benefit == 'Yes' ? 'Yes' : '';
        $meta['property_pets_yes']              = (string)$obj->property->pets == 'Yes' ? 'Yes' : '';
                
        //EPC DIR
        $meta['property_epcreference']          = (string)$obj->property->epcreference;
        $meta['property_epcregister']           = (string)$obj->property->epcregister;
        $meta['property_eercurrent']            = (string)$obj->property->eercurrent;
        $meta['property_eerpotential']          = (string)$obj->property->eerpotential;
        $meta['property_eircurrent']            = (string)$obj->property->eircurrent;
        $meta['property_eirpotential']          = (string)$obj->property->eirpotential;
        $meta['property_eerchart']              = (string)$obj->property->eerchart;
        $meta['property_eirchart']              = (string)$obj->property->eirchart;
        $meta['property_eircurrent']            = (string)$obj->property->eircurrent;
               
        $meta['property_new_construction']      = (string)$obj->property->newhome == 'Yes' ? 'Yes' : '';

        //fees [Text]
        $meta['property_fees']                  = (string)$obj->property->fees;
               
        //rooms [Textarea]
        $meta['property_rooms_text']            = (string)$obj->property->rooms;
        
        //bulletpoint1 - 10 [Text]
        $meta['property_bulletpoint1']          = (string)$obj->property->bulletpoint1;
        $meta['property_bulletpoint2']          = (string)$obj->property->bulletpoint2;
        $meta['property_bulletpoint3']          = (string)$obj->property->bulletpoint3;
        $meta['property_bulletpoint4']          = (string)$obj->property->bulletpoint4;
        $meta['property_bulletpoint5']          = (string)$obj->property->bulletpoint5;
        $meta['property_bulletpoint6']          = (string)$obj->property->bulletpoint6;
        $meta['property_bulletpoint7']          = (string)$obj->property->bulletpoint7;
        $meta['property_bulletpoint8']          = (string)$obj->property->bulletpoint8;
        $meta['property_bulletpoint9']          = (string)$obj->property->bulletpoint9;
        $meta['property_bulletpoint10']         = (string)$obj->property->bulletpoint10;
        
        //picture1 - 32 [image]          
        $meta['property_picture1']              = (string)$obj->property->picture1;
        $meta['property_picture2']              = (string)$obj->property->picture2;
        $meta['property_picture3']              = (string)$obj->property->picture3;
        $meta['property_picture4']              = (string)$obj->property->picture4;
        $meta['property_picture5']              = (string)$obj->property->picture5;
        $meta['property_picture6']              = (string)$obj->property->picture6;
        $meta['property_picture7']              = (string)$obj->property->picture7;
        $meta['property_picture8']              = (string)$obj->property->picture8;
        $meta['property_picture9']              = (string)$obj->property->picture9;
        $meta['property_picture10']             = (string)$obj->property->picture10;
        $meta['property_picture11']             = (string)$obj->property->picture11;
        $meta['property_picture12']             = (string)$obj->property->picture12;
        $meta['property_picture13']             = (string)$obj->property->picture13;
        $meta['property_picture14']             = (string)$obj->property->picture14;
        $meta['property_picture15']             = (string)$obj->property->picture15;
        $meta['property_picture16']             = (string)$obj->property->picture16;
        $meta['property_picture17']             = (string)$obj->property->picture17;
        $meta['property_picture18']             = (string)$obj->property->picture18;
        $meta['property_picture19']             = (string)$obj->property->picture19;
        $meta['property_picture20']             = (string)$obj->property->picture20;
        $meta['property_picture21']             = (string)$obj->property->picture21;
        $meta['property_picture22']             = (string)$obj->property->picture22;
        $meta['property_picture23']             = (string)$obj->property->picture23;
        $meta['property_picture24']             = (string)$obj->property->picture24;
        $meta['property_picture25']             = (string)$obj->property->picture25;
        $meta['property_picture26']             = (string)$obj->property->picture26;
        $meta['property_picture27']             = (string)$obj->property->picture27;
        $meta['property_picture28']             = (string)$obj->property->picture28;
        $meta['property_picture29']             = (string)$obj->property->picture29;
        $meta['property_picture30']             = (string)$obj->property->picture30;  
        $meta['property_picture31']             = (string)$obj->property->picture31;    
        $meta['property_picture32']             = (string)$obj->property->picture32;            
        
        //Other images [image]
        $meta['property_brochure']              = (string)$obj->property->brochure;         
        $meta['property_floorplan']             = (string)$obj->property->floorplan; 
        $meta['property_qrcode']                = (string)$obj->property->qrcode; 
        
        $meta['property_virtualtour']           = (string)$obj->property->virtualtour != 'http://' ? (string)$obj->property->virtualtour : ''; 
        
        $meta = apply_filters('aquaint_crm_epl_get_property_meta',$meta, $obj);
      
        return $meta;
        
    }
    
    
    public function getPropertyPostTerms($obj){
        
        $locality   = (string)$obj->property->locality;//Area
        $town       = (string)$obj->property->town;//City    
        
        
        if($locality){
            $tag[] = $locality; 
        }
        
        if($town){
            $tag[] = $town; 
        }        
                      
        return $tag;
        
    }
    
    
    
    /**
     * Maps Acquaint propertysalesstatus to EPL Property Status
     * 
     * EPL Property Status customised via action 'epl_opts_property_status_filter'
     * 
     * 
     * @param type $obj
     * @return string
     */
    private function getPropertyStatus($obj){
        
        $status = (string)$obj->property->status;
        
        switch($status){
            
            case 0 : 
                $state = 'available';
                break;
            case 1 : 
                $state = 'under-offer';
                break;      
            case 2 : //Sold
                $state = (string)$obj->property->statusdescription == 'Let' ? 'leased' : 'sold';              
                break;   
            case 3 : 
                $state = 'not-available';
                break;  
            case 4 : 
                $state = 'archived';
                break;            
            
        }   
        
        return $state;
        
    }
    
    
    public function getCountryCode($obj){
        
        $code = (string)$obj->property->countrycode;
        
        $countrycodes = AcquaintCountryCodes::iso31661Numeric();
        
        return $countrycodes[$code];       
                
    }
    
    
    public function getHouseCategory($obj){
        
        $id = (string)$obj->property->type;
        
        $array = $this->propertyTypesArray();
        
        return $array[$id];
     
    }    
    
    
    
    public static function propertyTypesArray(){       
            
            $array[] = 'Other';
            $array[] = 'Maisonette';
            $array[] = 'Flat';
            $array[] = 'Bungalow';
            $array[] = 'Terrace';
            $array[] = 'Semi-Detached';
            $array[] = 'Detached';   
            $array[] = 'Cottage';
            $array[] = 'Retirement';
            $array[] = 'Land';
            $array[] = 'Link-Detached';
            $array[] = 'Room';
            $array[] = 'Apartment';     
            $array[] = 'Retail / Commercial';
            $array[] = 'Industrial';
            $array[] = 'Office';
            $array[] = 'End Terrace';
            $array[] = 'Link-Terrace';
            $array[] = 'Town House';     
            $array[] = 'Student Accommodation';
            $array[] = 'Studio';
            $array[] = 'Penthouse';
            $array[] = 'Villa';
            $array[] = 'Mews';
            $array[] = 'House';     
            $array[] = 'Room (Double)';
            $array[] = 'Cluster House';
            $array[] = 'Garage';
            $array[] = 'Barn Conversion';
            $array[] = 'Conversion';
            $array[] = 'Lower Conversion';     
            $array[] = 'Upper Conversion';
            $array[] = 'Lower Cottage';
            $array[] = 'Upper Cottage';
            $array[] = 'Plot';
            $array[] = 'Complex';
            $array[] = 'Hotel Suite';     
            $array[] = 'Hotel Room';
            $array[] = 'Caravan';
            $array[] = 'Mobile Home';
            $array[] = 'Park Home';
            $array[] = 'Farm House';
            $array[] = 'Car Space';         
            $array[] = 'Farm Land';   
            $array[] = 'Restaurant'; 
            $array[] = 'Bar'; 
            $array[] = 'House Boat'; 
            $array[] = 'Chalet';     
            $array[] = 'Bedsit';   
            $array[] = 'Warehouse'; 
            $array[] = 'Hotel'; 
            $array[] = 'Leisure'; 
            $array[] = 'Serviced Office';  
            $array[] = 'Serviced Apartment';   
            $array[] = 'Manor Hous'; 
            $array[] = 'Country House'; 
            $array[] = 'Care Home'; 
            $array[] = 'Duplex';  
            $array[] = 'Triplex';   
            $array[] = 'Creche'; 
            $array[] = 'Semi-Detached Bungalow'; 
            $array[] = 'Detached Bungalow'; 
            $array[] = 'Block of Apartments';  
            $array[] = 'Pub';   
            $array[] = 'Commercial Property'; 
            $array[] = 'Guest House'; 
            $array[] = 'Lodge'; 
            $array[] = 'Log Cabin';  
            $array[] = 'Coach House';   
            $array[] = 'House Share'; 
            
            //? last id skips 10
            $array[79] = 'Flat Share'; 
      
        return $array;
        
    }
    
    
    public function getPropertyTenure($obj){
        
        $id = (string)$obj->property->tenure;
        
        $array = $this->getPropertyTenureArray();
        
        return $array[$id];
     
    }    
    
    
    public function getPropertyTenureArray(){
        
        $array[] = 'Freehold';
        $array[] = 'Leasehold';
        $array[] = 'To Let';
        $array[] = 'Share Transfer';
        $array[] = 'Share of Leasehold';
        $array[] = 'Flying Freehold';
        $array[] = 'Commonhold';
        $array[] = 'Feudal';
        $array[] = 'Share of Freehold';
              
        return $array;
        
    }
    
    
    public function getPropertyPricePrefix($obj){
        
        $id = (string)$obj->property->priceprefix;
        
        $array = $this->getPropertyPricePrefixArray();
        
        return $array[$id];
     
    }    
    
    
    public function getPropertyPricePrefixArray(){
        
        $array[] = '';
        $array[] = 'Price';
        $array[] = 'Guide Price';
        $array[] = 'OIEO';
        $array[] = 'OIRO';
        $array[] = 'POA';
        $array[] = 'Price Reduced';
        $array[] = 'Price From';
        $array[] = 'Offers Over';
        $array[] = 'Off Plan';
        $array[] = 'Offers Invited';
              
        return $array;
        
    }    
    
    public function getPropertyRentalTerm($obj){
        
        $id = (string)$obj->property->rentalterms;
        
        $array = $this->getPropertyRentalTermArray();
        
        return $array[$id];
     
    }    
    
    
    public function getPropertyRentalTermArray(){
        
        $array[] = 'Not Specified';
        $array[] = 'Short';
        $array[] = 'Long';
              
        return $array;
        
    }      
    
    
    
    public function getPriceFrequency($obj){
        
        $id = (string)$obj->property->pricefrequency;
        
        $array = $this->getPriceFrequencyArray();
        
        return $array[$id];
     
    }    
    
    
    public function getPriceFrequencyArray(){
        
        $array[] = 'pcm';
        $array[] = 'pw';
        $array[] = 'per annum';
        $array[] = 'per night';
        $array[] = 'pppw';
              
        return $array;
        
    }   
    
    
    public function getPropertyRentalFurnished($obj){
        
        $id = (string)$obj->property->rentalfurnished;
        
        $array = $this->getPropertyRentalFurnishedArray();
        
        return $array[$id];
     
    }    
    
    
    public function getPropertyRentalFurnishedArray(){
        
        
        $array[] = 'Unfurnished';
        $array[] = 'Part Furnished';
        $array[] = 'Furnished';
        $array[] = 'Furnished / Unfurnished';
        
        return $array;
        
        
    }
    
    public function getPropertyCountry($obj){
        
        $id = (string)$obj->property->countrycode;
        
        $array = AcquaintEplCountryCodes::iso31661Numeric();
        
        return $array[$id];
     
    }        
    

    
    
}


