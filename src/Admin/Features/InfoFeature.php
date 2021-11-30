<?php

namespace OZiTAG\Tager\Backend\Mail\Admin\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;

class InfoFeature extends Feature
{
    public function handle()
    {
        $service = TagerMailConfig::getService();

        return new JsonResource([
            'enabled' => !TagerMailConfig::isDisabled(),
            'allowedEmails' => TagerMailConfig::getAllowedEmails(),
            'subjectTemplate' => TagerMailConfig::getSubjectTemplate(),
            'service' => $service
        ]);
    }
}
