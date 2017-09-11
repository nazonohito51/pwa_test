<?php
namespace App\Http\Response\ApiStatus;

class NotFoundStatus implements StatusInterface
{
    const HTTP_STATUS_CODE = 404;
    const API_STATUS_CODE = 'Not Found';

    public function getHttpStatusCode()
    {
        return self::HTTP_STATUS_CODE;
    }

    public function getApiStatusCode()
    {
        return self::API_STATUS_CODE;
    }
}
