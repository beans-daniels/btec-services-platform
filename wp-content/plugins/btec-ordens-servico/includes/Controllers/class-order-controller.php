<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order_Controller
{
    private $repository;

    public function __construct()
    {
        $this->repository = new BTEC_Order_Repository();
    }

    public function create($data)
    {
        $payload = [
            'company_id'      => 1,
            'client_id'       => absint($data['client_id']),
            'equipment_type'  => sanitize_text_field($data['equipment_type']),
            'brand'           => sanitize_text_field($data['brand']),
            'model'           => sanitize_text_field($data['model']),
            'serial_number'   => sanitize_text_field($data['serial_number']),
            'reported_defect' => sanitize_textarea_field($data['reported_defect']),
            'status'          => 'open',
            'created_by'      => get_current_user_id(),
        ];

        return $this->repository->create($payload);
    }
    
    public function create_from_appointment($appointment_id)
    {
        global $wpdb;
    
        $appointments = $wpdb->prefix . 'btec_appointments';
    
        $appointment = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$appointments} WHERE id=%d",
                $appointment_id
            )
        );
    
        if (!$appointment) {
            return new WP_Error(
                'appointment_not_found',
                'Agendamento não encontrado.'
            );
        }
    
        $payload = [
            'company_id'      => $appointment->company_id,
            'appointment_id'  => $appointment->id,
            'client_id'       => $appointment->client_id,
            'equipment_type'  => $appointment->service_type,
            'brand'           => '',
            'model'           => '',
            'serial_number'   => '',
            'reported_defect' => $appointment->notes,
            'status'          => 'open',
            'created_by'      => get_current_user_id(),
        ];
    
        return $this->repository->create($payload);
    }
}