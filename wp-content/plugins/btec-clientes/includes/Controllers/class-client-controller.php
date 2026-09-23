<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Controller de clientes.
 *
 * Responsável pelas regras de negócio e validação
 * antes de acessar o Repository.
 */
class BTEC_Client_Controller
{
    /**
     * Repository de clientes.
     *
     * @var BTEC_Client_Repository
     */
    private $repository;

    /**
     * Construtor.
     */
    public function __construct()
    {
        $this->repository = new BTEC_Client_Repository();
    }

    /**
     * Retorna o Repository.
     *
     * @return BTEC_Client_Repository
     */
    public function get_repository()
    {
        return $this->repository;
    }

    /**
     * Cadastra um novo cliente.
     *
     * @param array $data Dados recebidos.
     * @return int|WP_Error
     */
    public function create($data)
    {
        $data = $this->sanitize_data($data);

        $validation = $this->validate($data);

        if (is_wp_error($validation)) {
            return $validation;
        }

        $duplicate = $this->repository->find_by_document(
            $data['cpf_cnpj'],
            $data['company_id']
        );

        if ($duplicate) {
            return new WP_Error(
                'duplicate_document',
                'Já existe um cliente cadastrado com este CPF/CNPJ.'
            );
        }

        $data['code'] = $this->repository->generate_next_code(
            $data['company_id']
        );

        if (empty($data['code'])) {
            return new WP_Error(
                'code_generation_error',
                'Não foi possível gerar o código do cliente.'
            );
        }

        $now = current_time('mysql');

        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        return $this->repository->insert($data);
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

        $existing = $this->repository->find($id);

        if (!$existing) {
            return new WP_Error(
                'client_not_found',
                'Cliente não encontrado.'
            );
        }

        $data = $this->sanitize_data($data);

        /*
         * O company_id não deve ser alterado pelo formulário.
         */
        $data['company_id'] = $existing->company_id;

        /*
         * O código do cliente também é permanente.
         */
        unset($data['code']);

        $validation = $this->validate($data);

        if (is_wp_error($validation)) {
            return $validation;
        }

        $duplicate = $this->repository->find_by_document(
            $data['cpf_cnpj'],
            $existing->company_id,
            $id
        );

        if ($duplicate) {
            return new WP_Error(
                'duplicate_document',
                'Já existe outro cliente cadastrado com este CPF/CNPJ.'
            );
        }

        $data['updated_at'] = current_time('mysql');

        return $this->repository->update(
            $id,
            $data
        );
    }

    /**
     * Exclui um cliente.
     *
     * A permissão administrativa é verificada aqui
     * e também poderá ser reforçada pela interface.
     *
     * @param int $id ID.
     * @return bool|WP_Error
     */
    public function delete($id)
    {
        if (!current_user_can('manage_options')) {
            return new WP_Error(
                'permission_denied',
                'Você não possui permissão para excluir clientes.'
            );
        }

        $id = absint($id);

        if (!$id) {
            return new WP_Error(
                'invalid_client_id',
                'ID do cliente inválido.'
            );
        }

        $client = $this->repository->find($id);

        if (!$client) {
            return new WP_Error(
                'client_not_found',
                'Cliente não encontrado.'
            );
        }

        return $this->repository->delete($id);
    }

    /**
     * Busca um cliente.
     *
     * @param int $id ID.
     * @return BTEC_Client|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
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
        return $this->repository->get_all(
            $company_id,
            $search,
            $page,
            $per_page
        );
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
        return $this->repository->count(
            $company_id,
            $search
        );
    }

    /**
     * Sanitiza os dados recebidos.
     *
     * @param array $data Dados.
     * @return array
     */
    private function sanitize_data($data)
    {
        $fields = [
            'company_id',
            'type',
            'name',
            'cpf_cnpj',
            'phone',
            'email',
            'whatsapp',
            'cep',
            'address',
            'number',
            'complement',
            'neighborhood',
            'city',
            'state',
            'origin',
        ];

        $clean = [];

        foreach ($fields as $field) {

            if (!isset($data[$field])) {
                $clean[$field] = '';
                continue;
            }

            $value = $data[$field];

            if ($field === 'company_id') {
                $clean[$field] = absint($value);
                continue;
            }

            if ($field === 'email') {
                $clean[$field] = sanitize_email($value);
                continue;
            }

            $clean[$field] = sanitize_text_field($value);
        }

        /*
         * CPF/CNPJ será armazenado somente com números.
         */
        $clean['cpf_cnpj'] = preg_replace(
            '/\D/',
            '',
            $clean['cpf_cnpj']
        );

        /*
         * Normaliza tipo.
         */
        $clean['type'] = strtolower(
            $clean['type']
        );

        /*
         * Normaliza origem.
         */
        $clean['origin'] = strtolower(
            $clean['origin']
        );

        return $clean;
    }

