<?php
defined( 'ABSPATH' ) or exit;
$molongui_logo = $this->plugin->url . 'fw/admin/img/masthead_logo.png';
$logo = !empty( $args['logo'] ) ? $args['logo'] : $molongui_logo;
$link = !empty( $args['link'] ) ? $args['link'] : $this->plugin->web;
$btn  = !empty( $args['button'] );

?>

<!-- Page Header -->
<div class="m-page-masthead">
    <div class="m-page-masthead__inside_container">
        <div class="m-page-masthead__logo-container">
            <a class="m-page-masthead__logo-link" href="<?php echo $args['link']; ?>">
                <img src="<?php echo $logo; ?>" alt="<?php echo $this->plugin->name; ?>" height="32">
            </a>
        </div>
        <?php if ( $btn ) : ?>
            <div class="m-page-masthead__nav">
                <span class="m-buttons">
                    <a <?php echo ( !empty( $args['button']['id'] ) ? 'id="'.$args['button']['id'].'"' : '' ); ?> class="m-button <?php echo $args['button']['class']; ?> is-compact is-primary" type="button"><?php echo $args['button']['label']; ?></a>
                </span>
            </div>
        <?php endif; ?>
    </div><!-- !m-page-masthead -->
</div><!-- !m-page-masthead -->