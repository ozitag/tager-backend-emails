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
        parent::__construct();
    }

    public function __toString(): string
    {
        return 'sendpulse';
    }

    private function getFilename($header) {
        if (preg_match('/Content-Disposition:.*?filename="(.+?)"/', $header, $matches)) {
            return $matches[1];
        }
        if (preg_match('/Content-Disposition:.*?filename=([^; ]+)/', $header, $matches)) {
            return rawurldecode($matches[1]);
        }
        throw new Exception(__FUNCTION__ .": Filename not found");
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
            'attachments_binary' => [],
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
            $data = $attachment->getPreparedHeaders()->get('Content-Disposition')->toString();
            $data = trim(preg_replace('/\s+/', '', $data));
            $data = str_replace('filename*0*=', 'filename=', $data);
            $data = preg_replace('#;filename\*\d+\*\=#si', '', $data);

            $filename = str_replace("utf-8''", '', $this->getFilename($data));
            $ind = 1;
            while (isset($emailData['attachments_binary'][$filename])) {
                $ind++;
                $filename = '(' . $ind . ')' . $filename;
            }

            $emailData['attachments_binary'][$filename] = base64_encode($attachment->getBody());
        }


        $result = $this->sendPulseApiClient->smtpSendMail($emailData);

        if (property_exists($result, 'is_error') && $result->is_error) {
            throw new TagerMailSenderException($result->message);
        }
    }
}
