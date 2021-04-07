<?php
defined( 'ABSPATH' ) or exit;

?>

<div id="molongui-options">

    <?php
        $args = array
        (
            'logo'   => $this->plugin->url . 'assets/img/plugin_logo.png',
            'link'   => $this->plugin->web,
            'button' => array
            (
                'id'    => 'm-button-save',
                'class' => 'm-button-save',
                'label' => __( "Save Settings", 'molongui-authorship' ),
            ),
        );
        include 'parts/html-part-masthead.php';

    ?>

    <!-- Page Content -->
    <div class="m-page-content">

        <!-- Nav -->
        <div id="m-navigation" class="m-navigation">
            <div class="m-section-nav <?php echo ( empty( $tabs ) ? 'is-empty' : 'has-pinned-items' ); ?>">

                <div class="m-section-nav__mobile-header" role="button" tabindex="0">
                    <?php echo $tabs[$this->_tab]['name']; ?>
                </div>

                <div class="m-section-nav__panel">
                    <div class="m-section-nav-group">
                        <div class="m-section-nav-tabs">
                            <ul class="m-section-nav-tabs__list" role="menu">
                                <?php echo $nav_items; ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tabs -->
        <?php echo $div_contents; ?>

        <!-- Other stuff -->
        <?php echo wp_nonce_field( 'mfw_save_options_nonce', 'mfw_save_options_nonce', true, false ); ?>

    </div><!-- !m-page-content -->

    <?php
        $plugin_url    = $this->plugin->web;
        $support_url   = molongui_get_constant( $this->plugin->id, 'URL_SUPPORT', true );
        $docs_url      = molongui_get_constant( $this->plugin->id, 'URL_DOCS', true ) . '/' . molongui_get_constant( $this->plugin->id, 'NAME', false );
        $changelog_url = $this->plugin->is_pro ? $docs_url.'/changelog/changelog-pro-version/' : $docs_url.'/changelog/changelog-free-version/';
        $demo_url      = 'https://demos.molongui.com/test-drive-'.$this->plugin->name.'-premium/';

        $args = array
        (
            'links' => array
            (
                array
                (
                    'label'   => __( "Pro", 'molongui-authorship' ) . " " . molongui_get_constant($this->plugin->id.' Pro', 'VERSION' ),
                    'prefix'  => '<span class="m-page-footer__version">',
                    'suffix'  => '</span>',
                    'href'    => $plugin_url,
                    'display' => $this->plugin->is_pro,
                ),
                array
                (
                    'label'   => __( "Free", 'molongui-authorship' ) . " " . $this->plugin->version,
                    'prefix'  => '<span class="m-page-footer__version">',
                    'suffix'  => '</span>',
                    'href'    => $plugin_url,
                    'display' => true,
                ),
                array
                (
                    'label'   => __( "Changelog", 'molongui-authorship' ),
                    'prefix'  => '',
                    'suffix'  => '',
                    'href'    => $changelog_url,
                    'display' => true,
                ),
                array
                (
                    'label'   => __( "Docs", 'molongui-authorship' ),
                    'prefix'  => '',
                    'suffix'  => '',
                    'href'    => $docs_url,
                    'display' => true,
                ),
                array
                (
                    'label'   => __( "Support", 'molongui-authorship' ),
                    'prefix'  => '',
                    'suffix'  => '',
                    'href'    => $support_url,
                    'display' => true,
                ),
                array
                (
                    'label'   => __( "Try Pro", 'molongui-authorship' ),
                    'prefix'  => '',
                    'suffix'  => '',
                    'href'    => $demo_url,
                    'display' => !$this->plugin->is_pro,
                ),
                array
                (
                    'label'   => __( "Upgrade", 'molongui-authorship' ),
                    'prefix'  => '',
                    'suffix'  => '',
                    'href'    => $plugin_url.'pricing/',
                    'display' => !$this->plugin->is_pro,
                ),
            ),
        );
        include 'parts/html-part-footer.php';

    ?>

</div> <!-- #molongui-options -->

<div id="m-options-saving"><div class="m-loader"><div></div><div></div><div></div><div></div></div></div>
<div id="m-options-saved"><span class="dashicons dashicons-yes"></span><strong><?php echo __( 'Saved', 'molongui-authorship' ); ?></strong></div>
<div id="m-options-error"><span class="dashicons dashicons-no"></span><strong><?php echo __( 'Error', 'molongui-authorship' ); ?></strong></div>