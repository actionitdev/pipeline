<?php
$bio_text_style = '';
if ( !empty( $settings['bio_text_style'] ) )
{
	foreach ( explode(',', $settings['bio_text_style'] ) as $style ) $bio_text_style .= ' molongui-text-style-'.$style;
}
$bio_line_height = '';
if ( !empty( $settings['bio_line_height'] ) )
{
	$bio_line_height = 'molongui-line-height-'.$settings['bio_line_height']*10;
}

?>

<div class="m-a-box-bio" <?php echo ( $add_microdata ? 'itemprop="description"' : '' ); ?>>
	<div class="molongui-font-size-<?php echo $settings['bio_text_size']; ?>-px molongui-text-align-<?php echo $settings['bio_text_align']; ?> <?php echo $bio_text_style; ?> <?php echo $bio_line_height; ?>"
         style="color: <?php echo $settings['bio_text_color']; ?>">
	    <?php
        if ( !isset( $settings['show_bio'] ) or !empty( $settings['show_bio'] ) )
        {
            $bio = apply_filters( 'authorship/front/author/bio', str_replace( array("\n\r", "\r\n", "\n\n", "\r\r"), "<br>", wpautop(  $author['bio'] ) ) );

            echo $bio;
        }
	    if ( !empty( $settings['extra_content'] ) )
	    {
	        echo $settings['extra_content'];
	    }
        ?>
	</div>
</div>
