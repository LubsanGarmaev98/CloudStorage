<?php

namespace App\Http\Requests;

use App\Rules\MaxSizeDisk;
use App\Rules\NotPhpType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\File;

class StoreFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                new NotPhpType(),
                new MaxSizeDisk(),
                File::types([])->max(20 * 1024)
            ],
            'folder_id' => ['nullable', 'int', 'exists:folders,id'],
            'die_datetime' => ['nullable', 'date', 'after:now']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    public function messages(): array
    {
        return [
            'folderId.exists' => 'Папки с таким идентификатором не существует',
        ];
    }
}
