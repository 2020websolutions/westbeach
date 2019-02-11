<?php
/**
 * Acquaint CRM To Easy Property Listings: EPL Filters
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

class EplFilters extends AcquaintToEpl{
    
    
    public function __construct()
    {
        
        add_action('do_meta_boxes', array( $this, 'remove_meta_boxes'), 10 );       
                
        add_filter('epl_opts_property_status_filter', array( $this, 'epl_opts_property_status_filter' ) );
        
        add_filter('epl_labels_property_status_filter', array( $this, 'epl_opts_property_status_filter' ) );
        
        add_filter('epl_listing_meta_property_category', array( $this, 'epl_listing_meta_property_category' ));
        
        add_filter('epl_opts_rent_period_filter' , array( $this, 'my_epl_opts_rent_period_filter') );    
        
        add_filter('epl_listing_meta_boxes' , array($this, 'epl_listing_meta_boxes') );

        add_filter('epl_listing_meta_address_block' , array($this, 'epl_listing_meta_address_block') );    
        
        add_filter('epl_meta_box_block_epl_features_section_id' , array($this, 'epl_meta_box_block_epl_features_section_id') );
        
        add_filter( 'epl_listing_search_bed_select_min' , array($this, 'my_custom_range_bedrooms_min' ));
        
        add_filter( 'epl_listing_search_bed_select_max' , array($this, 'my_custom_range_bedrooms_max' ));
        
        
        
    }    
    
    
    
    public function remove_meta_boxes(){
        
        if (is_admin()){
            remove_meta_box('epl-additional-features-section-id', $this->getPropertyPostTypeArray(), 'normal');
        }
               
    }
    
    public function my_custom_range_bedrooms_min() {
            $range = array(
                    //'0'		=>	'Studio',
                    '1'		=>	'1',
                    '2'		=>	'2',
                    '3'		=>	'3',
                    '4'		=>	'4',
                    '5'		=>	'5',
                    '6'		=>	'6',
                    '7'		=>	'7',
                    '8'		=>	'8',
                    '9'		=>	'9',
                    '10'	=>	'10',
            );
            return $range;
    }
    
    public function my_custom_range_bedrooms_max() {
            $range = array(
                    //'0'		=>	'Studio',
                    '1'		=>	'1',
                    '2'		=>	'2',
                    '3'		=>	'3',
                    '4'		=>	'4',
                    '5'		=>	'5',
                    '6'		=>	'6',
                    '7'		=>	'7',
                    '8'		=>	'8',
                    '9'		=>	'9',
                    '10'	=>	'10',
            );
            return $range;
    }    

  
    
    public function epl_opts_property_status_filter(){

        $fields = array(
                'available'     =>   __('Available', 'epl'),
                'not-available' =>   __('Not Available', 'epl'),
                'under-offer'   =>   __('Under Offer', 'epl'),
                'sold'          =>   __('Sold', 'epl'),        
                'leased'        =>   __('Let', 'epl'),
                'valuation'     =>   __('Valuation', 'epl'),
                'archived'      =>   __('Archived', 'epl'),

            );


         return $fields;    

    }        
    

    /**
     * Modify Property categories
     */    
    public function epl_listing_meta_property_category($defaults) {
        
            $defaults = array();
        
            $typesArray = AcquaintToEpl::propertyTypesArray();
            
            foreach($typesArray as $value){
                $defaults[$value] = __($value, 'epl');
            }
               
            return $defaults;
    }
    

    /**
     * Easy Property Listings Rental Options Filter
     * @return type
     */
    public function my_epl_opts_rent_period_filter() {
        
            $opts_rent_period = array(
                    'pcm'		=>	__('pcm', 'epl'),
                    'pw'		=>	__('pw', 'epl'),
                    'per quater'	=>	__('per quater ', 'epl'),
                    'per annum'		=>	__('per annum', 'epl'),
                    'per night'		=>	__('per night', 'epl'),
                    'pppw'		=>	__('pppw', 'epl'),
            );
            return $opts_rent_period;
    }

    
    
    /**
     * Separate Section Added for Acquiant Specific Fields
     * 
     * See: http://codex.easypropertylistings.com.au/article/131-epllistingmetaboxes-filter-all-available-meta-fields
     * 
     */    
    public function epl_listing_meta_boxes($meta_fields) {
            
        
        $custom_field = array(
                    'id'		=>	'epl_property_listing_custom_data_id',
                    'label'		=>	__('Custom Acquaint CRM Fields', 'epl'), // Box header
                    'post_type'	=>	array('property', 'rural', 'rental', 'land', 'commercial', 'commercial_land', 'business'), // Which listing types these will be attached to
                    'context'	=>	'normal',
                    'priority'	=>	'default',
                    'groups'	=>	array(
                            array(
                                    'id'		=>	'property_custom_data_section_1',
                                    'columns'	=>	'2', // One or two columns
                                    'label'		=>	'',
                                    'fields'	=>	array(
					array(
                                                    'name'		=>	'property_date_updated',
                                                    'label'		=>	__('Date Updated', 'epl'),
                                                    'type'		=>	'date',
					),     
                                        array(
                                                    'name'		=>	'property_statusdescription',
                                                    'label'		=>	__('Property Status Description', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),                                        
                                        array(
                                                    'name'		=>	'property_tenure',
                                                    'label'		=>	__('Property Tenure', 'epl'),
                                                    'type'		=>	'text',                                                    
                                                    'help'		=>	'',
                                            ),
                                            array(
                                                    'name'		=>	'property_price_prefix',
                                                    'label'		=>	__('Property Price Prefix', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),      
                                            array(
                                                    'name'		=>	'property_rent',
                                                    'label'		=>	__('Property Rent', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),       
                                            array(
                                                    'name'		=>	'property_rent_period',
                                                    'label'		=>	__('Property Rent Period', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),                                          
                                            array(
                                                    'name'		=>	'property_rent_view',
                                                    'label'		=>	__('Property Rent Text', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),    
                                            array(
                                                    'name'		=>	'property_date_available',
                                                    'label'		=>	__('Property Date Available', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),                                          
                                            array(
                                                    'name'		=>	'property_rental_term',
                                                    'label'		=>	__('Property Rental Term', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),     
                                            array(
                                                    'name'		=>	'property_rental_furnish',
                                                    'label'		=>	__('Furnishing', 'epl'),
                                                    'type'		=>	'text',
                                                    'help'		=>	'',
                                            ),         
                                            array(
                                                    'name'		=>	'property_rooms_text',
                                                    'label'		=>	__('Rooms (Formatted Text)', 'epl'),
                                                    'type'		=>	'textarea',

                                            ),     
                                            array(
                                                    'name'		=>	'property_fees',
                                                    'label'		=>	__('Fees', 'epl'),
                                                    'type'		=>	'text',
                                            ),                                           
                                            array(
                                                    'name'		=>	'property_bulletpoint1',
                                                    'label'		=>	__('Bullet Point 1', 'epl'),
                                                    'type'		=>	'text',

                                            ),       
                                            array(
                                                    'name'		=>	'property_bulletpoint2',
                                                    'label'		=>	__('Bullet Point 2', 'epl'),
                                                    'type'		=>	'text',

                                            ),     
                                            array(
                                                    'name'		=>	'property_bulletpoint3',
                                                    'label'		=>	__('Bullet Point 3', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_bulletpoint4',
                                                    'label'		=>	__('Bullet Point 4', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_bulletpoint5',
                                                    'label'		=>	__('Bullet Point 5', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_bulletpoint6',
                                                    'label'		=>	__('Bullet Point 6', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_bulletpoint7',
                                                    'label'		=>	__('Bullet Point 7', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_bulletpoint8',
                                                    'label'		=>	__('Bullet Point 8', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_bulletpoint9',
                                                    'label'		=>	__('Bullet Point 9', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_bulletpoint10',
                                                    'label'		=>	__('Bullet Point 10', 'epl'),
                                                    'type'		=>	'text',

                                            ),  
                                            array(
                                                    'name'		=>	'property_picture1',
                                                    'label'		=>	__('Picture 1', 'epl'),
                                                    'type'		=>	'text',

                                            ),       
                                            array(
                                                    'name'		=>	'property_picture2',
                                                    'label'		=>	__('Picture 2', 'epl'),
                                                    'type'		=>	'text',

                                            ),         
                                            array(
                                                    'name'		=>	'property_picture3',
                                                    'label'		=>	__('Picture 3', 'epl'),
                                                    'type'		=>	'text',

                                            ),        
                                            array(
                                                    'name'		=>	'property_picture4',
                                                    'label'		=>	__('Picture 4', 'epl'),
                                                    'type'		=>	'text',

                                            ),     
                                            array(
                                                    'name'		=>	'property_picture5',
                                                    'label'		=>	__('Picture 5', 'epl'),
                                                    'type'		=>	'text',

                                            ),            
                                            array(
                                                    'name'		=>	'property_picture6',
                                                    'label'		=>	__('Picture 6', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture7',
                                                    'label'		=>	__('Picture 7', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture8',
                                                    'label'		=>	__('Picture 8', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture9',
                                                    'label'		=>	__('Picture 9', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture10',
                                                    'label'		=>	__('Picture 10', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture11',
                                                    'label'		=>	__('Picture 11', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture12',
                                                    'label'		=>	__('Picture 12', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture13',
                                                    'label'		=>	__('Picture 13', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture14',
                                                    'label'		=>	__('Picture 14', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture15',
                                                    'label'		=>	__('Picture 15', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture16',
                                                    'label'		=>	__('Picture 16', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture17',
                                                    'label'		=>	__('Picture 17', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture19',
                                                    'label'		=>	__('Picture 18', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture19',
                                                    'label'		=>	__('Picture 19', 'epl'),
                                                    'type'		=>	'text',

                                            ),   
                                            array(
                                                    'name'		=>	'property_picture20',
                                                    'label'		=>	__('Picture 20', 'epl'),
                                                    'type'		=>	'text',

                                            ),       
                                            array(
                                                    'name'		=>	'property_picture21',
                                                    'label'		=>	__('Picture 21', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture22',
                                                    'label'		=>	__('Picture 22', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture23',
                                                    'label'		=>	__('Picture 23', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture24',
                                                    'label'		=>	__('Picture 24', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture25',
                                                    'label'		=>	__('Picture 25', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture26',
                                                    'label'		=>	__('Picture 26', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture27',
                                                    'label'		=>	__('Picture 27', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture28',
                                                    'label'		=>	__('Picture 28', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture29',
                                                    'label'		=>	__('Picture 29', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture30',
                                                    'label'		=>	__('Picture 30', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture31',
                                                    'label'		=>	__('Picture 31', 'epl'),
                                                    'type'		=>	'text',

                                            ),      
                                            array(
                                                    'name'		=>	'property_picture32',
                                                    'label'		=>	__('Picture 32', 'epl'),
                                                    'type'		=>	'text',

                                            ),                                                                              
                                            array(
                                                    'name'		=>	'property_brochure',
                                                    'label'		=>	__('Brochure', 'epl'),
                                                    'type'		=>	'text',

                                            ),    
                                            array(
                                                    'name'		=>	'property_virtualtour',
                                                    'label'		=>	__('Virtual Tour', 'epl'),
                                                    'type'		=>	'text',

                                            ),     
                                            array(
                                                    'name'		=>	'property_floorplan',
                                                    'label'		=>	__('Floorplan', 'epl'),
                                                    'type'		=>	'text',

                                            ),                                             
                                            array(
                                                    'name'		=>	'property_qrcode',
                                                    'label'		=>	__('QrCode', 'epl'),
                                                    'type'		=>	'text',

                                            ),                                            
                                                                                   
                                           
                                    )
                            ),
                        
                        array(
				'id'		=>	'property_custom_data_section_2',
				'columns'	=>	'2', // One or two columns
				'label'		=>	'',
				'fields'	=>	array(
                                        array(
                                                'name'		=>	'property_epcreference',
                                                'label'		=>	__('EPC Referencee', 'epl'),
                                                'type'		=>	'text',

                                        ),        
                                        array(
                                                'name'		=>	'property_epcregister',
                                                'label'		=>	__('EPC Register', 'epl'),
                                                'type'		=>	'text',

                                        ),        
                                        array(
                                                'name'		=>	'property_eercurrent',
                                                'label'		=>	__('EER Current', 'epl'),
                                                'type'		=>	'decimal',
                                                'maxlength'	=>	'4',

                                        ),        
                                        array(
                                                'name'		=>	'property_eerpotential',
                                                'label'		=>	__('EER Potential', 'epl'),
                                                'type'		=>	'decimal',
                                                'maxlength'	=>	'4',

                                        ),        
                                        array(
                                                'name'		=>	'property_eircurrent',
                                                'label'		=>	__('EIR Current', 'epl'),
                                                'type'		=>	'decimal',
                                                'maxlength'	=>	'4',

                                        ),        
                                        array(
                                                'name'		=>	'property_eirpotential',
                                                'label'		=>	__('EIR Potential', 'epl'),
                                                'type'		=>	'decimal',
                                                'maxlength'	=>	'4',

                                        ),                                        
                                        array(
                                                'name'		=>	'property_eerchart',
                                                'label'		=>	__('EER Chart', 'epl'),
                                                'type'		=>	'text',

                                        ),          
                                        array(
                                                'name'		=>	'property_eirchart',
                                                'label'		=>	__('EIR Chart', 'epl'),
                                                'type'		=>	'text',

                                        ),                                        
					array(
						'name'		=>	'property_exclusive_yes',
						'label'		=>	__('Exclusive', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),     
					array(
						'name'		=>	'property_featured_yes',
						'label'		=>	__('Featured', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),                                       
					array(
						'name'		=>	'property_garage_yes',
						'label'		=>	__('Garage', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),
					array(
						'name'		=>	'property_parkingoffstreet_yes',
						'label'		=>	__('Off Street Parking', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),     
					array(
						'name'		=>	'property_garden_yes',
						'label'		=>	__('Garden', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),    
					array(
						'name'		=>	'property_doubleglazing_yes',
						'label'		=>	__('Double Glazing', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),    
					array(
						'name'		=>	'property_retirement_yes',
						'label'		=>	__('Retirement', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),           
					array(
						'name'		=>	'property_students_yes',
						'label'		=>	__('Students', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),   
					array(
						'name'		=>	'property_benefit_yes',
						'label'		=>	__('Benefit', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),   
					array(
						'name'		=>	'property_pets_yes',
						'label'		=>	__('Pets', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),                                          
                                    
				)
			),                        

                    )
            );
            $meta_fields[] = $custom_field;
            return $meta_fields;
    }
    
    
   public function epl_listing_meta_address_block($meta_fields) {
           
       
            foreach($meta_fields['fields'] as $key => $field){
                
                $newOrder[] = $field;
                
                if($field['name'] == 'property_address_lot_number' && $meta_fields['fields'][$key+1]['name'] != 'property_address_name'){
                    $newOrder[] = 	array(
                                        'name'		=>	'property_address_name',
                                        'label'		=>	__('Property Name', 'easy-property-listings' ),
                                        'type'		=>	'text',
                                        'maxlength'	=>	'150'
                                );                    
                }
            }
            
            $meta_fields['fields'] = $newOrder;
            
            return $meta_fields;
    }
    
    
   private function getHiddenFields(){
       
       return array(
           'property_ensuite',
           'property_toilet',
           'property_garage',
           'property_carport',
           'property_year_built',
           'property_pool',
           'property_air_conditioning',
           'property_security_system',
           'property_land_fully_fenced'
           
       );
               
       
   } 
    
   public function epl_meta_box_block_epl_features_section_id($meta_box) {

       
            foreach($meta_box['groups'][0]['fields'] as $key => $field){
                
                if(in_array($field['name'], $this->getHiddenFields())){
                    continue;
                }
                
                $newOrder[] = $field;
                
                if($field['name'] == 'property_rooms' && $meta_box['groups'][0]['fields'][$key+1]['name'] != 'property_receptions'){
                    $newOrder[] = 	array(
                                        'name'		=>	'property_receptions',
                                        'label'		=>	__('Receptions', 'easy-property-listings' ),
                                        'type'		=>	'decimal',
                                        'maxlength'	=>	'4'
                                );                    
                }
            }
            
            $meta_box['groups'][0]['fields'] = $newOrder;
            
            return $meta_box;
    }    
    
   
    
/**
 * Example custom meta section a
 * @param array $meta_fields
 * @return array
 */
function my_add_meta_box_epl_listings_advanced_callback($meta_fields) {
	$custom_field = array(
		'id'		=>	'epl_property_listing_custom_data_id',
		'label'		=>	__('Custom Details', 'epl'), // Box header
		'post_type'	=>	$this->getPropertyPostTypeArray(), // Listing types these will be attached to
		'context'	=>	'normal',
		'priority'	=>	'default',
		'groups'	=>	array(
			array(
				'id'		=>	'property_custom_data_section_1',
				'columns'	=>	'2', // One or two columns
				'label'		=>	'custom property data 1',
				'fields'	=>	array(
					array(
						'name'		=>	'property_custom_data_text',
						'label'		=>	__('custom property data', 'epl'),
						'type'		=>	'text',
						'maxlength'	=>	'150',
						'help'		=>	'Help text',
					),
					array(
						'name'		=>	'property_custom_data_radio',
						'label'		=>	__('custom property data Radio Options', 'epl'),
						'type'		=>	'radio',
						'opts'		=>	array(
								'option_1'	=>	'Option 1',
								'option_2'	=>	'Option 2',
								'option_3'	=>	'Option 3',
							),
						'help'		=>	'Radio help text',
					),
					array(
						'name'		=>	'property_custom_data_checkbox_single',
						'label'		=>	__('Checkbox Single', 'epl'),
						'type'		=>	'checkbox_single',
						'opts'		=>	array(
							'yes'	=>	__('Yes', 'epl'),
						)
					),
					
					array(
						'name'		=>	'property_custom_data_checkbox_multiple',
						'label'		=>	__('Checkbox Multiple', 'epl'),
						'type'		=>	'checkbox',
						'opts'		=>	array(
							'red'	=>	__('Red', 'epl'),
							'green'	=>	__('Green', 'epl'),
							'blue'	=>	__('Blue', 'epl'),
						)
					)
				)
			),
			
			array(
				'id'		=>	'property_custom_data_section_2',
				'columns'	=>	'2', // One or two columns
				'label'		=>	'custom property data 2',
				'fields'	=>	array(
					array(
						'name'		=>	'property_custom_data_select',
						'label'		=>	__('Custom Select', 'epl'),
						'type'		=>	'select',
						'opts'		=>	array(
								'select_1'	=>	'Select 1',
								'select_2'	=>	'Select 2',
								'select_3'	=>	'Select 3',
							),
					),
					
					array(
						'name'		=>	'property_custom_data_decimal',
						'label'		=>	__('Custom Decimal', 'epl'),
						'type'		=>	'decimal',
						'maxlength'	=>	'50'
					),
					
					array(
						'name'		=>	'property_custom_data_number',
						'label'		=>	__('Custom Number', 'epl'),
						'type'		=>	'number',
						'maxlength'	=>	'50'
					),
					
					array(
						'name'		=>	'property_custom_data_date',
						'label'		=>	__('Custom Date', 'epl'),
						'type'		=>	'date',
						'maxlength'	=>	'100'
					),
					
					array(
						'name'		=>	'property_custom_text_area',
						'label'		=>	__('Custom Text Area', 'epl'),
						'type'		=>	'textarea',
						'maxlength'	=>	'500'
					)
				)
			),
			array(
				'id'		=>	'property_custom_data_section_3',
				'columns'	=>	'1', // One or two columns
				'label'		=>	'',
				'fields'	=>	array(


					array(
						'name'		=>	'property_custom_editor',
						'label'		=>	__('Custom Editor', 'epl'),
						'type'		=>	'editor',
					)
				)
			)
		)
	);
	$meta_fields[] = $custom_field;
	return $meta_fields;
}
//add_filter( 'epl_listing_meta_boxes' , 'my_add_meta_box_epl_listings_advanced_callback' );    
    

    
}