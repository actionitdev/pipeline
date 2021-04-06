<?php

namespace Molongui\Authorship\Includes;

use Molongui\Authorship\Fw\Includes\Loader;
use Molongui\Authorship\Includes\Author;
defined( 'ABSPATH' ) or exit;
class Guest
{
    private $loader;
    public $cpt_name;
    public function __construct()
    {
        $this->init();
        $this->loader->add_action( 'init', $this, 'register_custom_post_type' );
        if ( molongui_is_request( 'admin' ) )
        {
            $this->loader->add_filter( 'post_updated_messages', $this, 'post_updated_messages' );
            $this->loader->add_action( 'save_post_'.$this->cpt_name, $this, 'save' );
            $this->loader->add_action( 'trashed_post', $this, 'trash' );
            $this->loader->add_action( 'untrashed_post', $this, 'untrash' );
            $this->loader->add_action( 'delete_post', $this, 'delete' );
            $this->loader->add_action( 'deleted_post', $this, 'deleted', 10, 2 );
            $this->loader->add_action( 'admin_notices', $this, 'admin_notices' );
            $this->loader->add_filter( 'removable_query_args', $this, 'add_removable_arg' );
            $this->loader->add_action( 'edit_form_after_title', $this, 'add_top_section_after_title' );
            $this->loader->add_action( 'add_meta_boxes', $this, 'add_meta_boxes' );
            $this->loader->add_filter( 'postbox_classes_'.$this->cpt_name.'_authorconversiondiv', $this, 'add_conversion_metabox_class' );
            $this->loader->add_filter( 'postbox_classes_'.$this->cpt_name.'_authorshortbiodiv', $this, 'add_short_bio_metabox_class' );
            $this->loader->add_action( 'admin_head', $this, 'remove_media_buttons' );
            $this->loader->add_action( 'admin_head', $this, 'remove_preview_button' );
            $this->loader->add_action( 'admin_menu', $this, 'custom_remove_menu_pages' );
            $this->loader->add_filter( 'wp_insert_post_data', $this, 'filter_cpt_title', 99, 2 );
            $this->loader->add_filter( 'manage_'.$this->cpt_name.'_posts_columns', $this, 'add_list_columns' );
            $this->loader->add_action( 'manage_'.$this->cpt_name.'_posts_custom_column', $this, 'fill_list_columns', 5, 2 );
            $this->loader->add_filter( 'post_row_actions', $this, 'remove_view_link', 10, 1 );
            $this->loader->add_filter( 'bulk_actions-'.'edit-'.$this->cpt_name, $this, 'remove_bulk_edit_action' );
            $this->loader->add_action( 'admin_head', $this, 'quick_edit_add_guest_title_field' );
            $this->loader->add_action( 'quick_edit_custom_box', $this, 'quick_edit_add_guest_custom_fields', 10, 2 );
            $this->loader->add_action( 'admin_footer', $this, 'quick_edit_populate_guest_custom_fields' );
        }
        elseif ( molongui_is_request( 'ajax' ) )
        {
            $this->loader->add_filter( 'manage_'.$this->cpt_name.'_posts_columns', $this, 'add_list_columns' );
            $this->loader->add_action( 'manage_'.$this->cpt_name.'_posts_custom_column', $this, 'fill_list_columns', 5, 2 );
            $this->loader->add_action( 'save_post_'.$this->cpt_name, $this, 'quick_edit_save_guest_custom_fields', 10, 2 );
            $this->loader->add_action( 'wp_ajax_quick_add_guest_author', $this, 'quick_add' );
        }
    }
    private function init()
    {
        $this->loader = Loader::get_instance();
        $config = require MOLONGUI_AUTHORSHIP_DIR . 'config/config.php';
        $this->cpt_name = $config['cpt']['guest'];
    }
    public function register_custom_post_type()
    {
        $settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
        $labels = array
        (
            'name'					=> _x( 'Guest Authors', 'post type general name', 'molongui-authorship' ),
            'singular_name'			=> _x( 'Guest Author', 'post type singular name', 'molongui-authorship' ),
            'menu_name'				=> __( 'Guest Authors', 'molongui-authorship' ),
            'name_admin_bar'		=> __( 'Guest Author', 'molongui-authorship' ),
            'all_items'				=> ( ( !empty( $settings['guest_menu_item_level'] ) and $settings['guest_menu_item_level'] != 'top' ) ? __( 'Guest authors', 'molongui-authorship' ) : __( 'All Guest authors', 'molongui-authorship' ) ),
            'add_new'				=> _x( 'Add New', $this->cpt_name, 'molongui-authorship' ),
            'add_new_item'			=> __( 'Add New Guest Author', 'molongui-authorship' ),
            'edit_item'				=> __( 'Edit Guest Author', 'molongui-authorship' ),
            'new_item'				=> __( 'New Guest Author', 'molongui-authorship' ),
            'view_item'				=> __( 'View Guest Author', 'molongui-authorship' ),
            'search_items'			=> __( 'Search Guest Authors', 'molongui-authorship' ),
            'not_found'				=> __( 'No Guest Authors Found', 'molongui-authorship' ),
            'not_found_in_trash'	=> __( 'No Guest Authors Found in the Trash', 'molongui-authorship' ),
            'parent_item_colon'		=> '',
            'featured_image'        => __( 'Profile Image', 'molongui-authorship' ),
            'set_featured_image'    => __( 'Set Profile Image', 'molongui-authorship' ),
            'remove_featured_image' => __( 'Remove Profile Pmage', 'molongui-authorship' ),
            'use_featured_image'    => __( 'Use as Profile Pmage', 'molongui-authorship' ),
        );

        $args = array
        (
            'labels'				=> $labels,
            'description'			=> 'Guest author custom post type by Molongui',
            'public'				=> false,
            'exclude_from_search'	=> apply_filters( 'authorship/guest/search', true ),//true,
            'publicly_queryable'	=> true, // false => not being able to edit slug from the Quick Editor.
            'show_ui'				=> true,
            'show_in_menu'          => ( ( !empty( $settings['guest_menu_item_level'] ) and $settings['guest_menu_item_level'] != 'top' ) ? $settings['guest_menu_item_level'] : true ),
            'show_in_nav_menus'		=> true,
            'show_in_admin_bar '	=> true,
            'menu_position'			=> 5, // 5 = Below posts
            'menu_icon'				=> 'dashicons-id',
            'supports'		 		=> molongui_authorship_is_feature_enabled( 'avatar' ) ? array( 'thumbnail' ) : array( '' ), // The editor to input guest author bio is added into a metabox.
            'register_meta_box_cb'	=> '',
            'has_archive'			=> true,
            'rewrite'				=> array( 'slug' => 'guest-author' ),
            'can_export'            => MOLONGUI_AUTHORSHIP_IS_PRO,
            'capability_type'       => 'post',  // https://codex.wordpress.org/Function_Reference/register_post_type#capability_type
            'map_meta_cap'          => true,    // https://codex.wordpress.org/Function_Reference/register_post_type#map_meta_cap
        );
        register_post_type( $this->cpt_name, $args );
    }
    public function post_updated_messages( $msg )
    {
        $msg[$this->cpt_name] = array
        (
            0  => '',                                       // Unused. Messages start at index 1.
            1  => "Guest author updated.",
            2  => "Custom field updated.",                  // Probably better do not touch
            3  => "Custom field deleted.",                  // Probably better do not touch
            4  => "Guest author updated.",
            5  => "Guest author restored to revision",
            6  => "Guest author published.",
            7  => "Guest author saved.",
            8  => "Guest author submitted.",
            9  => "Guest author scheduled.",
            10 => "Guest author draft updated.",
        );

        return $msg;
    }
    public function custom_remove_menu_pages()
    {
        $settings = get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );

