<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Mail\Message;
use OZiTAG\Tager\Backend\Mail\Enums\MailStatus;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidServiceConfigException;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailSenderException;
use OZiTAG\Tager\Backend\Mail\Jobs\SetLogStatusJob;
use OZiTAG\Tager\Backend\Mail\Services\TagerMailServiceFactory;
use Illuminate\Support\Facades\Mail;

class TagerMailSender
{
    public function send($to, $cc, $bcc, $subject, $body, ?TagerMailAttachments $attachments = null, ?string $fromEmail = null, ?string $fromName = null, $logId = null)
    {
        try {
            Mail::send([], ['eventData' => ['logId' => $logId]], function (Message $message) use ($to, $cc, $bcc, $subject, $body, $attachments, $fromEmail, $fromName) {

                $message->html($body);
                $message->to($to);

                if ($cc) {
                    $message->cc($cc);
                }

                if ($bcc) {
                    $message->bcc($bcc);
                }

                $message->subject($subject);

                if ($fromEmail) {
                    $message->from($fromEmail, $fromName);
                }

                if ($attachments) {
                    $attachments->injectToMessage($message);
                }
            });
        } catch (\Exception $exception) {
            throw new TagerMailSenderException($exception->getMessage());
        }
    }

    public function sendUsingServiceTemplate($to, $cc, $bcc, $serviceTemplate, $serviceTemplateParams = null, $subject = null, ?TagerMailAttachments $attachments = null, ?string $fromEmail = null, ?string $fromName = null, $logId = null)
    {
        try {
            $service = TagerMailServiceFactory::create();
            if (!$service) {
                throw new TagerMailInvalidServiceConfigException('Service can not be initialized');
            }
            $service->sendUsingTemplate($to, $cc, $bcc, $serviceTemplate, $serviceTemplateParams, $subject, $attachments, $fromEmail, $fromName, $logId);
            dispatch(new SetLogStatusJob($logId, MailStatus::Success));
        } catch (\Exception $exception) {
            throw new TagerMailSenderException($exception->getMessage());
        }
    }
}
