<?php
defined( 'ABSPATH' ) or exit;

?>

<!-- Page Footer -->
<div class="m-page-footer">

    <div class="m-page-footer__a8c-attr-container">
        <a href="<?php echo $this->plugin->web; ?>">
            <img src="<?php echo $this->plugin->url . 'fw/admin/img/footer_logo.png'; ?>" alt="Molongui" width="152" height="32">
        </a>
    </div>

    <?php if ( !empty( $args['links'] ) ) : ?>
        <ul class="m-page-footer__links">
            <?php foreach( $args['links'] as $link ) : ?>
                <?php if ( $link['display'] ) : ?>
                    <li class="m-page-footer__link-item">
                        <a rel="noopener noreferrer" class="m-page-footer__link" target="_blank" href="<?php echo $link['href']; ?>">
                            <?php echo $link['prefix']; ?>
                                <?php echo $link['label']; ?>
                            <?php echo $link['suffix']; ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div><!-- !m-page-footer -->