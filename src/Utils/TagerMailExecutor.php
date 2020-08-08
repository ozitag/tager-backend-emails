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

    private function createLogItem($recipient, $status = TagerMailStatus::Created)
    {
        if (TagerMailConfig::hasDatabase() == false) {
            return null;
        }

        $templateInstance = $this->getTemplateInstance();

        $body = $serviceTemplate = $serviceTemplateParams = null;

        if ($templateInstance->getServiceTemplate()) {
            $serviceTemplate = $templateInstance->getServiceTemplate();
            $serviceTemplateParams = $this->templateFields;
        } else {
            $body = $this->getBody();
        }

        $subject = $this->getSubject();

        $this->logRepository->reset();

        return $this->logRepository->fillAndSave([
            'recipient' => $recipient,
            'subject' => $subject,
            'body' => $body,
            'status' => $status,
            'template_id' => $templateInstance ? $templateInstance->getDatabaseId() : null,
            'template' => $templateInstance ? $templateInstance->getTemplateName() : null,
            'service_template' => $serviceTemplate,
            'service_template_params' => $serviceTemplateParams ? json_encode($serviceTemplateParams) : null
        ]);
    }

    private function send($recipient)
    {
        if (TagerMailConfig::isDisabled()) {
            $this->createLogItem($recipient, TagerMailStatus::Disabled);
            return;
        }

        $log = $this->createLogItem($recipient, TagerMailStatus::Created);

        dispatch(new ProcessSendingRealMailJob(
            $recipient,
            $this->getSubject(),
            $this->getBody(),
            $this->getTemplateInstance()->getServiceTemplate(),
            $this->templateFields,
            $log->id,
            $this->attachments
        ));
    }

    private function validate()
    {
        if ($this->getTemplateInstance() == null || $this->getTemplateInstance()->getServiceTemplate() == null) {
            $subject = $this->getSubject();
            if (empty($subject)) {
                throw new TagerMailInvalidMessageException('Subject is empty');
            }
        }
    }

    public function run()
    {
        $this->validate();

        $recipients = $this->getRecipients();
        foreach ($recipients as $recipient) {
            $this->send($recipient);
        }
    }
}
