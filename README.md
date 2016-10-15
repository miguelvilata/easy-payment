EasyPaymentBundle Installation
========================

This bundle is under hard development, it's still not ready to use.

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

@todo:

```bash
$ composer require miguelv/easy-payment-bundlef:dev-master
```

Step 2: Configure the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Miguelv\EasyPaymentBundle\EasyPaymentBundle(),
        );

        // ...
    }

    // ...
}
```


Add to your `app/config/config.yml` the following:

```yaml
easy_payment:
    stripe:
        apiKey: sk_test_bz5XxyAyVMZJd2wOiX2XWc75
        publishable_key: "%omnipay.stripe.publishable_key%"
        manager: 'Miguelv\EasyPaymentBundle\Gateways\Stripe\StripeManager'
        form:
            type: 'easy_payment_stripe_type'
            validation_groups:

    default:
        success_path: 'home'
        fail_path: 'pricing'
        currency: 'eur'
```

Add routing in app/config/routing.yml

```yaml
//...
easy_payment:
    resource: "@EasyPaymentBundle/Resources/config/routing/routing.xml"
//...
```


Usage
---------------------------

The easy way is rendering the form in the template.

```yaml
//...
{% render(controller('easy_payment.gateway.stripe_payment.controller:renderFormAction', {
      description: 'Foo Product (12$)',
      amount: 1200
    }))
%}
//...


You can pass more information in the call:

//...
{% render(controller('easy_payment.gateway.stripe_payment.controller:renderFormAction', {
      description: 'Foo Product (12$)',
      amount: 1200,
      currency: 'usd'
    }))
%}
//...
