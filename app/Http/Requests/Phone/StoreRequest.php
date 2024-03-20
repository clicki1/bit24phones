<?php

namespace App\Http\Requests\Phone;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
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
            'format' => 'required|string',
            'automatic' => 'string',
            'member_id' => 'required|string|exists:App\Models\Setting,member_id',
        ];
    }

//
    public function failedValidation(Validator $validator)
    {
        $data = $validator->validated();
        $member_id = $data['member_id'];
        $automatic = $data['automatic'] ?? '';
        $format = $data['format'] ?? '';
        $errors = $validator->errors();
        throw new HttpResponseException(response(view('btx.index_phone', compact('member_id', 'automatic', 'format' , 'errors'))));
    }

    public function messages(): array
    {
        return [
            'format.required' => 'A format is required',
            'format.string' => 'A format is string',
            'automatic.string' => 'A automatic is required',
            'member_id.string' => 'A member_id must be string',
            'member_id.required' => 'A member_id is required',
        ];
    }
}
