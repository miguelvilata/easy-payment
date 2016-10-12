EasyPaymentBundle
========================

This bundle is under hard development, it's still not ready to use.

The aim of this bundle is provide a fully customizable and extensible payment methods in your website on top of Symfony.


Installation
========================

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

@todo:

```bash
$ composer require miguelv/easy-payment-bundle:dev-master
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
Add gateway configuration, at the moment only stripe gateway is provided. Get your
api keys in Stripe site.

```yaml
    easy_payment.stripe.api_key: "%easy_payment.stripe.api_key%"
    easy_payment.stripe.publishable_key: "%easy_payment.stripe.publishable_key%"
```

Minimal configuration to add in your `app/config/config.yml`:

```yaml
easy_payment:
    stripe:
        apiKey: "%easy_payment.stripe.api_key%"
        publishable_key: "%easy_payment.stripe.publishable_key%"

    default:
        success_path: 'home'
        fail_path: 'pricing'
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


You can pass aditional information in the render call:

//...
{% render(controller('easy_payment.gateway.stripe_payment.controller:renderFormAction', {
      description: 'Foo Product (12$)',
      amount: 1200,
      currency: 'usd',
      metadata: {"order_id": "12345"}
    }))
%}
//...

If you want for example private information to be added, better override the service controller. Because in this bundle, the
controllers are declared as services, you can override the controllers, modifiying the class loaded.

```yaml
easy_payment:
    stripe:
        controller: 'MyAppBundle\Controller\StripePaymentController'
//...
```

Then create your own service and extends the original one overriding paymentAction:

```php
//...

use Miguelv\EasyPaymentBundle\Controller\StripePaymentController

//...

class MyStripePaymentControllerService extends StripePaymentController
{
    public function paymentAction(Request $request)
    {
        //your code here

    //...
}

```

Full configuration
---------------------------

```yaml
easy_payment:
    stripe:
        controller: 'Miguelv\EasyPaymentBundle\Controller\StripePaymentController'
        apiKey: '%easy_payment.stripe.api_key%'
        publishable_key: "%easy_payment.stripe.publishable_key%"
        manager: 'Miguelv\EasyPaymentBundle\Gateways\Stripe\StripeManager'
        form:
            type: 'easy_payment_stripe_type'
            validation_groups: ['Default']
        data: # You can put here any default values admited by Stripe: see https://stripe.com/docs/checkout
          image: 'http://www.my-company.com/logo.png'
          name: 'My Company'
          locale: auto
          zip-code: true
          billing-address: false
          amount: 1000 #Yo can also put a default amount, then you can skip this data when rendering the form

    default:
        success_path: 'easy_payment_home' # The bundle add success message into symfony message flash bag
        fail_path: 'easy_payment_fail' # The bundle add fail message into symfony message flash bag
        currency: 'usd' # three letter defining currency ('eur' for euro).
```