<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Support\Facades\Auth;
use OZiTAG\Tager\Backend\Core\Controller;
use OZiTAG\Tager\Backend\Core\SuccessResource;
use OZiTAG\Tager\Backend\Admin\Resources\ProfileResource;

class TagerMailConfig
{
    public function getConfigTemplates()
    {
        $config = config('tager-mail.templates');

        $result = [];
        foreach ($config as $id => $template) {
            $result[] = ['id' => $id, 'title' => $template['title']];
        }

        return $result;
    }

    public function isTemplateExists($template)
    {
        return false;
    }
}
