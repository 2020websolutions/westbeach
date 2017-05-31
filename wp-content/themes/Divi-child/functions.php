<?php 

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' ); 

function my_enqueue_assets() { 

    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' ); 

} 



/**
 * -----------------------------------
 * 2020 Websolutions Custom Code START
 * -----------------------------------
 */

/*
 * Remove admin bar if logged in for faster page load for testing
 */
add_filter('show_admin_bar', '__return_false');


/*
 * Acquaint-crm-epl logging on 1 / off 0
 * Writes to /acquaint.log
 */
define('ACQUAINT_CRM_LOG_ON', 1);

/**
 * Add style-epl.css to separate css
 */
function acquaint_crm_epl_stylesheet() {
    wp_enqueue_style( 'acquaint-crm-epl-style', get_stylesheet_directory_uri().'/style-listing.css' );
}
add_action( 'wp_enqueue_scripts', 'acquaint_crm_epl_stylesheet' );


/*
 * Update the rental slug (save permalinks after changing)
 * 
 */
//define( 'EPL_RENTAL_SLUG' , 'holiday-lets/rental' );
//define( 'EPL_PROPERTY_SLUG' , 'for-sale/property' );

//define( 'EPL_RENTAL_SLUG' , 'lettings' );
//define( 'EPL_PROPERTY_SLUG' , 'sales' );
//define( 'EPL_RURAL_SLUG' , 'holiday-lets' );

define( 'EPL_RENTAL_SLUG' , 'property-search/lettings' );
define( 'EPL_PROPERTY_SLUG' , 'property-search/sales' );
define( 'EPL_RURAL_SLUG' , 'property-search/holiday-lets' );

/**
 * EPL Search Form Label Updates
 * NB: Specific form elements are included / excluded via the shortcode
 * 
 */
function epl_search_widget_label_property_location() {
	$label = 'AREA';
	return $label;
}
add_filter( 'epl_search_widget_label_property_location' , 'epl_search_widget_label_property_location' );

function epl_search_widget_label_property_category() {
	$label = 'PROPERTY TYPE';
	return $label;
}
add_filter( 'epl_search_widget_label_property_category' , 'epl_search_widget_label_property_category' );

function epl_search_widget_label_property_price_from() {
	$label = 'MIN PRICE';
	return $label;
}
add_filter( 'epl_search_widget_label_property_price_from' , 'epl_search_widget_label_property_price_from' );

function epl_search_widget_label_property_price_to() {
	$label = 'MAX PRICE';
	return $label;
}
add_filter( 'epl_search_widget_label_property_price_to' , 'epl_search_widget_label_property_price_to' );

function epl_search_widget_label_property_bedrooms_min() {
	$label = 'MIN BEDROOMS';
	return $label;
}
add_filter( 'epl_search_widget_label_property_bedrooms_min' , 'epl_search_widget_label_property_bedrooms_min' );

function epl_search_widget_label_property_bedrooms_max(){
	$label = 'MAX BEDROOMS';
	return $label;
}
add_filter( 'epl_search_widget_label_property_bedrooms_max' , 'epl_search_widget_label_property_bedrooms_max' );


/**
 * Set properties per page
 * NB: Check theme options for overrides
 * 
 * @param type $query
 * @return type
 */
function rental_property_pagesize( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;
    if ( is_post_type_archive( 'rental' ) ) {
        $query->set( 'posts_per_page', 9);
        return $query;
    }
}
add_action( 'pre_get_posts', 'rental_property_pagesize', 1 );


/**
 * Adds the number of properties found to the EPL utility bar
 */
function epl_add_properties_found(){    
    echo '<div class="epl_properties_found">'. $GLOBALS['wp_query']->found_posts. ' PROPERTIES FOUND</div>';   
}
add_action( 'epl_archive_utility_wrap_end', 'epl_add_properties_found', 1 );


/* -------------------------------------------------
 * Holiday Lets 'Rental Property Type' not supported.
 * Converting 'rural' property type to 'holiday-lets'
 * -------------------------------------------------
 */

