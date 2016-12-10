<?php

namespace Miguelv\EasyPaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * @ORM\Table(name="paypal_payment_token")
 * @ORM\Entity
 */
class PaypalPaymentToken extends Token
{
}