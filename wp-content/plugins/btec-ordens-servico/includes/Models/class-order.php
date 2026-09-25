<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order
{
    public static function get_equipment_types()
    {
        return [
            'desktop'      => 'Desktop',
            'notebook'     => 'Notebook',
            'macos'        => 'macOS',
            'printer'      => 'Impressora',
            'server'       => 'Servidor',
            'network'      => 'Equipamento de Rede',
            'other'        => 'Outro',
        ];
    }

    public static function get_equipment_label($type)
    {
        $items = self::get_equipment_types();

        return $items[$type] ?? 'Outro';
    }

    public static function get_statuses()
    {
        return [
            'open'        => 'Aberta',
            'progress'    => 'Em atendimento',
            'waiting'     => 'Aguardando',
            'completed'   => 'Finalizada',
            'delivered'   => 'Entregue',
            'cancelled'   => 'Cancelada',
        ];
    }

    public static function get_status_label($status)
    {
        $items = self::get_statuses();

        return $items[$status] ?? $status;
    }
}