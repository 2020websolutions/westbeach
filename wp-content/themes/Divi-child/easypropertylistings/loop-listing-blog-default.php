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

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
global $property;
global $post;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-clearfix'); ?> <?php do_action('epl_archive_listing_atts'); ?>>
    
    
    
	<?php do_action('epl_property_before_content'); ?>
    
       <div class="epl-property-wrapper ">
		
			<div class="property-box property-box-left property-featured-image-wrapper">
                            
                            <?php if ( has_post_thumbnail() ) : ?>
				<?php do_action('epl_property_archive_featured_image'); ?>
                            <?php else: ?>
                            <div class="epl-archive-entry-image epl-blog-image">  
                                <div class="epl-stickers-wrapper">
                                        <?php echo epl_get_price_sticker(); ?>
                                </div>
                                <img src="<?php echo ACQUAINT_CRM_EPL_URL; ?>images/placeholder300x200.png" />
                            </div>
                            <?php endif; ?>
			</div>
		

		<div class="property-box property-box-right property-content">
			<!-- Heading -->
                        <div class="property-grid-text">
                            <div class="entry-title-street"><a href="<?php the_permalink() ?>"><?php echo get_post_meta($post->ID , 'property_address_street' , $single = true ) ?></a></div>
                            <div class="entry-title-city"><a href="<?php the_permalink() ?>"><?php echo get_post_meta($post->ID , 'property_address_city' , $single = true ) ?></a></div>
                            <!-- Price -->
                            <div class="price">
                                <?php echo get_post_meta($post->ID , 'property_rent_view' , $single = true ) ?> 
                                <?php echo get_post_meta($post->ID , 'property_price_view' , $single = true ) ?> 
                            </div>  
                        </div>
                        <div class="more-info">
                                <span class="more-info-title"><a href="<?php the_permalink() ?>">MORE INFO</a></span>
                        </div>
		</div>
              
        
        </div>    
	<?php do_action('epl_property_after_content'); ?>
        
        
        
</div>
