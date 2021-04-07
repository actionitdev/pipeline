<?php

namespace Molongui\Authorship\Fw\Includes;
defined( 'ABSPATH' ) or exit;
if ( !class_exists( 'Molongui\Authorship\Fw\Includes\DB_Update' ) )
{
	class DB_Update
	{
		private $plugin;
		protected $target_version;
		public function __construct( $plugin_id, $target_version )
		{
            $this->plugin = molongui_get_plugin( $plugin_id );
			$this->target_version = $target_version;
		}
		public function db_update_needed()
		{
			$current_version = get_option( $this->plugin->db_settings );
			if ( empty( $current_version ) )
			{
				update_option( $this->plugin->db_settings, $this->target_version );
				return false;
			}
			if ( $current_version >= $this->target_version ) return false;
			return true;
		}
		public function run_update()
		{
			$current_db_ver = get_option( $this->plugin->db_settings, 1 );
			$target_db_ver = $this->target_version;
			while ( $current_db_ver < $target_db_ver )
			{
				$current_db_ver ++;
				$func = "db_update_{$current_db_ver}";
				if ( method_exists( '\Molongui\\'.ucfirst($this->plugin->namespace).'\Includes\DB_Update', $func ) )
				{
					$class_name   = '\Molongui\\'.ucfirst($this->plugin->namespace).'\Includes\DB_Update';
					$plugin_class = new $class_name();
					$plugin_class->{$func}();
				}
				update_option( $this->plugin->db_settings, $current_db_ver );
			}
		}
	}
}
namespace Molongui\Fw\Includes;
if ( !class_exists( 'Molongui\Fw\Includes\DB_Update' ) )
{
    class DB_Update
    {
        private $plugin;
        protected $target_version;
        public function __construct( $plugin_id, $target_version )
        {
            $this->plugin = molongui_get_plugin( $plugin_id );
            $this->target_version = $target_version;
        }
        public function db_update_needed()
        {
            $current_version = get_option( $this->plugin->db_settings );
            if ( empty( $current_version ) )
            {
                update_option( $this->plugin->db_settings, $this->target_version );
                return false;
            }
            if ( $current_version >= $this->target_version ) return false;
            return true;
        }
        public function run_update()
        {
            $current_db_ver = get_option( $this->plugin->db_settings, 1 );
            $target_db_ver = $this->target_version;
            while ( $current_db_ver < $target_db_ver )
            {
                $current_db_ver ++;
                $func = "db_update_{$current_db_ver}";
                if ( method_exists( '\Molongui\\'.ucfirst($this->plugin->namespace).'\Includes\DB_Update', $func ) )
                {
                    $class_name   = '\Molongui\\'.ucfirst($this->plugin->namespace).'\Includes\DB_Update';
                    $plugin_class = new $class_name();
                    $plugin_class->{$func}();
                }
                update_option( $this->plugin->db_settings, $current_db_ver );
            }
        }
    }
}