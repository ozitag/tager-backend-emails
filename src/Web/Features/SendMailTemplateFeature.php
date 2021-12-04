<?php

namespace OZiTAG\Tager\Backend\Mail\Web\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\SuccessResource;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidMessageException;
use OZiTAG\Tager\Backend\Mail\TagerMail;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;
use OZiTAG\Tager\Backend\Mail\Web\Requests\SendMailTemplateRequest;
use OZiTAG\Tager\Backend\Validation\Facades\Validation;

class SendMailTemplateFeature extends Feature
{
    public function handle(SendMailTemplateRequest $request, TagerMail $tagerMail)
    {
        $params = [];

        if ($request->params) {
            foreach ($request->params as $param) {
                if (!isset($param['name'])) continue;
                $params[$param['name']] = $param['value'] ?? null;
            }
        }

        $attachments = null;
        if ($request->attachments && !empty($request->attachments)) {
            $attachments = new TagerMailAttachments();
            foreach ($request->attachments as $ind => $attachment) {
                $attachments->add(null, $attachment['name'], $attachment['mime'], $attachment['url']);
            }
        }

        try {
            $tagerMail->sendMailUsingTemplate($request->template, $params, $request->to, $attachments);
        } catch (TagerMailInvalidMessageException $exception) {
            Validation::throw('template', 'Invalid template');
        }

        return new SuccessResource();
    }
}
