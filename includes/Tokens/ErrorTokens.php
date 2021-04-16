<?php

namespace CPY\Tokens;

class ErrorTokens {
    private $error_tokens;

    public function __construct() {
        $this->error_tokens = get_option( 'cpy_payments_error_tokens', [] );
    }

    /**
     * @return array
     */
    public function get_error_tokens() {
        return $this->error_tokens;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function is_error_token( $name ) {
        return in_array( $name, $this->get_error_tokens() );
    }

    /**
     * @param string $name
     */
    public function add_error_token( $name ) {
        if ( ! in_array( $name, $this->error_tokens ) ) {
            $this->error_tokens[] = $name;
            $this->save();
        }
    }

    /**
     * @param $name
     */
    public function remove_error_token( $name ) {
        $key = array_search( $name ,$this->error_tokens );
        zx_woocommerce_log( $key );
        if ( $key !== false ) {
            unset( $this->error_tokens[ $key ] );
        }
        $this->save();
    }

    public function save() {
        update_option( 'cpy_payments_error_tokens', $this->error_tokens, false );
    }
}