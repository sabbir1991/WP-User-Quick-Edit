<?php
/*
Plugin Name: WP User Quick Edit
Plugin URI: http://sabbirahmed.me/
Description: Quick edit functionality for WP Users menu
Version: 1.0.0
Author: Sabbir Ahmed
Author URI: http://sabbirahmed.me/
License: GPL2
*/

/**
 * Copyright (c) YEAR Sabbir Ahmed (email: sabbir.081070@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WP_User_Quick_Edit class
 *
 * @class WP_User_Quick_Edit The class that holds the entire WP_User_Quick_Edit plugin
 */
class WP_User_Quick_Edit {

    /**
     * Constructor for the WP_User_Quick_Edit class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );

        // Loads frontend scripts and styles
        // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_filter( 'user_row_actions', array( $this, 'add_quick_edit_link' ), 10, 2 );

        add_action( 'admin_footer', array( $this, 'load_inline_edit_template' ), 10 );

    }

    /**
     * Initializes the WP_User_Quick_Edit() class
     *
     * Checks for an existing WP_User_Quick_Edit() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WP_User_Quick_Edit();
        }

        return $instance;
    }

    /**
     * Add quick edit link in users row actions
     *
     * @since 0.1
     *
     * @param array $actions
     * @param object $user_object
     *
     * @return string
     */
    public function add_quick_edit_link( $actions, $user_object ) {
        if ( current_user_can( 'edit_user',  $user_object->ID )  ) {

            $actions['inline hide-if-no-js'] = sprintf(
                '<a href="#" class="user-quick-editinline" data-id="%d" aria-label="%s">%s</a>%s',
                $user_object->ID,
                esc_attr( sprintf( __( 'Quick edit &#8220;%s&#8221; inline' ), $user_object->display_name ) ),
                __( 'Quick&nbsp;Edit' ),
                $this->get_inlineedit_data( $user_object )
            );
        }

        return $actions;
    }

    function get_inlineedit_data( $user_object ) {
        $user_roles = array_intersect( array_values( $user_object->roles ), array_keys( get_editable_roles() ) );
        $user_role  = reset( $user_roles );

        $html  = '<div class="hidden" id="inline_' . $user_object->ID . '">';
        $html .= '<div class="ID">'. $user_object->ID. '</div>';
        $html .= '<div class="user_email">'. $user_object->user_email. '</div>';
        $html .= '<div class="first_name">'. $user_object->first_name. '</div>';
        $html .= '<div class="last_name">'. $user_object->last_name. '</div>';
        $html .= '<div class="role">'. $user_role. '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wpu-quick-edit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Enqueue admin scripts
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts( $hook ) {
        wp_enqueue_style( 'wpu-quick-edit-styles', plugins_url( 'assets/css/style.css', __FILE__ ), false, date( 'Ymd' ) );
        wp_enqueue_script( 'wpu-quick-edit-scripts', plugins_url( 'assets/js/script.js', __FILE__ ), array( 'jquery' ), false, true );

        // $translation_array = array( 'some_string' => __( 'Some string to translate', 'wpu-quick-edit' ), 'a_value' => '10' );
        // wp_localize_script( 'base-plugin-scripts', 'baseplugin', $translation_array ) );
    }

    public function load_inline_edit_template() {
        global $current_screen;

        if ( 'users' == $current_screen->base ) {
            require_once dirname( __FILE__ ) . '/views/inline-edit-template.php';
        }
    }

} // WP_User_Quick_Edit

$wpuqedit = WP_User_Quick_Edit::init();