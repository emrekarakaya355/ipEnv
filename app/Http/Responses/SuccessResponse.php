<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SuccessResponse implements Responsable
{
    protected mixed $data;
    protected array $metadata;

    protected int $statusCode;

    protected array $headers;
    protected string $message;
    protected ?string $routeName;




    public function __construct(string $message = "İşlem Başarı İle Tamamlandı.",
                                mixed $data = null,
                                ?string $routeName = null,
                                array $metadata = [],
                                int $statusCode = ResponseAlias::HTTP_OK, $headers = [])
    {
        $this->data = $data;
        $this->metadata = $metadata;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->message = $message;
        $this->routeName = $routeName;
    }

    public function toResponse($request)
    {
        // Eğer AJAX isteği ise JSON döndür
        if ($request->expectsJson()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => $this->message,
                    'data' => $this->data,
                    'redirect_url' => $this->routeName
                ], $this->statusCode, $this->headers
            );
        } else {
            // Eğer routeName belirtilmişse redirect et, belirtilmemişse bir önceki sayfaya yönlendir
            return redirect()->route($this->routeName ?? 'fallback.route.name')->with([
                'success' => $this->message,
                'data' => $this->data,
            ]);
        }
    }
}
