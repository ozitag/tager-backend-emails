<?php

namespace OZiTAG\Tager\Backend\Mail\Web\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\SuccessResource;
use OZiTAG\Tager\Backend\Mail\TagerMail;
use OZiTAG\Tager\Backend\Mail\Web\Requests\SendMailRequest;

class SendMailFeature extends Feature
{
    public function handle(SendMailRequest $request, TagerMail $tagerMail)
    {
        $tagerMail->sendMail($request->to, $request->subject, $request->body);

        return new SuccessResource();
    }
}
