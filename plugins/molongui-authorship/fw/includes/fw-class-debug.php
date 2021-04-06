<?php

namespace Molongui\Authorship\Fw\Includes;
\defined( 'ABSPATH' ) or exit;
if ( !\class_exists( 'Molongui\Authorship\Fw\Includes\Debug' ) )
{
	class Debug
	{
		public function get()
        {
            if ( !\class_exists( 'WP_Debug_Data' ) ) require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
            $data = \WP_Debug_Data::debug_data();
            return $data;
        }
		public function format( $info_array = array(), $type = 'info' )
        {
            if ( empty( $info_array ) ) return false;
            if ( !\class_exists( 'WP_Debug_Data' ) ) require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
            $data = \WP_Debug_Data::format( $info_array, $type );
            return $data;
        }
        public function get_data()
        {
            $data = $this->get();
            $data = $this->format( $data );

            return $data;
        }
        public function get_client_info()
        {
            $browser = new \Molongui\Authorship\Fw\Includes\Browser();

            return array
            (
                'platform'   => $browser->getPlatform() . ' ' . ( $browser->isMobile() ? '(mobile)' : ( $browser->isTablet() ? '(tablet)' : '(desktop)' ) ),
                'browser'    => $browser->getBrowser() . ' ' . $browser->getVersion(),
                'user_agent' => $browser->getUserAgent(),
                'ip'         => \molongui_get_ip(),
            );
        }
        public function get_mail_appendix()
        {
            $appendix = '';
            global $current_user;
            $data   = $this->get();
            $client = $this->get_client_info();
            $css_title    = 'font-size: 14px; font-weight: bold;';
            $css_subtitle = 'font-size: 13px; font-weight: bold; color: #4a4a4a; margin-left: 20px;';
            $css_item     = 'font-size: 12px; font-family: consolas; margin-left: 20px;';
            $css_detail   = 'font-size: 11px; color: #b0b0b0;';
            $css_table_1  = 'border-collapse: collapse; border: 1px solid lightgray; margin-left: 20px;';
            $css_table_2  = 'border-collapse: collapse; border: 1px solid lightgray; width: 100%';
            $css_tr       = 'border: 1px solid lightgray;';
            $css_td_head  = 'font-size: 12px; font-family: consolas; font-weight: bold; border: 1px solid lightgray; background: lightgray;';
            $css_td_title = 'font-size: 12px; font-family: consolas; font-weight: bold; border: 1px solid lightgray; width: 240px;';
            $css_td_value = 'font-size: 12px; font-family: consolas; border: 1px solid lightgray;';
            $appendix .= '<p style="'.$css_title.'">'.__( "Molongui Plugins", 'molongui-authorship' ).'</p>';
            $molonguis = \get_molongui_plugins();
            foreach( $molonguis as $plugin )
            {
                $appendix .= '<p style="'.$css_item.'">'. \esc_html( $plugin['Name'] ) . ' ' . '<span style="'.$css_detail.'">' . \esc_html( $plugin['Version'] ) . '</span>' . '</p>';
            }
            $appendix .= '<p style="'.$css_title.'">' . $data['wp-plugins-active']['label'] . '</p>';
            foreach( $data['wp-plugins-active']['fields'] as $plugin )
            {
                $appendix .= '<p style="'.$css_item.'">' . $plugin['label'] . ' ' . '<span style="'.$css_detail.'">' . $plugin['value'] . '</span>' . '</p>';
            }
            $appendix .= '<p style="'.$css_title.'">' . $data['wp-active-theme']['label'] . '</p>';
            $appendix .= '<p style="'.$css_item.'">' . $data['wp-active-theme']['fields']['name']['value'] . ' ' . '<span style="'.$css_detail.'">' . $data['wp-active-theme']['fields']['version']['value'] .  ' by ' . $data['wp-active-theme']['fields']['author']['value'] . '</span>' . '</p>';
            $appendix .= '<p style="'.$css_title.'">'.__( "Client Browser", 'molongui-authorship' ).'</p>';
            $appendix .= '<p style="'.$css_item.'">'.$client['browser'].' on '.$client['platform'].'</p>';
            $appendix .= '<p style="'.$css_title.'">'.__( "Current User", 'molongui-authorship' ).'</p>';
            $appendix .= '<p style="'.$css_item.'">'.$current_user->display_name.' with registered e-mail '.$current_user->user_email.'</p>';
            $appendix .= '<p style="'.$css_title.'">'.__( "System Report", 'molongui-authorship' ).'</p>';
            $appendix .= '<p style="'.$css_item.'">'.\nl2br( $this->get_data() ).'</p>';
            $appendix .= '<p style="'.$css_title.'">'.__( "Plugin Settings", 'molongui-authorship' ).'</p>';
            foreach ( $molonguis as $plugin )
            {
                $options = \molongui_get_options( $plugin['TextDomain'] );
                $appendix .= '<p style="'.$css_subtitle.'">' . \esc_html( $plugin['Name'] ). ' Options' . '</p>';
                $appendix .= '<table style="'.$css_table_1.'">';
                foreach ( $options as $option_group => $values )
                {
                    $appendix .= '<tr style="'.$css_tr.'"><td style="'.$css_td_head.'">'.$option_group.'</td></tr>';

                    $appendix .= '<tr style="'.$css_tr.'">';
                    if ( !\is_array( $values ) )
                    {
                        $appendix .= '<td style="'.$css_td_value.'">'.$values.'</td>';
                    }
                    else
                    {
                        $appendix .= '<td style="'.$css_td_value.'">';
                        $appendix .= '<table style="'.$css_table_2.'">';
                        foreach ( $values as $key => $item )
                        {
                            $appendix .= '<tr style="'.$css_tr.'">';
                            $appendix .= '<td style="'.$css_td_title.'">'.$key.'</td>';
                            $appendix .= '<td style="'.$css_td_value.'">'.$item.'</td>';
                            $appendix .= '</tr>';
                        }
                        $appendix .= '</table>';
                        $appendix .= '</td>';
                    }
                    $appendix .= '</tr>';
                }
                $appendix .= '</table>';
            }

            return $appendix;
        }

    } // End of class
} // End if_class_exists