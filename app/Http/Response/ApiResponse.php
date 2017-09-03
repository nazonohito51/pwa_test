<?php
namespace App\Http\Response;

use App\Http\Response\ApiStatus\StatusInterface;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;

class ApiResponse implements Responsable
{
    private $status;
    private $message;
    private $data;

    public function __construct(StatusInterface $status, $message = '', Jsonable $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public function status()
    {
        return $this->status->getHttpStatusCode();
    }

    public function toResponse($request)
    {
        return response()->json([
            'status' => $this->status->getApiStatusCode(),
            'message' => $this->message,
            'data' => $this->data ? $this->data : null
        ]);
    }
}
