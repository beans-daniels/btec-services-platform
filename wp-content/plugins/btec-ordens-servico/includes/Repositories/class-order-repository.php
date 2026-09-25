<?php

if (!defined('ABSPATH')) exit;

class BTEC_Order_Repository
{
    private $table;

    public function __construct()
    {
        global $wpdb;

        $this->table = $wpdb->prefix.'btec_orders';
    }

    public function create($data)
    {
        global $wpdb;

        $number = BTEC_Sequence_Manager::next(
            $data['company_id'],
            'orders'
        );

        if (!$number) {
            return new WP_Error(
                'sequence_error',
                'Não foi possível gerar o número da OS.'
            );
        }

        $result = $wpdb->insert(
            $this->table,
            [
                'company_id'      => $data['company_id'],
                'appointment_id' => $data['appointment_id'] ?? null,
                'client_id'       => $data['client_id'],
                'number'          => $number,
                'equipment_type'  => $data['equipment_type'],
                'brand'           => $data['brand'],
                'model'           => $data['model'],
                'serial_number'   => $data['serial_number'],
                'reported_defect' => $data['reported_defect'],
                'status'          => 'open',
                'created_by'      => get_current_user_id(),
                'created_at'      => current_time('mysql'),
                'updated_at'      => current_time('mysql'),
            ]
        );

        if ($result === false) {
            return new WP_Error(
                'db_error',
                $wpdb->last_error
            );
        }

        return $wpdb->insert_id;
    }

	public function get_all($company_id)
	{
		global $wpdb;

		$clients = $wpdb->prefix . 'btec_clients';

		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT
					o.*,
					c.name  AS client_name,
					c.code  AS client_code
				FROM {$this->table} o
				INNER JOIN {$clients} c
					ON c.id = o.client_id
				WHERE o.company_id = %d
				ORDER BY o.created_at DESC",
				$company_id
			)
		);
	}

	public function find($id)
    {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT
                    o.*,
                    c.code AS client_code,
                    c.name AS client_name,
                    c.phone,
                    c.email,
                    a.number AS appointment_number
                FROM {$this->table} o

                INNER JOIN {$wpdb->prefix}btec_clients c
                    ON c.id = o.client_id

                LEFT JOIN {$wpdb->prefix}btec_appointments a
                    ON a.id = o.appointment_id

                WHERE o.id = %d",
                $id
            )
        );
    }

    public function update($id, $data)
    {
        global $wpdb;

        return $wpdb->update(
            $this->table,
            [
                'equipment_type'  => $data['equipment_type'],
                'brand'           => $data['brand'],
                'model'           => $data['model'],
                'serial_number'   => $data['serial_number'],
                'reported_defect' => $data['reported_defect'],
                'diagnosis'       => $data['diagnosis'],
                'solution'        => $data['solution'],
                'status'          => $data['status'],
                'labor_value'     => $data['labor_value'],
                'parts_value'     => $data['parts_value'],
                'total_value'     => $data['total_value'],
                'updated_at'      => current_time('mysql')
            ],
            ['id' => $id]
        );
    }

}