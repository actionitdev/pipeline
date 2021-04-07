<?php
defined( 'ABSPATH' ) or exit;
function molongui_authorship_profile_customizer_preview( $author, $settings, $random_id )
{
    $add_microdata  = false;

	switch ( substr( $settings['profile_layout'], 7 ) )
	{
        case '2':
        case '3':
        case '4':
        case '5':
		case '6':

		    include MOLONGUI_AUTHORSHIP_DIR . 'public/views/html-author-box-profile-layout-1.php';

		break;

		case '7':
		case '8': ?>

            <div class="m-a-box-content-top"></div><!-- End of .m-a-box-content-top -->

            <div class="m-a-box-content-middle">
                <!-- Author picture -->
                <?php include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-avatar.php'; ?>

                <!-- Author data -->
                <div class="m-a-box-item m-a-box-data <?php echo ( ( !empty( $settings['profile_valign'] ) and $settings['profile_valign'] != 'center' ) ? 'molongui-align-self-'.$settings['profile_valign'] : '' ); ?>">

                    <!-- Author name -->
		            <?php include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-name.php'; ?>

                    <!-- Author metadata -->
		            <?php include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-meta.php'; ?>

                    <!-- Author bio -->
		            <?php include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-bio.php'; ?>

		            <?php if ( $settings['layout'] == 'slim' and !empty( $settings['show_related'] ) ) : ?>

                        <!-- Author related posts -->
                        <div class="m-a-box-related" data-related-layout="<?php echo $settings['related_layout']; ?>">
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
						            if ( !empty( $author['posts'] ) )
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
							            echo ' <span class="m-a-box-string-no-related-posts">'. ( $settings[ 'no_related_posts' ] ? $settings[ 'no_related_posts' ] : __( 'This author does not have any more posts.', 'molongui-authorship' ) ).'</span>';
						            }
						            ?>
                                </ul>
                            </div><!-- End of .m-a-box-related-entries -->
                        </div><!-- End of .m-a-box-related -->
		            <?php endif; ?>
                </div><!-- End of .m-a-box-data -->
            </div><!-- End of .m-a-box-content-middle -->

            <div class="m-a-box-content-bottom molongui-border-style-<?php echo $settings['bottom_border_style']; ?> molongui-border-right-none molongui-border-bottom-none molongui-border-left-none molongui-border-width-<?php echo $settings['bottom_border_width']; ?>-px" style="background-color: <?php echo $settings['bottom_background_color']; ?>; border-color: <?php echo $settings['bottom_border_color']; ?>">
                <!-- Author social -->
                <?php include MOLONGUI_AUTHORSHIP_DIR . 'public/views/parts/html-author-box-socialmedia.php'; ?>
            </div><!-- End of .m-a-box-content-bottom -->

		<?php break;
	}
}
function molongui_authorship_related_customizer_preview( $author, $settings, $random_id )
{
	switch ( substr( $settings['related_layout'], 7 ) )
	{
        case '3':

            foreach( $author['posts'] as $related )
            {
            ?>
            <li>
                <div class="m-a-box-related-entry" itemscope itemtype="http://schema.org/CreativeWork">

                    <div class="molongui-display-none" itemprop="author" itemscope itemtype="http://schema.org/Person">
                        <div itemprop="name"><?php echo $author['name']; ?></div>
                        <div itemprop="url"><?php echo esc_url( $author['archive'] ); ?></div>
                    </div>

                    <!-- Related entry thumb -->
                    <div class="m-a-box-related-entry-thumb">
						<?php if ( has_post_thumbnail( $related->ID ) ) : ?>
                            <a href="<?php echo get_permalink( $related->ID ); ?>">
								<?php echo get_the_post_thumbnail( $related->ID, 'thumbnail', $attr = array( 'itemprop' => 'thumbnailUrl' ) ) ?>
                            </a>
						<?php else : ?>
                            <img src="<?php echo MOLONGUI_AUTHORSHIP_URL.'public/img/related_placeholder.svg'; ?>" width="<?php echo get_option( 'thumbnail_size_w' ).'px'; ?>">
						<?php endif; ?>
                    </div>

                    <!-- Related entry date -->
                    <div class="m-a-box-related-entry-date" itemprop="datePublished">
						<?php echo get_the_date( '', $related->ID ); ?>
                    </div>

                    <!-- Related entry title -->
                    <div class="m-a-box-related-entry-title">
                        <a class="molongui-remove-text-underline" itemprop="url" href="<?php echo get_permalink( $related->ID ); ?>">
                            <span itemprop="headline"><?php echo $related->post_title; ?></span>
                        </a>
                    </div>

                </div>
            </li>
            <?php
            }

		break;
	}
}