<?php

namespace App\Utilities;

use Symfony\Component\HttpFoundation\Request;

/**
 * API Interceptor Interface
 * @package  Assignment
 */
interface ApiInterceptorInterface
{
    /**
     * Prepare Request DTO
     *
     * @param  Request $request
     * @param  string  $action
     */
    public function prepareRequestDto(Request $request);

    /**
     * Send Response
     * @param  int    $statusCode
     */
    public function sendResponse($responseDto, int $statusCode);
}