<?phpnamespace Molongui\Authorship\Admin;use Molongui\Authorship\Fw\Includes\Loader;use Molongui\Authorship\Includes\Post;use Molongui\Authorship\Includes\User;use Molongui\Authorship\Includes\Guest;defined( 'ABSPATH' ) or exit;class Admin{    private $loader;	private $vars;    public function __construct()    {        $this->init();        $this->hook();    }    private function init()    {        $this->loader = Loader::get_instance();        $this->vars = new \stdClass();        $this->vars->config = include( MOLONGUI_AUTHORSHIP_DIR . 'config/config.php' );    }	private function hook()	{        $this->hook_admin();        $this->hook_user();	}    private function hook_admin()    {        $this->loader->add_action( 'init', $this, 'init_plugin_settings' );        $this->loader->add_action( 'init', $this, 'update_post_counters' );        $this->loader->add_action( 'admin_notices', $this, 'post_counters_update_completed' );        $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );        $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts', 99 );    }    private function hook_user()    {        new User();    }    public function init_plugin_settings()    {        molongui_authorship_add_default_settings();    }    public function update_post_counters()    {        if ( get_option( 'molongui_authorship_update_post_counters' ) )        {            delete_option( 'molongui_authorship_update_post_counters' );            authorship_update_post_counters();        }    }    public function post_counters_update_completed()    {        if ( get_option( 'm_update_post_counters_complete' ) )        {            delete_option( 'm_update_post_counters_complete' );            delete_option( 'm_update_post_counters_running'  );            $message = '<p>' . sprintf( __( '%sAuthorship Data Updater%s - The database update process is now complete. Thank you for updating %s to the latest version!', 'molongui-authorship' ), '<strong>', '</strong>', MOLONGUI_AUTHORSHIP_TITLE ) . '</p>';            echo '<div class="notice notice-success is-dismissible">' . $message . '</div>';        }        elseif ( get_option( 'm_update_post_counters_running' ) )        {            $message = '<p>' . sprintf( __( '%sAuthorship Data Updater%s - Database update process is running in the background.', 'molongui-authorship' ), '<strong>', '</strong>' ) . '</p>';            echo '<div class="notice notice-warning is-dismissible">' . $message . '</div>';        }    }    public function enqueue_styles()    {	    $screen = get_current_screen();	    if ( in_array( $screen->id, array( 'profile', 'users', 'user', 'user-edit' ) ) )        {            if ( molongui_authorship_is_feature_enabled( 'avatar' ) )            {                if ( current_user_can( 'upload_files' ) )                {                    $file = 'fw/'.'admin/css/mcf-media-uploader.min.css';                    if ( is_rtl() ) $file = 'fw/'.'admin/css/mcf-media-uploader-rtl.min.css';                    if ( file_exists( MOLONGUI_AUTHORSHIP_DIR.$file ) )                    {                        wp_enqueue_style( 'mcf-media-uploader', MOLONGUI_AUTHORSHIP_URL.$file, array(), MOLONGUI_AUTHORSHIP_VERSION, 'all' );                        wp_add_inline_style( 'mcf-media-uploader', '.user-profile-picture { display: none; }' );                    }                }            }        }	    $screens = array_merge        (            molongui_enabled_post_screens( MOLONGUI_AUTHORSHIP_ID, 'all' ),         // Enabled post screens.            array            (                'profile', 'users', 'user', 'user-edit',                                           // User screens.                $this->vars->config['cpt']['guest'], 'edit-'.$this->vars->config['cpt']['guest'],  // Guest screens.                'molongui_page_molongui-authorship'                                                // Plugin settings page.            )        );        if ( in_array( $screen->id, $screens ) )        {            $file = 'admin/css/molongui-authorship-admin.7144.min.css';            if ( is_rtl() ) $file = 'admin/css/molongui-authorship-admin-rtl.0498.min.css';            if ( file_exists( MOLONGUI_AUTHORSHIP_DIR.$file ) )            {                wp_enqueue_style( 'molongui-authorship-admin', MOLONGUI_AUTHORSHIP_URL.$file, array(), MOLONGUI_AUTHORSHIP_VERSION, 'all' );            }        }    }    public function enqueue_scripts( $hook )    {        $screen = get_current_screen();        if ( in_array( $screen->id, molongui_enabled_post_screens( MOLONGUI_AUTHORSHIP_ID, 'all' ) ) )        {            if ( current_user_can( 'edit_others_posts' ) or current_user_can( 'edit_others_pages' ) )            {                $multi_feature = molongui_authorship_is_feature_enabled( 'multi' );                molongui_enqueue_selectr();                if ( $multi_feature )                {                    molongui_enqueue_tiptip();                    molongui_enqueue_sortable();                }                $file = 'admin/js/edit-post.e05a.min.js';                if ( file_exists( MOLONGUI_AUTHORSHIP_DIR.$file ) )                {                    $handle = MOLONGUI_AUTHORSHIP_NAME . '-edit-post';                    wp_enqueue_script( $handle, MOLONGUI_AUTHORSHIP_URL.$file, array( 'jquery' ), MOLONGUI_AUTHORSHIP_VERSION , true );                    wp_localize_script( $handle, 'authorship', array                    (                        'guest_enabled'     => molongui_authorship_is_feature_enabled( 'guest' ),                        'select_author_tip' => ( $multi_feature ? esc_html__( 'Select an(other) author...', 'molongui-authorship' ) : esc_html__( 'Select an author', 'molongui-authorship' ) ),                        'remove_author_tip' => esc_html__( "Remove author from selection", 'molongui-authorship' ),                        'guest_name_error'  => esc_html__( "You must provide a guest author display name", 'molongui-authorship' ),                        'ajax_fn_error'     => esc_html__( "Something went wrong. Guest author not added", 'molongui-authorship' ),                        'ajax_call_error'   => esc_html__( "Something went wrong. Can't connect to backend", 'molongui-authorship' ),                    ));                }            }        }        elseif ( in_array( $screen->id, array( 'profile', 'user', 'user-edit' ) ) )        {            if ( !MOLONGUI_AUTHORSHIP_IS_PRO ) molongui_enqueue_tiptip();            if ( molongui_authorship_is_feature_enabled( 'avatar' ) )            {                $file = 'admin/js/user.f5c8.min.js';                if ( file_exists(MOLONGUI_AUTHORSHIP_DIR . $file ) ) wp_enqueue_script(MOLONGUI_AUTHORSHIP_NAME . '-user', MOLONGUI_AUTHORSHIP_URL . $file, array('jquery'), MOLONGUI_AUTHORSHIP_VERSION, true );            }        }        elseif ( in_array( $screen->id, array( 'users', $this->vars->config['cpt']['guest'], 'edit-'.$this->vars->config['cpt']['guest'] ) ) )        {            molongui_enqueue_tiptip();        }        elseif ( $screen->id == 'molongui_page_molongui-authorship' )        {            $file = 'admin/js/options.689f.min.js';            if ( file_exists( MOLONGUI_AUTHORSHIP_DIR.$file ) )            {                $handle = MOLONGUI_AUTHORSHIP_NAME . '-settings';                wp_enqueue_script( $handle, MOLONGUI_AUTHORSHIP_URL.$file, array( 'jquery' ), MOLONGUI_AUTHORSHIP_VERSION , true );                wp_localize_script( $handle, 'authorship', array                (                    'is_premium' => MOLONGUI_AUTHORSHIP_IS_PRO,                ));            }        }    }    public function add_footer_scripts()	{        ?>        <script type="text/javascript">			document.addEventListener("DOMContentLoaded", function()			{			});		</script>		<?php	}}