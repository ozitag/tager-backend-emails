<?php

namespace OZiTAG\Tager\Backend\Mail\Web\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

/**
 *
 * @property string $to
 * @property string $subject
 * @property string $body
 */
class SendMailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'to' => 'required|string|email',
            'subject' => 'required|string',
            'body' => 'required|string'
        ];
    }
}
