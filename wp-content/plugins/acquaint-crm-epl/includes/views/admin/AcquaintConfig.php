<?php
/**
 * Acquaint CRM To Easy Property Listings: Admin Configuration
 *
 * @package     Acquaint CRM To Easy Property Listings Import
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://2020websolutions.co.uk/commercial-license-v003
 * @since       1.0
 */


Class AcquaintConfig{
      
  
    
    public function __construct()
    {        
        
        add_action( 'admin_menu', array($this, 'acquaint_add_admin_menu'));
        add_action( 'admin_init', array($this, 'acquaint_settings_init'));
                     
    }
    
    
    public function acquaint_add_admin_menu(  ) { 

         if ( empty ( $GLOBALS['admin_page_hooks']['acquaint-crm-epl'] ) ){
        
            add_menu_page( 'acquaint-crm-epl', 'Acquaint CRM', 'manage_options', 'acquaint-crm-epl', array($this,  'acquaint_options_page') );
        
         }

}


    public function acquaint_settings_init(  ) { 

            register_setting( 'pluginPage', 'acquaint_settings' );

            add_settings_section(
                    'acquaint_pluginPage_section', 
                    __( '', 'acquaint-crm-epl' ), 
                    '', 
                    'pluginPage'
            );

            add_settings_field( 
                    'acquaint_text_field_0', 
                    __( 'Site Prefix', 'acquaint-crm-epl' ), 
                    array($this, 'acquaint_text_field_0_render'), 
                    'pluginPage', 
                    'acquaint_pluginPage_section'
            );

            add_settings_field( 
                    'acquaint_text_field_1', 
                    __( 'Site ID', 'acquaint-crm-epl' ), 
                    array($this, 'acquaint_text_field_1_render'), 
                    'pluginPage', 
                    'acquaint_pluginPage_section' 
            );

            add_settings_field( 
                    'acquaint_text_field_2', 
                    __( 'Password', 'acquaint-crm-epl' ), 
                    array($this, 'acquaint_text_field_2_render'), 
                    'pluginPage', 
                    'acquaint_pluginPage_section',
                    array('type' => 'password') 
            );


    }


    public function acquaint_text_field_0_render(  ) { 

            $options = get_option( 'acquaint_settings' );
            ?>
            <input type='text' name='acquaint_settings[acquaint_site_prefix]' value='<?php echo $options['acquaint_site_prefix']; ?>'>
            <?php

    }


    public function acquaint_text_field_1_render(  ) { 

            $options = get_option( 'acquaint_settings' );
            ?>
            <input type='text' name='acquaint_settings[acquaint_site_id]' value='<?php echo $options['acquaint_site_id']; ?>'>
            <?php

    }


    public function acquaint_text_field_2_render(  ) { 

            $options = get_option( 'acquaint_settings' );
            ?>
            <input type='password' name='acquaint_settings[acquaint_xml_service]' value='<?php echo $options['acquaint_xml_service']; ?>'>
            <?php

    }


    public function acquaint_settings_section_callback(  ) { 

            echo __( '', 'acquaint-crm-epl' );

    }


    public function acquaint_options_page(  ) { 

            ?>
            <form action='options.php' method='post'>

                    <h2>Acquaint XML Web Service Settings</h2>

                    <?php
                    settings_fields( 'pluginPage' );
                    do_settings_sections( 'pluginPage' );
                    submit_button();
                    ?>

            </form>
            <?php

    }


}
