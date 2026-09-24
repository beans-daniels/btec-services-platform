<?php

if (!defined('ABSPATH')) exit;

class BTEC_Order_Menu
{
    public function init()
    {
        add_action('admin_menu', [$this, 'register_menu'], 20);
    }

    public function register_menu()
    {
        add_submenu_page(
            'btec-platform',
            'Ordens de Serviço',
            'Ordens de Serviço',
            'read',
            'btec-ordens-servico',
            [$this, 'render']
        );
    }

    public function render()
    {
        $admin = new BTEC_Order_Admin();
        $admin->render();
    }
}