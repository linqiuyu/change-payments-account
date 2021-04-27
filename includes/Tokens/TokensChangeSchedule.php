<?php

namespace CPY\Tokens;

class TokensChangeSchedule {

    /**
     * @var TokensManager
     */
    private $tokens;

    /**
     * TokensChangeSchedule constructor.
     * @param TokensManager $tokens
     */
    public function __construct( $tokens ) {
        $this->tokens = $tokens;
    }

    public function init() {
        // 添加tokens轮询
        if ( carbon_get_theme_option( 'cpy_schedule_enabled' ) ) {

            $this->add_schedule();

            add_action( 'cpy_tokens_change_schedule', [ $this, 'tokens_change_schedule' ] );

            add_filter( 'cpy_new_error_token', [ $this, 'trigger_schedule' ] );
        }
    }

    /**
     * 添加任务
     */
    public function add_schedule() {
        if ( ! wp_next_scheduled( 'cpy_tokens_change_schedule' ) ) {
            $schedules = wp_get_schedules();
            if (isset($schedules[carbon_get_theme_option('cpy_schedule_recurrence')])) {
                $time = time() + $schedules[carbon_get_theme_option('cpy_schedule_recurrence')]['interval'];

                wp_schedule_single_event(
                    $time,
                    'cpy_tokens_change_schedule'
                );
            }
        }
    }

    /**
     * 触发账户修改
     *
     * @param $name
     * @return mixed
     */
    public function trigger_schedule( $name ) {
        $this->tokens_change_schedule();
        return $name;
    }

    /**
     * 修改账户任务逻辑
     */
    public function tokens_change_schedule() {
        $names = array_keys( $this->tokens->get_tokens() );
        if ( empty( $names ) ) {
            return;
        }

        $current_name = $this->tokens->get_current_token_name();

        $next_token = false;
        foreach ( $names as $name ) {
            if ( $next_token ) {
                carbon_set_theme_option( 'cpy_current_token', $name );
                return;
            }

            if ( $name === $current_name ) {
                $next_token = true;
            }
        }

        $this->tokens->set_token( $names[ 0 ] );

        $this->add_schedule();
    }

}