        $slug = 'edit.php?post_type='.$this->cpt_name;

        if ( !current_user_can( 'edit_others_pages' ) and !current_user_can( 'edit_others_posts' ) )
        {
            if ( isset( $settings['guest_menu_item_level'] ) and $settings['guest_menu_item_level'] != 'top' )
            {
                if ( $settings['guest_menu_item_level'] == 'users.php' ) $settings['guest_menu_item_level'] = 'profile.php';

                remove_submenu_page( $settings['guest_menu_item_level'], $slug );
            }
            else
            {
                remove_menu_page( $slug );
            }
        }
    }
    public function add_list_columns( $columns )
    {
        unset( $columns['title'] );
        unset( $columns['date'] );
        unset( $columns['thumbnail'] );
        $new_cols = array
        (
            'guestAuthorPic'     => __( 'Photo', 'molongui-authorship' ),
            'title'		         => __( 'Name', 'molongui-authorship' ),
            'guestDisplayBox'    => __( 'Box', 'molongui-authorship' ),
            'guestAuthorBio'     => __( 'Bio', 'molongui-authorship' ),
            'guestAuthorMail'    => __( 'Email', 'molongui-authorship' ),
            'guestAuthorPhone'   => __( 'Phone', 'molongui-authorship' ),
            'guestAuthorUrl'     => __( 'URL', 'molongui-authorship' ),
            'guestAuthorJob'     => __( 'Job', 'molongui-authorship' ),
            'guestAuthorCia'     => __( 'Co.', 'molongui-authorship' ),
            'guestAuthorCiaUrl'  => __( 'Co. URL', 'molongui-authorship' ),
            'guestAuthorSocial'  => __( 'Social', 'molongui-authorship' ),
            'guestAuthorEntries' => __( 'Entries', 'molongui-authorship' ),
            'guestAuthorId'      => __( 'ID', 'molongui-authorship' ),
        );
        if ( !molongui_authorship_is_feature_enabled( 'box' ) )
        {
            unset( $new_cols['guestDisplayBox'] );
        }
        if ( 'trash' == get_query_var( 'post_status' ) ) unset( $new_cols['guestAuthorEntries'] );
        return array_merge( $columns, $new_cols );
    }
    public function fill_list_columns( $column, $ID )
    {
        $value = '';
        $author = new Author ( $ID, 'guest' );
        if ( $column == 'guestAuthorPic' )
        {
            echo get_the_post_thumbnail( $ID, array( 60, 60 ) );
            return;
        }
        elseif ( $column == 'guestDisplayBox' )
        {
            $settings = get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );

            $value = $author->get_meta( 'box_display' );
            $icon  = ( empty( $value ) or $value == 'default' ) ? $settings['display'] : $value;
            echo '<div id="box_display_'.$ID.'" data-display-box="'.$value.'">'.'<i data-tip="'.( $icon == 'show' ? __( 'Show', 'molongui-authorship' ) : __( 'Hide', 'molongui-authorship' ) ).'" class="m-a-icon-'.$icon.' molongui-tip"></i>'.'</div>';
            return;
        }
        elseif ( $column == 'guestAuthorEntries' )
        {
            $html   = '';
            $values = $author->get_post_count();
            foreach ( molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all', true ) as $post_type )
            {
                if ( isset( $values[$post_type['id']] ) and $values[$post_type['id']] > 0 ) $html .= '<div>'.$values[$post_type['id']].' '.$post_type['label'].'</div>';
            }
            if ( !$html ) $html = __( 'None' );

            echo $html;
            return;
        }
        elseif ( $column == 'guestAuthorSocial' )
        {
            $networks = molongui_authorship_get_social_networks( 'enabled' );
            foreach ( $networks as $name => $network )
            {
                if ( $sn = $author->get_meta( $name ) )
                {
                    echo '<a href="'.esc_url( $sn ).'" target="_blank">';
                    echo '<i data-tip="'.esc_url( $sn ).'" class="m-a-icon-'.$name.' molongui-tip"></i>';
                    echo '</a>';
                }
            }
            return;
        }
        elseif ( $column == 'guestAuthorId' )
        {
            echo $ID;
            return;
        }
        elseif ( $column == 'guestAuthorBio'   ) $value = $author->get_bio();
        elseif ( $column == 'guestAuthorMail'  ) $value = $author->get_meta( 'mail' );
        elseif ( $column == 'guestAuthorPhone' ) $value = $author->get_meta( 'phone' );
        elseif ( $column == 'guestAuthorJob'   ) $value = $author->get_meta( 'job' );
        elseif ( $column == 'guestAuthorCia'   ) $value = $author->get_meta( 'company' );

        if ( !empty( $value ) )
        {
            echo '<i data-tip="'.esc_html( $value ).'" class="m-a-icon-ok molongui-tip"></i>';
            return;
        }
        elseif ( $column == 'guestAuthorUrl'    ) $value = $author->get_meta( 'web' );
        elseif ( $column == 'guestAuthorCiaUrl' ) $value = $author->get_meta( 'company_link' );

        if ( !empty( $value ) )
        {
            echo '<a href="'.esc_url( $value ).'" target="_blank">';
            echo '<i data-tip="'.esc_url( $value ).'" class="m-a-icon-ok molongui-tip"></i>';
            echo '</a>';
            return;
        }
        else
        {
            echo '-';//'<i data-tip="'.esc_url( $value ).'" class="m-a-icon-minus molongui-tip"></i>';
            return;
        }
    }
    public function remove_view_link( $actions )
    {
        if ( !apply_filters( 'authorship/admin/row_actions/remove_view_link', true ) ) return $actions;

        if ( $this->cpt_name == get_post_type() ) unset( $actions['view'] );

        return $actions;
    }
    public function quick_edit_add_guest_title_field()
    {
        global $pagenow, $post_type;

        if ( 'edit.php' == $pagenow and $post_type == $this->cpt_name ) add_post_type_support( $post_type, 'title' );
    }
    public function	quick_edit_add_guest_custom_fields( $column_name, $post_type )
    {
        if ( $column_name != 'guestDisplayBox' ) return;

        wp_nonce_field( 'quick_edit_guest', 'quick_edit_guest_nonce' );

        ?>
        <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
                <div class="inline-edit-group wp-clearfix">
                    <label class="inline-edit-status alignleft">
                        <span class="title"><?php esc_html_e( 'Author Box', 'molongui-authorship' ); ?></span>
                        <select name="_molongui_guest_author_box_display">
                            <option value="default" ><?php _e( 'Default', 'molongui-authorship' ); ?></option>
                            <option value="show"    ><?php _e( 'Show', 'molongui-authorship' ); ?></option>
                            <option value="hide"    ><?php _e( 'Hide', 'molongui-authorship' ); ?></option>
                        </select>
                    </label>
                </div>
            </div>
        </fieldset>
        <?php
    }
    public function quick_edit_populate_guest_custom_fields()
    {
        $current_screen = get_current_screen();
        if ( $current_screen->id != 'edit-'.$this->cpt_name or $current_screen->post_type != $this->cpt_name ) return;
        wp_enqueue_script( 'jquery' );
        ?>
        <script type="text/javascript">
            jQuery( function( $ )
            {
                var $inline_editor = inlineEditPost.edit;
                inlineEditPost.edit = function(id)
                {
                    $inline_editor.apply( this, arguments);
                    var post_id = 0;
                    if ( typeof(id) == 'object' )
                    {
                        post_id = parseInt(this.getId(id));
                    }
                    if ( post_id != 0 )
                    {
                        $row = $('#edit-' + post_id);
                        $box_display = $('#box_display_' + post_id).data( 'display-box' );
                        if ( $box_display === '' )
                        {
                            $box_display = 'default';
                        }
                        $row.find('[name="_molongui_guest_author_box_display"]').val($box_display);
                        $row.find('[name="_molongui_guest_author_box_display"]').children('[value="' + $box_display + '"]').attr('selected', true);
                    }
                }
            });
        </script>
        <?php
    }
    public function quick_edit_save_guest_custom_fields( $post_id, $post )
    {
        if ( !isset( $_POST['quick_edit_guest_nonce'] ) or !wp_verify_nonce( $_POST['quick_edit_guest_nonce'], 'quick_edit_guest' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) return;
        if ( wp_is_post_revision( $post_id ) ) return;
        if ( !current_user_can( 'edit_post', $post_id ) ) return;
        if ( isset( $_POST['post_title'] ) ) update_post_meta( $post_id, '_molongui_guest_author_display_name', $_POST['post_title'] );
        if ( isset( $_POST['_molongui_guest_author_box_display'] ) ) update_post_meta( $post_id, '_molongui_guest_author_box_display', $_POST['_molongui_guest_author_box_display'] );
    }
    public function remove_bulk_edit_action( $actions )
    {
        if ( !apply_filters( 'authorship/admin/bulk_actions/remove_bulk_edit', true ) ) return $actions;

        unset( $actions['edit'] );
        return $actions;
    }
    public function remove_media_buttons()
    {
        global $current_screen;

        if ( $this->cpt_name == $current_screen->post_type ) remove_action( 'media_buttons', 'media_buttons' );
    }
    public function remove_preview_button()
    {
        $current_screen = get_current_screen();
        if ( $current_screen->post_type != $this->cpt_name ) return;
        if ( apply_filters( 'authorship/admin/guest/show_preview_button', false, $current_screen ) ) return;
        echo '<style>#post-preview{ display:none !important; }</style>';
    }
    public function add_top_section_after_title()
    {
        global $post;
        if ( $post->post_type !== $this->cpt_name ) return;
        do_meta_boxes( get_current_screen(), 'top', $post );
    }
    public function add_meta_boxes( $post_type )
    {
        if ( !current_user_can( 'edit_others_pages' ) and !current_user_can( 'edit_others_posts' ) ) return;
        if ( in_array( $post_type, array( $this->cpt_name ) ) )
        {
            add_meta_box(
                'authorprofilediv'
                ,__( 'Profile', 'molongui-authorship' )
                ,array( $this, 'render_profile_metabox' )
                ,$post_type
                ,'top'
                ,'high'
            );
            add_meta_box(
                'authorbiodiv'
                ,__( 'Biography', 'molongui-authorship' )
                ,array( $this, 'render_bio_metabox' )
                ,$post_type
                ,'top'
                ,'high'
            );
            if ( apply_filters( 'authorship/admin/guest/shortbio/metabox', '__return_true' ) )
            {
                add_meta_box(
                    'authorshortbiodiv'
                    ,__( 'Short Biography', 'molongui-authorship' )
                    ,array( $this, 'render_short_bio_metabox' )
                    ,$post_type
                    ,'top'
                    ,'default'
                );
            }
            add_meta_box(
                'authorsocialdiv'
                ,__( 'Social Media', 'molongui-authorship' )
                ,array( $this, 'render_social_metabox' )
                ,$post_type
                ,'normal'
                ,'high'
            );
            if ( molongui_authorship_is_feature_enabled( 'box' ) )
            {
                add_meta_box(
                    'authorboxdiv'
                    ,__( 'Author Box', 'molongui-authorship' )
                    ,array( $this, 'render_box_metabox' )
                    ,$post_type
                    ,'side'
                    ,'high'
                );
            }
            if ( !molongui_authorship_is_feature_enabled( 'avatar' ) )
            {
                add_meta_box(
                    'authoravatardiv'
                    ,__( 'Profile Image', 'molongui-authorship' )
                    ,array( $this, 'render_avatar_metabox' )
                    ,$post_type
                    ,'side'
                    ,'low'
                );
            }
            if ( apply_filters( 'authorship/admin/guest/convert/metabox', '__return_true' ) )
            {
                add_meta_box(
                    'authorconversiondiv'
                    ,__( 'Role' )
                    ,array( $this, 'render_conversion_metabox' )
                    ,$post_type
                    ,'side'
                    ,'low'
                );
            }
            do_action( 'authorship/admin/guest/metaboxes', $post_type );
        }
    }
    public function render_profile_metabox( $post )
    {
        wp_nonce_field( 'molongui_authorship_guest', 'molongui_authorship_guest_nonce' );
        $guest_author_first_name   = get_post_meta( $post->ID, '_molongui_guest_author_first_name', true );
        $guest_author_last_name    = get_post_meta( $post->ID, '_molongui_guest_author_last_name', true );
        $guest_author_display_name = get_post_meta( $post->ID, '_molongui_guest_author_display_name', true ); //get_the_title( $post->ID );
        $guest_author_mail         = get_post_meta( $post->ID, '_molongui_guest_author_mail', true );
        $guest_author_phone        = get_post_meta( $post->ID, '_molongui_guest_author_phone', true );
        $guest_author_web          = get_post_meta( $post->ID, '_molongui_guest_author_web', true );
        $guest_author_job          = get_post_meta( $post->ID, '_molongui_guest_author_job', true );
        $guest_author_company      = get_post_meta( $post->ID, '_molongui_guest_author_company', true );
        $guest_author_company_link = get_post_meta( $post->ID, '_molongui_guest_author_company_link', true );
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/guest/html-profile-metabox.php';
    }
    public function render_bio_metabox( $post )
    {
        $guest_author_bio = get_post_field( 'post_content', $post->ID );
        wp_editor( $guest_author_bio, 'content', array( 'media_buttons' => false, /*'editor_height' => 100,*/ 'textarea_rows' => 10, 'editor_css' => '<style>#wp-content-editor-tools{background:none;padding-top:0;}</style>' ) );
    }
    public function render_short_bio_metabox( $post )
    {
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/guest/html-short-bio-metabox.php';
    }
    public function render_social_metabox( $post )
    {
        $networks = molongui_authorship_get_social_networks( 'enabled' );
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/guest/html-social-metabox.php';
    }
    public function render_box_metabox( $post )
    {
        $guest_author_hide_box   = get_post_meta( $post->ID, '_molongui_guest_author_box_display', true );
        $guest_author_mail_icon  = get_post_meta( $post->ID, '_molongui_guest_author_show_icon_mail', true );
        $guest_author_phone_icon = get_post_meta( $post->ID, '_molongui_guest_author_show_icon_phone', true );
        $guest_author_web_icon   = get_post_meta( $post->ID, '_molongui_guest_author_show_icon_web', true );
        $guest_author_mail_meta  = get_post_meta( $post->ID, '_molongui_guest_author_show_meta_mail', true );
        $guest_author_phone_meta = get_post_meta( $post->ID, '_molongui_guest_author_show_meta_phone', true );
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/guest/html-box-metabox.php';
    }
    public function render_avatar_metabox( $post )
    {
        $options = get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );

        $customizer_link = molongui_authorship_get_customizer();
        $settings_link   = admin_url( 'admin.php?page=molongui-authorship&tab=molongui_authorship_main' );
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/guest/html-avatar-metabox.php';
    }
    public function render_conversion_metabox( $post )
    {
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/guest/html-convert-metabox.php';
    }
    public function add_conversion_metabox_class( $classes )
    {
        if ( apply_filters( 'authorship/admin/guest/convert/metabox', '__return_true' ) ) array_push( $classes, 'free' );
        return $classes;
    }
    public function add_short_bio_metabox_class( $classes )
    {
        if ( apply_filters( 'authorship/admin/guest/shortbio/metabox', '__return_true' ) ) array_push( $classes, 'free' );
        return $classes;
    }
    public function filter_cpt_title( $data , $postarr )
    {
        if ( $data['post_type'] != $this->cpt_name ) return $data;
        if ( $postarr['ID'] == null or empty( $_POST ) ) return $data;
        if ( !isset( $_POST['molongui_authorship_guest_nonce'] ) or !wp_verify_nonce( $_POST['molongui_authorship_guest_nonce'], 'molongui_authorship_guest' ) ) return $data;
        $first_name   = !empty( $_POST['_molongui_guest_author_first_name'] )   ? $_POST['_molongui_guest_author_first_name']   : '';
        $last_name    = !empty( $_POST['_molongui_guest_author_last_name'] )    ? $_POST['_molongui_guest_author_last_name']    : '';
        $display_name = !empty( $_POST['_molongui_guest_author_display_name'] ) ? $_POST['_molongui_guest_author_display_name'] : '';
        if ( $display_name ) $post_title = sanitize_text_field( $_POST['_molongui_guest_author_display_name'] );
        elseif (  $first_name and  $last_name ) $post_title = sanitize_text_field( $_POST['_molongui_guest_author_first_name'] ) . ' ' . sanitize_text_field( $_POST['_molongui_guest_author_last_name'] );
        elseif (  $first_name and !$last_name ) $post_title = sanitize_text_field( $_POST['_molongui_guest_author_first_name'] );
        elseif ( !$first_name and  $last_name ) $post_title = sanitize_text_field( $_POST['_molongui_guest_author_last_name'] );

        if ( !empty( $post_title ) ) $data['post_title'] = $post_title;
        $data['post_name'] = '';
        return $data;
    }
    private function clear_object_cache()
    {
        \molongui_clear_object_cache( 'guests' );
        \molongui_clear_object_cache( 'posts'  );
    }
    public function save( $post_id )
    {
        if ( !isset( $_POST['molongui_authorship_guest_nonce'] ) or !wp_verify_nonce( $_POST['molongui_authorship_guest_nonce'], 'molongui_authorship_guest' ) ) return $post_id;
        if ( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) return $post_id;
        if ( wp_is_post_revision( $post_id ) ) return $post_id;
        if ( 'page' == $_POST['post_type'] ) if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
        elseif ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
        $networks = molongui_authorship_get_social_networks( 'enabled' );
        $inputs = array
        (
            '_molongui_guest_author_first_name',
            '_molongui_guest_author_last_name',
            '_molongui_guest_author_display_name',
            '_molongui_guest_author_mail',
            '_molongui_guest_author_phone',
            '_molongui_guest_author_web',
            '_molongui_guest_author_job',
            '_molongui_guest_author_company',
            '_molongui_guest_author_company_link',
        );
        foreach ( $inputs as $input )
        {
            if ( !empty( $_POST[$input] ) ) update_post_meta( $post_id, $input, sanitize_text_field( $_POST[$input] ) );
            else delete_post_meta( $post_id, $input );
        }
        foreach ( $networks as $id => $network )
        {
            if ( !empty( $_POST['_molongui_guest_author_'.$id] ) ) update_post_meta( $post_id, '_molongui_guest_author_'.$id, sanitize_text_field( $_POST['_molongui_guest_author_'.$id] ) );
            else delete_post_meta( $post_id, '_molongui_guest_author_'.$id );
        }
        $checkboxes = array
        (
            '_molongui_guest_author_show_meta_mail',
            '_molongui_guest_author_show_meta_phone',
            '_molongui_guest_author_show_icon_mail',
            '_molongui_guest_author_show_icon_web',
            '_molongui_guest_author_show_icon_phone',
        );
        foreach ( $checkboxes as $checkbox )
        {
            if ( isset( $_POST[$checkbox] ) ) update_post_meta( $post_id, $checkbox, sanitize_text_field( $_POST[$checkbox] ) );
            else delete_post_meta( $post_id, $checkbox );
        }
        update_post_meta( $post_id, '_molongui_guest_author_box_display', 'default' );
        $this->clear_object_cache();
        add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99, 2 );
        do_action( 'authorship/admin/guest/save', $post_id, $_POST );
    }
    public function add_notice_query_var( $location, $post_id )
    {
        remove_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );

        $name_exists = authorship_author_name_exists( $post_id, 'guest' );
        if ( $name_exists )
        {
            switch ( $name_exists )
            {
                case 'user' : return add_query_arg( array( 'authorship_guest_save' => 'user_alert'  ), $location ); break;
                case 'guest': return add_query_arg( array( 'authorship_guest_save' => 'guest_alert' ), $location ); break;
                case 'both' : return add_query_arg( array( 'authorship_guest_save' => 'both_alert'  ), $location ); break;
            }
        }

        return $location;
    }
    public function add_removable_arg( $args )
    {
        array_push( $args, 'authorship_guest_save' );
        return $args;
    }
    public function admin_notices()
    {
        if ( !isset( $_GET['authorship_guest_save'] ) ) return;

        switch ( $_GET['authorship_guest_save'] )
        {
            case 'user_alert':
                $message = esc_html__( 'There is a registered WordPress user with the same display name. You might want to address that.', 'molongui-authorship' );
            break;

            case 'guest_alert':
                $message = esc_html__( 'There is another guest author with the same display name. You might want to address that.', 'molongui-authorship' );
            break;

            case 'both_alert':
                $message = esc_html__( 'There is a registered WordPress user and another guest author with the same display name. You might want to address that.', 'molongui-authorship' );
            break;

            default:
                $message = '';
            break;
        }

        if ( empty( $message ) ) return;
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php echo $message; ?></p>
        </div>
        <?php
    }
    public function trash( $guest_id )
    {
        $this->clear_object_cache();
    }
    public function untrash( $guest_id )
    {
        $this->clear_object_cache();
    }
    public function delete( $guest_id )
    {
        $author      = new Author( $guest_id, 'guest' );
        $guest_posts = $author->get_posts( array( 'fields' => 'ids', 'post_type' => 'all' ) );

        \add_filter( 'authorship/admin/guest/delete', function() use ( $guest_posts ) { return $guest_posts; } );
    }
    public function deleted( $guest_id, $guest = null )
    {
        $post_ids = \apply_filters( 'authorship/admin/guest/delete', array() );
        if ( !empty( $post_ids ) )
        {
            foreach ( $post_ids as $post_id )
            {
                \delete_post_meta( $post_id, '_molongui_author', 'guest-'.$guest_id );
                if ( \get_post_meta( $post_id, '_molongui_main_author', true ) === 'guest-'.$guest_id )
                {
                    $post_authors = \get_post_meta( $post_id, '_molongui_author', false );
                    if ( empty( $post_authors ) )
                    {
                        $post_author = \get_post_field ('post_author', $post_id );
                        \update_post_meta( $post_id, '_molongui_main_author', 'user-'.$post_author, 'guest-'.$guest_id );
                        \update_post_meta( $post_id, '_molongui_author', 'user-'.$post_author );
                    }
                    else
                    {
                        \update_post_meta( $post_id, '_molongui_main_author', $post_authors[0], 'guest-'.$guest_id );
                        if ( \strpos( $post_authors[0], 'user-' ) !== false )
                        {
                            $id = \str_replace ( 'user-', '', $post_authors[0] );
                            \wp_update_post( array( 'ID' => $post_id, 'post_author' => $id ) );
                        }
                    }
                }
            }
        }
        $this->clear_object_cache();
        \do_action( 'authorship/admin/guest/deleted', $guest_id, $guest );
    }
    public function quick_add()
    {
        if ( !wp_verify_nonce( $_POST['nonce'], 'molongui_authorship_quick_add_nonce' ) ) die();
        if ( empty( $_POST['display_name'] ) )
        {
            echo json_encode( array( 'error' => __( "No display name provided", 'molongui-authorship' ) ) );
            die();
        }
        $postarr = array
        (
            'post_type'      => 'guest_author',
            'post_name'      => $_POST['display_name'],
            'post_title'     => $_POST['display_name'],
            'post_excerpt'   => '',
            'post_content'   => '',
            'thumbnail'      => '',
            'meta_input'     => array
            (
                '_molongui_guest_author_display_name' => $_POST['display_name'],
            ),
            'post_status'    => 'publish',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_author'    => get_current_user_id(),
        );
        $guest_id = wp_insert_post( $postarr, true );

        if ( is_wp_error( $guest_id ) )
        {
            echo json_encode( array( 'error' => $guest_id->get_error_message() ) );
        }
        else
        {
            $this->clear_object_cache();

            echo json_encode( array( 'guest_id' => $guest_id, 'guest_ref' => 'guest-'.$guest_id, 'guest_name' => $_POST['display_name'] ) );
        }

        die();
    }

} // !class Guest