<?php
$name_text_style = '';
if ( !empty( $settings['name_text_style'] ) )
{
	foreach ( explode(',', $settings['name_text_style'] ) as $style ) $name_text_style .= ' molongui-text-style-'.$style;
}
$name_tag = ( !empty( $settings['box_author_name_tag'] ) ? $settings['box_author_name_tag'] : 'h5' );

?>

<div class="m-a-box-title">
	<<?php echo $name_tag; ?> class="molongui-font-size-<?php echo $settings['name_text_size']; ?>-px
	        molongui-text-align-<?php echo $settings['name_text_align']; ?>
            <?php echo $name_text_style; ?>
            molongui-text-case-<?php echo $settings['name_text_case']; ?>"
            style="color: <?php echo $settings['name_text_color']; ?>"
            <?php echo ( $add_microdata ? 'itemprop="name"' : '' ); ?>>
        <?php
            if ( !$settings['name_link_to_archive'] or
	             ( $author['type'] === 'guest' and !MOLONGUI_AUTHORSHIP_IS_PRO ) or
	             ( $author['type'] === 'guest' and !$settings['guest_archive_enabled'] ) or
	             ( $author['type'] === 'user'  and !$settings['user_archive_enabled'] ) )
            {
	            ?>
                <span class="molongui-font-size-<?php echo $settings['name_text_size']; ?>-px molongui-text-align-<?php echo $settings['name_text_align']; ?>" style="color:<?php echo$settings['name_text_color']; ?>;">
			        <?php echo $author['name']; ?>
                </span>
	            <?php
            }
            elseif ( is_customize_preview() and
                     ( ( $author['type'] === 'guest' and $settings['guest_archive_enabled'] and MOLONGUI_AUTHORSHIP_IS_PRO ) or
                       ( $author['type'] === 'user'  and $settings['user_archive_enabled'] ) ) )
            {
	            ?>
                <a href="#" class="customize-unpreviewable <?php echo ( $settings['name_inherited_underline'] == 'remove' ? 'molongui-remove-text-underline' : '' ); ?> molongui-font-size-<?php echo $settings['name_text_size']; ?>-px molongui-text-align-<?php echo $settings['name_text_align']; ?> <?php echo $name_text_style; ?>" style="color:<?php echo $settings['name_text_color']; ?>;" <?php echo ( $add_microdata ? 'itemprop="url"' : '' ); ?>>
		            <?php echo $author['name']; ?>
                </a>
	            <?php
            }
            else
            {
	            ?>
                <a href="<?php echo esc_url( $author['archive'] ); ?>" class="<?php echo ( $settings['name_inherited_underline'] == 'remove' ? 'molongui-remove-text-underline' : '' ); ?> molongui-font-size-<?php echo $settings['name_text_size']; ?>-px molongui-text-align-<?php echo $settings['name_text_align']; ?> <?php echo $name_text_style; ?>" style="color:<?php echo $settings['name_text_color']; ?>;" <?php echo ( $add_microdata ? 'itemprop="url"' : '' ); ?>>
		            <?php echo $author['name']; ?>
                </a>
	            <?php
            }
        ?>
	</<?php echo $name_tag; ?>>
</div>