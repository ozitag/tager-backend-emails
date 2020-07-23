<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;
use OZiTAG\Tager\Backend\Mail\Resources\MailLogResource;

class ListMailLogsFeature extends Feature
{
    public function handle(MailLogRepository $repository)
    {
        return MailLogResource::collection($repository->all());
    }
}
