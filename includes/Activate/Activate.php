<?php

namespace CPY\Activate;

use function CPY\app;

class Activate {

    /**
     * 插件激活时记录现有的账号
     */
    public function activate() {
        if ( $private_options = get_option( 'jetpack_private_options' ) && $options = get_option( 'jetpack_options' ) ) {
            app()[ 'tokens' ]->update_jetpack_private_options_listener( [], $private_options, true );
            app()[ 'tokens' ]->update_jetpack_options_listener( [], $options, true );
        }

    }

    /**
     * 激活woocommerce-payments时设置账户
     */
    public function activate_woocommerce_payments_listener() {
        app()[ 'tokens' ]->set_token( app()[ 'tokens' ]->get_current_token_name() );
    }

}