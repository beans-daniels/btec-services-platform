<?php

if (!defined('ABSPATH')) exit;

class BTEC_Order_Repository
{
    private $table;

    public function __construct()
    {
        global $wpdb;

        $this->table = $wpdb->prefix.'btec_orders';
    }

    public function create($data)
    {
        global $wpdb;

        $number = BTEC_Sequence_Manager::next(
            $data['company_id'],
            'orders'
        );

        if (!$number) {
            return false;
        }

        $data['number'] = $number;

        $wpdb->insert($this->table, $data);

        return $wpdb->insert_id;
    }

    public function get_all($company_id)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                 FROM {$this->table}
                 WHERE company_id=%d
                 ORDER BY created_at DESC",
                $company_id
            )
        );
    }
}