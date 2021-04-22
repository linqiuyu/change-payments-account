<?php

namespace CPY\Options;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OptionsServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {
        $app[ 'options' ] = function () {
            return new Options();
        };
    }

}