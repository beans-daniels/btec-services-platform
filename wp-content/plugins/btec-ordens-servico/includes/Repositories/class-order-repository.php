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
}