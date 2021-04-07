<?php
defined( 'ABSPATH' ) or exit;

?>

<div id="molongui-author-tools">

    <h3><?php _e( 'Author Tools', 'molongui-authorship' ); ?></h3>
    <ul class="m-tip m-premium"><li><?php printf( __( "Does this user need an account on your site? Does it need access to your Dashboard? Convert it to a guest author with just 1-click. It will be removed and a new guest author created. Posts authorship will be kept. %sUpgrade to PRO%s to unlock this feature.", 'molongui-authorship' ), '<a href="'.MOLONGUI_AUTHORSHIP_WEB.'">', '</a>' ); ?></li></ul>

    <table class="form-table" role="presentation">
        <tbody>

        <!-- Convert User to Guest -->
        <tr class="user-m-convert-to-guest-wrap">
            <th scope="row"><label><?php _e( 'Convert to Guest', 'molongui-authorship' ); ?></label></th>
            <td>
                <a class="button button-large button-disabled"><?php _e( "Convert", 'molongui-authorship' ); ?></a>
            </td>
        </tr>

        </tbody>
    </table>

</div>