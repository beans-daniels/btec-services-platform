<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Administração do módulo Clientes.
 */
class BTEC_Client_Admin
{
    /**
     * Controller de clientes.
     *
     * @var BTEC_Client_Controller
     */
    private $controller;

    /**
     * Inicializa o módulo administrativo.
     *
     * @return void
     */
    public function init()
    {
        $this->controller = new BTEC_Client_Controller();

        add_action(
            'admin_init',
            [$this, 'handle_actions']
        );
    }

    /**
     * Processa ações administrativas.
     *
     * @return void
     */
    public function handle_actions()
    {
        if (!is_admin()) {
            return;
        }

        if (!isset($_REQUEST['page'])) {
            return;
        }

        if ($_REQUEST['page'] !== 'btec-clientes') {
            return;
        }

        /*
         * Cadastro.
         */
        if (
            isset($_POST['btec_client_action']) &&
            $_POST['btec_client_action'] === 'create'
        ) {
            $this->handle_create();
        }

        /*
         * Atualização.
         */
        if (
            isset($_POST['btec_client_action']) &&
            $_POST['btec_client_action'] === 'update'
        ) {
            $this->handle_update();
        }

        /*
         * Exclusão.
         */
        if (
            isset($_GET['btec_client_action']) &&
            $_GET['btec_client_action'] === 'delete'
        ) {
            $this->handle_delete();
        }
    }

    /**
     * Processa criação.
     *
     * @return void
     */
    private function handle_create()
    {
        if (!current_user_can('create_btec_clients')) {
            wp_die(
                esc_html__(
                    'Você não possui permissão para cadastrar clientes.',
                    'btec-clientes'
                )
            );
        }

        check_admin_referer(
            'btec_client_create',
            'btec_client_nonce'
        );

        $data = $this->get_form_data();

        /*
         * Por enquanto utilizamos a empresa padrão
         * criada pelo BTEC Core.
         *
         * Posteriormente isso será obtido através
         * da empresa ativa na sessão/contexto.
         */
        $data['company_id'] = $this->get_default_company_id();

        $result = $this->controller->create($data);

        if (is_wp_error($result)) {
            $this->redirect_with_error(
                $result->get_error_message()
            );
        }

        $this->redirect_with_message(
            'Cliente cadastrado com sucesso.'
        );
    }

    /**
     * Processa atualização.
     *
     * @return void
     */
    private function handle_update()
    {
        if (!current_user_can('create_btec_clients')) {
            wp_die(
                esc_html__(
                    'Você não possui permissão para editar clientes.',
                    'btec-clientes'
                )
            );
        }

        check_admin_referer(
            'btec_client_update',
            'btec_client_nonce'
        );

        $id = isset($_POST['client_id'])
            ? absint($_POST['client_id'])
            : 0;

        if (!$id) {
            $this->redirect_with_error(
                'Cliente inválido.'
            );
        }

        $data = $this->get_form_data();

        $result = $this->controller->update(
            $id,
            $data
        );

        if (is_wp_error($result)) {
            $this->redirect_with_error(
                $result->get_error_message()
            );
        }

        $this->redirect_with_message(
            'Cliente atualizado com sucesso.'
        );
    }

    /**
     * Processa exclusão.
     *
     * @return void
     */
    private function handle_delete()
    {
        if (!current_user_can('delete_btec_clients')) {
            wp_die(
                esc_html__(
                    'Você não possui permissão para excluir clientes.',
                    'btec-clientes'
                )
            );
        }

        $id = isset($_GET['client_id'])
            ? absint($_GET['client_id'])
            : 0;

        if (!$id) {
            $this->redirect_with_error(
                'Cliente inválido.'
            );
        }

        check_admin_referer(
            'btec_client_delete_' . $id,
            'btec_client_nonce'
        );

        $result = $this->controller->delete($id);

        if (is_wp_error($result)) {
            $this->redirect_with_error(
                $result->get_error_message()
            );
        }

        $this->redirect_with_message(
            'Cliente excluído com sucesso.'
        );
    }

