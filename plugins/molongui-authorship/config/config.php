<?php
defined( 'ABSPATH' ) or exit;

return array
(
	'cpt' => array
	(
		'guest' => 'guest_author',
	),
    'notices' => array
    (
    	'install' => array
	    (
		    'dismissible' => true,
		    'dismissal'   => 'forever',
	    ),
    	'whatsnew' => array
	    (
		    'dismissible' => true,
		    'dismissal'   => 'forever',
	    ),
    	'upgrade' => array
	    (
		    'dismissible' => true,
		    'dismissal'   => 60,
	    ),
    	'rate' => array
	    (
	    	'trigger'     => 30,
		    'dismissible' => true,
		    'dismissal'   => 'forever',
	    ),
    	'update' => array
	    (
		    'dismissible' => false,
		    'dismissal'   => 0,
	    ),
	    'missing-dependency' => array
	    (
		    'dismissible' => false,
		    'dismissal'   => 0,
	    ),
	    'missing-version' => array
	    (
		    'dismissible' => false,
		    'dismissal'   => 0,
	    ),
	    'many-installations' => array
	    (
		    'dismissible' => false,
		    'dismissal'   => 0,
	    ),
    ),
	'customizer' => array
	(
		'enable' => true,
	),
	'fw' => array
	(
		'enable'   => true,
		'settings' => array
		(
			'uninstalling'    => true,
			'keep_config'     => true,
			'keep_data'       => true,
		),
	),
);