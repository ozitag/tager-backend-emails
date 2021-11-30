<?php

namespace OZiTAG\Tager\Backend\Mail\Admin\Controllers;

use OZiTAG\Tager\Backend\Crud\Actions\StoreOrUpdateAction;
use OZiTAG\Tager\Backend\Crud\Controllers\AdminCrudController;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Admin\Requests\UpdateTemplateRequest;
use OZiTAG\Tager\Backend\Mail\Jobs\UpdateTemplateJob;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;

class AdminMailTemplatesController extends AdminCrudController
{
    protected bool $hasIndexAction = true;

    protected bool $hasViewAction = true;

    protected bool $hasStoreAction = false;

    protected bool $hasUpdateAction = true;

    protected bool $hasDeleteAction = false;

    protected bool $hasMoveAction = false;

    public function __construct(MailTemplateRepository $repository)
    {
        parent::__construct($repository);

        $this->setResourceFields([
            'id',
            'alias' => 'template',
            'name',
            'serviceTemplate' => 'service_template',
            'fromName' => 'from_name',
            'fromEmail' => 'from_email',
            'subject', 'body', 'editorMode' => 'editor_mode',
            'recipients' => function (TagerMailTemplate $model) {
                return $model->recipients ? explode(',', $model->recipients) : [];
            },
            'cc' => function (TagerMailTemplate $model) {
                return $model->cc ? explode(',', $model->cc) : [];
            },
            'bcc' => function (TagerMailTemplate $model) {
                return $model->bcc ? explode(',', $model->bcc) : [];
            },
            'variables' => function ($model) {
                return (new TagerMailConfig())->getTemplateVariables($model->template);
            }
        ], true);

        $this->setUpdateAction(new StoreOrUpdateAction(UpdateTemplateRequest::class, UpdateTemplateJob::class));
    }
}
