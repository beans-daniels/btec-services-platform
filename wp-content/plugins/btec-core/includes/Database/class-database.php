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
		$sequences = $wpdb->prefix . 'btec_sequences';

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
			type VARCHAR(2) DEFAULT 'pf',
			name VARCHAR(150) NOT NULL,
			cpf_cnpj VARCHAR(20),
			phone VARCHAR(30),
			email VARCHAR(120),
			whatsapp VARCHAR(30),
			cep VARCHAR(10),
			address VARCHAR(200),
			number VARCHAR(20),
			complement VARCHAR(100),
			neighborhood VARCHAR(100),
			city VARCHAR(100),
			state VARCHAR(2),
			origin VARCHAR(30),
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY company_id (company_id),
			KEY code (code),
			KEY cpf_cnpj (cpf_cnpj)
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
		CREATE TABLE {$sequences} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			company_id BIGINT UNSIGNED NOT NULL,
			module VARCHAR(30) NOT NULL,
			prefix VARCHAR(10) NOT NULL,
			last_number BIGINT UNSIGNED DEFAULT 0,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY company_module (company_id, module),
			KEY prefix (prefix)
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
		$company_id = (int) $wpdb->get_var(
			"SELECT id FROM {$companies} ORDER BY id ASC LIMIT 1"
		);

		if (!$company_id) {
			return;
		}

			$modules = [
				['clients', 'CLI'],
				['appointments', 'AG'],
				['orders', 'OS'],
				['coupons', 'CP'],
				['products', 'PRD'],
			];

			foreach ($modules as $module) {

				$exists = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT id
						 FROM {$sequences}
						 WHERE company_id = %d
						 AND module = %s",
						$company_id,
						$module[0]
					)
				);

				if (!$exists) {

					$wpdb->insert(
						$sequences,
						[
							'company_id' => $company_id,
							'module'     => $module[0],
							'prefix'     => $module[1],
							'last_number'=> 0,
						]
					);

				}
			}
    }
}