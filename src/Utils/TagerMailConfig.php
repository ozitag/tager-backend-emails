<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Support\Facades\Auth;
use OZiTAG\Tager\Backend\Core\Controller;
use OZiTAG\Tager\Backend\Core\SuccessResource;
use OZiTAG\Tager\Backend\Admin\Resources\ProfileResource;

class TagerMailConfig
{
    /**
     * @param string $template
     * @return array
     */
    public function getTemplateVariables($template)
    {
        $config = config('tager-mail.templates');

        $template = $config[$template] ?? null;
        if (!$template) {
            return [];
        }

        $params = $template['templateParams'] ?? [];

        $result = [];
        foreach ($params as $name => $label) {
            $result[] = [
                'variable' => $name,
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
}
