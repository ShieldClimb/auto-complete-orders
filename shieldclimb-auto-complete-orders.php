<?php

/**
 * Plugin Name: ShieldClimb – Auto Complete Orders for WooCommerce
 * Plugin URI: https://shieldclimb.com/free-woocommerce-plugins/auto-complete-orders/
 * Description: Auto Complete Orders for WooCommerce after payment. Works with downloadable & virtual products. Fast, lightweight & compatible with gateways.
 * Version: 1.0.1
 * Requires Plugins: woocommerce
 * Requires at least: 5.8
 * Tested up to: 6.8
 * WC requires at least: 5.8
 * WC tested up to: 9.8.1
 * Requires PHP: 7.2
 * Author: shieldclimb.com
 * Author URI: https://shieldclimb.com/about-us/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_action('woocommerce_thankyou', 'shieldclimb_auto_complete_virtual_or_downloadable_order');

function shieldclimb_auto_complete_virtual_or_downloadable_order($order_id) {
    if (!$order_id) return;

    $order = wc_get_order($order_id);

    // Check if the order contains only virtual or downloadable items
    $shieldclimb_virtual_or_downloadable = true;

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();

        if (!$product || (! $product->is_virtual() && ! $product->is_downloadable())) {
            $shieldclimb_virtual_or_downloadable = false;
            break;
        }
    }

    // Auto-complete if all items are virtual or downloadable
    if ($shieldclimb_virtual_or_downloadable && $order->get_status() === 'processing') {
        $order->update_status('completed');
    }
}

?>