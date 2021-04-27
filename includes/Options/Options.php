<?php

namespace CPY\Options;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Options {

    /**
     * 规则配置页面
     */
    public function options_page() {
        $tokens = apply_filters( 'cpy_current_token_options', [] );
        Container::make( 'theme_options', __( 'Change Payments Account', 'change-payments-account' ) )
            ->add_tab( __( 'Change Account', 'change-payments-account' ), [
                Field::make( 'checkbox', 'cpy_schedule_enabled', __( 'Start the account loop (will automatically skip the wrong account)', 'change-payments-account' ) ),

                Field::make( 'select', 'cpy_schedule_recurrence', __( 'Loop interval', 'change-payments-account' ) )
                    ->set_options( [
                        'hourly' => __( 'Hourly', 'change-payments-account' ),
                        'twicedaily' => __( 'Twice daily', 'change-payments-account' ),
                        'daily' => __( 'Daily', 'change-payments-account' ),
                        'weekly' => __( 'Weekly', 'change-payments-account' ),
                    ] )->set_conditional_logic( [
                        [
                            'field' => "cpy_schedule_enabled",
                            'value' => true,
                            'compare' => '=',
                        ],
                    ] ),

                Field::make( 'select', 'cpy_current_token', __( 'Current account', 'change-payments-account' ) )
                    ->set_options( array_merge( [ 0 => __( 'unset', 'change-payments-account' ) ], $tokens ) ),

                Field::make( 'html', 'cpy_add_token' )
                    ->set_html( sprintf( '<a href="%s">' . __( 'Connect new account', 'change-payments-account' ) . '</a>', esc_url( site_url( '?wc-ajax=cpy_bind_new_account' ) ) ) ),
            ] )
            ->add_tab( __( 'Delete accounts', 'change-payments-account' ), [
                Field::make( 'multiselect', 'crb_delete_accounts', __( 'Select the account that you want to delete (Orders receiving Payments with a selected account will not be refundable through Woocommerce Payments!)', 'change-payments-account' ) )
                    ->add_options( $tokens )
            ] );
    }

    /**
     * 绑定新账号
     */
    public function bind_new_account() {
        do_action( 'cpy_bind_new_account' );
        remove_query_arg( 'wc-ajax' );
        wp_safe_redirect( admin_url( 'admin.php?page=wc-admin&path=%2Fpayments%2Fconnect' ) );
    }

}