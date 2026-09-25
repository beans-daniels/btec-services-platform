<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order_History_Repository
{
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'btec_order_history';
    }

    /**
     * Adiciona um evento ao histórico
     */
    public function create(
        $order_id,
        $event_type,
        $description
    ) {
        global $wpdb;

        return $wpdb->insert(
            $this->table,
            [
                'order_id'    => $order_id,
                'event_type'  => $event_type,
                'description' => $description,
                'created_by'  => get_current_user_id(),
            ]
        );
    }

    /**
     * Lista o histórico da OS
     */
    public function get_by_order($order_id)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                 FROM {$this->table}
                 WHERE order_id = %d
                 ORDER BY created_at DESC, id DESC",
                $order_id
            )
        );
    }
}