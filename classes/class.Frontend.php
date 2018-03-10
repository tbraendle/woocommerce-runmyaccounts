<?php

/**
 * class.Frontend.php 
 * 
 * @author      Sandro Lucifora
 * @copyright   (c) 2018, Openstream Internet Solutions
 * @link        https://www.openstream.ch/
 * @package     WooCommerce Run My Account
 * @since       1.0  
 */
if (!class_exists('WC_RMA_FRONTEND')) {

    class WC_RMA_FRONTEND {

        private $locale = '';

        /**
         *  Construct
         */
        public function __construct() {

            /**
             * add_action() WP Since: 1.2.0
             * https://developer.wordpress.org/reference/functions/add_action/
             */
            add_action('init', array($this, 'init'));
            add_action('plugins_loaded', array($this, 'plugins_loaded'));

	        // if ( class_exists( 'WooCommerce' ) ) {
		        // Fire when a new order comes in ro create new invoice in RMA
		        add_action( 'woocommerce_checkout_order_processed', array( $this, 'rma_send_invoice' ), 10, 1 );
	        //}

        }

        /**
         * Init
         */
        public function init() {

            $this->init_filters(); // Filter
        }

        /**
         * Filters
         */
        public function init_filters() {

            /**
             * apply_filters() WP Since: 0.71
             * https://developer.wordpress.org/reference/functions/apply_filters/
             */
            $this->locale = apply_filters('plugin_locale', get_locale(), 'wc-rma'); // Locale holen und festhalten
        }

        /**
         * Plugins Loaded
         */
        public function plugins_loaded() {

            $this->load_textdomain();

            // Display the admin notification
	        add_action( 'admin_notices', array( $this, 'admin_notices' ) ) ;
        }

        /**
         * Load Textdomains
         * Textdomain für plugin und WordPress.org laden
         */
        public function load_textdomain() {

            /**
             * load_textdomain() WP Since: 1.5.0
             * https://codex.wordpress.org/Function_Reference/load_textdomain
             */
            load_textdomain('wc-rma', WP_LANG_DIR . "/plugins/woocommerce-rma/wc-rma-$this->locale.mo");

            /**
             * load_plugin_textdomain() WP Since: 1.5.0
             * https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
             */
            load_plugin_textdomain('wc-rma', false, plugin_basename(WC_RMA_PFAD . 'languages/'));
        }

        public function admin_notices() {

	        $rmasettings = get_option('wc_rma_settings');
	        $rmaclient = ( isset ($rmasettings['rma-client']) ? $rmasettings['rma-client'] : '');
	        $rmaapikey = ( isset ($rmasettings['rma-apikey']) ? $rmasettings['rma-apikey'] : '');

	        if( (!$rmaclient || !$rmaapikey ) ) {

		        $html = '<div class="notice notice-error">';
		        $html .= '<p>';
		        $html .= '<b>'.__( 'Warning', 'wc-rma' ).'&nbsp;</b>';
		        $html .= __( 'Please add your Mandant and API Key before you start using WooCommerce Run My Account.', 'wc-rma' );
		        $html .= '</p>';
		        $html .= '</div>';

		        echo $html;

	        }

	        if( (isset($rmasettings['rma-active']) && $rmasettings['rma-active']=='') || !isset($rmasettings['rma-active'] ))  {

		        $html = '<div class="update-nag">';
		        $html .= __( 'WooCommerce Run My Account is not activated. No invoice will be created.', 'wc-rma' );
		        $html .= '</div>';

		        echo $html;

	        }

        }

	    public function rma_send_invoice($order_id) {

		    if (class_exists('WC_RMA_API')) $SEND_INVOICE = new WC_RMA_API();
        	$result = $SEND_INVOICE->post_invoice($order_id);

		    return $result;

	    }

    }

}
