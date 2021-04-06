<?php
defined( 'ABSPATH' ) or exit;
$plugin_compat = molongui_authorship_is_feature_enabled( 'plugin_compat' );
$theme_compat  = molongui_authorship_is_feature_enabled( 'theme_compat' );
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/wordpress.php';
if ( $plugin_compat )
{
    if ( !function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

    if ( is_plugin_active( 'anywhere-elementor/anywhere-elementor.php' ) or is_plugin_active( 'anywhere-elementor-pro/anywhere-elementor-pro.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/anywhere-elementor.php';
    if ( is_plugin_active( 'bb-plugin/fl-builder.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/beaver-builder.php';
    if ( is_plugin_active( 'bb-theme-builder/bb-theme-builder.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/beaver-builder-theme-builder.php';
    if ( is_plugin_active( 'buddyboss-platform/bp-loader.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/buddyboss-platform.php';
    if ( class_exists( 'BuddyPress' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/buddypress.php';
    if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/elementor-pro.php';
    if ( is_plugin_active( 'essential-grid/essential-grid.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/essential-grid.php';
    if ( is_plugin_active( 'google-sitemap-generator/sitemap.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/google-sitemap-generator.php';
    if ( is_plugin_active( 'jetpack/jetpack.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/jetpack.php';
    if ( is_plugin_active( 'sfwd-lms/sfwd_lms.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/learndash.php';
    if ( is_plugin_active( 'memberpress/memberpress.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/memberpress.php';
    if ( is_plugin_active( 'seo-by-rank-math/rank-math.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/rank-math-seo.php';
    if ( is_plugin_active( 'schema/schema.php' ) or is_plugin_active( 'schema-premium/schema-premium.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/schema.php';
    if ( is_plugin_active( 'schema-and-structured-data-for-wp/structured-data-for-wp.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/schema-and-structured-data-for-wp.php';
    if ( is_plugin_active( 'shortcodes-indep/init.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/shortcodes-indep.php';
    if ( is_plugin_active( 'td-cloud-library/td-cloud-library.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/tagdiv-cloud-library.php';
    if ( is_plugin_active( 'autodescription/autodescription.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/the-seo-framework.php';
    if ( is_plugin_active( 'ultimate-member/ultimate-member.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/ultimate-member.php';
    if ( is_plugin_active( 'userswp/userswp.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/userswp.php';
    if ( is_plugin_active( 'wordpress-popular-posts/wordpress-popular-posts.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/wordpress-popular-posts.php';
    if ( is_plugin_active( 'js_composer/js_composer.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/wpbakery-page-builder.php';
    if ( is_plugin_active( 'wpdiscuz/class.WpdiscuzCore.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/wpdiscuz.php';
    if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/wpml.php';
    if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) or is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/plugins/yoast.php';
}
if ( $theme_compat )
{
    $theme = wp_get_theme();

    if     ( 'Astra' == $theme->name or 'Astra' == $theme->parent_theme )                           require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/astra.php';
    elseif ( 'GeneratePress' == $theme->name or 'GeneratePress' == $theme->parent_theme )           require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/generatepress.php';
    elseif ( 'Divi' == $theme->name or 'Divi' == $theme->parent_theme )                             require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/divi.php';
    elseif ( 'Extra' == $theme->name or 'Extra' == $theme->parent_theme )                           require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/extra.php';
    elseif ( 'The7' == $theme->name or 'The7' == $theme->parent_theme )                             require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/the7.php';
    elseif ( 'Publisher' == $theme->name or 'Publisher' == $theme->parent_theme )                   require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/publisher.php';
    elseif ( 'soledad' == $theme->name or 'soledad' == $theme->parent_theme )                       require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/soledad.php';
    elseif ( 'Newspaper' == $theme->name or 'Newspaper' == $theme->parent_theme or
             'Newsmag'   == $theme->name or 'Newsmag'   == $theme->parent_theme )                   require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/newspaper.php';
    elseif ( 'ColorMag' == $theme->name or 'ColorMag' == $theme->parent_theme )                     require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/colormag.php';
    elseif ( 'Alea' == $theme->name or 'Alea' == $theme->parent_theme )                             require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/alea.php';
    elseif ( 'JNews' == $theme->name or 'JNews' == $theme->parent_theme )                           require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/jnews.php';
    elseif ( 'Agama' == $theme->name or 'Agama' == $theme->parent_theme )                           require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/agama.php';
    elseif ( 'fruitful' == $theme->name or 'fruitful' == $theme->parent_theme )                     require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/fruitful.php';
    elseif ( 'Jupiter' == $theme->name or 'Jupiter' == $theme->parent_theme )                       require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/jupiter.php';
    elseif ( 'Magazine Prime Pro' == $theme->name or 'Magazine Prime Pro' == $theme->parent_theme ) require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/magazine-prime-pro.php';
    elseif ( 'university' == $theme->name or 'university' == $theme->parent_theme )                 require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/university.php';
    elseif ( 'Vellum' == $theme->name or 'Vellum' == $theme->parent_theme )                         require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/vellum.php';
    elseif ( 'Bitz' == $theme->name or 'Bitz' == $theme->parent_theme )                             require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/bitz.php';
    elseif ( 'Mundana' == $theme->name or 'Mundana' == $theme->parent_theme )                       require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/mundana.php';
    elseif ( 'Genesis' == $theme->name or 'Genesis' == $theme->parent_theme )                       require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/genesis.php';
    elseif ( 'Spotlight' == $theme->name or 'Spotlight' == $theme->parent_theme )                   require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/spotlight.php';
    elseif ( 'Themify Ultra' == $theme->name or 'Themify Ultra' == $theme->parent_theme )           require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/themify-ultra.php';
    elseif ( 'Flatsome' == $theme->name or 'Flatsome' == $theme->parent_theme )                     require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/flatsome.php';
    elseif ( 'Blocksy' == $theme->name or 'Blocksy' == $theme->parent_theme )                       require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/blocksy.php';
    elseif ( 'BuddyBoss Theme' == $theme->name or 'BuddyBoss Theme' == $theme->parent_theme )       require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/buddyboss.php';
    elseif ( 'Creativo Theme' == $theme->name or 'Creativo Theme' == $theme->parent_theme )         require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/creativo.php';
    elseif ( 'AdoreChurch' == $theme->name or 'AdoreChurch' == $theme->parent_theme )               require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/adorechurch.php';
    elseif ( 'Thrive Themes' == $theme->get( 'Author' ) or
            ( $theme->parent() and 'Thrive Themes' == $theme->parent()->get( 'Author' ) ) )   require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/compat/themes/thrive-themes.php';
}