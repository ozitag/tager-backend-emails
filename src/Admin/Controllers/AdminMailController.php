<?php

namespace OZiTAG\Tager\Backend\Mail\Admin\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Admin\Features\InfoFeature;
use OZiTAG\Tager\Backend\Mail\Admin\Features\ServiceTemplatesFeature;

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
