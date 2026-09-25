<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">

    <h1 class="wp-heading-inline">

        <?php echo $order ? 'Ordem de Serviço' : 'Nova Ordem de Serviço'; ?>

    </h1>

    <a href="<?php echo admin_url('admin.php?page=btec-ordens-servico'); ?>"
       class="page-title-action">

        Voltar

    </a>

    <hr class="wp-header-end">

    <div class="btec-card">

        <form method="post">

            <input type="hidden"
                   name="btec_order_action"
                   value="<?php echo $order ? 'update' : 'create'; ?>">

            <?php if ($order): ?>

                <input type="hidden"
                       name="order_id"
                       value="<?php echo esc_attr($order->id); ?>">

                <?php wp_nonce_field('btec_update_order','btec_nonce'); ?>

            <?php else: ?>

                <?php wp_nonce_field('btec_create_order','btec_nonce'); ?>

            <?php endif; ?>

            <!-- CABEÇALHO -->

            <div class="btec-header">

                <div>

                    <span class="label">Número</span>

                    <h2>

                        <?php echo $order ? esc_html($order->number) : 'Será gerado automaticamente'; ?>

                    </h2>

                </div>

                <?php if ($order): ?>

                <div class="status-box">

                    <label>Status</label>

                    <select name="status">

                        <?php foreach ($statuses as $k=>$v): ?>

                            <option value="<?php echo $k; ?>"
                                <?php selected($order->status,$k); ?>>

                                <?php echo esc_html($v); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <?php endif; ?>

            </div>

            <!-- CLIENTE -->

            <h3>Cliente</h3>

            <?php if (!$order): ?>

                <select name="client_id" required>

                    <option value="">Selecione...</option>

                    <?php foreach ($clients as $client): ?>

                        <option value="<?php echo $client->id; ?>">

                            <?php echo esc_html($client->code.' • '.$client->name); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            <?php else: ?>

                <input type="hidden"
                       name="client_id"
                       value="<?php echo esc_attr($order->client_id); ?>">

                <div class="readonly">

                    <strong><?php echo esc_html($order->client_name); ?></strong>

                    <br>

                    <?php echo esc_html($order->client_code); ?>

                </div>

            <?php endif; ?>

            <!-- EQUIPAMENTO -->

            <h3>Equipamento</h3>

            <div class="grid">

                <div>

                    <label>Tipo</label>

                    <select name="equipment_type">

                        <?php foreach ($equipment as $k=>$v): ?>

                            <option value="<?php echo $k; ?>"
                                <?php selected($order->equipment_type ?? '',$k); ?>>

                                <?php echo esc_html($v); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div>

                    <label>Marca</label>

                    <input type="text"
                           name="brand"
                           value="<?php echo esc_attr($order->brand ?? ''); ?>">

                </div>

                <div>

                    <label>Modelo</label>

                    <input type="text"
                           name="model"
                           value="<?php echo esc_attr($order->model ?? ''); ?>">

                </div>

                <div>

                    <label>Nº Série</label>

                    <input type="text"
                           name="serial_number"
                           value="<?php echo esc_attr($order->serial_number ?? ''); ?>">

                </div>

            </div>

            <!-- DEFEITO -->

            <h3>Defeito informado</h3>

            <textarea name="reported_defect"
                      rows="4"
                      required><?php echo esc_textarea($order->reported_defect ?? ''); ?></textarea>

            <!-- DIAGNÓSTICO -->

            <h3>Diagnóstico Técnico</h3>

            <textarea name="diagnosis"
                      rows="5"><?php echo esc_textarea($order->diagnosis ?? ''); ?></textarea>

            <!-- SOLUÇÃO -->

            <h3>Solução Aplicada</h3>

            <textarea name="solution"
                      rows="5"><?php echo esc_textarea($order->solution ?? ''); ?></textarea>

            <!-- VALORES -->

            <h3>Valores</h3>

            <div class="grid values">

                <div>

                    <label>Mão de obra</label>

                    <input type="number"
                           step="0.01"
                           id="labor"
                           name="labor_value"
                           value="<?php echo esc_attr($order->labor_value ?? 0); ?>">

                </div>

                <div>

                    <label>Peças</label>

                    <input type="number"
                           step="0.01"
                           id="parts"
                           name="parts_value"
                           value="<?php echo esc_attr($order->parts_value ?? 0); ?>">

                </div>

                <div>

                    <label>Total</label>

                    <input type="number"
                           step="0.01"
                           id="total"
                           name="total_value"
                           readonly
                           value="<?php echo esc_attr($order->total_value ?? 0); ?>">

                </div>

            </div>

            <p class="submit">

                <button class="button button-primary button-large">

                    <?php echo $order ? 'Salvar Alterações' : 'Criar Ordem de Serviço'; ?>

                </button>

            </p>

        </form>

    </div>

</div>

<style>

.btec-card{
    background:#fff;
    padding:24px;
    border-radius:12px;
    max-width:1000px;
    box-shadow:0 1px 3px rgba(0,0,0,.08);
}

.btec-header{
    display:flex;
    justify-content:space-between;
    align-items:end;
    margin-bottom:24px;
}

.btec-header .label{
    color:#64748b;
    font-size:12px;
}

.status-box{
    width:220px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
    margin-bottom:20px;
}

.values{
    grid-template-columns:repeat(3,1fr);
}

.readonly{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:8px;
    padding:14px;
    margin-bottom:20px;
}

textarea,
input,
select{
    width:100%;
}

@media(max-width:768px){

    .grid,
    .values{
        grid-template-columns:1fr;
    }

    .btec-header{
        flex-direction:column;
        align-items:start;
        gap:16px;
    }

    .status-box{
        width:100%;
    }

}

</style>

<script>

document.addEventListener('DOMContentLoaded',()=>{

    const labor=document.getElementById('labor');
    const parts=document.getElementById('parts');
    const total=document.getElementById('total');

    function calc(){

        const l=parseFloat(labor.value)||0;
        const p=parseFloat(parts.value)||0;

        total.value=(l+p).toFixed(2);

    }

    labor.addEventListener('input',calc);
    parts.addEventListener('input',calc);

    calc();

});

</script>