<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_active === true;
    }

    public function rules(): array
    {
        return [
            'target_type' => [
                'required',
                Rule::in([
                    'video',
                    'channel',
                    'comment',
                ]),
            ],

            'target_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'reason' => [
                'required',
                Rule::in([
                    'spam',
                    'harassment',
                    'hate_speech',
                    'violence',
                    'sexual_content',
                    'misinformation',
                    'copyright',
                    'other',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }
}