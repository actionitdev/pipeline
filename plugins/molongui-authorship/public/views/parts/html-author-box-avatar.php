<?php
?>

<?php if ( $author['img'] and ( !empty( $settings['show_avatar'] ) and $settings['show_avatar'] ) ) : ?>
	<div class="m-a-box-item m-a-box-avatar <?php echo ( ( !empty( $settings['profile_valign'] ) and $settings['profile_valign'] != 'center' ) ? 'molongui-align-self-'.$settings['profile_valign'] : '' ); ?>">
		<?php
            if ( !$settings['avatar_link_to_archive'] or
                 ( $author['type'] === 'guest' and !MOLONGUI_AUTHORSHIP_IS_PRO ) or
                 ( $author['type'] === 'guest' and !$settings['guest_archive_enabled'] ) or
                 ( $author['type'] === 'user'  and !$settings['user_archive_enabled'] ) )
            {
	            ?>
                <span>
                    <?php echo $author['img']; ?>
                </span>
                <?php
            }
            elseif ( is_customize_preview() and
	                 ( ( $author['type'] === 'guest' and $settings['guest_archive_enabled'] and MOLONGUI_AUTHORSHIP_IS_PRO ) or
	                   ( $author['type'] === 'user'  and $settings['user_archive_enabled'] ) ) )
            {
                ?>
                <a href="#" class="customize-unpreviewable">
                    <?php echo $author['img']; ?>
                </a>
                <?php
            }
            else
            {
                ?>
                <a href="<?php echo esc_url( $author['archive'] ); ?>">
                    <?php echo $author['img']; ?>
                </a>
                <?php
            }
        ?>
	</div>
<?php endif; ?>