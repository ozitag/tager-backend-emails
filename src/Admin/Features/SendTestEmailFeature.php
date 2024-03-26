<?php

namespace OZiTAG\Tager\Backend\Mail\Admin\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\SuccessResource;
use OZiTAG\Tager\Backend\Core\Validation\Facades\Validation;
use OZiTAG\Tager\Backend\Mail\Admin\Requests\SendTestEmailRequest;
use OZiTAG\Tager\Backend\Mail\Services\TagerMailServiceFactory;
use OZiTAG\Tager\Backend\Mail\TagerMail;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;
use OZiTAG\Tager\Backend\Mail\Web\Requests\SendMailTemplateRequest;

class SendTestEmailFeature extends Feature
{
    public function handle(SendTestEmailRequest $request, TagerMail $mail)
    {
        $template = TagerMailConfig::getTemplate($request->template);
        if(!$template){
            Validation::throw('template', 'Template not found');
        }

        $values = [];
        foreach($request->params as $param){
            $values[$param['param']] = $param['value'];
        }

        $mail->sendMailUsingTemplate($request->template, $values, $request->recipient);

        return new SuccessResource();
    }
}
