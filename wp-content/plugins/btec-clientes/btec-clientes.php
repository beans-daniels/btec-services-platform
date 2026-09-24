<?php
/**
 * Plugin Name: BTEC Clientes
 * Plugin URI: https://btecservices.net
 * Description: Módulo de gerenciamento de clientes da plataforma B-TEC Services.
 * Version: 1.0.0
 * Author: B-TEC
 * License: GPL2
 * Text Domain: btec-clientes
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Versão do módulo
 */
define('BTEC_CLIENTES_VERSION', '1.0.0');

/**
 * Caminho físico do plugin
 */
define('BTEC_CLIENTES_PATH', plugin_dir_path(__FILE__));

/**
 * URL do plugin
 */
define('BTEC_CLIENTES_URL', plugin_dir_url(__FILE__));

/**
 * Arquivo principal do plugin
 */
define('BTEC_CLIENTES_FILE', __FILE__);


/**
 * Verifica se o BTEC Core está disponível.
 *
 * O módulo Clientes depende da infraestrutura
 * fornecida pelo BTEC Core.
 */
function btec_clientes_check_dependencies()
{
    if (!defined('BTEC_CORE_VERSION')) {

        add_action('admin_notices', function () {

            if (!current_user_can('activate_plugins')) {
                return;
            }

            echo '<div class="notice notice-error">';
            echo '<p>';
            echo '<strong>BTEC Clientes:</strong> ';
            echo 'O plugin BTEC Core precisa estar instalado e ativo.';
            echo '</p>';
            echo '</div>';

        });

        return false;
    }

    return true;
}


/**
 * Carrega os arquivos do módulo.
 */
function btec_clientes_load_files()
{
    if (!btec_clientes_check_dependencies()) {
        return;
    }

    /*
     * Admin
     */
    require_once BTEC_CLIENTES_PATH .
        'includes/Admin/class-client-admin.php';

    require_once BTEC_CLIENTES_PATH .
        'includes/Admin/class-client-menu.php';


    /*
     * Controllers
     */
    require_once BTEC_CLIENTES_PATH .
        'includes/Controllers/class-client-controller.php';


    /*
     * Models
     */
    require_once BTEC_CLIENTES_PATH .
        'includes/Models/class-client.php';


    /*
     * Repositories
     */
    require_once BTEC_CLIENTES_PATH .
        'includes/Repositories/class-client-repository.php';
}


/**
 * Inicializa o módulo.
 */
function btec_clientes_init()
{
    if (!btec_clientes_check_dependencies()) {
        return;
    }

    btec_clientes_load_files();

    /*
     * Inicialização administrativa.
     */
	if (is_admin()) {

		$menu = new BTEC_Client_Menu();
		$menu->init();

		$admin = new BTEC_Client_Admin();
		$admin->init();

	}
}


/**
 * Inicialização do plugin.
 */
add_action(
    'plugins_loaded',
    'btec_clientes_init',
    20
);