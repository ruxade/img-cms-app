<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users
    }

    public function rules()
    {
        return [
            'image' => 'required|image|max:10240', // Max size 10MB
            'caption' => 'nullable|string|max:100',
            'visibility' => 'required|in:public,private',
        ];
    }
}
