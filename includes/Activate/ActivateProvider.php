<?php

namespace CPY\Activate;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ActivateProvider implements ServiceProviderInterface {

    public function register(Container $app) {
        $app[ 'activate' ] = function () {
            return new Activate();
        };
    }

}