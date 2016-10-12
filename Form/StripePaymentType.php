<?php

namespace Miguelv\EasyPaymentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class StripePaymentType
 */
class StripePaymentType extends AbstractType
{
    /** @var string */
    private $validationGroups, $publishableKey, $defaultCurrency;

    /** @var */
    private $defaultData;

    public function __construct($publishableKey, array $validationGroups, $defaultCurrency, array $defaultData = [])
    {
        $this->publishableKey = $publishableKey;
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
            ->add('amount', 'hidden')
            ->add('currency', HiddenType::class, [
                'data' => $options['currency'],
            ])
            ->add('key', HiddenType::class, [
                'data' => $options['stripe_key'],
            ])
            ->add('stripe_data', CollectionType::class, [
                'allow_extra_fields' => true,
                'label' => false,
                'entry_type' => HiddenType::class,
                'data' => $this->getDefaultData(isset($options['data']) ? $options['data']: []),
            ])

            ->add('metadata', CollectionType::class, [
                'allow_extra_fields' => true,
                'label' => false,
                'entry_type' => HiddenType::class,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => $this->validationGroups,
            'stripe_key' => $this->publishableKey,
            'currency' => $this->defaultCurrency,
            'allow_extra_fields' => true,
        ));
    }

    protected function getDefaultData($data = [])
    {
        unset($data['metadata']);
        $this->defaultData = array_merge($this->defaultData, $data);
        $result = [];

        foreach ($this->defaultData as $key => $value) {
            $result[$key] = str_replace('_', '-', $value);
        }

        return $result;
    }

    public function getName()
    {
        return 'easy_payment_stripe_type';
    }
}
