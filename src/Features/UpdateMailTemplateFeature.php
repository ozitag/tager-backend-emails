<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;

class UpdateMailTemplateFeature extends Feature
{
    private $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function handle(MailTemplateRepository $repository)
    {
        return TagerMailTemplate::collection($repository->all());
    }
}
