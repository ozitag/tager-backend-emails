<?php

namespace OZiTAG\Tager\Backend\Mail\Requests;

use Ozerich\FileStorage\Rules\FileRule;
use OZiTAG\Tager\Backend\Core\FormRequest;

class UpdateTemplateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'recipients' => 'nullable',
            'recipients.*' => 'email',
            'subject' => 'required|string',
            'body' => 'required|string'
        ];
    }
}
