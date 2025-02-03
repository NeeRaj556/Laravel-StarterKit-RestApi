<?php

namespace App\Http\Requests\Admin\Speciality;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecialityRequest extends BaseRequest
{

    public function rules(): array
    {

        return [
            'name' => ['required'],
            'status' => ['nullable'],
        ];
    }


    // public function wantsJson()
    // {
    //     return $this->isJson() || $this->ajax() || $this->wantsJson();
    // }
}
