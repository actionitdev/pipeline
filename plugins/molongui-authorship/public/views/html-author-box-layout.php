<?php
$show_related = ( $settings['layout'] != 'slim' and !empty( $settings['show_related'] ) and ( !empty( $author['posts'] ) or !empty( $settings['show_empty_related'] ) ) );
?>

<!-- MOLONGUI AUTHORSHIP PLUGIN <?php echo MOLONGUI_AUTHORSHIP_VERSION; ?> -->
<!-- <?php echo MOLONGUI_AUTHORSHIP_WEB; ?> -->
<div class="molongui-clearfix"></div>
<div id="mab-<?php echo $random_id; ?>"
     class="m-a-box <?php echo $settings['box_class']; ?>"
     data-plugin-release="<?php echo MOLONGUI_AUTHORSHIP_VERSION; ?>"
     data-plugin-version="<?php echo MOLONGUI_AUTHORSHIP_LICENSE; ?>"
     data-box-layout="<?php echo $settings['layout']; ?>"
     data-box-position="<?php echo $settings['position']; ?>"
     data-multiauthor="<?php echo ( $is_multiauthor ? 'true' : 'false' ); ?>"
     data-author-type="<?php echo $author['type']; ?>"
     <?php echo ( $add_microdata ? 'itemscope itemtype="https://schema.org/Person"' : '' ); ?>
     style="<?php echo ( ( !empty( $settings['box_width']  ) ) ? 'width: '  . $settings['box_width']  . '%;' : '' );?>
            <?php echo ( ( !empty( $settings['box_margin'] ) ) ? 'margin: ' . $settings['box_margin'] . 'px auto;' : '' );?>">

	<?php
		if ( $show_headline and !empty( $settings['show_headline'] ) and $settings['show_headline'] == 1  )
		{
			include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-headline.php';
		}
		if ( $show_tabs = ( !empty( $settings['layout'] ) and $settings['layout'] == 'tabbed' ) )
		{
		    echo '<div class="m-a-box-tabs">';
			include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-tabs.php';
		}

	?>

    <div class="m-a-box-container
                <?php echo $this->get_border( $settings['box_border'] ); ?>
                molongui-border-style-<?php echo ( ( !empty( $settings['box_border_style'] ) ) ? $settings['box_border_style'] : 'none' ); ?>
                molongui-border-width-<?php echo ( ( !empty( $settings['box_border_width'] ) ) ? $settings['box_border_width'] : '0' ); ?>-px
                m-a-box-shadow-<?php echo ( ( !empty( $settings['box_shadow'] ) ) ? $settings['box_shadow'] : 'left' ); ?>"
         style="<?php echo ( ( !empty( $settings['box_border_color'] ) ) ? 'border-color: ' . $settings['box_border_color'] . ';' : '' );?>
                <?php echo ( ( !empty( $settings['box_background'] ) ) ? 'background-color: ' . $settings['box_background'] . ';' : '' );?>">

        <div class="m-a-box-tab m-a-box-content m-a-box-profile"
             data-profile-layout="<?php echo $settings['profile_layout']; ?>"
             data-author-ref="<?php echo $author['type'].'-'.$author['id']; ?>">
            <?php
                if ( !isset( $settings['profile_layout'] ) or empty( $settings['profile_layout'] ) or $settings['profile_layout'] == 'layout-1' )
                {
                    include MOLONGUI_AUTHORSHIP_DIR . 'public/views/html-author-box-profile-layout-1.php';
                }
                elseif ( MOLONGUI_AUTHORSHIP_IS_PRO )
                {
                    include MOLONGUI_AUTHORSHIP_PRO_DIR . 'public/views/html-author-box-profile-'.$settings['profile_layout'].'.php';
                }
                elseif ( is_customize_preview() )
                {
                    require_once MOLONGUI_AUTHORSHIP_DIR . 'customizer/plugin-customizer-preview.php';
                    molongui_authorship_profile_customizer_preview( $author, $settings, $random_id );
                }
            ?>
        </div><!-- End of .m-a-box-profile -->

        <?php if ( $show_related ) : ?>

        <div class="m-a-box-tab m-a-box-content m-a-box-related" data-related-layout="<?php echo $settings['related_layout']; ?>">

            <div class="m-a-box-content-top">

		        <?php if ( $settings['layout'] == 'stacked' ) include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-related-title.php'; ?>

            </div><!-- End of .m-a-box-content-top -->

            <div class="m-a-box-content-middle">

                <!-- Related entries -->
                <div class="m-a-box-item m-a-box-related-entries" <?php echo ( $settings['layout'] == 'slim' ? 'style="display: none;"' : '' ); ?>>

			        <?php
			        $related_text_style = '';
			        if ( !empty( $settings['related_text_style'] ) )
			        {
				        foreach ( explode(',', $settings['related_text_style'] ) as $style ) $related_text_style .= ' molongui-text-style-'.$style;
			        }
			        ?>
                    <ul class="molongui-font-size-<?php echo $settings['related_text_size']; ?>-px <?php echo $related_text_style; ?>" style="color: <?php echo $settings['related_text_color']; ?>">
                        <?php
                        if ( !empty( $author['posts'] ) ) //or is_array( $author_posts ) or is_object( $author_posts ) )
                        {
	                        $premium_layouts = array( 'layout-3' );
	                        if ( !isset( $settings['related_layout'] ) or empty( $settings['related_layout'] ) or $settings['related_layout'] == 'layout-1' )
	                        {
		                        include MOLONGUI_AUTHORSHIP_DIR . 'public/views/html-author-box-related-layout-1.php';
	                        }
                            elseif ( isset( $settings['related_layout'] ) and !empty( $settings['related_layout'] ) and !in_array( $settings['related_layout'], $premium_layouts ) )
	                        {
		                        include MOLONGUI_AUTHORSHIP_DIR . 'public/views/html-author-box-related-'.$settings['related_layout'].'.php';
	                        }
                            elseif ( MOLONGUI_AUTHORSHIP_IS_PRO and in_array( $settings['related_layout'], $premium_layouts ) )
	                        {
		                        include MOLONGUI_AUTHORSHIP_PRO_DIR . 'public/views/html-author-box-related-'.$settings['related_layout'].'.php';
	                        }
                            elseif ( is_customize_preview() )
	                        {
		                        require_once MOLONGUI_AUTHORSHIP_DIR . 'customizer/plugin-customizer-preview.php';
		                        molongui_authorship_related_customizer_preview( $author, $settings, $random_id );
	                        }
                        }
                        else
                        {
                            echo ' <span class="m-a-box-string-no-related-posts">'. ( $settings['no_related_posts'] ? $settings['no_related_posts'] : __( 'This author does not have any more posts.', 'molongui-authorship' ) ).'</span>';
                        }
                        ?>
                    </ul>

                </div><!-- End of .m-a-box-related-entries -->

            </div><!-- End of .m-a-box-content-middle -->

            <div class="m-a-box-content-bottom"></div><!-- End of .m-a-box-content-bottom -->

        </div><!-- End of .m-a-box-related -->

        <?php endif; ?>

    </div><!-- End of .m-a-box-container -->

	<?php if ( $show_tabs ) echo '</div><!-- End of .m-a-box-tabs -->'; ?>

</div><!-- End of .m-a-box -->