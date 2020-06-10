<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Resources\MailTemplateResource;

class ViewMailTemplateFeature extends Feature
{
    private $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function handle(MailTemplateRepository $repository)
    {
        $model = $repository->findByTemplate($this->template);
        if (!$model) {
            abort(404, 'Template not found');
        }

        return new MailTemplateResource($model);
    }
}
