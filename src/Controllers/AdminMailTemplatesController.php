<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use OZiTAG\Tager\Backend\Crud\Actions\StoreOrUpdateAction;
use OZiTAG\Tager\Backend\Crud\Controllers\AdminCrudController;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;
use OZiTAG\Tager\Backend\Mail\Requests\UpdateTemplateRequest;
use OZiTAG\Tager\Backend\Mail\Jobs\UpdateTemplateJob;
use OZiTAG\Tager\Backend\Mail\Utils\TagerMailConfig;

class AdminMailTemplatesController extends AdminCrudController
{
    protected $hasIndexAction = true;

    protected $hasViewAction = true;

    protected $hasStoreAction = false;

    protected $hasUpdateAction = true;

    protected $hasDeleteAction = false;

    protected $hasMoveAction = false;

    public function __construct(MailTemplateRepository $repository)
    {
        parent::__construct($repository);

        $this->setResourceFields([
            'id',
            'alias' => 'template',
            'name',
            'serviceTemplate' => 'service_template',
            'subject', 'body',
            'recipients' => function ($model) {
                return $model->recipients ? explode(',', $model->recipients) : [];
            },
            'variables' => function ($model) {
                return (new TagerMailConfig())->getTemplateVariables($model->template);
            }
        ], true);

        $this->setUpdateAction(new StoreOrUpdateAction(UpdateTemplateRequest::class, UpdateTemplateJob::class));
    }
}
