<?php

namespace CPY\Tokens;

use Carbon_Fields\Field\Field;
use function foo\func;

class TokensManager {

    const Options_Name = 'cpy_payments_tokens';

    /**
     * @return array
     */
    public function get_tokens() {
        return get_option( self::Options_Name ) ?: [];
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get_token( $name ) {
        if ( $this->has_token( $name ) ) {
            return $this->get_tokens()[ $name ];
        }

        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has_token( $name ) {
        return isset( $this->get_tokens()[ $name ] );
    }

    /**
     * @return string
     */
    public function get_current_token_name() {
        if ( ( $private_token = get_option( 'jetpack_private_options' ) ) && ( $options = get_option( 'jetpack_options' ) ) ) {
            return get_user_by( 'ID', $options[ 'master_user' ] )->user_email . ': ' . $private_token[ 'user_tokens' ][ $options[ 'master_user' ] ];
        } else {
            return 0;
        }

    }

    /**
     * @param string $name
     * @param array $token
     * @param bool $private
     */
    public function add_token( $name, $token, $private = false ) {
        $tokens = $this->get_tokens();
        if ( ! $this->has_token( $name ) ) {
            $tokens[ $name ] = [];
        }

        if ( $private ) {
            $tokens[ $name ][ 'jetpack_private_options' ] = $token;
        } else {
            $tokens[ $name ][ 'jetpack_options' ] = $token;
        }

        update_option( self::Options_Name, $tokens, false );
    }

    /**
     * @param string|array $name
     */
    public function delete_token( $name ) {
        if ( is_array( $name ) ) {
            foreach ( $name as $item ) {
                $this->delete_token( $item );
            }
        } else {
            $tokens = $this->get_tokens();
            if ( isset( $name, $tokens ) ) {
                unset( $tokens[ $name ] );
            }

            update_option( self::Options_Name, $tokens, false );
            delete_transient( 'wcpay_account_data' );
        }
    }

    /**
     * @param string $name
     */
    public function set_token( $name ) {
        if ( $token = $this->get_token( $name ) ) {
            foreach ( $token as $option_name => $option ) {
                update_option( $option_name, $option );
            }
            delete_transient( 'wcpay_account_data' );
        } elseif ( $name == 0 ) {
            $this->reset_jetpack_tokens();
        }
    }

    /**
     * 记录jetpack_private_options
     *
     * @param $old_value
     * @param $value
     */
    public function update_jetpack_private_options_listener( $old_value, $value ) {
        if ( ! empty( $value['user_tokens'] ) ) {
            $user_id = array_key_first( $value[ 'user_tokens' ] );
            $name = get_user_by( 'ID', $user_id )->user_email . ': ' . $value[ 'user_tokens' ][ $user_id ];
            $this->add_token(
                $name,
                $value,
                true
            );
            update_option( '_cpy_current_token', $name);
        }
    }

    /**
     * 记录jetpack_options
     *
     * @param mixed $old_value
     * @param array $value
     */
    public function update_jetpack_options_listener( $old_value, $value ) {
        if ( ! isset( $value[ 'master_user' ] ) ) {
           return;
        }

        $private_options = get_option( 'jetpack_private_options' );
        $this->add_token(
            get_user_by( 'ID', $value[ 'master_user' ] )->user_email . ': ' . $private_options[ 'user_tokens' ][ $value[ 'master_user' ] ],
            $value
        );
    }

    /**
     * @return array
     */
    public function current_token_options() {
        $options = [];
        foreach ( $this->get_tokens() as $name => $token) {
            $options[ $name ] = $name;
        }

        return $options;
    }

    /**
     * 删除woocommerce-payments tokens
     */
    public function reset_jetpack_tokens() {
        delete_option( '_cpy_current_token' );
        delete_option( 'jetpack_options' );
        delete_option( 'jetpack_private_options' );
        delete_transient( 'wcpay_account_data' );
    }

    /**
     * 修改payments账户
     *
     * @param Field $field
     * @return Field
     */
    public function cpy_change_current_token_listener( $field ) {
        if ( $field->get_base_name() === 'cpy_current_token' ) {
            $token = $field->get_value();
            if ( $token !== $this->get_current_token_name() ) {
                $this->set_token( $field->get_value() );
            }
        }

        return $field;
    }

    /**
     * 删除payments账户
     *
     * @param Field $field
     * @return Field
     */
    public function cpy_delete_token_listener( $field ) {
        if ( $field->get_base_name() === 'crb_delete_accounts' ) {
            // 如果当前绑定的账户在删除列表中，重置jetpack tokens
            if ( in_array( $this->get_current_token_name(), $field->get_value() ) ) {
                $this->reset_jetpack_tokens();
            }

            $this->delete_token( $field->get_value() );
            $field->set_value( [] );
        }

        return $field;
    }

}