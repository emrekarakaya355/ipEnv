<?php

namespace App\Http\Responses;

use App\Exceptions\CustomException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\QueryException;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ErrorResponse implements Responsable
{
    protected ?\Throwable $exception;
    protected string $message;

    protected int $statusCode;
    protected ?string $routeName;
    protected array $headers;

    public function __construct(\Throwable $exception = null, string $message = "Beklenmeyen Bir Hata Oluştu.", int $statusCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR, ?string $routeName = null, array $headers = [])
    {
        $this->exception = $exception;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->routeName = $routeName;
        $this->headers = $headers;
    }

    public function toResponse($request)
    {
        if($this->exception){
                //return response()->json(['success' => false,'message' => $this->message], $this->statusCode);
            if ($this->exception instanceof CustomException) {
                $this->message = $this->exception->getMessage();
                $this->statusCode =$this->exception->getCode();
            }
            //uniuqe hatası
            if ($this->isUniqueConstraintViolation($this->exception->getCode())) {
                if (str_contains($this->exception->getMessage(), 'Duplicate entry')) {
                    $this->message = "Bu verilere ait kayıt bulunmaktadır.";

                    $this->statusCode = ResponseAlias::HTTP_CONFLICT;
                } elseif (str_contains($this->exception->getMessage(), 'a foreign key constraint fails')) {
                    $this->message = "Başka Cihaz Tarafından Kullanıldığı için Silinemez.";
                    $this->statusCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
                }
            }

        }
        $response = [
            'success' => false,
            'message' => $this->message,
        ];
        if($this->exception &&config('app.debug') ){
                $response['debug'] = [
                    'message' => $this->exception->getMessage(),
                    'file' => $this->exception->getFile(),
                    'line' => $this->exception->getLine(),
                    'trace' => $this->exception->getTraceAsString(),
                ];
        }
        if ($request->expectsJson()) {
            return response()->json($response, $this->statusCode, $this->headers);
        } else {
            return back()
                ->with(['error' => $this->message])
                ->withHeaders($this->headers)
                ->setStatusCode($this->statusCode);
        }



    }

    private function isUniqueConstraintViolation(string $exceptionCode): bool
    {
        return $exceptionCode === '23000'; // 23000 is the SQLSTATE code for integrity constraint violation
    }

}

