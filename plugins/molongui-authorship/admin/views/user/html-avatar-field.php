<?php
defined( 'ABSPATH' ) or exit;
$img_id       = get_the_author_meta( 'molongui_author_image_id',   $user->ID );
$img_url      = get_the_author_meta( 'molongui_author_image_url',  $user->ID );
$img_edit_url = get_the_author_meta( 'molongui_author_image_edit', $user->ID );
if ( molongui_authorship_is_feature_enabled( 'user_profile' ) )
{
    $gravatar_img = get_avatar( $user->ID, '150', '', '', array( 'class' => 'molongui_current_img', 'extra_attr' => 'id="m-default-gravatar"' ) );
    $gravatar_url = get_avatar_url( $user->ID, array( 'size' => '150' ) );
}
else
{
    $gravatar_url = 'http://placehold.jp/cccccc/666666/150x150.png?text=Upload%20custom%20image';
    $gravatar_img = '<img id="molongui_no_avatar" src="'.$gravatar_url.'">';
}

if ( current_user_can( 'upload_files' ) ) :
    wp_enqueue_media();
    ?>

    <!-- Outputs the image after save -->
    <div id="current_img">
        <?php if ( $img_url ) : ?>
            <img src="<?php echo esc_url( $img_url ); ?>" class="molongui_current_img">
        <?php else : ?>
            <?php echo $gravatar_img; ?>
        <?php endif; ?>
        <div class="edit_options <?php echo ( $img_url ? 'uploaded' : '' ); ?>">
            <a class="remove_img"><span><?php _e( 'Remove', 'molongui-authorship' ); ?></span></a>
            <a href="<?php echo $img_edit_url; ?>" class="edit_img" target="_blank"><span><?php _e( 'Edit', 'molongui-authorship' ); ?></span></a>
        </div>
    </div>

    <!-- Hold the value here of WPMU image -->
    <div id="molongui_image_upload">
        <?php
        ?>
        <input type="hidden" name="molongui_author_image_id"   id="molongui_author_image_id"   value="<?php echo $img_id; ?>" class="hidden" />
        <input type="hidden" name="molongui_author_image_url"  id="molongui_author_image_url"  value="<?php echo esc_url_raw( $img_url ); ?>" class="hidden" />
        <input type="hidden" name="molongui_author_image_edit" id="molongui_author_image_edit" value="<?php echo $img_edit_url; ?>" class="hidden" />
        <input type='button' class="molongui_wpmu_button button-primary" id="molongui_uploadimage" value="<?php echo ( $img_url ? __( 'Change Image', 'molongui-authorship' ) : __( 'Upload Image', 'molongui-authorship' ) ); ?>" /><br />
    </div>

<?php else : ?>

    <?php if ( $img_url ): ?>
        <img src="<?php echo esc_url( $img_url ); ?>" class="molongui_current_img">
    <?php else : ?>
        <?php echo $gravatar_img; ?>
    <?php endif; ?>
    <div>
        <p class="description"><?php _e( 'You do not have permission to upload a custom profile picture. Please, contact the administrator of this site.', 'molongui-authorship' ); ?></p>
    </div>

<?php endif; ?>