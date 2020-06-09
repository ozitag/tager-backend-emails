<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use OZiTAG\Tager\Backend\Core\Controller;
use OZiTAG\Tager\Backend\Core\SuccessResource;
use OZiTAG\Tager\Backend\Admin\Resources\ProfileResource;
use OZiTAG\Tager\Backend\Mail\Features\ListMailTemplatesFeature;
use OZiTAG\Tager\Backend\Mail\Features\UpdateMailTemplateFeature;
use OZiTAG\Tager\Backend\Mail\Features\ViewMailTemplateFeature;

class AdminTemplatesController extends Controller
{
    public function index()
    {
        return $this->serve(ListMailTemplatesFeature::class);
    }

    public function view(Request $request)
    {
        return $this->serve(ViewMailTemplateFeature::class, [
            'template' => $request->get('template')
        ]);
    }

    public function update()
    {
        return $this->serve(UpdateMailTemplateFeature::class, [
            'template' => $request->get('template')
        ]);
    }
}
