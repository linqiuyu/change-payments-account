<?php

namespace CPY\Options;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Field\Complex_Field;
use WC_Payments_Account;
use function CPY\app;

class Options {

    /**
     * 规则配置页面
     */
    public function options_page() {
        $tokens = apply_filters( 'cpy_current_token_options', [] );
        Container::make( 'theme_options', 'Change Payments Account' )
            ->add_tab( '切换账号', [
                Field::make( 'checkbox', 'cpy_schedule_enabled', '开启账号轮询（会自动跳过错误账号）' ),
                Field::make( 'select', 'cpy_schedule_recurrence', '轮询间隔' )
                    ->set_options( [
                        'hourly' => '每小时',
                        'twicedaily' => '每12小时',
                        'daily' => '每天',
                        'weekly' => '每周',
                    ] )->set_conditional_logic( [
                        [
                            'field' => "cpy_schedule_enabled",
                            'value' => true,
                            'compare' => '=',
                        ]
                    ] ),
                Field::make( 'select', 'cpy_current_token', '当前账号' )
                    ->set_options( array_merge( [ 0 => '未设置' ], $tokens ) ),
                Field::make( 'html', 'cpy_add_token' )
                    ->set_html( sprintf( '<a href="%s">绑定新账号</a>', esc_url( site_url( '?wc-ajax=cpy_bind_new_account' ) ) ) ),
            ] )
            ->add_tab( '删除账号', [
                Field::make( 'multiselect', 'crb_delete_accounts', '选择需要删除的账号（选中账号收款的订单将无法通过Woocommerce Payments退款！）' )
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