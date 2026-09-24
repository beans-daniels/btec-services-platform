<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Appointment_Admin
{
    private $repository;

    public function init()
	{
		$this->repository = new BTEC_Appointment_Repository();

		add_action(
			'admin_init',
			[$this, 'handle_create']
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

		$controller = new BTEC_Appointment_Controller();

		$result = $controller->create($_POST);

		if ($result) {

			wp_safe_redirect(
				admin_url(
					'admin.php?page=btec-agendamentos'
				)
			);

			exit;
		}
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
        $appointments = [];

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