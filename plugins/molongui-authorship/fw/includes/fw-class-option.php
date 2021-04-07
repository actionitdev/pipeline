<?php

namespace Molongui\Authorship\Fw\Includes;
\defined( 'ABSPATH' ) or exit;
if ( !\class_exists('Molongui\Authorship\Fw\Includes\Option') )
{
    class Option
    {
        public $_saved;
        public $_tab;
        public $_data;
        public $_premium;
        public $_type;
        public $_id;
        public $_default;
        public $_value;
        public $_class;
        public $_desc;
        public $_step;
        public $_name;
        public $_option_cls;
        public $_options;
        public $_min;
        public $_max;
        public $_args;
        public $_prefix;
        public $_suffix;
        public $_source;
        public $_placeholder;
        public $_editor;
        public $_height;
        public $_upload_title;
        public $_upload_button;
        public $_options_prefix;
        private $is_premium;
        public function __construct( $tab = '', $data = null, $prefix = 'white', $is_premium = false )
        {
            $this->_saved = \get_option( $tab );
            $this->_tab 			= $tab;
            $this->_data 			= $data;
            $this->_premium			= ( isset( $data['premium'] ) ) ? $data['premium'] : false;
            $this->_type			= $data['type'];

            $this->_id				= ( isset( $data['id'] ) ) ? $data['id'] : null;
            $this->_default 		= ( isset( $data['default'] ) ) ? $data['default'] : null;
            $this->_value 			= ( isset( $this->_id ) and isset( $this->_saved[$this->_id] ) ) ? $this->_saved[$this->_id] : $this->_default;

            $this->_class 			= ( isset( $data['class'] ) ) ? $data['class'] : null;
            $this->_desc 			= ( isset( $data['desc'] ) ) ? '<span class="description">'.$data['desc'].'</span>' : null;
            $this->_desc 		   .= ( isset( $data['alert'] ) ) ? '<span class="description alert">'.$data['alert'].'</span>' : null;
            $this->_step 		    = ( isset( $data['step'] ) ) ? $data['step'] : 1;
            $this->_name 			= ( isset( $data['name'] ) ) ? \esc_html($data['name']) : null;
            $this->_option_cls 		= ( isset( $data['option_cls'] ) ) ? $data['option_cls'] : null;
            $this->_options 		= ( isset( $data['options'] ) ) ? $data['options'] : null;
            $this->_min 			= ( isset( $data['min'] ) ) ? $data['min'] : null;
            $this->_max 			= ( isset( $data['max'] ) ) ? $data['max'] : null;
            $this->_args			= ( isset( $data['args'] ) ) ? $data['args'] : array();
            $this->_prefix 			= ( isset( $data['prefix'] ) ) ? $data['prefix'] : null;
            $this->_suffix 			= ( isset( $data['suffix'] ) ) ? $data['suffix'] : null;
            $this->_source 			= ( isset( $data['source'] ) ) ? $data['source'] : null;
            $this->_placeholder 	= ( isset( $data['placeholder'] ) ) ? $data['placeholder'] : null;
            $this->_editor 			= ( isset( $data['editor'] ) ) ? $data['editor_settings'] : null;
            $this->_height 			= ( isset( $data['height'] ) ) ? $data['height'] : null;
            $this->_link            = ( isset( $data['link'] ) ) ? $data['link'] : '';
            $this->_upload_title 	= __( 'Insert ', 'molongui-bump-offer' ) . $this->_name;
            $this->_upload_button	= __( 'Choose as ', 'molongui-bump-offer' ) . $this->_name;
            $this->_options_prefix	= $prefix;
            $this->is_premium = $is_premium;
        }
        private function _help()
        {
            $help = '';

            if ( !empty( $this->_data['help'] ) )
            {
                \ob_start();
                ?>
                <div class="m-support-info">
                    <button class="m-info-popup m-info-popup-button">
                        <svg class="gridicon gridicons-info-outline needs-offset" height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g><path d="M13 9h-2V7h2v2zm0 2h-2v6h2v-6zm-1-7c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8m0-2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2z"></path></g>
                        </svg>
                        <span class="screen-reader-text"><?php _e( "Learn more", 'molongui-bump-offer' ); ?></span>
                    </button>
                </div>
                <div class="ui popup mini left center transition">
                    <div class="m-support-info-description">
                        <?php echo ( \is_array( $this->_data['help'] ) ? $this->_data['help']['text'] : $this->_data['help'] ); ?>
                    </div>
                    <?php if ( !empty( $this->_data['help']['link'] ) ) : ?>
                        <div class="m-support-info-link">
                            <a href="<?php echo \esc_url( $this->_data['help']['link'] ); ?>?source=<?php echo 'settings-'.\str_replace( '_', '-', $this->_data['id'] ); ?>&amp;site=<?php echo \molongui_get_domain(); ?>" target="_blank"><?php _e( "Learn more", 'molongui-bump-offer' ); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                $help .= \ob_get_clean();
            }

            return $help;
        }
        private function prepend()
        {
            $help = $this->_help();

            $html  = '<div class="m-card"'. ( !empty( $this->_data['deps'] ) ? ' data-deps="'.$this->_data['deps'].'"'  : '' ) . ' >';
            $html .= $help;
            $html .= !empty( $this->_data['title'] ) ? '<div class="m-option-title">'.$this->_data['title'].'</div>'  : '';
            $html .= !empty( $this->_data['desc']  ) ? '<p class="m-option-description">'.$this->_data['desc'].'</p>' : '';
            $html .= '<div class="m-option">';

            return $html;
        }
        private function append()
        {
            $html  = '';
            $html .= '</div>'; // Close .m-option
            $html .= '</div>'; // Close .m-card

            return $html;
        }
        public function __toString()
        {
            switch ( $this->_type )
            {
                case 'notice':          return $this->notice();          break;
                case 'title':           return $this->title();           break;
                case 'text':            return $this->text();            break;
                case 'inline-text':     return $this->inline_text();     break;
                case 'color':           return $this->color();           break;
                case 'textarea':        return $this->textarea();        break;
                case 'radio':           return $this->radio();           break;
                case 'radio-text':      return $this->radio_text();      break;
                case 'number':          return $this->number();          break;
                case 'inline-number':   return $this->inline_number();   break;
                case 'slider':          return $this->slider();          break;
                case 'button':          return $this->button();          break;
                case 'export':          return $this->export();          break;
                case 'header':          return $this->header();          break;
                case 'link':            return $this->link();            break;
                case 'toggle':          return $this->toggle();          break;
                case 'toggle-group':    return $this->toggle_group();    break;
                case 'dropdown':        return $this->dropdown();        break;
                case 'inline-dropdown': return $this->inline_dropdown(); break;
                case 'banner':          return $this->banner();          break;
                case 'select_wp_page':  return $this->select_wp_page();  break;
                default	:               return '';
            }
        }
        private function title()
        {
            return '<h2 class="m-section-title">'.$this->_data['label'].'</h2>';
        }
        private function header()
        {
            $output = '';
            $button = ( empty( $this->_data['button'] ) or !$this->_data['button']['display'] ) ? false : true;

            \ob_start();
            ?>
            <div class="m-card m-card-header <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>" <?php echo empty( $this->_data['id'] ) ? '' : 'id="'.$this->_data['id'].'"'; ?> <?php echo ( empty ( $this->_data['deps'] ) ? '' : 'data-deps="'.$this->_data['deps'].'"' ); ?>>
                <div class="m-card-header__label">
                    <span class="m-card-header__label-text"><?php echo $this->_data['label']; ?></span>
                </div>
                <?php if ( $button ) : ?>
                    <div class="m-card-header__actions">
                        <?php switch ( $this->_data['button']['type'] ) :
                            case 'input': ?>
                                <input type="file" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'" name="'.$this->_data['button']['id'].'"'; ?> class="m-file-upload" accept="<?php echo $this->_data['button']['accept']; ?>" data-multiple-caption="{count} files selected" <?php echo ( $this->_data['button']['multi'] ? 'multiple' : '' ); ?> />
                                <label for="<?php echo empty( $this->_data['button']['id'] ) ? '' : $this->_data['button']['id']; ?>" class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></label>
                                <?php break; ?>
                            <?php case 'download': ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                                <?php break; ?>
                            <?php case 'action': ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                                <?php break; ?>
                            <?php case 'link': ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                                <?php break; ?>
                            <?php case 'save':default: ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button m-button-save is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                                <?php break; ?>
                            <?php endswitch; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            $output .= \ob_get_clean();

            return $output;
        }
        private function link()
        {
            $output = '';
            \ob_start();
            ?>
            <a class="m-card is-card-link is-compact" <?php echo empty( $this->_data['id'] ) ? '' : 'id="'.$this->_data['id'].'"'; ?> href="<?php echo $this->_data['href']; ?>" target="<?php echo $this->_data['target']; ?>" title="<?php echo ( empty( $this->_data['help'] ) ? '' : $this->_data['help'] ); ?>" <?php echo ( empty ( $this->_data['deps'] ) ? '' : 'data-deps="'.$this->_data['deps'].'"' ); ?>>
                <svg class="gridicon gridicons-external m-card__link-indicator" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M19 13v6c0 1.105-.895 2-2 2H5c-1.105 0-2-.895-2-2V7c0-1.105.895-2 2-2h6v2H5v12h12v-6h2zM13 3v2h4.586l-7.793 7.793 1.414 1.414L19 6.414V11h2V3h-8z"></path></g></svg>
                <?php echo $this->_data['label']; ?>
            </a>
            <?php
            $output .= \ob_get_clean();

            return $output;
        }
        private function banner()
        {
            $output = '';
            $title  = empty( $this->_data['title'] )  ? $this->_data['label'] : $this->_data['title'];
            $desc   = empty( $this->_data['desc'] )   ? false : true;
            $button = empty( $this->_data['button'] ) ? false : true;

            \ob_start();
            ?>
            <div class="m-card m-banner has-call-to-action is-upgrade-premium" id="<?php echo $this->_data['id'].'_ad'; ?>" <?php echo ( empty ( $this->_data['deps'] ) ? '' : 'data-deps="'.$this->_data['deps'].'"' ); ?>>
                <div class="m-banner__icon-plan">
                    <div class="m-plan-icon">
                        <div class="m-plan-icon__text">
                            <?php _e( 'PRO', 'molongui-bump-offer' ); ?>
                        </div>
                    </div>
                </div>
                <div class="m-banner__content">
                    <div class="m-banner__info">
                        <div class="m-banner__title"><?php echo $title; ?></div>
                        <?php if ( $desc ) : ?>
                            <div class="m-banner__description"><?php echo $this->_data['desc']; ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ( $button ) : ?>
                        <div class="m-banner__action">
                            <a href="<?php echo $this->_data['button']['href']; ?>?source=<?php echo 'settings-'.str_replace( '_', '-', $this->_data['id'] ); ?>&amp;site=<?php echo molongui_get_domain(); ?>" target="<?php echo $this->_data['button']['target']; ?>" type="button" class="m-button is-compact is-primary <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $output .= \ob_get_clean();

            return $output;
        }
        private function notice()
        {
            $title = empty( $this->_data['title'] ) ? '' : $this->_data['title'];
            $title = empty( $this->_data['link']  ) ? $title : '<a href="'.\esc_url( $this->_data['link'] ).'" target="_blank">'.$this->_data['title'].'</a>';
            $help  = $this->_help();

            $output  = '';
            $output .= '<div class="m-card"' . ( empty( $this->_data['id'] ) ? '' : 'id="'.$this->_data['id'].'"' ) . '>';
            $output .= $help;
            $output .= !empty( $this->_data['title'] ) ? '<div class="m-option-title">'.$title.'</div>'  : '';
            $output .= !empty( $this->_data['desc']  ) ? '<p class="m-option-description">'.$this->_data['desc'].'</p>' : '';
            $output .= '</div>';

            return $output;
        }
        private function toggle()
        {
            $output  = $this->prepend();

            $output .= '<label for="'.$this->_id.'" class="custom-switch m-toggle '.$this->_data['class'].'">';
            $output .= '<input type="checkbox" class="custom-switch-input" id="'.$this->_id.'" name="'.$this->_tab.'['.$this->_id.']" '.\checked( $this->_value, true, false ).'>';
            $output .= '<span class="custom-switch-indicator"></span>';
            $output .= '<span class="custom-switch-description">'.$this->_data['label'].'</span>';
            $output .= '</label>';

            $output .= $this->append();

            return $output;
        }
        private function toggle_group()
        {
            $output  = $this->prepend();
            $output .= '<div class="m-toggle-group">';

            foreach( $this->_data['toggles'] as $toggle )
            {
                $value = ( ( isset( $toggle['id'] ) and isset( $this->_saved[$toggle['id']] ) ) ? \esc_html( $this->_saved[$toggle['id']] ) : $toggle['default'] );

                $output .= '<label for="'.$toggle['id'].'" class="custom-switch m-toggle">';
                $output .= '<input type="checkbox" class="custom-switch-input" id="'.$toggle['id'].'" name="'.$this->_tab.'['.$toggle['id'].']" '.\checked( $value, true, false ).'>';
                $output .= '<span class="custom-switch-indicator"></span>';
                $output .= '<span class="custom-switch-description">'.$toggle['label'].'</span>';
                $output .= '</label>';
            }

            $output .= '</div>';
            $output .= $this->append();

            return $output;
        }
        private function dropdown()
        {
            $value  = $this->_value;
            $multi  = empty( $this->_data['atts']['multi']  ) ? '' : 'multiple';
            $search = empty( $this->_data['atts']['search'] ) ? '' : 'search';

            $output = $this->prepend();
            \ob_start();
            ?>
            <div class="ui dropdown selection fluid <?php echo $multi; ?> <?php echo $search; ?> <?php echo $this->_data['class']; ?>">
                <input type="hidden" id="<?php echo $this->_id; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" value="<?php echo $value; ?>">
                <i class="dropdown icon"></i>
                <div class="text default"><?php echo $this->_data['default']; ?></div>
                <div class="menu">
                    <!--
                    <div class="ui icon search input">
                        <i class="search icon"></i>
                        <input type="text" placeholder="Search tags...">
                    </div>
                    <div class="divider"></div>-->
                    <?php foreach( $this->_data['options'] as $option ) : ?>
                        <div class="item <?php echo ( !empty( $option['disabled'] ) ? 'disabled' : '' ); ?>" data-value="<?php echo $option['id']; ?>">
                            <?php if ( !empty( $option['icon'] ) ) : ?><i class="<?php echo $option['icon']; ?>"></i><?php endif; ?>
                            <?php echo $option['label']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function inline_dropdown()
        {
            $saved = $this->_value;
            $value = !empty( $saved ) ? $this->_data['options'][$saved]['label'] : ( !empty( $this->_data['default'] ) ? $this->_data['options'][$this->_data['default']]['label'] : $this->_data['options'][\array_keys( $this->_data['options'] )[0]]['label'] );
            $tmp = \explode( '{input}', $this->_data['label'] );
            foreach ( $tmp as $key => $part ) if ( !empty( $part ) ) $tmp[$key] = '<label class="label-inline-dropdown" for="'.$this->_id.'">'.$part.'</label>';
            $label = $tmp[0].'{input}'.$tmp[1];

            \ob_start();
            ?>
            <div class="ui dropdown inline <?php echo $this->_data['class']; ?>">
                <input type="text" id="<?php echo $this->_id; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" value="<?php echo $saved; ?>">
                <div class="text"><?php echo $value; ?></div>
                <i class="dropdown icon"></i>
                <div class="menu transition hidden">
                    <?php foreach( $this->_data['options'] as $id => $option ) : ?>
                        <div class="item <?php echo ( $saved === $id ? 'active' : '' ); ?> <?php echo ( !empty( $option['disabled'] ) ? 'disabled' : '' ); ?>" data-text="<?php echo $option['label']; ?>" data-value="<?php echo $id; ?>">
                            <?php if ( !empty( $option['icon'] ) ) : ?><i class="<?php echo $option['icon']; ?>"></i><?php endif; ?>
                            <?php echo $option['label']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php

            $inline  = \ob_get_clean();
            $output  = $this->prepend();
            $output .= \str_replace( '{input}', $inline, $label );
            $output .= $this->append();

            return $output;
        }
        private function select_wp_page()
        {
            $args = array
            (
                'child_of'    => 0,
                'sort_order'  => 'ASC',
                'sort_column' => 'post_title',
                'exclude'     => array(),
                'include'     => array(),
                'authors'     => '',
                'parent'      => -1,
                'number'      => 0,
                'offset'      => 0,
                'post_type'   => 'page',
                'post_status' => 'publish',
            );
            $wp_pages = \get_pages( $args );
            $options = array();
            foreach ( $wp_pages as $wp_page )
            {
                $options[$wp_page->ID]['label'] = $wp_page->post_title;
            }
            $this->_data['options'] = $options;
            $this->_data['class']   = 'search'; // Add a scroll if more than 8 items to list.
            return $this->inline_dropdown();
        }
        private function radio()
        {
            $output = $this->prepend();
            \ob_start();
            ?>
            <div class="custom-controls-stacked">
                <?php foreach( $this->_data['options'] as $key => $option ) : ?>
                    <label class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="<?php echo $this->_id . '_' . $key; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" data-id="<?php echo $this->_id; ?>" value="<?php echo $option['value']; ?>" <?php \checked( $this->_value, $option['value'], true ); ?>>
                        <div class="custom-control-label"><?php echo $option['label']; ?></div>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function radio_text()
        {
            $output = $this->prepend();

            \ob_start();
            ?>
            <div class="selectgroup <?php echo $this->_id; ?>">
                <?php foreach( $this->_data['options'] as $value => $label ) : ?>
                    <label class="selectgroup-item" for="<?php echo $this->_id.'_'.$value; ?>">
                        <input type="radio" id="<?php echo $this->_id.'_'.$value; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" data-id="<?php echo $this->_id; ?>" value="<?php echo $value; ?>" class="selectgroup-input" <?php \checked( $this->_value, $value, true ); ?>>
                        <span class="selectgroup-button"><?php echo $label; ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function number()
        {
            $output = $this->prepend();
            \ob_start();
            ?>
            <div class="m-number <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>">
                <label class="" for="<?php echo $this->_id; ?>">
                    <?php echo $this->_data['label']; ?>
                </label>
                <input type="number" id="<?php echo $this->_id; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" value="<?php echo $this->_value; ?>" class="" min="<?php echo $this->_min; ?>" max="<?php echo $this->_max; ?>">
            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function inline_number()
        {
            $output = $this->prepend();

            $input  = '<input type="number" id="'. $this->_id .'" name="'. $this->_tab.'['.$this->_id.']' .'" value="'. $this->_value .'" class="">';
            $inline = \str_replace( '{input}', $input, $this->_data['label'] );

            \ob_start();
            ?>
            <div class="m-inline-number <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>">
                <label class="" for="<?php echo $this->_id; ?>">
                    <?php echo $inline; ?>
                </label>
            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function text()
        {
            $output = $this->prepend();
            \ob_start();
            ?>
            <div class="m-text <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>">
                <label class="" for="<?php echo $this->_id; ?>">
                    <?php echo $this->_data['label']; ?>
                </label>
                <input type="text" id="<?php echo $this->_id; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" value="<?php echo $this->_value; ?>" class="" placeholder="<?php echo $this->_data['placeholder']; ?>">

            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function inline_text()
        {
            $output = $this->prepend();

            $input  = '<input type="text" id="'. $this->_id .'" name="'. $this->_tab.'['.$this->_id.']' .'" value="'. $this->_value .'" class="" placeholder="'.$this->_data['placeholder'].'">';
            $inline = \str_replace( '{input}', $input, $this->_data['label'] );

            \ob_start();
            ?>
            <div class="m-inline-text <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>">
                <label class="" for="<?php echo $this->_id; ?>">
                    <?php echo $inline; ?>
                </label>
            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function color()
        {
            $output = $this->prepend();
            \ob_start();
            ?>
            <div class="m-color <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>">
                <label class="" for="<?php echo $this->_id; ?>">
                    <?php echo $this->_data['label']; ?>
                </label>
                <input type="text" class="colorpicker" id="<?php echo $this->_id; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" value="<?php echo $this->_value; ?>" placeholder="<?php echo $this->_data['placeholder']; ?>">

            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function textarea()
        {
            $output = $this->prepend();
            \ob_start();
            ?>
            <div class="m-textarea <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>">
                <label class="" for="<?php echo $this->_id; ?>">
                    <?php echo $this->_data['label']; ?>
                </label>
                <textarea id="<?php echo $this->_id; ?>" name="<?php echo $this->_tab.'['.$this->_id.']'; ?>" rows="<?php echo $this->_data['rows']; ?>" placeholder="<?php echo $this->_data['placeholder']; ?>"><?php echo $this->_value; ?></textarea>
            </div>
            <?php
            $output .= \ob_get_clean();
            $output .= $this->append();

            return $output;
        }
        private function button()
        {
            $output = '';
            $button = ( empty( $this->_data['button'] ) or !$this->_data['button']['display'] ) ? false : true;

            \ob_start();
            ?>
            <div class="m-card m-card-button <?php echo ( empty ( $this->_data['class'] ) ? '' : $this->_data['class'] ); ?>" <?php echo empty( $this->_data['id'] ) ? '' : 'id="'.$this->_data['id'].'"'; ?> <?php echo ( empty ( $this->_data['deps'] ) ? '' : 'data-deps="'.$this->_data['deps'].'"' ); ?>>
                <div class="m-card-button__label">
                    <span class="m-card-button__label-text"><?php echo $this->_data['label']; ?></span>
                </div>
                <?php if ( $button ) : ?>
                    <div class="m-card-button__actions">
                        <?php switch ( $this->_data['button']['type'] ) :
                            case 'input': ?>
                                <input type="file" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'" name="'.$this->_data['button']['id'].'"'; ?> class="m-file-upload" accept="<?php echo $this->_data['button']['accept']; ?>" data-multiple-caption="{count} files selected" <?php echo ( $this->_data['button']['multi'] ? 'multiple' : '' ); ?> />
                                <label for="<?php echo empty( $this->_data['button']['id'] ) ? '' : $this->_data['button']['id']; ?>" class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></label>
                            <?php break; ?>
                            <?php case 'download': ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                            <?php break; ?>
                            <?php case 'action': ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                            <?php break; ?>
                            <?php case 'link': ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                            <?php break; ?>
                            <?php case 'save':default: ?>
                                <button type="submit" <?php echo empty( $this->_data['button']['id'] ) ? '' : 'id="'.$this->_data['button']['id'].'"'; ?> <?php echo empty( $this->_data['button']['disabled'] ) ? '' : 'disabled=""'; ?> class="m-button m-button-save is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></button>
                            <?php break; ?>
                            <?php endswitch; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            $output .= \ob_get_clean();

            return $output;
        }
        function export()
        {
            $output = '';
            $button = ( empty( $this->_data['button'] ) or !$this->_data['button']['display'] ) ? false : true;
            $all_options = \wp_load_alloptions();
            $options_data = array();
            foreach( $all_options as $option_name => $this->_value )
            {
                if ( \substr( $option_name, 0, \strlen( $this->_options_prefix ) ) === $this->_options_prefix ) $options_data[$option_name] = $this->_value;
            }

            \ob_start();
            ?>
            <div class="m-card m-card-button <?php echo $this->_data['class']; ?>">
                <div class="m-card-button__label">
                    <span class="m-card-button__label-text"><?php echo $this->_data['label']; ?></span>
                </div>
                <?php if ( $button ) : ?>
                    <div class="m-card-button__actions">
                        <textarea readonly id="export_field" name="export_field"><?php echo \base64_encode( \json_encode( $options_data ) ); ?></textarea>
                        <label id="<?php echo empty( $this->_data['button']['id'] ) ? '' : $this->_data['button']['id']; ?>" for="<?php echo empty( $this->_data['button']['id'] ) ? '' : $this->_data['button']['id']; ?>" class="m-button is-compact <?php echo $this->_data['button']['class']; ?>"><?php echo $this->_data['button']['label']; ?></label>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            $output .= \ob_get_clean();

            return $output;
        }

    } // End of class
} // End if_class_exists