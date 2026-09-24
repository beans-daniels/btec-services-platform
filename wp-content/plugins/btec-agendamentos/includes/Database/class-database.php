<?php

if (!defined('ABSPATH')) exit;

class BTEC_Appointment_Database
{
    public function install()
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table = $wpdb->prefix . 'btec_appointments';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (

            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

            company_id BIGINT UNSIGNED NOT NULL,
            client_id BIGINT UNSIGNED NOT NULL,

            number VARCHAR(20) NOT NULL,

            service_type VARCHAR(40) NOT NULL,
            service_target VARCHAR(20) DEFAULT 'residence',

            scheduled_date DATE NOT NULL,
            scheduled_time TIME NOT NULL,

            status VARCHAR(20) DEFAULT 'scheduled',

            pending_reason VARCHAR(30) DEFAULT NULL,

            notes TEXT,

            created_by BIGINT UNSIGNED DEFAULT NULL,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            PRIMARY KEY (id),

            UNIQUE KEY number (number),

            KEY company_id (company_id),
            KEY client_id (client_id),
            KEY status (status),
            KEY scheduled_date (scheduled_date)

        ) {$charset};";

        dbDelta($sql);
    }
}