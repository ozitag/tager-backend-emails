<?php

namespace OZiTAG\Tager\Backend\Mail\Jobs;

use Illuminate\Mail\Transport\MailgunTransport;
use Illuminate\Support\Facades\Mail;
use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;
use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Transports\SendPulseTransport;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailSender;

class ProcessSendingRealMailJob extends QueueJob
{
    /** @var string */
    private $to;

    /** @var string[] */
    private $cc;

    /** @var string[] */
    private $bcc;

    /** @var string */
    private $subject;

    /** @var string */
    private $body;

    /** @var string */
    private $serviceTemplate;

    /** @var array */
    private $templateFields;

    /** @var null|integer */
    private $logId;

    /** @var TagerMailAttachments|null */
    private $attachments = null;

    private ?string $fromName;

    private ?string $fromEmail;

    public function __construct($to, $cc, $bcc, $subject, $body, $serviceTemplate = null, $templateFields = null, $logId = null, ?TagerMailAttachments $attachments = null, ?string $fromEmail = null, ?string $fromName = null)
    {
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;

        $this->subject = $subject;
        $this->body = $body;
        $this->logId = $logId;
        $this->serviceTemplate = $serviceTemplate;
        $this->templateFields = $templateFields;
        $this->attachments = $attachments;

        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
    }

    private function setLogStatus($status, $error = null)
    {
        dispatch(new SetLogStatusJob($this->logId, $status, $error));
    }

    /**
     * @return bool
     */
    private function isRecipientAllowed(): bool
    {
        return $this->isEmailAllowed($this->to);
    }

    private function isEmailAllowed(string $email): bool
    {
        $validEmails = TagerMailConfig::getAllowedEmails();
        return $validEmails == '*' || in_array($email, $validEmails);
    }

    public function handle(TagerMailSender $sender)
    {
        if ($this->isRecipientAllowed() == false) {
            $this->setLogStatus(TagerMailStatus::Skip);
            return;
        }

        $ccFiltered = [];
        if ($this->cc) {
            foreach ($this->cc as $ccValue) {
                if ($this->isEmailAllowed($ccValue)) {
                    $ccFiltered[] = $ccValue;
                }
            }
        }

        $bccFiltered = [];
        if ($this->bcc) {
            foreach ($this->bcc as $bccValue) {
                if ($this->isEmailAllowed($bccValue)) {
                    $bccFiltered[] = $bccValue;
                }
            }
        }

        $this->setLogStatus(TagerMailStatus::Sending);

        try {
            $transport = Mail::getSwiftMailer()->getTransport();
        } catch (\Exception $exception) {
            $this->setLogStatus(TagerMailStatus::Failure, 'Transport Init Error: ' . $exception->getMessage());
            return;
        }

        if ($transport instanceof MailgunTransport) {
            if (empty(config('services.mailgun.domain'))) {
                $this->setLogStatus(TagerMailStatus::Failure, 'Mailgun domain is empty');
                return;
            }
        } else if ($transport instanceof SendPulseTransport) {
            if (empty($this->body)) {
                $this->body = ' ';
            }
        }

        try {
            if ($this->serviceTemplate) {
                $sender->sendUsingServiceTemplate($this->to, $ccFiltered, $bccFiltered, $this->serviceTemplate, $this->templateFields, $this->subject, $this->attachments, $this->fromEmail, $this->fromName, $this->logId);
            } else {
                $sender->send($this->to, $ccFiltered, $bccFiltered, $this->subject, $this->body, $this->attachments, $this->fromEmail, $this->fromName, $this->logId);
            }
        } catch (\Throwable $exception) {
            $this->setLogStatus(TagerMailStatus::Failure, $exception->getMessage());
        }
    }
}
