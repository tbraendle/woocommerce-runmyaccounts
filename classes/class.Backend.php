<?php
/**
 * class.Backend.php  
 *  
 * @author      Sandro Lucifora
 * @copyright   (c) 2018, Openstream Internet Solutions
 * @link        https://www.openstream.ch/
 * @package     WooCommerce Run My Account
 * @since       1.0  
 */

if (!defined('ABSPATH')) { exit; }

require_once 'class.BackendAbstract.php';

if (!class_exists('WC_RMA_BACKEND')) {

    /**
     * Class  
     * Dann erstellen wir die Klasse und verbinden Sie mit unserer Abstract Klasse  
     */
    class WC_RMA_BACKEND extends WC_RMA_BACKEND_ABSTRACT {

        /**
         * Construct  
         */
        public function __construct() {

            add_action('admin_menu', array($this, 'add_menu')); // admin_menu diese action wird benötigt um die Admin Menüs zu registrieren
            add_action('admin_init', array($this, 'admin_init')); // admin_init diese action wird benötigt um z.B. option, settings und filter zusetzen  
            add_action('plugins_loaded', array($this, 'plugins_loaded')); // plugins_loaded diese action wird bei jedem aufruf der Seite ausgeführt
            add_action('plugins_loaded', array($this, 'plugins_loaded_about'), 1); // plugins_loaded diese action begrenzen wir auf ein einmaligen aufruf, der hier beim aktivieren des plugins genutzt wird
        }

        /**
         * Activate - Diese Funktion wird beim Aufruf register_activation_hook() ausgelöst, doch dies machen wir in der mein-plugin-name.php  
         */
        public function activate() {

            /**
             * set_transient() WP Since: 2.8  
             * https://codex.wordpress.org/Function_Reference/set_transient  
             */
            set_transient('wc-rma-page-activated', 1, 30);

	        //ToDo: Check if cCURL is installed. Is needed for POST requests to RMA
        }

        /**
         * Deactivate - Diese Funktion wird beim Aufruf register_deactivation_hook() ausgelöst, doch dies machen wir in der wc-rma.php
         */
        public function deactivate() {

            //..  
        }

        /**
         * Admin Menus - Dies ist nun die admin_menu action und die funktion, die funktion hätte auch anders benannt werden können,  
         * diese funktion wird auch im Konstruktor aufgerufen, mit add_action()  
         */
	    public function add_menu() {
		    /**
		     * Sub Menu -- Dies ist das untermenü
		     * add_submenu_page() WP Since: 1.5.0
		     * https://developer.wordpress.org/reference/functions/add_submenu_page/
		     */
		    add_submenu_page('woocommerce', // $parent_slug
			    'Run My Accounts - Settings', // $page_title
			    __('Run My Accounts', 'wc-rma'), // $menu_title
			    'manage_options', // $capability
			    'wc-rma-settings', // $menu_slug
			    array($this, 'settings') // $function
		    );
	    }

        /**
         * Menu Download -- Hier machen wir das selbe jedoch fürs erste untermenü -- diese funktion wird in add_menu_page aufgerufen in der funktion add_submenu_page() 
         */
        public function settings() {

            require_once WC_RMA_PFAD . 'html/settings.php';
        }

        /**
         * Admin Init -- Hier initieren wir alles was wir benötigen, 
         * ich habe diese 3 funktionen in die Abstract Klasse verlegt wegen der übersichtlichkeit, diese funktion wird auch im Konstruktor aufgerufen, mit add_action() 
         */
        public function admin_init() {

            $this->init_options(); // Option 
            $this->init_settings(); // Einstellungen 
            $this->init_filter(); // Filter 
        }

        /**
         * Plugins Loaded -- Diese Funktionen sind ebenfalls in der Abstract Klasse für die Übersichtlichkeit.
         */
        public function plugins_loaded() {

            $this->create(); // Create 
            $this->update(); // Update 
        }

        /**
         * Plugins Loaded Once on Activate -- Diese Funktion wird nur einmal aufgerufen wenn das Plugin aktiviert wird,
         * diese funktion wird auch im Konstruktor aufgerufen, mit add_action() 
         */
        public function plugins_loaded_about() {

            /**
             * Wir Prüfen ob transient vorhanden, wenn es nicht vorhanden ist wird nichts gemacht 
             * get_transient() WP Since: 2.8 
             * https://codex.wordpress.org/Function_Reference/get_transient 
             */
            if (!get_transient('wc-rma-page-activated')) {

                return;
            }

            /**
             * Hier löschen wir den transient weil wir nicht wollen, dass die Willkommen-Seite immer wieder aufgerufen wird
             * delete_transient() WP Since: 2.8 
             * https://codex.wordpress.org/Function_Reference/delete_transient 
             */
            delete_transient('wc-rma-page-activated');

            /**
             * Und hier leiten wir nun weiter zur gewünschten Seite in diesem fall habe ich das letzte untermenü genommen 
             * wp_redirect() WP Since: 1.5.1 
             * https://codex.wordpress.org/Function_Reference/wp_redirect 
             */
            wp_redirect(
                    
                    /**
                     * admin_url() WP Since:2.6.0 
                     * https://codex.wordpress.org/Function_Reference/admin_url 
                     */
                    admin_url('admin.php?page=wc-rma-settings')
            );

            exit;
        }

    }

} 