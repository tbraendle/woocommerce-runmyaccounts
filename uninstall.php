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

// Ist die function bereits vorhanden ?
if (!function_exists('mein_plugin_name_uninstall')) {

    /**
     * Deinstallieren
     * @return type
     */
    function mein_plugin_name_uninstall() {

        // Check Admin
        if (is_admin()) {

            /**
             * Kann dbzw. darf der User Plugins löschen
             * https://codex.wordpress.org/Function_Reference/current_user_can
             * https://codex.wordpress.org/Roles_and_Capabilities
             * since 2.0.0
             */
            if (!current_user_can('delete_plugins')) {
                return;
            }

            /**
             * Alle registierten setting wieder entfernen und die option dazu
             * Unregister settings
             * https://codex.wordpress.org/Function_Reference/unregister_setting
             */
            unregister_setting("wc_rma_settings_group", "wc_rma_settings", "");
            delete_option("wc_rma_settings");
            
            // Wir entfernen die beiden versionen die wir erstellt haben
            delete_option('wc_rma_db_version');
            delete_option('wc_rma_version');
            
            // hIer könnte noch die erstellte custom tables entfernt werden und auch sollte, wenn welche erstellt worden sind
        }
    }

    mein_plugin_name_uninstall();
}