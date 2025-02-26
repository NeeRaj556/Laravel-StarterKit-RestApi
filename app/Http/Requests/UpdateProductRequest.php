<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends BaseRequest
{

    public function rules(): array
    {

        return [
            'name' => ['required'],
            'details' => ['required'],
            'price' => ['required'],
            'image1' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'image2' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    // public function wantsJson()
    // {
    //     return $this->isJson() || $this->ajax() || $this->wantsJson();
    // }
}
