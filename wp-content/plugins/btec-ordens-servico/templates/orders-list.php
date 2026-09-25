<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">

    <h1 class="wp-heading-inline">
        Ordens de Serviço
    </h1>

	<a href="<?php echo admin_url(
		'admin.php?page=btec-ordens-servico&action=new'
		); ?>"
		class="page-title-action">
		Nova OS
	</a>

<hr class="wp-header-end">

<?php if (!empty($_GET['message']) && $_GET['message'] === 'created') : ?>

    <div class="notice notice-success is-dismissible">
        <p>Ordem de Serviço criada com sucesso.</p>
    </div>

<?php endif; ?>

    <table class="wp-list-table widefat striped">

        <thead>
            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Equipamento</th>
                <th style="width:120px;">Status</th>
                <th style="width:120px;">Ações</th>
            </tr>
        </thead>

		<tbody>

				<?php if (empty($orders)) : ?>

				<tr>
				<td colspan="5">
				Nenhuma ordem encontrada.
				</td>
				</tr>

				<?php else : ?>

				<?php foreach ($orders as $order) : ?>
				

				<tr>

    				<td>
        				<strong><?php echo esc_html($order->number); ?></strong>
    				</td>
    
    				<td>
        				<strong><?php echo esc_html($order->client_name); ?></strong><br>
        				<small><?php echo esc_html($order->client_code); ?></small>
    				</td>
    
    				<td>
        				<?php echo esc_html(
        				BTEC_Order::get_equipment_label($order->equipment_type)
        				); ?>
    				</td>
    
    				<td>
    
        				<span class="btec-status status-<?php echo esc_attr($order->status); ?>">
        
        				<?php echo esc_html(
        				BTEC_Order::get_status_label($order->status)
        				); ?>
        
        				</span>
    
    				</td>
    				
    				<td>
                    
                        <a href="<?php echo esc_url(
                            admin_url(
                                'admin.php?page=btec-ordens-servico' .
                                '&action=edit' .
                                '&order_id=' . $order->id
                            )
                        ); ?>" class="button button-small">
                    
                            Editar
                    
                        </a>
                    
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

.status-open{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-service{
    background:#e0f2fe;
    color:#0369a1;
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