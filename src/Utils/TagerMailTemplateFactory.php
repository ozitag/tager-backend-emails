<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;

class TagerMailTemplateFactory
{
    private $templateRepository;

    public function __construct(MailTemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @param $template
     * @return TagerMailTemplate
     */
    private function getTemplateFromConfig($template)
    {
        $configTemplate = TagerMailConfig::getTemplate($template);
        if (!$configTemplate) {
            return null;
        }

        $result = new TagerMailTemplate();

        $result->setSubject($configTemplate['subject'] ?? null);
        $result->setBody($configTemplate['body'] ?? null);
        $result->setRecipients($configTemplate['recipients'] ?? []);
        $result->setCc($configTemplate['cc'] ?? []);
        $result->setBcc($configTemplate['bcc'] ?? []);
        $result->setTemplate($template, null, $configTemplate['serviceTemplate'] ?? null);
        $result->setFrom($template['fromName'] ?? null, $template['fromEmail'] ?? null);

        return $result;
    }

    /**
     * @param $template
     * @return TagerMailTemplate|null
     */
    private function getTemplateFromDatabase($template)
    {
        /** @var \OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate $model */
        $model = $this->templateRepository->findByTemplate($template);
        if (!$model) {
            return null;
        }

        $result = new TagerMailTemplate();

        $result->setSubject($model->subject);
        $result->setBody($model->body);
        $result->setRecipients($model->recipients ? explode(',', $model->recipients) : []);
        $result->setCc($model->cc ? explode(',', $model->cc) : []);
        $result->setBcc($model->bcc ? explode(',', $model->bcc) : []);
        $result->setTemplate($model->template, $model->id, $model->service_template);
        $result->setFrom($model->from_name, $model->from_email);

        return $result;
    }

    /**
     * @param $template
     * @return TagerMailTemplate
     */
    public function getTemplate($template)
    {
        if (TagerMailConfig::hasDatabase()) {
            $result = $this->getTemplateFromDatabase($template);
            if ($result) {
                return $result;
            }
        }

        return $this->getTemplateFromConfig($template);
    }
}
