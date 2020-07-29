<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Resources\MailTemplateResource;

class ViewMailTemplateFeature extends Feature
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle(MailTemplateRepository $repository)
    {
        $model = $repository->find($this->id);
        if (!$model) {
            abort(404, 'Template not found');
        }

        return new MailTemplateResource($model);
    }
}
