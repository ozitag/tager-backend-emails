<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

class TagerMailConfig
{
    public static function getTemplate($template)
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
     *
     */
    public static function hasDatabase()
    {
        return !(boolean)config('tager-mail.no_database', false);
    }

    /**
     * @return bool
     */
    public static function isDisabled()
    {
        return (boolean)config('tager-mail.disabled', false);
    }

    /**
     * @return string
     */
    public static function getSubjectTemplate()
    {
        return (string)config('tager-mail.subject_template', '{subject}');
    }

    /**
     * @return string
     */
    public static function getService()
    {
        return (string)config('mail.default');
    }

    /**
     * @return string|null
     */
    public static function getMandrillSecret()
    {
        return config('services.mandrill.secret');
    }

    /**
     * @return array|string
     */
    public static function getAllowedEmails()
    {
        $value = config('tager-mail.allow_emails');

        if (is_null($value) || $value == '*') {
            return '*';
        }

        if (!$value) {
            return [];
        }

        return is_array($value) ? $value : explode(',', $value);
    }


}
