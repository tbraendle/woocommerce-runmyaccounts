<?php

// wc-rma.php

/*
  Plugin Name: WooCommerce RMA
  Plugin URI:  https://www.openstream.ch
  Description: This plug-in connect WooCommerce with <a href="https://www.runmyaccounts.ch/">Run My Accounts</a> and creates an invoice automatically after an order is placed.
  Version:     1.0
  Author:      Sandro Lucifora
  Author URI:  https://www.openstream.ch
  Text Domain: wc-rma
  Domain Path: /languages/
  License:     Commercial
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Script absichern gegen indirekten zugriff. 
// ABSPATH ist nicht der Plugin Pfad sondern der pfad zu deiner WP installation. 
if (!defined('ABSPATH')) { exit; }

// Vollen Pfad festlegen. 
if (!defined('WC_RMA_PFAD')) { define('WC_RMA_PFAD', plugin_dir_path(__FILE__)); }

// LOAD BACKEND ////////////////////////////////////////////////////////////////

// Wir Prüfen zuerst ob Admin, da erst der Adminbereich kommt. 
if (is_admin()) {

    // Hier binden wir unsere Backend Klasse ein. 
    require_once WC_RMA_PFAD . 'classes/class.Backend.php';

    // Ist die Klasse vorhanden ? 
    if (class_exists('WC_RMA_BACKEND')) {


        // Backend Klasse instanziieren. 
        $WC_RMA_BACKEND = new WC_RMA_BACKEND();

        // Installation und deinstalltions hooks setzen, wenn die Klasse vorhanden ist die funktion activate und deactivate befinden sich in der Klasse 
        register_activation_hook(__FILE__, array('WC_RMA_BACKEND', 'activate')); // Hier ist wichtig das es ein Array ist um die Klasse und die funktion zu übergeben
        register_deactivation_hook(__FILE__, array('WC_RMA_BACKEND', 'deactivate'));
    }
}

// LOAD FRONTEND ///////////////////////////////////////////////////////////////

// Include frontend class
require_once WC_RMA_PFAD . 'classes/class.Frontend.php';
// Include RMA class
require_once WC_RMA_PFAD . 'classes/class.RmaApi.php';

// Ist die Klasse vorhanden ? 
if (class_exists('WC_RMA_FRONTEND')) {

    // Backend Klasse instanziieren. 
    $WC_RMA_FRONTEND = new WC_RMA_FRONTEND();
}

