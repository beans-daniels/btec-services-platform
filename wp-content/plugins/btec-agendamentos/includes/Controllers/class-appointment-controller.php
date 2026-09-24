<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Appointment_Controller
{
    private $repository;

    public function __construct()
    {
        $this->repository = new BTEC_Appointment_Repository();
    }

    public function create($data)
    {
        $company_id = 1;

        $payload = [
            'company_id'     => $company_id,
            'client_id'      => absint($data['client_id']),
            'service_type'   => sanitize_text_field($data['service_type']),
            'service_target' => sanitize_text_field($data['service_target']),
            'scheduled_date' => sanitize_text_field($data['scheduled_date']),
            'scheduled_time' => sanitize_text_field($data['scheduled_time']),
            'notes'          => sanitize_textarea_field($data['notes']),
        ];

        return $this->repository->create($payload);
    }
}