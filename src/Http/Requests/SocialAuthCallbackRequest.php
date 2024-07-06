<?php

namespace R64\SocialAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAuthCallbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'access_token' => 'required|string',
        ];
    }
}
