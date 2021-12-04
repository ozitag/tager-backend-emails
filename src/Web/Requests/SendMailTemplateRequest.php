<?php

namespace OZiTAG\Tager\Backend\Mail\Web\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

/**
 *
 * @property string $to
 * @property string $template
 * @property array $params
 * @property array $attachments
 */
class SendMailTemplateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'to' => 'required|string|email',
            'template' => 'required|string',
            'params' => 'present|array',
            'params.*.name' => 'required|string',
            'params.*.value' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*.name' => 'required|string',
            'attachments.*.mime' => 'required|string',
            'attachments.*.url' => 'required|string|url',
        ];
    }
}
