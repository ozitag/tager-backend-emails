<?php

namespace OZiTAG\Tager\Backend\Mail\Repositories;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailLog;

class MailLogRepository extends EloquentRepository
{
    public function __construct(TagerMailLog $model)
    {
        parent::__construct($model);
    }
}
