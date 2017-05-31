<?php
/**
 * Acquaint CRM To Easy Property Listings: Core Class
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */

Class AcquaintEpl{
    
    
    public static $version = '1.0.0.0';
    
    
    private static $instance;


    /**
     * Singleton instance
     *
     * @return CrewAvailability_Plugin   CrewAvailability_Plugin  object
     */
    public static function getInstance() {

            if( empty( self::$instance ) ) {
                    self::$instance = new self;
            }

            return self::$instance;
    }       
    

    
    
    public function __construct()
    {
        $this->includeFiles();         
        
        //Runs via cron 'hourly' to import and update properties
        add_action('acquaint_crm_web_service_import', array($this, 'runAcquaintImport'));
        
        //Runs via cron 'daily' to delete properties
        add_action('acquaint_crm_web_service_delete', array($this, 'runAcquaintDelete'));
        
        //Manual Testing
        add_shortcode('do_acquaint_crm_web_service_import', array($this, 'runAcquaintImport')); 
        add_shortcode('do_acquaint_crm_web_service_delete', array($this, 'runAcquaintDelete'));
        
        //add_action('acquaint_crm_epl_get_media', array(new EplTemplateFunctions, 'acquaint_crm_epl_get_media'));
        
        // Adds fields and constants to EPL Property and Rental Admin
        new EplFilters;        
        
        //Add setting page for acquaint web service user pass and site prefix
        new AcquaintConfig;
                     
    }
    
    private function includeFiles(){

        
        
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/models/resource/Acquaint.php' );
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/models/resource/AcquaintWebservice.php' );
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/models/resource/AcquaintWebserviceCall.php' );        
        
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/models/AcquaintEplCountryCodes.php' );
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/models/AcquaintToEpl.php' );
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/models/AcquaintEplCronJob.php' );

        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/views/admin/AcquaintConfig.php' );
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/views/EplFilters.php' );
        require_once( ACQUAINT_CRM_EPL_DIR . 'includes/views/EplTemplate.php' );

        //required for media_sideload_image() to import image from url
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );   

    }    
           

       
    /**
     * Property Import 
     * 
     * @param type $atts
     * @return type
     */
    public function runAcquaintImport()
    {
                              
        $call = new AcquaintEplCronJob();

        $call->acquaintPropertyImport();                 
 
    }
    
    
    /**
     * Property Delete 
     * 
     * @param type $atts
     * @return type
     */
    public function runAcquaintDelete()
    {
                        
        $call = new AcquaintEplCronJob();

        $call->acquaintPropertyDelete();                 
 
    }    
    
    
    
    



}
