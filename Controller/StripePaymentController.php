<?php

namespace Miguelv\EasyPaymentBundle\Controller;

use Miguelv\EasyPaymentBundle\Model\Interfaces\PaymentManagerInterface;
use Stripe\Charge;
use Symfony\Component\HttpFoundation\Request;
use Miguelv\EasyPaymentBundle\Controller;

/**
 * Class StripePaymentController
 */
class StripePaymentController extends AbstractBaseController
{
    const CHARGE_SUCCESS = 'succeeded';

    /** @var PaymentManagerInterface */
    protected $stripeManager;

    /** @var string */
    protected $formType;

    /**
     * StripePaymentController constructor.
     * @param $formType
     */
    public function __construct($formType)
    {
        $this->formType = $formType;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function paymentAction(Request $request)
    {
        $form = $this->getFormFactory()->createNamedBuilder(null, $this->getFormType())->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $token = $request->request->get('stripeToken', null);
            $amount = $form->get('amount')->getData();
            $customerEmail = $request->request->get('stripeEmail', null);
            $metaData = $form->get('metadata')->getData();
            $metaData = count($metaData) ? $metaData : $form->get('metadata')->getExtraData();

            if ($form->isValid() && isset($token) && isset($customerEmail)) {
                $paymentData = array_merge([
                    'amount' => $amount,
                    'currency' => $form->get('currency')->getData(),
                    'source' => $token,
                    'metadata' => $metaData,
                ]);

                $charge = $this->getManager()->charge($paymentData);

                if ($charge instanceof Charge) {
                    if (self::CHARGE_SUCCESS === $charge['status']) {
                        $this->addFlash('info', $this->trans('flashbag_messages.payment.success'));
                        return $this->redirectToRoute($this->getSuccessPath());
                    }
                }
            }
        }

        //Redirects fails
        $this->addFlash('danger', $this->trans('flashbag_messages.payment.error'));
        return $this->redirectToRoute($this->getFailPath());
    }

    /**
     * @param $amount
     * @param $name
     * @param string $description
     * @param $currency
     * @param array $metadata
     * @return mixed
     */
    public function renderFormAction($amount, $name, $description = '', $currency, $metadata = [])
    {
        $form = $this->getFormFactory()->createNamedBuilder(null, $this->getFormType(), [
            'name' => $name,
            'description' => $description,
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => $metadata, //Metadata tha Stripe store as a Aditional Field
        ])->getForm();

        return $this->getTemplating()->renderResponse('EasyPaymentBundle:stripe:_form.html.twig', [
            'form' => $form->createView(),
        ]);        
    }    
    
    public function getManager()
    {
        return $this->stripeManager;   
    }

    public function setManager(PaymentManagerInterface $stripeManager)
    {
        $this->stripeManager = $stripeManager;
    }

    /**
     * @return PaymentManagerInterface
     */
    public function getFormType()
    {
        return $this->formType;
    }
}
    