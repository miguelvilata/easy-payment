<?php

namespace Miguelv\EasyPaymentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class PaypalPaymentType
 */
class PaypalPaymentType extends AbstractType
{
    /** @var string */
    private $validationGroups, $defaultCurrency;

    /** @var */
    private $defaultData;

    public function __construct(array $validationGroups, $defaultCurrency, array $defaultData = [])
    {
        $this->validationGroups = $validationGroups;
        $this->defaultCurrency = $defaultCurrency;
        $this->defaultData = $defaultData;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class)
            ->add('description', HiddenType::class)
            ->add('amount', HiddenType::class)
            ->add('currency', HiddenType::class, [
                'data' => $options['currency'],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => $this->validationGroups,
            'currency' => $this->defaultCurrency,
            'allow_extra_fields' => true,
        ));
    }

    public function getName()
    {
        return 'easy_payment_paypal_type';
    }
}
