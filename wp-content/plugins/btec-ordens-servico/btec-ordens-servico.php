<?php
/**
 * Plugin Name: BTEC Ordens de Serviço
 * Plugin URI: https://btecservices.net
 * Description: Módulo de Ordens de Serviço da plataforma BTEC Services.
 * Version: 1.0.0
 * Author: Daniel da Silva / B-TEC
 * License: GPL2
 * Text Domain: btec-ordens-servico
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BTEC_OS_VERSION', '1.0.0');
define('BTEC_OS_PATH', plugin_dir_path(__FILE__));
define('BTEC_OS_URL', plugin_dir_url(__FILE__));

function btec_os_check_dependencies()
{
    return defined('BTEC_CORE_VERSION');
}

function btec_os_init()
{
    if (!btec_os_check_dependencies()) {
        return;
    }

    require_once BTEC_OS_PATH . 'includes/Admin/class-order-admin.php';
    require_once BTEC_OS_PATH . 'includes/Admin/class-order-menu.php';
    require_once BTEC_OS_PATH . 'includes/Controllers/class-order-controller.php';
	require_once BTEC_CORE_PATH . 'includes/Core/class-sequence-manager.php';
    require_once BTEC_OS_PATH . 'includes/Repositories/class-order-repository.php';
    require_once BTEC_OS_PATH . 'includes/Models/class-order.php';
    require_once BTEC_OS_PATH . 'includes/Database/class-database.php';

    if (is_admin()) {
        $menu = new BTEC_Order_Menu();
        $menu->init();

        $admin = new BTEC_Order_Admin();
        $admin->init();
    }
}

register_activation_hook(__FILE__, function () {

    if (!btec_os_check_dependencies()) {
        wp_die(
            'O plugin BTEC Core precisa estar ativo antes de ativar Ordens de Serviço.'
        );
    }

    require_once BTEC_OS_PATH . 'includes/Database/class-database.php';

    $db = new BTEC_Order_Database();
    $db->install();

    update_option(
        'btec_os_version',
        BTEC_OS_VERSION
    );
});

add_action('plugins_loaded', 'btec_os_init', 30);