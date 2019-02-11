<?php
/**
 * Property Template: Customised for Westbeach Properties
 * 
 * Renders the individual grid / list items pulled in on archive-listing.php
 *
 * @package     EPL
 * @subpackage  Templates/Content
 * @copyright   Copyright (c) 2016, Rich Hancock
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
?>
<div id="post-<?php the_ID(); ?>">
      
<div class="et_pb_specialty_column">
<div class=" et_pb_row_inner et_pb_row_inner_0">
        
        <?php global $post; ?>

        <div class="et_pb_column et_pb_column_1_1  property-details">

                <div class="property-meta property-title">                   
                        <?php echo get_post_meta($post->ID , 'property_address_street' , $single = true ) ?>, 
                        <?php echo get_post_meta($post->ID , 'property_address_city' , $single = true ) ?>                         
                        <?php $status = epl_get_price_sticker(); ?>
                        <?php if ( $status ):?>
                        <?php echo '&nbsp;&nbsp;&nbsp;['.$status.']'; ?> 
                        <?php endif; ?> 
                </div>
            
               
                <div class="property-meta property-pricing">
                    <?php echo get_post_meta($post->ID , 'property_rent_view' , $single = true ) ?> 
                    <?php echo get_post_meta($post->ID , 'property_price_view' , $single = true ) ?> 
                    <?php //do_action('epl_property_price'); ?>                
                </div>

        </div>


	<div class="entry-content epl-content epl-clearfix">
           
                <?php $media = EplTemplate::acquaint_crm_epl_get_media(); ?>
          
                <div class="property-featured-image-wrapper">
                    <!--Acquait picture1 is the featured image. It needs to be 800px wide-->
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php echo wp_get_attachment_image($media['featured'], 'full') ?>
                    <?php else: ?>                      
                        <img src="<?php echo ACQUAINT_CRM_EPL_URL; ?>/images/placeholder.png" />
                    <?php endif; ?>
                </div>
            
                <div class="property-thumbnails">
                    <div class="property-thumnail-row clearfix">
                        
                        <?php $i = 0; ?> 
                        <?php if(isset($media['pictures'])): ?>  
                        <?php foreach($media['pictures'] as $attachment_id ): ?>            
                           <?php if($i++ % 5 == 0): ?>              
                                </div><div class="property-thumnail-row clearfix"> 
                           <?php endif; ?> 
                           <a href="<?php echo wp_get_attachment_url( $attachment_id ) ?>" class="property-thumbnail hs-rsp-popup"><?php echo wp_get_attachment_image($attachment_id, 'full') ?></a>
                        <?php endforeach; ?> 
                        <?php endif; ?> 
                        </div>                                                                  
                </div>
            
        </div>
</div>
<div class=" et_pb_row_inner et_pb_row_inner_1">
    
    <div class="et_pb_column et_pb_column_1_3 et_pb_column_inner et_pb_column_inner_1 property-map">
        <a href="http://wordpress.2020demo.co.uk/sales/212/?googlemap=true"><?php do_action( 'epl_property_map' ); ?></a>
        
        
        <?php if(isset($media['floorplan'])): ?>
            <div class="floorplan">
                <a href="<?php echo wp_get_attachment_url($media['floorplan']) ?>" target="_blank" >
                <?php echo wp_get_attachment_image($media['floorplan'], 'full') ?>  
                </a>
            </div>
        <?php endif; ?>
            
    </div>
    
    <div class="et_pb_column et_pb_column_2_3 et_pb_column_inner et_pb_column_inner_2 property-content">
        
        <div class="property-content-section property-features">
            
        <h4>PROPERTY FEATURES</h4>
        
            <ul class="property-bullit-list clearfix">
                <?php for($i=1; $i<=10; $i++): ?>
                    <?php $key = 'property_bulletpoint'.$i; ?>
                    <?php $bullit = get_post_meta($post->ID , $key , $single = true ); ?>
                    <?php if($bullit):?>
                        <li class="property-bullit"><?php echo $bullit ?></li>
                    <?php endif;?>
                <?php endfor; ?>
            </ul>

        
        </div>
        
        <div class="property-content-section property-description">
       
        <h4>PROPERTY DESCRIPTION</h4>
            <?php do_action('epl_property_the_content');?>
               
        </div>        
        
        <div class="property-content-section property-rooms">
        
        <h4>ROOMS</h4>
            <?php echo get_post_meta($post->ID , 'property_rooms_text' , $single = true ) ?>
        
        
        </div>
        
    </div>
    
</div>
</div>
    
</div>