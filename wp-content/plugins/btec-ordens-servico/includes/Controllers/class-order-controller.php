<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order_Controller
{
    private $repository;

	private $item_repository;

	private $history_repository;

    public function __construct()
	{
		$this->repository = new BTEC_Order_Repository();
		$this->item_repository = new BTEC_Order_Item_Repository();
		$this->history_repository = new BTEC_Order_History_Repository();
	}

    /**
     * Cria uma nova OS
     */
    public function create($data)
    {
        $payload = [
            'company_id'      => 1,
            'appointment_id'  => $data['appointment_id'] ?? null,
            'client_id'       => absint($data['client_id']),
            'equipment_type'  => sanitize_text_field($data['equipment_type']),
            'brand'           => sanitize_text_field($data['brand']),
            'model'           => sanitize_text_field($data['model']),
            'serial_number'   => sanitize_text_field($data['serial_number']),
            'reported_defect' => sanitize_textarea_field($data['reported_defect']),
            'status'          => 'open',
            'created_by'      => get_current_user_id(),
        ];

        $order_id = $this->repository->create($payload);

		if ($order_id) {

			$this->history_repository->create(
				$order_id,
				'created',
				'Ordem de Serviço criada.'
			);

		}

		return $order_id;
    }

    /**
     * Atualiza uma OS existente
     */
    public function update($id, $data)
	{
		global $wpdb;

		$wpdb->query('START TRANSACTION');

		$payload = [
			'equipment_type'  => sanitize_text_field($data['equipment_type']),
			'brand'           => sanitize_text_field($data['brand']),
			'model'           => sanitize_text_field($data['model']),
			'serial_number'   => sanitize_text_field($data['serial_number']),
			'reported_defect' => sanitize_textarea_field($data['reported_defect']),
			'diagnosis'       => sanitize_textarea_field($data['diagnosis']),
			'solution'        => sanitize_textarea_field($data['solution']),
			'status'          => sanitize_text_field($data['status']),
			'labor_value'     => (float) $data['labor_value'],
			'parts_value'     => (float) $data['parts_value'],
			'total_value'     => (float) $data['total_value'],
		];

		$this->repository->update($id, $payload);

		$this->history_repository->create(
			$id,
			'status',
			'Status alterado para: ' .
			BTEC_Order::get_status_label($payload['status'])
		);

		if (!empty($payload['diagnosis'])) {

			$this->history_repository->create(
				$id,
				'diagnosis',
				'Diagnóstico atualizado.'
			);

		}

		if (!empty($payload['solution'])) {

			$this->history_repository->create(
				$id,
				'solution',
				'Solução registrada.'
			);

		}

		$this->item_repository->delete_by_order($id);

		if (!empty($data['items'])) {

			foreach ($data['items'] as $item) {

				if (empty($item['description'])) {
					continue;
				}

				$this->item_repository->create($id, $item);
			}

			$this->history_repository->create(
				$id,
				'item',
				'Peça adicionada: ' .
				$item['description']
			);
		}

		$wpdb->query('COMMIT');

		return true;
	}

    /**
     * Abre uma OS
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Converte Agendamento → OS
     */
    public function create_from_appointment($appointment_id)
    {
        global $wpdb;

        $appointment = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT *
                 FROM {$wpdb->prefix}btec_appointments
                 WHERE id=%d",
                $appointment_id
            )
        );

        if (!$appointment) {
            return new WP_Error(
                'appointment_not_found',
                'Agendamento não encontrado.'
            );
        }

        return $this->create([
            'appointment_id'  => $appointment->id,
            'client_id'       => $appointment->client_id,
            'equipment_type'  => $appointment->service_type,
            'brand'           => '',
            'model'           => '',
            'serial_number'   => '',
            'reported_defect' => $appointment->notes,
        ]);
    }
}