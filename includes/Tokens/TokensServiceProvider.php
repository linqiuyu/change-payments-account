<?php

namespace CPY\Tokens;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TokensServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {
        $app[ 'tokens' ] = function () {
            return new TokensManager();
        };
    }

}