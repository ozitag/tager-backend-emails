<?php

namespace OZiTAG\Tager\Backend\Mail;

use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidTemplateException;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailLog;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;
use OZiTAG\Tager\Backend\Mail\Jobs\ProcessSendingRealMailJob;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailExecutor;

class TagerMail
{
    /** @var TagerMailExecutor */
    private $executor;

    public function __construct(TagerMailExecutor $executor)
    {
        $this->executor = $executor;
    }

    public function sendMail($recipients, $subject, $body, ?TagerMailAttachments $attachments = null)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setSubject($subject);
        $this->executor->setBody($body);
        $this->executor->setAttachments($attachments);

        $this->executor->run();
    }

    public function sendMailUsingTemplate($template, $templateValues = [], $recipients = null, ?TagerMailAttachments $attachments = null)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setAttachments($attachments);
        $this->executor->setTemplate($template, $templateValues);

        if ($recipients) {
            $this->executor->setRecipients($recipients);
        }

        $this->executor->run();

        return;
    }
}
