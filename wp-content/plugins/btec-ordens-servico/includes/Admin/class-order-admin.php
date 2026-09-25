<?php

if (!defined('ABSPATH')) exit;

class BTEC_Order_Admin
{
    public function init()
    {
        add_action('admin_init', [$this, 'handle_create']);
    }

    public function handle_create()
    {
        if (!isset($_POST['btec_order_action'])) {
            return;
        }

        check_admin_referer(
            'btec_create_order',
            'btec_nonce'
        );

        $controller = new BTEC_Order_Controller();

        $controller->create([
            'client_id'       => $_POST['client_id'],
            'equipment_type'  => $_POST['equipment_type'],
            'brand'           => $_POST['brand'],
            'model'           => $_POST['model'],
            'serial_number'   => $_POST['serial_number'],
            'reported_defect' => $_POST['reported_defect'],
        ]);

        wp_safe_redirect(
            admin_url(
                'admin.php?page=btec-ordens-servico&message=created'
            )
        );

        exit;
    }

    public function render()
    {
        $action = $_GET['action'] ?? 'list';

        if ($action === 'new') {
            $this->render_form();
            return;
        }

        $this->render_list();
    }

    private function render_list()
    {
        $repository = new BTEC_Order_Repository();

        $orders = $repository->get_all(1);

        require BTEC_OS_PATH.'templates/orders-list.php';
    }

    private function render_form()
    {
        global $wpdb;

        $clients = $wpdb->get_results(
            "SELECT id, code, name
             FROM {$wpdb->prefix}btec_clients
             ORDER BY name"
        );

        $equipment = BTEC_Order::get_equipment_types();

        require BTEC_OS_PATH.'templates/order-form.php';
    }
}