<?php
/**
 * Plugin Name: BTEC Core
 * Plugin URI: https://btecservices.net
 * Description: Núcleo da plataforma B-TEC Services.
 * Version: 0.5.1
 * Author: Daniel da Silva / B-TEC
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BTEC_CORE_VERSION', '0.6.0');
define('BTEC_CORE_PATH', plugin_dir_path(__FILE__));
define('BTEC_CORE_URL', plugin_dir_url(__FILE__));

require_once BTEC_CORE_PATH . 'includes/Core/class-loader.php';
require_once BTEC_CORE_PATH . 'includes/Core/class-activator.php';

register_activation_hook(__FILE__, ['BTEC_Activator', 'activate']);

function btec_core_init()
{
    $loader = new BTEC_Core_Loader();
    $loader->run();
}

function btec_core_init()
{
    $installed_version = get_option('btec_core_version');

    if ($installed_version !== BTEC_CORE_VERSION) {

        require_once BTEC_CORE_PATH . 'includes/Database/class-database.php';

        $database = new BTEC_Database();
        $database->install();

        update_option('btec_core_version', BTEC_CORE_VERSION);
    }

    $loader = new BTEC_Core_Loader();
    $loader->run();
}

btec_core_init();