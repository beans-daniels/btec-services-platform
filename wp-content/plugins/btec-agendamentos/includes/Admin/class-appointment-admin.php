<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Appointment_Admin
{
    public function init()
	{
		add_action(
			'admin_init',
			[$this, 'handle_create']
		);
		add_action(
            'admin_init',
            [$this, 'handle_convert_to_order']
        );
	}

	public function handle_create()
	{
    if (!is_admin()) {
        return;
    }

    if (!isset($_POST['btec_appointment_action'])) {
        return;
    }

    if ($_POST['btec_appointment_action'] !== 'create') {
        return;
    }

    check_admin_referer(
        'btec_create_appointment',
        'btec_nonce'
    );

    $data = [
        'client_id'       => absint($_POST['client_id']),
        'service_type'    => sanitize_text_field($_POST['service_type']),
        'service_target'  => sanitize_text_field($_POST['service_target'] ?? ''),
        'scheduled_date'  => sanitize_text_field($_POST['scheduled_date']),
        'scheduled_time'  => sanitize_text_field($_POST['scheduled_time']),
        'notes'           => sanitize_textarea_field($_POST['notes']),
        'company_id'      => 1,
    ];

    $controller = new BTEC_Appointment_Controller();

    $result = $controller->create($data);

    if (is_wp_error($result)) {
        wp_die($result->get_error_message());
    }

    wp_safe_redirect(
        admin_url(
            'admin.php?page=btec-agendamentos&message=created'
        )
    );
    exit;
	}
	
	public function handle_convert_to_order()
    {
        if (!is_admin()) {
            return;
        }
    
        if (!isset($_GET['convert_os'])) {
            return;
        }
    
        $appointment_id = absint($_GET['convert_os']);
    
        check_admin_referer(
            'convert_os_' . $appointment_id
        );
    
        $controller = new BTEC_Order_Controller();
    
        $order_id = $controller->create_from_appointment(
            $appointment_id
        );
                    
        $repository = new BTEC_Appointment_Repository();
            
        $repository->mark_as_converted($appointment_id);
    
        if (is_wp_error($order_id)) {
            wp_die($order_id->get_error_message());
        }
    
        wp_safe_redirect(
            admin_url(
                'admin.php?page=btec-ordens-servico&message=created'
            )
        );
    
        exit;
    }
	
    public function render()
    {
        $action = isset($_GET['action'])
            ? sanitize_key($_GET['action'])
            : 'list';

        if ($action === 'new') {
            $this->render_form();
            return;
        }

        $this->render_list();
    }

	private function render_list()
	{
		$repository = new BTEC_Appointment_Repository();

		$appointments = $repository->get_all(1);

		require BTEC_APPOINTMENTS_PATH .
			'templates/appointments-list.php';
	}

    private function render_form()
    {
        global $wpdb;

        $clients = $wpdb->get_results(
            "SELECT id, code, name
             FROM {$wpdb->prefix}btec_clients
             ORDER BY name ASC"
        );

        $services = BTEC_Appointment::get_services();
        $infra = BTEC_Appointment::get_infrastructure_types();

        require BTEC_APPOINTMENTS_PATH .
            'templates/appointment-form.php';
    }
}