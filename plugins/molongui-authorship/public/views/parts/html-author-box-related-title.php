<?php

?>

<div class="m-a-box-related-title">
    <?php echo ( $settings['related_title'] ? apply_filters( 'authorship/box/related/title', $settings['related_title'], $author ) : __( 'Related posts', 'molongui-authorship' ) ); ?>
</div>