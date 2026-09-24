<?php

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">

    <h1 class="wp-heading-inline">
        Novo Agendamento
    </h1>

    <a href="<?php echo admin_url(
        'admin.php?page=btec-agendamentos'
    ); ?>" class="page-title-action">

        Voltar

    </a>

    <hr class="wp-header-end">

    <div class="btec-card">

        <form
			method="post"
			action="<?php echo esc_url(admin_url('admin.php?page=btec-agendamentos')); ?>"
		>
			<input
				type="hidden"
				name="btec_appointment_action"
				value="create"
			>
			<?php
				wp_nonce_field(
					'btec_create_appointment',
					'btec_nonce'
				);
			?>
            <div class="btec-grid">

                <div class="btec-field full">

                    <label>Cliente</label>

                    <select name="client_id" required>

                        <option value="">
                            Selecione...
                        </option>

                        <?php foreach ($clients as $client) : ?>

                            <option value="<?php echo esc_attr($client->id); ?>">

                                <?php
                                echo esc_html(
                                    $client->code . ' • ' . $client->name
                                );
                                ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="btec-field">

                    <label>Serviço</label>

                    <select
                        id="service_type"
                        name="service_type"
                        required
                    >

                        <option value="">
                            Selecione...
                        </option>

                        <?php foreach ($services as $key => $label) : ?>

                            <option value="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($label); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div
                    class="btec-field"
                    id="infra_area"
                    style="display:none;"
                >

                    <label>Infraestrutura</label>

                    <select name="service_target">

                        <?php foreach ($infra as $key => $label) : ?>

                            <option value="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($label); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="btec-field">

                    <label>Data</label>

                    <input
                        type="date"
                        name="scheduled_date"
                        required
                    >

                </div>


                <div class="btec-field">

                    <label>Horário</label>

                  <input
						type="time"
						name="scheduled_time"
						step="900"
						required
					>

                </div>


                <div class="btec-field full">

                    <label>Observações</label>

                    <textarea
                        name="notes"
                        rows="4"
                    ></textarea>

                </div>

            </div>


            <div class="btec-actions">

                <button
                    class="button button-primary button-large"
                    type="submit"
                >
                    Salvar Agendamento
                </button>

            </div>

        </form>

    </div>

</div>


<style>

.btec-card{
    background:#fff;
    padding:24px;
    border-radius:12px;
    box-shadow:0 1px 3px rgba(0,0,0,.08);
    max-width:1000px;
}

.btec-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:18px;
}

.btec-field{
    display:flex;
    flex-direction:column;
}

.btec-field.full{
    grid-column:1/-1;
}

.btec-field label{
    font-weight:600;
    margin-bottom:6px;
}

.btec-field input,
.btec-field select,
.btec-field textarea{
    width:100%;
}

.btec-actions{
    margin-top:24px;
}

@media (max-width:768px){

    .btec-card{
        padding:16px;
    }

    .btec-grid{
        grid-template-columns:1fr;
    }

}

</style>


<script>

document.addEventListener('DOMContentLoaded',function(){

    const service=document.getElementById('service_type');
    const infra=document.getElementById('infra_area');

    function toggleInfra(){

        if(service.value==='infrastructure'){
            infra.style.display='flex';
        }else{
            infra.style.display='none';
        }

    }

		service.addEventListener('change', toggleInfra);
		toggleInfra();
});

</script>