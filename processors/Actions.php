<?php

namespace CPY\Processors;

use Carbon_Fields\Carbon_Fields;
use CPY\Application;
use CPY\ProcessorInterface;

/**
 * Class Actions
 *
 * @package CPY\Processors
 */
class Actions implements ProcessorInterface{

    private Application $app;

    public function process( $app ) {
        $this->app = $app;
        register_activation_hook( CPY_PLUGIN_FILE, [ $this->app[ 'activate' ], 'activate' ] );
        $this->actions();
        if ( is_admin() ) {
            $this->admin_actions();
        }
        add_action( 'template_redirect', [$this, 'template_actions'] );
    }

    public function actions() {
        add_action( 'plugins_loaded', [ $this->app[ 'i18n' ], 'load_textdomain' ] );
        add_action( 'after_setup_theme', [ Carbon_Fields::class, 'boot' ] );

        // Options
        add_action( 'carbon_fields_register_fields', [ $this->app[ 'options' ], 'options_page' ] );
        add_action( 'wc_ajax_cpy_bind_new_account', [ $this->app[ 'options' ], 'bind_new_account' ] );

        // Tokens
        add_action( 'update_option_jetpack_private_options', [ $this->app[ 'tokens' ], 'update_jetpack_private_options_listener' ], 10, 2 );
        add_action( 'update_option_jetpack_options', [ $this->app[ 'tokens' ], 'update_jetpack_options_listener' ], 10, 2 );
        add_action( 'carbon_fields_before_field_save', [ $this->app[ 'tokens' ], 'cpy_change_current_token_listener' ] );
        add_action( 'carbon_fields_before_field_save', [ $this->app[ 'tokens' ], 'cpy_delete_token_listener' ] );
        add_action( 'cpy_current_token_options', [ $this->app[ 'tokens' ], 'current_token_options' ] );
        add_action( 'cpy_bind_new_account', [ $this->app[ 'tokens' ], 'reset_jetpack_tokens' ] );
        add_action( 'set_transient_wcpay_account_data', [ $this->app[ 'tokens' ], 'error_token_listener' ] );
        add_action( 'init', [ $this->app[ 'tokens' ], 'schedule_init' ] );

        add_action( 'activate_woocommerce-payments/woocommerce-payments.php', [ $this->app[ 'activate' ], 'activate_woocommerce_payments_listener' ] );

    }

    public function admin_actions() {
        add_action( 'admin_enqueue_scripts', [ $this->app[ 'order' ], 'admin_order_script' ] );
    }

    public function template_actions() {

    }

}