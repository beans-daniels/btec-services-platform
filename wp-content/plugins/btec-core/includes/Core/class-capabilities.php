<?php

if (!defined('ABSPATH')) exit;

class BTEC_Capabilities
{
    public static function register()
    {
        $role = get_role('administrator');

        if (!$role) {
            return;
        }

        $capabilities = self::get_capabilities();

        foreach ($capabilities as $capability) {
            $role->add_cap($capability);
        }
    }

    public static function get_capabilities()
    {
        return [
            'view_btec_clients',
            'create_btec_clients',
            'edit_btec_clients',
            'delete_btec_clients',
        ];
    }
}