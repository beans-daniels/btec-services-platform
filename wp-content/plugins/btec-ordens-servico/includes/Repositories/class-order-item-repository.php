<?php

if (!defined('ABSPATH')) {
    exit;
}

class BTEC_Order_Item_Repository
{
    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'btec_order_items';
    }

    /**
     * Lista as peças de uma OS
     */
    public function get_by_order($order_id)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
                 FROM {$this->table}
                 WHERE order_id = %d
                 ORDER BY id ASC",
                $order_id
            )
        );
    }

    /**
     * Remove todas as peças da OS
     */
    public function delete_by_order($order_id)
    {
        global $wpdb;

        return $wpdb->delete(
            $this->table,
            ['order_id' => $order_id]
        );
    }

    /**
     * Salva um item
     */
    public function create($order_id, $item)
    {
        global $wpdb;

        return $wpdb->insert(
            $this->table,
            [
                'order_id'    => $order_id,
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]
        );
    }
}