<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Admin_Menu
{
    public function init()
    {
        add_action('admin_menu', [$this, 'register_menu']);
    }

    public function register_menu()
    {
        add_menu_page(
            'B-TEC Platform',
            'B-TEC Platform',
            'manage_options',
            'btec-platform',
            [$this, 'dashboard'],
            'dashicons-laptop',
            26
        );
    }

    public function dashboard()
    {
        include BTEC_CORE_PATH . 'templates/dashboard.php';
    }
}