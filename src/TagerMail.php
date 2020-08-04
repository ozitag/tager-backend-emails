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

class TagerMail
{
    private function config()
    {
        return new TagerMailConfig();
    }

    /**
     * @return bool
     */
    private function isApplicationHasDatabase()
    {
        return $this->config()->hasDatabase();
    }

    private function createLog($to, $subject, $body, ?TagerMailTemplate $template = null, ?TagerMailAttachments $attachments = null)
    {
        if (!$this->isApplicationHasDatabase()) {
            return null;
        }

        $log = new TagerMailLog();

        $log->template_id = $template ? $template->id : null;
        $log->recipient = $to;
        $log->subject = $subject;
        $log->body = $body;
        $log->status = TagerMailStatus::Created;
        $log->attachments = $attachments ? $attachments->getLogString() : null;

        if (!$log->save()) {
            return null;
        }

        return $log;
    }

    private function sendDebugMail($to, $subject, $body, ?TagerMailAttachments $attachments = null, ?TagerMailTemplate $template = null)
    {
        if (!$this->isApplicationHasDatabase()) {
            return;
        }

        $logModel = $this->createLog($to, $subject, $body, $template, $attachments);
        $logModel->status = TagerMailStatus::Success;
        $logModel->debug = true;
        $logModel->save();
    }

    private function sendRealMail($to, $subject, $body, ?TagerMailAttachments $attachments = null, ?TagerMailTemplate $template = null)
    {
        $logModel = $this->createLog($to, $subject, $body, $template, $attachments);

        dispatch(new ProcessSendingRealMailJob(
            $to,
            $subject,
            $body,
            $logModel ? $logModel->id : null,
            $attachments
        ));
    }

    public function sendMail($to, $subject, $body, ?TagerMailAttachments $attachments = null)
    {
        if ($this->config()->isDebug()) {
            $this->sendDebugMail($to, $subject, $body, $attachments);
        } else {
            $this->sendRealMail($to, $subject, $body, $attachments);
        }
    }

    private function getMailParams($template)
    {
        if ($this->isApplicationHasDatabase()) {
            $repository = new MailTemplateRepository(new TagerMailTemplate());

            $templateModel = $repository->findByTemplate($template);
            if (!$templateModel) {
                throw new TagerMailInvalidTemplateException($template);
            }

            return [
                'subject' => $templateModel->subject,
                'body' => $templateModel->body,
                'recipients' => $templateModel->recipients ? explode(',', $templateModel->recipients) : [],
                'model' => $templateModel
            ];
        } else {
            $template = $this->config()->getTemplate($template);
            if (!$template) {
                throw new TagerMailInvalidTemplateException($template);
            }

            return [
                'subject' => $template['subject'] ?? null,
                'body' => $template['body'] ?? null,
                'recipients' => $template['recipients'] ?? [],
                'model' => null
            ];
        }
    }

    public function sendMailUsingTemplate($template, $templateValues = [], $to = null, ?TagerMailAttachments $attachments = null)
    {
        $templateParams = $this->getMailParams($template);

        if (empty($templateParams['subject']) || empty($templateParams['body'])) {
            throw new TagerMailInvalidTemplateException($template, 'Subject or Body is empty');
        }

        $body = $templateParams['body'];
        $subject = $templateParams['subject'];
        $recipients = $templateParams['recipients'];
        $model = $templateParams['model'];

        $params = $this->config()->getTemplateVariables($template);

        foreach ($params as $param) {
            $variable = $param['key'];
            if (isset($templateValues[$variable])) {
                $body = str_replace('{{' . $variable . '}}', $templateValues[$variable], $body);
            }
        }

        if (!is_null($to)) {
            $recipients = is_array($to) ? $to : [$to];
        }

        if (empty($recipients)) {
            return;
        }

        foreach ($recipients as $recipient) {
            if ($this->config()->isDebug()) {
                $this->sendDebugMail($recipient, $subject, $body, $attachments, $model);
            } else {
                $this->sendRealMail($recipient, $subject, $body, $attachments, $model);
            }
        }
    }
}
