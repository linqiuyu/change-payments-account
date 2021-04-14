<?php

namespace CPY\Tokens;

use function CPY\app;

class TokensChangeSchedule {

    public function init() {
        // 添加tokens轮询
        if ( carbon_get_theme_option( 'cpy_schedule_enabled' ) ) {
            if ( ! wp_next_scheduled( 'cpy_tokens_change_schedule' ) ) {
                $schedules = wp_get_schedules();
                if ( isset( $schedules[ carbon_get_theme_option( 'cpy_schedule_recurrence' ) ] ) ) {
                    $time = time() + $schedules[ carbon_get_theme_option( 'cpy_schedule_recurrence' ) ][ 'interval' ];

                    wp_schedule_event(
                        $time,
                        carbon_get_theme_option( 'cpy_schedule_recurrence' ),
                        'cpy_tokens_change_schedule'
                    );
                }
            }
            add_action( 'cpy_tokens_change_schedule', [ $this, 'tokens_change_schedule' ] );
        }
    }

    public function tokens_change_schedule() {
        $names = array_keys( app()[ 'tokens' ]->get_tokens() );
        if ( empty( $names ) ) {
            return;
        }

        $current_name = app()[ 'tokens' ]->get_current_token_name();

        $next_token = false;
        foreach ( $names as $name ) {
            if ( $next_token ) {
                app()[ 'tokens' ]->set_token( $name );
                return;
            }

            if ( $name === $current_name ) {
                $next_token = true;
            }
        }

        app()['tokens']->set_token( $names[ 0 ] );
    }

}