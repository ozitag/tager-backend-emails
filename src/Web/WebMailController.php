<?php

namespace OZiTAG\Tager\Backend\Mail\Web;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Features\Admin\InfoFeature;
use OZiTAG\Tager\Backend\Mail\Features\Admin\ServiceTemplatesFeature;
use OZiTAG\Tager\Backend\Mail\Web\Features\SendMailFeature;
use OZiTAG\Tager\Backend\Mail\Web\Features\SendMailTemplateFeature;

class WebMailController extends Controller
{
    public function send()
    {
        return $this->serve(SendMailFeature::class);
    }

    public function sendTemplate()
    {
        return $this->serve(SendMailTemplateFeature::class);
    }
}
