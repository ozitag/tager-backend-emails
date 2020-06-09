<?php

namespace OZiTAG\Tager\Backend\Mail\Utils;

use Illuminate\Support\Facades\Auth;
use OZiTAG\Tager\Backend\Core\Controller;
use OZiTAG\Tager\Backend\Core\SuccessResource;
use OZiTAG\Tager\Backend\Admin\Resources\ProfileResource;

class TagerMailConfig
{
    public function getTemplates()
    {

    }

    public function isTemplateExists($template)
    {
        return false;
    }
}
