<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Resources\MailTemplateResource;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;

class ListMailTemplatesFeature extends Feature
{
    public function handle(MailTemplateRepository $repository)
    {
        return MailTemplateResource::collection($repository->all());
    }
}
