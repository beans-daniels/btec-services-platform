<?php
/**
 * Plugin Name: BTEC Core
 * Plugin URI: https://btecservices.net
 * Description: Núcleo da plataforma B-TEC Services.
 * Version: 0.5.0
 * Author: Daniel da Silva / B-TEC
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BTEC_CORE_VERSION', '0.5.0');
define('BTEC_CORE_PATH', plugin_dir_path(__FILE__));
define('BTEC_CORE_URL', plugin_dir_url(__FILE__));

require_once BTEC_CORE_PATH . 'includes/class-loader.php';

function btec_core_init()
{
    $loader = new BTEC_Core_Loader();
    $loader->run();
}

btec_core_init();