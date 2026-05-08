<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . ($this->route('category') ?? 'NULL'),
            'slug' => 'required|string|max:255|unique:categories,slug,' . ($this->route('category') ?? 'NULL'),        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
            'name.unique' => 'Nama kategori sudah terdaftar.',

            'slug.max' => 'Slug kategori tidak boleh lebih dari 255 karakter.',
            'slug.unique' => 'Slug kategori sudah terdaftar.',
        ];
    }
}
