<?php

namespace OZiTAG\Tager\Backend\Mail\Web\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\SuccessResource;
use OZiTAG\Tager\Backend\Mail\TagerMail;
use OZiTAG\Tager\Backend\Mail\Web\Requests\SendMailTemplateRequest;

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

        $tagerMail->sendMailUsingTemplate($request->template, $params, $request->to);

        return new SuccessResource();
    }
}
