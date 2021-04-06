<?php

namespace Molongui\Authorship\Fw\Includes;
\defined( 'ABSPATH' ) or exit;
if ( !\class_exists( 'Molongui\Authorship\Fw\Includes\Loader' ) )
{
	class Loader
	{
        private static $instance;
		protected $actions;
		protected $filters;
        public static function get_instance()
        {
            if ( null === self::$instance ) self::$instance = new self();

            return self::$instance;
        }
		public function __construct()
		{
			$this->actions = array();
			$this->filters = array();
		}
		public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 )
		{
			$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
		}
		public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 )
		{
			$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
		}
		private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args )
		{
			$hooks[] = array
            (
				'hook'          => $hook,
				'component'     => $component,
				'callback'      => $callback,
				'priority'      => $priority,
				'accepted_args' => $accepted_args
			);

			return $hooks;
		}
		public function run()
		{
			foreach ( $this->filters as $hook )
			{
				\add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
			}

			foreach ( $this->actions as $hook )
			{
				\add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
			}
            $this->actions = array();
            $this->filters = array();
		}
	}
}