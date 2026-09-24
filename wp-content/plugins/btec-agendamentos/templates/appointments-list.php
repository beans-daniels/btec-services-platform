<?php

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">

    <h1 class="wp-heading-inline">Agendamentos</h1>

    <a href="<?php echo esc_url(
        admin_url('admin.php?page=btec-agendamentos&action=new')
    ); ?>" class="page-title-action">
        Novo Agendamento
    </a>

    <hr class="wp-header-end">

    <?php if (!empty($_GET['message']) && $_GET['message'] === 'created') : ?>

        <div class="notice notice-success is-dismissible">
            <p>Agendamento criado com sucesso.</p>
        </div>

    <?php endif; ?>

    <table class="wp-list-table widefat fixed striped">

        <thead>

            <tr>
                <th style="width:120px;">Número</th>
                <th>Cliente</th>
                <th style="width:170px;">Serviço</th>
                <th style="width:170px;">Data / Hora</th>
                <th style="width:120px;">Status</th>
                <th style="width:170px;">Ações</th>
            </tr>

        </thead>

        <tbody>
        
        <?php if (empty($appointments)) : ?>
        
            <tr>
                <td colspan="5">
                    Nenhum agendamento encontrado.
                </td>
            </tr>
        
        <?php else : ?>
        
            <?php foreach ($appointments as $appointment) : ?>
        
                <tr>
        
                    <td>
                        <strong><?php echo esc_html($appointment->number); ?></strong>
                    </td>
        
                    <td>
                        <strong><?php echo esc_html($appointment->client_name); ?></strong>
                        <br>
                        <small><?php echo esc_html($appointment->client_code); ?></small>
                    </td>
        
                    <td>
                        <?php echo esc_html(BTEC_Appointment::get_service_label($appointment->service_type)); ?>
                    </td>
        
                    <td>
                        <?php
                        echo esc_html(
                            date_i18n(
                                'd/m/Y',
                                strtotime($appointment->scheduled_date)
                            )
                        );
                        ?>
                        <br>
                        <small><?php echo esc_html(substr($appointment->scheduled_time,0,5)); ?></small>
                    </td>
        
                    <td>
                    
                        <?php
                        $status_label = BTEC_Appointment::get_status_label(
                            $appointment->status
                        );
                        ?>
                    
                        <span class="btec-status status-<?php echo esc_attr($appointment->status); ?>">
                            <?php echo esc_html($status_label); ?>
                        </span>
                    
                    </td>
        
                </tr>
        
            <?php endforeach; ?>
        
        <?php endif; ?>
        
        </tbody>
    </table>

</div>

<style>

.btec-status{
    display:inline-block;
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.status-scheduled{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-waiting{
    background:#fef3c7;
    color:#92400e;
}

.status-completed{
    background:#dcfce7;
    color:#166534;
}

.status-cancelled{
    background:#fee2e2;
    color:#991b1b;
}

</style>