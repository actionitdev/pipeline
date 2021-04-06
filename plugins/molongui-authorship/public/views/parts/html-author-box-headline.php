<?php
$headline_text_style = '';
if ( !empty( $settings['headline_text_style'] ) )
{
    foreach ( explode(',', $settings['headline_text_style'] ) as $style ) $headline_text_style .= ' molongui-text-style-'.$style;
}
$headline_tag = ( !empty( $settings['box_headline_tag'] ) ? $settings['box_headline_tag'] : 'h3' );
?>

<?php if ( !empty( $settings['show_headline'] ) and $settings['show_headline'] == 1  ) : ?>
    <!-- Author headline -->
    <div class="m-a-box-item m-a-box-headline">
        <<?php echo $headline_tag; ?> class="molongui-font-size-<?php echo $settings['headline_text_size']; ?>-px
                                             molongui-text-align-<?php echo $settings['headline_text_align']; ?>
                                             <?php echo $headline_text_style; ?>
                                             molongui-text-case-<?php echo $settings['headline_text_case']; ?>"
            style="color: <?php echo $settings['headline_text_color']; ?>">
            <span class="m-a-box-string-headline"><?php echo ( $settings['headline'] ? $settings['headline'] : __( 'About the author', 'molongui-authorship' ) ); ?></span>
        </<?php echo $headline_tag; ?>>
    </div>
<?php endif; ?>