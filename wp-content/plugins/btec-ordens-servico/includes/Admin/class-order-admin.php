<?php

if (!defined('ABSPATH')) exit;

class BTEC_Order_Admin
{
    public function init()
    {
    }

    public function render()
    {
        $orders = [];

        require BTEC_OS_PATH . 'templates/orders-list.php';
    }
}