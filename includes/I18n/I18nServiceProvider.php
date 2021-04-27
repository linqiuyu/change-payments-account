<?php

namespace CPY\I18n;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class I18nServiceProvider implements ServiceProviderInterface {

    public function register( Container $app ) {
        $app[ 'i18n' ] = function () {
            return new I18n();
        };
    }

}