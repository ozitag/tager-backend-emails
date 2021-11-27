<?php

namespace OZiTAG\Tager\Backend\Mail\Requests;

use Illuminate\Validation\Rules\Enum;
use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;
use OZiTAG\Tager\Backend\Mail\Enums\MailTemplateEditorMode;
use OZiTAG\Tager\Backend\Validation\Rule;

/**
 * @property string[] $recipients
 * @property string[] $cc
 * @property string[] $bcc
 * @property string $subject
 * @property string $body
 * @property string $editorMode
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
            'editorMode' => ['required', 'string', Rule::in(MailTemplateEditorMode::getValues())],
            'serviceTemplate' => 'nullable|string',
            'fromName' => 'nullable|string',
            'fromEmail' => 'nullable|string|email',
        ];
    }
}
