<?php
/**
 * Acquaint CRM To Easy Property Listings: Deactivator
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

/**
 * Stop Direct Access
 */
defined('ABSPATH') or die("Die");

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class AcquaintEplDeactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
            
            wp_clear_scheduled_hook('acquaint_crm_web_service_import');
        
      
	}
        
        
        
        

}
