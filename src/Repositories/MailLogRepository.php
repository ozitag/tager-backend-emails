<?php

namespace OZiTAG\Tager\Backend\Mail\Repositories;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailLog;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;

class MailLogRepository extends EloquentRepository
{
    public function __construct(TagerMailLog $model)
    {
        parent::__construct($model);
    }
}
