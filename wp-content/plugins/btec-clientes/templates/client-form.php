<?php

if (!defined('ABSPATH')) {
    exit;
}

$is_edit = !empty($client) && !empty($client->id);

$title = $is_edit
    ? 'Editar Cliente'
    : 'Novo Cliente';

$action = $is_edit
    ? 'update'
    : 'create';

$nonce_action = $is_edit
    ? 'btec_client_update'
    : 'btec_client_create';

$client_type = $is_edit
    ? $client->type
    : 'pf';

$client_origin = $is_edit
    ? $client->origin
    : '';

?>

<div class="wrap">

    <h1 class="wp-heading-inline">
        <?php echo esc_html($title); ?>
    </h1>

    <a
        href="<?php echo esc_url(
            admin_url('admin.php?page=btec-clientes')
        ); ?>"
        class="page-title-action"
    >
        Voltar para Clientes
    </a>

    <hr class="wp-header-end">

    <form
        method="post"
        action="<?php echo esc_url(
            admin_url('admin.php?page=btec-clientes')
        ); ?>"
    >

        <input
            type="hidden"
            name="btec_client_action"
            value="<?php echo esc_attr($action); ?>"
        >

        <?php if ($is_edit) : ?>

            <input
                type="hidden"
                name="client_id"
                value="<?php echo esc_attr($client->id); ?>"
            >

        <?php endif; ?>

        <?php wp_nonce_field(
            $nonce_action,
            'btec_client_nonce'
        ); ?>


        <table class="form-table" role="presentation">

            <tbody>

                <tr>
                    <th scope="row">
                        <label for="btec-client-type">
                            Tipo de Cliente
                        </label>
                    </th>

                    <td>

                        <select
                            name="type"
                            id="btec-client-type"
                            required
                        >

                            <?php foreach ($types as $key => $label) : ?>

                                <option
                                    value="<?php echo esc_attr($key); ?>"
                                    <?php selected(
                                        $client_type,
                                        $key
                                    ); ?>
                                >
                                    <?php echo esc_html($label); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-name">
                            Nome / Razão Social
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="name"
                            id="btec-client-name"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->name
                                    : ''
                            ); ?>"
                            required
                            maxlength="150"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-document">
                            <span id="btec-document-label">
                                CPF
                            </span>
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="cpf_cnpj"
                            id="btec-client-document"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->cpf_cnpj
                                    : ''
                            ); ?>"
                            required
                            autocomplete="off"
                        >

                        <p class="description">
                            Informe um CPF ou CNPJ válido.
                        </p>

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-phone">
                            Telefone
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="phone"
                            id="btec-client-phone"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->phone
                                    : ''
                            ); ?>"
                            maxlength="30"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-whatsapp">
                            WhatsApp
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="whatsapp"
                            id="btec-client-whatsapp"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->whatsapp
                                    : ''
                            ); ?>"
                            maxlength="30"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-email">
                            E-mail
                        </label>
                    </th>

                    <td>

                        <input
                            type="email"
                            name="email"
                            id="btec-client-email"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->email
                                    : ''
                            ); ?>"
                            maxlength="120"
                        >

                    </td>
                </tr>


                <tr>
                    <th colspan="2">
                        <h2>Endereço</h2>
                    </th>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-cep">
                            CEP
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="cep"
                            id="btec-client-cep"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->cep
                                    : ''
                            ); ?>"
                            maxlength="10"
                        >

                        <p class="description">
                            A consulta automática de CEP será adicionada posteriormente.
                        </p>

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-address">
                            Endereço
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="address"
                            id="btec-client-address"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->address
                                    : ''
                            ); ?>"
                            maxlength="200"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-number">
                            Número
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="number"
                            id="btec-client-number"
                            class="small-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->number
                                    : ''
                            ); ?>"
                            maxlength="20"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-complement">
                            Complemento
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="complement"
                            id="btec-client-complement"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->complement
                                    : ''
                            ); ?>"
                            maxlength="100"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-neighborhood">
                            Bairro
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="neighborhood"
                            id="btec-client-neighborhood"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->neighborhood
                                    : ''
                            ); ?>"
                            maxlength="100"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-city">
                            Cidade
                        </label>
                    </th>

                    <td>

                        <input
                            type="text"
                            name="city"
                            id="btec-client-city"
                            class="regular-text"
                            value="<?php echo esc_attr(
                                $is_edit
                                    ? $client->city
                                    : ''
                            ); ?>"
                            maxlength="100"
                        >

                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-state">
                            Estado
                        </label>
                    </th>

                    <td>

                        <select
                            name="state"
                            id="btec-client-state"
                        >

                            <option value="">
                                Selecione
                            </option>

                            <?php

                            $states = [
                                'AC' => 'Acre',
                                'AL' => 'Alagoas',
                                'AP' => 'Amapá',
                                'AM' => 'Amazonas',
                                'BA' => 'Bahia',
                                'CE' => 'Ceará',
                                'DF' => 'Distrito Federal',
                                'ES' => 'Espírito Santo',
                                'GO' => 'Goiás',
                                'MA' => 'Maranhão',
                                'MT' => 'Mato Grosso',
                                'MS' => 'Mato Grosso do Sul',
                                'MG' => 'Minas Gerais',
                                'PA' => 'Pará',
                                'PB' => 'Paraíba',
                                'PR' => 'Paraná',
                                'PE' => 'Pernambuco',
                                'PI' => 'Piauí',
                                'RJ' => 'Rio de Janeiro',
                                'RN' => 'Rio Grande do Norte',
                                'RS' => 'Rio Grande do Sul',
                                'RO' => 'Rondônia',
                                'RR' => 'Roraima',
                                'SC' => 'Santa Catarina',
                                'SP' => 'São Paulo',
                                'SE' => 'Sergipe',
                                'TO' => 'Tocantins',
                            ];

                            foreach ($states as $uf => $state_name) :

                            ?>

                                <option
                                    value="<?php echo esc_attr($uf); ?>"
                                    <?php selected(
                                        $is_edit
                                            ? $client->state
                                            : '',
                                        $uf
                                    ); ?>
                                >
                                    <?php echo esc_html(
                                        $state_name
                                    ); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </td>
                </tr>


                <tr>
                    <th colspan="2">
                        <h2>Origem do Cliente</h2>
                    </th>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="btec-client-origin">
                            Origem
                        </label>
                    </th>

                    <td>

                        <select
                            name="origin"
                            id="btec-client-origin"
                            required
                        >

                            <option value="">
                                Selecione
                            </option>

                            <?php foreach ($origins as $key => $label) : ?>

                                <option
                                    value="<?php echo esc_attr($key); ?>"
                                    <?php selected(
                                        $client_origin,
                                        $key
                                    ); ?>
                                >
                                    <?php echo esc_html($label); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </td>
                </tr>

            </tbody>

        </table>


        <?php submit_button(
            $is_edit
                ? 'Salvar Alterações'
                : 'Cadastrar Cliente'
        ); ?>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeField = document.getElementById('btec-client-type');
    const documentField = document.getElementById('btec-client-document');
    const documentLabel = document.getElementById('btec-document-label');

    function updateDocumentField() {

        if (!typeField || !documentField || !documentLabel) {
            return;
        }

        if (typeField.value === 'pj') {

            documentLabel.textContent = 'CNPJ';

            documentField.placeholder = '00.000.000/0000-00';

            documentField.maxLength = 18;

        } else {

            documentLabel.textContent = 'CPF';

            documentField.placeholder = '000.000.000-00';

            documentField.maxLength = 14;

        }
    }

    if (typeField) {

        typeField.addEventListener(
            'change',
            updateDocumentField
        );

        updateDocumentField();
    }

});
</script>