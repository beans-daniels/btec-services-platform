<?php

if (!defined('ABSPATH')) exit;

class BTEC_Database
{
    public function install()
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset = $wpdb->get_charset_collate();

        $companies = $wpdb->prefix . 'btec_companies';
        $clients   = $wpdb->prefix . 'btec_clients';
        $logs      = $wpdb->prefix . 'btec_logs';

        $sql = "

        CREATE TABLE {$companies} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(150) NOT NULL,
            document VARCHAR(20),
            phone VARCHAR(30),
            email VARCHAR(120),
            address TEXT,
            active TINYINT DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        ) $charset;

        CREATE TABLE {$clients} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            company_id BIGINT UNSIGNED NOT NULL,
            code VARCHAR(20) NOT NULL,
            type VARCHAR(2) DEFAULT 'PF',
            name VARCHAR(150) NOT NULL,
            cpf_cnpj VARCHAR(20),
            phone VARCHAR(30),
            email VARCHAR(120),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        ) $charset;

        CREATE TABLE {$logs} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED,
            module VARCHAR(50),
            action VARCHAR(50),
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        ) $charset;

        ";

        dbDelta($sql);

        $exists = $wpdb->get_var("SELECT COUNT(*) FROM {$companies}");

        if (!$exists) {
            $wpdb->insert(
                $companies,
                [
                    'name'     => 'B-TEC Matriz',
                    'active'   => 1
                ]
            );
        }
    }
}