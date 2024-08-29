<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\Validator;

class ValidatorResponse implements Responsable
{

    protected Validator $validator;
    protected string $message;



    public function __construct(Validator $validator,?string $message = '')
    {
        $this->validator = $validator;
        $this->message = $message;

    }

    public function toResponse($request)
    {

        // Get all error messages and flatten them into a single array
        $messages = collect($this->validator->errors()->all())->flatten()->all();
        // Join all messages into a single string, separated by a line break or any other delimiter
        $errorMessage = implode(' ', $messages);
        if ($request->expectsJson()) {

            $response = [
                'success' => false,
                'message' => $errorMessage
            ];
            return response()->json($response, 422);
        } else {

            // Eğer routeName belirtilmişse redirect et, belirtilmemişse bir önceki sayfaya yönlendir
            return back()
                ->withInput()
                ->with(['error' => $this->message ?: $errorMessage]);
        }
    }
}
