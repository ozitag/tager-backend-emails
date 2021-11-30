<?php

namespace OZiTAG\Tager\Backend\Mail\Admin\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Mail\Services\TagerMailServiceFactory;

class ServiceTemplatesFeature extends Feature
{
    public function handle()
    {
        $result = [];

        $service = TagerMailServiceFactory::create();
        if ($service) {
            $result = $service->getTemplates();
        }

        return new JsonResource($result);
    }
}
