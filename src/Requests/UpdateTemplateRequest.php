<?php

namespace OZiTAG\Tager\Backend\Mail\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'recipients' => 'nullable',
            'recipients.*' => 'email',
            'subject' => 'nullable|string',
            'body' => 'nullable|string',
            'serviceTemplate' => 'nullable|string',
        ];
    }
}
