<?php
/**
 * class.BackendAbstract.php 
 * 
 * @author      Sandro Lucifora
 * @copyright   (c) 2018, Openstream Internet Solutions
 * @link        https://www.openstream.ch/
 * @package     WooCommerce Run My Account
 * @since       1.0  
 */

if (!defined('ABSPATH')) { exit; }

if (!class_exists('WC_RMA_BACKEND_ABSTRACT')) {

    abstract class WC_RMA_BACKEND_ABSTRACT {

        const VERSION = '1.1.0';
        const DB_VERSION = '1.0.0';

	    private static function _table_log() {
		    global $wpdb;
		    return $wpdb->prefix . WC_RMA_LOG_TABLE;
	    }

        public function create() {

            /**
             * Create Custom Table
             * https://codex.wordpress.org/Creating_Tables_with_Plugins
             */

	        global $wpdb;

	        if ($wpdb->get_var("SHOW TABLES LIKE '".self::_table_log()."'") != self::_table_log()) {

		        $charset_collate = $wpdb->get_charset_collate();

		        $sql = 'CREATE TABLE ' . self::_table_log() . ' (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    time datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
                    status text NOT NULL,
                    orderid text NOT NULL, 
                    mode text NOT NULL,
                    message text NOT NULL, 
                    UNIQUE KEY id (id) ) ' . $charset_collate . ';';

		        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		        dbDelta($sql);
	        }

        }

        /**
         * Update Custom Tables
         * Diese funktion wird in der class.Backend.php aufgerufen mit plugins_loaded()
         */
        public function update() {
            /**
             * get_option() WP Since: 1.5.0
             * https://codex.wordpress.org/Function_Reference/get_option
             */
            if (self::DB_VERSION > get_option('wc_rma_db_version')) { // update option if value is different

                // database update if necessary
                /**
                 * update_option() WP Since: 1.0.0
                 * https://codex.wordpress.org/Function_Reference/update_option
                 */
                update_option("wc_rma_db_version", self::DB_VERSION);
            }

            if (self::VERSION > get_option('wc_rma_version')) { // update option if value is different

                update_option("wc_rma_version", self::VERSION);

	            // do necessary stuff for a version update
            }
        }

	    public function delete() {
		    $settings = get_option('wc_rma_settings'); // get settings
		    if ( 'yes' == $settings['rma-delete-settings'] ) {
			    global $wpdb;

			    // drop table
			    $wpdb->query('DROP TABLE IF EXISTS ' . self::_table_log() . ';');

			    // clean all option
			    delete_option('wc_rma_db_version');
			    delete_option('wc_rma_version');
			    delete_option('wc_rma_settings');
		    }
	    }

        /**
         * Hier kannst du alle deine vorhandenen bzw. benötigten option schon anlegen
         * Diese funktion wird in der class.Backend.php aufgerufen mit admin_init()
         */
        public function init_options() {

            /**
             * add_option() WP Since: 1.0.0
             * https://codex.wordpress.org/Function_Reference/add_option
             */
            add_option('wc_rma_version', self::VERSION);
            add_option('wc_rma_db_version', self::DB_VERSION);
        }

        /**
         * Das kannst du benutzen um Short Codes im WP Editor anzulegen
         * Diese funktion wird in der class.Backend.php aufgerufen mit admin_init()
         */
        public function init_filter() {

	        if ( class_exists( 'WooCommerce' ) ) {
		        // Add RMA fields to user profile
		        add_filter( 'woocommerce_customer_meta_fields', array( $this, 'profile_rma_fields' ), 10, 1 );
	        }
        }

        /**
         * 
         */
        public function init_settings() {

            /**
             * register_setting() WP Since: 2.7.0
             * https://codex.wordpress.org/Function_Reference/register_setting
             */
            register_setting(
                    "wc_rma_settings_group", // ID
                    "wc_rma_settings", // Datenbankeintrag
                    array($this, 'save_option') // Funktion die aufgerufen wird
            );

        }

        /**
         * 
         * @param type $input
         * @return boolean
         */
        public function save_option($input) {

            $return = $input;
            if (!empty($_POST) && check_admin_referer('wc-rma-nonce-action', 'wc-rma-nonce')) {

                /**
                 * https://codex.wordpress.org/Function_Reference/current_user_can
                 * https://codex.wordpress.org/Roles_and_Capabilities
                 * since 2.0.0
                 */
                if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                    $return = false;
                }

                return $return;
            }
        }

	    /**
	     * Add RMA fields to user profile
	     *
	     * @param $fields
	     * @return mixed
	     */
	    public function profile_rma_fields( $fields ) {

		    $fields[ 'rma' ][ 'title' ] = __( 'Settings Run My Account', 'wc-rma' );

		    if (class_exists('WC_RMA_API')) $WC_RMA_API = new WC_RMA_API();

		    $options = $WC_RMA_API->get_customers();

		    if( !$options ) {

			    $fields[ 'rma' ][ 'fields' ][ 'rma_customer' ] = array(
				    'label'       => __( 'Customer', 'wc-rma' ),
				    'type'		  => 'select',
				    'options'	  => array('' => __( 'Error while connecting to RMA. Please check your settings.', 'wc-rma' )),
				    'description' => __( 'Select the corresponding RMA customer for this account.', 'wc-rma' )
			    );

			    return $fields;
		    }

		    $options = array('' => __( 'Select customer...', 'wc-rma' )) + $options;

		    $fields[ 'rma' ][ 'fields' ][ 'rma_customer' ] = array(
			    'label'       => __( 'Customer', 'wc-rma' ),
			    'type'		  => 'select',
			    'options'	  => $options,
			    'description' => __( 'Select the corresponding RMA customer for this account.', 'wc-rma' )
		    );

		    unset($WC_RMA_API);

		    $fields[ 'rma' ][ 'fields' ][ 'rma_billing_account' ] = array(
			    'label'       => __( 'Receivables Account', 'wc-rma' ),
			    'type'		  => 'input',
			    'description' => __( 'The receivables account has to be available in RMA. Leave it blank to use default value 1100.', 'wc-rma' )
		    );

		    $fields[ 'rma' ][ 'fields' ][ 'rma_payment_period' ] = array(
			    'label'       => __( 'Payment Period', 'wc-rma' ),
			    'type'		  => 'input',
			    'description' => __( 'How many days has this customer to pay your invoice?', 'wc-rma' )
		    );
		    return $fields;
	    }

    }

}
