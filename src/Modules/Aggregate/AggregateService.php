<?php

namespace App\Modules\Aggregate;

use App\Utilities\UtilityContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class AggregateService
{

    /**
     *
     * @var type
     */
    private $dataObject;

    /**
     *
     * @var string
     */
    private $subscriptionKey;

    private $logger;

    /**
     *
     * @param ContainerInterface $container
     * @param UtilityContainer $utility
     */
    public function __construct(ContainerInterface $container, UtilityContainer $utilityContainer)
    {
        $this->container = $container;
        $this->utilityContainer = $utilityContainer;
        $this->logger = $utilityContainer->getService('logger_service');

    }

    public function validateData(array $requestData): array
    {
        try {

            if (empty($requestData['calendarIds'])) {
                return [
                    "code" => Response::HTTP_NOT_FOUND,
                    "message" => "Please pass calendar ids"
                ];
            }
            if (empty($requestData['duration']) || !is_numeric($requestData['duration'])) {
                return [
                    "code" => Response::HTTP_NOT_FOUND,
                    "message" => "Please pass duration in minutes"
                ];
            }
            if (empty($requestData['periodToSearchStart']) && empty($requestData['periodToSearchEnd'])) {
                return [
                    "code" => Response::HTTP_NOT_FOUND,
                    "message" => "Please pass start date and end date"
                ];
            }

            return [];
        } catch (\Exception $ex) {
            $this->logger->writeLog('ERROR', 'Exception while validating Calendar request data' . $ex->getMessage() . 'at line - ' . $ex->getLine() . ' in file' . $ex->getFile());
            throw new \Exception($ex->getMessage());
        }
    }

    public function findAvailableTime(array $calendarIds, int $duration, $periodToSearchStart, $periodToSearchEnd, string $timeSlotType = null): array
    {
        try {
            $calendarData = json_decode($this->container->getParameter('CALENDAR_DATA'), true);
            foreach ($calendarIds as $calendarId) {
                $data = array_filter($calendarData, function ($item) use ($calendarId, $duration, $periodToSearchStart, $periodToSearchEnd, $timeSlotType) {
                    $appointmentData = array_filter($item['appointments'], function ($appointments) use ($calendarId, $periodToSearchStart, $periodToSearchEnd) {
                        return $appointments['calendar_id'] == $calendarId && ($appointments['start'] >= $periodToSearchStart && $appointments['end'] <= $periodToSearchEnd);
                    });
                    $timeSlotTypes = $item['timeslottypes'];
                    $slotTypes = array_filter($appointmentData, function ($appointment) use ($timeSlotTypes, $duration) {
                        $timeSlotTypesData = array_filter($timeSlotTypes, function ($timeSlotType) use ($appointment, $duration) {
                            if (is_null($timeSlotType)) {
                                if ($timeSlotType['id'] = $timeSlotType) {
                                    return $appointment['time_slot_type_id'] == $timeSlotType['id'] && $timeSlotType['slot_size'] == $duration;
                                }
                            } else {
                                return $appointment['time_slot_type_id'] == $timeSlotType['id'] && $timeSlotType['slot_size'] == $duration;
                            }
                        });
                        $appointment['timeslottypes'] = $timeSlotTypesData;
                        return $timeSlotTypesData;
                    });
                    return $slotTypes;
                });
            }
            if (!empty($data)) {
                return [
                    "code" => Response::HTTP_OK,
                    "message" => "Success",
                    "description" => $data
                ];
            } else {
                return [];
            }
        } catch (\Exception $ex) {
            $this->logger->writeLog('ERROR', 'Exception while validating Calendar request data' . $ex->getMessage() . 'at line - ' . $ex->getLine() . ' in file' . $ex->getFile());
            throw new \Exception($ex->getMessage());
        }
    }
}