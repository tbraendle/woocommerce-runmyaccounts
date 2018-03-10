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
if (!class_exists('WC_RMA_BACKEND_ABSTRACT')) {

    abstract class WC_RMA_BACKEND_ABSTRACT {

        const VERSION = '1.0';
        const DB_VERSION = '1.0';

        public function create() {

            /**
             * Create Custom Table
             * https://codex.wordpress.org/Creating_Tables_with_Plugins
             */
            // ToDo: Add custom tables for log data
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
            if (self::DB_VERSION > get_option('wc_rma_db_version')) { // Wenn du oben in der Konstante die DB version erhöhst findet beim aktualiseiren, aktivieren ein update statt

                // Hier kannst du beim erhöhen der DB version deine Datenbanktabelle updaten
                /**
                 * update_option() WP Since: 1.0.0
                 * https://codex.wordpress.org/Function_Reference/update_option
                 */
                update_option("wc_rma_db_version", self::DB_VERSION);
            }

            if (self::VERSION > get_option('wc_rma_version')) { // Wenn du oben in der Konstante die version erhöhst findet beim aktualiseiren, aktivieren ein update statt
                
                // Hier kannst du beim erhöhen der version andere sachen machen
                update_option("wc_rma_version", self::VERSION);
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
	     *
	     * @return mixed
	     */
	    public function profile_rma_fields( $fields ) {

		    $fields[ 'rma' ][ 'title' ] = __( 'Settings Run My Account', 'wc-rma' );

		    if (class_exists('WC_RMA_API')) $WC_RMA_API = new WC_RMA_API();

		    $options = $WC_RMA_API->get_customers();
		    $options = array('' => __( 'Please select a RMA customer.', 'wc-rma' )) + $options;

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
