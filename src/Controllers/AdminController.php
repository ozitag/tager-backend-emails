<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use Illuminate\Http\Request;
use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Mail\Features\ListMailLogsFeature;
use OZiTAG\Tager\Backend\Mail\Features\ListMailTemplatesFeature;
use OZiTAG\Tager\Backend\Mail\Features\UpdateMailTemplateFeature;
use OZiTAG\Tager\Backend\Mail\Features\ViewMailTemplateFeature;

class AdminController extends Controller
{
    public function logs(Request $request)
    {
        return $this->serve(ListMailLogsFeature::class);
    }

    public function templates(Request $request)
    {
        $alias = $request->get('alias');

        if (!empty($alias)) {
            return $this->serve(ViewMailTemplateFeature::class, [
                'alias' => $alias
            ]);
        }

        return $this->serve(ListMailTemplatesFeature::class);
    }

    public function update(Request $request)
    {
        return $this->serve(UpdateMailTemplateFeature::class, [
            'alias' => $request->get('alias')
        ]);
    }
}
