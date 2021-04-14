<?php

namespace CPY\Order;

use function CPY\app;

class Order {

    public function admin_order_script() {
        global $pagenow;
        if ( $pagenow === 'post.php' && $order = wc_get_order() ) {
            $hide_api_refund = false;
            $token_name = '';

            if ( $order->get_payment_method() === 'woocommerce_payments' ) {
                $token_name = get_post_meta( $order->get_id(), 'woocommerce_payments_token_name', true );
                if ( $token_name !== app()[ 'tokens' ]->get_current_token_name() ) {
                    $hide_api_refund = true;
                }
            }
            wp_enqueue_script(
                'cpy-admin-order',
                plugin_dir_url( CPY_PLUGIN_FILE ) . 'assets/js/admin-order.js',
                [ 'jquery' ],
                false,
                true
            );

            wp_localize_script( 'cpy-admin-order', 'cpy_order_settings', array(
                'hide_api_refund' => $hide_api_refund,
                'token_name' => $token_name,
            ) );
        }
    }

}