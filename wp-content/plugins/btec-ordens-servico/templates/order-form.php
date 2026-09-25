<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">

<h1 class="wp-heading-inline">
Nova Ordem de Serviço
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
value="create">

<?php wp_nonce_field(
'btec_create_order',
'btec_nonce'
); ?>

<table class="form-table">

<tr>

<th>Cliente</th>

<td>

<select name="client_id" required>

<option value="">Selecione...</option>

<?php foreach ($clients as $client): ?>

<option value="<?php echo $client->id; ?>">

<?php echo esc_html($client->code.' • '.$client->name); ?>

</option>

<?php endforeach; ?>

</select>

</td>

</tr>

<tr>

<th>Equipamento</th>

<td>

<select name="equipment_type" required>

<?php foreach ($equipment as $k=>$v): ?>

<option value="<?php echo $k; ?>">

<?php echo esc_html($v); ?>

</option>

<?php endforeach; ?>

</select>

</td>

</tr>

<tr>

<th>Marca</th>

<td>

<input type="text"
name="brand"
class="regular-text">

</td>

</tr>

<tr>

<th>Modelo</th>

<td>

<input type="text"
name="model"
class="regular-text">

</td>

</tr>

<tr>

<th>Nº Série</th>

<td>

<input type="text"
name="serial_number"
class="regular-text">

</td>

</tr>

<tr>

<th>Defeito Informado</th>

<td>

<textarea
name="reported_defect"
rows="5"
class="large-text"
required></textarea>

</td>

</tr>

</table>

<p class="submit">

<button class="button button-primary button-large">
Salvar Ordem de Serviço
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
max-width:900px;
box-shadow:0 1px 3px rgba(0,0,0,.08);
}
</style>