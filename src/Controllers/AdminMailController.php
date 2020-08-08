<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Features\InfoFeature;
use OZiTAG\Tager\Backend\Mail\Features\ServiceTemplatesFeature;

class AdminMailController extends Controller
{
    public function info()
    {
        return $this->serve(InfoFeature::class);
    }

    public function serviceTemplates()
    {
        return $this->serve(ServiceTemplatesFeature::class);
    }
}
