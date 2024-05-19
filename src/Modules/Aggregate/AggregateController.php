<?php

namespace App\Modules\Aggregate;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{
    Request,
    Response,
    JsonResponse,
    Cookie
};
use App\Modules\Aggregate\Interceptor\{
    HeaderInterceptor,
    HeaderIgnoreInterceptor
};
use App\Utilities\UtilityContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use App\Modules\Aggregate\DTO\{
    ErrorResponse
};

class AggregateController extends AbstractController
{
    private mixed $responseHandler;
    private AggregateService $aggregateServices;


    /**
     * AggregateController Constructor
     *
     * @param \App\Modules\Aggregate\AggregateService $service
     * @param ContainerInterface $container
     * @param \App\Modules\Aggregate\UtilityContainer $utility
     */
    public function __construct(AggregateService $service, ContainerInterface $container, UtilityContainer $utility)
    {
        $this->aggregateServices = $service;
        $this->container = $container;
        $this->utility = $utility;
        $this->logger = $utility->getService('logger_service');
        $this->responseHandler = $utility->getService('response_handler');
    }

    /**
     * @Route("/eg-assignment/calendar", name="assignment", methods="GET")
     * @author Impelsys
     * @OA\Get(
     * tags={"Assignment"},
     * summary="",
     * @OA\Parameter(
     *      name="calendarIds",
     *      in="query",
     *      example  ="48cadf26-975e-11e5-b9c2-c8e0eb18c1e9",
     *      description="pass id comma separated",
     *     required=true,
     *  ),
     * @OA\Parameter(
     *    name="duration",
     *    in="query",
     *    example  ="30",
     *    description="duration in minutes",
     *     required=false,
     *  ),
     *     @OA\Parameter(
     *          name="periodToSearch",
     *          in="query",
     *          required=false,
     *          example  ="2023-01-20T00:00:00",
     *          description="startDate for the invoice",
     *   ),
     *     @OA\Parameter(
     *       name="timeSlotType",
     *       in="query",
     *       example  ="48cadf26-975e-11e5-b9c2-c8e0eb18c1e9",
     *       description="timeSlot type id",
     *
     *   ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Successful",
     *   )
     * @OA\Response(
     *     response=400,
     *     description="BAD REQUEST",
     *   )
     * @OA\Response(
     *     response=401,
     *     description="UnAuthorised",
     *   )
     * @OA\Response(
     *     response=404,
     *     description="Not Found",
     *   )
     * @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *   )
     * @author Impelsys
     */
    public function assignment(Request $request, HeaderInterceptor $apiInterceptor): JsonResponse
    {
        try {
            $requestData = $request->query->all();
            $isValid = $this->aggregateServices->validateData($requestData);
            if (!empty($isValid)) {
                return $this->responseHandler->getAllSuccessResponse($isValid, $apiInterceptor);
            }

            $calendarIds = explode(",", $requestData['calendarIds']);
            $timeSlotType = !empty($requestData['timeSlotType']) ? $requestData['timeSlotType'] : null;
            $calendarData = $this->aggregateServices->findAvailableTime($calendarIds, $requestData['duration'], $requestData['periodToSearchStart'], $requestData['periodToSearchEnd'], $timeSlotType);
            if (!empty($calendarData)) {
                return $this->responseHandler->getErrorResponse($apiInterceptor, $calendarData);
            }
            return $this->responseHandler->getAllSuccessResponse($calendarData, $apiInterceptor);
        } catch (\Exception $ex) {
            $msg = "Assignment list Exception =>" . $ex->getMessage() . 'at line - ' . $ex->getLine() . ' in file' . $ex->getFile();
            $this->logger->writeLog("ERROR", $msg);
            // Internel server error
            return $this->responseHandler->getFailedResponse([], $apiInterceptor);
        }
    }
} 


