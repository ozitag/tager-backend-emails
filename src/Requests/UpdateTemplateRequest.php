<?php

namespace OZiTAG\Tager\Backend\Mail\Requests;

use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;

class UpdateTemplateRequest extends CrudFormRequest
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
