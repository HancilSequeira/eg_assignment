<?php

namespace App\Modules\Aggregate\Interceptor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Utilities\{
    ResponseHandler,
    ApiInterceptorInterface
};
use Symfony\Component\Serializer\Encoder\{
    XmlEncoder,
    JsonEncoder
};
use App\Modules\Aggregate\DTO\{
    HeaderRequest
};
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of headerInterceptor
 *
 */
class HeaderInterceptor extends ResponseHandler implements ApiInterceptorInterface
{

    /**
     *
     * @var AggregateService
     */
    private $AggregateService;

    /**
     * @var Logger service 
     */
    private $logger;

    /**
     * @var \Serializer \Symfony\Component\Serializer\Serializer
     */
    private $serializer;

    private $accessLogId;

    /**
     * Class constructor
     * 
     * @param  ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $this->container->get('logger_service');
        $this->helper = $this->container->get('helper_service');
        $this->format = $container->getParameter('RESPONSE_FORMAT');
        $encoders = array(new XmlEncoder(), new JsonEncoder());

        $normalizers = array(new ObjectNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * prepare request DTO
     * 
     * @param Request $request
     * @return headerRequest $response
     * 
     */
    public function prepareRequestDto(Request $request, $action = '')
    {
        try {
            $requestHeaders = $request->headers->all();
            $this->accessLogId = $requestHeaders['accesslogid'][0];

            $requestParameters = $request->query->all();
            $logContactId = $request->headers->get('logContactId');

            $authorizationHeader = $request->headers->get('Authorization');
            if(isset($authorizationHeader)){
                $token=substr($authorizationHeader, 7);
                $requestParameters['accessToken'] = $token;
            }
            if(isset($logContactId)){
                $requestParameters['logContactId'] = $logContactId;
            }
            $logUserId = $request->headers->get('logUserId');
            if(isset($logUserId)){
                $requestParameters['logUserId'] = $logUserId;
            }
            // JSON_NUMERIC_CHECK used for type cast. 
            $requestParams = json_encode($requestParameters, JSON_NUMERIC_CHECK);
            $requestObj = json_decode($requestParams);
  
            //logging request data
            $this->logger->writeLog('INFO', 'Request data received' . json_encode($requestObj));

            $headerResponse = $this->serializer->deserialize(json_encode($requestObj), headerRequest::class, 'json');

            return $headerResponse;
        } catch (\Exception $ex) {
            $this->logger->writeLog('ERROR', 'Exception while preparing dto; Error=>' . $ex->getMessage() . 'at line - ' . $ex->getLine() . ' in file' . $ex->getFile());
            return new headerRequest();
        }
    }
    /**
     * For generate response
     * 
     * @param object $responseDto
     * @param int $statusCode
     * @return type
     */
    public function sendResponse($responseDto, int $statusCode): JsonResponse
    {
        try {
            $responseObj = $this->serializer->serialize($responseDto, 'json');

            $responseArray = json_decode($responseObj, true);
            if($statusCode=='200'){
                $responseFormatArray = array();
                $responseFormatArray['code'] = $statusCode;
                $responseFormatArray['message'] = 'success';
                $responseFormatArray['data'] = $responseArray;
                $responseArray = $responseFormatArray;
            }
            $jsonResponse = $this->generateResponse($statusCode, $responseArray);

            return $jsonResponse;
        } catch (\Exception $ex) {
            $this->logger->writeLog('ERROR', 'EXCEPTION: Error while send response ' . $ex->getMessage() . 'at line - ' . $ex->getLine() . ' in file' . $ex->getFile());
            return new JsonResponse();
        }
    }

    /**
     * 
     * @param array $responseArray
     * @param int $statusCode
     * @return JsonResponse
     */
    public function sendGetAllResponse(array $responseArray, int $statusCode): JsonResponse
    {
        try {
            $filterArray = $responseArray;
            if($statusCode=='200'){
                $responseFormatArray = array();
                $responseFormatArray['data'] = $filterArray;
                $responseArray = $filterArray;
            
            }
            $jsonResponse = $this->generateResponse($statusCode, $responseArray);
            $this->helper->updateAPIAccessLogs($this->accessLogId, json_decode($jsonResponse->getContent(), true));
            return $jsonResponse;
        } catch (\Exception $ex) {
            $this->logger->writeLog('ERROR', 'EXCEPTION: Error while send response ' . $ex->getMessage() . 'at line - ' . $ex->getLine() . ' in file' . $ex->getFile());
            return new JsonResponse();
        }
    }

    /**
     * Method to sanitize the request object
     *
     * @param headerRequest $requestObject
     * @return headerRequest
     */
    public function sanitizeRequestObject($requestObject): GetAllSetting
    {
        try {
            if ($requestObject->getAccessToken() !== null) {
                $requestObject->setAccessToken($this->helper->stringToArrayForFilter($requestObject->getAccessToken()));
            }

            return $requestObject;
        } catch (\Exception $ex) {
            $this->logger->writeLog('ERROR', 'EXCEPTION: Error while sanitize request object ' . $ex->getMessage() . 'at line - ' . $ex->getLine() . ' in file' . $ex->getFile());
            throw new \Exception($ex->getMessage());
        }
    }

}
