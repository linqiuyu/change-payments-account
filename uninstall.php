<?php

defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

wp_clear_scheduled_hook( 'cpy_tokens_change_schedule' );
delete_option( \CPY\Tokens\TokensManager::Options_Name );