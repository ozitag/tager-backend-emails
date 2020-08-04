<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use OZiTAG\Tager\Backend\Mail\Enums\TagerMailStatus;
use OZiTAG\Tager\Backend\Mail\Exceptions\TagerMailInvalidMessageException;
use OZiTAG\Tager\Backend\Mail\Jobs\ProcessSendingRealMailJob;
use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;

class TagerMailExecutor
{
    private $recipients = [];

    private $subject;

    private $body;

    private $attachments = null;

    private $template = null;

    private $templateFields = [];

    /** @var TagerMailTemplateFactory */
    private $templateFactory;

    /** @var MailLogRepository */
    private $logRepository;

    public function __construct(TagerMailTemplateFactory $templateFactory, MailLogRepository $mailLogRepository)
    {
        $this->templateFactory = $templateFactory;
        $this->logRepository = $mailLogRepository;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

    public function setSubject($value)
    {
        $this->subject = $value;
    }

    public function setBody($value)
    {
        $this->body = $value;
    }

    public function setAttachments(?TagerMailAttachments $attachments = null)
    {
        $this->attachments = $attachments;
    }

    public function setTemplate($template, $templateFields)
    {
        $this->template = $template;
        $this->templateFields = $templateFields;
    }

    /**
     * @return array|array[]
     */
    private function getRecipients()
    {
        if (!$this->recipients) {
            if ($this->template) {
                return $this->getTemplateInstance()->getRecipients();
            }
            return [];
        }

        if (is_array($this->recipients)) {
            return $this->recipients;
        }

        return [$this->recipients];
    }

    private function prepareSubject($subject)
    {
        $subjectTemplate = TagerMailConfig::getSubjectTemplate();

        if (!$subjectTemplate) {
            return $this->subject;
        }

        return \str_replace('{subject}', $subject, $subjectTemplate);
    }

    private function prepareBody($body, $templateFields = [])
    {
        if (is_array($templateFields)) {
            foreach ($templateFields as $param => $value) {
                $body = str_replace('{{' . $param . '}}', $value, $body);
            }
        }

        return $body;
    }

    /**
     * @return TagerMailTemplate
     * @throws TagerMailInvalidMessageException
     */
    private function getTemplateInstance()
    {
        $template = $this->templateFactory->getTemplate($this->template);
        if (!$template) {
            throw new TagerMailInvalidMessageException('Template not found');
        }

        return $template;
    }

    private function getSubject()
    {
        if (!$this->template) {
            $result = $this->subject;
        } else {
            $result = $this->getTemplateInstance()->getSubject();
        }

        return $this->prepareSubject($result);
    }

    private function getBody()
    {
        if (!$this->template) {
            $result = $this->body;
        } else {
            $result = $this->getTemplateInstance()->getBody();
        }

        return $this->prepareBody($result, $this->templateFields);
    }

    private function createLogItem($recipient, $subject, $body, $status = TagerMailStatus::Created)
    {
        if (TagerMailConfig::hasDatabase() == false) {
            return null;
        }

        $this->logRepository->reset();

        return $this->logRepository->fillAndSave([
            'recipient' => $recipient,
            'subject' => $subject,
            'body' => $body,
            'status' => $status,
            'template_id' => $this->getTemplateInstance()->getDatabaseId(),
            'template' => $this->getTemplateInstance()->getTemplateName()
        ]);
    }

    private function send($recipient, $subject, $body)
    {
        if (TagerMailConfig::isDisabled()) {
            $this->createLogItem($recipient, $subject, $body, TagerMailStatus::Disabled);
            return;
        }

        $log = $this->createLogItem($recipient, $subject, $body, TagerMailStatus::Created);

        dispatch(new ProcessSendingRealMailJob(
            $recipient,
            $subject,
            $body,
            $log->id,
            $this->attachments
        ));
    }

    public function run()
    {
        $subject = $this->getSubject();
        if (empty($subject)) {
            throw new TagerMailInvalidMessageException('Subject is empty');
        }

        $body = $this->getBody();

        $recipients = $this->getRecipients();

        foreach ($recipients as $recipient) {
            $this->send($recipient, $subject, $body);
        }
    }
}
