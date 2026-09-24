<?php

if (!defined('ABSPATH')) exit;

class BTEC_Core_Loader
{
    public function run()
    {
        require_once BTEC_CORE_PATH . 'includes/Core/class-sequence-manager.php';
        require_once BTEC_CORE_PATH . 'includes/Core/class-capabilities.php';
        require_once BTEC_CORE_PATH . 'includes/Admin/class-admin-menu.php';

        // Registra as permissões do sistema
        BTEC_Capabilities::register();

        $menu = new BTEC_Admin_Menu();
        $menu->init();
    }
}