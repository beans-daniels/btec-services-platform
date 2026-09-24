<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Appointment_Repository
{
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'btec_appointments';
    }

    /**
     * Cria um novo agendamento.
     */
    public function create($data)
    {
        global $wpdb;

        $number = BTEC_Sequence_Manager::next(
            $data['company_id'],
            'appointments'
        );

        if (!$number) {
            return false;
        }

        $insert = [
            'company_id'      => $data['company_id'],
            'client_id'       => $data['client_id'],
            'number'          => $number,
            'service_type'    => $data['service_type'],
            'service_target'  => $data['service_target'],
            'scheduled_date'  => $data['scheduled_date'],
            'scheduled_time'  => $data['scheduled_time'],
            'status'          => 'scheduled',
            'pending_reason'  => null,
            'notes'           => $data['notes'],
            'created_by'      => get_current_user_id(),
            'created_at'      => current_time('mysql'),
            'updated_at'      => current_time('mysql')
        ];

        $result = $wpdb->insert($this->table, $insert);

        if (!$result) {
            return false;
        }

        return $wpdb->insert_id;
    }

    /**
     * Busca por ID.
     */
    public function find($id)
    {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE id = %d",
                $id
            )
        );
    }

    /**
     * Lista agendamentos.
     */
    public function get_all($company_id)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                 FROM {$this->table}
                 WHERE company_id = %d
                 ORDER BY scheduled_date ASC,
                          scheduled_time ASC",
                $company_id
            )
        );
    }

    /**
     * Atualiza status.
     */
    public function update_status(
        $id,
        $status,
        $reason = null
    ) {
        global $wpdb;

        return $wpdb->update(
            $this->table,
            [
                'status'         => $status,
                'pending_reason' => $reason,
                'updated_at'     => current_time('mysql')
            ],
            ['id' => $id]
        );
    }

    /**
     * Exclui.
     */
    public function delete($id)
    {
        global $wpdb;

        return $wpdb->delete(
            $this->table,
            ['id' => $id]
        );
    }
}