<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|unique:posts,title',
            'content' => 'required|string|min:10',
            'is_published' => 'boolean',
        ];
    }
}
