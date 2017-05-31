                    

<div class="epl-searchbox-title">
    <h3>PROPERTY SEARCH</h3>  
</div>

<?php if (is_post_type_archive('property') || get_post_type() == 'property') : ?>
    <?php echo do_shortcode('[listing_search post_type=property submit_label="SEARCH" search_bath="off" search_other="off" limit="9"]'); ?>
<?php endif; ?>
<?php if (is_post_type_archive('rental') || get_post_type() == 'rental') : ?>
    <?php echo do_shortcode('[listing_search post_type="rental" submit_label="SEARCH" search_bath="off" search_other="off" limit="9"]'); ?>                        
<?php endif; ?>
<?php if (is_post_type_archive('rural') || get_post_type() == 'rural') : ?>
    <?php echo do_shortcode('[listing_search post_type="rural"  submit_label="SEARCH" search_bath="off" search_other="off" limit="9"]'); ?>                        
<?php endif; ?>