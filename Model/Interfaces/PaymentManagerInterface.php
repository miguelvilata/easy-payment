<?php

namespace Miguelv\EasyPaymentBundle\Model\Interfaces;

/**
 * Interface PaymentManagerInterface
 */
interface PaymentManagerInterface
{
    public function charge(array $data);
}