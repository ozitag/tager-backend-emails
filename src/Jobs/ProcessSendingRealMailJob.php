<?php

namespace OZiTAG\Tager\Backend\Mail\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;
use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailSender;

class ProcessSendingRealMailJob extends QueueJob
{
    /** @var string */
    private $to;

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

    public function __construct($to, $subject, $body, $serviceTemplate = null, $templateFields = null, $logId = null, ?TagerMailAttachments $attachments = null, ?string $fromEmail = null, ?string $fromName = null)
    {
        $this->to = $to;
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
    private function isRecipientAllowed()
    {
        $validEmails = TagerMailConfig::getAllowedEmails();
        return $validEmails == '*' || in_array($this->to, $validEmails);
    }

    public function handle(TagerMailSender $sender)
    {
        if ($this->isRecipientAllowed() == false) {
            $this->setLogStatus(TagerMailStatus::Skip);
            return;
        }

        $this->setLogStatus(TagerMailStatus::Sending);

        try {
            if ($this->serviceTemplate) {
                $sender->sendUsingServiceTemplate($this->to, $this->serviceTemplate, $this->templateFields, $this->subject, $this->attachments, $this->fromEmail, $this->fromName, $this->logId);
            } else {
                $sender->send($this->to, $this->subject, $this->body, $this->attachments, $this->fromEmail, $this->fromName, $this->logId);
            }

        } catch (\Throwable $exception) {
            $this->setLogStatus(TagerMailStatus::Failure, $exception->getMessage());
        }
    }
}
