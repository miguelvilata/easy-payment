<?php

namespace Miguelv\EasyPaymentBundle\Gateways\Stripe;

use Miguelv\EasyPaymentBundle\Model\Interfaces\PaymentManagerInterface;

/**
 * Class StripeManager
 */
class StripeManager implements PaymentManagerInterface
{
    protected $stripeGateway;

    /**
     * StripeManager constructor.
     * @param $stripeApiKey
     */
    public function __construct($stripeApiKey)
    {
        $this->stripeGateway = \Stripe\Stripe::setApiKey($stripeApiKey);
    }

    /**
     * @param array $data
     * @return \Stripe\Charge
     */
    public function charge(array $data)
    {
        try {
            return \Stripe\Charge::create($data);
        } catch(\Stripe\Error\Card $e) {
            // The card has been declined
        }
    }
}