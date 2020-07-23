<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

class TagerMailConfig
{
    public function getTemplate($template)
    {
        $config = config('tager-mail.templates');

        $template = $config[$template] ?? null;
        if (!$template) {
            return [];
        }

        return $template;
    }

    /**
     * @param string $template
     * @return array
     */
    public function getTemplateVariables($template)
    {
        $template = $this->getTemplate($template);

        $params = $template['templateParams'] ?? [];

        $result = [];
        foreach ($params as $name => $label) {
            $result[] = [
                'key' => $name,
                'label' => $label
            ];
        }

        return $result;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return (boolean)config('tager-mail.debug', false);
    }

    /**
     *
     */
    public function hasDatabase()
    {
        return !(boolean)config('tager-mail.no_database', false);
    }
}
