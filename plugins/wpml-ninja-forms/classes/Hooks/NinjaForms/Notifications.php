<?php

namespace WPML\Forms\Hooks\NinjaForms;

use NF_Abstracts_ModelFactory;
use WPML\Forms\Hooks\Base;
use WPML\Forms\Translation\Package;

class Notifications extends Base {

	const ACTION_STRING_NAME = 'action-%d';
	const EMAIL_TYPE         = 'email';
	const EMAIL_SUBJECT      = 'email-subject';
	const EMAIL_BODY         = 'email-body';
	const NF_EMAIL_SUBJECT   = 'email_subject';
	const NF_EMAIL_BODY      = 'email_message';
	const SUCCESS_MSG_TYPE   = 'successmessage';
	const SUCCESS_MSG        = 'success_msg';

	/**
	 * User preferred language.
	 *
	 * @var string $userPreferredLanguage
	 */
	private $userPreferredLanguage;

	/**
	 * WPML current language.
	 *
	 * @var string $currentLanguage
	 */
	private $currentLanguage;

	/** @var array $settingsToRegister */
	private $settingsToRegister = [
		self::EMAIL_TYPE       => [
			self::NF_EMAIL_SUBJECT => self::EMAIL_SUBJECT,
			self::NF_EMAIL_BODY    => self::EMAIL_BODY,
		],
		self::SUCCESS_MSG_TYPE => [ self::SUCCESS_MSG => self::SUCCESS_MSG ],
	];

	/** Adds hooks. */
	public function addHooks() {
		add_action( 'wpml_forms_ninja_forms_register', [ $this, 'register' ], 10, 2 );
		add_filter( 'ninja_forms_run_action_settings', [ $this, 'applyTranslations' ], 5, 3 );
		add_filter( 'ninja_forms_merge_label', [ $this, 'applyLabelTranslation' ], 10, 3 );
		add_filter( 'ninja_forms_localize_list_labels', [ $this, 'applyOptionsTranslation' ], 10, 3 );
	}

	/**
	 * Registers email strings.
	 *
	 * @param NF_Abstracts_ModelFactory $form Form object.
	 * @param Package                   $package Translation package.
	 */
	public function register( NF_Abstracts_ModelFactory $form, Package $package ) {

		$actions = $form->get_actions();

		foreach ( $actions as $action ) {

			$settings = $action->get_settings();

			if ( array_key_exists( 'type', $settings ) ) {

				$actionId   = $action->get_id();
				$stringName = $this->getActionStringName( $actionId );

				if ( array_key_exists( $settings['type'], $this->settingsToRegister ) ) {
					foreach ( $this->settingsToRegister[ $settings['type'] ] as $field => $id ) {
						if ( $this->notEmpty( $field, $settings ) ) {
							$package->registerString( $settings[ $field ], $stringName, $id );
						}
					}
				}
			}
		}
	}

	/**
	 * Returns action string name.
	 *
	 * @param int $actionId Action ID.
	 *
	 * @return string
	 */
	private function getActionStringName( $actionId ) {
		return sprintf( self::ACTION_STRING_NAME, $actionId );
	}

	/**
	 * Applies translations to email strings.
	 *
	 * @param array $settings Action settings.
	 * @param int   $formId Form ID.
	 * @param int   $actionId Form action ID.
	 *
	 * @return array
	 */
	public function applyTranslations( array $settings, $formId, $actionId ) {

		if ( $this->getFormId() !== $formId ) {
			$this->setFormId( $formId );
		}

		if ( array_key_exists( 'type', $settings ) && array_key_exists( $settings['type'], $this->settingsToRegister ) ) {

			$stringName = $this->getActionStringName( $actionId );

			if ( self::EMAIL_TYPE === $settings['type'] ) {
				$this->currentLanguage       = apply_filters( 'wpml_current_language', null );
				$this->userPreferredLanguage = $this->getUserPreferredLanguage( $settings['to'] );
				do_action( 'wpml_switch_language', $this->userPreferredLanguage );
			}

			foreach ( $this->settingsToRegister[ $settings['type'] ] as $field => $id ) {
				if ( $this->notEmpty( $field, $settings ) ) {
					$settings[ $field ] = $this->getPackage()->translateString( $settings[ $field ], $stringName, $id );
				}
			}

			if ( self::EMAIL_TYPE === $settings['type'] ) {
				do_action( 'wpml_switch_language', $this->currentLanguage );
			}
		}

		return $settings;
	}

	/**
	 * Applies translation to field label in email body.
	 *
	 * @param string $label Field label.
	 * @param array  $field Field data.
	 * @param int    $formId Form ID.
	 *
	 * @return string
	 */
	public function applyLabelTranslation( $label, array $field, $formId ) {

		if ( $this->getFormId() !== $formId ) {
			$this->setFormId( $formId );
		}

		do_action( 'wpml_switch_language', $this->userPreferredLanguage );
		$label = $this->getPackage()->translateString( $label, $field['id'], 'label' );
		do_action( 'wpml_switch_language', $this->currentLanguage );

		return $label;
	}

	/**
	 * Applies translation to field options.
	 *
	 * @param array $options Field options.
	 * @param array $field Field data.
	 * @param int   $formId Form ID.
	 *
	 * @return array
	 */
	public function applyOptionsTranslation( array $options, array $field, $formId ) {

		if ( $this->getFormId() !== $formId ) {
			$this->setFormId( $formId );
		}

		do_action( 'wpml_switch_language', $this->userPreferredLanguage );
		$translatedField = $this->getPackage()->translateField( $field, $field['id'] );
		do_action( 'wpml_switch_language', $this->currentLanguage );

		return $translatedField['options'];
	}

	/**
	 * Gets user preferred language.
	 *
	 * @param string $to Action setting email TO header.
	 *
	 * @return string
	 */
	public function getUserPreferredLanguage( $to ) {

		$preferredLanguage = null;

		switch ( $to ) {
			case '{system:admin_email}':
			case '{wp:admin_email}':
				$user = get_user_by( 'email', get_option( 'admin_email' ) );
				break;
			case '{wp:user_email}':
				$user = wp_get_current_user();
				break;
			case '{wp:post_author_email}':
				$post_id = null;
				if ( wp_doing_ajax() ) {
					$post_id = url_to_postid( wp_get_referer() );
				}
				$post = get_post( $post_id );
				if ( $post ) {
					$user = get_user_by( 'id', $post->post_author );
				}
				break;
			default:
				$user = get_user_by( 'email', $to );
				break;
		}

		if ( $user ) {
			$preferredLanguage = get_user_meta( $user->ID, 'icl_admin_language', true );
		}

		return $preferredLanguage ? $preferredLanguage : $this->currentLanguage;
	}
}
