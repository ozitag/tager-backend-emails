<?php

namespace OZiTAG\Tager\Backend\Mail\Requests;

use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;

/**
 * @property string[] $recipients
 * @property string[] $cc
 * @property string[] $bcc
 * @property string $subject
 * @property string $body
 * @property string $serviceTemplate
 * @property string $fromName
 * @property string $fromEmail
 */
class UpdateTemplateRequest extends CrudFormRequest
{
    public function rules()
    {
        return [
            'recipients' => 'nullable|array',
            'recipients.*' => 'email',
            'cc' => 'nullable|array',
            'cc.*' => 'email',
            'bcc' => 'nullable|array',
            'bcc.*' => 'email',
            'subject' => 'nullable|string',
            'body' => 'nullable|string',
            'serviceTemplate' => 'nullable|string',
            'fromName' => 'nullable|string',
            'fromEmail' => 'nullable|string|email',
        ];
    }
}
