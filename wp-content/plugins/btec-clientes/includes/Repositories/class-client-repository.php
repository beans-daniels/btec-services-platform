<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Repository de clientes.
 *
 * Responsável exclusivamente pelo acesso
 * e manipulação dos dados de clientes.
 */
class BTEC_Client_Repository
{
    /**
     * Instância do banco WordPress.
     *
     * @var wpdb
     */
    private $wpdb;

    /**
     * Nome da tabela de clientes.
     *
     * @var string
     */
    private $table;

    /**
     * Construtor.
     */
    public function __construct()
    {
        global $wpdb;

        $this->wpdb  = $wpdb;
        $this->table = $wpdb->prefix . 'btec_clients';
    }

    /**
     * Busca um cliente pelo ID.
     *
     * @param int $id ID do cliente.
     * @return BTEC_Client|null
     */
    public function find($id)
    {
        $id = absint($id);

        if (!$id) {
            return null;
        }

        $row = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE id = %d LIMIT 1",
                $id
            ),
            ARRAY_A
        );

        if (!$row) {
            return null;
        }

        return new BTEC_Client($row);
    }

    /**
     * Busca cliente pelo código.
     *
     * @param string $code Código do cliente.
     * @return BTEC_Client|null
     */
    public function find_by_code($code)
    {
        $code = sanitize_text_field($code);

        if (empty($code)) {
            return null;
        }

        $row = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE code = %s LIMIT 1",
                $code
            ),
            ARRAY_A
        );

        if (!$row) {
            return null;
        }

        return new BTEC_Client($row);
    }

    /**
     * Busca cliente pelo CPF/CNPJ.
     *
     * A comparação é feita somente com números.
     *
     * @param string $document Documento.
     * @param int    $company_id Empresa.
     * @param int    $exclude_id ID a ignorar.
     * @return BTEC_Client|null
     */
    public function find_by_document(
        $document,
        $company_id,
        $exclude_id = 0
    ) {
        $document = preg_replace('/\D/', '', $document);
        $company_id = absint($company_id);
        $exclude_id = absint($exclude_id);

        if (empty($document) || !$company_id) {
            return null;
        }

        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE company_id = %d
            AND REPLACE(
                REPLACE(
                    REPLACE(
                        REPLACE(cpf_cnpj, '.', ''),
                    '-', ''),
                '/', ''),
            ' ', '') = %s
        ";

        $params = [
            $company_id,
            $document,
        ];

        if ($exclude_id) {
            $sql .= " AND id != %d";
            $params[] = $exclude_id;
        }

        $sql .= " LIMIT 1";

        $row = $this->wpdb->get_row(
            $this->wpdb->prepare($sql, $params),
            ARRAY_A
        );

        if (!$row) {
            return null;
        }

        return new BTEC_Client($row);
    }

    /**
     * Lista clientes.
     *
     * @param int    $company_id Empresa.
     * @param string $search Pesquisa.
     * @param int    $page Página.
     * @param int    $per_page Registros por página.
     * @return array
     */
    public function get_all(
        $company_id,
        $search = '',
        $page = 1,
        $per_page = 20
    ) {
        $company_id = absint($company_id);
        $page = max(1, absint($page));
        $per_page = max(1, absint($per_page));

        if (!$company_id) {
            return [];
        }

        $offset = ($page - 1) * $per_page;

        $where = "WHERE company_id = %d";
        $params = [$company_id];

        $search = sanitize_text_field($search);

        if (!empty($search)) {

            $like = '%' . $this->wpdb->esc_like($search) . '%';

            $where .= "
                AND (
                    name LIKE %s
                    OR cpf_cnpj LIKE %s
                    OR phone LIKE %s
                    OR whatsapp LIKE %s
                    OR email LIKE %s
                    OR code LIKE %s
                )
            ";

            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql = "
            SELECT *
            FROM {$this->table}
            {$where}
            ORDER BY id DESC
            LIMIT %d OFFSET %d
        ";

        $params[] = $per_page;
        $params[] = $offset;

        $rows = $this->wpdb->get_results(
            $this->wpdb->prepare($sql, $params),
            ARRAY_A
        );

        $clients = [];

        foreach ($rows as $row) {
            $clients[] = new BTEC_Client($row);
        }

        return $clients;
    }

    /**
     * Conta clientes.
     *
     * @param int    $company_id Empresa.
     * @param string $search Pesquisa.
     * @return int
     */
    public function count(
        $company_id,
        $search = ''
    ) {
        $company_id = absint($company_id);

        if (!$company_id) {
            return 0;
        }

        $where = "WHERE company_id = %d";
        $params = [$company_id];

        $search = sanitize_text_field($search);

        if (!empty($search)) {

            $like = '%' . $this->wpdb->esc_like($search) . '%';

            $where .= "
                AND (
                    name LIKE %s
                    OR cpf_cnpj LIKE %s
                    OR phone LIKE %s
                    OR whatsapp LIKE %s
                    OR email LIKE %s
                    OR code LIKE %s
                )
            ";

            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        return (int) $this->wpdb->get_var(
            $this->wpdb->prepare(
                "
                SELECT COUNT(*)
                FROM {$this->table}
                {$where}
                ",
                $params
            )
        );
    }

    /**
     * Gera o próximo código de cliente.
     *
     * Formato:
     * CLI-000001
     *
     * @param int $company_id Empresa.
     * @return string
     */
    public function generate_next_code($company_id)
    {
        $company_id = absint($company_id);

        if (!$company_id) {
            return '';
        }

        $last_code = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "
                SELECT code
                FROM {$this->table}
                WHERE company_id = %d
                AND code LIKE 'CLI-%'
                ORDER BY id DESC
                LIMIT 1
                ",
                $company_id
            )
        );

        if (!$last_code) {
            return 'CLI-000001';
        }

        $number = (int) preg_replace('/\D/', '', $last_code);

        $number++;

        return 'CLI-' . str_pad(
            $number,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Cria um cliente.
     *
     * @param array $data Dados.
     * @return int|WP_Error
     */
    public function insert($data)
    {
        $result = $this->wpdb->insert(
            $this->table,
            $data,
            $this->get_formats($data)
        );

        if ($result === false) {

            return new WP_Error(
                'client_insert_error',
                'Não foi possível cadastrar o cliente.',
                [
                    'db_error' => $this->wpdb->last_error,
                ]
            );
        }

        return (int) $this->wpdb->insert_id;
    }

    /**
     * Atualiza um cliente.
     *
     * @param int   $id ID.
     * @param array $data Dados.
     * @return bool|WP_Error
     */
    public function update($id, $data)
    {
        $id = absint($id);

        if (!$id) {
            return new WP_Error(
                'invalid_client_id',
                'ID do cliente inválido.'
            );
        }

        $result = $this->wpdb->update(
            $this->table,
            $data,
            [
                'id' => $id,
            ],
            $this->get_formats($data),
            [
                '%d',
            ]
        );

        if ($result === false) {

            return new WP_Error(
                'client_update_error',
                'Não foi possível atualizar o cliente.',
                [
                    'db_error' => $this->wpdb->last_error,
                ]
            );
        }

        return true;
    }

    /**
     * Exclui um cliente.
     *
     * A autorização será controlada pelo Controller/Admin.
     *
     * @param int $id ID.
     * @return bool|WP_Error
     */
    public function delete($id)
    {
        $id = absint($id);

        if (!$id) {
            return new WP_Error(
                'invalid_client_id',
                'ID do cliente inválido.'
            );
        }

        $result = $this->wpdb->delete(
            $this->table,
            [
                'id' => $id,
            ],
            [
                '%d',
            ]
        );

        if ($result === false) {

            return new WP_Error(
                'client_delete_error',
                'Não foi possível excluir o cliente.',
                [
                    'db_error' => $this->wpdb->last_error,
                ]
            );
        }

        return true;
    }

    /**
     * Retorna os formatos dos campos.
     *
     * @param array $data Dados.
     * @return array
     */
    private function get_formats($data)
    {
        $formats = [];

        $integer_fields = [
            'id',
            'company_id',
        ];

        $datetime_fields = [
            'created_at',
            'updated_at',
        ];

        foreach ($data as $field => $value) {

            if (in_array($field, $integer_fields, true)) {
                $formats[] = '%d';
                continue;
            }

            if (in_array($field, $datetime_fields, true)) {
                $formats[] = '%s';
                continue;
            }

            $formats[] = '%s';
        }

        return $formats;
    }
}