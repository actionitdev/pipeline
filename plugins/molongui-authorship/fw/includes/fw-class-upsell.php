<?php

namespace Molongui\Authorship\Fw\Includes;
defined( 'ABSPATH' ) or exit;
if ( !class_exists( 'Molongui\Authorship\Fw\Includes\Upsell' ) )
{
	class Upsell
	{
		private $plugin;
		public function __construct( $plugin )
		{
			$this->plugin = $plugin;
		}
		public function get()
		{
            $upsells = include $this->plugin->dir . 'fw/upsells/upsells.php';
            if ( empty( $upsells ) ) return false;
            foreach ( \get_molongui_plugins( 'keys' ) as $plugin_file ) unset( $upsells[$plugin_file] );
            return $upsells;
		}

    } // End of 'Upsell' class
} // End if_class_exists