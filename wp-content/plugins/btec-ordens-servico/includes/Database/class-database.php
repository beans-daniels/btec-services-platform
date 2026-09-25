<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order_Database
{
    public function install()
    {
        global $wpdb;

        $charset = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . 'btec_orders';

        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            company_id BIGINT UNSIGNED NOT NULL,
            appointment_id BIGINT UNSIGNED NULL,
            client_id BIGINT UNSIGNED NOT NULL,
            number VARCHAR(20) NOT NULL,
            equipment_type VARCHAR(30) NOT NULL,
            brand VARCHAR(80) NULL,
            model VARCHAR(120) NULL,
            serial_number VARCHAR(120) NULL,
            reported_defect TEXT NULL,
            diagnosis TEXT NULL,
            solution TEXT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'open',
            labor_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            parts_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            total_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_by BIGINT UNSIGNED NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uk_number (number),
            KEY idx_company (company_id),
            KEY idx_client (client_id),
            KEY idx_appointment (appointment_id)
        ) {$charset};";

        $wpdb->query($sql);

        $items_table = $wpdb->prefix . 'btec_order_items';

        $sql_items = "CREATE TABLE IF NOT EXISTS {$items_table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            order_id BIGINT UNSIGNED NOT NULL,
            description VARCHAR(150) NOT NULL,
            quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00,
            unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            total_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

            PRIMARY KEY (id),
            KEY idx_order (order_id)

        ) {$charset};";

        $wpdb->query($sql_items);

        if ($wpdb->last_error) {
            error_log('BTEC ORDER ITEMS: ' . $wpdb->last_error);
        }

        // Registra a sequência OS
        $seq_table = $wpdb->prefix . 'btec_sequences';

        $company_id = (int) $wpdb->get_var(
            "SELECT id FROM {$wpdb->prefix}btec_companies LIMIT 1"
        );

        if ($company_id > 0) {

            $exists = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id
                     FROM {$seq_table}
                     WHERE company_id = %d
                     AND module = %s",
                    $company_id,
                    'orders'
                )
            );

            if (!$exists) {

                $wpdb->insert(
                    $seq_table,
                    [
                        'company_id' => $company_id,
                        'module' => 'orders',
                        'prefix' => 'OS',
                        'last_number' => 0
                    ]
                );

            }
        }
    }
}