    /**
     * Obtém os dados do formulário.
     *
     * @return array
     */
    private function get_form_data()
    {
        return [
            'type' => isset($_POST['type'])
                ? wp_unslash($_POST['type'])
                : '',

            'name' => isset($_POST['name'])
                ? wp_unslash($_POST['name'])
                : '',

            'cpf_cnpj' => isset($_POST['cpf_cnpj'])
                ? wp_unslash($_POST['cpf_cnpj'])
                : '',

            'phone' => isset($_POST['phone'])
                ? wp_unslash($_POST['phone'])
                : '',

            'email' => isset($_POST['email'])
                ? wp_unslash($_POST['email'])
                : '',

            'whatsapp' => isset($_POST['whatsapp'])
                ? wp_unslash($_POST['whatsapp'])
                : '',

            'cep' => isset($_POST['cep'])
                ? wp_unslash($_POST['cep'])
                : '',

            'address' => isset($_POST['address'])
                ? wp_unslash($_POST['address'])
                : '',

            'number' => isset($_POST['number'])
                ? wp_unslash($_POST['number'])
                : '',

            'complement' => isset($_POST['complement'])
                ? wp_unslash($_POST['complement'])
                : '',

            'neighborhood' => isset($_POST['neighborhood'])
                ? wp_unslash($_POST['neighborhood'])
                : '',

            'city' => isset($_POST['city'])
                ? wp_unslash($_POST['city'])
                : '',

            'state' => isset($_POST['state'])
                ? wp_unslash($_POST['state'])
                : '',

            'origin' => isset($_POST['origin'])
                ? wp_unslash($_POST['origin'])
                : '',
        ];
    }

    /**
     * Obtém a empresa padrão.
     *
     * @return int
     */
    private function get_default_company_id()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'btec_companies';

        $company_id = $wpdb->get_var(
            "
            SELECT id
            FROM {$table}
            WHERE active = 1
            ORDER BY id ASC
            LIMIT 1
            "
        );

        return absint($company_id);
    }

    /**
     * Renderiza a página principal.
     *
     * @return void
     */
    public function render()
    {
        if (!current_user_can('view_btec_clients')) {
            wp_die(
                esc_html__(
                    'Você não possui permissão para acessar esta área.',
                    'btec-clientes'
                )
            );
        }

        $action = isset($_GET['action'])
            ? sanitize_key($_GET['action'])
            : 'list';

        if ($action === 'add') {
            $this->render_form();
            return;
        }

        if ($action === 'edit') {
            $this->render_form(
                isset($_GET['client_id'])
                    ? absint($_GET['client_id'])
                    : 0
            );
            return;
        }

        $this->render_list();
    }

    /**
     * Renderiza lista de clientes.
     *
     * @return void
     */
    private function render_list()
    {
        $company_id = $this->get_default_company_id();

        $search = isset($_GET['s'])
            ? sanitize_text_field(
                wp_unslash($_GET['s'])
            )
            : '';

        $page = isset($_GET['paged'])
            ? max(
                1,
                absint($_GET['paged'])
            )
            : 1;

        $per_page = 20;

        $clients = $this->controller->get_all(
            $company_id,
            $search,
            $page,
            $per_page
        );

        $total = $this->controller->count(
            $company_id,
            $search
        );

        $total_pages = max(
            1,
            (int) ceil($total / $per_page)
        );

        require BTEC_CLIENTES_PATH .
            'templates/clients-list.php';
    }

    /**
     * Renderiza formulário.
     *
     * @param int $id ID do cliente.
     * @return void
     */
    private function render_form($id = 0)
    {
        $client = null;

        if ($id) {
            $client = $this->controller->find($id);

            if (!$client) {
                wp_die(
                    esc_html__(
                        'Cliente não encontrado.',
                        'btec-clientes'
                    )
                );
            }
        }

        $types = BTEC_Client::get_types();
        $origins = BTEC_Client::get_origins();

        require BTEC_CLIENTES_PATH .
            'templates/client-form.php';
    }

    /**
     * Redireciona com mensagem de sucesso.
     *
     * @param string $message Mensagem.
     * @return void
     */
    private function redirect_with_message($message)
    {
        $url = admin_url(
            'admin.php?page=btec-clientes'
        );

        $url = add_query_arg(
            [
                'btec_message' => rawurlencode($message),
            ],
            $url
        );

        wp_safe_redirect($url);
        exit;
    }

    /**
     * Redireciona com mensagem de erro.
     *
     * @param string $message Mensagem.
     * @return void
     */
    private function redirect_with_error($message)
    {
        $url = admin_url(
            'admin.php?page=btec-clientes'
        );

        $url = add_query_arg(
            [
                'btec_error' => rawurlencode($message),
            ],
            $url
        );

        wp_safe_redirect($url);
        exit;
    }
}