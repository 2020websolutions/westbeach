<?php
/**
* Plugin Name: Acquaint CRM To Easy Property Listings Import
* Plugin URI: http://2020websolutions.co.uk
* Description: Acquaint CRM To Easy Property Listings Importer with scheduled updates
* Version: 0.1.0
* Author: Rich Hancock
* Author URI: http://2020websolutions.co.uk
* License: commercial-license-v003
*/

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Full path to the plugin file "./plugin-file.php"
 */
define( 'ACQUAINT_CRM_EPL_FILE', __FILE__ );

/**
 * The URL to plugin directory
 */
define( 'ACQUAINT_CRM_EPL_URL', plugin_dir_url( __FILE__ ) );

/**
 * The absolute path to the plugin directory
 */
define( 'ACQUAINT_CRM_EPL_DIR', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * 
 */
function activate_acquaint_crm_epl() {
	require_once ACQUAINT_CRM_EPL_DIR . 'includes/models/setup/AcquaintEplActivator.php';
	AcquaintEplActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * 
 */
function deactivate_acquaint_crm_epl() {
	require_once ACQUAINT_CRM_EPL_DIR . 'includes/models/setup/AcquaintEplDeactivator.php';
	AcquaintEplDeactivator::deactivate();
}

register_activation_hook( ACQUAINT_CRM_EPL_FILE, 'activate_acquaint_crm_epl' );
register_deactivation_hook( ACQUAINT_CRM_EPL_FILE , 'deactivate_acquaint_crm_epl' );


/**
 * The core plugin class 
 */
require_once ACQUAINT_CRM_EPL_DIR . 'includes/models/AcquaintEpl.php';


function run_acquaint_crm_epl() {
  
	$plugin = new AcquaintEpl();
	$plugin->getInstance();

}
add_action('plugins_loaded', 'run_acquaint_crm_epl');



        




