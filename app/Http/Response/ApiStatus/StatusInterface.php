<?php
namespace App\Http\Response\ApiStatus;

interface StatusInterface
{
    public function getHttpStatusCode();

    public function getApiStatusCode();
}
