<?php

namespace OZiTAG\Tager\Backend\Mail\Repositories;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;

class MailTemplateRepository extends EloquentRepository
{
    public function __construct(TagerMailTemplate $model)
    {
        parent::__construct($model);
    }

    public function findByTemplate($template)
    {
        return TagerMailTemplate::whereTemplate($template)->first();
    }
}
