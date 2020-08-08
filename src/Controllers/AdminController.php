<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Features\InfoFeature;
use OZiTAG\Tager\Backend\Mail\Features\ListMailLogsFeature;
use OZiTAG\Tager\Backend\Mail\Features\ListMailTemplatesFeature;
use OZiTAG\Tager\Backend\Mail\Features\UpdateMailTemplateFeature;
use OZiTAG\Tager\Backend\Mail\Features\ViewMailTemplateFeature;

class AdminController extends Controller
{
    public function info()
    {
        return $this->serve(InfoFeature::class);
    }

    public function logs()
    {
        return $this->serve(ListMailLogsFeature::class);
    }

    public function templates()
    {
        return $this->serve(ListMailTemplatesFeature::class);
    }

    public function view($id)
    {
        return $this->serve(ViewMailTemplateFeature::class, [
            'id' => $id
        ]);
    }

    public function update($id)
    {
        return $this->serve(UpdateMailTemplateFeature::class, [
            'id' => $id
        ]);
    }
}
