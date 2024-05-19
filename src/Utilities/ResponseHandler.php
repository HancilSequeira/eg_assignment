<?php

/**
 * ResponseHandler - Handles All common rest functions to build response
 * */

namespace App\Utilities;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use App\Modules\Aggregate\DTO\{
    ErrorResponse
};

/**
 * Wrapper class to prepare the response body and send HTTP response
 *
 * This class uses the Symfony\Component\HttpFoundation\JsonResponse class to generate HTTP response
 *
 */
class ResponseHandler
{

    private $httpMessage;
    protected $container;
    /**
     * @var \App\Utilities\ErrorDTO
     */
    private $errorDtoObj;
    /**
     * Response handler constructor function
     * 
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->helperService = $this->container->get('helper_service');
        $this->httpMessage = $this->container->getParameter('HTTP_STATUS_MESSAGE');
        $this->httpCode = $this->container->getParameter('HTTP_STATUS_CODE');
        $this->logger = $this->container->get('logger_service');
        $this->apiCodes = $this->container->getParameter('API_CODES');
        /**
         * Set error DTo object
         * 
         */
        $this->errorDtoObj = $this->container->get('error_dto');
    }

    /**
     * Function with take the HTTP status code in input and return the status message
     * @param int $code status code
     * @return string
     */
    private function getStatusMessage(int $code)
    {
        $this->httpMessage = $this->container->getParameter('HTTP_STATUS_MESSAGE');
        $status = $this->httpMessage[$code];
        return ($code != '' ) ? $status : $this->httpMessage[500];
    }

    /**
     * CONSTRUCT API RESPONSE
     *
     * @param int $statusCode API Response status code
     * @param string $arg_status_msg API Response status message corresponding to status code
     * @param string $uiMessage API Response UI message corresponding to status code, which is end-user friendly
     * @param string|array $responseData API Response data may in the form or string or an array
     *
     * @return array $apiResponse Complete response of an API call
     */
    private function prepareResponse(int $statusCode = null, $responseData): array
    {
        $responseArray = $responseData;
        if ($statusCode != 200) {
            $responseArray = $this->array_insert_after('code', $responseArray, 'message', $this->getStatusMessage($statusCode));
        }
        return $responseArray;
    }

    /**
     * Generate API response
     *
     * @param int $statusCode API Response status code
     * @param string $uiMessage API Response UI message corresponding to status code, which is end-user friendly
     * @param string|array $responseData API Response data may in the form or string or an array
     * @param string|array $optionalData optional data
     *
     * @return array $apiResponse Complete response of an API call
     */
    public function generateResponse(int $statusCode, $responseData)
    {

        $responseArray = $this->prepareResponse(
                $statusCode, $responseData
        );
        return new JsonResponse($responseArray, $statusCode);
    }

    /**
     * Insert a new element at a specific position to the array
     * 
     * @param string $key key after which the new element has to be pushed in the array
     * @param array $array array in which the new element has to be pushed
     * @param string $newKey new key which has be pushed to the array
     * @param type $newValue new value which has to be pushed to the array
     * @return mixed array $updatedArray in case of success, false in case of failed
     */
    function array_insert_after($key, array &$array, $newKey, $newValue)
    {
        if (array_key_exists($key, $array)) {
            $updatedArray = array();
            foreach ($array as $k => $value) {
                $updatedArray[$k] = $value;
                if ($k === $key) {
                    $updatedArray[$newKey] = $newValue;
                }
            }
            return $updatedArray;
        }
        return false;
    }

    /**
     * Return Serializer object
     *
     * @access protected
     * @return Serializer
     */
    protected function getSerializer()
    {
        return $this->helperService->getSerializer();
    }

    /**
     * Get success response
     *
     * @access public
     * @param  array                          $apiResponse
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getSuccessResponse($apiResponse, $apiInterceptor)
    {
        try {
            return $apiInterceptor->sendResponse($apiResponse, $this->httpCode['OK']);
        } catch (\Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating success response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get success response
     *
     * @access public
     * @param  array                          $apiResponse
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getAllSuccessResponse($apiResponse, $apiInterceptor)
    {
        try {
            return $apiInterceptor->sendGetAllResponse($apiResponse, $this->httpCode['OK']);
        } catch (Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating success response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get invalid response
     *
     * @access public
     * @param  array                          $invalidRequest
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getInvalidResponse(array $invalidRequest, $apiInterceptor, $additionalData = array())
    {
        try {
            $errorResponseObj = new ErrorResponse();
            $errorResponseObj->setCode($this->httpCode['BAD']);
            $errorResponseObj->setMessage('Input request data is not valid');
            $errorResponseObj->setDescription('Input request data in not valid');
            $errorResponseObj->setErrors($invalidRequest);
            if (!empty($additionalData)) {
                $errorResponseObj->setAdditionalDetails($additionalData);
            }
            return $apiInterceptor->sendResponse($errorResponseObj, $this->httpCode['BAD']);
        } catch (\Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating invalid response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get failed response
     *
     * @access public
     * @param  array                          $errorList
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getFailedResponse(array $errorList, $apiInterceptor)
    {
        try {
            $errorResponseObj = new ErrorResponse();
            $errorResponseObj->setCode($this->httpCode['SERVER_ERROR']);
            $errorResponseObj->setDescription('Something went wrong. Could not complete selected action.');
            $errorResponseObj->setErrors($errorList);
            return $apiInterceptor->sendResponse($errorResponseObj, $this->httpCode['SERVER_ERROR']);
        } catch (\Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating failed response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get result not found response
     *
     * @access public
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getNotFoundResponse($apiInterceptor)
    {
        try {
            $errorResponseObj = new ErrorResponse();
            $errorResponseObj->setCode($this->httpCode['NOT_FOUND']);
            $errorResponseObj->setDescription('No records found for selected parameters.');
            return $apiInterceptor->sendResponse($errorResponseObj, $this->httpCode['NOT_FOUND']);
        } catch (\Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating not found response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get result not found response
     *
     * @access public
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getErrorResponse($apiInterceptor, $errorResponse)
    {
        try {
            $errorResponseObj = new ErrorResponse();
            $errorResponseObj->setCode($errorResponse['code']);
            $errorResponseObj->setDescription($errorResponse['description']);
            $errorResponseObj->setMessage($errorResponse['message']);
            if(isset($errorResponse['errors'])){
            $errorResponseObj->setErrors($errorResponse['errors']);
            }
            return $apiInterceptor->sendResponse($errorResponseObj, $errorResponse['code']);
        } catch (\Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating not found response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get success response
     *
     * @access public
     * @param  array                          $apiResponse
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getErrorArrayResponse($apiResponse, $apiInterceptor)
    {
        try {
            return $apiInterceptor->sendGetAllResponse($apiResponse, $apiResponse['code']);
        } catch (Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating success response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get result not found response
     *
     * @access public
     * @param  \App\Utilities\ResponseHandler $apiInterceptor
     * @return JsonResponse
     */
    public function getInvalidJsonResponse($apiInterceptor)
    {
        try {
            $errorResponseObj = new ErrorResponse();
            $errorResponseObj->setCode($this->httpCode['BAD']);
            $errorResponseObj->setDescription('Invalid JSON Payload');
            $errorResponseObj->setMessage('Invalid JSON Payload');
            return $apiInterceptor->sendResponse($errorResponseObj, $this->httpCode['BAD']);
        } catch (\Exception $ex) {
            $this->logger->writeLog("ERROR", "Exception while generating json error response=" . $ex->getMessage() . ' in file ' . $ex->getFile() . ' at line - ' . $ex->getLine());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Construct API response
     * @param  int    $statusCode
     * @param  string $uiMessage
     * @param  string $responseData
     * @param  string $optionalData
     * @return array $apiResponse
     */
    public function buildResponseArray(int $statusCode, string $uiMessage, array $responseData, array $optionalData): array
    {
        $responseArray[$this->apiCodes['STATUS_CODE']] = $statusCode;
        $responseArray[$this->apiCodes['STATUS_MSG']] = $this->get_status_message($statusCode);
        $responseArray[$this->apiCodes['UI_MSG']] = $uiMessage;
       
        // SKIP DATA IF THE ARGUEMENT $responseData IS NULL
        if (!empty($responseData)) {
            $responseArray[$this->apiCodes['DATA']] = $responseData;
        }

        // MERGE OPTIONAL DATA INTO THE RESPONSE
        if (is_array($optionalData)) {
            $responseArray = array_merge($responseArray, $optionalData);
        }

        return $responseArray;
    }
    
    /**
     * Render API response
     *
     * @param  int    $statusCode   API Response status code
     * @param  string $uiMessage    API Response UI message corresponding to status code, which is end-user friendly
     * @param  array  $responseData API Response data may in the form or string or an array
     * @param  array  $optionalData optional data
     * @return array  $apiResponse  Complete response of an API call
     */
    public function renderResponse(int $statusCode, string $uiMessageCode, array $responseData = [], array $optionalData = []): JsonResponse
    {
        $res = $this->logger->writeLog('DEBUG', 'calling buildResponseArray with data >>>> statusCode = ' . $statusCode . ",uiMessageCode=" . $uiMessageCode . ",responseData=" . json_encode($responseData) . ",optionalData=" . json_encode($optionalData));

        $responseArray = $this->buildResponseArray(
            $statusCode, $uiMessageCode, $responseData, $optionalData
        );

        $this->logger->writeLog('DEBUG', 'calling generateResponse with data >>>>' . json_encode($responseArray));

        return $this->generateResponse($responseArray[$this->apiCodes['STATUS_CODE']], $responseArray);
    }
    
    /**
     * Sets the status code and status message to the response
     *
     * @param  int $code
     * @return string returns status message based on status code
     */
    private function get_status_message(int $code)
    {
        return (!is_array($code) && $this->httpMessage[$code] ) ? $this->httpMessage[$code] : $this->httpMessage[500];
    }
}
