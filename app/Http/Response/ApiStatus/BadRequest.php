<?php
namespace App\Http\Response\ApiStatus;

class BadRequest implements StatusInterface
{
    const HTTP_STATUS_CODE = 400;
    const API_STATUS_CODE = 'Bad Request';

    public function getHttpStatusCode()
    {
        return self::HTTP_STATUS_CODE;
    }

    public function getApiStatusCode()
    {
        return self::API_STATUS_CODE;
    }
}
