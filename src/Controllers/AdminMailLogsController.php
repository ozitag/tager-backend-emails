<?php

namespace OZiTAG\Tager\Backend\Mail\Controllers;

use OZiTAG\Tager\Backend\Crud\Actions\IndexAction;
use OZiTAG\Tager\Backend\Crud\Controllers\AdminCrudController;
use OZiTAG\Tager\Backend\Mail\Repositories\MailLogRepository;

class AdminMailLogsController extends AdminCrudController
{
    protected $hasIndexAction = true;

    protected $hasViewAction = false;

    protected $hasStoreAction = false;

    protected $hasUpdateAction = false;

    protected $hasDeleteAction = false;

    protected $hasMoveAction = false;

    public function __construct(MailLogRepository $repository)
    {
        parent::__construct($repository);

        $this->setResourceFields([
            'id', 'template',
            'serviceTemplate' => 'service_template',
            'recipient',
            'body' => function ($model) {
                return $model->body ?? $model->service_template_params;
            },
            'status', 'error',
            'attachments' => function ($model) {
                $attachments = [];
                if ($model->attachments) {
                    $attachmentsJson = json_decode($model->attachments, true);
                    if ($attachmentsJson) {
                        foreach ($attachmentsJson as $attachment) {
                            $attachments[] = [
                                'name' => $attachment['as'] ?? null,
                                'url' => $attachment['url'] ?? null
                            ];
                        }
                    }
                }
                return $attachments;
            },
            'createdAt' => 'created_at:datetime',
            'updatedAt' => 'created_at:datetime',
        ]);

        $this->setIndexAction((new IndexAction())->enablePagination());
    }
}
