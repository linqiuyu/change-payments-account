<?php
/**
 * Plugin Name: Change Payment Account
 * Plugin URI: https://github.com/linqiuyu/page-fetcher
 * Description: 更换woocommerce-payments支付账号
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Author: linqiuyu
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: change-payment-account
 * Domain Path: languages
 */

namespace CPY;

if ( ! defined( 'CPY_PLUGIN_FILE' ) ) {
    define( 'CPY_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'PF_PLUGIN_DIR' ) ) {
    define( 'CPY_PLUGIN_DIR', plugin_dir_path( CPY_PLUGIN_FILE ) );
}

require_once CPY_PLUGIN_DIR . 'vendor/autoload.php';

function app() {
    static $app;
    if ( is_null( $app ) ) {
        $app = new Application();
        $app->bootstrap();
    }

    return $app;
}

app();