/*
 * Change the 'Rural' proprty type to 'Holiday Lets'
 * 
 */
function set_rural_labels($labels) {
	$labels = array(
		'name'			=>	__('Holiday Lets', 'epl'),
		'singular_name'		=>	__('Holiday Let', 'epl'),
		'menu_name'		=>	__('Holiday Lets', 'epl'),
		'add_new'		=>	__('Add New', 'epl'),
		'add_new_item'		=>	__('Add New Listing', 'epl'),
		'edit_item'		=>	__('Edit Listing', 'epl'),
		'new_item'		=>	__('New Listing', 'epl'),
		'update_item'		=>	__('Update Listing', 'epl'),
		'all_items'		=>	__('All Listings', 'epl'),
		'view_item'		=>	__('View Listing', 'epl'),
		'search_items'		=>	__('Search Listing', 'epl'),
		'not_found'		=>	__('Listing Not Found', 'epl'),
		'not_found_in_trash'    =>	__('Listing Not Found in Trash', 'epl'),
		'parent_item_colon'	=>	__('Parent Listing:', 'epl')
	);
	return $labels;
}
add_filter('epl_rural_labels', 'set_rural_labels');


/**
 * Adjust post type. Set to 'rural' if tag: 'Holiday Let:' is found in short description
 */
function aquaint_crm_epl_get_post_type($post_type, $obj){
    
    $excerpt = (string)$obj->property->descriptionbrief;    
    
    //TESTING
    //-------
    //$holiday_porperties = array('175','190','187','183','2','169','181','193','211');
    //TESTING
    
//    if(in_array((string)$obj->property->id, $holiday_porperties)){
//       $excerpt = 'Holiday Let: This is a Holiday let';   
//    }
       
    if(stristr($excerpt, 'Holiday Let:')){
        $post_type = 'rural';
        Acquaint::log('PROPERTY ID ['.(string)$obj->property->id.'] SET TO HOLIDAY LET POST TYPE');
    }else{
        Acquaint::log('PROPERTY ID ['.(string)$obj->property->id.'] NOT HOLIDAY LET');
    }
    
    return $post_type;
    
    
}
add_filter('aquaint_crm_epl_get_post_type', 'aquaint_crm_epl_get_post_type', 10, 2);

  
/**
 * Rural has its own property categories so reset them...
 * 
 * @param type $defaults
 * @return type
 */
function epl_listing_meta_rural_category($defaults){
            
        $defaults = array();

        $typesArray = AcquaintToEpl::propertyTypesArray();

        foreach($typesArray as $value){
            $defaults[$value] = __($value, 'epl');
        }

        return $defaults;

}
add_filter('epl_listing_meta_rural_category', 'epl_listing_meta_rural_category' );

/**
 * Saveing meta data to property_rural_category to enable property type serach
 * 
 * @param array $meta
 * @param type $obj
 * @return type
 */
function aquaint_crm_epl_get_property_meta($meta, $obj){
    
   
    $id = (string)$obj->property->type;
    
    $array = AcquaintToEpl::propertyTypesArray();      
    
    $meta['property_rural_category'] = $array[$id];
    $meta['property_price'] = (string)$obj->property->price;
     
    
    return $meta;
}

add_filter('aquaint_crm_epl_get_property_meta', 'aquaint_crm_epl_get_property_meta', 10,2 );

/*
 * 
 */
function epl_search_widget_label_property_rural_category() {
	$label = 'PROPERTY TYPE';
	return $label;
}
add_filter( 'epl_search_widget_label_property_rural_category' , 'epl_search_widget_label_property_rural_category' );

/**
 * Update frintend price array as it searches price not rental
 * @param type $price_array
 * @return type
 */
function epl_listing_search_price_sale($price_array){
    
    $price_array = array_combine(range(50,5000,50),array_map('epl_currency_formatted_amount',range(50,5000,50)) );
    
    return $price_array;
}
add_filter( 'epl_listing_search_price_sale' , 'epl_listing_search_price_sale' );

/**
 * ---------------------------------
 * 2020 Websolutions Custom Code END
 * ---------------------------------
 */



