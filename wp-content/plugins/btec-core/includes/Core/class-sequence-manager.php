<?php

if (!defined('ABSPATH')) exit;

class BTEC_Sequence_Manager
{
    public static function next($company_id, $module)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'btec_sequences';

        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT *
                 FROM {$table}
                 WHERE company_id = %d
                 AND module = %s",
                $company_id,
                $module
            )
        );

        if (!$row) {
            return false;
        }

        $next = ((int) $row->last_number) + 1;

        $wpdb->update(
            $table,
            ['last_number' => $next],
            ['id' => $row->id]
        );

        return sprintf(
            '%s-%06d',
            $row->prefix,
            $next
        );
    }
}