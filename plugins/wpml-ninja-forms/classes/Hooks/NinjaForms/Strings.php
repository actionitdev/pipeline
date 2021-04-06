<?php

namespace WPML\Forms\Hooks\NinjaForms;

use WPML\Forms\Hooks\Registration;

class Strings extends Registration {

	/**
	 * Not translatable fields.
	 *
	 * @var array $ignoredFieldTypes
	 */
	private $ignoredFieldTypes = [ 'liststate', 'hidden', 'label' ];

	/** Adds hooks. */
	public function addHooks() {
		parent::addHooks();
		add_action( 'nf_get_form_id', [ $this, 'setFormId' ] );
		add_filter( 'ninja_forms_display_form_settings', [ $this, 'applySettingsTranslation' ] );
		add_filter( 'ninja_forms_localize_fields', [ $this, 'applyFieldTranslation' ] );
		add_action( 'ninja_forms_save_form', [ $this, 'register' ] );
	}

	/**
	 * Applies translation to form settings.
	 *
	 * @param array $settings Form settings.
	 *
	 * @return array
	 */
	public function applySettingsTranslation( array $settings ) {

		if ( $this->getFormId() ) {
			$settings = $this->getPackage()->translateFormSettings( $settings );
		}

		return $settings;
	}

	/**
	 * Applies translations to form field.
	 *
	 * @param array $field Form field.
	 *
	 * @return array
	 */
	public function applyFieldTranslation( array $field ) {

		if (
			$this->getFormId() && $this->notEmpty( 'settings', $field )
			&& $this->notEmpty( 'type', $field['settings'] )
			&& ! in_array( $field['settings']['type'], $this->ignoredFieldTypes, true )
		) {
			$field['settings'] = $this->getPackage()->translateField( $field['settings'], $this->getId( $field ) );
		}

		return $field;
	}

	/**
	 * Registers form for translation.
	 *
	 * @param int $formId Form ID.
	 */
	public function register( $formId ) {

		$form     = Ninja_Forms()->form( $formId );
		$package  = $this->newPackage( $formId );
		$settings = $form->get()->get_settings();
		$fields   = $form->get_fields();

		$package->registerFormSettings( $settings );
		foreach ( $fields as $field ) {
			if ( ! in_array( $field->get_setting( 'type' ), $this->ignoredFieldTypes, true ) ) {
				$package->registerField( $field->get_id(), $field->get_settings() );
			}
		}

		do_action( 'wpml_forms_ninja_forms_register', $form, $package );
		$package->cleanup();
	}

	/**
	 * Adds forms info for bulk registration.
	 *
	 * @param array $items Array of form infos.
	 *
	 * @return array
	 */
	public function bulkRegistrationItems( array $items ) {

		$forms = Ninja_Forms()->form()->get_forms();
		if ( is_array( $forms ) ) {
			foreach ( $forms as $form ) {
				$items[] = $this->getBulkRegistrationItem( $form->get_id(), $form->get_setting( 'title' ) );
			}
		}

		return $items;
	}

	/**
	 * Registers forms for translation.
	 *
	 * @param array $forms Array of form IDs.
	 */
	public function bulkRegistration( array $forms ) {
		foreach ( $forms as $formId ) {
			$this->register( $formId );
		}
	}
}
