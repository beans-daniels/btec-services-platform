<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order_Database
{
    public function install()
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . 'btec_orders';
        $seq   = $wpdb->prefix . 'btec_sequences';

        $sql = "CREATE TABLE {$table} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            company_id BIGINT UNSIGNED NOT NULL,
            appointment_id BIGINT UNSIGNED NULL,
            client_id BIGINT UNSIGNED NOT NULL,

            number VARCHAR(20) NOT NULL,

            equipment_type VARCHAR(30) NOT NULL,
            brand VARCHAR(80),
            model VARCHAR(120),
            serial_number VARCHAR(120),

            reported_defect TEXT,
            diagnosis TEXT,
            solution TEXT,

            status VARCHAR(20) DEFAULT 'open',

            labor_value DECIMAL(10,2) DEFAULT 0,
            parts_value DECIMAL(10,2) DEFAULT 0,
            total_value DECIMAL(10,2) DEFAULT 0,

            created_by BIGINT UNSIGNED,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            PRIMARY KEY(id),
            UNIQUE KEY number(number),
            KEY company(company_id),
            KEY client(client_id),
            KEY appointment(appointment_id)

        ) {$charset};";

        dbDelta($sql);

        $company = $wpdb->get_var(
            "SELECT id FROM {$wpdb->prefix}btec_companies LIMIT 1"
        );

        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id
                 FROM {$seq}
                 WHERE company_id=%d
                 AND module='orders'",
                $company
            )
        );

        if (!$exists) {

            $wpdb->insert(
                $seq,
                [
                    'company_id' => $company,
                    'module'     => 'orders',
                    'prefix'     => 'OS',
                    'last_number'=> 0,
                ]
            );

        }
    }
}