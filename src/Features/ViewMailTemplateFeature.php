<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Resources\MailTemplateResource;

class ViewMailTemplateFeature extends Feature
{
    private $alias;

    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    public function handle(MailTemplateRepository $repository)
    {
        $model = $repository->findByTemplate($this->alias);
        if (!$model) {
            abort(404, 'Template not found');
        }

        return new MailTemplateResource($model);
    }
}
