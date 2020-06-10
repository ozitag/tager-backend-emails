<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Resources\MailLogResource;
use OZiTAG\Tager\Backend\Mail\Resources\MailTemplateResource;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;

class ListMailLogsFeature extends Feature
{
    public function handle(MailLogRepository $repository)
    {
        return MailLogResource::collection($repository->all());
    }
}
