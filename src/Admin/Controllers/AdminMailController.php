<?php

namespace OZiTAG\Tager\Backend\Mail\Admin\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Features\Admin\InfoFeature;
use OZiTAG\Tager\Backend\Mail\Features\Admin\ServiceTemplatesFeature;

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
