<?php
/**
 * Plugin Name: Arta Consult RX
 * Description: A plugin to Reserve and manage product orders through medical consultation, with appointment scheduling
 * Version: 1.0.0
 * Author: Amir Safari
 * Author URI: https://amirsafaridev.github.io/
 * Text Domain: arta-consult-rx
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.5
 * Tested up to: 6.8
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ARTA_CONSULT_RX_VERSION', '1.0.1');
define('ARTA_CONSULT_RX_PLUGIN_FILE', __FILE__);
define('ARTA_CONSULT_RX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ARTA_CONSULT_RX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ARTA_CONSULT_RX_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include the main plugin class
require_once ARTA_CONSULT_RX_PLUGIN_DIR . 'includes/class/arta-consult-rx.php';

require_once ARTA_CONSULT_RX_PLUGIN_DIR . 'includes/function.php';

