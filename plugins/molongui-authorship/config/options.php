<?php
defined( 'ABSPATH' ) or exit;
$customizer_url = \molongui_authorship_get_customizer();

$options = array();
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'main',
        'name'    => __( 'Main', 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Personalize Molongui Authorship. Settings that make it yours.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'main_features_header',
        'label'   => __( 'Features', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_multi_authors',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => sprintf( __( "Enable %sco-authoring%s to be able to add more than one author to a post", 'molongui-authorship' ), '<strong>', '</strong>' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_guest_authors',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sAdd content from other authors to your site and easily credit them by changing post author.%s %sJust add a new post, paste content and change post author accordingly.%s %sGuest authors are just names. They doesn't have access to your Dashboard, so you don't have to worry about managing them.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' , '<p>', '</p>' ),
        'label'   => sprintf( __( "Enable %sguest posting%s to be able to add non-registered users as post author", 'molongui-authorship' ), '<strong>', '</strong>' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_author_boxes',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sAuthor boxes are a great way to credit authors for their work.%s %sCustomize how they look like so they integrate nicely on your site.%s %sAnd easily configure how and when to display them.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' , '<p>', '</p>' ),
        'label'   => sprintf( __( "Enable %sauthor boxes%s to properly credit authors for their work displaying their information on your posts", 'molongui-authorship' ), '<strong>', '</strong>' )

    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_local_avatars',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sStop depending on Gravatar to display author pictures.%s %sEnabling this setting allows you to upload custom images as author avatars.%s %sThis setting does not disable Gravatar. In fact, it can still be used as fallback if no local avatar is provided.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' , '<p>', '</p>' ),
        'label'   => sprintf( __( "Enable %slocal avatars%s to be able to upload custom images as author profile avatars", 'molongui-authorship' ), '<strong>', '</strong>' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_user_profiles',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sEnabling this setting additional fields are added to the user profile page.%s %sMost of them can be displayed on the author box and other public places.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
        'label'   => __( "Enhance user profile adding extra fields like short bio, phone, company, social links and others", 'molongui-authorship' ),
    );
    $enhanced_search   = array();
    $enhanced_search[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'enhanced_search',
        'title'   => '',
        'desc'    => '',
        'label'   => sprintf( __( "Enhance the search function to give visitors more relevant search results and a better user experience allowing them to search content by author display name.", 'molongui-authorship' ) ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/features/search/markup', $enhanced_search ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'main_post_types_header',
        'label'   => __( 'Post Types', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'dropdown',
        'atts'    => array
        (
            'search' => true,
            'multi'  => true,
        ),
        'class'   => '',
        'default' => '',
        'id'      => 'post_types',
        'title'   => '',
        'desc'    => MOLONGUI_AUTHORSHIP_IS_PRO ? __( "Select those post types where plugin features will be enabled on.", 'molongui-authorship' ) : sprintf( __( "Select those post types where plugin features will be enabled on. By default, they are enabled on %sPosts%s and %sPages%s.", 'molongui-authorship' ), '<strong>', '</strong>', '<strong>', '</strong>' ),
        'help'    => '',
        'label'   => '',
        'options' => authorship_get_post_types(),
    );
    $options[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'premium_post_types',
        'title'   => __( "Enable plugin features on other post types", 'molongui-authorship' ),
        'desc'    => __( "Need guest authors, co-authors or author box in articles, projects, products or any other custom post type? Upgrade now!", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'main_user_roles_header',
        'label'   => __( 'User Roles', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $user_roles   = array();
    $user_roles[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'user_roles',
        'title'   => '',
        'desc'    => '',
        'label'   => sprintf( __( "Select which user roles to take to populate authors dropdown selector. Even custom user roles are supported. %sBy default, %sadministrator%s, %seditor%s, %sauthor%s and %scontributor%s roles are enabled.", 'molongui-authorship' ), '<br>', '<code>', '</code>', '<code>', '</code>', '<code>', '</code>', '<code>', '</code>' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/user_roles/markup', $user_roles ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'main_social_networks_header',
        'label'   => __( 'Social Networks', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'dropdown',
        'atts'    => array
        (
            'search' => true,
            'multi'  => true,
        ),
        'class'   => '',
        'default' => '',
        'id'      => 'social_networks',
        'title'   => '',
        'desc'    => __( "Select those social networks you want to enable. You can select as many as you wish.", 'molongui-authorship' ),
        'help'    => array
        (
            'text' => sprintf( __( "%sThere are a ton of social networks. To avoid clutter, select those you want to enable.%s %sYou can select as many as you wish. And you can filter displayed list by typing the name of the network you are looking for.%s %sAnd if you find one missing, you can request us to include it.%s %s" ), '<p>', '</p>', '<p>', '</p>', '<p>', '</p>', ( !MOLONGUI_AUTHORSHIP_IS_PRO ? sprintf( __( "%sDisabled options are only available in %sMolongui Authorship Pro%s%s", 'molongui-authorship' ), '<p>', '<a href="'.MOLONGUI_AUTHORSHIP_WEB.'" target="_blank">', '</a>', '</p>' ) : '' ) ),
            'link' => 'https://www.molongui.com/docs/molongui-authorship/author-box/social-networks/',
        ),
        'label'   => '',
        'options' => molongui_authorship_get_social_networks(),
    );
    $options[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'premium_social_networks',
        'title'   => __( "Need to enable any of those disabled listed networks?", 'molongui-authorship' ),
        'desc'    => __( "Disabled networks are only available in Molongui Authorship Pro. Upgrade now!", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'main_spam_protecction_header',
        'label'   => __( 'Spam Protection', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $spam_protect   = array();
    $spam_protect[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'spam_protect',
        'title'   => __( "Display plain author email and phone without worrying about them getting spammed.", 'molongui-authorship' ),
        'desc'    => __( "Encode sensitive information to make it unreadable for SPAM bots.", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/spam_protect/markup', $spam_protect ) );
    $options[] = array
    (
        'display' => molongui_authorship_is_feature_enabled( 'guest' ),
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'main_interface_header',
        'label'   => __( 'Interface', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $interface   = array();
    $interface[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'interface',
        'title'   => '',
        'desc'    => '',
        'label'   => sprintf( __( "Move 'Guest Authors' menu item to place it under %sUsers%s, %sPosts%s or %sPages%s menu.", 'molongui-authorship' ), '<code>', '</code>', '<code>', '</code>', '<code>', '</code>' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/interface/markup', $interface ) );
}
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'byline',
        'name'    => __( 'Byline', 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Configure bylines to your needs.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'byline_author_name_header',
        'label'   => __( 'Author Name', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,//molongui_authorship_is_feature_enabled( 'multi' ),
        'deps'    => 'enable_multi_authors',
        'search'  => '',
        'type'    => 'inline-dropdown',
        'class'   => '',
        'default' => 'all',
        'id'      => 'byline_multiauthor_display',
        'title'   => '',
        'desc'    => '',
        'help'    => array
        (
            'text' => sprintf( __( "%sThe byline on a post gives the name of the people who contributed to write it.%s %sWhen a post is written by one single person, that person's name is the one displayed on the post byline. However, on co-authored posts you have the option to choose how author names are displayed.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
            'link' => 'https://www.molongui.com/docs/molongui-authorship/byline/byline-content-and-link/',
        ),

        'label'   => sprintf( __( "On co-authored posts display %s", 'molongui-authorship' ),  '{input}' ),
        'options' => array
        (
            'all' => array
            (
                'icon'  => '',
                'label' => __( "all authors names", 'molongui-authorship' ),
            ),
            'main' => array
            (
                'icon'  => '',
                'label' => __( "only the name of the main author", 'molongui-authorship' ),
            ),
            '1' => array
            (
                'icon'  => '',
                'label' => __( "main author name and remaining authors count as number", 'molongui-authorship' ),
            ),
            '2' => array
            (
                'icon'  => '',
                'label' => __( "first 2 authors names and remaining authors count as number", 'molongui-authorship' ),
            ),
            '3' => array
            (
                'icon'  => '',
                'label' => __( "first 3 authors names and remaining authors count as number", 'molongui-authorship' ),
            ),
        ),
    );
    $options[] = array
    (
        'display'     => true,//molongui_authorship_is_feature_enabled( 'multi' ),
        'deps'        => 'enable_multi_authors',
        'search'      => '',
        'type'        => 'inline-text',
        'placeholder' => ', ',
        'default'     => ', ',
        'class'       => 'inline',
        'id'          => 'byline_multiauthor_separator',
        'title'       => '',
        'desc'        => '',
        'help'        => sprintf( __( "%sYou can provide any word, string or symbol except these symbols: %s?%s %s/%s %s*%s.%s %sAny question mark, slash and asterisk you provide will be removed.%s", 'molongui-authorship' ), '<p>', '<code>', '</code>', '<code>', '</code>', '<code>', '</code>', '</p>', '<p>', '</p>' ),
        'label'       => sprintf( __( "As delimiter to separate multiple authors names, use: %s", 'molongui-authorship' ), '{input}' ),
    );
    $options[] = array
    (
        'display'     => true,//molongui_authorship_is_feature_enabled( 'multi' ),
        'deps'        => 'enable_multi_authors',
        'search'      => '',
        'type'        => 'inline-text',
        'placeholder' => 'and',
        'default'     => 'and',
        'class'       => 'inline',
        'id'          => 'byline_multiauthor_last_separator',
        'title'       => '',
        'desc'        => '',
        'help'        => sprintf( __( "%sYou can provide any word, string or symbol except these symbols: %s?%s %s/%s %s*%s.%s %sAny question mark, slash and asterisk you provide will be removed.%s", 'molongui-authorship' ), '<p>', '<code>', '</code>', '<code>', '</code>', '<code>', '</code>', '</p>', '<p>', '</p>' ),
        'label'       => sprintf( __( "As delimiter to separate last two authors names, use: %s", 'molongui-authorship' ), '{input}' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'byline_name_link',
        'title'   => '',
        'desc'    => '',
        'help'    => array
        (
            'text' => sprintf( __( "%sThemes normally make author name on bylines to link to that author's archive page. That's standard behavior so everyone expects it to work like that.%s %sHowever, Molongui Authorship allows you to disable that feature preventing author names to be a link.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
            'link' => 'https://www.molongui.com/docs/molongui-authorship/byline/byline-content-and-link/',
        ),
        'label'   => __( "Make the author name link to the author archive page", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => molongui_authorship_is_feature_enabled( 'multi' ),
        'deps'    => 'enable_multi_authors',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'byline_multiauthor_link',
        'title'   => '',
        'desc'    => '',
        'help'    => array
        (
            'text' => __( "Your theme or third-party plugin might prevent this setting to work. If disabled, the whole byline will link to the main author archive page.", 'molongui-authorship' ),
            'link' => 'https://www.molongui.com/docs/molongui-authorship/byline/byline-content-and-link/',
        ),
        'label'   => sprintf( __( "On co-authored posts, make each author name link to its author archive page %sMight not always work!%s", 'molongui-authorship' ), '<code>', '</code>' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'byline_modifiers_header',
        'label'   => __( 'Modifiers', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => MOLONGUI_AUTHORSHIP_IS_PRO,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $modifiers   = array();
    $modifiers[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'byline_modifiers',
        'title'   => sprintf( __( "Customize bylines by adding custom strings to the beginning %se.g. 'Written by'%s or to the end %se.g. 'et al.'%s of it.", 'molongui-authorship' ), '<code>', '</code>', '<code>', '</code>' ),
        'desc'    => __( "HTML markup is accepted, so you can add your own styles and custom elements.", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/byline/modifiers/markup', $modifiers ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'byline_template_tags_header',
        'label'   => __( 'Template Tags', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_byline_template_tags',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sFully customized and functional bylines can be achieved using the provided template tags.%s %sIt requires coding skills, but using them is the best way to go in order to avoid byline issues (e.g., wrong author name being displayed).%s %sSome parameters can be provided to the template tags so bylines can be fully localized and customized with custom HTML markup and CSS styles.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>', '<p>', '</p>' ),
        'label'   => __( "Enable Template Tags", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'link',
        'class'   => '',
        'default' => '',
        'id'      => '',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Click to open Plugin Docs", 'molongui-authorship' ),
        'label'   => __( "Check out Template Tags documentation", 'molongui-authorship' ),
        'href'    => MOLONGUI_AUTHORSHIP_FW_URL_WEB.'docs/molongui-authorship/howto/how-to-incorporate-molongui-authorship-template-tags-into-your-theme/',
        'target'  => '_blank',
    );
}
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'box',
        'name'    => __( 'Author Box', 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Let your readers learn more about authors on your site at a glance.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'box_styling_header',
        'label'   => __( 'Styling', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display'     => true,
        'deps'        => '',
        'search'      => '',
        'type'        => 'inline-text',
        'placeholder' => '',
        'default'     => '',
        'class'       => 'inline',
        'id'          => 'box_class',
        'title'       => '',
        'desc'        => '',
        'help'        => sprintf( __( "%sYou can provide a CSS class you want to be added to the author box. %sThis is useful if you need to add some custom styling to the author box or overwrite a default value.%s %sRemember that CSS selectors are generally case-insensitive.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>', '<p>', '</p>' ),
        'label'       => sprintf( __( "Add this custom CSS class to the author box container: %s", 'molongui-authorship' ), '{input}' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'radio',
        'class'   => '',
        'default' => 1,
        'id'      => 'enable_author_box_styles',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sAuthor boxes are fully customizable. All the settings you need to make them fit your likings and website style are available in the %sWordPress Customizer%s.%s %sIf you prefer to style it from scratch coding your own stylesheets, you can disable the load of the provided styles and settings.%s", 'molongui-authorship' ), '<p>', '<strong><i>', '</i></strong>', '</p>', '<p>', '</p>' ),
        'label'   => '',
        'options' => array
        (
            array
            (
                'value' => 1,
                'label' => __( "I want to visually and easily style my author box using WordPress Customizer", 'molongui-authorship' ),
            ),
            array
            (
                'value' => 0,
                'label' => __( "I'm a skilled developer and I prefer to provide my own styles. Don't load plugin CSS files.", 'molongui-authorship' ),
            ),
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'link',
        'class'   => '',
        'default' => '',
        'id'      => 'open_customizer',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Click to open WordPress Customizer", 'molongui-authorship' ),
        'label'   => __( "Customize author box styles", 'molongui-authorship' ),
        'href'    => $customizer_url,
        'target'  => '_self',
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'box_styles_warning',
        'title'   => __( "REMEMBER!", 'molongui-authorship' ),
        'desc'    => __( "Not loading the provided author box styles requires you to provide your own stylesheet. To do so you can use the 'Custom CSS' setting available at the WordPress Customizer or a child theme.", 'molongui-authorship' ),
        'help'    => '',
        'link'    => '',
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'box_display_header',
        'label'   => __( 'Display', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'link',
        'class'   => '',
        'default' => '',
        'id'      => 'display_settings_override_notice',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Click to learn more", 'molongui-authorship' ),
        'label'   => __( "Settings below might get overridden by more specific post or author settings", 'molongui-authorship' ),
        'href'    => 'https://www.molongui.com/docs/molongui-authorship/author-box/display-settings/',
        'target'  => '_blank',
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'inline-dropdown',
        'class'   => '',
        'default' => 'below',
        'id'      => 'display',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => sprintf( __( "Automatically display the author box %s", 'molongui-authorship' ),  '{input}' ),
        'options' => array
        (
            'posts' => array
            (
                'icon'  => '',
                'label' => __( "only on Posts", 'molongui-authorship' ),
            ),
            'pages' => array
            (
                'icon'  => '',
                'label' => __( "only on Pages", 'molongui-authorship' ),
            ),
            'show' => array
            (
                'icon'  => '',
                'label' => __( "on those post types configured on the 'Main' tab", 'molongui-authorship' ),
            ),
            'hide' => array
            (
                'icon'  => '',
                'label' => __( "nowhere. I will configure author box display on a per post basis", 'molongui-authorship' ),
            ),
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'hide_if_no_bio',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => sprintf( __( "Hide the author box if there is no author bio info to show", 'molongui-authorship' ), '<strong>', '</strong>' ),
    );

    $display   = array();
    $display[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'hide_on_categories',
        'title'   => __( "Want to hide the author box on posts with a given category?", 'molongui-authorship' ),
        'desc'    => __( "Upgrade to be able to select those post categories where the author box won't be displayed", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/display/categories/markup', $display ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'box_placement_header',
        'label'   => __( 'Placement', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'inline-dropdown',
        'class'   => '',
        'default' => 'below',
        'id'      => 'position',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => sprintf( __( "Display the author box %s the post content", 'molongui-authorship' ),  '{input}' ),
        'options' => array
        (
            'above' => array
            (
                'icon'  => '',
                'label' => __( "above", 'molongui-authorship' ),
            ),
            'below' => array
            (
                'icon'  => '',
                'label' => __( "below", 'molongui-authorship' ),
            ),
            'both' => array
            (
                'icon'  => '',
                'label' => __( "above and below", 'molongui-authorship' ),
            ),
        ),
    );
    $placement   = array();
    $placement[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'hook_priority',
        'title'   => __( "Push the author box higher/lower so it gets displayed before/after other added post widgets", 'molongui-authorship' ),
        'desc'    => __( "This helps ordering content added by third-party plugins", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );
    $placement[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode',
        'title'   => __( "Display the author box anywhere: Custom post types, Sidebar, Footer...", 'molongui-authorship' ),
        'desc'    => __( "Easy-to-use shortcode with many customization attributes", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/hook_priority/markup', $placement ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'box_layout_header',
        'label'   => __( 'Layout', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => false,
        'id'      => 'show_headline',
        'title'   => '',
        'desc'    => '',
        'help'    => array
        (
            'text' => sprintf( __( "%sSometimes you may want to display some text just above the author box.%s %sSomething like: %sAbout the author%s%s %sActivate this setting to add a heading to your author box and fully customize it using WordPress Customizer.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '<i>', '</i>', '</p>', '<p>', '</p>' ),
            'link' => $customizer_url, // TODO: Make this link have the label = "Open Customizer"
        ),
        'label'   => __( "Display a fully customizable heading above the author box", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'inline-dropdown',
        'class'   => '',
        'default' => 'slim',
        'id'      => 'layout',
        'title'   => '',
        'desc'    => '',
        'help'    => array
        (
            'text' => sprintf( __( "%sDifferent author box layouts to choose from.%s %sGet to know how each one looks like on your site using the Customizer's live preview.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
            'link' => $customizer_url, // TODO: Make this link have the label = "Open Customizer"
        ),
        'label'   => sprintf( __( "Use the %s layout to display the author box", 'molongui-authorship' ), '{input}' ),
        'options' => array
        (
            'slim' => array
            (
                'icon'  => '',
                'label' => __( "slim", 'molongui-authorship' ),
            ),
            'tabbed' => array
            (
                'icon'  => '',
                'label' => __( "tabbed", 'molongui-authorship' ),
            ),
            'stacked' => array
            (
                'icon'  => '',
                'label' => __( "stacked", 'molongui-authorship' ),
            ),
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'enable_multi_authors',
        'search'  => '',
        'type'    => 'inline-dropdown',
        'class'   => '',
        'default' => 'default',
        'id'      => 'multiauthor_box_layout',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => sprintf( __( "On co-authored posts, display author bio information in %s", 'molongui-authorship' ), '{input}' ),
        'options' => array
        (
            'default' => array
            (
                'icon'  => '',
                'label' => __( "one single box", 'molongui-authorship' ),
            ),
            'individual' => array
            (
                'icon'  => '',
                'label' => __( "as many author boxes as authors", 'molongui-authorship' ),
            ),
        ),
    );
    $options[] = array
    (
        'display' => false,
        'deps'    => '',
        'search'  => '',
        'type'    => 'number',
        'default' => 600,
        'min'     => 1,
        'max'     => '',
        'step'    => 1,
        'class'   => 'inline',
        'id'      => 'breakpoint',
        'title'   => '',
        'desc'    => '',
        'help'    => sprintf( __( "%sAs other plugins may also add their stuff to post content, the author box might be displayed somewhere different than expected. Making the plugin to add the author box before that third-party content (lowering the priority number) should move the box up, while adding it later (increasing the priority number) should move the box down.%s %sA value below 10 may cause issues with your content%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
        'label'   => sprintf( __( "Display the responsive layout version of the author box for screens up to %s px wide", 'molongui-authorship' ), '{input}' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'link',
        'class'   => '',
        'default' => '',
        'id'      => 'open_customizer',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Click to open WordPress Customizer", 'molongui-authorship' ),
        'label'   => __( "Open Live-Preview Author Box Customizer", 'molongui-authorship' ),
        'href'    => $customizer_url,
        'target'  => '_self',
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'box_metadata_header',
        'label'   => __( 'Metadata', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => false,
        'id'      => 'show_meta',
        'title'   => '',
        'desc'    => '',
        'help'    => array
        (
            'text' => sprintf( __( "%sDisplay author job position, company, telephone, email and other available author details in a line below the author name.%s %sYou can style that metadata line using WordPress Customizer.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
            'link' => $customizer_url, // TODO: Make this link have the label = "Open Customizer"
        ),
        'label'   => __( "Display author details like job position and company in the author box", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'box_social_links_header',
        'label'   => __( 'Social Links', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'inline-dropdown',
        'class'   => '',
        'default' => '_blank',
        'id'      => 'social_link_target',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => sprintf( __( "Open social links on %s", 'molongui-authorship' ),  '{input}' ),
        'options' => array
        (
            '_self' => array
            (
                'icon'  => '',
                'label' => __( "the same browser tab", 'molongui-authorship' ),
            ),
            '_blank' => array
            (
                'icon'  => '',
                'label' => __( "a new tab", 'molongui-authorship' ),
            ),
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'box_related_posts_header',
        'label'   => __( 'Related Posts', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'show_related',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => __( "Display related posts from the same author within the author box", 'molongui-authorship' ),
    );
    $related_posts   = array();
    $related_posts[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'show_related',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'related_posts',
        'title'   => __( "Configure which related posts are displayed to provide more relevant content and a better user experience", 'molongui-authorship' ),
        'desc'    => __( "Select which (custom) post types to retrieve, how many to display and how to order and sort them", 'molongui-authorship' ),
        'label'   => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/related_posts/markup', $related_posts ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'show_related',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => false,
        'id'      => 'show_empty_related',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => __( "Show section even if there are no related entries to show.", 'molongui-authorship-pro' ),
    );
    $options[] = array
    (
        'display'     => true,
        'deps'        => 'show_related show_empty_related',
        'search'      => '',
        'type'        => 'inline-text',
        'placeholder' => __( 'This author does not have any more posts.', 'molongui-authorship-pro' ),
        'default'     => '',
        'class'       => 'long',
        'id'          => 'no_related_posts',
        'title'       => '',
        'desc'        => '',
        'help'        => array
        (
            'text'    => sprintf( __( "%sMessage to display to let your readers know the author has no related entries to show.%s", 'molongui-authorship' ), '<p>', '</p>' ),
            'link'    => '',
        ),
        'label'       => sprintf( __( "When there are no related posts to show, display this text %s", 'molongui-authorship-pro' ), '{input}' ),
    );
}
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'archives',
        'name'    => __( 'Author Pages', 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Customize those pages listing all content written by a given author. Hassle-Free.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'archive_guest_header',
        'label'   => __( 'Guest Author Page', 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => MOLONGUI_AUTHORSHIP_IS_PRO,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $guest_archive   = array();
    $guest_archive[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'guest-archives',
        'title'   => __( "Give your guest authors the credit they deserve with Guest Archive Pages.", 'molongui-authorship' ),
        'desc'    => __( "Just like User Archives. Same layout, same styles.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/archives/guest/markup', $guest_archive ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'archive_user_header',
        'label'   => __( "Registered User Page", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => MOLONGUI_AUTHORSHIP_IS_PRO,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $user_archive   = array();
    $user_archive[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'user-archives',
        'title'   => __( "Easily configure User Pages to your needs.", 'molongui-authorship' ),
        'desc'    => __( "Disable them and configure redirection. Switch template and permalink. Include pages and custom post types. Customize page title.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/archives/user/markup', $user_archive ) );
}
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'seo',
        'name'    => __( 'SEO', 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Easily include your authors in your SEO strategy.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'seo_tags_header',
        'label'   => __( "Author Tags", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => '',
        'title'   => '',
        'desc'    => __( "Author meta tags might be useful for rich snippets and SEO. Enabling settings below might not be required if you already have a dedicated SEO/Schema plugin that gets the job done.", 'molongui-authorship' ),
        'help'    => '',
        'link'    => '',
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'add_html_meta',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => sprintf( __( "Add %sname='author'%s meta tags.", 'molongui-authorship' ), '<code>', '</code>' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'add_opengraph_meta',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Opengraph meta tags might help your site improve SEO ratings.", 'molongui-authorship' ),
        'label'   => __( "Add Opengraph meta tags.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'add_facebook_meta',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Facebook tags might help your site improve SEO ratings.", 'molongui-authorship' ),
        'label'   => sprintf( __( "Add %sproperty='article:author'%s meta tag for Facebook.", 'molongui-authorship' ), '<code>', '</code>' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'add_twitter_meta',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Twitter tags might help your site improve SEO ratings.", 'molongui-authorship' ),
        'label'   => sprintf( __( "Add %sname='twitter:creator'%s meta tag for Twitter.", 'molongui-authorship' ), '<code>', '</code>' ),
    );
    $seo_tags   = array();
    $seo_tags[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_multi_authors',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'seo-tags',
        'title'   => __( "Configure how to include co-authors information into post meta tags.", 'molongui-authorship' ),
        'desc'    => __( "Get full control over meta tags to improve your SEO.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/seo/tags/markup', $seo_tags ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'seo_schema_header',
        'label'   => __( "Structured Data", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_author_box_schema',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => __( "Add Structured Data/Schema.org Markup to HTML code generated by the plugin.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'seo_links_header',
        'label'   => __( "Links", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'add_nofollow',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => __( "Add 'nofollow' attribute to social networks links.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'enable_author_boxes',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'seo_headings_header',
        'label'   => __( "Headings", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => MOLONGUI_AUTHORSHIP_IS_PRO,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $seo_headings   = array();
    $seo_headings[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_author_boxes',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'seo-headings',
        'title'   => __( "Improve your SEO selecting the HTML tags that best suits your SEO strategy.", 'molongui-authorship' ),
        'desc'    => __( "Configure heading tags for author box headline and author box author name.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/seo/headings/markup', $seo_headings ) );
}
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'shortcodes',
        'name'    => __( "Shortcodes", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Handy shortcodes that will make building your site a lot easier.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'shortcodes_header',
        'label'   => __( "Shortcodes", 'molongui-authorship' ),
        'button'  => array(),
    );
    $options[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcodes',
        'title'   => __( "Display author information wherever your design requires. Easy yet highly flexible and customizable.", 'molongui-authorship' ),
        'desc'    => __( "A must-have if you use a page builder like Elementor or Divi Builder.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_box',
        'title'   => '[molongui_author_box]',
        'desc'    => sprintf( __( "Displays an author box anywhere you want. You can customize which author information to show and how the displayed author box will look like by using additional attributes. All styling settings can be overridden. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_box/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_box/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_list',
        'title'   => '[molongui_author_list]',
        'desc'    => sprintf( __( "Displays a list of (all) authors in your site anywhere you want. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_list/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_list/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_posts',
        'title'   => '[molongui_author_posts]',
        'desc'    => sprintf( __( "Displays a list showing (all the) posts from any given author anywhere you want. Listed posts can be configured making use of the optional attributes this shortcode can take. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_posts/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_posts/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_byline',
        'title'   => '[molongui_byline]',
        'desc'    => sprintf( __( "Displays the post's byline. Most themes display the byline just below the title. You can place it anywhere you want using this shortcode. What is more, customize it by prepending and/or appending any text you like. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_byline/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_byline/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_name',
        'title'   => '[molongui_author_name]',
        'desc'    => sprintf( __( "Displays the name of the current post author(s) if no attributes are provided or the name of a given author if you provide the author ID and author type. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_name/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_name/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_slug',
        'title'   => '[molongui_author_slug]',
        'desc'    => sprintf( __( "Displays the slug of the current post author(s) if no attributes are provided or the slug of a given author if you provide the author ID and author type. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_slug/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_slug/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_url',
        'title'   => '[molongui_author_url]',
        'desc'    => sprintf( __( "Displays the url to the archive page of the current post author(s) if no attributes are provided or the url to the archive page of a given author if you provide the author ID and author type. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_url/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_url/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_bio',
        'title'   => '[molongui_author_bio]',
        'desc'    => sprintf( __( "Displays the bio of the current post author(s) if no attributes are provided or the bio of a given author if you provide the author ID and author type. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_bio/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_bio/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_mail',
        'title'   => '[molongui_author_mail]',
        'desc'    => sprintf( __( "Displays the email address of the current post author(s) if no attributes are provided or the email address of a given author if you provide the author ID and author type. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_mail/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_mail/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_meta',
        'title'   => '[molongui_author_meta]',
        'desc'    => sprintf( __( "Displays the any author meta data of the current post author(s) if no attributes are provided or any author meta data of a given author if you provide the author ID and author type. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_meta/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_meta/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_avatar',
        'title'   => '[molongui_author_avatar]',
        'desc'    => sprintf( __( "Displays the avatar of the current post author(s) if no attributes are provided or the avatar of a given author if you provide the author ID and author type. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_avatar/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui_author_avatar/",
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'notice',
        'class'   => '',
        'default' => '',
        'id'      => 'shortcode_molongui_author_select',
        'title'   => '[molongui_author_select]',
        'desc'    => sprintf( __( "Displays a dropdown select listing (all the) authors from your blog. This shortcode is intended for developers only. %sLearn more%s", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui-author-select/" target="_blank">', '</a>' ),
        'help'    => '',
        'link'    => "https://www.molongui.com/docs/molongui-authorship/shortcodes/molongui-author-select/",
    );
}
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'compat',
        'name'    => __( 'Compatibility', 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Most of the issues you might have with the plugin can be easily fixed with these settings.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'compat_themes_header',
        'label'   => __( "Themes", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_theme_compat',
        'title'   => '',
        'desc'    => sprintf( __( "Molongui Authorship plugin works great with just about every theme, especially with the most popular. Some require tailored functions to achieve full compatibility, so you need to enable this setting. If you experience issues with the information displayed on your bylines or anything related to your authors information, make sure this is enabled. If it is and the issue persists, please %sopen a support ticket%s with us so we can assist.", 'molongui-authorship' ), '<a href="https://www.molongui.com/support/#open-ticket" target="_blank">', '</a>' ),
        'help'    => sprintf( __( "%sSome themes require this setting to be enabled in order to work properly.%s %sIn case of doubt, keep it enabled.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>'),
        'label'   => __( "Enable theme compatibility", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'link',
        'class'   => '',
        'default' => '',
        'id'      => '',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Click to open a Support Ticket", 'molongui-authorship' ),
        'label'   => __( "Issue persists? Report it", 'molongui-authorship' ),
        'href'    => molongui_get_support(),
        'target'  => '_blank',
    );
    $options[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'theme_premium_support',
        'title'   => __( "Need to make your theme work with Molongui Authorship ASAP?", 'molongui-authorship' ),
        'desc'    => __( "Get Premium support to make your theme run smoothly with Molongui Authorship.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB.'/pricing/',
            'target' => '_blank',
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'compat_plugins_header',
        'label'   => __( "Plugins", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => true,
        'id'      => 'enable_plugin_compat',
        'title'   => '',
        'desc'    => sprintf( __( "Some third plugins require tailored functions to be compatible with Molongui Authorship, so you need to enable this setting. If you experience issues related to your authors information, make sure this is enabled. If it is and the issue persists, please %sopen a support ticket%s with us so we can assist.", 'molongui-authorship' ), '<a href="https://www.molongui.com/support/#open-ticket" target="_blank">', '</a>' ),
        'help'    => sprintf( __( "%sSome plugins require this setting to be enabled in order to work properly.%s %sIn case of doubt, keep it enabled.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>'),
        'label'   => __( "Enable plugin compatibility", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'link',
        'class'   => '',
        'default' => '',
        'id'      => '',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Click to open a Support Ticket", 'molongui-authorship' ),
        'label'   => __( "Issue persists? Report it", 'molongui-authorship' ),
        'href'    => molongui_get_support(),
        'target'  => '_blank',
    );
    $options[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'plugin_premium_support',
        'title'   => __( "Want to benefit from Priority?", 'molongui-authorship' ),
        'desc'    => __( "Get elevated levels of support to help you keep your favourite plugins running smoothly together.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB.'/pricing/',
            'target' => '_blank',
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'enable_author_boxes',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'compat_cdn_header',
        'label'   => __( "CDN", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'enable_author_boxes',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => false,
        'id'      => 'enable_cdn_compat',
        'title'   => '',
        'desc'    => sprintf( __( "Messed up author box layout? And you using a CDN? Enable this setting and clear every cache you might have, including your CDN's. If the issue persists, please %sopen a support ticket%s with us so we can assist.", 'molongui-authorship' ), '<a href="https://www.molongui.com/support/#open-ticket" target="_blank">', '</a>' ),
        'help'    => array
        (
            'text' => sprintf( __( "%sActivate this setting only if you are experiencing issues.%s %sWhen using a CDN to serve stylesheet files, author box layout might look messed up. Enabling this setting should fix that.%s", 'molongui-authorship' ), '<p><strong>', '</strong></p>', '<p>', '</p>' ),
            'link' => 'https://www.molongui.com/docs/molongui-authorship/troubleshooting/the-author-box-layout-is-being-displayed-oddly/',
        ),
        'label'   => __( "Enable CDN compatibility to fix author box layout and make it display nicely.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'enable_author_boxes',
        'search'  => '',
        'type'    => 'link',
        'class'   => '',
        'default' => '',
        'id'      => '',
        'title'   => '',
        'desc'    => '',
        'help'    => __( "Click to open a Support Ticket", 'molongui-authorship' ),
        'label'   => __( "Issue persists? Report it", 'molongui-authorship' ),
        'href'    => molongui_get_support(),
        'target'  => '_blank',
    );
    $options[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_author_boxes',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'cdn_premium_support',
        'title'   => __( "Need Premium Support?", 'molongui-authorship' ),
        'desc'    => __( "Paid users are given top priority in our support system, with replies to their support tickets taking precedence.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB.'/pricing/',
            'target' => '_blank',
        ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '', // This header has multiple dependencies, so it must be handled with tailor-made JS.
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'compat_rest_header',
        'label'   => __( "REST API", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => MOLONGUI_AUTHORSHIP_IS_PRO,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $rest_api   = array();
    $rest_api[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '', // This header has multiple dependencies, so it must be handled with tailor-made JS.
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'rest_api',
        'title'   => __( "Allow third-party applications to interact with your posts and authors via the WordPress REST API.", 'molongui-authorship' ),
        'desc'    => __( "Expose post co-authors and guest author object.", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/rest_api/markup', $rest_api ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'compat_misc_header',
        'label'   => __( "Misc", 'molongui-authorship' ),
        'button'  => array
        (
            'display'  => true,
            'type'     => 'save',
            'label'    => __( "Save Settings", 'molongui-authorship' ),
            'title'    => __( "Save Settings", 'molongui-authorship' ),
            'class'    => 'm-save-options',
            'disabled' => true,
        ),
    );
    $options[] = array
    (
        'display'     => true,
        'deps'        => '',
        'search'      => '',
        'type'        => 'text',
        'placeholder' => '#author-bio, .author-box-wrap',
        'default'     => '',
        'class'       => 'inline',
        'id'          => 'hide_elements',
        'title'       => '',
        'desc'        => '',
        'help'        => array
        (
            'text'    => sprintf( __( "%sMany themes add elements to your site without giving the option to disable them.%s %sNow you can hide unwanted author boxes, byline decorations or whatever.%s", 'molongui-authorship' ), '<p>', '</p>', '<p>', '</p>' ),
            'link'    => 'https://www.molongui.com/docs/molongui-authorship/troubleshooting/the-author-box-shows-up-twice/',
        ),
        'label'       => sprintf( __( "Need to get rid of some elements you don't have the setting to? Provide a comma-separated list of CSS IDs and/or classes and Molongui Authorship will prevent them from being displayed on the front-end.", 'molongui-authorship' ), '<a href="https://www.molongui.com/docs/molongui-authorship/troubleshooting/the-author-box-shows-up-twice/" target="_blank">', '</a>' ),
    );
    $options[] = array
    (
        'display' => MOLONGUI_AUTHORSHIP_IS_PRO and version_compare( get_bloginfo( 'version' ),'4.9', '<' ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'toggle',
        'class'   => '',
        'default' => false,
        'id'      => 'enable_sc_text_widget',
        'title'   => '',
        'desc'    => '',
        'help'    => '',
        'label'   => __( "Enable the use of shortcodes in text widgets.", 'molongui-authorship' ),
    );
}
if ( true )
{
    $options[] = array
    (
        'display' => true,
        'type'    => 'section',
        'id'      => MOLONGUI_AUTHORSHIP_DB_PREFIX . 'tools',
        'name'    => __( 'Tools' ),
    );
    $options[] = array
    (
        'display' => true,
        'type'    => 'title',
        'label'   => __( "Convenient tools to easily manage plugin data.", 'molongui-authorship' ),
    );
    $options[] = array
    (
        'display' => true,
        'deps'    => '',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'tools_authorship_header',
        'label'   => __( "Authorship", 'molongui-authorship' ),
        'button'  => array(),
    );
    $authorship_tools[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => '',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'authorship_tools',
        'title'   => __( "1-click easy manage post authorship", 'molongui-authorship' ),
        'desc'    => __( "Export, import and reset your posts author configuration. Hasle-free!", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade same-width',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/authorship/tools/markup', $authorship_tools ) );
    $options[] = array
    (
        'display' => true,
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'header',
        'class'   => '',
        'id'      => 'tools_guests_header',
        'label'   => __( "Guest authors", 'molongui-authorship' ),
        'button'  => array(),
    );

    $guest_tools = array();
    $guest_tools[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'export_guests',
        'label'   => __( "Export Guest Authors to have a backup or import them on another installation", 'molongui-authorship' ),
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade same-width',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );
    $guest_tools[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'import_guests',
        'title'   => __( "Easily import one or thousands of guest authors with just 1 click", 'molongui-authorship' ),
        'desc'    => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade same-width',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );
    $guest_tools[] = array
    (
        'display' => apply_filters( 'authorship/options/display/banners', true ),
        'deps'    => 'enable_guest_authors',
        'search'  => '',
        'type'    => 'banner',
        'class'   => '',
        'default' => '',
        'id'      => 'delete_guests',
        'title'   => __( "Remove all existing guest authors at once. Instantly", 'molongui-authorship' ),
        'desc'    => '',
        'button'  => array
        (
            'label'  => __( "Upgrade", 'molongui-authorship' ),
            'title'  => __( "Upgrade", 'molongui-authorship' ),
            'class'  => 'm-upgrade same-width',
            'href'   => MOLONGUI_AUTHORSHIP_WEB,
            'target' => '_blank',
        ),
    );

    $options = array_merge( $options, apply_filters( '_authorship/options/guest/tools/markup', $guest_tools ) );
}