<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class BookRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'tag' => 'required',
            'book_name' => 'required',
            'book_desc' => 'required',
            'pen_name' => 'required',
            'book_thumb' => 'required',
            'book_thumb2' => 'required',
        ];
    }
}
