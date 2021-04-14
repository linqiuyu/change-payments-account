<?php

namespace CPY\Processors;

use CPY\Application;
use CPY\ProcessorInterface;

class Filters implements ProcessorInterface {

    private Application $app;

    public function process( $app ) {
        $this->app = $app;
        $this->filters();
        if ( is_admin() ) {
            $this->admin_filters();
        }
        add_action( 'template_redirect', [$this, 'template_filters'] );
    }

    public function filters() {
        // Tokens
        add_filter( 'woocommerce_payment_successful_result', [ $this->app[ 'tokens' ], 'add_order_token_meta' ], 10, 2 );
    }

    public function admin_filters() {

    }

    public function template_filters() {

    }

}