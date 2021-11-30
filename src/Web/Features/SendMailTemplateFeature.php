<?php

namespace OZiTAG\Tager\Backend\Mail\Web\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\SuccessResource;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidMessageException;
use OZiTAG\Tager\Backend\Mail\TagerMail;
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

        try {
            $tagerMail->sendMailUsingTemplate($request->template, $params, $request->to);
        } catch (TagerMailInvalidMessageException $exception) {
            Validation::throw('template', 'Invalid template');
        }

        return new SuccessResource();
    }
}
