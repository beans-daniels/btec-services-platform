<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Appointment
{
    public static function get_services()
    {
        return [
            'desktop'       => 'Desktop',
            'notebook'      => 'Notebook',
            'macos'         => 'macOS',
            'infrastructure'=> 'Infraestrutura',
            'printer'       => 'Impressoras',
            'other'         => 'Outros',
        ];
    }

    public static function get_statuses()
    {
        return [
            'scheduled'   => 'Agendado',
            'confirmed'   => 'Confirmado',
            'in_progress' => 'Em atendimento',
            'waiting'     => 'Aguardando',
            'completed'   => 'Finalizado',
            'cancelled'   => 'Cancelado',
            'converted'   => 'Convertido',
        ];
    }

    public static function get_pending_reasons()
    {
        return [
            'budget'   => 'Aprovação de orçamento',
            'parts'    => 'Aguardando peças',
            'customer' => 'Retorno do cliente',
            'delivery' => 'Equipamento não entregue',
            'temporary'=> 'Equipamento retirado',
            'other'    => 'Outro',
        ];
    }

    public static function get_infrastructure_types()
    {
        return [
            'residence' => 'Residência',
            'company'   => 'Empresa',
            'router'    => 'Modem / Roteador / Switch',
            'server'    => 'Servidor / Computador',
            'wireless'  => 'Rede Wireless',
            'cabling'   => 'Cabeamento',
            'printer'   => 'Impressoras',
            'other'     => 'Outros',
        ];
    }
    /**
     * Retorna o nome amigável do serviço.
     */
    public static function get_service_label($service)
    {
        $services = self::get_services();
    
        return $services[$service] ?? $service;
    }
    
    /**
     * Retorna o nome amigável do status.
     */
    public static function get_status_label($status)
    {
        $statuses = self::get_statuses();
    
        return $statuses[$status] ?? $status;
    }
}