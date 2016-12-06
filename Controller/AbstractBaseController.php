<?php

namespace Miguelv\EasyPaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AbstractBaseController
 */
Abstract class AbstractBaseController
{
    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var \Twig_Environment */
    protected $templating;

    /** Session */
    protected $session;

    /**
     * @var Translator
     */
    protected $translator;

    /** @var string */
    protected $failPath, $successPath, $router;

    /**
     * @return string
     */
    protected function paymentSuccessAction()
    {
        return $this->renderView('EasyPaymentBundle::success.html.twig');
    }

    /**
     * @return string
     */
    protected function paymentFailAction()
    {
        return $this->renderView('EasyPaymentBundle::fail.html.twig');
    }
    
    /**
     * @param $key
     * @return string
     */
    protected function translate($key)
    {
        return $this->getTranslator()->trans($key, [], 'easy_payment');
    }

    /**
     * @param $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->getFormFactory()->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return FormBuilder
     */
    public function createFormBuilder($data = null, array $options = array())
    {
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            $type = 'Symfony\Component\Form\Extension\Core\Type\FormType';
        } else {
            $type = 'form';
        }

        return $this->getFormFactory()->createBuilder($type, $data, $options);
    }

    /**
     * @param $view
     * @param array $parameters
     * @return string
     */
    public function renderView($view, array $parameters = array())
    {
        return $this->templating->render($view, $parameters);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url    The URL to redirect to
     * @param int    $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route      The name of the route
     * @param array  $parameters An array of parameters
     * @param int    $status     The status code to use for the Response
     *
     * @return RedirectResponse
     */
    protected function redirectToRoute($route, array $parameters = array(), $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->getRouter()->generate($route, $parameters, $referenceType);
    }

    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * @param $key
     * @param array $params
     * @param string $domain
     * @return string
     */
    public function trans($key, array $params = [], $domain = 'easy_payment')
    {
        return $this->getTranslator()->trans($key, $params, $domain);
    }

    /**
     * @return \Twig_Environment
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @param \Twig_Environment $templating
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    /**
     * @return string
     */
    public function getFailPath()
    {
        return $this->failPath;
    }

    /**
     * @param string $failPath
     */
    public function setFailPath($failPath)
    {
        $this->failPath = $failPath;
    }

    /**
     * @return string
     */
    public function getSuccessPath()
    {
        return $this->successPath;
    }

    /**
     * @param string $successPath
     */
    public function setSuccessPath($successPath)
    {
        $this->successPath = $successPath;
    }

    /**
     * @return string
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param string $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return FormFactoryInterface
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type    The type
     * @param string $message The message
     *
     * @throws \LogicException
     */
    protected function addFlash($type, $message)
    {
        $this->getSession()->getFlashBag()->add($type, $message);
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
}