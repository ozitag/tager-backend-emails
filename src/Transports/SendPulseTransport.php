<?php

namespace OZiTAG\Tager\Backend\Mail\Transports;

use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailSenderException;
use Sendpulse\RestApi\ApiClient;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class SendPulseTransport extends AbstractTransport
{
    protected ApiClient $sendPulseApiClient;

    public function __construct(ApiClient $sendPulseApiClient)
    {
        $this->sendPulseApiClient = $sendPulseApiClient;
    }

    public function __toString(): string
    {
        return 'sendpulse';
    }

    protected function doSend(SentMessage $message): void
    {
        $message = MessageConverter::toEmail($message->getOriginalMessage());

        $to = [];

        foreach ($message->getTo() as $toItem) {
            $to[] = [
                'name' => $toItem->getName(),
                'email' => $toItem->getAddress()
            ];
        }

        $cc = [];
        if ($message->getCc()) {
            foreach ($message->getCc() as $email => $name) {
                $cc[] = [
                    'name' => $name,
                    'email' => $email
                ];
            }
        }

        $bcc = [];
        if ($message->getBcc()) {
            foreach ($message->getBcc() as $email => $name) {
                $bcc[] = [
                    'name' => $name,
                    'email' => $email
                ];
            }
        }

        $messageFrom = $message->getFrom();
        $key = array_keys($messageFrom)[0];

        $emailData = [
            'attachments' => [],
            'subject' => $message->getSubject(),
            'html' => $message->getHtmlBody(),
            'to' => $to,
            'сс' => $cc,
            'bcc' => $bcc,
            'from' => [
                'name' => $messageFrom[$key]->getName(),
                'email' => $messageFrom[$key]->getAddress(),
            ]
        ];

        foreach ($message->getAttachments() as $attachment) {
            $emailData['attachments_binary'][$attachment->getContentId()] = base64_encode($attachment->getBody());
        }

        $result = $this->sendPulseApiClient->smtpSendMail($emailData);

        if (property_exists($result, 'is_error') && $result->is_error) {
            throw new TagerMailSenderException($result->message);
        }
    }
}
