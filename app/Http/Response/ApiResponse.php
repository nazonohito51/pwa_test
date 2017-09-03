<?php
namespace App\Http\Response;

use App\Http\Response\ApiStatus\StatusInterface;
use Illuminate\Contracts\Support\Responsable;

class ApiResponse implements Responsable
{
    private $status;
    private $message;

    public function __construct(StatusInterface $status, $message = '')
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function status()
    {
        return $this->status->getHttpStatusCode();
    }

    public function toResponse($request)
    {
        return response()->json([
            'status' => $this->status->getApiStatusCode(),
            'message' => $this->message
        ]);
    }
}
