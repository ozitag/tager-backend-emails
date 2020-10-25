<?php

namespace OZiTAG\Tager\Backend\Mail;

use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailExecutor;

class TagerMail
{
    /** @var TagerMailExecutor */
    private $executor;

    public function __construct(TagerMailExecutor $executor)
    {
        $this->executor = $executor;
    }

    /**
     * @param string[] $recipients
     * @param string $subject
     * @param string $body
     * @param TagerMailAttachments|null $attachments
     */
    public function sendMail($recipients, $subject, $body, ?TagerMailAttachments $attachments = null)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setSubject($subject);
        $this->executor->setBody($body);
        $this->executor->setAttachments($attachments);

        $this->executor->run();
    }

    /**
     * @param string $template
     * @param string[] $templateValues
     * @param string[]|null $recipients
     * @param TagerMailAttachments|null $attachments
     * @throws Exceptions\TagerMailInvalidMessageException
     */
    public function sendMailUsingTemplate($template, $templateValues = [], $recipients = null, ?TagerMailAttachments $attachments = null)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setAttachments($attachments);
        $this->executor->setTemplate($template, $templateValues);

        if ($recipients) {
            $this->executor->setRecipients($recipients);
        }

        $this->executor->run();
    }
}
