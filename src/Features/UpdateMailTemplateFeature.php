<?php

namespace OZiTAG\Tager\Backend\Mail\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Requests\UpdateTemplateRequest;
use OZiTAG\Tager\Backend\Mail\Resources\MailTemplateResource;

class UpdateMailTemplateFeature extends Feature
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle(UpdateTemplateRequest $request, MailTemplateRepository $repository)
    {
        $model = $repository->find($this->id);
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
