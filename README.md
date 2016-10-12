EasyPaymentBundle Installation
========================

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

@todo:

```bash
$ composer require <miguelv/easy-payment> "~1"
```

Step 2: Enable the Bundle
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

            new Application\EasyPaymentBundle\EasyPaymentBundle(),
        );

        // ...
    }

    // ...
}
```