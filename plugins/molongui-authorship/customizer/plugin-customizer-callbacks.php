<?php
defined( 'ABSPATH' ) or exit;
function molongui_authorship_validate_box_width( $validity, $value )
{
	$value = intval( $value );
	if ( ( empty( $value ) and ( $value !== 0 ) ) || !is_numeric( $value ) )
	{
		$validity->add( 'required', __( 'You must supply a valid width.', 'molongui-authorship' ) );
	}
	elseif ( $value < 0 )
	{
		$validity->add( 'box_width_too_small', __( 'Box width is too small.', 'molongui-authorship' ) );
	}
	elseif ( $value > 100 )
	{
		$validity->add( 'box_width_too_big', __( 'Box width cannot be bigger than 100%.', 'molongui-authorship' ) );
	}
	return $validity;
}
function molongui_authorship_sanitize_setting( $input, $setting )
{
	$setting_type = $setting->manager->get_control( $setting->id )->type;
	$choices = $setting->manager->get_control( $setting->id )->choices;
	if ( !empty( $choices ) )
	{
		$accepted = array();
		foreach ( $choices as $choice => $atts )
		{
			if ( !isset( $atts['premium'] ) or ( isset( $atts['premium'] ) and !$atts['premium'] ) ) $accepted[] = $choice;
		}
		if ( in_array( $input, $accepted ) ) return $input;
		if ( $setting_type == 'molongui-image-checkbox' or $setting_type == 'molongui-compact-image-checkbox' )
		{
			if ( !empty( $input ) )
			{
				$input_values = explode( ',', $input );
				foreach ( $input_values as $key => $input_value )
				{
					if ( !in_array( $input_value, $accepted ) ) unset( $input_values[$key] );
				}
			}
			else return $input;
			return implode(',', $input_values );
		}
		$box = get_option( MOLONGUI_AUTHORSHIP_BOX_SETTINGS );
		preg_match('/\[(.*?)\]/', $setting->id, $setting_name);
		if ( !empty( $box[$setting_name[1]] ) )
		{
			if ( in_array( $box[$setting_name[1]], $accepted ) ) return $box[$setting_name[1]];
		}
		return $setting->default ;
	}
	else
	{
		if ( $setting_type == 'molongui-color' ) $input = molongui_sanitize_color( $input );
		$input_attrs = $setting->manager->get_control( $setting->id )->input_attrs;
		if ( !isset( $input_attrs['premium'] ) or ( isset( $input_attrs['premium'] ) and !$input_attrs['premium'] ) ) return $input;
		else return $setting->default;
	}
}
function molongui_authorship_sanitize_string( $input, $setting )
{
	if ( !empty( $input ) ) return wp_filter_nohtml_kses( $input );
	return $setting->default;
}
function molongui_authorship_sanitize_checkbox( $input, $setting )
{
	if ( !empty( $input ) ) return $input;
	return '';
}
function molongui_get_customizer_setting( $control, $setting, $value )
{
    return $control->manager->get_setting('molongui_authorship_box['.$setting.']')->value() == $value;
}
function molongui_active_ribbon_layout_setting( $control )
{
	$layout = $control->manager->get_setting('molongui_authorship_box[profile_layout]')->value();

	return ( ( $layout == 'layout-7' or $layout == 'layout-8' ) ? true : false );
}
function molongui_active_ribbon_border_setting( $control )
{
	return ( $control->manager->get_setting('molongui_authorship_box[bottom_border_style]')->value() == 'none' ? false : true );
}
function molongui_active_headline_setting( $control )
{
	return ( ( $control->manager->get_setting('molongui_authorship_box[show_headline]')->value() != '1' or $control->manager->get_setting('molongui_authorship_box[layout]')->value() == 'template-3' ) ? false : true );
}
function molongui_active_tabs_border_setting( $control )
{
	return ( $control->manager->get_setting('molongui_authorship_box[tabs_border]')->value() == 'none' ? false : true );
}
function molongui_active_tabs_border_color_setting( $control )
{
	$box_border  = $control->manager->get_setting('molongui_authorship_box[box_border]')->value();
	$tab_border  = $control->manager->get_setting('molongui_authorship_box[tabs_border]')->value();

	$box_borders = array( 'all', 'horizontals', 'top' );
	$tab_borders = array( 'none', 'around' );

	return ( in_array( $box_border, $box_borders ) and in_array( $tab_border, $tab_borders ) ? false : true );
}
function molongui_active_tabs_border_style_width_setting( $control )
{
	$box_border  = $control->manager->get_setting('molongui_authorship_box[box_border]')->value();
	$tab_border  = $control->manager->get_setting('molongui_authorship_box[tabs_border]')->value();

	$box_borders = array( 'all', 'horizontals', 'top' );
	$tab_borders = array( 'none', 'around', 'bottom' );

	return ( in_array( $box_border, $box_borders ) and in_array( $tab_border, $tab_borders ) ? false : true );
}
function molongui_active_tabs_background_setting( $control )
{
	return ( $control->manager->get_setting('molongui_authorship_box[tabs_position]')->value() == 'top-full' ? false : true );
}
function molongui_active_icons_setting( $control )
{
	return ( $control->manager->get_setting('molongui_authorship_box[show_icons]')->value() == '1' ? true : false );
}
function molongui_active_icons_color_setting( $control )
{
	$no_color_styles = array( 'branded', 'branded-boxed', 'branded-squared-reverse', 'branded-circled-reverse' );
	return ( ( in_array( $control->manager->get_setting('molongui_authorship_box[icons_style]')->value(), $no_color_styles ) or $control->manager->get_setting('molongui_authorship_box[show_icons]')->value() != '1' ) ? false : true );
}