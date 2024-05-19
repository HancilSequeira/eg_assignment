<?php

namespace App\Utilities;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Wrapper class to hadle container related operations
 *
 * @package Assignment
 */
class UtilityContainer
{

    /**
     * @var \ContainerInterface
     */
    private $container;

    /**
     * Sets container object
     *
     * @access public
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get service defined in Container
     *
     * @access public
     * @param  string $serviceParam
     * @return mixed
     */
    public function getService(string $serviceParam)
    {
        return $this->container->get($serviceParam);
    }

    /**
     * Returns container parameter value
     *
     * @access public
     * @param  string $param
     * @return string
     */
    public function getParameter(string $param)
    {
        return $this->container->getParameter($param);
    }

    /**
     * Get Response Handler
     *
     * @return ResponseHandler
     */
    public function getResponseHandler(): ResponseHandler
    {
        return $this->container->get('response_handler');
    }

    /**
     * Get current date time
     *
     * @access public
     * @return \DateTime
     */
    public function getCurrentDateTime(): \DateTime
    {
        return new \DateTime();
    }

}
