<?php get_header(); ?>

<div id="main-content">    
    <div class="entry-content epl-listings epl-listings-archive"><!--epl-listings-->   
        
        <?php get_template_part('property-header'); ?>  
        
        <div class="et_pb_section  et_pb_section_1 et_section_regular et_section_transparent">            
            <div class="et_pb_row et_pb_row_1 et_pb_row_1-4_3-4">                
                <div class="et_pb_column et_pb_column_1_4 et_pb_column_0 epl-search">  
                    
                <?php get_template_part('property-search'); ?>    
                    
                </div>               
                <div class="et_pb_column et_pb_column_3_4 et_pb_column_1 epl-search-results">                    
                    <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left et_pb_text_0">                        
                        <div class="et_pb_row_inner et_pb_row_inner_0 et_pb_row_1-4_1-4_1-4">                         
                        <?php if (have_posts()) : ?>                            
                            <?php do_action('epl_property_loop_start'); ?>                            
                            <?php $i = 1;$row = 1;?>                                
                                <?php while (have_posts()) : the_post(); ?>                                    
                                    <article class="article-<?php echo $i++; ?>"><?php do_action('epl_property_blog'); ?></article> <!-- .et_pb_post -->                                                                    
                                
                                <?php if ($i++ % 3 == 0): ?>                                  
                                </div>                                
                                <div class="et_pb_row_inner et_pb_row_inner_<?php echo $row++; ?> et_pb_row_1-4_1-4_1-4">                                
                                <?php endif; ?> 
                                    
                                <?php endwhile; ?>                            
                            <?php do_action('epl_property_loop_end'); ?>	                            
                            <div class="loop-footer">                                
                                <!-- Previous/Next page navigation -->                                
                                <div class="loop-utility clearfix">                                    
                                    <?php do_action('epl_pagination'); ?>                                
                                </div>                            
                            </div>                                                            
                        <?php endif; ?>                           
                        </div>                              
                    </div>                
                </div>            
            </div>        
        </div>    
    </div>  
</div> 
<!-- #main-content -->
<?php get_footer(); ?>