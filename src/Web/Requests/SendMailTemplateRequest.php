<?php

namespace OZiTAG\Tager\Backend\Mail\Web\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

/**
 *
 * @property string $template
 * @property array $params
 * @property string $to
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
        ];
    }
}
