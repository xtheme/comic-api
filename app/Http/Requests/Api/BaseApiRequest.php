<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class BaseApiRequest extends BaseRequest
{
    /**
     * Return custom error format for backward compatible
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        $messages = collect($errors)->flatten();

        throw new HttpResponseException(
            Response::jsonError($messages[0], 422)
        );
    }
}
