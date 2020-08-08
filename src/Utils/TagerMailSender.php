<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidServiceConfigException;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailSenderException;
use OZiTAG\Tager\Backend\Mail\Jobs\SetLogStatusJob;
use OZiTAG\Tager\Backend\Mail\Services\TagerMailServiceFactory;

class TagerMailSender
{
    public function send($to, $subject, $body, ?TagerMailAttachments $attachments = null, $logId = null)
    {
        try {
            Mail::send([], ['eventData' => ['logId' => $logId]], function (Message $message) use ($to, $subject, $body, $attachments) {
                $message->setBody($body, 'text/html', 'UTF-8');
                $message->setTo($to);
                $message->setSubject($subject);
                if ($attachments) {
                    $attachments->injectToMessage($message);
                }
            });
        } catch (\Exception $exception) {
            throw new TagerMailSenderException($exception->getMessage());
        }
    }

    public function sendUsingServiceTemplate($to, $serviceTemplate, $serviceTemplateParams = null, $subject = null, ?TagerMailAttachments $attachments = null, $logId = null)
    {
        try {
            $service = TagerMailServiceFactory::create();
            if(!$service){
                throw new TagerMailInvalidServiceConfigException('Service can not be initialized');
            }
            $service->sendUsingTemplate($to, $serviceTemplate, $serviceTemplateParams, $subject, $attachments, $logId);
            dispatch(new SetLogStatusJob($logId, TagerMailStatus::Success));
        } catch (\Exception $exception) {
            throw new TagerMailSenderException($exception->getMessage());
        }
    }
}
