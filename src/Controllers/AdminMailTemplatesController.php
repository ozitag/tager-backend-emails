<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Features\ListMailTemplatesFeature;
use OZiTAG\Tager\Backend\Mail\Features\UpdateMailTemplateFeature;
use OZiTAG\Tager\Backend\Mail\Features\ViewMailTemplateFeature;

class AdminMailTemplatesController extends Controller
{
    public function index()
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
