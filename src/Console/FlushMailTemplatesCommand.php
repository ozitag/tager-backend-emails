<?php

namespace OZiTAG\Tager\Backend\Mail\Console;

use Illuminate\Console\Command;
use OZiTAG\Tager\Backend\Mail\Models\TagerMailTemplate;
use OZiTAG\Tager\Backend\Mail\Repositories\MailTemplateRepository;

class FlushMailTemplatesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tager:mail-flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync DB Mail templates with config';

    public function handle(MailTemplateRepository $repository)
    {
        $templates = config()->get('tager-mail.templates');

        if (!$templates) {
            $templates = [];
        }

        $added = [];

        $index = 1;
        foreach ($templates as $template => $data) {
            /** @var TagerMailTemplate $model */
            $model = $repository->findByTemplate($template);

            if (!$model) {
                $model = $repository->createModelInstance();
                $model->template = $template;
            }

            $model->priority = $index++;

            if ($model->changed_by_admin == false) {
                if (isset($data['recipients'])) {
                    if (is_array($data['recipients'])) {
                        $model->recipients = implode(',', $data['recipients']);
                    } else if (is_string($data['recipients'])) {
                        $model->recipients = $data['recipients'];
                    }
                }

                if (isset($data['cc'])) {
                    if (is_array($data['cc'])) {
                        $model->cc = implode(',', $data['cc']);
                    } else if (is_string($data['cc'])) {
                        $model->cc = $data['cc'];
                    }
                }

                if (isset($data['bcc'])) {
                    if (is_array($data['bcc'])) {
                        $model->bcc = implode(',', $data['bcc']);
                    } else if (is_string($data['bcc'])) {
                        $model->bcc = $data['bcc'];
                    }
                }

                $model->subject = $data['subject'] ?? '';
                $model->body = $data['body'] ?? '';
                $model->from_name = $data['fromName'] ?? null;
                $model->from_email = $data['fromEmail'] ?? null;

                $model->service_template = $data['serviceTemplate'] ?? null;
            }

            $model->name = $data['name'];
            $model->save();

            $added[] = $template;
        }

        $repository->builder()->whereNotIn('template', $added)->delete();
    }
}
