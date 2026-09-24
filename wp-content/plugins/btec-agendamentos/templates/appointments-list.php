<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">

    <h1 class="wp-heading-inline">Agendamentos</h1>

    <a href="<?php echo admin_url(
        'admin.php?page=btec-agendamentos&action=new'
    ); ?>" class="page-title-action">

        Novo Agendamento

    </a>

    <hr class="wp-header-end">

    <table class="widefat striped">

        <thead>

            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Serviço</th>
                <th>Data</th>
                <th>Status</th>
            </tr>

        </thead>

        <tbody>

        <?php if (empty($appointments)) : ?>

            <tr>

                <td colspan="5">

                    Nenhum agendamento encontrado.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>