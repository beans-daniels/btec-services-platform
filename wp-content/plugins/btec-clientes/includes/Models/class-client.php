<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Representa um cliente da plataforma B-TEC.
 */
class BTEC_Client
{
    /**
     * ID do cliente.
     *
     * @var int
     */
    public $id = 0;

    /**
     * Empresa à qual o cliente pertence.
     *
     * @var int
     */
    public $company_id = 0;

    /**
     * Código interno.
     *
     * Exemplo: CLI-000001
     *
     * @var string
     */
    public $code = '';

    /**
     * Tipo de cliente.
     *
     * Valores:
     * pf = Pessoa Física
     * pj = Pessoa Jurídica
     *
     * @var string
     */
    public $type = 'pf';

    /**
     * Nome ou razão social.
     *
     * @var string
     */
    public $name = '';

    /**
     * CPF ou CNPJ.
     *
     * @var string
     */
    public $cpf_cnpj = '';

    /**
     * Telefone principal.
     *
     * @var string
     */
    public $phone = '';

    /**
     * E-mail.
     *
     * @var string
     */
    public $email = '';

    /**
     * WhatsApp.
     *
     * @var string
     */
    public $whatsapp = '';

    /**
     * CEP.
     *
     * @var string
     */
    public $cep = '';

    /**
     * Logradouro.
     *
     * @var string
     */
    public $address = '';

    /**
     * Número.
     *
     * @var string
     */
    public $number = '';

    /**
     * Complemento.
     *
     * @var string
     */
    public $complement = '';

    /**
     * Bairro.
     *
     * @var string
     */
    public $neighborhood = '';

    /**
     * Cidade.
     *
     * @var string
     */
    public $city = '';

    /**
     * Estado.
     *
     * @var string
     */
    public $state = '';

    /**
     * Origem do cliente.
     *
     * Valores:
     * site
     * whatsapp
     * instagram
     * indicacao
     * loja_fisica
     * outros
     *
     * @var string
     */
    public $origin = '';

    /**
     * Data de criação.
     *
     * @var string
     */
    public $created_at = '';

    /**
     * Data da última atualização.
     *
     * @var string
     */
    public $updated_at = '';

    /**
     * Construtor.
     *
     * @param array $data Dados iniciais do cliente.
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Preenche os dados do cliente.
     *
     * @param array $data Dados do cliente.
     * @return void
     */
    public function fill(array $data)
    {
        $fields = [
            'id',
            'company_id',
            'code',
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
            'created_at',
            'updated_at',
        ];

        foreach ($fields as $field) {

            if (array_key_exists($field, $data)) {
                $this->{$field} = $data[$field];
            }

        }
    }

    /**
     * Retorna os tipos de cliente disponíveis.
     *
     * @return array
     */
    public static function get_types()
    {
        return [
            'pf' => 'Pessoa Física',
            'pj' => 'Pessoa Jurídica',
        ];
    }

    /**
     * Retorna as origens disponíveis.
     *
     * @return array
     */
    public static function get_origins()
    {
        return [
            'site' => 'Site',
            'whatsapp' => 'WhatsApp',
            'instagram' => 'Instagram',
            'indicacao' => 'Indicação',
            'loja_fisica' => 'Loja Física',
            'outros' => 'Outros',
        ];
    }

    /**
     * Verifica se o tipo de cliente é válido.
     *
     * @return bool
     */
    public function is_valid_type()
    {
        $types = self::get_types();

        return isset($types[$this->type]);
    }

    /**
     * Verifica se a origem é válida.
     *
     * @return bool
     */
    public function is_valid_origin()
    {
        $origins = self::get_origins();

        return isset($origins[$this->origin]);
    }

    /**
     * Retorna o nome do tipo de cliente.
     *
     * @return string
     */
    public function get_type_label()
    {
        $types = self::get_types();

        return $types[$this->type] ?? '';
    }

    /**
     * Retorna o nome da origem.
     *
     * @return string
     */
    public function get_origin_label()
    {
        $origins = self::get_origins();

        return $origins[$this->origin] ?? '';
    }

    /**
     * Retorna o documento formatado.
     *
     * @return string
     */
    public function get_formatted_document()
    {
        $document = preg_replace('/\D/', '', $this->cpf_cnpj);

        if ($this->type === 'pf' && strlen($document) === 11) {

            return substr($document, 0, 3) . '.' .
                substr($document, 3, 3) . '.' .
                substr($document, 6, 3) . '-' .
                substr($document, 9, 2);

        }

        if ($this->type === 'pj' && strlen($document) === 14) {

            return substr($document, 0, 2) . '.' .
                substr($document, 2, 3) . '.' .
                substr($document, 5, 3) . '/' .
                substr($document, 8, 4) . '-' .
                substr($document, 12, 2);

        }

        return $this->cpf_cnpj;
    }

    /**
     * Retorna o nome do cliente.
     *
     * @return string
     */
    public function get_display_name()
    {
        return $this->name;
    }

    /**
     * Verifica se o cliente possui WhatsApp.
     *
     * @return bool
     */
    public function has_whatsapp()
    {
        return !empty($this->whatsapp);
    }

    /**
     * Retorna o número do WhatsApp somente com números.
     *
     * @return string
     */
    public function get_whatsapp_number()
    {
        return preg_replace('/\D/', '', $this->whatsapp);
    }

    /**
     * Retorna a URL do WhatsApp.
     *
     * @return string
     */
    public function get_whatsapp_url()
    {
        $number = $this->get_whatsapp_number();

        if (empty($number)) {
            return '';
        }

        return 'https://wa.me/' . $number;
    }
}