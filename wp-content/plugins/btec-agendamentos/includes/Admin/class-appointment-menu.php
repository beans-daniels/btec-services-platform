<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Appointment_Menu
{
    public function init()
    {
        add_action('admin_menu', [$this, 'register_menu']);
    }

    public function register_menu()
    {
        add_submenu_page(
            'btec-dashboard',
            'Agendamentos',
            'Agendamentos',
            'view_btec_clients',
            'btec-agendamentos',
            [$this, 'render']
        );
    }

    public function render()
    {
        $admin = new BTEC_Appointment_Admin();
        $admin->render();
    }
}