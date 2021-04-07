<?php
defined( 'ABSPATH' ) or exit;
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/helpers/post.php';
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/helpers/author.php';
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/helpers/options.php';
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/helpers/misc.php';
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/helpers/overwrites.php';
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/deprecated/actions.php';
require_once MOLONGUI_AUTHORSHIP_DIR . 'includes/deprecated/filters.php';
/*
add_filter('molongui_authorship_dont_render_author_box', function()
{
	$dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
	molongui_debug($dbt);
	return false;
});
*/