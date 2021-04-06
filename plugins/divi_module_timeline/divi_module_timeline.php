<?php

/*
 * Plugin Name: Divi Timeline Module
 * Plugin URI:  http://www.sean-barton.co.uk
 * Description: A plugin to add a module to create timeline based modules for the Divi Builder
 * Author:      Sean Barton - Tortoise IT
 * Version:     1.6
 * Author URI:  http://www.sean-barton.co.uk
 *
 *
 * Changelog
 *
 * < V1.3
 * - Initial Release
 *
 * V1.3
 * - Fixed responsive text size/line height issues
 * - Added more configuration options in advanced design settings across all modules
 *
 * V1.4
 * - Added new module for posts based timeline
 * - Added title, content and gallery modules to compliment post timeline module
 *
 * V1.5
 * - Added more styling settings for each module
 * - Fixed bug whereby bullet points didn't style properly
 *
 * V1.6
 * - Numbers instead of icons work in progress
 *
 */

    add_action('plugins_loaded', 'sb_dtm_init');
    
    function sb_dtm_init() {
				add_action('init', 'sb_dtm_theme_setup', 9999);
        add_action('admin_head', 'sb_dtm_admin_head', 9999);
				add_action('wp_enqueue_scripts', 'sb_dtm_enqueue', 9999);
    }
    
    function sb_dtm_enqueue() {
				wp_enqueue_script('jquery');
				wp_enqueue_style('sb_dtm_custom_css', plugins_url( '/style.css', __FILE__ ));
    }
    
    function sb_dtm_admin_head() {
	
				if (!isset($_GET['post_type']) || $_GET['post_type'] != 'et_pb_layout') {
						return; //we will only purge the cache on the layouts page
				}
			
				$prop_to_remove = array(
						'et_pb_templates_et_pb_vertical_timeline'
						, 'et_pb_templates_et_pb_vertical_timeline_item'
						, 'et_pb_templates_et_pb_vertical_post_timeline'
				);
				
				$js_prop_to_remove = 'var sb_ls_remove = ["' . implode('","', $prop_to_remove) . '"];';
			
				echo '<script>
				
				' . $js_prop_to_remove . '
				
				for (var prop in localStorage) {
						if (sb_ls_remove.indexOf(prop) != -1) {
					//console.log("found "+prop);
					console.log(localStorage.removeItem(prop));	
						}
				}
				
				</script>';
    }
    
    function sb_dtm_theme_setup() {
	
		if ( class_exists('ET_Builder_Module')) {
	    
	    class et_pb_vertical_timeline extends ET_Builder_Module {
		function init() {
			$this->name     = __( 'Timeline - Vertical', 'et_builder' );
			$this->slug     = 'et_pb_vertical_timeline';
			$this->child_slug      = 'et_pb_vertical_timeline_item';
			$this->child_item_text = esc_html__( 'Timeline Item', 'et_builder' );
			
			$this->whitelisted_fields = array(
			    'title',
			    'line_color',
			    'module_id',
			    'module_class',
			);
    
			$this->fields_defaults = array();
			$this->main_css_element = '%%order_class%%';
					
			$this->advanced_options = array(
                                        'fonts' => array(
                                                'text'   => array(
                                                                'label'    => esc_html__( 'Text', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} p, {$this->main_css_element} ul li",
                                                                ),
                                                                'font_size' => array('default' => '14px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                                'label'   => array(
                                                                'label'    => esc_html__( 'Item Label', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} .cd-date",
                                                                ),
                                                                'font_size' => array('default' => '14px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                                'headings'   => array(
                                                                'label'    => esc_html__( 'Headings', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} h1, {$this->main_css_element} h2, {$this->main_css_element} h1 a, {$this->main_css_element} h2 a, {$this->main_css_element} h3, {$this->main_css_element} h4",
                                                                ),
                                                                'font_size' => array('default' => '30px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                        ),
                                        'background' => array(
                                                'settings' => array(
                                                        'color' => 'alpha',
                                                ),
                                        ),
                                        'border' => array(),
                                        'custom_margin_padding' => array(
                                                'css' => array(
                                                        'important' => 'all',
                                                ),
                                        ),
                                );
	
		}
	
		function get_fields() {
				$fields = array(
				    
				'title' => array(
				    'label'       => __( 'Title', 'et_builder' ),
				    'type'        => 'text',
				    'description' => __( 'Optional heading to show on the front end', 'et_builder' ),
				),
				'admin_label' => array(
					'label'       => __( 'Admin Label', 'et_builder' ),
					'type'        => 'text',
					'option_category' => 'configuration',
					'tab_slug'        => 'custom_css',
					'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
				),
				'line_color' => array(
					'label'             => esc_html__( 'Central Line Color', 'et_builder' ),
					'type'              => 'color-alpha',
					'description'       => esc_html__( 'The colour of the vertical line between the items.', 'et_builder' ),
				),
				'module_id' => array(
					'label'           => __( 'CSS ID', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'configuration',
					'tab_slug'        => 'custom_css',
					'option_class'    => 'et_pb_custom_css_regular',
				),
				'module_class' => array(
					'label'           => __( 'CSS Class', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'configuration',
					'tab_slug'        => 'custom_css',
					'option_class'    => 'et_pb_custom_css_regular',
				),
			);
			return $fields;
		}
	
		function shortcode_callback( $atts, $content = null, $function_name ) {
			$module_id          = $this->shortcode_atts['module_id'];
			$module_class = $this->shortcode_atts['module_class'];
			$title = $this->shortcode_atts['title'];
			$line_color = $this->shortcode_atts['line_color'];
    
			$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
			
			if ( '' !== $line_color) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% #cd-timeline::before',
					'declaration' => 'background-color: ' . esc_html( $line_color )
				) );
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .cd-timeline-img .et-pb-icon',
					'declaration' => 'box-shadow: 0 0 0 5px ' . esc_html( $line_color )
				) );
				ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .cd-timeline-content',
								'declaration' => 'box-shadow: 0 4px 0 ' . esc_html( $line_color )
				) );
			}
			
			//////////////////////////////////////////////////////////////////////
      
			$content = '';
			
			//echo '<pre>';
			//print_r($this->shortcode_atts);
			//echo '</pre>';
			
			if ($title) {
				$content .= '<h2 itemprop="name" class="timeline_label">' . $title . '</h2>';
			}
			
			//////////////////////////////////////////////////////////////////////
			
			$content .= '<section id="cd-timeline" class="cd-container">' . $this->shortcode_content . '</section>';
			$output = ' <div ' . ( '' !== $module_id ? 'id="' . esc_attr( $module_id ) . '" ' : '' ) . ' class="et_pb_module ' . $module_class . '">
					' . $content . '
				    </div>';
    
			return $output;
		}
	    }
	
	    new et_pb_vertical_timeline;
	    
	    class et_pb_vertical_timeline_item extends ET_Builder_Module {
		    function init() {
			    $this->name                        = esc_html__( 'Timeline Item', 'et_builder' );
			    $this->slug                        = 'et_pb_vertical_timeline_item';
			    $this->type                        = 'child';
			    $this->child_title_var             = 'title';
	    
			    $this->whitelisted_fields = array(
				    'title',
				    'timeline_label',
				    'use_read_more',
				    'read_more_text',
				    'read_more_url',
				    'font_icon',
				    'icon_color',
				    'circle_color',
				    'animation',
				    'module_id',
				    'module_class',
			    );
	    
			    $this->advanced_setting_title_text = esc_html__( 'New Timeline Item', 'et_builder' );
			    $this->settings_text               = esc_html__( 'Timeline Item Settings', 'et_builder' );
			    $this->main_css_element = '%%order_class%%';
			    
			    $this->custom_css_options = array(
				'content' => array(
					'label'    => esc_html__( 'Content Box', 'et_builder' ),
					'selector' => '.cd-timeline-content',
				)
			    );
			    
			    $this->advanced_options = array(
                                        'fonts' => array(
                                                'text'   => array(
                                                                'label'    => esc_html__( 'Text', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} p",
                                                                ),
                                                                'font_size' => array('default' => '14px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                                'headings'   => array(
                                                                'label'    => esc_html__( 'Headings', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} h1, {$this->main_css_element} h2, {$this->main_css_element} h1 a, {$this->main_css_element} h2 a, {$this->main_css_element} h3, {$this->main_css_element} h4",
                                                                ),
                                                                'font_size' => array('default' => '30px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                        ),
                                        'background' => array(
                                                'settings' => array(
                                                        'color' => 'alpha',
                                                ),
                                        ),
                                        'border' => array(),
                                        'custom_margin_padding' => array(
                                                'css' => array(
                                                        'important' => 'all',
                                                ),
                                        ),
                                );
		    }
	    
		    function get_fields() {
			    
			    $fields = array(
				    'title' => array(
					    'label'       => esc_html__( 'Title', 'et_builder' ),
					    'type'        => 'text',
					    'description' => esc_html__( 'The content item title', 'et_builder' ),
				    ),
				    'content' => array(
					    'label'       => esc_html__( 'Content', 'et_builder' ),
					    'type'        => 'tiny_mce',
					    'description' => esc_html__( 'The content to show within the timeline item. Optional', 'et_builder' ),
				    ),
				    'timeline_label' => array(
					    'label'       => esc_html__( 'Timeline Label', 'et_builder' ),
					    'type'        => 'text',
					    'description' => esc_html__( 'The label to show next to the timeline marker for this item.', 'et_builder' ),
				    ),
				    'use_read_more' => array(
					    'label'           => esc_html__( 'Show a Read More button?', 'et_builder' ),
					    'type'            => 'yes_no_button',
					    'option_category' => 'configuration',
					    'options'         => array(
						    'off' => esc_html__( 'No', 'et_builder' ),
						    'on'  => esc_html__( 'Yes', 'et_builder' ),
					    ),
					    'affects'           => array(
						    '#et_pb_read_more_url',
						    '#et_pb_read_more_text',
					    ),
					    'description' => esc_html__( 'Should there be a read more button below the content?', 'et_builder' ),
				    ),
				    'read_more_text' => array(
					    'label'       => esc_html__( 'Read More Text', 'et_builder' ),
					    'type'        => 'text',
					    'depends_show_if'   => 'on',
					    'description' => esc_html__( 'Enter your read more button label', 'et_builder' ),
				    ),
				    'read_more_url' => array(
					    'label'       => esc_html__( 'Read More URL', 'et_builder' ),
					    'type'        => 'text',
					    'depends_show_if'   => 'on',
					    'description' => esc_html__( 'Enter your read more button URL', 'et_builder' ),
				    ),
				    'font_icon' => array(
					    'label'               => esc_html__( 'Item Icon', 'et_builder' ),
					    'type'                => 'text',
					    'option_category'     => 'basic_option',
					    'class'               => array( 'et-pb-font-icon' ),
					    'renderer'            => 'et_pb_get_font_icon_list',
					    'renderer_with_field' => true,
					    'description'         => esc_html__( 'Choose an icon to display to indicate this item in the timeline.', 'et_builder' ),
				    ),
				    'icon_color' => array(
					    'label'             => esc_html__( 'Icon Color', 'et_builder' ),
					    'type'              => 'color-alpha',
					    'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'et_builder' ),
				    ),
				    'circle_color' => array(
					    'label'             => esc_html__( 'Icon Background Color', 'et_builder' ),
					    'type'              => 'color-alpha',
					    'description'       => esc_html__( 'Here you can define a custom color for your icon background.', 'et_builder' ),
				    ),
				    'animation' => array(
					    'label'             => esc_html__( 'Image/Icon Animation', 'et_builder' ),
					    'type'              => 'select',
					    'option_category'   => 'configuration',
					    'options'           => array(
						'off'     => esc_html__( 'No Animation', 'et_builder' ),
						'fade_in' => esc_html__( 'Fade In', 'et_builder' ),
						'left'    => esc_html__( 'Left To Right', 'et_builder' ),
						'right'   => esc_html__( 'Right To Left', 'et_builder' ),
						'top'     => esc_html__( 'Top To Bottom', 'et_builder' ),
						'bottom'  => esc_html__( 'Bottom To Top', 'et_builder' ),
					    ),
					    'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
				    ),
				    'module_id' => array(
					    'label'           => esc_html__( 'CSS ID', 'et_builder' ),
					    'type'            => 'text',
					    'option_category' => 'configuration',
					    'tab_slug'        => 'custom_css',
					    'option_class'    => 'et_pb_custom_css_regular',
				    ),
				    'module_class' => array(
					    'label'           => esc_html__( 'CSS Class', 'et_builder' ),
					    'type'            => 'text',
					    'option_category' => 'configuration',
					    'tab_slug'        => 'custom_css',
					    'option_class'    => 'et_pb_custom_css_regular',
				    ),
			    );
			    return $fields;
		    }
	    
		    function shortcode_callback( $atts, $content = null, $function_name ) {
			    $title = $this->shortcode_atts['title'];
			    $timeline_label = $this->shortcode_atts['timeline_label'];
			    $use_read_more = $this->shortcode_atts['use_read_more'];
			    $read_more_url = $this->shortcode_atts['read_more_url'];
			    $read_more_text = $this->shortcode_atts['read_more_text'];
			    $font_icon = $this->shortcode_atts['font_icon'];
			    $icon_color = $this->shortcode_atts['icon_color'];
			    $circle_color = $this->shortcode_atts['circle_color'];
			    $animation = $this->shortcode_atts['animation'];
			    $shortcode_content = $this->shortcode_content;
			    $content = '';
			    
			    $image = '<span class="et-pb-icon et-pb-icon-circle" style="' . ($icon_color ? 'color: ' . $icon_color:'') . '">' . esc_attr( et_pb_process_font_icon( $font_icon ) ) . '</span>';
	    
			    $module_class = ET_Builder_Element::add_module_order_class( '', $function_name );
	    
			    $content .= '    <div class="cd-timeline-block ' . $module_class . '">
						    <div class="cd-timeline-img" style="' . ($circle_color ? 'background-color: ' . $circle_color:'') . '">' . $image . '</div>
			    
						    <div class="cd-timeline-content">
							' . ($timeline_label ? '<span class="cd-date">' . $timeline_label . '</span>':'') . '
							<div class="cd-timeline-content-liner ' . ($animation != 'off' ? 'et-waypoint et_pb_animation_' . $animation:'') . '">
								<h2>' .  $title . '</h2>
								' . ($shortcode_content ? wpautop($shortcode_content):'') . '
								' . ($use_read_more == 'on' ? '<p><a href="' . $read_more_url . '" class="cd-read-more">' . $read_more_text . '</a></p>':'') . '
							</div>
						    </div>
					    </div>';
	    
			    return $content;
		    }
	    }
	    
	    new et_pb_vertical_timeline_item;
						
			class et_pb_vertical_post_timeline extends ET_Builder_Module {
				function init() {
					$this->name = esc_html__( 'Timeline (Posts) - Vertical', 'et_builder' );
					$this->slug = 'et_pb_vertical_posts_timeline';
			
					$this->whitelisted_fields = array(
						'loop_layout',
						'show_pagination',
						'post_type',
						'posts_number',
						'offset_number',
						'include_tax',
						'include_tax_terms',
						//'line_or_icon',
						'number_order',
						'line_color',
						'font_icon',
				    'icon_color',
				    'circle_color',
				    'animation',
						'admin_label',
						'module_id',
						'module_class',
					);
			
					$this->fields_defaults = array(
						'loop_layout'         => array( 'on' ),
						'fullwidth'         => array( 'on' ),
						'columns'         => array( '3' ),
						'posts_number'      => array( 10, 'add_default_setting' ),
						'show_pagination'   => array( 'on' ),
						'offset_number'     => array( 0, 'only_default_setting' ),
					);
			
					$this->main_css_element = '%%order_class%%';
					
					$this->advanced_options = array(
																							'fonts' => array(
																										'text'   => array(
																																		'label'    => esc_html__( 'Text', 'et_builder' ),
																																		'css'      => array(
																																						'main' => "{$this->main_css_element} p, {$this->main_css_element} ul li",
																																		),
																																		'font_size' => array('default' => '14px'),
																																		'line_height'    => array('default' => '1.5em'),
																										),
																										'label'   => array(
																																		'label'    => esc_html__( 'Item Label', 'et_builder' ),
																																		'css'      => array(
																																						'main' => "{$this->main_css_element} .cd-date",
																																		),
																																		'font_size' => array('default' => '14px'),
																																		'line_height'    => array('default' => '1.5em'),
																										),
																										'numbers'   => array(
																																		'label'    => esc_html__( 'Item Icon Number', 'et_builder' ),
																																		'css'      => array(
																																						'main' => "{$this->main_css_element} .sb_dtm_numeral",
																																		),
																																		'font_size' => array('default' => '14px'),
																																		'line_height'    => array('default' => '1.5em'),
																										),
																										'headings'   => array(
																																		'label'    => esc_html__( 'Headings', 'et_builder' ),
																																		'css'      => array(
																																						'main' => "{$this->main_css_element} h1, {$this->main_css_element} h2, {$this->main_css_element} h1 a, {$this->main_css_element} h2 a, {$this->main_css_element} h3, {$this->main_css_element} h4",
																																		),
																																		'font_size' => array('default' => '30px'),
																																		'line_height'    => array('default' => '1.5em'),
																										),
																							),
																							'background' => array(
																											'settings' => array(
																															'color' => 'alpha',
																											),
																							),
																							'border' => array(),
																							'custom_margin_padding' => array(
																											'css' => array(
																															'important' => 'all',
																											),
																							),
																			);
				}
				
				function get_fields() {
					$options = array();
					
					$layouts = get_posts(array('post_type'=>'et_pb_layout', 'posts_per_page'=>-1));
					foreach ($layouts as $layout) {
						$options[$layout->ID] = $layout->post_title;
					}
					
					$args = array(
						'public'   => true
					);
					$output = 'objects'; // names or objects
					$pt_options = array();
					
					$post_types = get_post_types( $args, $output );
					
					foreach ( $post_types as $post_type=>$post_type_obj ) {
						$pt_options[$post_type] = $post_type_obj->labels->name;
					}
					
								
					$fields = array(
						'title' => array(
								'label'       => __( 'Title', 'et_builder' ),
								'type'        => 'text',
								'description' => __( 'Optional heading to show on the front end', 'et_builder' ),
						),
						'loop_layout' => array(
							'label'             => esc_html__( 'Loop Layout', 'et_builder' ),
							'type'              => 'select',
							'option_category'   => 'layout',
							'options'           => $options,
							'description'        => esc_html__( 'Choose a layout to use for each post in this archive loop', 'et_builder' ),
						),
						'line_color' => array(
								'label'             => esc_html__( 'Central Line Color', 'et_builder' ),
								'type'              => 'color-alpha',
								'description'       => esc_html__( 'The colour of the vertical line between the items.', 'et_builder' ),
						),
						'font_icon' => array(
					    'label'               => esc_html__( 'Item Icon', 'et_builder' ),
					    'type'                => 'text',
					    'option_category'     => 'basic_option',
					    'class'               => array( 'et-pb-font-icon' ),
					    'renderer'            => 'et_pb_get_font_icon_list',
					    'renderer_with_field' => true,
					    'description'         => esc_html__( 'Choose an icon to display to indicate this item in the timeline.', 'et_builder' ),
				    ),
						/*'line_or_icon' => array(
							'label'             => esc_html__( 'Show Numbers instead of icons?', 'et_builder' ),
							'type'              => 'select',
							'options'           => array(
								'off' => esc_html__( 'No', 'et_builder' ),
								'on'  => esc_html__( 'Yes', 'et_builder' ),
							),
							'description'        => esc_html__( 'On the line next to each item should numbers be shown or icons?', 'et_builder' ),
						),*/
				    'icon_color' => array(
					    'label'             => esc_html__( 'Icon/Number Color', 'et_builder' ),
					    'type'              => 'color-alpha',
					    'description'       => esc_html__( 'Here you can define a custom color for your icon/numbers.', 'et_builder' ),
				    ),
				    'circle_color' => array(
					    'label'             => esc_html__( 'Icon Background Color', 'et_builder' ),
					    'type'              => 'color-alpha',
					    'description'       => esc_html__( 'Here you can define a custom color for your icon background.', 'et_builder' ),
				    ),
				    'animation' => array(
					    'label'             => esc_html__( 'Image/Icon Animation', 'et_builder' ),
					    'type'              => 'select',
					    'option_category'   => 'configuration',
					    'options'           => array(
						'off'     => esc_html__( 'No Animation', 'et_builder' ),
						'fade_in' => esc_html__( 'Fade In', 'et_builder' ),
						'left'    => esc_html__( 'Left To Right', 'et_builder' ),
						'right'   => esc_html__( 'Right To Left', 'et_builder' ),
						'top'     => esc_html__( 'Top To Bottom', 'et_builder' ),
						'bottom'  => esc_html__( 'Bottom To Top', 'et_builder' ),
					    ),
					    'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
				    ),
						'show_pagination' => array(
							'label'             => esc_html__( 'Show Pagination', 'et_builder' ),
							'type'              => 'yes_no_button',
							'option_category'   => 'configuration',
							'options'           => array(
								'on'  => esc_html__( 'Yes', 'et_builder' ),
								'off' => esc_html__( 'No', 'et_builder' ),
							),
							'description'        => esc_html__( 'Turn pagination on and off.', 'et_builder' ),
						),
						'post_type' => array(
							'label'             => esc_html__( 'Post Type', 'et_builder' ),
							'type'              => 'select',
							'options'           => $pt_options,
							'description'        => esc_html__( 'Choose a post type to show', 'et_builder' ),
						),
						'posts_number' => array(
							'label'             => esc_html__( 'Posts Number', 'et_builder' ),
							'type'              => 'text',
							'description'       => esc_html__( 'Choose how many posts you would like to display per page.', 'et_builder' ),
						),
						'offset_number' => array(
							'label'           => esc_html__( 'Offset Number', 'et_builder' ),
							'type'            => 'text',
							'description'     => esc_html__( 'Choose how many posts you would like to offset by', 'et_builder' ),
						),
						'include_tax' => array(
							'label'           => esc_html__( 'Include Taxonomy Only', 'et_builder' ),
							'type'            => 'text',
							'description'     => esc_html__( 'This will filter the query by this taxonomy slug (advanced users only).', 'et_builder' ),
						),
						'include_tax_terms' => array(
							'label'           => esc_html__( 'Include Taxonomy Terms', 'et_builder' ),
							'type'            => 'text',
							'description'     => esc_html__( 'This will filter the query by the above taxonomy and these comma separated term slugs (advanced users only).', 'et_builder' ),
						),
						'admin_label' => array(
							'label'       => esc_html__( 'Admin Label', 'et_builder' ),
							'type'        => 'text',
							'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
						),
						'module_id' => array(
							'label'           => esc_html__( 'CSS ID', 'et_builder' ),
							'type'            => 'text',
							'option_category' => 'configuration',
							'tab_slug'        => 'custom_css',
							'option_class'    => 'et_pb_custom_css_regular',
						),
						'module_class' => array(
							'label'           => esc_html__( 'CSS Class', 'et_builder' ),
							'type'            => 'text',
							'option_category' => 'configuration',
							'tab_slug'        => 'custom_css',
							'option_class'    => 'et_pb_custom_css_regular',
						),
					);
					return $fields;
				}
			
				function shortcode_callback( $atts, $content = null, $function_name ) {
					
						$loop_layout           = $this->shortcode_atts['loop_layout'];
						$cols           = $this->shortcode_atts['columns'];
						$posts_number        = $this->shortcode_atts['posts_number'];
						$post_type        = $this->shortcode_atts['post_type'];
						$show_pagination     = $this->shortcode_atts['show_pagination'];
						$offset_number       = $this->shortcode_atts['offset_number'];
						$include_tax         = $this->shortcode_atts['include_tax'];
						$include_tax_terms      = $this->shortcode_atts['include_tax_terms'];
						$module_id          = $this->shortcode_atts['module_id'];
						$module_class = $this->shortcode_atts['module_class'];
						$title = $this->shortcode_atts['title'];
						$line_color = $this->shortcode_atts['line_color'];
						$font_icon = $this->shortcode_atts['font_icon'];
						$icon_color = $this->shortcode_atts['icon_color'];
						$circle_color = $this->shortcode_atts['circle_color'];
						$animation = $this->shortcode_atts['animation'];
						//$line_or_icon = (@$this->shortcode_atts['line_or_icon'] ? $this->shortcode_atts['line_or_icon']:'off');
					
						$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
					
						if ( '' !== $line_color) {
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% #cd-timeline::before',
								'declaration' => 'background-color: ' . esc_html( $line_color )
							) );
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .cd-timeline-img .et-pb-icon',
								'declaration' => 'box-shadow: 0 0 0 5px ' . esc_html( $line_color )
							) );
							ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .cd-timeline-content',
								'declaration' => 'box-shadow: 0 4px 0 ' . esc_html( $line_color )
							) );
						}
			
						global $paged;
				
						// remove all filters from WP audio shortcode to make sure current theme doesn't add any elements into audio module
						remove_all_filters( 'wp_audio_shortcode_library' );
						remove_all_filters( 'wp_audio_shortcode' );
						remove_all_filters( 'wp_audio_shortcode_class');
			
						$args = array( 'posts_per_page' => (int) $posts_number );
			
						$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );
				
						if ( is_front_page() ) {
							$paged = $et_paged;
						}
				
						$args['post_type'] = $post_type;
				
						if ( ! is_search() ) {
							$args['paged'] = $et_paged;
						}
				
						if ( '' !== $offset_number && ! empty( $offset_number ) ) {
							/**
							 * Offset + pagination don't play well. Manual offset calculation required
							 * @see: https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
							 */
							if ( $paged > 1 ) {
								$args['offset'] = ( ( $et_paged - 1 ) * intval( $posts_number ) ) + intval( $offset_number );
							} else {
								$args['offset'] = intval( $offset_number );
							}
						}
						
						if ($include_tax && $include_tax_terms) {
							if (strpos($include_tax, '|') !== false) {
								$include_tax = explode('|', $include_tax);
								$include_tax_terms = explode('|', $include_tax_terms);
								
								$args['tax_query'] = array();
								
								for ($i = 0; $i < count($include_tax); $i++) {
									$args['tax_query'][] = array(
											'taxonomy' => $include_tax[$i],
											'field'    => 'slug',
											'terms'    => explode(',', $include_tax_terms[$i]),
									);
								}
							} else {
								$args['tax_query'] = array(
									array(
											'taxonomy' => $include_tax,
											'field'    => 'slug',
											'terms'    => explode(',', $include_tax_terms),
									)
								);
							}
						}
				
						if ( is_single() && ! isset( $args['post__not_in'] ) ) {
							$args['post__not_in'] = array( get_the_ID() );
						}
				
						$args = apply_filters('sb_et_divi_dtm_posts_module_args', $args);
				
						//echo '<pre>';
						//print_r($args);
						//echo '</pre>';
				
						if (class_exists('EM_Event_Post')) {
							remove_action('parse_query', array('EM_Event_Post','parse_query'));
						}
						
						query_posts( $args );
						
						if (class_exists('EM_Event_Post')) {
							add_action('parse_query', array('EM_Event_Post','parse_query'));
						}
					
						ob_start();
			
						if ( have_posts() ) {
								$shortcodes = '';
								
								$i = 0;
								
								$content = '';
								
								if ($title) {
									$content .= '<h2 itemprop="name" class="timeline_label">' . $title . '</h2>';
								}
					
								//////////////////////////////////////////////////////////////////////
								while ( have_posts() ) {
									the_post();
									
										$image = '<span class="' .  (@$line_or_icon == 'on' ? 'sb_dtm_numeral':'') . ' et-pb-icon et-pb-icon-circle" style="' . ($icon_color ? 'color: ' . $icon_color:'') . '">' . (@$line_or_icon == 'on' ? ($i+1):esc_attr( et_pb_process_font_icon( $font_icon ) )) . '</span>';
					
										echo '    <div class="cd-timeline-block">
												<div class="cd-timeline-img" style="' . ($circle_color ? 'background-color: ' . $circle_color:'') . '">' . $image . '</div>
												<div class="cd-timeline-content">
											' . ($timeline_label ? '<span class="cd-date">' . $timeline_label . '</span>':'') . '
											<div class="cd-timeline-content-liner ' . ($animation != 'off' ? 'et-waypoint et_pb_animation_' . $animation:'') . '">';
		
										echo do_action('sb_dtm_loop_archive_start', get_the_ID());
									
										echo do_shortcode('[et_pb_section global_module="' . $loop_layout . '"][/et_pb_section]');
									
										echo do_action('sb_dtm_loop_archive_end', get_the_ID());									
												
										echo '</div>
												</div>
											</div>';							
									
									$i++;
									
								} // endwhile
								
								if ( 'on' === $show_pagination && ! is_search() ) {
										echo '</div> <!-- .et_pb_posts -->';
						
										if ( function_exists( 'wp_pagenavi' ) ) {
												wp_pagenavi();
										} else {
												if ( et_is_builder_plugin_active() ) {
														include( ET_BUILDER_PLUGIN_DIR . 'includes/navigation.php' );
												} else {
														get_template_part( 'includes/navigation', 'index' );
												}
										}
								}
					
								wp_reset_query();
						} else {
								if ( et_is_builder_plugin_active() ) {
										include( ET_BUILDER_PLUGIN_DIR . 'includes/no-results.php' );
								} else {
										get_template_part( 'includes/no-results', 'index' );
								}
						}
			
						$posts = ob_get_contents();
				
						ob_end_clean();
				
						$class = " et_pb_module et_pb_vertical_posts_timeline";
			
						$content .= '<section id="cd-timeline" class="cd-container">' . $posts . '</section>';
						$output = ' <div ' . ( '' !== $module_id ? 'id="' . esc_attr( $module_id ) . '" ' : '' ) . ' class="' . $class . ' ' . $module_class . '">
						' . $content . '
				    </div>';
						
						wp_reset_query();
				
						return $output;
				}
			}
			new et_pb_vertical_post_timeline;
			
			class et_pb_dtm_title_module extends ET_Builder_Module {
                function init() {
                    $this->name = __( 'Timeline - Post Title', 'et_builder' );
                    $this->slug = 'et_pb_dtm_post_title';
            
                    $this->whitelisted_fields = array(
                        'link_content',
                        'module_id',
                        'module_class',
                    );
            
                    $this->fields_defaults = array();
                    $this->main_css_element = '.et_pb_dtm_title';
                    $this->advanced_options = array(
                                        'fonts' => array(
                                                'text'   => array(
                                                                'label'    => esc_html__( 'Text', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} p",
                                                                ),
                                                                'font_size' => array('default' => '14px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                                'headings'   => array(
                                                                'label'    => esc_html__( 'Headings', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} h1, {$this->main_css_element} h2, {$this->main_css_element} h3, {$this->main_css_element} h4",
                                                                ),
                                                                'font_size' => array('default' => '30px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                        ),
                                        'background' => array(
                                                'settings' => array(
                                                        'color' => 'alpha',
                                                ),
                                        ),
                                        'border' => array(),
                                        'custom_margin_padding' => array(
                                                'css' => array(
                                                        'important' => 'all',
                                                ),
                                        ),
                                );
                    $this->custom_css_options = array();
                }
            
                function get_fields() {
                    $fields = array(
                                'link_content' => array(
                                                'label'           => __( 'Link to Content?', 'et_builder' ),
                                                'type'            => 'yes_no_button',
                                                'option_category' => 'configuration',
                                                'options'         => array(
                                                                'on'  => __( 'Yes', 'et_builder' ),
                                                                'off' => __( 'No', 'et_builder' ),
                                                ),
                                                'description'       => __( 'If Yes this will link the item to the contet', 'et_builder' ),
                                ),
                                'admin_label' => array(
                                    'label'       => __( 'Admin Label', 'et_builder' ),
                                    'type'        => 'text',
                                    'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
                                ),
                                'module_id' => array(
                                    'label'           => __( 'CSS ID', 'et_builder' ),
                                    'type'            => 'text',
                                    'option_category' => 'configuration',
                                    'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
                                ),
                                'module_class' => array(
                                    'label'           => __( 'CSS Class', 'et_builder' ),
                                    'type'            => 'text',
                                    'option_category' => 'configuration',
                                    'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
                                ),
                    );
                    
                    return $fields;
                }
            
                function shortcode_callback( $atts, $content = null, $function_name ) {
                    $module_id          = $this->shortcode_atts['module_id'];
                    $module_class       = $this->shortcode_atts['module_class'];
                    $link_content       = $this->shortcode_atts['link_content'];
            
                    $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
            
                    //////////////////////////////////////////////////////////////////////
                    
                    $content = '<h1 itemprop="name" class="page_title entry-title">';
                    
                    if ($link_content == 'on') {
                                $content .= '<a href="' . get_permalink(get_the_ID()) . '">';
                    }
                    
                    $content .= get_the_title();
                    
                    if ($link_content == 'on') {
                                $content .= '</a>';
                    }
                    
                    $content .= '</h1>';
                      
                     //////////////////////////////////////////////////////////////////////
            
                    $output = sprintf(
                        '<div%5$s class="%1$s%3$s%6$s">
                            %2$s
                        %4$s',
                        'clearfix ',
                        $content,
                        esc_attr( 'et_pb_module' ),
                        '</div>',
                        ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                        ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
                    );
            
                    return $output;
                }
            }
        
            new et_pb_dtm_title_module();
						
						class et_pb_dtm_gallery_module extends ET_Builder_Module {
                function init() {
                        $this->name = __( 'Timeline - Featured Image', 'et_builder' );
                        $this->slug = 'et_pb_dtm_featured_image';
        
                        $this->whitelisted_fields = array(
                                'image_size',
                                'alt',
                                'title_text',
                                'show_in_lightbox',
                                'url',
                                'url_new_window',
                                'animation',
                                'sticky',
                                'align',
                                'admin_label',
                                'module_id',
                                'module_class',
                                'max_width',
                                'force_fullwidth',
                                'always_center_on_mobile',
                                'use_overlay',
                                'overlay_icon_color',
                                'hover_overlay_color',
                                'hover_icon',
                                'max_width_tablet',
                                'max_width_phone',
                        );
        
                        $this->fields_defaults = array(
                                'show_in_lightbox'        => array( 'off' ),
                                'url_new_window'          => array( 'off' ),
                                'animation'               => array( 'left' ),
                                'sticky'                  => array( 'off' ),
                                'align'                   => array( 'left' ),
                                'force_fullwidth'         => array( 'off' ),
                                'always_center_on_mobile' => array( 'on' ),
                                'use_overlay'             => array( 'off' ),
                        );
        
                        $this->advanced_options = array(
                                'border'                => array(),
                                'custom_margin_padding' => array(
                                        'use_padding' => false,
                                        'css' => array(
                                                'important' => 'all',
                                        ),
                                ),
                        );
                }
            
                function get_fields() {
                        
                        $options = array();
                        $sizes = get_intermediate_image_sizes();
                        
                        foreach ($sizes as $size) {
                                    $options[$size] = $size;
                        }
                        
                        // List of animation options
                        $animation_options_list = array(
                                'left'    => esc_html__( 'Left To Right', 'et_builder' ),
                                'right'   => esc_html__( 'Right To Left', 'et_builder' ),
                                'top'     => esc_html__( 'Top To Bottom', 'et_builder' ),
                                'bottom'  => esc_html__( 'Bottom To Top', 'et_builder' ),
                                'fade_in' => esc_html__( 'Fade In', 'et_builder' ),
                                'off'     => esc_html__( 'No Animation', 'et_builder' ),
                        );
        
                        $animation_option_name       = sprintf( '%1$s-animation', $this->slug );
                        $default_animation_direction = ET_Global_Settings::get_value( $animation_option_name );
        
                        // If user modifies default animation option via Customizer, we'll need to change the order
                        if ( 'left' !== $default_animation_direction && ! empty( $default_animation_direction ) && array_key_exists( $default_animation_direction, $animation_options_list ) ) {
                                // The options, sans user's preferred direction
                                $animation_options_wo_default = $animation_options_list;
                                unset( $animation_options_wo_default[ $default_animation_direction ] );
        
                                // All animation options
                                $animation_options = array_merge(
                                        array( $default_animation_direction => $animation_options_list[$default_animation_direction] ),
                                        $animation_options_wo_default
                                );
                        } else {
                                // Simply copy the animation options
                                $animation_options = $animation_options_list;
                        }
        
                        $fields = array(
                                'image_size' => array(
                                                'label'           => __( 'Image Size', 'et_builder' ),
                                                'type'            => 'select',
                                                'options'         => $options,
                                                'description'       => __( 'Pick a size for the featured image from the list. Leave blank for default.', 'et_builder' ),
                                    ),
                                'alt' => array(
                                        'label'           => esc_html__( 'Image Alternative Text', 'et_builder' ),
                                        'type'            => 'text',
                                        'option_category' => 'basic_option',
                                        'description'     => esc_html__( 'This defines the HTML ALT text. A short description of your image can be placed here.', 'et_builder' ),
                                ),
                                'title_text' => array(
                                        'label'           => esc_html__( 'Image Title Text', 'et_builder' ),
                                        'type'            => 'text',
                                        'option_category' => 'basic_option',
                                        'description'     => esc_html__( 'This defines the HTML Title text.', 'et_builder' ),
                                ),
                                'show_in_lightbox' => array(
                                        'label'             => esc_html__( 'Open in Lightbox', 'et_builder' ),
                                        'type'              => 'yes_no_button',
                                        'option_category'   => 'configuration',
                                        'options'           => array(
                                                'off' => esc_html__( "No", 'et_builder' ),
                                                'on'  => esc_html__( 'Yes', 'et_builder' ),
                                        ),
                                        'affects'           => array(
                                                '#et_pb_url',
                                                '#et_pb_url_new_window',
                                                '#et_pb_use_overlay'
                                        ),
                                        'description'       => esc_html__( 'Here you can choose whether or not the image should open in Lightbox. Note: if you select to open the image in Lightbox, url options below will be ignored.', 'et_builder' ),
                                ),
                                'url' => array(
                                        'label'           => esc_html__( 'Link URL', 'et_builder' ),
                                        'type'            => 'text',
                                        'option_category' => 'basic_option',
                                        'depends_show_if' => 'off',
                                        'affects'         => array(
                                                '#et_pb_use_overlay',
                                        ),
                                        'description'     => esc_html__( 'If you would like your image to be a link, input your destination URL here. No link will be created if this field is left blank.', 'et_builder' ),
                                ),
                                'url_new_window' => array(
                                        'label'             => esc_html__( 'Url Opens', 'et_builder' ),
                                        'type'              => 'select',
                                        'option_category'   => 'configuration',
                                        'options'           => array(
                                                'off' => esc_html__( 'In The Same Window', 'et_builder' ),
                                                'on'  => esc_html__( 'In The New Tab', 'et_builder' ),
                                        ),
                                        'depends_show_if'   => 'off',
                                        'description'       => esc_html__( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
                                ),
                                'use_overlay' => array(
                                        'label'             => esc_html__( 'Image Overlay', 'et_builder' ),
                                        'type'              => 'yes_no_button',
                                        'option_category'   => 'layout',
                                        'options'           => array(
                                                'off' => esc_html__( 'Off', 'et_builder' ),
                                                'on'  => esc_html__( 'On', 'et_builder' ),
                                        ),
                                        'affects'           => array(
                                                '#et_pb_overlay_icon_color',
                                                '#et_pb_hover_overlay_color',
                                                '#et_pb_hover_icon',
                                        ),
                                        'depends_default'   => true,
                                        'description'       => esc_html__( 'If enabled, an overlay color and icon will be displayed when a visitors hovers over the image', 'et_builder' ),
                                ),
                                'overlay_icon_color' => array(
                                        'label'             => esc_html__( 'Overlay Icon Color', 'et_builder' ),
                                        'type'              => 'color',
                                        'custom_color'      => true,
                                        'depends_show_if'   => 'on',
                                        'description'       => esc_html__( 'Here you can define a custom color for the overlay icon', 'et_builder' ),
                                ),
                                'hover_overlay_color' => array(
                                        'label'             => esc_html__( 'Hover Overlay Color', 'et_builder' ),
                                        'type'              => 'color-alpha',
                                        'custom_color'      => true,
                                        'depends_show_if'   => 'on',
                                        'description'       => esc_html__( 'Here you can define a custom color for the overlay', 'et_builder' ),
                                ),
                                'hover_icon' => array(
                                        'label'               => esc_html__( 'Hover Icon Picker', 'et_builder' ),
                                        'type'                => 'text',
                                        'option_category'     => 'configuration',
                                        'class'               => array( 'et-pb-font-icon' ),
                                        'renderer'            => 'et_pb_get_font_icon_list',
                                        'renderer_with_field' => true,
                                        'depends_show_if'     => 'on',
                                        'description'       => esc_html__( 'Here you can define a custom icon for the overlay', 'et_builder' ),
                                ),
                                'animation' => array(
                                        'label'             => esc_html__( 'Animation', 'et_builder' ),
                                        'type'              => 'select',
                                        'option_category'   => 'configuration',
                                        'options'           => $animation_options,
                                        'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
                                ),
                                'sticky' => array(
                                        'label'             => esc_html__( 'Remove Space Below The Image', 'et_builder' ),
                                        'type'              => 'yes_no_button',
                                        'option_category'   => 'layout',
                                        'options'           => array(
                                                'off'     => esc_html__( 'No', 'et_builder' ),
                                                'on'      => esc_html__( 'Yes', 'et_builder' ),
                                        ),
                                        'description'       => esc_html__( 'Here you can choose whether or not the image should have a space below it.', 'et_builder' ),
                                ),
                                'align' => array(
                                        'label'           => esc_html__( 'Image Alignment', 'et_builder' ),
                                        'type'            => 'select',
                                        'option_category' => 'layout',
                                        'options' => array(
                                                'left'   => esc_html__( 'Left', 'et_builder' ),
                                                'center' => esc_html__( 'Center', 'et_builder' ),
                                                'right'  => esc_html__( 'Right', 'et_builder' ),
                                        ),
                                        'description'       => esc_html__( 'Here you can choose the image alignment.', 'et_builder' ),
                                ),
                                'max_width' => array(
                                        'label'           => esc_html__( 'Image Max Width', 'et_builder' ),
                                        'type'            => 'text',
                                        'option_category' => 'layout',
                                        'tab_slug'        => 'advanced',
                                        'mobile_options'  => true,
                                        'validate_unit'   => true,
                                ),
                                'force_fullwidth' => array(
                                        'label'             => esc_html__( 'Force Fullwidth', 'et_builder' ),
                                        'type'              => 'yes_no_button',
                                        'option_category'   => 'layout',
                                        'options'           => array(
                                                'off' => esc_html__( "No", 'et_builder' ),
                                                'on'  => esc_html__( 'Yes', 'et_builder' ),
                                        ),
                                        'tab_slug'    => 'advanced',
                                ),
                                'always_center_on_mobile' => array(
                                        'label'             => esc_html__( 'Always Center Image On Mobile', 'et_builder' ),
                                        'type'              => 'yes_no_button',
                                        'option_category'   => 'layout',
                                        'options'           => array(
                                                'on'  => esc_html__( 'Yes', 'et_builder' ),
                                                'off' => esc_html__( "No", 'et_builder' ),
                                        ),
                                        'tab_slug'    => 'advanced',
                                ),
                                'max_width_tablet' => array(
                                        'type' => 'skip',
                                ),
                                'max_width_phone' => array(
                                        'type' => 'skip',
                                ),
                                'disabled_on' => array(
                                        'label'           => esc_html__( 'Disable on', 'et_builder' ),
                                        'type'            => 'multiple_checkboxes',
                                        'options'         => array(
                                                'phone'   => esc_html__( 'Phone', 'et_builder' ),
                                                'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
                                                'desktop' => esc_html__( 'Desktop', 'et_builder' ),
                                        ),
                                        'additional_att'  => 'disable_on',
                                        'option_category' => 'configuration',
                                        'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
                                ),
                                'admin_label' => array(
                                        'label'       => esc_html__( 'Admin Label', 'et_builder' ),
                                        'type'        => 'text',
                                        'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
                                ),
                                'module_id' => array(
                                        'label'           => esc_html__( 'CSS ID', 'et_builder' ),
                                        'type'            => 'text',
                                        'option_category' => 'configuration',
                                        'tab_slug'        => 'custom_css',
                                        'option_class'    => 'et_pb_custom_css_regular',
                                ),
                                'module_class' => array(
                                        'label'           => esc_html__( 'CSS Class', 'et_builder' ),
                                        'type'            => 'text',
                                        'option_category' => 'configuration',
                                        'tab_slug'        => 'custom_css',
                                        'option_class'    => 'et_pb_custom_css_regular',
                                ),
                        );
        
                        return $fields;
                }
            
                function shortcode_callback( $atts, $content = null, $function_name ) {
                        $output = '';
                        
                        $image_size               = $this->shortcode_atts['image_size'];
                        $module_id               = $this->shortcode_atts['module_id'];
                        $module_class            = $this->shortcode_atts['module_class'];
                        $alt                     = $this->shortcode_atts['alt'];
                        $title_text              = $this->shortcode_atts['title_text'];
                        $animation               = $this->shortcode_atts['animation'];
                        $url                     = $this->shortcode_atts['url'];
                        $url_new_window          = $this->shortcode_atts['url_new_window'];
                        $show_in_lightbox        = $this->shortcode_atts['show_in_lightbox'];
                        $sticky                  = $this->shortcode_atts['sticky'];
                        $align                   = $this->shortcode_atts['align'];
                        $max_width               = $this->shortcode_atts['max_width'];
                        $max_width_tablet        = $this->shortcode_atts['max_width_tablet'];
                        $max_width_phone         = $this->shortcode_atts['max_width_phone'];
                        $force_fullwidth         = $this->shortcode_atts['force_fullwidth'];
                        $always_center_on_mobile = $this->shortcode_atts['always_center_on_mobile'];
                        $overlay_icon_color      = $this->shortcode_atts['overlay_icon_color'];
                        $hover_overlay_color     = $this->shortcode_atts['hover_overlay_color'];
                        $hover_icon              = $this->shortcode_atts['hover_icon'];
                        $use_overlay             = $this->shortcode_atts['use_overlay'];
        
                        $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
        
                        if ( 'on' === $always_center_on_mobile ) {
                                $module_class .= ' et_always_center_on_mobile';
                        }
        
                        // overlay can be applied only if image has link or if lightbox enabled
                        $is_overlay_applied = 'on' === $use_overlay && ( 'on' === $show_in_lightbox || ( 'off' === $show_in_lightbox && '' !== $url ) ) ? 'on' : 'off';
        
                        if ( '' !== $max_width_tablet || '' !== $max_width_phone || '' !== $max_width ) {
                                $max_width_values = array(
                                        'desktop' => $max_width,
                                        'tablet'  => $max_width_tablet,
                                        'phone'   => $max_width_phone,
                                );
        
                                et_pb_generate_responsive_css( $max_width_values, '%%order_class%%', 'max-width', $function_name );
                        }
        
                        if ( 'on' === $force_fullwidth ) {
                                ET_Builder_Element::set_style( $function_name, array(
                                        'selector'    => '%%order_class%% img',
                                        'declaration' => 'width: 100%;',
                                ) );
                        }
        
                        if ( $this->fields_defaults['align'][0] !== $align ) {
                                ET_Builder_Element::set_style( $function_name, array(
                                        'selector'    => '%%order_class%%',
                                        'declaration' => sprintf(
                                                'text-align: %1$s;',
                                                esc_html( $align )
                                        ),
                                ) );
                        }
        
                        if ( 'center' !== $align ) {
                                ET_Builder_Element::set_style( $function_name, array(
                                        'selector'    => '%%order_class%%',
                                        'declaration' => sprintf(
                                                'margin-%1$s: 0;',
                                                esc_html( $align )
                                        ),
                                ) );
                        }
        
                        if ( 'on' === $is_overlay_applied ) {
                                if ( '' !== $overlay_icon_color ) {
                                        ET_Builder_Element::set_style( $function_name, array(
                                                'selector'    => '%%order_class%% .et_overlay:before',
                                                'declaration' => sprintf(
                                                        'color: %1$s !important;',
                                                        esc_html( $overlay_icon_color )
                                                ),
                                        ) );
                                }
        
                                if ( '' !== $hover_overlay_color ) {
                                        ET_Builder_Element::set_style( $function_name, array(
                                                'selector'    => '%%order_class%% .et_overlay',
                                                'declaration' => sprintf(
                                                        'background-color: %1$s;',
                                                        esc_html( $hover_overlay_color )
                                                ),
                                        ) );
                                }
        
                                $data_icon = '' !== $hover_icon
                                        ? sprintf(
                                                ' data-icon="%1$s"',
                                                esc_attr( et_pb_process_font_icon( $hover_icon ) )
                                        )
                                        : '';
        
                                $overlay_output = sprintf(
                                        '<span class="et_overlay%1$s"%2$s></span>',
                                        ( '' !== $hover_icon ? ' et_pb_inline_icon' : '' ),
                                        $data_icon
                                );
                        }
        
                        if (has_post_thumbnail( get_the_ID() ) ) {
                                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $image_size );
                                    $src = $image[0];
        
                                    $output = sprintf(
                                            '<img src="%1$s" alt="%2$s"%3$s />
                                            %4$s',
                                            esc_url( $src ),
                                            esc_attr( $alt ),
                                            ( '' !== $title_text ? sprintf( ' title="%1$s"', esc_attr( $title_text ) ) : '' ),
                                            'on' === $is_overlay_applied ? $overlay_output : ''
                                    );
                    
                                    if ( 'on' === $show_in_lightbox ) {
                                            $output = sprintf( '<a href="%1$s" class="et_pb_lightbox_image" title="%3$s">%2$s</a>',
                                                    esc_url( $src ),
                                                    $output,
                                                    esc_attr( $alt )
                                            );
                                    } else if ( '' !== $url ) {
                                            $output = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
                                                    esc_url( $url ),
                                                    $output,
                                                    ( 'on' === $url_new_window ? ' target="_blank"' : '' )
                                            );
                                    }
                    
                                    $animation = '' === $animation ? ET_Global_Settings::get_value( 'et_pb_image-animation' ) : $animation;
                    
                                    $output = sprintf(
                                            '<div%5$s class="et_pb_module et-waypoint et_pb_image%2$s%3$s%4$s%6$s">
                                                    %1$s
                                            </div>',
                                            $output,
                                            esc_attr( " et_pb_animation_{$animation}" ),
                                            ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( ltrim( $module_class ) ) ) : '' ),
                                            ( 'on' === $sticky ? esc_attr( ' et_pb_image_sticky' ) : '' ),
                                            ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                                            'on' === $is_overlay_applied ? ' et_pb_has_overlay' : ''
                                    );
                        }
        
                        return $output;
                }
            }
        
            new et_pb_dtm_gallery_module();
						
						class et_pb_dtm_content_module extends ET_Builder_Module {
                function init() {
                    $this->name = __( 'Timeline - Content', 'et_builder' );
                    $this->slug = 'et_pb_dtm_text';
            
                    $this->whitelisted_fields = array(
                        'excerpt_only',
                        'show_read_more',
                        'read_more_label',
                        'admin_label',
                        'module_id',
                        'module_class',
                    );
            
                    $this->fields_defaults = array();
                                $this->main_css_element = '%%order_class%%';
                    
                                $this->advanced_options = array(
                                        'fonts' => array(
                                                'text'   => array(
                                                                'label'    => esc_html__( 'Text', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} p",
                                                                ),
                                                                'font_size' => array('default' => '14px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                                'headings'   => array(
                                                                'label'    => esc_html__( 'Headings', 'et_builder' ),
                                                                'css'      => array(
                                                                        'main' => "{$this->main_css_element} h1, {$this->main_css_element} h2, {$this->main_css_element} h3, {$this->main_css_element} h4",
                                                                ),
                                                                'font_size' => array('default' => '30px'),
                                                                'line_height'    => array('default' => '1.5em'),
                                                ),
                                                'buttons'   => array(
                                                        'label'    => esc_html__( 'Read More Button', 'et_builder' ),
                                                        'css'      => array(
                                                                'main' => "{$this->main_css_element} .et_pb_more_button",
                                                        ),
                                                ),
                                        ),
                                        'background' => array(
                                                'settings' => array(
                                                        'color' => 'alpha',
                                                ),
                                        ),
                                        'border' => array(),
                                        'custom_margin_padding' => array(
                                                'css' => array(
                                                        'important' => 'all',
                                                ),
                                        ),
                                );
                }
            
                function get_fields() {
                    $fields = array(
                                'excerpt_only' => array(
                                                'label'           => __( 'Excerpt Only?', 'et_builder' ),
                                                'type'            => 'yes_no_button',
                                                'option_category' => 'configuration',
                                                'options'         => array(
                                                                'off' => __( 'No', 'et_builder' ),
                                                                'on'  => __( 'Yes', 'et_builder' ),
                                                ),
                                                'description'       => __( 'Should this show content only or excerpt?', 'et_builder' ),
                                ),
                                'show_read_more' => array(
                                                'label'           => __( 'Show Read More?', 'et_builder' ),
                                                'type'            => 'yes_no_button',
                                                'option_category' => 'configuration',
                                                'options'         => array(
                                                                'off' => __( 'No', 'et_builder' ),
                                                                'on'  => __( 'Yes', 'et_builder' ),
                                                ),
                                                'affects'=>array('#et_pb_read_more_label'),
                                                'description'       => __( 'Should a read more button be shown below the content?', 'et_builder' ),
                                ),
                                'read_more_label' => array(
                                                'label'       => __( 'Read More Label', 'et_builder' ),
                                                'type'        => 'text',
                                                'depends_show_if'=>'on',
                                                'description' => __( 'What should the read more button be labelled as? Defaults to "Read More".', 'et_builder' ),
                                ),
                                'admin_label' => array(
                                                'label'       => __( 'Admin Label', 'et_builder' ),
                                                'type'        => 'text',
                                                'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
                                ),
                                'module_id' => array(
                                                'label'           => esc_html__( 'CSS ID', 'et_builder' ),
                                                'type'            => 'text',
                                                'option_category' => 'configuration',
                                                'tab_slug'        => 'custom_css',
                                                'option_class'    => 'et_pb_custom_css_regular',
                                ),
                                'module_class' => array(
                                                'label'           => esc_html__( 'CSS Class', 'et_builder' ),
                                                'type'            => 'text',
                                                'option_category' => 'configuration',
                                                'tab_slug'        => 'custom_css',
                                                'option_class'    => 'et_pb_custom_css_regular',
                                ),
                    );
                    
                    return $fields;
                }
            
                function shortcode_callback( $atts, $content = null, $function_name ) {
                    $module_id          = $this->shortcode_atts['module_id'];
                    $module_class       = $this->shortcode_atts['module_class'];
                    $excerpt_only       = $this->shortcode_atts['excerpt_only'];
                    $show_read_more       = $this->shortcode_atts['show_read_more'];
                    $read_more_label       = $this->shortcode_atts['read_more_label'];
                    
                    $read_more_label = ($read_more_label ? $read_more_label:'Read More');
                    
                    $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
            
                    //////////////////////////////////////////////////////////////////////
                    
                                if ($excerpt_only == 'on') {
                                                ob_start();
                                                the_excerpt();
                                                $content = ob_get_clean();
                                                //$content = apply_filters('the_content', get_the_excerpt());
                                } else {
                                                $content = apply_filters('the_content', get_the_content());
                                }
                                
                                if ($show_read_more == 'on') {
                                                $content .= '<p><a class="button et_pb_button et_pb_more_button" href="' . get_permalink(get_the_ID()) . '">' . $read_more_label . '</a></p>';
                                }
                    
                    //////////////////////////////////////////////////////////////////////
            
                    $output = sprintf(
                        '<div%5$s class="%1$s%3$s%6$s">
                            %2$s
                        %4$s',
                        'clearfix ',
                        $content,
                        esc_attr( 'et_pb_module' ),
                        '</div>',
                        ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
                        ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
                    );
            
                    return $output;
                }
            }
        
            new et_pb_dtm_content_module();
			
			
	}
    }
    
?>