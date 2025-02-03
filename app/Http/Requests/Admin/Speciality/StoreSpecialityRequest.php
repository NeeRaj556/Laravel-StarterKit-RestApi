<?php

namespace App\Http\Requests\Admin\Speciality;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialityRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'status' => ['nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? 0,
        ]);
    }
}
