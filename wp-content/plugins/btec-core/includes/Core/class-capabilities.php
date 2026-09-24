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

        $caps = [

            // Clientes
            'view_btec_clients',
            'create_btec_clients',
            'edit_btec_clients',
            'delete_btec_clients',

            // Agendamentos
            'view_btec_appointments',
            'create_btec_appointments',
            'edit_btec_appointments',
            'delete_btec_appointments',

            // Ordens de serviço
            'view_btec_orders',
            'create_btec_orders',
            'edit_btec_orders',
            'delete_btec_orders',

        ];

        foreach ($caps as $cap) {
            $role->add_cap($cap);
        }
    }
}