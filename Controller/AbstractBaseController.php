<?php

namespace Miguelv\EasyPaymentBundle\Controller;

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

    /** @var string */
    protected $failPath, $successPath, $currency, $router;

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
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }    
}