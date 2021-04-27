<?php
/**
 * Plugin Name: Change Payments Account
 * Description: Change woocommerce-payments account
 * Version: 0.0.1
 * Requires PHP: 7.4
 * Author: linqiuyu191
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: change-payments-account
 * Domain Path: languages
 */

namespace CPY;

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

function app() {
    static $app;
    if ( is_null( $app ) ) {
        $app = new Application();
        $app->bootstrap();
    }

    return $app;
}

app();