    /**
     * Valida os dados do cliente.
     *
     * @param array $data Dados.
     * @return true|WP_Error
     */
    private function validate($data)
    {
        if (empty($data['company_id'])) {
            return new WP_Error(
                'invalid_company',
                'Empresa não informada.'
            );
        }

        if (empty($data['type'])) {
            return new WP_Error(
                'missing_type',
                'Informe o tipo de cliente.'
            );
        }

        $types = BTEC_Client::get_types();

        if (!isset($types[$data['type']])) {
            return new WP_Error(
                'invalid_type',
                'Tipo de cliente inválido.'
            );
        }

        if (empty($data['name'])) {
            return new WP_Error(
                'missing_name',
                'Informe o nome ou razão social.'
            );
        }

        if (empty($data['cpf_cnpj'])) {
            return new WP_Error(
                'missing_document',
                'Informe o CPF ou CNPJ.'
            );
        }

        if ($data['type'] === 'pf') {

            if (!$this->validate_cpf($data['cpf_cnpj'])) {
                return new WP_Error(
                    'invalid_cpf',
                    'CPF inválido.'
                );
            }
        }

        if ($data['type'] === 'pj') {

            if (!$this->validate_cnpj($data['cpf_cnpj'])) {
                return new WP_Error(
                    'invalid_cnpj',
                    'CNPJ inválido.'
                );
            }
        }

        if (!empty($data['email']) &&
            !is_email($data['email'])
        ) {
            return new WP_Error(
                'invalid_email',
                'E-mail inválido.'
            );
        }

        if (empty($data['origin'])) {
            return new WP_Error(
                'missing_origin',
                'Informe a origem do cliente.'
            );
        }

        $origins = BTEC_Client::get_origins();

        if (!isset($origins[$data['origin']])) {
            return new WP_Error(
                'invalid_origin',
                'Origem do cliente inválida.'
            );
        }

        return true;
    }

    /**
     * Valida CPF.
     *
     * @param string $cpf CPF.
     * @return bool
     */
    private function validate_cpf($cpf)
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        /*
         * Rejeita sequências repetidas:
         * 00000000000
         * 11111111111
         * etc.
         */
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        /*
         * Primeiro dígito verificador.
         */
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $cpf[$i] * (10 - $i);
        }

        $remainder = $sum % 11;

        $digit = ($remainder < 2)
            ? 0
            : 11 - $remainder;

        if ((int) $cpf[9] !== $digit) {
            return false;
        }

        /*
         * Segundo dígito verificador.
         */
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $cpf[$i] * (11 - $i);
        }

        $remainder = $sum % 11;

        $digit = ($remainder < 2)
            ? 0
            : 11 - $remainder;

        if ((int) $cpf[10] !== $digit) {
            return false;
        }

        return true;
    }

    /**
     * Valida CNPJ.
     *
     * @param string $cnpj CNPJ.
     * @return bool
     */
    private function validate_cnpj($cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return false;
        }

        /*
         * Rejeita sequências repetidas.
         */
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        /*
         * Primeiro dígito.
         */
        $weights = [
            5, 4, 3, 2,
            9, 8, 7, 6, 5, 4, 3, 2
        ];

        $sum = 0;

        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }

        $remainder = $sum % 11;

        $digit = ($remainder < 2)
            ? 0
            : 11 - $remainder;

        if ((int) $cnpj[12] !== $digit) {
            return false;
        }

        /*
         * Segundo dígito.
         */
        $weights = [
            6, 5, 4, 3, 2,
            9, 8, 7, 6, 5, 4, 3, 2
        ];

        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }

        $remainder = $sum % 11;

        $digit = ($remainder < 2)
            ? 0
            : 11 - $remainder;

        if ((int) $cnpj[13] !== $digit) {
            return false;
        }

        return true;
    }
}