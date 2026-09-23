<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Menu administrativo do módulo Clientes.
 */
class BTEC_Client_Menu
{
    /**
     * Inicializa os menus.
     *
     * @return void
     */
    public function init()
    {
        add_action(
            'admin_menu',
            [$this, 'register_menu'],
            20
        );
    }

    /**
     * Registra o menu de Clientes.
     *
     * @return void
     */
    public function register_menu()
    {
        /*
         * O BTEC Core possui o menu principal
         * "B-TEC Platform".
         *
         * O módulo Clientes será adicionado
         * como submenu.
         */
        add_submenu_page(
            'btec-platform',
            'Clientes',
            'Clientes',
            'read',
            'btec-clientes',
            [$this, 'render_page']
        );
    }

    /**
     * Renderiza a página principal.
     *
     * A implementação visual ficará no
     * BTEC_Client_Admin.
     *
     * @return void
     */
    public function render_page()
    {
        if (!class_exists('BTEC_Client_Admin')) {
            wp_die(
                esc_html__(
                    'O módulo administrativo de clientes não está disponível.',
                    'btec-clientes'
                )
            );
        }

        $admin = new BTEC_Client_Admin();

        $admin->render();
    }
}