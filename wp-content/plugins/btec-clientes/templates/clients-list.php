<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Variáveis esperadas:
 *
 * $clients
 * $search
 * $page
 * $per_page
 * $total
 * $total_pages
 */

?>

<div class="wrap">

    <h1 class="wp-heading-inline">
        Clientes
    </h1>

    <a
        href="<?php echo esc_url(
            admin_url(
                'admin.php?page=btec-clientes&action=add'
            )
        ); ?>"
        class="page-title-action"
    >
        Novo Cliente
    </a>

    <hr class="wp-header-end">

    <?php if (!empty($_GET['btec_message'])) : ?>

        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                echo esc_html(
                    rawurldecode(
                        wp_unslash(
                            $_GET['btec_message']
                        )
                    )
                );
                ?>
            </p>
        </div>

    <?php endif; ?>


    <?php if (!empty($_GET['btec_error'])) : ?>

        <div class="notice notice-error is-dismissible">
            <p>
                <?php
                echo esc_html(
                    rawurldecode(
                        wp_unslash(
                            $_GET['btec_error']
                        )
                    )
                );
                ?>
            </p>
        </div>

    <?php endif; ?>


    <form method="get">

        <input
            type="hidden"
            name="page"
            value="btec-clientes"
        >

        <p class="search-box">

            <label
                class="screen-reader-text"
                for="client-search-input"
            >
                Pesquisar clientes:
            </label>

            <input
                type="search"
                id="client-search-input"
                name="s"
                value="<?php echo esc_attr($search); ?>"
                placeholder="Nome, CPF/CNPJ, telefone ou código"
            >

            <input
                type="submit"
                class="button"
                value="Pesquisar"
            >

        </p>

    </form>


    <div class="tablenav top">

        <div class="alignleft actions">

            <span class="displaying-num">
                <?php
                echo esc_html(
                    number_format_i18n($total)
                );
                ?>
                <?php
                echo $total === 1
                    ? 'cliente'
                    : 'clientes';
                ?>
            </span>

        </div>

        <div class="tablenav-pages">

            <?php

            $pagination_base = add_query_arg(
                [
                    'page' => 'btec-clientes',
                    's' => $search,
                    'paged' => '%#%',
                ],
                admin_url('admin.php')
            );

			$pagination = paginate_links(
				[
					'base'      => $pagination_base,
					'format'    => '',
					'current'   => $page,
					'total'     => $total_pages,
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
				]
			);

			echo wp_kses_post($pagination ?? '');

            ?>

        </div>

        <br class="clear">

    </div>


    <table class="wp-list-table widefat fixed striped">

        <thead>

            <tr>

                <th scope="col" style="width: 100px;">
                    Código
                </th>

                <th scope="col">
                    Cliente
                </th>

                <th scope="col" style="width: 150px;">
                    CPF/CNPJ
                </th>

                <th scope="col" style="width: 150px;">
                    Telefone
                </th>

                <th scope="col" style="width: 130px;">
                    Origem
                </th>

                <th scope="col" style="width: 150px;">
                    Ações
                </th>

            </tr>

        </thead>


        <tbody>

        <?php if (empty($clients)) : ?>

            <tr>

                <td
                    colspan="6"
                    style="text-align: center; padding: 30px;"
                >
                    <strong>
                        Nenhum cliente encontrado.
                    </strong>

                    <br>

                    <?php if (!empty($search)) : ?>

                        <span>
                            Tente alterar os termos da pesquisa.
                        </span>

                    <?php else : ?>

                        <span>
                            Cadastre o primeiro cliente.
                        </span>

                    <?php endif; ?>

                </td>

            </tr>

        <?php else : ?>

            <?php foreach ($clients as $client) : ?>

                <tr>

                    <td>

                        <strong>
                            <?php
                            echo esc_html(
                                $client->code
                            );
                            ?>
                        </strong>

                    </td>


                    <td>

                        <strong>

                            <a
                                href="<?php echo esc_url(
                                    admin_url(
                                        'admin.php?page=btec-clientes&action=edit&client_id=' .
                                        absint($client->id)
                                    )
                                ); ?>"
                            >

                                <?php
                                echo esc_html(
                                    $client->get_display_name()
                                );
                                ?>

                            </a>

                        </strong>

                        <br>

                        <small>

                            <?php
                            echo esc_html(
                                $client->get_type_label()
                            );
                            ?>

                            <?php if (!empty($client->email)) : ?>

                                —
                                <?php
                                echo esc_html(
                                    $client->email
                                );
                                ?>

                            <?php endif; ?>

                        </small>

                    </td>


                    <td>

                        <?php
                        echo esc_html(
                            $client->get_formatted_document()
                        );
                        ?>

                    </td>


                    <td>

                        <?php if ($client->has_whatsapp()) : ?>

                            <a
                                href="<?php echo esc_url(
                                    $client->get_whatsapp_url()
                                ); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >

                                <?php
                                echo esc_html(
                                    $client->whatsapp
                                );
                                ?>

                            </a>

                        <?php elseif (!empty($client->phone)) : ?>

                            <?php
                            echo esc_html(
                                $client->phone
                            );
                            ?>

                        <?php else : ?>

                            <span>
                                —
                            </span>

                        <?php endif; ?>

                    </td>


                    <td>

                        <?php
                        echo esc_html(
                            $client->get_origin_label()
                        );
                        ?>

                    </td>


                    <td>

                        <a
                            href="<?php echo esc_url(
                                admin_url(
                                    'admin.php?page=btec-clientes&action=edit&client_id=' .
                                    absint($client->id)
                                )
                            ); ?>"
                            class="button button-small"
                        >
                            Editar
                        </a>


                        <?php if (current_user_can('manage_options')) : ?>

                            <?php

                            $delete_url = wp_nonce_url(
                                admin_url(
                                    'admin.php?page=btec-clientes' .
                                    '&btec_client_action=delete' .
                                    '&client_id=' .
                                    absint($client->id)
                                ),
                                'btec_client_delete_' .
                                absint($client->id),
                                'btec_client_nonce'
                            );

                            ?>

                            <a
                                href="<?php echo esc_url(
                                    $delete_url
                                ); ?>"
                                class="button button-small"
                                onclick="return confirm(
                                    'Tem certeza que deseja excluir este cliente?'
                                );"
                            >
                                Excluir
                            </a>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

        </tbody>


        <tfoot>

            <tr>

                <th>
                    Código
                </th>

                <th>
                    Cliente
                </th>

                <th>
                    CPF/CNPJ
                </th>

                <th>
                    Telefone
                </th>

                <th>
                    Origem
                </th>

                <th>
                    Ações
                </th>

            </tr>

        </tfoot>

    </table>


    <div class="tablenav bottom">

        <div class="tablenav-pages">

            <?php


			$pagination = paginate_links(
				[
					'base'      => $pagination_base,
					'format'    => '',
					'current'   => $page,
					'total'     => $total_pages,
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
				]
			);

			echo wp_kses_post($pagination ?? '');

            ?>

        </div>

        <br class="clear">

    </div>

</div>