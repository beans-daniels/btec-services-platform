<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order_Admin
{
        /**
     * Controller da Ordem de Serviço
     */
    private ?BTEC_Order_Controller $controller = null;

    // restante da classe...
    
    private function controller()
    {
        if ($this->controller === null) {
            $this->controller = new BTEC_Order_Controller();
        }
    
        return $this->controller;
    }

    public function init()
    {
        $this->controller = new BTEC_Order_Controller();

        add_action(
            'admin_init',
            [$this, 'handle_actions']
        );
    }

    /**
     * Processa criação e edição
     */
    public function handle_actions()
    {
        if (!is_admin()) {
            return;
        }

        if (!isset($_REQUEST['page'])) {
            return;
        }

        if ($_REQUEST['page'] !== 'btec-ordens-servico') {
            return;
        }

        if (
            isset($_POST['btec_order_action']) &&
            $_POST['btec_order_action'] === 'create'
        ) {
            $this->handle_create();
        }

        if (
            isset($_POST['btec_order_action']) &&
            $_POST['btec_order_action'] === 'update'
        ) {
            $this->handle_update();
        }
    }

    /**
     * Nova OS
     */
    private function handle_create()
    {
        check_admin_referer(
            'btec_create_order',
            'btec_nonce'
        );

        $result = $this->controller()->create(
            $this->get_form_data()
        );

        if (is_wp_error($result)) {
            wp_die($result->get_error_message());
        }

        wp_safe_redirect(
            admin_url(
                'admin.php?page=btec-ordens-servico&message=created'
            )
        );

        exit;
    }

    /**
     * Atualiza OS
     */
    private function handle_update()
    {
        check_admin_referer(
            'btec_update_order',
            'btec_nonce'
        );

        $id = absint($_POST['order_id']);

        $result = $this->controller()->update(
            $id,
            $this->get_form_data()
        );

        if ($result === false) {
            wp_die('Erro ao atualizar a Ordem de Serviço.');
        }

        wp_safe_redirect(
            admin_url(
                'admin.php?page=btec-ordens-servico&message=updated'
            )
        );

        exit;
    }

    /**
     * Dados do formulário
     */
    private function get_form_data()
    {
        return [

            'client_id' => absint($_POST['client_id']),

            'equipment_type' => sanitize_text_field(
                $_POST['equipment_type']
            ),

            'brand' => sanitize_text_field(
                $_POST['brand']
            ),

            'model' => sanitize_text_field(
                $_POST['model']
            ),

            'serial_number' => sanitize_text_field(
                $_POST['serial_number']
            ),

            'reported_defect' => sanitize_textarea_field(
                $_POST['reported_defect']
            ),

            'diagnosis' => sanitize_textarea_field(
                $_POST['diagnosis'] ?? ''
            ),

            'solution' => sanitize_textarea_field(
                $_POST['solution'] ?? ''
            ),

            'status' => sanitize_text_field(
                $_POST['status'] ?? 'open'
            ),

            'labor_value' => floatval(
                $_POST['labor_value'] ?? 0
            ),

            'parts_value' => floatval(
                $_POST['parts_value'] ?? 0
            ),
        ];
    }

    /**
     * Router da página
     */
    public function render()
    {
        $action = isset($_GET['action'])
            ? sanitize_key($_GET['action'])
            : 'list';

        if ($action === 'new') {
            $this->render_form();
            return;
        }

        if ($action === 'edit') {

            $this->render_form(
                absint($_GET['order_id'])
            );

            return;
        }

        $this->render_list();
    }

    /**
     * Lista
     */
    private function render_list()
    {
        $repository = new BTEC_Order_Repository();

        $orders = $repository->get_all(1);

        require BTEC_OS_PATH .
            'templates/orders-list.php';
    }

    /**
     * Formulário
     */
    private function render_form($id = 0)
    {
        global $wpdb;

        $clients = $wpdb->get_results(
            "SELECT id, code, name
             FROM {$wpdb->prefix}btec_clients
             ORDER BY name"
        );

        $equipment = BTEC_Order::get_equipment_types();
        $statuses = BTEC_Order::get_statuses();

        $order = null;

        if ($id) {

            $order = $this->controller()->find($id);

            if (!$order) {
                wp_die('Ordem de Serviço não encontrada.');
            }

        }

        require BTEC_OS_PATH .
            'templates/order-form.php';
    }
}