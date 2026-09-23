<?php

if (!defined('ABSPATH')) exit;

class BTEC_Activator
{
    public static function activate()
    {
        require_once BTEC_CORE_PATH . 'includes/Database/class-database.php';
        require_once BTEC_CORE_PATH . 'includes/Core/class-capabilities.php';

        $database = new BTEC_Database();
        $database->install();

        BTEC_Capabilities::register();

        update_option('btec_core_version', BTEC_CORE_VERSION);
    }
}