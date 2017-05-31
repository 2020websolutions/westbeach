
<div class="et_pb_section et_pb_section_0 et_pb_with_background et_section_regular">
    <div class=" et_pb_row et_pb_row_0">
        <div class="et_pb_column et_pb_column_4_4 et_pb_column_0">
            <div class="et_pb_text et_pb_module et_pb_bg_layout_dark et_pb_text_align_left et_pb_text_0">
                <?php if ( is_post_type_archive( 'property' ) || get_post_type() == 'property' ) : ?>
                <h1>SALES</h1>
                <?php endif; ?>
                <?php if ( is_post_type_archive( 'rental' )  || get_post_type() == 'rental'  ) : ?>
                <h1>LETTINGS</h1>
                <?php endif; ?>
                <?php if ( is_post_type_archive( 'rural' )  || get_post_type() == 'rural' ) : ?>
                <h1>HOLIDAY LETS</h1>
                <?php endif; ?>  
            </div>
        </div>
    </div>
</div>