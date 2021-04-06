<?php
defined( 'ABSPATH' ) or exit;

?>

<div class="molongui-metabox">

    <ul class="m-tip"><li><?php _e( "Only filled in social profile links will be displayed on the frontend as icons. If you are missing some networks you would like to configure, enable them at the plugin settings page: Molongui > Authorship Settings > Main tab > Social Networks section.", 'molongui-authorship' ); ?></li></ul>
    <?php if ( !MOLONGUI_AUTHORSHIP_IS_PRO ) : ?><ul class="m-tip m-premium"><li><?php printf( __( "Disabled options are only available in the %sPremium version%s of the plugin.", 'molongui-authorship' ), '<a href="'.MOLONGUI_AUTHORSHIP_WEB.'">', '</a>' ); ?></li></ul><?php endif; ?>

    <?php foreach ( $networks as $id => $network ) : ?>

        <?php $network['value'] = get_post_meta( $post->ID, '_molongui_guest_author_'.$id, true ); ?>

        <div class="m-field <?php echo ( ( !MOLONGUI_AUTHORSHIP_IS_PRO and $network['premium'] ) ? 'm-premium' : '' ) ?>">
            <label class="m-title" for="_molongui_guest_author_<?php echo $id; ?>"><i class="m-a-icon-<?php echo $id; ?>"></i><?php echo $network['name']; ?></label>
            <?php if ( !MOLONGUI_AUTHORSHIP_IS_PRO and $network['premium'] ) : ?>
                <div class="input-wrap"><input type="text" disabled placeholder="<?php printf( __( '%s  Premium feature', 'molongui-authorship' ), '&#xf002;' ); ?>" data-tip="<?php echo molongui_premium_tip(); ?>" id="_molongui_guest_author_<?php echo $id; ?>" name="_molongui_guest_author_<?php echo $id; ?>" value="" class="text m-tiptip"></div>
            <?php else : ?>
                <div class="input-wrap"><input type="text" placeholder="<?php echo $network['url']; ?>" id="_molongui_guest_author_<?php echo $id; ?>" name="_molongui_guest_author_<?php echo $id; ?>" value="<?php echo ( $network['value'] ? $network['value'] : '' ); ?>" class="text"></div>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

</div>