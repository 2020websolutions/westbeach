<?php get_header(); ?>
    <?php while (have_posts()) : the_post(); ?>	
<div id="main-content"> 
    
    <div class="entry-content epl-listings epl-listings-single"><!--epl-listings-->
        
    <?php get_template_part('property-header'); ?>   
        
        <div class="et_pb_section  et_pb_section_1 et_section_regular et_section_transparent">            
            <div class="et_pb_row et_pb_row_1 et_pb_row_1-4_3-4">                
                <div class="et_pb_column et_pb_column_1_4 et_pb_column_0 epl-search">   
                    
                <?php get_template_part('property-search'); ?>  
                    
            </div>                     
            <div class="et_pb_column et_pb_column_3_4 et_pb_column_1 epl-single-listing"> 
                
                
                <?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>                        
                <a href="javascript:javascript:history.go(-1)">&lt;&lt; BACK TO RESULTS</a>                                                
                <article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>  
                    
                    <?php if (function_exists('epl_property_single')): ?>                                
                        <?php echo epl_property_single(); ?>                            
                    <?php endif; ?>  
                    
                </article> <!-- .et_pb_post -->                        
                    <?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>                                                                                 
                        <?php endwhile; ?>                  
            </div>            
            </div>        
        </div>    
    </div>    
</div> <!-- #main-content --><?php get_footer(); ?>