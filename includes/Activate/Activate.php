<?php

namespace CPY\Activate;

use function CPY\app;

class Activate {

    public function activate() {
        app()[ 'tokens' ]->update_jetpack_private_options_listener( [], get_option( 'jetpack_private_options' ), true );
        app()[ 'tokens' ]->update_jetpack_options_listener( [], get_option( 'jetpack_options' ), true );
    }

}