<?php

/**
 * uninstall.php
 * 
 * @author      Sandro Lucifora
 * @copyright   (c) 2018, Openstream Internet Solutions
 * @link        https://www.openstream.ch/
 * @package     WooCommerce Run My Account
 * @since       1.0  
 */
if (!defined('ABSPATH')) { exit; }
/**
 * https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
 */
if (!defined('WP_UNINSTALL_PLUGIN')) { exit(); }

// Does function exist?
if (!function_exists('wc_rma')) {

    /**
     * Deinstall
     * @return type
     */
    function wc_rma() {

        // Check Admin
        if (is_admin()) {

            if (!current_user_can('delete_plugins')) {
                return;
            }

            /**
             * Unregister settings
             * https://codex.wordpress.org/Function_Reference/unregister_setting
             */
            unregister_setting("wc_rma_settings_group", "wc_rma_settings", "");
            delete_option("wc_rma_settings");
            
            delete_option('wc_rma_db_version');
            delete_option('wc_rma_version');
        }
    }
    wc_rma();
}