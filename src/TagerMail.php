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

    private function createLog($to, $subject, $body, ?TagerMailTemplate $template = null, ?TagerMailAttachments $attachments = null)
    {
        $log = new TagerMailLog();
        $log->template_id = $template ? $template->id : null;
        $log->recipient = $to;
        $log->subject = $subject;
        $log->body = $body;
        $log->status = TagerMailStatus::Created;
        $log->attachments = $attachments ? $attachments->getLogString() : null;
        $log->save();
        return $log;
    }

    private function sendDebugMail($to, $subject, $body, ?TagerMailTemplate $template = null, ?TagerMailAttachments $attachments = null)
    {
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
            $logModel->id,
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

    public function sendMailUsingTemplate($template, $templateValues = [], $to = null, ?TagerMailAttachments $attachments = null)
    {
        $repository = new MailTemplateRepository(new TagerMailTemplate());

        $templateModel = $repository->findByTemplate($template);
        if (!$templateModel) {
            throw new TagerMailInvalidTemplateException();
        }

        if (empty($templateModel->subject) || empty($templateModel->body)) {
            throw new TagerMailInvalidTemplateException('Subject or Body is empty');
        }

        $params = $this->config()->getTemplateVariables($templateModel->template);

        $body = $templateModel->body;
        foreach ($params as $param) {
            $variable = $param['variable'];
            if (isset($templateValues[$variable])) {
                $body = str_replace('{{' . $variable . '}}', $templateValues[$variable], $body);
            }
        }

        if (!is_null($to)) {
            $recipients = is_array($to) ? $to : [$to];
        } else {
            $recipients = $templateModel->recipients ? explode(',', $templateModel->recipients) : [];
        }

        if (empty($recipients)) {
            return;
        }

        foreach ($recipients as $recipient) {
            if ($this->config()->isDebug()) {
                $this->sendDebugMail($recipient, $templateModel->subject, $body, $attachments, $templateModel);
            } else {
                $this->sendRealMail($recipient, $templateModel->subject, $body, $attachments, $templateModel);
            }
        }
    }
}
