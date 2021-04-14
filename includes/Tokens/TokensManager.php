<?php

namespace CPY\Tokens;

use Carbon_Fields\Field\Field;

class TokensManager {

    const Options_Name = 'cpy_payments_tokens';

    private $error_tokens;

    /**
     * @return ErrorTokens
     */
    public function error_tokens() {
        if ( ! $this->error_tokens ) {
            $this->error_tokens = new ErrorTokens();
        }

        return $this->error_tokens;
    }

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
        return carbon_get_theme_option( 'cpy_current_token' );
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

    public function prepare_token_name( $name ) {
        return str_replace( [ 'https://', 'http://' ], '', $name );
    }

    /**
     * 记录jetpack_private_options
     *
     * @param $old_value
     * @param $value
     */
    public function update_jetpack_private_options_listener( $old_value, $value ) {
        if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
            if ( ! empty( $value['user_tokens'] ) ) {
                $name = $this->prepare_token_name( site_url() );
                $this->add_token(
                    $name,
                    $value,
                    true
                );
                carbon_set_theme_option( 'cpy_current_token', $name );
            }
        }
    }

    /**
     * 记录jetpack_options
     *
     * @param mixed $old_value
     * @param array $value
     */
    public function update_jetpack_options_listener( $old_value, $value ) {
        if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
            if ( ! isset( $value[ 'master_user' ] ) ) {
                return;
            }

            $this->add_token(
                $this->prepare_token_name( site_url() ),
                $value
            );
        }
    }

    /**
     * @return array
     */
    public function current_token_options() {
        $options = [];
        foreach ( $this->get_tokens() as $name => $token) {
            $options[ $name ] = $name;
            if ( $this->error_tokens()->is_error_tokens( $name ) ) {
                $options[ $name ] .= '（token已失效!）';
            }
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

    /**
     * 为使用woocommerce_payments支付的订单添加meta，记录支付的token用于退款
     *
     * @param array $result
     * @param int $order_id
     * @return array
     */
    public function add_order_token_meta( $result, $order_id ) {
        $order = wc_get_order( $order_id );
        if ( $order->get_payment_method() === 'woocommerce_payments' ) {
            update_post_meta( $order_id, 'woocommerce_payments_token_name', $this->get_current_token_name() );
        }

        return $result;
    }

    /**
     * @param mixed $value
     */
    public function error_token_listener( $value ) {
        if ( $value === 'ERROR' ) {
            $this->error_tokens()->add_error_tokens( $this->get_current_token_name() );
        }
    }

}