<?php

namespace OZiTAG\Tager\Backend\Mail\Transports;

use Illuminate\Mail\Transport\Transport;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailSenderException;
use Sendpulse\RestApi\ApiClient;
use Swift_Mime_SimpleMessage;

class SendPulseTransport extends Transport
{
    protected ApiClient $sendPulseApiClient;

    public function __construct(ApiClient $sendPulseApiClient)
    {
        $this->sendPulseApiClient = $sendPulseApiClient;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $to = [];
        foreach ($message->getTo() as $email => $name) {
            $to[] = [
                'name' => $name,
                'email' => $email
            ];
        }

        $cc = [];
        foreach ($message->getCc() as $email => $name) {
            $cc[] = [
                'name' => $name,
                'email' => $email
            ];
        }

        $bcc = [];
        foreach ($message->getBcc() as $email => $name) {
            $bcc[] = [
                'name' => $name,
                'email' => $email
            ];
        }

        $messageFrom = $message->getFrom();
        $key = array_keys($messageFrom)[0];

        $emailData = [
            'attachments' => [],
            'subject' => $message->getSubject(),
            'html' => $message->getBody(),
            'to' => $to,
            'сс' => $cc,
            'bcc' => $bcc,
            'from' => [
                'name' => $messageFrom[$key],
                'email' => $key
            ]
        ];

        foreach ($message->getChildren() as $child) {
            $emailData['attachments'][$child->getFilename()] = base64_encode($child->getBody());
        }

        $result = $this->sendPulseApiClient->smtpSendMail($emailData);

        if (property_exists($result, 'is_error') && $result->is_error) {
            throw new TagerMailSenderException($result->message);
        }

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }
}
