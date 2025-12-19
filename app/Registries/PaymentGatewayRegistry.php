<?php

namespace App\Registries;

use App\Contracts\PaymentGateway;
use Exception;

class PaymentGatewayRegistry
{
    protected $gateways = [];

    function register ($name, PaymentGateway $instance) {
        $this->gateways[$name] = $instance;
        return $this;
    }

    function get($name) {
        if (array_key_exists($name, $this->gateways)) {
            return $this->gateways[$name];
        }

        throw new Exception("Invalid gateway");
    }
}
