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
            return new WP_Error(
                'sequence_error',
                'Não foi possível gerar o número do agendamento.'
            );
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
    
        $result = $wpdb->insert(
            $this->table,
            $insert,
            [
                '%d','%d','%s','%s','%s',
                '%s','%s','%s','%s','%s',
                '%d','%s','%s'
            ]
        );
    
        if ($result === false) {
            return new WP_Error(
                'db_error',
                $wpdb->last_error ?: 'Erro desconhecido ao gravar o agendamento.'
            );
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
    
        $clients = $wpdb->prefix . 'btec_clients';
    
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT
                    a.*,
                    c.name AS client_name,
                    c.code AS client_code
                 FROM {$this->table} a
                 INNER JOIN {$clients} c
                    ON c.id = a.client_id
                 WHERE a.company_id = %d
                    AND a.status <> 'converted'
                 ORDER BY a.scheduled_date ASC,
                          a.scheduled_time ASC",
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
    public function mark_as_converted($id)
    {
        global $wpdb;
    
        return $wpdb->update(
            $this->table,
            [
                'status' => 'converted',
                'updated_at' => current_time('mysql')
            ],
            ['id' => $id]
        );
    }
}