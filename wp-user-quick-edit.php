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

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_filter( 'user_row_actions', array( $this, 'add_quick_edit_link' ), 10, 2 );

        add_action( 'admin_footer', array( $this, 'load_inline_edit_template' ), 10 );

        add_action( 'wp_ajax_user-inline-save', array( $this, 'save_inline_edit' ) );
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
        $html .= '<div class="rich_editing">'. $user_object->rich_editing. '</div>';
        $html .= '<div class="comment_shortcuts">'. $user_object->comment_shortcuts. '</div>';
        $html .= '<div class="admin_bar_front">'. $user_object->show_admin_bar_front. '</div>';
        $html .= '<div class="user_email">'. $user_object->user_email. '</div>';
        $html .= '<div class="first_name">'. $user_object->first_name. '</div>';
        $html .= '<div class="last_name">'. $user_object->last_name. '</div>';
        $html .= '<div class="nickname">'. $user_object->nickname. '</div>';
        $html .= '<div class="description">'. $user_object->description. '</div>';
        $html .= '<div class="role">'. $user_role. '</div>';
        $html .= '<div class="user_url">'. $user_object->user_url . '</div>';
        $html .= '<div class="display_name">'. $user_object->display_name. '</div>';
        $html .= '<div class="display_name_options">'. implode( ',', $this->displa_publicaly_name( $user_object ) ). '</div>';
        $html .= '</div>';

        return $html;
    }

    public function displa_publicaly_name( $user ) {
        $public_display = array();
        $public_display['display_nickname']  = $user->nickname;
        $public_display['display_username']  = $user->user_login;

        if ( !empty($user->first_name) )
            $public_display['display_firstname'] = $user->first_name;

        if ( !empty($user->last_name) )
            $public_display['display_lastname'] = $user->last_name;

        if ( !empty($user->first_name) && !empty($user->last_name) ) {
            $public_display['display_firstlast'] = $user->first_name . ' ' . $user->last_name;
            $public_display['display_lastfirst'] = $user->last_name . ' ' . $user->first_name;
        }

        if ( !in_array( $user->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
            $public_display = array( 'display_displayname' => $user->display_name ) + $public_display;

        $public_display = array_map( 'trim', $public_display );
        $public_display = array_unique( $public_display );

        return $public_display;
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

        $localize_array = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'wp-user-quick-edit' )
        );
        wp_localize_script( 'wpu-quick-edit-scripts', 'wpUserQE', $localize_array );
    }

    public function load_inline_edit_template() {
        global $current_screen;

        if ( 'users' == $current_screen->base ) {
            require_once dirname( __FILE__ ) . '/views/inline-edit-template.php';
        }
    }

    public function save_inline_edit() {
        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-user-quick-edit' ) ) {
            wp_send_json_error( __( 'Error: Nonce verification failed', 'wpu-quick-edit' ) );
        }

        if ( ! current_user_can('edit_user') ) {
            wp_send_json_error( __( 'Sorry, you haven&#8217;t permission to edit this user', 'wpu-quick-edit' ) );
        }

        if ( ! isset( $_POST['user_id'] ) || empty( $_POST['user_id'] ) ) {
            wp_send_json_error( __( 'Sorry can&#8217;t edit this user', 'wpu-quick-edit' ) );
        }

        $result = edit_user( $_POST['user_id'] );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( $result->get_error_messages() );
        }

        $wp_user_list_table = _get_list_table('WP_Users_List_Table');

        $user_obj = get_userdata( $_POST['user_id'] );
        ob_start();
        echo $wp_user_list_table->single_row( $user_obj );
        $output = ob_get_clean();

        wp_send_json_success( $output );
    }

} // WP_User_Quick_Edit

$wpuqedit = WP_User_Quick_Edit::init();