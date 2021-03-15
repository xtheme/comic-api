<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Log;

final class UserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $validationData = $this->validationData();

        return [
            'username' => 'required|max:11',
            'mobile'   => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => '请填写用户昵称',
            'username.max'      => '昵称不能大于11个字',
            'mobile.required'   => '请填写用户手机号',
        ];
    }
}
