<?php

if (!defined('ABSPATH')) exit;

class BTEC_Order
{
    public static function get_equipment_types()
    {
        return [
            'desktop'  => 'Desktop',
            'notebook' => 'Notebook',
            'macos'    => 'macOS',
            'printer'  => 'Impressora',
            'other'    => 'Outro',
        ];
    }

    public static function get_statuses()
    {
        return [
            'open'      => 'Aberta',
            'service'   => 'Em Atendimento',
            'waiting'   => 'Aguardando',
            'completed' => 'Finalizada',
            'cancelled' => 'Cancelada',
        ];
    }

    public static function get_status_label($status)
    {
        $items = self::get_statuses();

        return $items[$status] ?? $status;
    }

    public static function get_equipment_label($type)
    {
        $items = self::get_equipment_types();

        return $items[$type] ?? $type;
    }
}