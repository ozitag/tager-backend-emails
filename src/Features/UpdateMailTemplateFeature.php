<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Requests\UpdateTemplateRequest;
use OZiTAG\Tager\Backend\Mail\Resources\MailTemplateResource;

class UpdateMailTemplateFeature extends Feature
{
    private $alias;

    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    public function handle(UpdateTemplateRequest $request, MailTemplateRepository $repository)
    {
        $model = $repository->findByTemplate($this->alias);
        if (!$model) {
            abort(404, 'Template not found');
        }

        $model->subject = $request->subject;
        $model->body = $request->body;
        $model->recipients = implode(',', $request->recipients);
        $model->changed_by_admin = true;
        $model->save();

        return new MailTemplateResource($model);
    }
}
