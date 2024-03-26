<?php

namespace OZiTAG\Tager\Backend\Mail\Admin\Requests;

use Illuminate\Validation\Rules\Enum;
use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;
use OZiTAG\Tager\Backend\Mail\Enums\MailTemplateEditorMode;

/**
* @property string $recipient
* @property string $template
* @property array $params
 */
class SendTestEmailRequest extends CrudFormRequest
{
    public function rules()
    {
        return [
            'recipient' => 'required|string|email',
            'template' => 'required|string',
            'params' => 'present|array',
            'params.*.param' => 'required|string',
            'params.*.value' => 'nullable|string',
        ];
    }
}
