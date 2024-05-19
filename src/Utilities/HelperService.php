<?php

namespace App\Utilities;

use App\Document\AccessLogs;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\{
    XmlEncoder,
    JsonEncoder
};
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * HelperService - It's service file to handle additional database related functions.
 *
 * @package Assignment
 */
class HelperService
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     *
     * @var LoggerService 
     */
    private $logger;

    /**
     * @access private
     * @var array 
     */
    private $oktaConfigParams = [];

    /**
     * Class constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, UtilityContainer $utilityContainer, DocumentManager $documentManager)
    {
        $this->container = $container;
        $this->dm = $documentManager;
        $this->format = $container->getParameter('RESPONSE_FORMAT');
        $this->logger = $this->container->get('logger_service');
    }
}
