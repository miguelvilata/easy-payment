<?php

namespace Miguelv\EasyPaymentBundle\Controller;

use Miguelv\EasyPaymentBundle\Model\Interfaces\PaymentManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Miguelv\EasyPaymentBundle\Controller;
use Miguelv\EasyPaymentBundle\Form\StripePaymentType;

/**
 * Class StripePaymentController
 */
class StripePaymentController extends AbstractBaseController
{
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
     * @return Response
     */
    public function paymentAction(Request $request)
    {
        $form = $this->createForm($this->getFormType());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $token = $request->request->get('stripeToken', null);
            $amount = $form->get('amount')->getData();
            $customerEmail = $request->request->get('stripeEmail', null);

            if ($form->isValid() && isset($token) && isset($customerEmail)) {
                $charge = $this->getManager()->charge([
                    "amount" => $amount,
                    "currency" => $this->getCurrency(),
                    "source" => $token,
                    "description" => sprintf("Example charge %s", uniqid()),
                ]);

                //Redirect if success
                return $this->redirectToRoute($this->getSuccessPath());
            }
        }

        //Redirects fails
        return $this->redirectToRoute($this->getFailPath());
    }

    /**
     * @param $description
     * @param $amount
     * @return mixed
     */
    public function renderFormAction($description, $amount)
    {
        $form = $this->createForm($this->getFormType(), [
            'description' => $description,
            'amount' => $amount,
        ]);

        return $this->getTemplating()->renderResponse('EasyPaymentBundle:stripe:_stripe_form.html.twig', [
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
     * @return string
     */
    public function paymentSuccessAction()
    {
        return $this->renderView('EasyPaymentBundle::success.html.twig');
    }

    /**
     * @return string
     */
    public function paymentFailAction()
    {
        return $this->renderView('EasyPaymentBundle::fail.html.twig');
    }

    /**
     * @return PaymentManagerInterface
     */
    public function getFormType()
    {
        return $this->formType;
    }
}
    