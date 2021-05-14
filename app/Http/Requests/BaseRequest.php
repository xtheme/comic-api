<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Return custom error format for backward compatible
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        /*
         * Custom return format
         * {
         *      code: 400,
         *      msg: [
         *          'error message 1',
         *          'error message 2',
         *          'error message 3',
         *      ]
         * }
         */

        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();

            $httpStatus = 200;
            $messages = collect($errors)->flatten();

            throw new HttpResponseException(
                response()->json([
                    'code' => 500,
                    'msg' => $messages[0]
                ], $httpStatus)
            );
        }

        parent::failedValidation($validator);
    }
}
