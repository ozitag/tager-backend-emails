<?php

namespace OZiTAG\Tager\Backend\Mail;

use OZiTAG\Tager\Backend\Mail\Utils\TagerMailAttachments;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailExecutor;

class TagerMail
{
    private TagerMailExecutor $executor;

    public function __construct(TagerMailExecutor $executor)
    {
        $this->executor = $executor;
    }

    public function setFrom(string $fromEmail, string $fromName): self
    {
        $this->executor->setFrom($fromEmail, $fromName);
        return $this;
    }

    /**
     * @param string|string[] $cc
     * @return $this
     */
    public function setCc($cc): self
    {
        $this->executor->setCc($cc);
        return $this;
    }

    /**
     * @param string|string[] $to
     * @return $this
     */
    public function setTo($to): self
    {
        $this->executor->setRecipients($to);
        return $this;
    }

    /**
     * @param string|string[] $bcc
     * @return $this
     */
    public function setBcc($bcc): self
    {
        $this->executor->setBcc($bcc);
        return $this;
    }

    public function sendMail(string|string[] $to, string $subject, string $body, ?TagerMailAttachments $attachments = null)
    {
        $this->executor->setSubject($subject);
        $this->executor->setBody($body);
        $this->executor->setAttachments($attachments);
        $this->executor->setRecipients(is_array($to) ? $to : [$to]);

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
        $this->executor->setAttachments($attachments);
        $this->executor->setTemplate($template, $templateValues);

        if ($recipients) {
            $this->executor->setRecipients($recipients);
        }

        $this->executor->run();
    }
}
