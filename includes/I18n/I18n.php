<?php

namespace CPY\I18n;

class I18n {

    public function load_textdomain() {
        load_plugin_textdomain( 'change-payments-account', false, plugin_basename( CPY_PLUGIN_DIR ) . '/languages' );
    }

}