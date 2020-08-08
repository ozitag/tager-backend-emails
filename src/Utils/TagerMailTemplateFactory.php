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

        $result = new TagerMailTemplate();

        $result->setSubject($configTemplate['subject'] ?? null);
        $result->setBody($configTemplate['body'] ?? null);
        $result->setRecipients($configTemplate['recipients'] ?? []);
        $result->setTemplate($template, null, $configTemplate['serviceTemplate'] ?? null);

        return $result;
    }

    /**
     * @param $template
     * @return TagerMailTemplate|null
     */
    private function getTemplateFromDatabase($template)
    {
        $model = $this->templateRepository->findByTemplate($template);
        if (!$model) {
            return null;
        }

        $result = new TagerMailTemplate();
        $result->setSubject($model->subject);
        $result->setBody($model->body);
        $result->setRecipients($model->recipients ? explode(',', $model->recipients) : []);
        $result->setTemplate($model->template, $model->id, $model->service_template);

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
