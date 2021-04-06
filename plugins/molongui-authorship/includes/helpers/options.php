<?php
defined( 'ABSPATH' ) or exit;
if ( !function_exists( 'molongui_authorship_get_default_settings' ) )
{
    function molongui_authorship_get_default_settings()
    {
        return array
        (
            'main' => array
            (
                'enable_multi_authors'    => true,
                'enable_guest_authors'    => true,
                'enable_author_boxes'     => true,
                'enable_local_avatars'    => true,
                'enable_user_profiles'    => true,
                'enable_search_by_author' => false,
                'enable_guests_in_search' => false,
                'post_types' => "post", // Data stored as a string with comma-separated items. No array!
                'user_roles' => "administrator,editor,author,contributor", // Data stored as a string with comma-separated items. No array!
                'social_networks' => "facebook,twitter,youtube,pinterest,tumblr,instagram,soundcloud,spotify,skype,medium,whatsapp", // Data stored as a string with comma-separated items. No array!
                'encode_email' => false,
                'encode_phone' => false,
                'guest_menu_item_level' => 'top',
                'keep_config' => true,
                'keep_data'   => true,
            ),
            'box' => array
            (
                'box_class'                => '',
                'enable_author_box_styles' => true,
                'display'        => 'posts',
                'hide_if_no_bio' => false,
                'order'          => 11,
                'multiauthor_box_layout' => 'default',
                'bio_field' => 'full',
                'show_related'       => true,
                'related_orderby'    => 'date',
                'related_order'      => 'desc',
                'related_items'      => '4',
                'related_post_types' => "post", // Data stored as a string with comma-separated items. No array!
                'show_empty_related' => true,
                'layout'           => 'slim',
                'position'         => 'below',
                'box_margin'       => '20',
                'box_width'        => '100',
                'box_border'       => 'horizontals',
                'box_border_style' => 'solid',
                'box_border_width' => '3',
                'box_border_color' => '#adadad',
                'box_background'   => '#efefef',
                'box_shadow'       => 'right',
                'show_headline'       => false,
                'headline_text_size'  => '18',
                'headline_text_style' => '',
                'headline_text_case'  => 'none',
                'headline_text_align' => 'left',
                'headline_text_color' => 'inherit',
                'tabs_position'     => 'full',
                'tabs_background'   => '#000000',
                'tabs_color'        => '#f4f4f4',
                'tabs_active_color' => '#e6e6e6',
                'tabs_border'       => 'top',
                'tabs_border_style' => 'solid',
                'tabs_border_width' => '4',
                'tabs_border_color' => 'orange',
                'tabs_text_color'   => 'inherit',
                'profile_layout'          => 'layout-1',
                'profile_valign'          => 'center',
                'bottom_background_color' => '#e0e0e0',
                'bottom_border_style'     => 'solid',
                'bottom_border_width'     => '1',
                'bottom_border_color'     => '#b6b6b6',
                'show_avatar'             => true,
                'avatar_src'              => 'local',
                'avatar_local_fallback'   => 'gravatar',
                'avatar_default_gravatar' => 'mp',
                'avatar_width'            => 150,
                'avatar_height'           => 150,
                'avatar_style'            => 'none',
                'avatar_border_style'     => 'solid',
                'avatar_border_width'     => '2',
                'avatar_border_color'     => '#bfbfbf',
                'avatar_text_color'       => '#dd9933',
                'avatar_bg_color'         => '#000000',
                'avatar_link_to_archive'  => true,
                'name_link_to_archive'     => true,
                'name_text_size'           => '22',
                'name_text_style'          => '',
                'name_text_case'           => 'none',
                'name_text_color'          => 'inherit',
                'name_text_align'          => 'left',
                'name_inherited_underline' => 'keep',
                'show_meta'       => true,
                'meta_text_size'  => '12',
                'meta_text_style' => '',
                'meta_text_case'  => 'none',
                'meta_text_color' => 'inherit',
                'meta_text_align' => 'left',
                'meta_separator'  => '|',
                'bio_text_size'   => '14',
                'bio_line_height' => '1',
                'bio_text_color'  => 'inherit',
                'bio_text_align'  => 'justify',
                'bio_text_style'  => '',
                'show_icons'  => true,
                'icons_size'  => '20',
                'icons_color' => '#999999',
                'icons_style' => 'default',
                'related_layout' => 'layout-1',
                'related_text_size'  => '14',
                'related_text_style' => '',
                'related_text_case'  => 'none',
                'related_text_color' => 'inherit',
            ),
            'byline' => array
            (
                'byline_multiauthor_display'        => 'all',
                'byline_multiauthor_separator'      => '',
                'byline_multiauthor_last_separator' => '',
                'byline_name_link'                  => true,
                'byline_multiauthor_link'           => true,
                'byline_prefix'                     => '',
                'byline_suffix'                     => '',
                'enable_byline_template_tags'       => false,
            ),
            'archives' => array
            (
                'guest_archive_enabled'       => MOLONGUI_AUTHORSHIP_IS_PRO,
                'guest_archive_include_pages' => false,
                'guest_archive_include_cpts'  => false,
                'guest_archive_permalink'     => '',
                'guest_archive_base'          => 'author',
                'guest_archive_tmpl'          => '',
                'user_archive_enabled'        => true,
                'user_archive_include_pages'  => false,
                'user_archive_include_cpts'   => false,
                'user_archive_tmpl'           => '',
                'user_archive_base'           => 'author',
                'user_archive_slug'           => false,
            ),
            'seo' => array
            (
                'add_html_meta'            => true,
                'add_opengraph_meta'       => true,
                'add_facebook_meta'        => true,
                'add_twitter_meta'         => true,
                'multi_author_meta'        => 'many',
                'enable_author_box_schema' => true,
                'add_nofollow'             => false,
                'box_headline_tag'         => 'h3',
                'box_author_name_tag'      => 'h5',
            ),
            'compat' => array
            (
                'enable_theme_compat'   => true,
                'enable_plugin_compat'  => true,
                'enable_cdn_compat'     => false,
                'enable_guests_in_api'  => false,
                'hide_elements'         => '',
                'enable_sc_text_widget' => false,
            ),
            'strings' => array
            (
                'headline'         => __( "About the author", 'molongui-authorship' ),
                'at'               => __( "at", 'molongui-authorship' ),
                'web'              => __( "Website", 'molongui-authorship' ),
                'more_posts'       => __( "+ posts", 'molongui-authorship' ),
                'bio'              => __( "Bio", 'molongui-authorship' ),
                'about_the_author' => __( "About the author", 'molongui-authorship' ),
                'related_posts'    => __( "Related posts", 'molongui-authorship' ),
                'profile_title'    => __( "Author profile", 'molongui-authorship' ),
                'related_title'    => __( "Related entries", 'molongui-authorship' ),
                'no_related_posts' => __( "This author does not have any more posts.", 'molongui-authorship' ),
            ),
        );
    }
}
if ( !function_exists( 'molongui_authorship_add_default_settings' ) )
{
    function molongui_authorship_add_default_settings()
    {
        $main     = (array) get_option( MOLONGUI_AUTHORSHIP_MAIN_SETTINGS );
        $box      = (array) get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
        $byline   = (array) get_option( MOLONGUI_AUTHORSHIP_BYLINE_SETTINGS );
        $archives = (array) get_option( MOLONGUI_AUTHORSHIP_ARCHIVES_SETTINGS );
        $seo      = (array) get_option( MOLONGUI_AUTHORSHIP_SEO_SETTINGS );
        $compat   = (array) get_option( MOLONGUI_AUTHORSHIP_COMPAT_SETTINGS );
        $strings  = (array) get_option( MOLONGUI_AUTHORSHIP_STRINGS_SETTINGS );
        $defaults = molongui_authorship_get_default_settings();
        foreach ( $defaults as $tab => $default )
        {
            if ( !empty( $$tab ) ) update_option( constant( 'MOLONGUI_AUTHORSHIP_'.strtoupper( $tab ).'_SETTINGS' ), array_merge( $default, $$tab ) );
            else add_option( constant( 'MOLONGUI_AUTHORSHIP_'.strtoupper( $tab ).'_SETTINGS' ), $default );
        }
    }
}
if ( !function_exists( 'authorship_validate_settings' ) )
{
    function authorship_validate_settings( $option, $input )
    {
        switch ( str_replace( MOLONGUI_AUTHORSHIP_DB_PREFIX, '', $option ) )
        {
            case 'main':
                if ( !MOLONGUI_AUTHORSHIP_IS_PRO )
                {
$input['enable_search_by_author'] = false;
$input['enable_guests_in_search'] = false;
$input['encode_email']            = false;
$input['encode_phone']            = false;
$input['guest_menu_item_level']   = 'top';
                    $post_types = explode( ",", $input['post_types'] );
                    if ( in_array( 'post', $post_types ) ) $input['post_types']  = "post";
                    if ( in_array( 'page', $post_types ) ) $input['post_types'] .= ",page";
                }
                else
                {
                    $current = (array) get_option( $option );
                    if ( !empty( $input['post_types'] ) )
                    {
                        foreach ( explode( ",", $input['post_types'] ) as $post_type )
                        {
                            if ( in_array( $post_type, array( 'post', 'page' ) ) ) continue;
                            if ( !in_array( $post_type, (array) $current['post_types'] ) )
                            {
                                $post_types[] = $post_type;
                            }
                        }
                    }
                    if ( !empty( $post_types ) )
                    {
                        authorship_update_post_counters( $post_types );
                    }
                }

            break;

            case 'box':
                $saved = (array) get_option( $option );
                if ( !MOLONGUI_AUTHORSHIP_IS_PRO )
                {
                    $input['order']              = 11;
                    $input['related_items']      = 4;
                    $input['related_orderby']    = 'date';
                    $input['related_order']      = 'DESC';
                    $input['related_post_types'] = "post";
                    $customizer_premium_refresh_settings = array
                    (
                        'profile_layout' => array
                        (
                            'accepted' => array( 'layout-1' ),
                            'default'  => 'layout-1',
                        ),
                        'related_layout' => array
                        (
                            'accepted' => array( 'layout-1', 'layout-2' ),
                            'default'  => 'layout-1',
                        ),
                        'avatar_src' => array
                        (
                            'accepted' => array( 'local', 'gravatar' ),
                            'default'  => 'local',
                        ),
                        'avatar_default_gravatar' => array
                        (
                            'accepted' => array( 'mp', 'identicon', 'monsterid', 'wavatar', 'retro', 'robohash', 'blank' ),
                            'default'  => 'mp',
                        ),
                        'bio_field' => array
                        (
                            'accepted' => array( 'full' ),
                            'default'  => 'full',
                        ),
                    );
                    foreach ( $customizer_premium_refresh_settings as $setting => $params )
                    {
                        if ( !in_array( $input[$setting], $params['accepted'] ) )
                        {
                            if ( !empty( $saved[$setting] ) and in_array( $saved[$setting], $params['accepted'] ) ) $input[$setting] = $saved[$setting];
                            else $input[$setting] = $params['default'];
                        }
                    }
                }
                $customizer_settings = array
                (
                    'layout',
                    'position',
                    'order',
                    'show_headline',
                    'box_margin',
                    'box_width',
                    'box_border',
                    'box_border_style',
                    'box_border_width',
                    'box_border_color',
                    'box_background',
                    'box_shadow',
                    'headline_text_size',
                    'headline_text_style',
                    'headline_text_case',
                    'headline_text_align',
                    'headline_text_color',
                    'tabs_position',
                    'tabs_background',
                    'tabs_color',
                    'tabs_active_color',
                    'tabs_border',
                    'tabs_border_style',
                    'tabs_border_width',
                    'tabs_border_color',
                    'tabs_text_color',
                    'profile_layout',
                    'profile_valign',
                    'bottom_background_color',
                    'bottom_border_style',
                    'bottom_border_width',
                    'bottom_border_color',
                    'show_avatar',
                    'avatar_src',
                    'avatar_local_fallback',
                    'avatar_default_gravatar',
                    'avatar_width',
                    'avatar_height',
                    'avatar_style',
                    'avatar_border_style',
                    'avatar_border_width',
                    'avatar_border_color',
                    'avatar_text_color',
                    'avatar_bg_color',
                    'avatar_link_to_archive',
                    'name_link_to_archive',
                    'name_text_size',
                    'name_text_style',
                    'name_text_case',
                    'name_text_align',
                    'name_inherited_underline',
                    'name_text_color',
                    'show_meta',
                    'meta_text_size',
                    'meta_text_style',
                    'meta_text_case',
                    'meta_text_align',
                    'meta_text_color',
                    'meta_separator',
                    'bio_text_size',
                    'bio_line_height',
                    'bio_text_style',
                    'bio_text_align',
                    'bio_text_color',
                    'bio_field',
                    'show_icons',
                    'icons_style',
                    'icons_size',
                    'icons_color',
                    'related_layout',
                    'related_text_size',
                    'related_text_style',
                    'related_text_case',
                    'related_text_color',
                );
                foreach ( $customizer_settings as $customizer_setting )
                {
                    if ( !isset( $input[$customizer_setting] ) ) $input[$customizer_setting] = $saved[$customizer_setting];
                }

            break;

            case 'byline':
                if ( !MOLONGUI_AUTHORSHIP_IS_PRO )
                {
                }
                if ( isset( $input['byline_multiauthor_separator']      ) ) $input['byline_multiauthor_separator']      = str_replace( array( "*", "?", "/" ), "", trim( $input['byline_multiauthor_separator'] ) );
                if ( isset( $input['byline_multiauthor_last_separator'] ) ) $input['byline_multiauthor_last_separator'] = str_replace( array( "*", "?", "/" ), "", trim( $input['byline_multiauthor_last_separator'] ) );

            break;

            case 'archives':
                if ( !MOLONGUI_AUTHORSHIP_IS_PRO )
                {
                    $input['guest_archive_enabled']       = false;
                    $input['guest_archive_include_pages'] = false;
                    $input['guest_archive_include_cpts']  = false;
                    $input['guest_archive_tmpl']          = '';
                    $input['guest_archive_permalink']     = '';
                    $input['guest_archive_base']          = 'author';
                    $input['user_archive_enabled']        = true;
                    $input['user_archive_include_pages']  = false;
                    $input['user_archive_include_cpts']   = false;
                    $input['user_archive_tmpl']           = '';
                    $input['user_archive_permalink']      = '';
                    $input['user_archive_base']           = 'author';
                    $input['user_archive_slug']           = false;
                }
                else
                {
                }
                if ( !isset( $input['guest_archive_base'] ) ) $input['guest_archive_base'] = 'author';

            break;

            case 'seo':
                if ( !MOLONGUI_AUTHORSHIP_IS_PRO )
                {
                    $input['multi_author_meta']   = 'many';
                    $input['box_headline_tag']    = 'h3';
                    $input['box_author_name_tag'] = 'h5';
                }

            break;

            case 'compat':
                if ( !MOLONGUI_AUTHORSHIP_IS_PRO )
                {
                    $input['enable_sc_text_widgets'] = false;
                }

            break;

            case 'strings':

            break;
        }
        $input['plugin_version'] = MOLONGUI_AUTHORSHIP_VERSION;
        $input = apply_filters( 'authorship/options', $input, $option );
        do_action( 'authorship/options/validate', $input, $option );
        return $input;
    }
}
if ( !function_exists( 'authorship_get_post_types' ) )
{
    function authorship_get_post_types()
    {
        $options    = array();
        $post_types = molongui_get_post_types( 'all', 'objects', false );

        foreach( $post_types as $post_type )
        {
            $options[] = array
            (
                'id'       => $post_type->name,
                'label'    => $post_type->labels->name,
                'disabled' => apply_filters( '_authorship/options/post_type/disabled', in_array( $post_type->name, array( 'post', 'page' ) ) ? false : true, $post_type ),
            );
        }

        return $options;
    }
}