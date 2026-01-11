<?php

/**
 * Plugin Name: ShieldClimb – Auto Complete Orders for WooCommerce
 * Plugin URI: https://shieldclimb.com/free-woocommerce-plugins/auto-complete-orders/
 * Description: Auto Complete Orders for WooCommerce after payment. Works with downloadable & virtual products. Fast, lightweight & compatible with gateways.
 * Version: 1.0.3
 * Requires Plugins: woocommerce
 * Requires at least: 5.8
 * Tested up to: 6.9
 * WC requires at least: 5.8
 * WC tested up to: 10.4.3
 * Requires PHP: 7.2
 * Author: shieldclimb.com
 * Author URI: https://shieldclimb.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action(
    'woocommerce_order_status_processing',
    'shieldclimb_auto_complete_virtual_or_downloadable_order'
);

function shieldclimb_auto_complete_virtual_or_downloadable_order($order_id) {
    if (! $order_id) {
        return;
    }

    $order = wc_get_order($order_id);
    if (! $order) {
        return;
    }

    $only_virtual_or_downloadable = true;

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();

        if (
            ! $product ||
            (! $product->is_virtual() && ! $product->is_downloadable())
        ) {
            $only_virtual_or_downloadable = false;
            break;
        }
    }

    if ($only_virtual_or_downloadable) {
        $order->update_status(
            'completed',
            'Auto-completed by ShieldClimb for virtual/downloadable products.'
        );
    }
}

?>