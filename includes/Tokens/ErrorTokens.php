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
    public function is_error_tokens( $name ) {
        return in_array( $name, $this->get_error_tokens() );
    }

    /**
     * @param string $name
     */
    public function add_error_tokens( $name ) {
        $this->error_tokens[] = $name;
    }
}