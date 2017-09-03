<?php
namespace App\Http\Response\ApiStatus;

class SuccessStatus implements StatusInterface
{
    const HTTP_STATUS_CODE = 200;
    const API_STATUS_CODE = 'Success';

    public function getHttpStatusCode()
    {
        return self::HTTP_STATUS_CODE;
    }

    public function getApiStatusCode()
    {
        return self::API_STATUS_CODE;
    }
}
