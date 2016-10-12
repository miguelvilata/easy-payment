<?php

namespace Miguelv\EasyPaymentBundle\Test;

/**
 * Class EasyPaymentExtensionTest
 */
class EasyPaymentExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EasyPaymentExtension
     */
    private $extension;

    /**
     * Root name of the configuration
     *
     * @var string
     */
    private $root;

    public function setUp()
    {
        parent::setUp();

        $this->extension = $this->getExtension();
        $this->root      = "easy_payment";
    }

    public function testGetConfigWithDefaultValues()
    {
        $this->extension->load(array(), $container = $this->getContainer());

        $this->assertTrue($container->hasParameter($this->root . ".scalar"));
        $this->assertEquals("defaultValue", $container->getParameter($this->root . ".scalar"));

        $expected = array(
            "val1" => "defaultValue1",
            "val2" => "defaultValue2",
        );
        $this->assertTrue($container->hasParameter($this->root . ".array_node"));
        $this->assertEquals($expected, $container->getParameter($this->root . ".array_node"));
    }

    public function testGetConfigWithOverrideValues()
    {
        $configs = array(
            "scalar"     => "scalarValue",
            "array_node" => array(
                "val1" => "array_value_1",
                "val2" => "array_value_2",
            ),
        );

        $this->extension->load(array($configs), $container = $this->getContainer());

        $this->assertTrue($container->hasParameter($this->root . ".scalar"));
        $this->assertEquals("scalarValue", $container->getParameter($this->root . ".scalar"));

        $expected = array(
            "val1" => "array_value_1",
            "val2" => "array_value_2",
        );
        $this->assertTrue($container->hasParameter($this->root . ".array_node"));
        $this->assertEquals($expected, $container->getParameter($this->root . ".array_node"));
    }

    /**
     * @return MyBundleExtension
     */
    protected function getExtension()
    {
        return new \Miguelv\EasyPaymentBundle\DependencyInjection\EasyPaymentExtension();
    }

    /**
     * @return ContainerBuilder
     */
    private function getContainer()
    {
        $container = new \Symfony\Component\DependencyInjection\ContainerBuilder();

        return $container;
    }
}