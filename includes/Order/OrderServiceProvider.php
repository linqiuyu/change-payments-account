<?php

namespace CPY\Order;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OrderServiceProvider implements ServiceProviderInterface {

    public function register( Container $app ) {
        $app[ 'order' ] = function () {
            return new Order();
        };
    }

}