<?php
namespace App\Http\Response;

use App\Http\Response\ApiStatus\StatusInterface;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

class ApiResponse implements Responsable
{
    private $status;
    private $message;
    private $data;

    public function __construct(StatusInterface $status, $message = '', $data = null)
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
        $ret = [
            'status' => $this->status->getApiStatusCode(),
            'message' => $this->message,
        ];

        if ($this->data) {
            foreach ($this->data as $key => $value) {
                if (is_subclass_of($value, Model::class)) {
                    $value = $value->toArray();
                }
                $ret[$key] = $value;
            }
        }

        return response()->json($ret);
    }
}
