<?php

namespace Miguelv\EasyPaymentBundle\Controller;

use Miguelv\EasyPaymentBundle\Entity\PaypalPaymentDetails;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Range;
use Payum\Core\Payum;

/**
 * Class PaypalPaymentController
 */
class PaypalPaymentController extends AbstractBaseController
{
    /**
     * @var Payum
     */
    protected $payum;

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
//        http://stackoverflow.com/questions/28148887/setting-up-payum-bundle-with-symfony2-giving-error
        

        $gatewayName = 'paypal_express_checkout';
        $form = $this->getFormFactory()->createNamedBuilder(null, $this->getFormType())->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $storage = $this->getPayum()->getStorage(PaypalPaymentDetails::class);
            /** @var $payment PaymentDetails */
            $payment = $storage->create();
            $payment['PAYMENTREQUEST_0_CURRENCYCODE'] = $data['currency'];
            $payment['PAYMENTREQUEST_0_AMT'] = $data['amount'];
            $storage->update($payment);
            $captureToken = $this->getPayum()->getTokenFactory()->createCaptureToken(
                $gatewayName,
                $payment,
                $this->getSuccessPath()
            );
            $payment['INVNUM'] = $payment->getId();
            $storage->update($payment);

            return $this->redirect($captureToken->getTargetUrl());
        }

        return $this->getTemplating()->renderResponse('PaymentBundle::prepare.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $amount
     * @param $name
     * @param string $description
     * @param string $currency
     * @param array $metadata
     * @return mixed
     */
    public function renderFormAction($amount, $name, $description = '', $currency = 'USD', $metadata = [])
    {
        $form = $this->getFormFactory()->createNamedBuilder(null, $this->getFormType(), [
            'amount' => $amount,
        ])->getForm();

        return $this->getTemplating()->renderResponse('EasyPaymentBundle:paypal:_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }    
    
    /**
     * @return string
     */
    public function paymentSuccessAction()
    {
        $this->addFlash('notice', 'Payment done');

        return parent::paymentSuccessAction();
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    protected function createPurchaseForm($amount, $name, $description = '', $currency = 'USD', $metadata = [])
    {
        return $this->createFormBuilder()
            ->add('amount', null, array(
                'data' => $amount,
                'constraints' => array(new Range(array('max' => 2))),
            ))
            ->add('currency', null, array('data' => $currency))
            ->getForm();
    }

    /**
     * @return Payum
     */
    public function getPayum()
    {
        return $this->payum;
    }

    /**
     * @param Payum $payum
     */
    public function setPayum(Payum $payum)
    {
        $this->payum = $payum;
    }

    /**
     * @return mixed
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @param mixed $formType
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    }
}