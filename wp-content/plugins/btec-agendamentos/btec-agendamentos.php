<?php
/**
 * Plugin Name: BTEC Agendamentos
 * Plugin URI: https://btecservices.net
 * Description: Módulo de agendamento de serviços da plataforma BTEC Services.
 * Version: 1.0.0
 * Author: Daniel da Silva / B-TEC
 * License: GPL2
 * Text Domain: btec-agendamentos
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BTEC_APPOINTMENTS_VERSION', '1.0.0');
define('BTEC_APPOINTMENTS_PATH', plugin_dir_path(__FILE__));
define('BTEC_APPOINTMENTS_URL', plugin_dir_url(__FILE__));
define('BTEC_APPOINTMENTS_FILE', __FILE__);

/*
|--------------------------------------------------------------------------
| Dependência do Core
|--------------------------------------------------------------------------
*/

function btec_appointments_check_dependencies()
{
    if (!defined('BTEC_CORE_VERSION')) {

        add_action('admin_notices', function () {

            if (!current_user_can('activate_plugins')) {
                return;
            }

            echo '<div class="notice notice-error"><p>';
            echo '<strong>BTEC Agendamentos:</strong> ';
            echo 'O plugin BTEC Core precisa estar ativo.';
            echo '</p></div>';

        });

        return false;
    }

    return true;
}

/*
|--------------------------------------------------------------------------
| Carrega arquivos
|--------------------------------------------------------------------------
*/

function btec_appointments_load_files()
{
    if (!btec_appointments_check_dependencies()) {
        return;
    }

    require_once BTEC_APPOINTMENTS_PATH . 'includes/Admin/class-appointment-admin.php';
    require_once BTEC_APPOINTMENTS_PATH . 'includes/Admin/class-appointment-menu.php';

    require_once BTEC_APPOINTMENTS_PATH . 'includes/Controllers/class-appointment-controller.php';

    require_once BTEC_APPOINTMENTS_PATH . 'includes/Models/class-appointment.php';

    require_once BTEC_APPOINTMENTS_PATH . 'includes/Public/class-booking.php';

    require_once BTEC_APPOINTMENTS_PATH . 'includes/Repositories/class-appointment-repository.php';
}

/*
|--------------------------------------------------------------------------
| Inicialização
|--------------------------------------------------------------------------
*/

function btec_appointments_init()
{
    if (!btec_appointments_check_dependencies()) {
        return;
    }

    btec_appointments_load_files();

    if (is_admin()) {

        $menu = new BTEC_Appointment_Menu();
        $menu->init();

        $admin = new BTEC_Appointment_Admin();
        $admin->init();
    }
}

add_action(
    'plugins_loaded',
    'btec_appointments_init',
    30
);