<?php

namespace Molongui\Authorship\Includes;

use Molongui\Authorship\Fw\Includes\Loader;
\defined( 'ABSPATH' ) or exit;
class Post
{
    private $loader;
    public function __construct( $vars = null )
    {
        $this->init();
        if ( \is_admin() )
        {
            $this->loader->add_filter( 'manage_posts_columns', $this, 'customize_admin_columns' );
            $this->loader->add_action( 'manage_posts_custom_column', $this, 'populate_admin_columns', 10, 2 );
            $this->loader->add_filter( 'manage_pages_columns', $this, 'customize_admin_columns' );
            $this->loader->add_action( 'manage_pages_custom_column', $this, 'populate_admin_columns', 10, 2 );
            $this->loader->add_filter( 'pre_get_avatar_data', $this, 'filter_avatar', 999, 2 );
            $this->loader->add_action( 'admin_menu', $this, 'remove_author_metabox' );
            $this->loader->add_action( 'add_meta_boxes', $this, 'add_meta_boxes' );
            $this->loader->add_filter( 'wp_insert_post_data', $this, 'update_post_author', 10, 3 );
            $this->loader->add_action( 'pre_post_update', $this, 'post_status' );
            $this->loader->add_action( 'save_post', $this, 'save', 10, 2 );
            $this->loader->add_action( 'attachment_updated', $this, 'save', 10, 2 );
            $this->loader->add_action( 'admin_head', $this, 'quick_edit_remove_author' );
            $this->loader->add_action( 'quick_edit_custom_box', $this, 'quick_edit_add_custom_fields', 10, 2 );
            $this->loader->add_action( 'admin_footer', $this, 'quick_edit_populate_custom_fields' );
            $this->loader->add_action( 'save_post', $this, 'quick_edit_save_custom_fields', 10, 2 );
            $this->loader->add_action( 'trashed_post'  , $this, 'trash'   );
            $this->loader->add_action( 'untrashed_post', $this, 'untrash' );
        }
        $this->loader->add_action( 'wp_head', $this, 'add_authorship_meta', -1 );
        if ( \molongui_authorship_byline_takeover() )
        {
            $this->loader->add_filter( 'the_author', $this, 'maybe_filter_name', 999, 1 );
            $this->loader->add_filter( 'get_the_author_ID', $this, 'maybe_filter_the_author_ID', 999, 3 );
            $this->loader->add_filter( 'get_the_author_display_name', $this, 'maybe_filter_the_author_display_name', 999, 3 );
            $this->loader->add_filter( 'get_the_author_description', $this, 'maybe_filter_the_author_description', 999, 3 );
            $this->loader->add_filter( 'get_the_archive_title', $this, 'filter_archive_title', 999, 1 );
            $this->loader->add_filter( 'get_the_archive_description', $this, 'filter_archive_description', 999, 1 );
            $this->loader->add_filter( 'author_link', $this, 'maybe_filter_link', 999, 1 );
            $this->loader->add_filter( 'pre_get_avatar_data', $this, 'filter_avatar', 999, 2 );
            $this->loader->add_filter( 'get_usernumposts', $this, 'filter_post_count', 999, 4 );
        }
    }
    private function init()
    {
        $this->loader = Loader::get_instance();
    }
    public function add_authorship_meta()
    {
        global $post;
        $settings = \get_option( MOLONGUI_AUTHORSHIP_SEO_SETTINGS );
        if ( empty( $post ) or empty( $post->ID ) ) return;
        if ( !$settings['add_html_meta'] and !$settings['add_opengraph_meta'] and !$settings['add_facebook_meta'] and !$settings['add_twitter_meta'] ) return;
        $authors = \get_post_authors( $post->ID );
        if ( empty( $authors ) ) return;
        if ( MOLONGUI_AUTHORSHIP_IS_PRO ) $meta = "\n<!-- Author Meta Tags by Molongui Authorship Premium " . MOLONGUI_AUTHORSHIP_VERSION . ", visit: " . MOLONGUI_AUTHORSHIP_WEB . " -->\n";
        else $meta = "\n<!-- Author Meta Tags by Molongui Authorship " . MOLONGUI_AUTHORSHIP_VERSION . ", visit: https://wordpress.org/plugins/molongui-authorship/ -->\n";
        if ( \is_author() or \is_guest_author() )
        {
            global $wp_query;
            if ( \is_guest_author() )
            {
                $author_id = isset( $wp_query->guest_author_id ) ? $wp_query->guest_author_id : $wp_query->query_vars['author'];
                $author    = new Author( $author_id, 'guest' );
            }
            else
            {
                $author = new Author( $wp_query->get( 'author' ), 'user' );
            }
            if ( !empty( $settings['add_html_meta'] ) ) $meta .= '<meta name="author" content="'.$author->get_name().'">'."\n";
            if ( !empty( $settings['add_opengraph_meta'] ) ) $meta .= $this->add_opengraph_archive_meta();
        }
        elseif ( \is_singular() )
        {
            switch ( $settings['multi_author_meta'] )
            {
                case 'main':
                    if ( !$main_author = \get_main_author( $post->ID ) ) return;
                    $author = new Author( $main_author->id, $main_author->type );
                    if ( !empty( $settings['add_html_meta'] ) ) $meta .= '<meta name="author" content="'.$author->get_name().'">'."\n";
                    if ( !empty( $settings['add_facebook_meta'] ) ) $meta .= $this->add_facebook_author_meta( $author );
                    if ( !empty( $settings['add_twitter_meta'] ) ) $meta .= $this->add_twitter_author_meta( $author );
                    if ( !empty( $settings['add_opengraph_meta'] ) and empty( $settings['add_facebook_meta'] ) ) $meta .= $this->add_opengraph_author_meta( $author );

                break;

                case 'aio':
                    if ( !empty( $settings['add_html_meta'] ) ) $meta .= '<meta name="author" content="'.\mount_byline( $authors, \count( $authors ) ).'">'."\n";

                    foreach ( $authors as $auth )
                    {
                        $author = new Author( $auth->id, $auth->type );
                        if ( !empty( $settings['add_facebook_meta'] ) ) $meta .= $this->add_facebook_author_meta( $author );
                        if ( !empty( $settings['add_twitter_meta'] ) ) $meta .= $this->add_twitter_author_meta( $author );
                        if ( !empty( $settings['add_opengraph_meta'] ) and empty( $settings['add_facebook_meta'] ) ) $meta .= $this->add_opengraph_author_meta( $author );
                    }

                break;

                case 'many':
                default:

                    foreach ( $authors as $auth )
                    {
                        $author = new Author( $auth->id, $auth->type );
                        if ( !empty( $settings['add_html_meta'] ) ) $meta .= '<meta name="author" content="'.$author->get_name().'">'."\n";
                        if ( !empty( $settings['add_facebook_meta'] ) ) $meta .= $this->add_facebook_author_meta( $author );
                        if ( !empty( $settings['add_twitter_meta'] ) ) $meta .= $this->add_twitter_author_meta( $author );
                        if ( !empty( $settings['add_opengraph_meta'] ) and empty( $settings['add_facebook_meta'] ) ) $meta .= $this->add_opengraph_author_meta( $author );
                    }

                break;
            }
        }

        $meta .= "<!-- /Molongui Authorship -->\n\n";

        echo $meta;
    }
    public function add_facebook_author_meta( $author )
    {
        $meta = '';
        $fb = $author->get_meta( 'facebook' );
        if ( !empty( $fb ) ) $meta .= '<meta property="article:author" content="' . ( ( \strpos( $fb, 'http' ) === false ) ? 'https://www.facebook.com/' : '' ) . $fb . '" />' . "\n";

        return $meta;
    }
    public function add_twitter_author_meta( $author )
    {
        $meta = '';
        $tw = $author->get_meta( 'twitter' );
        if ( !empty( $tw ) ) $meta .= '<meta name="twitter:creator" content="' . $tw . '" />' . "\n";

        return $meta;
    }
    public function add_opengraph_author_meta( $author )
    {
        $meta = '';
        $meta .= '<meta property="article:author" content="' . $author->get_name() . '" />' . "\n";

        return $meta;
    }
    public function add_opengraph_archive_meta()
    {
        global $wp_query;
        $author_id   = null;
        $author_type = null;
        if ( !isset( $wp_query ) ) return;
        if ( \is_guest_author() )
        {
            $author_id   = isset( $wp_query->guest_author_id ) ? $wp_query->guest_author_id : $wp_query->query_vars['author'];
            $author_type = 'guest';
        }
        else
        {
            $author_id   = $wp_query->get( 'author' );
            $author_type = 'user';
        }
        if ( empty( $author_id ) and empty( $author_type ) ) return;
        $author = new Author( $author_id, $author_type );
        $author_name   = $author->get_name();
        $author_first  = $author->get_meta( 'first_name' );
        $author_last   = $author->get_meta( 'last_name' );
        $author_bio    = \esc_html( $author->get_bio() );
        $author_link   = $author->get_url();
        $author_avatar = $author->get_avatar( 'full', 'url', 'local' );

        $og  = '';
        $og .= '<meta property="og:type" content="profile" />' . "\n";
        $og .= ( $author_link   ? '<meta property="og:url" content="'.$author_link.'" />'."\n" : '' );
        $og .= ( $author_avatar ? '<meta property="og:image" content="'.$author_avatar.'" />'."\n" : '' );
        $og .= ( $author_bio    ? '<meta property="og:description" content="'.$author_bio.'" />'."\n" : '' );
        $og .= ( $author_first  ? '<meta property="profile:first_name" content="'.$author_first.'" />'."\n" : '' );
        $og .= ( $author_last   ? '<meta property="profile:last_name" content="'.$author_last.'" />'."\n" : '' );
        $og .= ( $author_name   ? '<meta property="profile:username" content="'.$author_name.'" />'."\n" : '' );

        return $og;
    }
    public function maybe_filter_name( $display_name )
    {
        if ( \molongui_is_request( 'admin' ) ) return $display_name;
        global $post;
        if ( empty( $post ) or !$post->ID ) return $display_name;
        $dbt = \debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
        if ( empty( $dbt ) ) return $display_name;
        $old_display_name = $display_name;
        $filter = true;
        $args = array( 'class' => $this, 'post' => $post, 'dbt' => $dbt );
if ( \apply_filters( 'molongui_authorship_dont_filter_name', false, $display_name, $post, $dbt ) ) return $display_name;
$args = array( 'class' => $this, 'display_name' => &$display_name, 'post' => $post, 'dbt' => $dbt );
if ( \apply_filters_ref_array( 'molongui_authorship_do_filter_name', array( false, &$args ) ) ) return $display_name;

        /*!
         * PRIVATE FILTER.
         *
         * For internal use only. Not intended to be used by plugin or theme developers.
         * Future compatibility NOT guaranteed.
         *
         * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
         * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
         * or deprecation phase.
         *
         * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
         * and knowing that it could cause code failure.
         */
        list( $filter, $display_name ) = \apply_filters( '_authorship/filter/the_author', array( true, $display_name ), $args );
        if ( !$filter ) return \is_null( $display_name ) ? $old_display_name : $display_name;
        if ( \is_author() or \is_guest_author() ) return $display_name;
        return $this->filter_name();
    }
    public function maybe_filter_the_author_ID( $id, $user_id = null, $original_user_id = null )
    {
        if ( ( \is_author() or \is_guest_author() ) and !\in_the_loop() )
        {
            global $wp_query;

            $author_id = isset( $wp_query->guest_author_id ) ? $wp_query->guest_author_id : $wp_query->query_vars['author'];
            return $author_id;
        }
        return $id;
    }
    public function maybe_filter_the_author_display_name( $display_name, $user_id = null, $original_user_id = null )
    {
        if ( ( !empty( $original_user_id ) or $original_user_id === 0 ) and !\apply_filters( 'molongui_authorship_bypass_original_user_id_if', false ) ) return $display_name;
        if ( \molongui_is_request( 'admin' ) ) return $display_name;
        global $post;
        if ( empty( $post ) or !$post->ID ) return $display_name;
        $post_id = \apply_filters( 'molongui_authorship_filter_the_author_display_name_post_id', $post->ID, $post, $display_name );
        $dbt = \debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
        if ( empty( $dbt ) ) return $display_name;
        if ( \apply_filters( 'molongui_authorship_dont_filter_the_author_display_name', false, $display_name, $user_id, $original_user_id, $post, $dbt ) ) return $display_name;
        if ( \is_author() or \is_guest_author() ) return $display_name;
        return $this->filter_name( $post_id );
    }
    public function maybe_filter_the_author_description( $description, $user_id = null, $original_user_id = null )
    {
        global $wp_query;
        if ( \apply_filters( 'authorship/get_the_author_description/skip', false, $description, $user_id, $original_user_id ) ) return $description;
        if ( !\is_author() and !\is_guest_author() ) return $description;
        if ( \is_guest_author() and isset( $wp_query->guest_author_id ) ) return \get_post_field( 'post_content', $wp_query->guest_author_id );
        if ( $wp_query->query_vars['author'] )
        {
            $user = new Author( $wp_query->query_vars['author'], 'user', false, false );
            return $user->get_bio();
        }
        return $description;
    }
    public function filter_archive_title( $title )
    {
        global $wp_query;
        if ( !\is_author() and !\is_guest_author() ) return $title;
        $options = \get_option( MOLONGUI_AUTHORSHIP_ARCHIVES_SETTINGS, '' );
        if ( \is_guest_author() and isset( $wp_query->guest_author_id ) )
        {
            $prefix  = !empty( $options['guest_archive_title_prefix'] ) ? $options['guest_archive_title_prefix'] : '';
            $suffix  = !empty( $options['guest_archive_title_suffix'] ) ? $options['guest_archive_title_suffix'] : '';

            return $prefix . ' ' . \get_post_field( 'post_title', $wp_query->guest_author_id ) . ' ' . $suffix;
        }
        if ( $wp_query->query_vars['author'] )
        {
            \add_filter( '_authorship/filter/get_user_by', '__return_list_false' );
            $user = \get_user_by( 'id', $wp_query->query_vars['author'] );
            \remove_filter( '_authorship/filter/get_user_by', '__return_list_false' );
            $prefix  = !empty( $options['user_archive_title_prefix'] ) ? $options['user_archive_title_prefix'] . ' ' : '';
            $suffix  = !empty( $options['user_archive_title_suffix'] ) ? ' ' . $options['user_archive_title_suffix'] : '';
            return $prefix . $user->display_name . $suffix;
        }
        return $title;
    }
    public function filter_archive_description( $description )
    {
        global $wp_query;
        if ( !\is_author() and !\is_guest_author() ) return $description;
        if ( \is_guest_author() and isset( $wp_query->guest_author_id ) ) return \get_post_field( 'post_content', $wp_query->guest_author_id );
        if ( $wp_query->query_vars['author'] )
        {
            $user = new Author( $wp_query->query_vars['author'], 'user', false, false );

            return $user->get_bio();
        }
        return $description;
    }
    public function filter_name( $pid = null )
    {
        return \get_byline( $pid );
    }
    public function maybe_filter_link( $link )
    {
        if ( \molongui_is_request( 'admin' ) ) return $link;
        $dbt = \debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 8 );
        if ( empty( $dbt ) ) return $link;
        if ( \apply_filters( 'molongui_authorship_dont_filter_link', false, $link, $dbt ) ) return $link;
        $args = array( 'class' => $this, 'link' => &$link, 'dbt' => $dbt );
        if ( \apply_filters_ref_array( 'molongui_authorship_do_filter_link', array( false, &$args ) ) ) return $link;
        return $this->filter_link( $link );
    }
    public function filter_link( $link, $post_id = null )
    {
        $settings = \molongui_get_plugin_settings( MOLONGUI_AUTHORSHIP_ID, array( 'byline', 'archives' ) );
        if ( empty( $settings['byline_name_link'] ) ) return '#molongui-disabled-link';
        if ( empty( $post_id ) )
        {
            global $post;
            if ( empty( $post ) ) return $link;
            if ( !$post->ID or $post->ID == 0 ) return $link;
            $post_id = \apply_filters( 'molongui_authorship_filter_link_post_id', $post->ID, $post, $link );
        }
        $authors = \get_post_authors( $post_id );
        if ( !$authors ) return $link;
        $modifiers_tag = ( ( !empty( $settings['byline_prefix'] ) or !empty( $settings['byline_suffix'] ) ) and MOLONGUI_AUTHORSHIP_IS_PRO ) ? '?m_bm=true' : '';
        if ( \is_multiauthor_post( $post_id ) and !empty( $settings['byline_multiauthor_link'] ) and $settings['byline_multiauthor_display'] != 'main' )
        {
            switch ( $settings['byline_multiauthor_display'] )
            {
                case 'main':
                    $count = 1;

                break;

                case '1':
                case '2':
                case '3':
                    $count = \min( \count( $authors ), (int)$settings['byline_multiauthor_display'] );

                break;

                case 'all':
                default:

                    $count = \count( $authors );

                break;
            }
            $url = '';
            $que = '%3F'; // Encoded into a valid ASCII format: '%3F' = '?'
            $amp = '%26'; // Encoded into a valid ASCII format: '%26' = '&'

            for ( $i = 0; $i < $count; $i++ )
            {
                switch ( $i )
                {
                    case 0:
                        $function  = 'get_url';   // 'get_url' must be used so returned $link is a valid URL.
                        $default   = \home_url(); // To ensure we return a valid URL even if main author is a guest.
                        $delimiter = '';          // Do not append anything at the beginning of the returned $link.
                        $querychar = '';
                    break;

                    case 1:
                        $function  = 'get_url';                // 'get_slug' could be used to return the author nicename.
                        $default   = 'molongui-disabled-link'; // Do NOT add a leading '#'!!!
                        $disabled  = $authors[0]->type == 'guest' ? \apply_filters( '_authorship/filter/link/disable_main', true, $authors[0]->type ) : false;
                        $delimiter = $disabled ? 'molongui_byline=true'.$amp.'m_main_disabled=true'.$amp.'mca=' : 'molongui_byline=true'.$amp.'mca=';
                        $querychar = $que;
                    break;

                    default:
                        $function  = 'get_url';                // 'get_slug' could be used to return the author nicename.
                        $default   = 'molongui-disabled-link'; // Do NOT add a leading '#'!!!
                        $delimiter = 'mca=';
                        $querychar = $amp;
                    break;
                }
                $author_class = new Author( $authors[$i]->id, $authors[$i]->type );
                switch ( $authors[$i]->type )
                {
                    case 'guest':
                        $data = $author_class->$function();
                        $data = $data == '#molongui-disabled-link' ? $default : $data;

                    break;

                    case 'user':
                    default:
                        $data = $author_class->$function();
                        $data = $data == '#molongui-disabled-link' ? $default : $data;

                    break;
                }
                if ( $i === 1 )
                {
                    $tmp = parse_url( $data );
                    if ( isset( $tmp['query'] ) ) $querychar = $amp;
                }
                $url .= $querychar . $delimiter . $data;
            }
            return $url.$modifiers_tag;
        }
        if ( $authors[0]->type == 'guest' )
        {
            $author_class = new Author( $authors[0]->id, $authors[0]->type );
            $url = $author_class->get_url();
            return $url.$modifiers_tag;
        }
        if ( !\in_the_loop() and $authors[0]->type == 'user' )
        {
            $author_class = new Author( $authors[0]->id, $authors[0]->type );
            $url = $author_class->get_url();
            return $url.$modifiers_tag;
        }
        else return $link.$modifiers_tag;
    }
    public function filter_archive_link( $link )
    {
        global $wp_query;
        if ( !\is_author() and !\is_guest_author() ) return $link;
        if ( \is_guest_author() and isset( $wp_query->guest_author_id ) )
        {
            $author = new Author( $wp_query->guest_author_id, 'guest' );
            return $author->get_url();
        }
        if ( $wp_query->query_vars['author'] )
        {
            $author = new Author( $wp_query->query_vars['author'], 'user' );
            return $author->get_url();
        }
        return $link;
    }
    public function filter_avatar( $args, $id_or_email )
    {
        if ( !\molongui_authorship_is_feature_enabled( 'avatar' ) ) return $args;
        $dbt = \debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 10 );
        if ( empty( $dbt ) ) return $args;

        /*!
         * PRIVATE FILTER.
         *
         * For internal use only. Not intended to be used by plugin or theme developers.
         * Future compatibility NOT guaranteed.
         *
         * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
         * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
         * or deprecation phase.
         *
         * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
         * and knowing that it could cause code failure.
         */
        if ( \apply_filters( 'authorship/get_avatar_data/skip', false, $args, $dbt ) ) return $args;
        $email        = false;
        $author       = new \stdClass();
        $local_avatar = false;
        if ( \is_object( $id_or_email ) and isset( $id_or_email->comment_ID ) )
        {
            $id_or_email = \get_comment( $id_or_email );
        }
        if ( \is_numeric( $id_or_email ) )
        {
            $author->user = \get_user_by( 'id', \absint( $id_or_email ) );
            if ( !isset( $author->user->user_email ) ) return $args;

            $email = $author->user->user_email;
            if ( isset( $author->user->guest_id ) ) $author->guest_id = $author->user->guest_id;
        }
        elseif ( \is_string( $id_or_email ) )
        {
            if ( !$id_or_email )
            {
                if ( \is_guest_author() )
                {
                    global $wp_query;
                    $author->guest_id = $wp_query->guest_author_id;
                }
                else
                {
                    /*!
                     * PRIVATE FILTER.
                     *
                     * For internal use only. Not intended to be used by plugin or theme developers.
                     * Future compatibility NOT guaranteed.
                     *
                     * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
                     * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
                     * or deprecation phase.
                     *
                     * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
                     * and knowing that it could cause code failure.
                     */
                    $post_id = \apply_filters( '_authorship/get_avatar_data/filter/post_id', null );

                    $authors = \get_post_authors( $post_id, 'id' );
                    if ( $authors ) $author->guest_id = $authors[0];
                }
            }
            elseif ( \strpos( $id_or_email, '@md5.gravatar.com' ) )
            {
                return $args;
            }
            else
            {
                $email = $id_or_email;
            }
        }
        elseif ( $id_or_email instanceof \WP_User )
        {
            $author->user = $id_or_email;
            $email        = $author->user->user_email;
        }
        elseif ( $id_or_email instanceof \WP_Post )
        {
            $author->user = \get_user_by( 'id', (int) $id_or_email->post_author );
            $email        = $author->user->user_email;
        }
        elseif ( $id_or_email instanceof \WP_Comment )
        {
            if ( !empty( $id_or_email->comment_author_email ) )
            {
                $email = $id_or_email->comment_author_email;
            }
            elseif ( !empty( $id_or_email->user_id ) )
            {
                \add_filter( '_authorship/filter/get_user_by', '__return_list_false' );
                $author->user = \get_user_by( 'id', (int) $id_or_email->user_id );
                $email        = $author->user->user_email;
                \remove_filter( '_authorship/filter/get_user_by', '__return_list_false' );
            }
        }
        if ( !$email and ( !empty( $author->guest_id ) or !empty( $author->user->guest_id ) ) )
        {
            $author->type  = 'guest';
            $author->guest = \get_post( !empty( $author->guest_id ) ? $author->guest_id : $author->user->guest_id );
        }
        elseif ( !$email )
        {
            return $args;
        }
        elseif ( $author->user = \molongui_get_author_by( 'user_email', $email, 'user' ) )
        {
            $author->type = 'user';
        }
        elseif ( $author->guest = \molongui_get_author_by( '_molongui_guest_author_mail', $email, 'guest' ) )
        {
            $author->type = 'guest';
        }

        /*!
         * PRIVATE FILTER.
         *
         * For internal use only. Not intended to be used by plugin or theme developers.
         * Future compatibility NOT guaranteed.
         *
         * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
         * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
         * or deprecation phase.
         *
         * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
         * and knowing that it could cause code failure.
         */
        $author = \apply_filters( '_authorship/get_avatar_data/filter/author', $author, $id_or_email, $dbt );
        if ( empty( $author->type ) ) return $args;
        switch ( $author->type )
        {
            case 'user':

                $user_local_avatar = \get_user_meta( $author->user->ID, 'molongui_author_image_url', true );
                $local_avatar      = $user_local_avatar ? $user_local_avatar : '';

            break;

            case 'guest':

                $local_avatar = \has_post_thumbnail( $author->guest->ID ) ? \get_the_post_thumbnail_url( $author->guest->ID, $args['size'] ) : '';
                \add_filter( 'authorship/get_avatar_data/skip', '__return_true' );
                if ( !$local_avatar ) $local_avatar = \get_avatar_url( $email, $args );
                \remove_filter( 'authorship/get_avatar_data/skip', '__return_true' );

            break;
        }
        if ( $local_avatar )
        {
            $args['found_avatar'] = true;
            $args['url'] = \apply_filters( 'authorship/get_avatar_data/filter/url', $local_avatar, $id_or_email, $args );
        }
        return $args;
    }
    public function filter_post_count( $count, $userid, $post_type, $public_only )
    {
        $author_type = ( \is_guest_author() and !\in_the_loop() ) ? 'guest' : 'user';

        /*!
         * PRIVATE FILTERS.
         *
         * For internal use only. Not intended to be used by plugin or theme developers.
         * Future compatibility NOT guaranteed.
         *
         * Please do not rely on this filter for your custom code to work. As a private filter it is meant to be
         * used only by Molongui. It may be edited, renamed or removed from future releases without prior notice
         * or deprecation phase.
         *
         * If you choose to ignore this notice and use this filter, please note that you do so at on your own risk
         * and knowing that it could cause code failure.
         */
        $author_id   = \apply_filters( '_authorship/filter/count/author_id', $userid );
        $author_type = \apply_filters( '_authorship/filter/count/author_type', $author_type );
        $author      = new Author( $author_id, $author_type );
        $post_counts = $author->get_post_count( $post_type );
        return \array_sum( $post_counts );
    }
    public function customize_admin_columns( $columns )
    {
        $new_columns = array();
        global $post, $post_type;
        $pt = ( isset( $post->post_type ) ? $post->post_type : '' );
        if ( empty( $post->post_type ) and $post_type == 'page' ) $pt = 'page';
        if ( empty( $pt ) or $pt == 'guest_author' or !\in_array( $pt, \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all' ) ) ) return $columns;
        if ( \array_key_exists( 'author', $columns ) ) $position = \array_search( 'author', \array_keys( $columns ) );      // Default 'Author' column position.
        elseif ( \array_key_exists( 'title', $columns ) ) $position = \array_search( 'title', \array_keys( $columns ) )+1;  // After 'Title' column.
        else $position = \count( $columns );                                                                                             // Last column.
        unset( $columns['author'] );

        $i = 0;
        foreach ( $columns as $key => $column )
        {
            if ( $i == $position )
            {
                $new_columns['molongui-author'] = __( 'Authors', 'molongui-authorship' );
                if ( \molongui_authorship_is_feature_enabled( 'box' ) )
                {
                    $new_columns['molongui-box'] = __( 'Author Box', 'molongui-authorship' );
                }
            }
            ++$i;
            $new_columns[$key] = $column;
        }
        return $new_columns;
    }
    public function populate_admin_columns( $column, $ID )
    {
        if ( $column == 'molongui-author' )
        {
            $authors = \get_post_authors( $ID );
            if ( !$authors ) return;
            foreach ( $authors as $author )
            {
                if ( $author->type == 'guest' )
                {
                    $display_name = \esc_html( get_the_title( $author->id ) );
                    $edit_link    = \admin_url().'post.php?post='.$author->id.'&action=edit';
                    $author_tag   = __( 'guest', 'molongui-authorship' );
                }
                else
                {
                    $user         = \get_userdata( $author->id );
                    $display_name = \esc_html( $user->display_name );
                    $edit_link    = \admin_url().'user-edit.php?user_id='.$author->id;
                    $author_tag   = __( 'user', 'molongui-authorship' );
                }

                ?>
                <p data-author-id="<?php echo $author->id; ?>" data-author-type="<?php echo $author->type; ?>" data-author-display-name="<?php echo $display_name; ?>" class="" style="margin:0 0 2px;">
                    <a href="<?php echo $edit_link; ?>">
                        <?php echo $display_name; ?>
                    </a>
                    <?php if ( \molongui_authorship_is_feature_enabled( 'guest' ) ) : ?>
                        <span style="font-family: 'Courier New', Courier, monospace; font-size: 81%; color: #a2a2a2;" >
                            [<?php echo $author_tag; ?>]
                        </span>
                    <?php endif; ?>
                </p>
                <?php
            }

            return;
        }
        elseif ( $column == 'molongui-box' )
        {
            $settings = \get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
            $value    = \get_post_meta( $ID, '_molongui_author_box_display', true );
            if ( empty( $value ) or $value == 'default' )
            {
                switch( $settings['display'] )
                {
                    case 'hide' : $pts = array(); break;
                    case 'show' : $pts = \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID ); break;
                    case 'posts': $pts = array( 'post' ); break;
                    case 'pages': $pts = array( 'page' ); break;
                }
                global $post, $post_type;
                $pt = ( isset( $post->post_type ) ? $post->post_type : '' );
                if ( empty( $post->post_type ) and $post_type == 'page' ) $pt = 'page';
                if ( \in_array( $pt, $pts) ) $icon = 'show';
                else $icon = 'hide';
            }
            else
            {
                $icon = $value;
            }

            echo '<div id="box_display_'.$ID.'" data-display-box="'.$value.'">'.'<i data-tip="'.( $icon == 'show' ? __( 'Show', 'molongui-authorship' ) : __( 'Hide', 'molongui-authorship' ) ).'" class="m-a-icon-'.$icon.' molongui-tip"></i>'.'</div>';
            return;
        }
    }
    public function quick_edit_remove_author()
    {
        global $pagenow, $post_type;

        if ( 'edit.php' == $pagenow and \molongui_authorship_byline_takeover() and \molongui_is_post_type_enabled( MOLONGUI_AUTHORSHIP_ID, $post_type, \molongui_enabled_post_screens( MOLONGUI_AUTHORSHIP_ID, 'all' ) ) )
        {
            \remove_post_type_support( $post_type, 'author' );
        }
    }
    public function	quick_edit_add_custom_fields( $column_name, $post_type )
    {
        if ( !\molongui_authorship_byline_takeover() ) return;
        if ( !\molongui_is_post_type_enabled( MOLONGUI_AUTHORSHIP_ID, $post_type, \molongui_enabled_post_screens( MOLONGUI_AUTHORSHIP_ID, 'all' ) ) ) return;
        if ( $column_name == 'molongui-author' )
        {
            \wp_nonce_field( 'molongui_authorship_quick_edit_nonce', 'molongui_authorship_quick_edit_nonce' );

            ?>
            <br class="clear" />
            <fieldset class="inline-edit-col-left">
                <div class="inline-edit-col">
                    <h4><?php _e( 'Authorship data', 'molongui-authorship' ); ?></h4>
                    <div class="inline-edit-group wp-clearfix">
                        <label class="inline-edit-authors alignleft" style="width: 100%;">
                            <span class="title"><?php \molongui_authorship_is_feature_enabled( 'multi' ) ? _e( 'Authors', 'molongui-authorship' ) : _e( 'Author' ); ?></span>
                            <div id="molongui-author-selectr" style="margin-left: 6em;">
                                <?php echo $this->get_dropdown_authors( 'authors', array( 'selected' => '' ) ); ?>
                            </div>
                        </label>
                    </div>
                </div>
            </fieldset>
            <?php
        }
        elseif ( $column_name == 'molongui-box' )
        {
            \wp_nonce_field( 'molongui_authorship_quick_edit_nonce', 'molongui_authorship_quick_edit_nonce' );

            ?>
            <br class="clear" />
            <fieldset class="inline-edit-col-left">
                <div class="inline-edit-col">
                    <div class="inline-edit-group wp-clearfix">
                        <label class="inline-edit-box-display alignleft">
                            <span class="title"><?php _e( 'Author box', 'molongui-authorship' ); ?></span>
                            <select name="_molongui_author_box_display">
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
    }
    public function quick_edit_populate_custom_fields()
    {
        if ( !\molongui_authorship_byline_takeover() ) return;
        $current_screen = \get_current_screen();
        if ( \substr( $current_screen->id, 0, \strlen( 'edit-' ) ) != 'edit-' or !\in_array( $current_screen->id, \molongui_enabled_post_screens( MOLONGUI_AUTHORSHIP_ID, 'all' ) ) ) return;
        \wp_enqueue_script( 'jquery' );
        ?>
        <script type="text/javascript">
            jQuery(function($)
            {
                var $inline_editor = inlineEditPost.edit;
                inlineEditPost.edit = function(id)
                {
                    $inline_editor.apply(this, arguments);
                    var post_id = 0;
                    if (typeof(id) === 'object') post_id = parseInt(this.getId(id));
                    if (post_id !== 0)
                    {
                        var $q_editor = $('#edit-' + post_id);
                        var $post_row = $('#post-' + post_id);
                        var authorSelect = document.getElementById('_molongui_author');
                        var authorList = $q_editor.find('ul#molongui_authors');
                        var container = document.getElementById('molongui-author-selectr');
                        if (container.hasChildNodes()) container.removeChild(container.firstElementChild);
                        container.prepend(authorSelect);
                        $.molonguiInitAuthorSelector(authorSelect, authorList, '');
                        <?php if ( \molongui_authorship_is_feature_enabled( 'multi' ) ) : ?>
                            authorList.empty();
                            $post_row.find('.molongui-author p').each(function(index, item)
                            {
                                var $ref = $(item).data('author-type') + '-' + $(item).data('author-id');
                                var $li  = '<li data-value="' + $ref + '">' + $(item).data('author-display-name') + '<input type="hidden" name="molongui_authors[]" value="' + $ref + '" /><span class="dashicons dashicons-trash molongui-tip js-remove" data-tip="' + authorship.remove_author_tip + '"></span></li>';
                                authorList.append($li);
                            });

                        <?php else : ?>
                            $post_row.find('.molongui-author p').each(function(index, item)
                            {
                                $q_editor.find('.selectr-selected .selectr-label').text($(item).data('author-display-name'));
                                var $ref = $(item).data('author-type') + '-' + $(item).data('author-id');
                                $q_editor.find('#_molongui_author').each(function(i)
                                {
                                    $(this).find('option').attr("selected",false);
                                    $(this).find('option[value='+$ref+']').attr("selected",true);
                                    $(this).val($ref);
                                });
                            });

                        <?php endif; ?>
                        var $box_display = $('#box_display_' + post_id).data('display-box');
                        if ($box_display === '') $box_display = 'default';
                        $q_editor.find('[name="_molongui_author_box_display"]').val($box_display);
                        $q_editor.find('[name="_molongui_author_box_display"]').children('[value="' + $box_display + '"]').attr('selected', true);
                    }
                };
            });
        </script>
        <?php
    }
    public function quick_edit_save_custom_fields( $post_id, $post )
    {
        if ( !isset( $_POST['molongui_authorship_quick_edit_nonce'] ) or !\wp_verify_nonce( $_POST['molongui_authorship_quick_edit_nonce'], 'molongui_authorship_quick_edit_nonce' ) ) return;
        if ( \defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) return;
        if ( !\molongui_authorship_byline_takeover() ) return;
        if ( !\in_array( $post->post_type, \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all' ) ) ) return;
        if ( !\current_user_can( 'edit_post', $post_id ) ) return;
        $this->save_authors( $_POST, $post_id, __CLASS__, __FUNCTION__ );
        if ( isset( $_POST['_molongui_author_box_display'] ) ) \update_post_meta( $post_id, '_molongui_author_box_display', $_POST['_molongui_author_box_display'] );
    }
    public function remove_author_metabox()
    {
        $post_types = \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all' );
        foreach ( $post_types as $post_type ) \remove_meta_box( 'authordiv', $post_type, 'normal' );
    }
    public function add_meta_boxes( $post_type )
    {
        if ( !\current_user_can( 'edit_others_pages' ) and !\current_user_can( 'edit_others_posts' ) ) return;
        $post_types = \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID, 'all' );
        if ( \in_array( $post_type, $post_types ) )
        {
            \add_meta_box
            (
                'authorboxdiv'
                ,__( "Authors", 'molongui-authorship' )
                ,array( $this, 'render_author_metabox' )
                ,$post_type
                ,'side'
                ,'high'
            );
            if ( \molongui_authorship_is_feature_enabled( 'box' ) )
            {
                \add_meta_box
                (
                    'showboxdiv'
                    ,__( "Authorship Box", 'molongui-authorship' )
                    ,array( $this, 'render_box_metabox' )
                    ,$post_type
                    ,'side'
                    ,'high'
                );
            }
        }
    }
    public function render_author_metabox( $post )
    {
        \wp_nonce_field( 'molongui_authorship_post', 'molongui_authorship_post_nonce' );
        if ( \molongui_authorship_is_feature_enabled( 'multi' ) )
        {
            if ( \molongui_authorship_is_feature_enabled( 'guest' ) )
            {
                $desc    = __( "Add as many authors as needed by selecting them from the dropdown below. Drag to change their order and click on trash icon to remove them. First listed author will be the main author.", 'molongui-authorship' );
                $select  = $this->get_dropdown_authors( 'authors', array( 'mutli' => true, 'selected' => '' ) );
                $add_new = __( "+ Add new guest", 'molongui-authorship' );
            }
            else
            {
                $desc   = __( "Add as many authors as needed by selecting them from the dropdown below. Drag to change their order and click on trash icon to remove them. First listed author will be the main author.", 'molongui-authorship' );
                $select = $this->get_dropdown_authors( 'users', array( 'mutli' => true, 'selected' => '' ) );
            }
        }
        else
        {
            $enable_coauthors_link = \admin_url( 'admin.php?page=molongui-authorship&tab=molongui_authorship_main' );
            if ( \molongui_authorship_is_feature_enabled( 'guest' ) )
            {
                $desc    = \sprintf( __( "Select an author for this post. Or enable the %sMulti-Author%s feature to add as many authors as needed.", 'molongui-authorship' ), '<strong><a href="'.$enable_coauthors_link.'" target="_blank">', '</a></strong>' );
                $author  = \get_main_author( $post->ID );
                $select  = $this->get_dropdown_authors( 'authors', array( 'mutli' => false, 'selected' => $author->ref ) );
                $add_new = __( "+ Add new guest", 'molongui-authorship' );
            }
            else
            {
                $desc   = \sprintf( __( "Select a user as author for this post. Or enable the %sMulti-Author%s feature to add as many authors as needed or the %sGuest Author%s feature to add contributors without adding new real users.", 'molongui-authorship' ), '<strong><a href="'.$enable_coauthors_link.'" target="_blank">', '</a></strong>', '<strong><a href="" target="_blank">', '</a></strong>' );
                $author = $post->post_author ? $post->post_author : \get_current_user_id();
                $select = $this->get_dropdown_authors( 'users', array( 'mutli' => false, 'selected' => 'user-'.$author ) );
            }
        }
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/post/html-author-metabox.php';
    }
    public function render_box_metabox( $post )
    {
        $screen = \get_current_screen();
        $author_box_display  = \get_post_meta( $post->ID, '_molongui_author_box_display', true );
        $author_box_position = \get_post_meta( $post->ID, '_molongui_author_box_position', true );
        if ( empty( $author_box_display ) )  $author_box_display  = 'default';
        if ( empty( $author_box_position ) ) $author_box_position = 'default';
        include MOLONGUI_AUTHORSHIP_DIR . 'admin/views/post/html-box-metabox.php';
    }
    public function get_dropdown_authors( $type = 'authors', $args = array() )
    {
        global $post;
        \extract( \array_merge( array
        (
            'multi'    => \molongui_authorship_is_feature_enabled( 'multi' ),
            'guest'    => \molongui_authorship_is_feature_enabled( 'guest' ),
            'selected' => '',
        ), $args ) );
        $authors = \molongui_get_authors( $type );
        $html = '';
        if ( empty( $authors ) )
        {
            $html .= '<div><p>'.__( 'No authors found.', 'molongui-authorship' ).'</p></div>';
        }
        else
        {
            if ( $multi )
            {
                $html .= '<select id="_molongui_author" name="_molongui_author" class="searchable" data-placeholder="'.__( 'Add an(other) author...', 'molongui-authorship' ).'">';
                foreach ( $authors as $author ) $html .= '<option value="'.$author['ref'].'" data-type="['.$author['type'].']">' . $author['name'] . '</option>';
            }
            else
            {
                $html .= '<select id="_molongui_author" name="_molongui_author" class="searchable" data-placeholder="'.__( 'Add an author...', 'molongui-authorship' ).'">';
                if ( !$selected )
                {
                    $main_author = \get_main_author( $post->ID );
                    $selected    = $main_author->ref;
                }
                foreach ( $authors as $author ) $html .= '<option value="'.$author['ref'].'" data-type="['.$author['type'].']"'.\selected( $author['type'].'-'.$author['id'], $selected, false ).'>' . $author['name'] . '</option>';
            }
            $html .= '</select>';
        }
        if ( !$multi ) return $html;

        $html .= '<div class="block__list block__list_words"><ul id="molongui_authors">';
        $post_authors = \get_post_authors( $post->ID );

        if ( $post_authors )
        {
            foreach ( $post_authors as $author )
            {
                if ( $type == 'users' and $author->type == 'guest' ) continue;
                $author_class = new Author( $author->id, $author->type );
                $html .= '<li data-post="'.$post->ID.'" data-value="'.$author->ref.'">'.$author_class->get_name().'<input type="hidden" name="molongui_authors[]" value="'.$author->ref.'" /><span class="dashicons dashicons-trash molongui-tip js-remove" data-tip="'.__( 'Remove author from selection', 'molongui-authorship' ).'"></span></li>';
            }
        }

        $html .= '</ul></div>';
        return $html;
    }
    public function update_post_author( $data, $postarr, $unsanitized_postarr )
    {
        if ( !isset( $data['post_type'] ) or !\molongui_is_post_type_enabled( MOLONGUI_AUTHORSHIP_ID, $data['post_type'] ) ) return $data;
        if ( !\molongui_authorship_byline_takeover() ) return $data;
        $current_author  = !empty( $postarr['post_author'] ) ? $postarr['post_author'] : false;
        $new_post_author = false;
        if ( !empty( $postarr['molongui_authors'] ) ) foreach ( $postarr['molongui_authors'] as $author )
        {
            $split = \explode( '-', $author );
            if ( $split[0] == 'user' )
            {
                $new_post_author = $split[1];
                break;
            }
        }
        elseif ( !empty( $postarr['_molongui_author'] ) )
        {
            $split = \explode( '-', $postarr['_molongui_author'] );
            if ( $split[0] == 'user' )
            {
                $new_post_author = $split[1];
            }
        }
        if ( !$new_post_author )
        {
            if ( $current_author ) $new_post_author = $current_author;
            else $new_post_author = \get_current_user_id();
        }
        $data['post_author'] = $new_post_author;
        return $data;
    }
    public function post_status( $post_id )
    {
        $status = \get_post_status( $post_id );

        \add_filter( 'authorship/post/save/previous/status', function() use ( $status )
        {

            return $status;
        });
    }
    public function save_authors( $data, $post_id, $class = '', $fn = '' )
    {
        $old_post_status  = \apply_filters( 'authorship/post/save/previous/status', 'publish' );
        $new_post_status  = \get_post_status( $post_id );
        $old_post_authors = \get_post_meta( $post_id, '_molongui_author', false );
        $new_post_authors = \molongui_authorship_is_feature_enabled( 'multi' ) ? $data['molongui_authors'] : array( $data['_molongui_author'] );
        $did_author_change = isset( $new_post_authors ) ? !\molongui_are_arrays_equal( $old_post_authors, $new_post_authors ) : true;
        $did_status_change = ( ( $new_post_status !== $old_post_status ) and !( \in_array( $old_post_status, array( 'publish', 'private' ) ) and \in_array( $new_post_status, array( 'publish', 'private' ) ) ) );
        if ( !$did_author_change and !$did_status_change ) return;
        if ( !$did_author_change and $did_status_change ) goto update_authorship_counters;
        if ( empty( $new_post_authors ) and \in_array( $data['post_type'], \molongui_supported_post_types( MOLONGUI_AUTHORSHIP_ID ) ) )
        {
            $current_user        = \wp_get_current_user();
            $new_post_authors[0] = 'user-'.$current_user->ID;
        }
        elseif ( empty( $new_post_authors ) )
        {
            $new_post_authors[0] = 'user-'.$data['post_author'];
        }
        \delete_post_meta( $post_id, '_molongui_author' );
        foreach ( $new_post_authors as $author )
        {
            \add_post_meta( $post_id, '_molongui_author', $author, false );
        }
        \update_post_meta( $post_id, '_molongui_main_author', $new_post_authors[0] );
        update_authorship_counters:
        if ( $did_status_change )
        {
            if ( \in_array( $new_post_status, array( 'publish', 'private' ) ) )
            {
                \authorship_increment_post_counter( $data['post_type'], $new_post_authors );
            }
            elseif ( \in_array( $old_post_status, array( 'publish', 'private' ) ) )
            {
                \authorship_decrement_post_counter( $data['post_type'], $old_post_authors );
            }
        }
        else
        {
            $removed = \array_diff( $old_post_authors, $new_post_authors );
            if ( !empty( $removed ) ) \authorship_decrement_post_counter( $data['post_type'], $removed );
            $added = \array_diff( $new_post_authors, $old_post_authors );
            if ( !empty( $added ) ) \authorship_increment_post_counter( $data['post_type'], $added );
        }
    }
    public function save( $post_id, $post )
    {
		if ( \is_null( $post_id ) or empty( $_POST ) ) return;
        if ( \defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) return;
        if ( \wp_is_post_revision( $post_id ) !== false ) return;
        if ( !isset( $_POST['post_type'] ) ) return;
        if ( 'page' == $_POST['post_type'] ) if ( !\current_user_can( 'edit_page', $post_id ) ) return;
        elseif ( !\current_user_can( 'edit_post', $post_id ) ) return;
        if ( !isset( $_POST['molongui_authorship_post_nonce'] ) or !\wp_verify_nonce( $_POST['molongui_authorship_post_nonce'], 'molongui_authorship_post' ) ) return;
        if ( (int)$_POST['post_ID'] !== (int)$post_id ) return;

        global $current_screen;
        $config = require MOLONGUI_AUTHORSHIP_DIR . 'config/config.php';
        if ( $config['cpt']['guest'] == $current_screen->post_type ) return $post_id;
        $this->save_authors( $_POST, $post_id );
        \update_post_meta( $post_id, '_molongui_author_box_display', $_POST['_molongui_author_box_display'] );
        \update_post_meta( $post_id, '_molongui_author_box_position', $_POST['_molongui_author_box_position'] );
        $this->clear_object_cache();

        return $post_id;
    } // save
    public function trash( $post_id )
    {
        $this->clear_object_cache();
        if ( \in_array( get_post_meta( $post_id, '_wp_trash_meta_status' ), array( 'publish', 'private' ) ) )
        {
            \authorship_decrement_post_counter( \get_post_type( $post_id ), \get_post_authors( $post_id, 'ref' ) );
        }
    }
    public function untrash( $post_id )
    {
        $this->clear_object_cache();
        if ( \in_array( get_post_meta( $post_id, '_wp_trash_meta_status' ), array( 'publish', 'private' ) ) )
        {
            \authorship_increment_post_counter( \get_post_type( $post_id ), \get_post_authors( $post_id, 'ref' ) );
        }
    }
    public function delete( $post_id )
    {
    }
    private function clear_object_cache()
    {
        \molongui_clear_object_cache( 'posts' );
    }

} // class