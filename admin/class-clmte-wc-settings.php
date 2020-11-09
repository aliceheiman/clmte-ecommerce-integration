<?php
/**
 * Extends the WC_Settings_Page class
 *
 * @link        https://paulmiller3000.com
 * @since       1.0.0
 *
 * @package     clmte
 * @subpackage  clmte/admin
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Clmte_WC_Settings' ) ) {

    /**
     * Settings class
     *
     * @since 1.0.0
     */
    class Clmte_WC_Settings extends WC_Settings_Page {

        /**
         * Constructor
         * @since  1.0
         */
        public function __construct() {
                
            $this->id    = 'clmte';
            $this->label = __( 'CLMTE', 'clmte' );

            // Define all hooks instead of inheriting from parent                    
            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
            add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
            add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
            add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
            
        }


        /**
         * Get sections.
         *
         * @return array
         */
        public function get_sections() {
            $sections = array(
                '' => __( 'Settings', 'clmte' ),
                'log' => __( 'Log', 'clmte' )
            );

            return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
        }


        /**
         * Get settings array
         *
         * @return array
         */
        public function get_settings() {

            global $current_section;
            $prefix = 'clmte_';

            switch ($current_section) {
                case 'log':
                    $settings = array(                              
                            array()
                    );
                    break;
                default:
                     $settings = array(                              
                            array()
                    );                 
            }   

            return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );                   
        }

        /**
         * Output the settings
         */
        public function output() {                  
            $settings = $this->get_settings();

            WC_Admin_Settings::output_fields( $settings );
        }

        /**
         * Save settings
         *
         * @since 1.0
         */
        public function save() {                    
            $settings = $this->get_settings();

            WC_Admin_Settings::save_fields( $settings );
        }

    }

}


return new Clmte_WC_Settings();