<?php

namespace Miguelv\EasyPaymentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StripePaymentType
 */
class StripePaymentType extends AbstractType
{
    /** @var string */
    private $validationGroups, $publishableKey;

    public function __construct($publishableKey, array $validationGroups)
    {
        $this->publishableKey = $publishableKey;
        $this->validationGroups = $validationGroups;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', 'hidden')
            ->add('key', 'hidden', [
                'data' => $options['stripe_key'],
            ])
            ->add('amount', 'hidden')
            ->add('api_token', 'hidden');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => $this->validationGroups,
            'stripe_key' => $this->publishableKey,
        ));
    }

    public function getName()
    {
        return 'easy_payment_stripe_type';
    }
}
